<?php

namespace App\Controller;

use App\Entity\Part;
use App\Form\PartType;
use App\Repository\PartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/part")
 */
class PartController extends AbstractController
{
    /**
     * @Route("/", name="part_index", methods="GET")
     */
    public function index(PartRepository $partRepository): Response
    {
        return $this->render('part/index.html.twig', ['parts' => $partRepository->findAll()]);
    }

    /**
     * @Route("/new", name="part_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $part = new Part();
        $form = $this->createForm(PartType::class, $part);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($part);
            $em->flush();

            return $this->redirectToRoute('part_index');
        }

        return $this->render('part/new.html.twig', [
            'part' => $part,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="part_show", methods="GET")
     */
    public function show(Part $part): Response
    {
        return $this->render('part/show.html.twig', ['part' => $part]);
    }

    /**
     * @Route("/{id}/edit", name="part_edit", methods="GET|POST")
     */
    public function edit(Request $request, Part $part): Response
    {
        $form = $this->createForm(PartType::class, $part);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('part_edit', ['id' => $part->getId()]);
        }

        return $this->render('part/edit.html.twig', [
            'part' => $part,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="part_delete", methods="DELETE")
     */
    public function delete(Request $request, Part $part): Response
    {
        if ($this->isCsrfTokenValid('delete'.$part->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($part);
            $em->flush();
        }

        return $this->redirectToRoute('part_index');
    }
}
