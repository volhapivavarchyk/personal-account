<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Repository;

use Doctrine\ORM\EntityRepository;
use VP\PersonalAccount\Entity\UserKind;

class UserKindRepository extends EntityRepository
{
    public function getUserKindByDefault()
    {
        return $this->createQuery(
            'SELECT name
                FROM UserKind userkind
                WHERE userkind.id = :query'
            )
            ->setParameter('query', 1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getUserKindById(int $id)
    {
        return $this->createQuery(
            'SELECT name
                FROM UserKind userkind
                WHERE userkind.id = :query'
        )
            ->setParameter('query', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

}