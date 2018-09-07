<?php

namespace App\Repository;

use App\Entity\Equipment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Equipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipment[]    findAll()
 * @method Equipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Equipment::class);
    }

    /**
     * @param $serial
     * @return mixed
     */
    public function findLike($serial)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.serial LIKE :serial')
            ->setParameter('serial', "%serial%")
            ->orderBy('a.serial')
            ->setMaxResults(5)
            ->getQuery()
            ->execute()
            ;
    }
}
