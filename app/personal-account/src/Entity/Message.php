<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Serializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="message", indexes={@ORM\Index(name="search_idx", columns={"name"})})
 */
class Message implements Serializable
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /** @ORM\Column(length=128) **/
    protected $name;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messages", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

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
     * $user getter
     * @return User|null $user
     */
    public function getUser(): ?User
{
    return $this->user;
}
    /**
     * $user setter
     * @param User|null $user
     * @return void
     */
    public function setUser(User $user = null): void
{
    $user->addMessage($this);
    $this->user = $user;
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