<?php

namespace App\Controller;

use App\Entity\Operator;
use App\Form\OperatorType;
use App\Repository\OperatorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/operator")
 */
class OperatorController extends AbstractController
{
    /**
     * @Route("/", name="operator_index", methods="GET")
     */
    public function index(OperatorRepository $operatorRepository): Response
    {
        return $this->render('admin/operator/index.html.twig', ['operators' => $operatorRepository->findAll()]);
    }

    /**
     * @Route("/new", name="operator_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $operator = new Operator();
        $form = $this->createForm(OperatorType::class, $operator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($operator);
            $em->flush();

            return $this->redirectToRoute('operator_index');
        }

        return $this->render('admin/operator/new.html.twig', [
            'operator' => $operator,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="operator_show", methods="GET")
     */
    public function show(Operator $operator): Response
    {
        return $this->render('admin/operator/show.html.twig', ['operator' => $operator]);
    }

    /**
     * @Route("/{id}/edit", name="operator_edit", methods="GET|POST")
     */
    public function edit(Request $request, Operator $operator): Response
    {
        $form = $this->createForm(OperatorType::class, $operator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('operator_edit', ['id' => $operator->getId()]);
        }

        return $this->render('admin/operator/edit.html.twig', [
            'operator' => $operator,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="operator_delete", methods="DELETE")
     */
    public function delete(Request $request, Operator $operator): Response
    {
        if ($this->isCsrfTokenValid('delete-operator', $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($operator);
            $em->flush();
        }

        return $this->redirectToRoute('operator_index');
    }
}
