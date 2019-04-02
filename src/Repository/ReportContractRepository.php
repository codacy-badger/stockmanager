<?php

namespace App\Repository;

use App\Entity\ReportContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReportContract|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportContract|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportContract[]    findAll()
 * @method ReportContract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportContractRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReportContract::class);
    }

    public function delete()
    {
        return $this->createQueryBuilder('r')
            ->delete()
            ->getQuery()
            ->execute();


    }

    public function findAllOrder()
    {
        return $this->createQueryBuilder('r')
            ->join('r.category', 'c')
            ->orderBy('c.isEmbeded', 'DESC')
            ->orderBy('r.issueQuantity', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Report[] Returns an array of Report objects
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
    public function findOneBySomeField($value): ?Report
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
