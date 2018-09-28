<?php

namespace App\Controller;


use App\Entity\Transportation;
use App\Form\TransportationType;
use App\Repository\TransportationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/transportation")
 */
class TransportationController extends AbstractController
{
    /**
     * @Route("/", name="transportation_index", methods="GET")
     */
    public function index(TransportationRepository $transportationRepository): Response
    {
        return $this->render('admin/transportation/index.html.twig', ['transportations' => $transportationRepository->findAll()]);
    }

    /**
     * @Route("/new", name="transportation_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $transportation = new Transportation();
        $form = $this->createForm(TransportationType::class, $transportation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transportation);
            $em->flush();

            return $this->redirectToRoute('transportation_index');
        }

        return $this->render('admin/transportation/new.html.twig', [
            'transportation' => $transportation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transportation_show", methods="GET")
     */
    public function show(Transportation $transportation): Response
    {
        return $this->render('admin/transportation/show.html.twig', ['transportation' => $transportation]);
    }

    /**
     * @Route("/{id}/edit", name="transportation_edit", methods="GET|POST")
     */
    public function edit(Request $request, Transportation $transportation): Response
    {
        $form = $this->createForm(TransportationType::class, $transportation);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('transportation_edit', ['id' => $transportation->getId()]);
        }

        return $this->render('admin/transportation/edit.html.twig', [
            'transportation' => $transportation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transportation_delete", methods="DELETE")
     */
    public function delete(Request $request, Transportation $transportation): Response
    {
        if ($this->isCsrfTokenValid('delete-transportation', $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($transportation);
            $em->flush();
        }

        return $this->redirectToRoute('transportation_index');
    }
}
