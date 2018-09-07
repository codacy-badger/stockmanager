<?php

namespace App\Repository;

use App\Entity\IssueSymptom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IssueSymptom|null find($id, $lockMode = null, $lockVersion = null)
 * @method IssueSymptom|null findOneBy(array $criteria, array $orderBy = null)
 * @method IssueSymptom[]    findAll()
 * @method IssueSymptom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IssueSymptomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IssueSymptom::class);
    }

//    /**
//     * @return IssueSymptom[] Returns an array of IssueSymptom objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IssueSymptom
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
