<?php

namespace App\Services;

use App\Entity\Equipment;
use App\Entity\EquipmentStatus;
use Doctrine\ORM\EntityManagerInterface;

class AvailabilityProcessor
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function generateAvailability()
    {
        $equipmentsWithIssues = $this->em->getRepository(Equipment::class)->findWithIssue();

        foreach ($equipmentsWithIssues as $equipment) {

            foreach ($equipment->getIssues() as $issue) {

                $equipmentStatus = $this->em->getRepository(EquipmentStatus::class)->findOneBy(['issue' => $issue]);

                if (null === $equipmentStatus) {

                    $status = new EquipmentStatus();
                    $status->setStartFailure($issue->getDateRequest());
                    $status->setEquipment($equipment);
                    $status->setIssue($issue);

                    $this->em->persist($status);
                    $this->em->flush();
                }
            }
        }

        return ;
    }
}