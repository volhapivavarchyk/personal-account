<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use VP\PersonalAccount\Entity\UserKind;

class UserKindRepository extends ServiceEntityRepository
{
    const DEFAULT_USER_KIND_ID = 3;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserKind::class);
    }

    /**
     * @return mixed
     */
    public function findByDefault()
    {
        return $this->find(self::DEFAULT_USER_KIND_ID);
    }
}