<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 14/02/2019
 * Time: 10:44
 */

namespace App\Services;


use App\Entity\Equipment;
use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;

class EquipmentLocator
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {

        $this->em = $em;
    }


    public function setLocation()
    {

    }

}