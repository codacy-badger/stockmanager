<?php

namespace App\Repository;

use App\Entity\Operator;
use App\Entity\Transportation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Transportation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transportation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transportation[]    findAll()
 * @method Transportation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransportationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transportation::class);
    }

    public function findByOperator($id)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.operators = :op')
            ->setParameter('op', $id )
            ->getQuery()
            ->getResult()
            ;


    }

//    /**
//     * @return Brand[] Returns an array of Brand objects
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
    public function findOneBySomeField($value): ?Brand
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
