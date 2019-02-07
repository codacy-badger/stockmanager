<?php

namespace App\Repository;

use App\Entity\SubcontractorRepair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SubcontractorRepair|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubcontractorRepair|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubcontractorRepair[]    findAll()
 * @method SubcontractorRepair[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubcontractorRepairRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SubcontractorRepair::class);
    }

//    /**
//     * @return SubcontractorRepair[] Returns an array of SubcontractorRepair objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubcontractorRepair
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
