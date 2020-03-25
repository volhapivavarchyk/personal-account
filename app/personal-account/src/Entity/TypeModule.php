<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Serializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="typemodule", indexes={@ORM\Index(name="search_idx", columns={"name"})})
 */
class TypeModule implements Serializable
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /** @ORM\Column(length=128) **/
    protected $name;
    /**
     * @ORM\OneToMany(targetEntity="Module", mappedBy="type")
     */
    protected $modules;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
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
     * @param Module $modules
     * @param Module $module
     * @return TypeModule
     */
    public function addModule(Module $module): self
    {
        if (!$this->modules->contains($module)) {
            $this->modules[] = $module;
        }
        return $this;
    }
    public function removeModule(Module $module): self
    {
        $this->modules->removeElement($module);
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