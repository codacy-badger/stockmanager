<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Entity\Issue;
use App\Entity\Location;
use App\Entity\Repair;
use App\Entity\Site;
use App\Form\EquipmentType;
use App\Form\LocationType;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/equipment")
 */
class EquipmentController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/", name="equipment_index", methods="GET")
     * @param EquipmentRepository $equipmentRepository
     * @return Response
     */
    public function index(EquipmentRepository $equipmentRepository): Response
    {
        return $this->render('admin/equipment/index.html.twig', ['equipment' => $equipmentRepository->findAll()]);
    }

    /**
     * @Route("/new", name="equipment_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $equipment = new Equipment();
        $form = $this->createForm(EquipmentType::class, $equipment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $homeSite = $this->em->getRepository(Site::class)->findOneBy(['id' => Site::SITEOISE]);

            $location = new Location();
            $location
                ->setSite($homeSite)
                ->setEquipment($equipment)
                ->setIsOk(true)
                ->setDate(new \DateTime());

            $this->em->persist($equipment);
            $this->em->persist($location);

            $this->em->flush();

            return $this->redirectToRoute('equipment_index');
        }

        return $this->render('admin/equipment/new.html.twig', [
            'equipment' => $equipment,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="equipment_edit", methods="GET|POST")
     * @param Request $request
     * @param Equipment $equipment
     * @return Response
     */
    public function edit(Request $request, Equipment $equipment): Response
    {
        $form = $this->createForm(EquipmentType::class, $equipment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('equipment_edit', ['id' => $equipment->getId()]);
        }

        return $this->render('admin/equipment/edit.html.twig', [
            'equipment' => $equipment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="equipment_delete", methods="DELETE")
     * @param Request $request
     * @param Equipment $equipment
     * @return Response
     */
    public function delete(Request $request, Equipment $equipment): Response
    {
        if ($this->isCsrfTokenValid('delete-equipment', $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($equipment);
            $em->flush();
        }

        return $this->redirectToRoute('equipment_index');
    }


    /**
     * Permet d'avoir toutes les informations sur un équipement
     * @Route("/show/{id}/{dontShowBootstrap}", name="equipment_show", methods={"GET|POST"})
     * @param Equipment $equipment
     * @param null $dontShowBootstrap
     * @return Response
     */
    public function show(Equipment $equipment, $dontShowBootstrap = null)
    {
        //get all issues concerning the current equipment
        $historicIssues = $this->em->getRepository(Issue::class)->findByEquipment($equipment);

        //get all repairs concerning the current equipment
        $oldRepairs = $this->em->getRepository(Repair::class)->findUnavailabilities($equipment->getId());

        //get all locations
        $locations = $this->em->getRepository(Location::class)->findBy([
            'equipment' => $equipment,
        ], [
            'date' => 'DESC',
            'id' => 'DESC'
        ]);

        // vérifie si la demande provient de la page de réparation ou non
        if (null !== $dontShowBootstrap) {

            $viewPath = 'admin/equipment/_show.html.twig';
        } else {
            $viewPath = 'admin/equipment/show.html.twig';
        }

        return $this->render($viewPath, [

            'historicIssues' => $historicIssues,
            'locations' => $locations,
            'equipment' => $equipment,

        ]);

    }


}
