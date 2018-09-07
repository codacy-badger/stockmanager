<?php

namespace App\Controller;

use App\Entity\BreakdownSymptom;
use App\Form\BreakdownSymptomType;
use App\Repository\BreakdownSymptomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/breakdown/symptom")
 */
class BreakdownSymptomController extends AbstractController
{
    /**
     * @Route("/", name="breakdown_symptom_index", methods="GET")
     */
    public function index(BreakdownSymptomRepository $breakdownSymptomRepository): Response
    {
        return $this->render('breakdown_symptom/index.html.twig', ['breakdown_symptoms' => $breakdownSymptomRepository->findAll()]);
    }

    /**
     * @Route("/new", name="breakdown_symptom_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $breakdownSymptom = new BreakdownSymptom();
        $form = $this->createForm(BreakdownSymptomType::class, $breakdownSymptom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($breakdownSymptom);
            $em->flush();

            return $this->redirectToRoute('breakdown_symptom_index');
        }

        return $this->render('breakdown_symptom/new.html.twig', [
            'breakdown_symptom' => $breakdownSymptom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="breakdown_symptom_show", methods="GET")
     */
    public function show(BreakdownSymptom $breakdownSymptom): Response
    {
        return $this->render('breakdown_symptom/show.html.twig', ['breakdown_symptom' => $breakdownSymptom]);
    }

    /**
     * @Route("/{id}/edit", name="breakdown_symptom_edit", methods="GET|POST")
     */
    public function edit(Request $request, BreakdownSymptom $breakdownSymptom): Response
    {
        $form = $this->createForm(BreakdownSymptomType::class, $breakdownSymptom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('breakdown_symptom_edit', ['id' => $breakdownSymptom->getId()]);
        }

        return $this->render('breakdown_symptom/edit.html.twig', [
            'breakdown_symptom' => $breakdownSymptom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="breakdown_symptom_delete", methods="DELETE")
     */
    public function delete(Request $request, BreakdownSymptom $breakdownSymptom): Response
    {
        if ($this->isCsrfTokenValid('delete'.$breakdownSymptom->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($breakdownSymptom);
            $em->flush();
        }

        return $this->redirectToRoute('breakdown_symptom_index');
    }
}
