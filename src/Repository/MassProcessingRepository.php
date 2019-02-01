<?php

namespace App\Repository;

use App\Entity\MassProcessing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MassProcessing|null find($id, $lockMode = null, $lockVersion = null)
 * @method MassProcessing|null findOneBy(array $criteria, array $orderBy = null)
 * @method MassProcessing[]    findAll()
 * @method MassProcessing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MassProcessingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MassProcessing::class);
    }



//    /**
//     * @return MassProcessing[] Returns an array of MassProcessing objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MassProcessing
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
