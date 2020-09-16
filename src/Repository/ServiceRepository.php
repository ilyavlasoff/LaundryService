<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function getServices($type) {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('sv')
            ->from('App\Entity\Service', 'sv')
            ->join('App\Entity\UsesMaterial', 'um', Join::WITH, 'sv.id = um.materials')
            ->join('App\Entity\Material', 'mat', Join::WITH, 'mat.id = um.materials');
        if ($type === 'available') {
            $qb->where('um.usesQuantity <= mat.available');
        }
        elseif ($type === 'unavailable') {
            $qb->where('um.usesQuantity > mat.available');
        }
        return $qb->getQuery()->getResult();
    }

    public function getAvailableServices() {
        return $this->getServices('available');
    }

    public function getUnavailableServices() {
        return $this->getServices('unavailable');
    }

    public function getMaterialsForService(Service $service) {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('mat.id, mat.name, um.usesQuantity')
            ->from('App\Entity\Service', 'sv')
            ->join('App\Entity\UsesMaterial', 'um', Join::WITH, 'sv.id = um.materials')
            ->join('App\Entity\Material', 'mat', Join::WITH, 'mat.id = um.materials')
            ->where('sv.id = :service')
            ->setParameter('service', $service->getId());
        return $qb->getQuery()->getResult();
    }

    public function getTotalSumPriceForService(Service $service)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('SUM(mat.price) as matPrice')
            ->from('App\Entity\Service', 'sv')
            ->join('App\Entity\UsesMaterial', 'um', Join::WITH, 'sv.id = um.materials')
            ->join('App\Entity\Material', 'mat', Join::WITH, 'mat.id = um.materials')
            ->where('sv.id = :service')
            ->setParameter('service', $service->getId());
        $materialsPrice = $qb->getQuery()->getResult()[0]['matPrice'];
        return $materialsPrice + $service->getStandardPricing();
    }
}
