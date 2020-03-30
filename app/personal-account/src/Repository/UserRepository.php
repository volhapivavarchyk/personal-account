<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQuery(
            'SELECT user
                FROM VP\PersonalAccount\Entity\User user
                WHERE user.username = :query'
        )
            ->setParameter('query', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}