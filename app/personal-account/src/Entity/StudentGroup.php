<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="studentgroup", indexes={@ORM\Index(name="search_idx", columns={"name"})})
 */
class StudentGroup implements \Serializable
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /** @ORM\Column(length=128) **/
    protected $name;
    /**
     * @ORM\OneToMany(targetEntity="UserStudentGroup", mappedBy="studentGroup")
     */
    protected $users;
    /**
     * @ORM\ManyToOne(targetEntity="Speciality", inversedBy="studentGroups")
     * @ORM\JoinColumn(name="speciality_id", referencedColumnName="id", nullable=true)
     */
    protected $speciality;

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
     * $users getter
     * @return Collection|null $users
     */
    public function getUsers(): ?Collection
    {
        return $this->users;
    }
    /**
     * @param UserStudentGroup $user
     * @return void
     */
    public function addUser(UserStudentGroup $user): void
    {
        if (!$this->users->contains($user)) {
            $user->addStudentGroup($this);
            $this->users[] = $user;
        }
    }
    public function removeUser(UserStudentGroup $user): void
    {
        $this->users->removeElement($user);
    }
    /**
     * $speciality getter
     * @return Speciality|null $cpeciality
     */
    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }
    /**
     * $speciality setter
     * @param Speciality|null $speciality
     * @return void
     */
    public function setSpeciality(Speciality $speciality = null): void
    {
        $speciality->addGroup($this);
        $this->speciality = $speciality;
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
        return $this->getId().'. '.$this->getName();
    }

}