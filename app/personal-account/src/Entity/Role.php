<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="roles", indexes={@ORM\Index(name="search_idx", columns={"name"})})
 */
class Role extends UserInterface, \Serializable
{
    /** @ORM\Id @ORM\Column(name="id_role", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $idRole;
    /** @ORM\Column(length=128) **/
    protected $rolename;
    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="role")
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
        return $this->idRole;
    }
    /**
     * $name getter
     * @return string $rolename
     */
    public function getRolename(): ?string
    {
        return $this->rolename;
    }
    /**
     * $name setter
     * @param string $rolename
     * @return void
     */
    public function setRolename(string $rolename)
    {
        $this->username = $rolename;
    }
    /**
     * @param User $user
     * @return Role
     */
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            //$user->setRole($this);
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
        return serialize([$this->idRole, $this->rolename]);
    }
    public function unserialize($serialized): void
    {
        [$this->idRole, $this->rolename] = unserialize($serialized, ['allowed_classes' => false]);
    }
}