<?php

namespace App\Repository;

use App\Entity\PartQuantity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PartQuantity|null find($id, $lockMode = null, $lockVersion = null)
 * @method PartQuantity|null findOneBy(array $criteria, array $orderBy = null)
 * @method PartQuantity[]    findAll()
 * @method PartQuantity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartQuantityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PartQuantity::class);
    }

    // /**
    //  * @return PartQuantity[] Returns an array of PartQuantity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PartQuantity
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
