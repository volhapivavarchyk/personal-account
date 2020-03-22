<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="user", indexes={@ORM\Index(name="search_idx", columns={"email"})})
 */
class User extends UserInterface, \Serializable
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /** @ORM\Column(length=128) **/
    protected $username;
    /** @ORM\Column(length=128) **/
    protected $email;
    /** @ORM\Column(length=128) **/
    protected $login;
    /** @ORM\Column(type="string", length=128) **/
    protected $password;
    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="id", nullable=true)
     */
    protected $role;
    /**
     * @ORM\ManyToMany(targetEntity="Position", inversedBy="users", cascade={"persist"})
     * @ORM\JoinTable(name="users_positions")
     */
    protected $positions;
    /**
     * @ORM\OneToMany(targetEntity="Interest", mappedBy="user")
     */
    protected $interests;
    /**
     * @ORM\OneToMany(targetEntity="History", mappedBy="user")
     */
    protected $histories;
    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="user")
     */
    protected $messages;
    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $apiToken;

    public function __construct()
    {
        $this->positions = new ArrayCollection();
        $this->interests = new ArrayCollection();
        $this->histories = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }
    /**
     * $id getter
     * @return integer $id
     */
    public function getId()
    {
        return $this->idUser;
    }
    /**
     * $name getter
     * @return string $name
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }
    /**
     * $name setter
     * @param string $name
     * @return void
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }
    /**
     * $email getter
     * @return string $email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    /**
     * $email setter
     * @param string $email
     * @return void
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
    /**
     * $password getter
     * @return string $login
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }
    /**
     * $password setter
     * @param string $login
     * @return void
     */
    public function setLogin(string $login)
    {
        $this->password = $login;
    }
    /**
     * $password getter
     * @return string $password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
     /**
     * $password setter
     * @param string $password
     * @return void
     */
    public function setPassword(string $password)
    {
        $this->password = password_hash($password);
    }
    /**
     * $role getter
     * @return Role|null $role
     */
    public function getRole(): ?Role
    {
       return $this->role;
    }
    /**
     * $role setter
     * @param Role|null $role
     * @return void
     */
    public function setRole(Role $role = null): void
    {
        $role->addUser($this);
        $this->role = $role;
    }
    public function addPosition(Position $position): self
    {
        if (!$this->positions->contains($position)) {
            $this->position[] = $positiont;
        }
        return $this;
    }
    public function removePosition(POsition $position): self
    {
        $this->positions->removeElement($position);
    }

    public function addInterest(Interest $interest): self
    {
        if (!$this->interests->contains($interest)) {
            $this->interests[] = $interest;
        }
        return $this;
    }
    public function removeInterest(Interest $interest): self
    {
        $this->interests->removeElement($interest);
    }
    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
        }
        return $this;
    }
    public function removeHistory(History $history): self
    {
        $this->histories->removeElement($history);
    }
    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
        }
        return $this;
    }
    public function removeMessage(Message $message): self
    {
        $this->messages->removeElement($message);
    }
    /**
     * convert properties to array
     * @return array of class properties
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}