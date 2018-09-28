<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/brand")
 */
class BrandController extends AbstractController
{
    /**
     * @Route("/", name="brand_index", methods="GET")
     * @param BrandRepository $brandRepository
     * @return Response
     */
    public function index(BrandRepository $brandRepository): Response
    {
        return $this->render('admin/brand/index.html.twig', ['brands' => $brandRepository->findAll()]);
    }

    /**
     * @Route("/new", name="brand_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($brand);
            $em->flush();

            return $this->redirectToRoute('brand_index');
        }

        return $this->render('admin/brand/new.html.twig', [
            'brand' => $brand,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="brand_show", methods="GET")
     * @param Brand $brand
     * @return Response
     */
    public function show(Brand $brand): Response
    {
        return $this->render('admin/brand/show.html.twig', ['brand' => $brand]);
    }

    /**
     * Edit a brand
     * @Route("/{id}/edit", name="brand_edit", methods="GET|POST")
     * @param Request $request
     * @param Brand $brand
     * @return Response
     */
    public function edit(Request $request, Brand $brand): Response
    {
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('brand_edit', ['id' => $brand->getId()]);
        }

        return $this->render('admin/brand/edit.html.twig', [
            'brand' => $brand,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a brand
     * @Route("/{id}", name="brand_delete", methods="DELETE")
     * @param Request $request
     * @param Brand $brand
     * @return Response
     */
    public function delete(Request $request, Brand $brand): Response
    {
        if ($this->isCsrfTokenValid('delete-brand', $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($brand);
            $em->flush();
        }

        return $this->redirectToRoute('brand_index');
    }
}
