<?php

namespace App\EventSubscriber;

use App\Entity\PartQuantity;
use App\Entity\Repair;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class RepairSubscriber implements EventSubscriber
{
    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist
        ];
    }


    public
    function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Repair) {
            return;
        }

        $this->subsctractPartQuantity($entity, $args);


    }


    private
    function subsctractPartQuantity(Repair $repair, LifecycleEventArgs $args)
    {

        $entityManager = $args->getEntityManager();

        foreach ($repair->getParts() as $part) {

            $partQuantity = new PartQuantity();
            $partQuantity->setDate($repair->getDateEnd())
                ->setPart($part)
                ->setQuantity(-1);

            $entityManager->persist($partQuantity);

        }

        $entityManager->flush();
    }


}