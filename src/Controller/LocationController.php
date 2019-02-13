<?php

namespace App\Controller;


use App\Entity\Contract;
use App\Entity\Issue;
use App\Entity\Location;
use App\Entity\Repair;
use App\Entity\Statistics;
use App\Form\LocationType;
use App\Form\RepairType;
use App\Services\DateDiffHour;
use App\Services\MTBFStatistics;
use App\Services\MTTRStatistics;
use App\Services\RateStatistics;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/location")
 */
class LocationController extends AbstractController
{


    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="location_index", methods="GET|POST")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {

        $locations = $this->em->getRepository(Location::class)->findAll();


        return $this->render('admin/location/index.html.twig', [
            'locations' => $locations,
        ]);
    }


    /**
     * @Route("/search", name="location_search")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request, RateStatistics $rate)
    {

        $location = new Location();

        $form = $this->createForm(LocationType::class, $location);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $contract = new Contract();
            $statistics = new Statistics();

            //get all issues concerning the current equipment
            $historicIssues = $this->em->getRepository(Issue::class)->findByEquipment($location->getEquipment());

            //get all repairs concerning the current equipment
            $oldRepairs = $this->em->getRepository(Repair::class)->findUnavailabilities($location->getEquipment()->getId());

            $sumUnaivalable = 0;

            /** @var Repair $oldRepair */
            foreach ($oldRepairs as $oldRepair) {
                $sumUnaivalable = $sumUnaivalable + $oldRepair->getUnavailability();
            }


            $hoursPerDay = $location->getEquipment()->getBrand()->getCategory()->getHoursPerDay();

            $now = new \DateTime();
            $before = new \DateTime('2011-09-01');
            $interval = $now->diff($before);


            $statistics->setDays($interval->days)
                ->setHoursPerDay($hoursPerDay)
                ->setNumber(1)
                ->setFailures(count($historicIssues))
                ->setHoursRepair($sumUnaivalable);




                //get mttr
                $mttr = new MTTRStatistics($statistics);
                //get mtbf
                $mtbf = new MTBFStatistics($statistics);



            //get number of changed parts and symptoms

            $numberOfParts = 0;
            $numberOfSymptoms = 0;

            /** @var Repair $oldRepair */
            foreach ($oldRepairs as $oldRepair) {
                $numberOfParts = $numberOfParts + $oldRepair->getParts()->count();
                $numberOfSymptoms = $numberOfSymptoms + $oldRepair->getSymptoms()->count();
            }


            $result = $this->em->getRepository(Location::class)->findBy([
                'equipment' => $location->getEquipment(),


            ], [
                'date' => 'DESC'
            ]);

            return $this->render('admin/location/index.html.twig', [

                'contract' => $contract,
                'historicIssues' => $historicIssues,
                'mttr' => $mttr->getMTTR(),
                'mtbf' => $mtbf->getMTBF(),
                'rate' => $rate->getRate($mtbf->getMTBF(), $mttr->getMTTR()),
                'numberOfParts' => $numberOfParts,
                'numberOfSymptoms' => $numberOfSymptoms,
                'form' => $form->createView(),
                'locations' => $result,
                'location'=> $location
            ]);


        }

        return $this->render('admin/location/search.html.twig', [
            'form' => $form->createView(),

        ]);


    }


}
