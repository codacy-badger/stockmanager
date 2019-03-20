<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 20/03/2019
 * Time: 10:31
 */

namespace App\Services;


use App\Entity\Brand;
use App\Entity\Equipment;
use Doctrine\ORM\EntityManagerInterface;

class ReportGenerator
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generate()
    {
        $equipments = $this->entityManager->getRepository(Brand::class)->findAll();


    }

}