<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Equipment;
use App\Entity\Repair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Repair|null find($id, $lockMode = null, $lockVersion = null)
 * @method Repair|null findOneBy(array $criteria, array $orderBy = null)
 * @method Repair[]    findAll()
 * @method Repair[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepairRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Repair::class);
    }


    public function findByFinished()
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.dateEnd is not null')
            ->orderBy('r.dateEnd', 'desc')
            ->getQuery()
            ->getResult();
    }

    public function findUnavailabilities($idEquipment)
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.issue', 'i')
            ->leftJoin('i.equipment', 'e')
            ->andWhere('e.id = :id')
            ->setParameter('id', $idEquipment)
            ->getQuery()
            ->getResult();
    }


    public function countRealIssues()
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->andWhere('r.noBreakdown = false')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countFakeIssues()
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->andWhere('r.noBreakdown = true')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countIssuesByDate(\DateTime $startDate, \DateTime $endDate)
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->andWhere('r.noBreakdown = false')
            ->andWhere('r.dateEnd >= :startDate ')
            ->andWhere('r.dateEnd =< :endDate ')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function getUnavaillabilityByCategoryByPeriod(Category $category, \DateTime $startDate, \DateTime $endDate)
    {
        return $this->createQueryBuilder('r')
            ->select('SUM(r.unavailability)')
            ->leftJoin('r.issue', 'i')
            ->leftJoin('i.equipment', 'e')
            ->leftJoin('e.brand', 'b')
            ->andWhere('b.category = :category')
            ->andWhere('r.dateEnd >= :startDate')
            ->andWhere('r.dateEnd <= :endDate')
            ->andWhere('r.noBreakdown is null')
            ->andWhere('r.degradation is null')
            ->setParameter('category', $category)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function countRepaired(\DateTime $startDate, \DateTime $endDate)
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->andWhere('r.dateEnd BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }




//    /**
//     * @return Repair[] Returns an array of Repair objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Repair
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
