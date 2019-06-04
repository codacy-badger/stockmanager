<?php


namespace App\Services;


use App\Entity\Part;
use Doctrine\ORM\EntityManagerInterface;

class WarningPartThreshold
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return bool
     */
    public function getWarningThreshold()
    {

        $warning = false;

        $parts = $this->entityManager->getRepository(Part::class)->findAll();

        foreach ($parts as $part) {

            $quantities = $part->getQuantities();

            $total = 0;

            foreach ($quantities as $quantity) {
                $total = $total + $quantity->getQuantity();
            }


            if ($part->getThreshold() && $total < $part->getThreshold()) {
                $warning = true;
            }

        }

        return $warning;
    }
}