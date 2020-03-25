<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="algorithm", indexes={@ORM\Index(name="search_idx", columns={"name"})})
 */
class Algorithm
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /** @ORM\Column(length=128) **/
    protected $name;
    /** @ORM\Column(type="text", length=256) **/
    protected $content;
    /**
     * @ORM\OneToMany(targetEntity="Functionality", mappedBy="formula", cascade={"persist"})
     */
    protected $functionalities;

    public function __construct()
    {
        $this->functionalities = new ArrayCollection();
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

    public function addFunctionality(Functionality $functionality): self
    {
        if (!$this->functionalities->contains($functionality)) {
            $this->functionalities[] = $functionality;
        }
        return $this;
    }
    public function removeFunctionality(Functionality $functionality): self
    {
        $this->functionalities->removeElement($functionality);
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