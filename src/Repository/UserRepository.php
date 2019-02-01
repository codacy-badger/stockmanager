<?php

namespace App\Repository;

use App\Entity\Operator;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * get the all users where issues has no notifications
     * @return mixed
     */
    public function countNotSendedNotification()
    {
        $qr = $this->createQueryBuilder('u');
        $qr->select('u')
            ->leftJoin('u.issues', 'i')
            ->addSelect('i')
            ->where('i.dateMessage is null')
            ->andWhere('i.dateReady is not null');


        return $qr->getQuery()->getResult();
    }

    /**
     * get the all users where issues has no notifications
     * @return mixed
     */
    public function notSendedNotification(Operator $operator)
    {
        $qr = $this->createQueryBuilder('u');
        $qr->select('u')
            ->leftJoin('u.issues', 'i')
            ->addSelect('i')
            ->where('i.dateMessage is null')
            ->andWhere('i.dateReady is not null')
            ->andWhere('u.operator = :operator')
            ->setParameter('operator', $operator);


        return $qr->getQuery()->getResult();
    }

    /**
     * get one user with non notified issues
     * @param User $user
     * @return User
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNotSendedNotification(User $user): User
    {

        $qr = $this->createQueryBuilder('u');
        $qr->select('u')
            ->leftJoin('u.issues', 'i')
            ->addSelect('i')
            ->where('i.dateMessage is null')
            ->andWhere('i.dateReady is not null')
            ->andWhere('u.id = :id')
            ->setParameter('id', $user->getId());

        return $qr->getQuery()->getSingleResult();
    }


    public function getTechnicians($role = 'ROLE_ADMIN')
    {
        $qr = $this->createQueryBuilder('u');
        $qr->leftJoin('u.authorization', 'a')
            ->addSelect('a')
            ->where('a.role = :role')
            ->setParameter('role', $role);

        return $qr->getQuery()->getResult();
    }


    public function getUsersByOperator(Operator $operator)
    {
        return $this->createQueryBuilder('u')
            ->where('u.operator = :operator')
            ->setParameter('operator', $operator)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
