<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Serializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="role", indexes={@ORM\Index(name="search_idx", columns={"name"})})
 */
class Role implements Serializable
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /** @ORM\Column(length=128) **/
    protected $name;
    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     */
    protected $users;
    /**
     * @ORM\OneToMany(targetEntity="Role", mappedBy="parent")
     */
    protected $children;
    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->children = new ArrayCollection();
    }
    /**
     * $id getter
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * $name getter
     * @return string $name
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    /**
     * $name setter
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
    /**
     * @param User $user
     * @return Role
     */
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $user->addRole($this);
            $this->users[] = $user;
        }
        return $this;
    }
    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);
    }
    public function getChildren(): Collection
    {
        return $this->children;
    }
    public function addChild(Module $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
        }
        return $this;
    }
    public function removeChild(Module $child): self
    {
        $this->children->removeElement($child);
    }

    /**
     * $parent getter
     * @return Role|null $module
     */
    public function getParent(): ?Role
    {
        return $this->parent;
    }
    /**
     * $parent setter
     * @param Role|null $parent
     * @return void
     */
    public function setParent(Role $parent = null): void
    {
        $parent->addChild($this);
        $this->parent = $parent;
    }

    public function serialize(): string
    {
        return serialize([$this->id, $this->name]);
    }
    public function unserialize($serialized): void
    {
        [$this->id, $this->name] = unserialize($serialized, ['allowed_classes' => false]);
    }
}