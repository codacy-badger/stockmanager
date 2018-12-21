<?php

namespace App\Repository;

use App\Entity\Issue;
use App\Entity\Operator;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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

    public function findByOperatorEnd(Operator $operator)
    {
        return $this->createQueryBuilder('i')
            ->join('i.user', 'u', 'WITH', 'u.operator = :operator')
            ->andWhere('i.dateEnd IS NOT NULL')
            ->setParameter('operator', $operator)
            ->getQuery()
            ->getResult();
    }

    public function findByOperator(Operator $operator)
    {
        return $this->createQueryBuilder('i')
            ->join('i.user', 'u', 'WITH', 'u.operator = :operator')
            ->andWhere('i.dateEnd IS NULL')
            ->setParameter('operator', $operator)
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

    public function countCheck()
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
        $qb->andWhere('i.dateEnd IS NULL');


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


//    public function countNonNotified()
//    {
//        $qb = $this->createQueryBuilder('i');
//        $qb->select('i.user, count(i.id)')
//            ->andWhere('i.dateMessage is null')
//            ->groupBy('i.user');
//
//        return $qb->getQuery()->getResult();
//    }


    public function countEquipmentInProgress($equipment)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c.id)')
            ->where('c.dateEnd IS NULL')
            ->andWhere('c.equipment = :equipment OR c.equipmentReplace = :equipment')
            ->setParameter('equipment', $equipment);

        return $qb->getQuery()->getSingleScalarResult();


    }


    public function countAllOpenIssues()
    {
        $qb = $this->createQueryBuilder('k');
        $qb->select('count(k.id)')
            ->where('k.dateEnd IS NULL');

        return $qb->getQuery()->getSingleScalarResult();

    }


    public function countUserOpenIssues(Operator $operator)
    {
        return $this->createQueryBuilder('i')
            ->select('count(i)')
            ->join('i.user', 'u', 'WITH', 'u.operator = :operator')
            ->andWhere('i.dateEnd IS NULL')
            ->setParameter('operator', $operator)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function getNonNotifed()
    {

        return $qr = $this->createQueryBuilder('i')
            ->leftJoin('i.user', 'u')
            ->addSelect('u')
            ->where('i.dateMessage is null')
            ->andWhere('i.dateReady is not null')
            ->getQuery()
            ->getResult();
    }

    public function countNonNotifed()
    {

        return $qr = $this->createQueryBuilder('i')
            ->select('count(i)')
//            ->leftJoin('i.user', 'u')
//            ->addSelect('u')
            ->where('i.dateMessage is null')
            ->andWhere('i.dateReady is not null')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getSymptoms()
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.symptoms', 's')
            ->select('s.name, count(s.name)')
            ->groupBy('s.name')
            ->getQuery()
            ->getResult()
            ;
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
