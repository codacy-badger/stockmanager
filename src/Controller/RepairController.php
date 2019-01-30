<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Entity\Equipment;
use App\Entity\EquipmentStatus;
use App\Entity\Issue;
use App\Entity\Repair;
use App\Form\RepairType;
use App\Repository\RepairRepository;
use App\Services\MTBFStatistics;
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
        //        cerate new object contract to get the constant and send it into view
        $contract = new Contract();

        $issues = $this->em->getRepository(Issue::class)->getNotRepaired();

        return $this->render('admin/repair/index.html.twig', [
            'issues' => $issues,
            'contract' => $contract
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
    public function repairItem(Issue $issue, Request $request, MTBFStatistics $mtbf)
    {
        $repair = new Repair();
        $contract = new Contract();

        $historicIssues = $this->em->getRepository(Issue::class)->findByEquipment($issue->getEquipment());


        $now = new \DateTime();
        $before = new \DateTime('2011-09-01');
        $interval = $now->diff($before);

        $hoursPerDay = $issue->getEquipment()->getBrand()->getCategory()->getHoursPerDay();

        if (null !== $hoursPerDay) {

            $mtbfResult = $mtbf->getMTBF($interval->days, $hoursPerDay, 1, count($historicIssues));

        } else {
            $mtbfResult = 'Non calculé';
        }

        $form = $this->get('form.factory')->create(RepairType::class, $repair);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $status = $this->em->getRepository(EquipmentStatus::class)->findOneBy([
                'equipment' => $issue->getEquipment(),
                'endFailure' => null
            ]);


            if (null !== $status) {
                $status->setEndFailure(new \DateTime());
            }
            $repair->setStartDate(new \DateTime());
            $repair->setTechnician($this->getUser());

            $issue->setRepair($repair);

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
            'mtbf' => $mtbfResult
        ]);
    }
}
