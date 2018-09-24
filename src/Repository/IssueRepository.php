<?php

namespace App\Repository;

use App\Entity\Issue;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Issue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Issue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Issue[]    findAll()
 * @method Issue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IssueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Issue::class);
    }

    /**
     * @return Issue[] Returns an array of Issue objects
     */

    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.user = :val')
            ->andWhere('i.dateEnd IS NULL')
            ->setParameter('val', $user)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function countByUser(User $user)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('count(t.id)');
        $qb->where('t.user = :user');
        $qb->setParameter('user', $user);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countNew()
    {
        $qb = $this->createQueryBuilder('i');
        $qb->select('count(i.id)');
        $qb->where('i.dateChecked IS NULL');

        return $qb->getQuery()->getSingleScalarResult();

    }

    public function countPrepare()
    {
        $qb = $this->createQueryBuilder('i');
        $qb->select('count(i.id)');
        $qb->where('i.dateReady IS NULL');
        $qb->andWhere('i.dateChecked IS NOT NULL');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countReady()
    {
        $qb = $this->createQueryBuilder('i');
        $qb->select('count(i.id)');
        $qb->where('i.dateReady IS NOT NULL');
        $qb->andWhere('i.dateChecked IS NOT NULL');


        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countEnd()
    {
        $qb = $this->createQueryBuilder('i');
        $qb->select('count(i.id)');
        $qb->where('i.dateEnd IS NOT NULL');


        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getChecked()
    {
        $qb = $this->createQueryBuilder('i');
        $qb->andWhere('i.dateChecked IS NOT NULL');
        $qb->andWhere('i.dateReady IS NULL');

        return $qb->getQuery()->getResult();
    }

    public function getReady()
    {
        $qb = $this->createQueryBuilder('i');
        $qb->andWhere('i.dateReady IS NOT NULL');
        $qb->andWhere('i.dateEnd IS NULL');

        return $qb->getQuery()->getResult();
    }

    public function getEnd()
    {
        $qb = $this->createQueryBuilder('i');
        $qb->andWhere('i.dateEnd IS NOT NULL');

        return $qb->getQuery()->getResult();
    }
    /*
    public function findOneBySomeField($value): ?Issue
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
