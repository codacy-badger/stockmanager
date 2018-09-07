<?php

namespace App\Controller;

use App\Entity\IssueSymptom;
use App\Form\IssueSymptomType;
use App\Repository\IssueSymptomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/issue/symptom")
 */
class IssueSymptomController extends AbstractController
{
    /**
     * @Route("/", name="issue_symptom_index", methods="GET")
     */
    public function index(IssueSymptomRepository $issueSymptomRepository): Response
    {
        return $this->render('issue_symptom/index.html.twig', ['issue_symptoms' => $issueSymptomRepository->findAll()]);
    }

    /**
     * @Route("/new", name="issue_symptom_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $issueSymptom = new IssueSymptom();
        $form = $this->createForm(IssueSymptomType::class, $issueSymptom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($issueSymptom);
            $em->flush();

            return $this->redirectToRoute('issue_symptom_index');
        }

        return $this->render('issue_symptom/new.html.twig', [
            'issue_symptom' => $issueSymptom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="issue_symptom_show", methods="GET")
     */
    public function show(IssueSymptom $issueSymptom): Response
    {
        return $this->render('issue_symptom/show.html.twig', ['issue_symptom' => $issueSymptom]);
    }

    /**
     * @Route("/{id}/edit", name="issue_symptom_edit", methods="GET|POST")
     */
    public function edit(Request $request, IssueSymptom $issueSymptom): Response
    {
        $form = $this->createForm(IssueSymptomType::class, $issueSymptom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('issue_symptom_edit', ['id' => $issueSymptom->getId()]);
        }

        return $this->render('issue_symptom/edit.html.twig', [
            'issue_symptom' => $issueSymptom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="issue_symptom_delete", methods="DELETE")
     */
    public function delete(Request $request, IssueSymptom $issueSymptom): Response
    {
        if ($this->isCsrfTokenValid('delete'.$issueSymptom->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($issueSymptom);
            $em->flush();
        }

        return $this->redirectToRoute('issue_symptom_index');
    }
}
