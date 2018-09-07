<?php

namespace App\Controller;

use App\Entity\EquipmentSoftware;
use App\Form\EquipmentSoftwareType;
use App\Repository\EquipmentSoftwareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/equipment/software")
 */
class EquipmentSoftwareController extends AbstractController
{
    /**
     * @Route("/", name="equipment_software_index", methods="GET")
     */
    public function index(EquipmentSoftwareRepository $equipmentSoftwareRepository): Response
    {
        return $this->render('equipment_software/index.html.twig', ['equipment_softwares' => $equipmentSoftwareRepository->findAll()]);
    }

    /**
     * @Route("/new", name="equipment_software_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $equipmentSoftware = new EquipmentSoftware();
        $form = $this->createForm(EquipmentSoftwareType::class, $equipmentSoftware);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($equipmentSoftware);
            $em->flush();

            return $this->redirectToRoute('equipment_software_index');
        }

        return $this->render('equipment_software/new.html.twig', [
            'equipment_software' => $equipmentSoftware,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="equipment_software_show", methods="GET")
     */
    public function show(EquipmentSoftware $equipmentSoftware): Response
    {
        return $this->render('equipment_software/show.html.twig', ['equipment_software' => $equipmentSoftware]);
    }

    /**
     * @Route("/{id}/edit", name="equipment_software_edit", methods="GET|POST")
     */
    public function edit(Request $request, EquipmentSoftware $equipmentSoftware): Response
    {
        $form = $this->createForm(EquipmentSoftwareType::class, $equipmentSoftware);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('equipment_software_edit', ['id' => $equipmentSoftware->getId()]);
        }

        return $this->render('equipment_software/edit.html.twig', [
            'equipment_software' => $equipmentSoftware,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="equipment_software_delete", methods="DELETE")
     */
    public function delete(Request $request, EquipmentSoftware $equipmentSoftware): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipmentSoftware->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($equipmentSoftware);
            $em->flush();
        }

        return $this->redirectToRoute('equipment_software_index');
    }
}
