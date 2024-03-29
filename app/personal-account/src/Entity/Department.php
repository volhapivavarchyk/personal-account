<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Serializable;

/**
 * @ORM\Entity(repositoryClass="VP\PersonalAccount\Repository\DepartmentRepository")
 * @ORM\Table(name="department", indexes={@ORM\Index(name="search_idx", columns={"name"})})
 */
class Department implements Serializable
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /** @ORM\Column(length=128) **/
    protected $name;
    /**
     * @ORM\OneToMany(targetEntity="Position", mappedBy="department")
     */
    protected $positions;
    /**
     * @ORM\OneToMany(targetEntity="Department", mappedBy="parent")
     */
    protected $children;
    /**
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    public function __construct()
    {
        $this->positions = new ArrayCollection();
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
     * $positions getter
     * @return string $positions
     */
    public function getPositions(): Collection
    {
        return $this->positions;
    }
    public function addPosition(Position $position): self
    {
        if (!$this->positions->contains($position)) {
            $this->positions[] = $position;
        }
        return $this;
    }
    public function removePosition(Position $position): self
    {
        $this->positions->removeElement($position);
    }
    /**
     * $parent getter
     * @return Department|null $parent
     */
    public function getParent(): ?Department
    {
        return $this->parent;
    }
    /**
     * $parent setter
     * @param Department|null $parent
     * @return void
     */
    public function setParent(Department $parent = null): void
    {
        $parent->addChild($this);
        $this->parent = $parent;
    }
    /**
     * $children getter
     * @return Collection|null $children
     */
    public function getChildren(): ?Collection
    {
        return $this->children;
    }
    /**
     * $children setter
     * @return void
     */
    public function addChild(Department $child): void
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
        }
    }
    public function removeChild(Module $child): void
    {
        $this->children->removeElement($child);
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