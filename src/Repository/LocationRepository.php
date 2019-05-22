<?php

namespace App\Repository;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Contract;
use App\Entity\Equipment;
use App\Entity\Location;
use App\Entity\Operator;
use App\Entity\Site;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Location::class);
    }


    public function search(
        Equipment $equipment = null,
        Site $site = null,
        Brand $brand = null,
        Category $category = null,
        Contract $contract = null

    )
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();

        $statement = $this->createQueryBuilder('l')
            ->leftJoin('l.equipment', 'e')
            ->leftJoin('e.brand', 'b');


        if (null !== $equipment) {
            $statement->andWhere('l.equipment = :equipment');
            $statement->setParameter('equipment', $equipment);
            $statement->addOrderBy('l.date', 'desc')
                ->addOrderBy('l.id', 'desc');

            return $statement->getQuery()->getResult();
        }
        if (null !== $site) {

            $statement->andWhere('l.site = :site');
            $statement->setParameter('site', $site);
        }

        if (null !== $brand) {

            $statement->andWhere('e.brand = :brand');
            $statement->setParameter('brand', $brand);
        }

        if (null !== $category) {

            $statement->andWhere('b.category = :category');
            $statement->setParameter('category', $category);
        }

        if (null !== $contract) {

            $statement->andWhere('e.contract = :contract');
            $statement->setParameter('contract', $contract);
        }


        $statement
            ->andWhere($expr->in('l.date',
                $this->createQueryBuilder('l2')
                    ->select('MAX(l2.date) AS maxDate')
                    ->where('l2.equipment = l.equipment ')
                    ->groupBy('l2.equipment')
                    ->getDQL()

            ))
            ->addOrderBy('l.date', 'desc')
            ->addOrderBy('l.id', 'desc')
            ->groupBy('e');


        return $statement->getQuery()->getResult();

    }


    public function findLastLocation(Equipment $equipment)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.equipment = :equipment')
            ->orderBy('l.date', 'desc')
            ->setParameter('equipment', $equipment)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

    }

}
