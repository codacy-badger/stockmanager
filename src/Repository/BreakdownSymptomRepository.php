<?php

namespace App\Repository;

use App\Entity\BreakdownSymptom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BreakdownSymptom|null find($id, $lockMode = null, $lockVersion = null)
 * @method BreakdownSymptom|null findOneBy(array $criteria, array $orderBy = null)
 * @method BreakdownSymptom[]    findAll()
 * @method BreakdownSymptom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BreakdownSymptomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BreakdownSymptom::class);
    }

//    /**
//     * @return BreakdownSymptom[] Returns an array of BreakdownSymptom objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BreakdownSymptom
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
