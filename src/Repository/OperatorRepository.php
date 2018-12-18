<?php

namespace App\Repository;

use App\Entity\Operator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Operator|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operator|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operator[]    findAll()
 * @method Operator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperatorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Operator::class);
    }


    public function getOperatorWithNonNotifiedIssues()
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.users', 'u')
            ->addSelect('u')
            ->leftJoin('u.issues', 'i')
            ->addSelect('i')
            ->where('i.dateMessage is null')
            ->andWhere('i.dateReady is not null')
            ->getQuery()
            ->getResult();
    }

    public function getOneOperatorWithNonNotifedIssues(Operator $operator)
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.users', 'u')
            ->addSelect('u')
            ->leftJoin('u.issues', 'i')
            ->addSelect('i')
            ->where('i.dateMessage is null')
            ->andWhere('i.dateReady is not null')
            ->andWhere('o.id = :id')
            ->setParameter('id', $operator->getId())
            ->getQuery()
            ->getSingleResult()
            ;
    }

    public function getOperatorWithNotEndedIssues()
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.users', 'u')
            ->addSelect('u')
            ->innerJoin('u.issues', 'i')
            ->addSelect('i')
            ->where('i.dateEnd is null')
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Operator[] Returns an array of Operator objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Operator
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
