<?php

namespace App\Repository;

use App\Entity\RepairSymptom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RepairSymptom|null find($id, $lockMode = null, $lockVersion = null)
 * @method RepairSymptom|null findOneBy(array $criteria, array $orderBy = null)
 * @method RepairSymptom[]    findAll()
 * @method RepairSymptom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepairSymptomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RepairSymptom::class);
    }

//    /**
//     * @return RepairSymptom[] Returns an array of RepairSymptom objects
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
    public function findOneBySomeField($value): ?RepairSymptom
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
