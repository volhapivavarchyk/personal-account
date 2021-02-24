<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Serializable;

/**
 * @ORM\Entity(repositoryClass="VP\PersonalAccount\Repository\UserKindRepository")
 * @ORM\Table(name="userkind", indexes={@ORM\Index(name="search_idx", columns={"name"})})
 */
class UserKind implements Serializable
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /** @ORM\Column(length=128) **/
    protected $name;
    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="userkind")
     */
    protected $users;


    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * $users getter
     * @return Collection $users
     */
    public function getUsers(): ?Collection
    {
        return $this->users;
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
             $this->users[] = $user;
        }
        return $this;
    }
    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);
    }

    public function serialize(): string
    {
        return serialize([$this->id, $this->name]);
    }
    public function unserialize($serialized): void
    {
        [$this->id, $this->name] = unserialize($serialized, ['allowed_classes' => false]);
    }
    public function __toString()
    {
        return $this->getName();
    }
}