<?php

namespace App\Repository;

use App\Entity\EquipmentStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EquipmentStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method EquipmentStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method EquipmentStatus[]    findAll()
 * @method EquipmentStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EquipmentStatus::class);
    }

//    /**
//     * @return EquipmentStatus[] Returns an array of EquipmentStatus objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EquipmentStatus
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
