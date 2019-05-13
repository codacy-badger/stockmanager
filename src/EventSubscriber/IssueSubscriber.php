<?php

namespace App\EventSubscriber;

use App\Entity\Issue;
use App\Entity\Location;
use App\Entity\Site;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;


class IssueSubscriber implements EventSubscriber
{

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate,
        ];
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {


        $entity = $args->getObject();

        if (!$entity instanceof Issue) {
            return;
        }

        //evite la boucle infinie
        $entityManager = $args->getEntityManager();

        $eventManager = $entityManager->getEventManager();

        $eventManager->removeEventListener('preUpdate', $this);

        $this->updateLocation($entity, $args);

    }


    public function updateLocation(Issue $issue, PreUpdateEventArgs $args)
    {
        $entityManager = $args->getEntityManager();
        $site = new Site();
        $siteHome = $entityManager->getRepository(Site::class)->find($site::SITEOISE);

        if ($args->hasChangedField('equipment')) {

            $locations = $entityManager->getRepository(Location::class)->findBy([

                'equipment' => $args->hasChangedField('equipment') ? $args->getOldValue('equipment') : $issue->getEquipment(),
                'date' => $issue->getDateEnd(),
                'site' => $siteHome,
                'isOk' => false
            ]);


            if (!$locations) {

                $this->addLocation($entityManager, $issue, $siteHome, false);
            }

            foreach ($locations as $location) {

                if ($args->hasChangedField('equipment')) {
                    $location->setEquipment($issue->getEquipment());
                }

            }

        }

        if ($args->hasChangedField('equipmentReplace')) {
            $locations = $entityManager->getRepository(Location::class)->findBy([

                'equipment' => $args->hasChangedField('equipmentReplace') ? $args->getOldValue('equipmentReplace') : $issue->getEquipment(),
                'date' => $issue->getDateEnd(),
                'site' => $issue->getUser()->getOperator()->getSite(),
                'isOk' => true
            ]);

            if (!$locations) {
                $this->addLocation($entityManager, $issue, $issue->getUser()->getOperator()->getSite(), true);

            }


            foreach ($locations as $location) {


                if ($args->hasChangedField('equipmentReplace')) {
                    $location->setEquipment($issue->getEquipmentReplace());
                }


            }

        }


        $entityManager->flush();

    }

    private function addLocation(EntityManagerInterface $entityManager, Issue $issue, Site $site, bool $isOk)
    {
        $newLocation = new Location();
        $newLocation->setEquipment($issue->getEquipmentReplace())
            ->setSite($site)
            ->setDate($issue->getDateEnd() ? $issue->getDateEnd() : $issue->getDateReady())
            ->setIsOk($isOk);

        $entityManager->persist($newLocation);
    }


}
