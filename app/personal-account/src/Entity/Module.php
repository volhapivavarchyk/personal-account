<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="module", indexes={@ORM\Index(name="search_idx", columns={"name"})})
 */
class Module
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /** @ORM\Column(length=128) **/
    protected $name;
    /** @ORM\Column(length=128) **/
    protected $description;
    /** @ORM\Column(length=128) **/
    protected $url;
    /**
     * @ORM\ManyToOne(targetEntity="TypeModule", inversedBy="modules", cascade={"persist"})
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=true)
     */
    protected $type;
    /**
     * @ORM\ManyToMany(targetEntity="Functionality", inversedBy="modules", cascade={"persist"})
     * @ORM\JoinTable(name="modules_functionality")
     */
    protected $functionalities;
    /**
     * @ORM\OneToMany(targetEntity="Module", mappedBy="parent", cascade={"persist"})
     */
    protected $children;
    /**
     * @ORM\ManyToOne(targetEntity="Module", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    public function __construct()
    {
        $this->functionalities = new ArrayCollection();
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
     * $description getter
     * @return string $description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
    /**
     * $description setter
     * @param string $description
     * @return void
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
    /**
     * $url getter
     * @return string $url
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
    /**
     * $url setter
     * @param string $url
     * @return void
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }
    /**
     * $type getter
     * @return TypeModule|null $type
     */
    public function getType(): ?TypeModule
    {
        return $this->type;
    }
    /**
     * $type setter
     * @param TypeModule|null $type
     * @return void
     */
    public function setType(TypeModule $type = null): void
    {
        $type->addModule($this);
        $this->type = $type;
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
     * @return Module|null $module
     */
    public function getParent(): ?Module
    {
        return $this->parent;
    }
    /**
     * $parent setter
     * @param Module|null $parent
     * @return void
     */
    public function setParent(Module $parent = null): void
    {
        $parent->addChild($this);
        $this->parent = $parent;
    }
}