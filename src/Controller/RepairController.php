<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Entity\Equipment;
use App\Entity\EquipmentStatus;
use App\Entity\Issue;
use App\Entity\Location;
use App\Entity\Repair;
use App\Entity\Site;
use App\Entity\Statistics;
use App\Entity\SubcontractorRepair;
use App\Form\RepairType;
use App\Repository\RepairRepository;
use App\Services\DateDiffHour;
use App\Services\MTBFStatistics;
use App\Services\MTTRStatistics;
use App\Services\RateStatistics;
use Aws\S3\S3Client;
use Doctrine\ORM\EntityManagerInterface;
use Gaufrette\Adapter\AwsS3;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/repair")
 */
class RepairController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="repair_index", methods="GET")
     */
    public function index(RepairRepository $repairRepository): Response
    {
        // create new object contract to get the constant and send it into view
        $contract = new Contract();

        $issues = $this->em->getRepository(Issue::class)->getNotRepaired();

        return $this->render('admin/repair/index.html.twig', [
            'issues' => $issues,
            'contract' => $contract
        ]);
    }

    /**
     * @Route("/historic", name="repair_historic")
     */
    public function repairHistoric()
    {
        $repairs = $this->em->getRepository(Repair::class)->findByFinished();


        return $this->render('admin/repair/historic.html.twig', [
            'repairs' => $repairs
        ]);
    }

    /**
     * @Route("/new", name="repair_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $repair = new Repair();
        $form = $this->createForm(RepairType::class, $repair);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($repair);
            $this->em->flush();

            return $this->redirectToRoute('repair_index');
        }

        return $this->render('admin/repair/new.html.twig', [
            'repair' => $repair,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="repair_show", methods="GET")
     */
    public function show(Repair $repair): Response
    {
        return $this->render('admin/repair/show.html.twig', ['repair' => $repair]);
    }

    /**
     * @Route("/{id}/edit", name="repair_edit", methods="GET|POST")
     */
    public function edit(Request $request, Repair $repair, DateDiffHour $dateDiffHour): Response
    {

        $checkIfisGoingToSubcontractor = $repair->getSubcontractorRepair();

        $form = $this->createForm(RepairType::class, $repair, [
            'brand' => $repair->getIssue()->getEquipment()->getBrand()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if ($repair->getIsGoingToSubcontractor() === true && $checkIfisGoingToSubcontractor === null) {

                $subcontracterRepair = new SubcontractorRepair();
                $subcontracterRepair->setDateEntry($repair->getDateEnd());
                $subcontracterRepair->setRepair($repair);

                $this->em->persist($subcontracterRepair);
            }


            $hours = $dateDiffHour->getDiff($repair->getDateEnd(), $repair->getIssue()->getDateRequest());

            $repair->setUnavailability($hours);


            $this->em->flush();

            $this->addFlash('success', 'La modification a bien été effectuée');
            return $this->redirectToRoute('repair_edit', ['id' => $repair->getId()]);
        }

        return $this->render('admin/repair/edit.html.twig', [
            'repair' => $repair,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="repair_delete", methods="DELETE")
     */
    public function delete(Request $request, Repair $repair): Response
    {
        if ($this->isCsrfTokenValid('delete-repair', $request->request->get('_token'))) {

            $this->em->remove($repair);
            $this->em->flush();

            $this->addFlash('success', 'La suppression a bien été effectuée');
        }

        return $this->redirectToRoute('repair_index');
    }

    /**
     * @Route("/repair-item-issue-{id}", name="repair_item", methods="GET|POST")
     * @param Issue $issue
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function repairItem(Issue $issue, Request $request, RateStatistics $rate, DateDiffHour $dateDiffHour)
    {
        $repair = new Repair();
        $contract = new Contract();
        $statistics = new Statistics();

        //get all issues concerning the current equipment
        $historicIssues = $this->em->getRepository(Issue::class)->findByEquipment($issue->getEquipment());

        //get all repairs concerning the current equipment
        $oldRepairs = $this->em->getRepository(Repair::class)->findUnavailabilities($issue->getEquipment()->getId());

        $sumUnaivalable = 0;

        /** @var Repair $oldRepair */
        foreach ($oldRepairs as $oldRepair) {
            $sumUnaivalable = $sumUnaivalable + $oldRepair->getUnavailability();
        }


        $hoursPerDay = $issue->getEquipment()->getBrand()->getCategory()->getHoursPerDay();

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


        $form = $this->createForm(RepairType::class, $repair, [
            'brand' => $issue->getEquipment()->getBrand(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // si l'équipement est envoyé chez le sous traitant , on enregistre
            if ($repair->getIsGoingToSubcontractor()) {

                $subcontracterRepair = new SubcontractorRepair();
                $subcontracterRepair->setRepair($repair);

                $this->em->persist($subcontracterRepair);


            } else {


                //cas ou l'équipement est réparé par siteoise
                //get number of hours to repair
                $hours = $dateDiffHour->getDiff($repair->getDateEnd(), $issue->getDateRequest());

                $repair->setUnavailability($hours);

                //réccupérer le site SITEOISE
                $homeSite = $this->em->getRepository(Site::class)->findOneBy(['id' => Site::SITEOISE]);


                $location = new Location();

                $location
                    ->setEquipment($issue->getEquipment())
                    ->setSite($homeSite)
                    ->setDate($repair->getDateEnd())
                    ->setIsOk(true);

                $this->em->persist($location);
            }


            // add technician
            $repair->setTechnician($this->getUser());


            $issue->setRepair($repair);
            $repair->setIssue($issue);

            $this->em->persist($repair);

            $this->em->flush();

            $this->addFlash('success', 'La réparation a bien été enregistrée');
            return $this->redirectToRoute('repair_historic');


        }

        return $this->render('admin/repair/repairItem.html.twig', [
                'form' => $form->createView(),
                'issue' => $issue,
                'contract' => $contract,
                'historicIssues' => $historicIssues,
                'mttr' => $mttr->getMTTR(),
                'mtbf' => $mtbf->getMTBF(),
                'rate' => $rate->getRate($mtbf->getMTBF(), $mttr->getMTTR()),
                'numberOfParts' => $numberOfParts,
                'numberOfSymptoms' => $numberOfSymptoms

            ]

        );
    }


    /**
     * @Route("/countJson", name="repair_countJson", methods={"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function countInProcessedJson()
    {

        $count = $this->em->getRepository(Issue::class)->countNotRepaired();

        $response = new JsonResponse([
            'number' => $count,
        ]);

        return $response;
    }


}
