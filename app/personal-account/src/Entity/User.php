<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="users", indexes={@ORM\Index(name="search_idx", columns={"email"})})
 */
class User extends UserInterface, \Serializable
{
    /** @ORM\Id @ORM\Column(name="id_user", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $iduser;
    /** @ORM\Column(length=128) **/
    protected $username;
    /** @ORM\Column(length=128) **/
    protected $email;
    /** @ORM\Column(type="string", length=128) **/
    protected $password;
    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="id_role", referencedColumnName="id_role", nullable=true)
     */
    protected $role;
    /**
     * @ORM\ManyToMany(targetEntity="Position", inversedBy="positions", cascade={"persist"})
     * @ORM\JoinColumn(name="id_position", referencedColumnName="id_posiiton", nullable=true)
     */
    protected $positions;
    /**
     * @ORM\ManyToMany(targetEntity="Interest", inversedBy="interests", cascade={"persist"})
     * @ORM\JoinColumn(name="id_interest", referencedColumnName="id_interest", nullable=true)
     */
    protected $interests;
    /**
     * @ORM\ManyToMany(targetEntity="History", inversedBy="hystories", cascade={"persist"})
     * @ORM\JoinColumn(name="id_hystory", referencedColumnName="id_history", nullable=true)
     */
    protected $histories;
    /**
     * @ORM\ManyToMany(targetEntity="Position", inversedBy="position", cascade={"persist"})
     * @ORM\JoinColumn(name="id_position", referencedColumnName="id_posiiton", nullable=true)
     */

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="user")
     */
    protected $messages;
    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $apiToken;

}