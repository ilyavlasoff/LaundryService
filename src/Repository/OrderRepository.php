<?php

namespace App\Repository;

use App\Entity\Client;
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

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
