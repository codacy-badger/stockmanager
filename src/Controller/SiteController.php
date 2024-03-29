<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/site")
 */
class SiteController extends AbstractController
{
    /**
     * @Route("/", name="site_index", methods="GET")
     */
    public function index(SiteRepository $siteRepository): Response
    {
        return $this->render('admin/site/index.html.twig', ['sites' => $siteRepository->findAll()]);
    }

    /**
     * @Route("/new", name="site_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();

            return $this->redirectToRoute('site_index');
        }

        return $this->render('admin/site/new.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="site_show", methods="GET")
     */
    public function show(Site $site): Response
    {
        return $this->render('admin/site/show.html.twig', ['site' => $site]);
    }

    /**
     * @Route("/{id}/edit", name="site_edit", methods="GET|POST")
     */
    public function edit(Request $request, Site $site): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('site_edit', ['id' => $site->getId()]);
        }

        return $this->render('admin/site/edit.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="site_delete", methods="DELETE")
     */
    public function delete(Request $request, Site $site): Response
    {
        if ($this->isCsrfTokenValid('delete-site', $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($site);
            $em->flush();
        }

        return $this->redirectToRoute('site_index');
    }
}
