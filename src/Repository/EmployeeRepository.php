<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function getLessBusyEmployee() : Employee {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('emp.id, SUM(ord.sumPrice)')
            ->from('App\Entity\Employee', 'emp')
            ->join('App\Entity\Order', 'ord', Join::WITH, 'emp.id = ord.employee')
            ->groupBy('emp.id')
            ->orderBy('SUM(ord.sumPrice)')
            ->setMaxResults(1);
        $res = $qb->getQuery()->getResult();
        return $this->find($res[0]['id']);
    }
}
