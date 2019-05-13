<?php


namespace App\EventListener;


use App\Entity\Delivery;
use App\Entity\Location;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;



class DeliveryListener implements EventSubscriber
{

    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate,
            Events::postPersist
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Delivery) {
            return;
        }


    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Delivery) {
            return;
        }

        //evite la boucle infinie
        $entityManager = $args->getEntityManager();

        $eventManager = $entityManager->getEventManager();

        $eventManager->removeEventListener('preUpdate', $this);


        $this->updateLocation($entity, $args);


    }

    public function addLocation(Delivery $delivery, LifecycleEventArgs $args)
    {
        $entityManager = $args->getObjectManager();

        $site = $delivery->getUser()->getOperator()->getSite();

        foreach ($delivery->getEquipments() as $equipment) {

            if (!$equipment) {
                return;
            }

            $location = new Location();
            $location->setSite($site)
                ->setIsOk(true)
                ->setEquipment($equipment)
                ->setDate($delivery->getDateCreation());

            $entityManager->persist($location);
        }

        $entityManager->flush();


    }

    public function updateLocation(Delivery $delivery, PreUpdateEventArgs $args)
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