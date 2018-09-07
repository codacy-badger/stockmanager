<?php

namespace App\Controller;

use App\Entity\Software;
use App\Form\SoftwareType;
use App\Repository\SoftwareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/software")
 */
class SoftwareController extends AbstractController
{
    /**
     * @Route("/", name="software_index", methods="GET")
     */
    public function index(SoftwareRepository $softwareRepository): Response
    {
        return $this->render('software/index.html.twig', ['softwares' => $softwareRepository->findAll()]);
    }

    /**
     * @Route("/new", name="software_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $software = new Software();
        $form = $this->createForm(SoftwareType::class, $software);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($software);
            $em->flush();

            return $this->redirectToRoute('software_index');
        }

        return $this->render('software/new.html.twig', [
            'software' => $software,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="software_show", methods="GET")
     */
    public function show(Software $software): Response
    {
        return $this->render('software/show.html.twig', ['software' => $software]);
    }

    /**
     * @Route("/{id}/edit", name="software_edit", methods="GET|POST")
     */
    public function edit(Request $request, Software $software): Response
    {
        $form = $this->createForm(SoftwareType::class, $software);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('software_edit', ['id' => $software->getId()]);
        }

        return $this->render('software/edit.html.twig', [
            'software' => $software,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="software_delete", methods="DELETE")
     */
    public function delete(Request $request, Software $software): Response
    {
        if ($this->isCsrfTokenValid('delete'.$software->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($software);
            $em->flush();
        }

        return $this->redirectToRoute('software_index');
    }
}
