<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Employee;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function getOrdersByClientId(Client $client, int $count = null, int $offset = null)
    {
        $qb = $this->createQueryBuilder('ord');

        $qb
            ->select('ord')
            ->innerJoin('App\Entity\Client', 'cl', Join::WITH, 'ord.client = cl.id')
            ->where('cl.id = :clientId')
            ->setParameter('clientId', $client->getId())
            ->orderBy('ord.receiveDate', 'DESC');
        if ($count && $offset)
        {
            $qb->setFirstResult($offset)->setMaxResults($count);
        }

        return $qb->getQuery()->getResult(Query::HYDRATE_OBJECT);
    }

    public function getOrdersAttachedToEmployee(Employee $employee, string $status = 'all', string $sortFields = null) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->select('ord')
            ->from('App\Entity\Employee', 'emp')
            ->join('App\Entity\Order', 'ord',Join::WITH, 'emp.id = ord.employee')
            ->where('emp.id = :employee');

        if($status == 'active') {
            $qb->andWhere('ord.completed = False');
        } elseif ($status == 'completed') {
            $qb->andWhere('ord.completed = True');
        }

        if ($sortFields == 'ending') {
            $qb->orderBy('ord.endingDate');
        } elseif ($sortFields == 'creation') {
            $qb->orderBy('ord.receiveDate');
        } elseif ($sortFields == 'price') {
            $qb->orderBy('ord.sumPrice');
        }

        $qb->setParameter('employee', $employee->getId());

        return $qb->getQuery()->getResult();
    }
}
