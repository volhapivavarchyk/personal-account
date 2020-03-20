<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="modules", indexes={@ORM\Index(name="search_idx", columns={"name"})})
 */
class Module extends UserInterface, \Serializable
{
    /** @ORM\Id @ORM\Column(name="id_module", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $idmodule;
    /** @ORM\Column(length=128) **/
    protected $name;
    /** @ORM\Column(length=128) **/
    protected $description;
    /** @ORM\Column(length=128) **/
    protected $address;
    /**
     * @ORM\ManyToOne(targetEntity="ModuleType", inversedBy="modules", cascade={"persist"})
     * @ORM\JoinColumn(name="id_moduletype", referencedColumnName="id_moduletype", nullable=true)
     */
    protected $type;
    /**
     * @ORM\ManyToMany(targetEntity="Function", inversedBy="positions", cascade={"persist"})
     * @ORM\JoinColumn(name="id_position", referencedColumnName="id_posiiton", nullable=true)
     */
    protected $functions;
}