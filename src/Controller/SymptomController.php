<?php

namespace App\Controller;

use App\Entity\Symptom;
use App\Form\SymptomType;
use App\Repository\BreakdownSymptomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/symptom")
 */
class SymptomController extends AbstractController
{
    /**
     * @Route("/", name="symptom_index", methods="GET")
     * @param BreakdownSymptomRepository $breakdownSymptomRepository
     * @return Response
     */
    public function index(BreakdownSymptomRepository $breakdownSymptomRepository): Response
    {
        return $this->render('admin/symptom/index.html.twig', ['symptoms' => $breakdownSymptomRepository->findAll()]);
    }

    /**
     * @Route("/new", name="symptom_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $symptom = new Symptom();
        $form = $this->createForm(SymptomType::class, $symptom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($symptom);
            $em->flush();

            return $this->redirectToRoute('symptom_index');
        }

        return $this->render('admin/symptom/new.html.twig', [
            'symptom' => $symptom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="symptom_show", methods="GET")
     * @param Symptom $symptom
     * @return Response
     */
    public function show(Symptom $symptom): Response
    {
        return $this->render('admin/symptom/show.html.twig', ['symptom' => $symptom]);
    }

    /**
     * @Route("/{id}/edit", name="symptom_edit", methods="GET|POST")
     * @param Request $request
     * @param Symptom $symptom
     * @return Response
     */
    public function edit(Request $request, Symptom $symptom): Response
    {
        $form = $this->createForm(SymptomType::class, $symptom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('symptom_edit', ['id' => $symptom->getId()]);
        }

        return $this->render('admin/symptom/edit.html.twig', [
            'symptom' => $symptom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="symptom_delete", methods="DELETE")
     * @param Request $request
     * @param Symptom $symptom
     * @return Response
     */
    public function delete(Request $request, Symptom $symptom): Response
    {
        if ($this->isCsrfTokenValid('delete'.$symptom->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($symptom);
            $em->flush();
        }

        return $this->redirectToRoute('symptom_index');
    }
}
