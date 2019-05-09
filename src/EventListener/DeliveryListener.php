<?php


namespace App\EventListener;


use App\Entity\Delivery;
use App\Entity\Location;
use App\Entity\User;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class DeliveryListener
{

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Delivery) {
            return;
        }

        $entityManager = $args->getEntityManager();

        $eventManager = $entityManager->getEventManager();

        $eventManager->removeEventListener('preUpdate', $this);


        $this->addLocation($entity, $args);


    }

    public function addLocation(Delivery $delivery, PreUpdateEventArgs $args)
    {
        $entityManager = $args->getEntityManager();

        $site = $delivery->getUser()->getOperator()->getSite();


        foreach ($delivery->getEquipments() as $equipment) {


            $locations = $entityManager->getRepository(Location::class)->findBy([
                'equipment' => $equipment,
                'site' => $args->hasChangedField('user') ? $args->getOldValue('user')->getOperator()->getSite() : $delivery->getUser()->getOperator()->getSite(),
                'date' => $args->hasChangedField('dateCreation') ? $args->getOldValue('dateCreation') : $delivery->getDateCreation(),
                'isOk' => true
            ]);


            if (!$locations) {

                $newLocation = new Location();
                $newLocation->setEquipment($equipment)
                    ->setSite($site)
                    ->setDate($args->hasChangedField('dateCreation') ? $args->getOldValue('dateCreation') : $delivery->getDateCreation())
                    ->setIsOk(true);

                $entityManager->persist($newLocation);

            }


            foreach ($locations as $location) {



                if ($args->hasChangedField('dateCreation')) {
                    $location->setDate($delivery->getDateCreation());
                }

                if ($args->hasChangedField('user')) {
                    $location->setSite($site);

                }


            }

            $entityManager->flush();

        }
    }

}