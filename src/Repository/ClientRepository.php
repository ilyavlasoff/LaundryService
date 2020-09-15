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
}
