<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="faculty", indexes={@ORM\Index(name="search_idx", columns={"name"})})
 */
class Faculty implements \Serializable
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /** @ORM\Column(length=128) **/
    protected $name;
    /** @ORM\Column(length=128) **/
    protected $shortName;
    /**
     * @ORM\OneToMany(targetEntity="Speciality", mappedBy="faculty")
     */
    protected $specialities;

    public function __construct()
    {
        $this->specialities = new ArrayCollection();
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
     * $specialities getter
     * @return Collection|null $specialities
     */
    public function getSpecialities(): ?Collection
    {
        return $this->specialities;
    }
    /**
     * @param Speciality $speciality
     * @return Speciality
     */
    public function addSpeciality(Speciality $speciality): void
    {
        if (!$this->specialities->contains($speciality)) {
            $speciality->addSpeciality($this);
            $this->specialities[] = $speciality;
        }
    }
    public function removeSpeciality(Speciality $speciality): void
    {
        $this->specialities->removeElement($speciality);
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