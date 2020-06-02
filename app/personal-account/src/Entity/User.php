<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user", indexes={@ORM\Index(name="search_idx", columns={"email", "firstname", "lastname"})})
 * @UniqueEntity(fields="username", message="Username already taken")
 * @UniqueEntity(fields="email", message="Email already taken")
 */
class User implements UserInterface, \Serializable
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /**
     * @ORM\Column(length=128, unique=true)
     * @Assert\NotBlank()
     */
    protected $username;
    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank()
     */
    protected $password;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    protected $passwordRepeat;
    /**
     * @ORM\Column(length=64)
     * @Assert\NotBlank()
     */
    protected $firstname;
    /**
     * @ORM\Column(length=64)
     * @Assert\NotBlank()
     */
    protected $lastname;
    /** @ORM\Column(length=64) **/
    protected $patronymic;
    /**
     * @ORM\Column(length=128)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;
    /**
     * @ORM\ManyToOne(targetEntity="UserKind", inversedBy="users")
     */
    protected $userkind;
    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="users")
     */
    protected $role;
    /**
     * @ORM\OneToMany(targetEntity="UserPosition", mappedBy="user")
     */
    protected $positions;
    /**
     * @ORM\OneToMany(targetEntity="UserStudentGroup", mappedBy="user")
     */
    protected $studentGroups;
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
        $this->studentGroups = new ArrayCollection();
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
     * $firstname getter
     * @return string $firstname
     */
    /**
     * $username getter
     * @return string $login
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }
    /**
     * $username setter
     * @param string $username
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
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
     * $passwordRepeat getter
     * @return string $passwordRepeat
     */
    public function getPasswordRepeat(): ?string
    {
        return $this->passwordRepeat;
    }
    /**
     * $passwordRepeat setter
     * @param string $passwordRepeat
     * @return void
     */
    public function setPasswordRepeat(string $passwordRepeat)
    {
        $this->passwordRepeat = password_hash($passwordRepeat);
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }
    /**
     * $firstname setter
     * @param string $firstname
     * @return void
     */
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;
    }
    /**
     * $lastname getter
     * @return string $lastname
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }
    /**
     * $lastname setter
     * @param string $lastname
     * @return void
     */
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;
    }
    /**
     * $patronymic getter
     * @return string $patronymic
     */
    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }
    /**
     * $patronymic setter
     * @param string $patronymic
     * @return void
     */
    public function setPatronymic(string $patronymic)
    {
        $this->patronymic = $patronymic;
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
     * $userkind getter
     * @return UserKind|null $userkind
     */
    public function getUserKind(): ?UserKind
    {
        return $this->userkind;
    }
    /**
     * $userkind setter
     * @param UserKind|null $userkind
     * @return void
     */
    public function setUserKind(UserKind $userkind = null): void
    {
        $userkind->addUser($this);
        $this->userkind = $userkind;
    }
    /**
     * $role getter
     * @return array|null $role
     */
    public function getRoles(): array
    {
        return $this->role == null ? ['ROLE_GUEST'] : [$this->role->getName()];
    }
    public function getRole(): Role
    {
        return $this->role;
    }
    public function setRole(Role $role = null)
    {
        $role->addUser($this);
        $this->role = $role;
    }
    public function getPositions(): Collection
    {
        return $this->positions;
    }
    public function addPosition(UserPosition $position): void
    {
        if (!$this->positions->contains($position)) {
            $this->positions[] = $position;
        }
    }
    public function removePosition(UserPosition $position): void
    {
        $this->positions->removeElement($position);
    }
    public function getStudentGroups(): Collection
    {
        return $this->studentGroups;
    }
    public function addStudentGroup(UserStudentGroup $studentGroup): void
    {
        if (!$this->studentGroups->contains($studentGroup)) {
            $this->studentGroups[] = $studentGroup;
        }
    }
    public function removeStudentGroup(UserStudentGroup $studentGroup): void
    {
        $this->studentGroups->removeElement($studentGroup);
    }

    public function getInterests(): Collection
    {
        return $this->interests;
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
    public function getHistories(): ArrayCollection
    {
        return $this->histories;
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
    public function getMessages(): ArrayCollection
    {
        return $this->messages;
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
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }
    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }
    /**
     * convert properties to array
     * @return array of class properties
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
    public function serialize(): string
    {
        return serialize([$this->id, $this->username, $this->password]);
    }
    public function unserialize($serialized): void
    {
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }
}