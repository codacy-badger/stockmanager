<?php

namespace App\Repository;

use App\Entity\Symptom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Symptom|null find($id, $lockMode = null, $lockVersion = null)
 * @method Symptom|null findOneBy(array $criteria, array $orderBy = null)
 * @method Symptom[]    findAll()
 * @method Symptom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BreakdownSymptomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Symptom::class);
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
