<?php

namespace App\Controller;

use App\Entity\Authorization;
use App\Form\AuthorizationType;
use App\Repository\AuthorizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/authorization")
 */
class AuthorizationController extends AbstractController
{
    /**
     * @Route("/", name="authorization_index", methods="GET")
     */
    public function index(AuthorizationRepository $authorizationRepository): Response
    {
        return $this->render('admin/authorization/index.html.twig', ['authorizations' => $authorizationRepository->findAll()]);
    }

    /**
     * @Route("/new", name="authorization_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $authorization = new Authorization();
        $form = $this->createForm(AuthorizationType::class, $authorization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($authorization);
            $em->flush();

            return $this->redirectToRoute('authorization_index');
        }

        return $this->render('admin/authorization/new.html.twig', [
            'authorization' => $authorization,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="authorization_show", methods="GET")
     */
    public function show(Authorization $authorization): Response
    {
        return $this->render('admin/authorization/show.html.twig', ['authorization' => $authorization]);
    }

    /**
     * @Route("/{id}/edit", name="authorization_edit", methods="GET|POST")
     */
    public function edit(Request $request, Authorization $authorization): Response
    {
        $form = $this->createForm(AuthorizationType::class, $authorization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('authorization_edit', ['id' => $authorization->getId()]);
        }

        return $this->render('admin/authorization/edit.html.twig', [
            'authorization' => $authorization,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="authorization_delete", methods="DELETE")
     */
    public function delete(Request $request, Authorization $authorization): Response
    {
        if ($this->isCsrfTokenValid('delete'.$authorization->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($authorization);
            $em->flush();
        }

        return $this->redirectToRoute('authorization_index');
    }
}
