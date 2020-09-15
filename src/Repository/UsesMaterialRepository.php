<?php

namespace App\Repository;

use App\Entity\UsesMaterial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsesMaterial|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsesMaterial|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsesMaterial[]    findAll()
 * @method UsesMaterial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsesMaterialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsesMaterial::class);
    }

    // /**
    //  * @return UsesMaterial[] Returns an array of UsesMaterial objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsesMaterial
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
