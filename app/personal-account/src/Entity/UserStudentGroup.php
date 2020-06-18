<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Entity;

use \DateTime;
use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_studentgroup")
 */
class UserStudentGroup
{
    /** @ORM\Id @ORM\Column(name="id", type="integer", unique=true, nullable=true) @ORM\GeneratedValue**/
    protected $id;
    /** @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime
     * @var string A "dd-mm-yyyy" formatted value
     */
    protected $dateStart;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime
     * @var string A "dd-mm-yyyy" formatted value
     */
    protected $dateEnd;
    /** @ORM\Column(type="string", length=128, nullable=true) **/
    protected $reasonDemote;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="studentGroups")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;
    /**
     * @ORM\ManyToOne(targetEntity="StudentGroup", inversedBy="users")
     * @ORM\JoinColumn(name="studentgroup_id", referencedColumnName="id", nullable=false)
     */
    protected $studentGroup;

    /**
     * $id getter
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * $dateStart getter
     * @return datetime $dateStart
     */
    public function getDateStart(): ?DateTime
    {
        return $this->dateStart;
    }

    /**
     * $dateStart setter
     * @param DateTime $dateStart
     * @return void
     */
    public function setDateStart(DateTime $dateStart): void
    {
        $this->dateStart = $dateStart;
    }
    /**
     * $dateEnd getter
     * @return datetime $dateEnd
     */
    public function getDateEnd(): ?DateTime
    {
        return $this->dateEnd;
    }

    /**
     * $dateEnd setter
     * @param DateTime $dateEnd
     * @return void
     */
    public function setDateEnd(DateTime $dateEnd): void
    {
        $this->dateEnd = $dateEnd;
    }
    /**
     * $reasonDemote getter
     * @return string $reasonDemote
     */
    public function getReasonDemote(): ?string
    {
        return $this->reasonDemote;
    }
    /**
     * $reasonDemote setter
     * @param string $reasonDemote
     * @return void
     */
    public function setReasonDemote(string $reasonDemote): void
    {
        $this->reasonDemote = $reasonDemote;
    }

    /**
     * $user getter
     * @return User $user
     */
    public function getUser(): User
    {
        return $this->user;
    }
    /**
     * $user setter
     * @param User $user
     * @return void
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * $studentGroup getter
     * @return StudentGroup $studentGroup
     */
    public function getStudentGroup(): StudentGroup
    {
        return $this->studentGroup;
    }
    /**
     * $group setter
     * @param StudentGroup $studentGroup
     * @return void
     */
    public function setStudentGroup(StudentGroup $studentGroup): void
    {
        $this->studentGroup = $studentGroup;
    }
}