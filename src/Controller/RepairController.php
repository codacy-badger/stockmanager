<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Entity\Equipment;
use App\Entity\EquipmentStatus;
use App\Entity\Issue;
use App\Entity\Repair;
use App\Entity\Statistics;
use App\Form\RepairType;
use App\Repository\RepairRepository;
use App\Services\MTBFStatistics;
use App\Services\MTTRStatistics;
use App\Services\RateStatistics;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function edit(Request $request, Repair $repair): Response
    {
        $form = $this->createForm(RepairType::class, $repair);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

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
        }

        return $this->redirectToRoute('repair_index');
    }

    /**
     * @Route("/repair-item-issue-{id}", name="repair_item", methods="POST")
     * @param Issue $issue
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function repairItem(Issue $issue, Request $request, RateStatistics $rate)
    {
        $repair = new Repair();
        $contract = new Contract();
        $statistics = new Statistics();

        //get all issues concerning the current equipment
        $historicIssues = $this->em->getRepository(Issue::class)->findByEquipment($issue->getEquipment());

        //get all repairs concerning the current equipment
        $oldRepairs = $this->em->getRepository(Repair::class)->findUnavailabilities($issue->getEquipment()->getId());

        $sumUnaivalable = 0;

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
        foreach ($oldRepairs as $oldRepair) {
            $numberOfParts = $numberOfParts + $oldRepair->getParts()->count();
            $numberOfSymptoms = $numberOfSymptoms + $oldRepair->getSymptoms()->count();
        }


        $form = $this->get('form.factory')->create(RepairType::class, $repair);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            //set the time to repair property
            $diff = $repair->getDateEnd()->diff($issue->getDateRequest());
            $hours = $diff->h;
            $hours = $hours + ($diff->days * 24);
            $repair->setUnavailability($hours);


            // add technician
            $repair->setTechnician($this->getUser());


            $issue->setRepair($repair);
            $repair->setIssue($issue);

            $this->em->persist($repair);
            $this->em->flush();

            $this->addFlash('success', 'La réparation a bien été enregistrée');
            return $this->redirectToRoute('repair_index');


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
}
