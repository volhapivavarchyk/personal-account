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
        $rsm->addFieldResult('dep', 'name', 'name');

        $query = $this->createNativeNamedQuery(
            'with recursive cte as (
                 SELECT dep.id, dep.parent
                 WHERE dep.id = ?
                 FROM   group

                 UNION  ALL

                 SELECT e.id, e.parent
                 FROM   cte c
                 JOIN   group e ON e.id = c.parent
            )
            SELECT *
            FROM   cte;',
            $rsm
        );
        $query->setParameter(1, $department_id);
        return $query->getResult();
    }
}