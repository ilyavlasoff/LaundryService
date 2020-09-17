<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function isClientExistsForUser(User $user) {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(cl) as count')
            ->from('App\Entity\Client', 'cl')
            ->join('App\Entity\User', 'us', Join::WITH, 'cl.user = us.id')
            ->where('us.id = :user')
            ->setParameter('user', $user->getId());
        return boolval($qb->getQuery()->getResult()[0]['count']);
    }

    public function getUserOrdersProperties(Client $client, $period = null) {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(ord) as count, SUM(ord.sumPrice) as sumPrice')
            ->from('App\Entity\Client', 'cl')
            ->join('App\Entity\Order', 'ord', Join::WITH, 'cl.id = ord.client')
            ->where('cl.id = :client')
            ->setParameter('client', $client->getId());
        if ($period == 'month')
        {
            $qb
                ->andWhere('ord.receiveDate BETWEEN :start AND :end')
                ->setParameter('start', (new \DateTime('now'))->format('Y-m') . '-01')
                ->setParameter('end', (new \DateTime('now'))->format('Y-m-d'));
        }
        return $qb->getQuery()->getResult();
    }

    public function getLastNOrdersOfClient(Client $client, int $count) {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('ord')
            ->from('App\Entity\Client', 'cl')
            ->join('App\Entity\Order', 'ord', Join::WITH, 'cl.id = ord.client')
            ->where('cl.id = :client')
            ->orderBy('ord.receiveDate', 'DESC')
            ->setMaxResults($count)
            ->setParameter('client', $client->getId())
            ->getQuery()->getResult();
    }
}
