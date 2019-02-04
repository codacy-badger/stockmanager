<?php

namespace App\Repository;

use App\Entity\PartGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PartGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PartGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PartGroup[]    findAll()
 * @method PartGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartGroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PartGroup::class);
    }

//    /**
//     * @return PartGroup[] Returns an array of PartGroup objects
//     */
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
    public function findOneBySomeField($value): ?PartGroup
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
