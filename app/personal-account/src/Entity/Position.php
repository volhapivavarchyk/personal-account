<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="position", indexes={@ORM\Index(name="search_idx", columns={"name"})})
 */
class Position implements \Serializable
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /** @ORM\Column(length=128) **/
    protected $name;
    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="positions")
     */
    protected $users;
    /**
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="positions")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id", nullable=true)
     */
    protected $department;

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
     * @return Position
     */
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $user->addPosition($this);
            $this->users[] = $user;
        }
        return $this;
    }
    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);
    }
    /**
     * $department getter
     * @return Department|null $role
     */
    public function getDepartment(): ?Department
    {
        return $this->department;
    }
    /**
     * $department setter
     * @param Department|null $department
     * @return void
     */
    public function setDepartment(Department $department = null): void
    {
        $department->addPosition($this);
        $this->department = $department;
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