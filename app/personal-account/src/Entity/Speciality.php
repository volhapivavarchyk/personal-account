<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="speciality", indexes={@ORM\Index(name="search_idx", columns={"name"})})
 */
class Speciality implements \Serializable
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /** @ORM\Column(length=128) **/
    protected $name;
    /** @ORM\Column(length=128) **/
    protected $shortName;
    /** @ORM\Column(length=64) **/
    protected $code;
    /**
     * @ORM\OneToMany(targetEntity="StudentsGroup", mappedBy="speciality")
     */
    protected $studentsGroups;
    /**
     * @ORM\ManyToOne(targetEntity="Faculty", inversedBy="specialities")
     * @ORM\JoinColumn(name="faculty_id", referencedColumnName="id", nullable=true)
     */
    protected $faculty;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
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
     * $shortName getter
     * @return string $shortName
     */
    public function getShortName(): ?string
    {
        return $this->shortName;
    }
    /**
     * $shortName setter
     * @param string $shortName
     * @return void
     */
    public function setShortName(string $shortName)
    {
        $this->shortName = $shortName;
    }
    /**
     * $code getter
     * @return string $code
     */
    public function getCode(): ?string
    {
        return $this->code;
    }
    /**
     * $code setter
     * @param string $code
     * @return void
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }
    /**
     * $studentsGroups getter
     * @return Collection|null $studentsGroups
     */
    public function getStudentsGroups(): ?Collection
    {
        return $this->studentsGroups;
    }
    /**
     * @param StudentsGroup $studentsGroup
     * @return void
     */
    public function addStudentsGroup(StudentsGroup $studentsGroup): void
    {
        if (!$this->studentsGroups->contains($studentsGroup)) {
            $studentsGroup->setSpeciality($this);
            $this->studentsGroups[] = $studentsGroup;
        }
    }
    public function removeStudentsGroup(StudentsGroup $studentsGroup): self
    {
        $this->studentsGroups->removeElement($studentsGroup);
    }
    /**
     * $faculty getter
     * @return Faculty|null $faculty
     */
    public function getFaculty(): ?Faculty
    {
        return $this->faculty;
    }
    /**
     * $faculty setter
     * @param Faculty|null $faculty
     * @return void
     */
    public function setFaculty(Faculty $faculty = null): void
    {
        $faculty->addSpeciality($this);
        $this->faculty = $faculty;
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