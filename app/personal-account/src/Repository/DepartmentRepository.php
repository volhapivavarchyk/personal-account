<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;
use VP\PersonalAccount\Entity\Department;
use VP\PersonalAccount\Entity\Role;

class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function getDepartmentOwnershipChain(int $department_id)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Department::class, 'dep');
        $rsm->addFieldResult('dep', 'id', 'id');
        $rsm->addFieldResult('dep', 'parent', 'parent');

        $em = $this->getEntityManager();

        $query = $em->createNativeQuery('
            with recursive departments as (
                SELECT id, parent_id, name
                FROM department
                WHERE id = ?
                
                UNION ALL
                
                SELECT dep.id, dep.parent_id, dep.name
                FROM departments as deps
                JOIN department as dep ON deps.id = dep.parent_id
            )
            SELECT *
            FROM departments;',
            $rsm);
        $query->setParameter(1, $department_id);
        //return $query;
        return $query->getScalarResult();
    }
}