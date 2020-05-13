<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use VP\PersonalAccount\Entity\Role;

class RoleRepository extends ServiceEntityRepository
{
    const DEFAULT_ROLE_ID = 2;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }

    /**
     * @return mixed
     */
    public function findByDefault()
    {
        return $this->find(self::DEFAULT_ROLE_ID);
    }
}