<?php

namespace App\Repository;

use App\Entity\EquipmentSoftware;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EquipmentSoftware|null find($id, $lockMode = null, $lockVersion = null)
 * @method EquipmentSoftware|null findOneBy(array $criteria, array $orderBy = null)
 * @method EquipmentSoftware[]    findAll()
 * @method EquipmentSoftware[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentSoftwareRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EquipmentSoftware::class);
    }

//    /**
//     * @return EquipmentSoftware[] Returns an array of EquipmentSoftware objects
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
    public function findOneBySomeField($value): ?EquipmentSoftware
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
