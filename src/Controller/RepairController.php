<?php

namespace App\Controller;

use App\Entity\Repair;
use App\Form\RepairType;
use App\Repository\RepairRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/repair")
 */
class RepairController extends AbstractController
{
    /**
     * @Route("/", name="repair_index", methods="GET")
     */
    public function index(RepairRepository $repairRepository): Response
    {
        return $this->render('repair/index.html.twig', ['repairs' => $repairRepository->findAll()]);
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

        return $this->render('repair/new.html.twig', [
            'repair' => $repair,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="repair_show", methods="GET")
     */
    public function show(Repair $repair): Response
    {
        return $this->render('repair/show.html.twig', ['repair' => $repair]);
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

        return $this->render('repair/edit.html.twig', [
            'repair' => $repair,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="repair_delete", methods="DELETE")
     */
    public function delete(Request $request, Repair $repair): Response
    {
        if ($this->isCsrfTokenValid('delete'.$repair->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($repair);
            $em->flush();
        }

        return $this->redirectToRoute('repair_index');
    }
}
