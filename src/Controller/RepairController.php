<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Entity\Issue;
use App\Entity\Repair;
use App\Form\RepairType;
use App\Repository\RepairRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/repair")
 */
class RepairController extends AbstractController
{
    /**
     * @Route("/", name="repair_index", methods="GET")
     */
    public function index(RepairRepository $repairRepository): Response
    {
        //        cerate new object contract to get the constant and send it into view
        $contract = new Contract();

        $issues = $this->getDoctrine()->getRepository(Issue::class)->getEnd();

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
            $em = $this->getDoctrine()->getManager();
            $em->persist($repair);
            $em->flush();

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
            $this->getDoctrine()->getManager()->flush();

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
            $em = $this->getDoctrine()->getManager();
            $em->remove($repair);
            $em->flush();
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
    public function repairItem(Issue $issue, Request $request)
    {
        $repair = new Repair();
        $contract = new Contract();

        $form = $this->get('form.factory')->create(RepairType::class, $repair);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repair->setStartDate(new \DateTime());

            $issue->setRepair($repair);

            $em = $this->getDoctrine()->getManager();
            $em->persist($repair);
            $em->flush();

            $this->addFlash('success', 'La réparation a bien été enregistrée');
            return $this->redirectToRoute('repair_index');


        }

        return $this->render('admin/repair/repairItem.html.twig', [
            'form' => $form->createView(),
            'issue' => $issue,
            'contract' => $contract
        ]);
    }
}
