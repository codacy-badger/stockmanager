<?php

namespace App\Controller;


use App\Entity\Equipment;
use App\Entity\Location;
use App\Entity\Search;
use App\Form\LocationEditFormType;
use App\Form\SearchType;
use App\Services\RateStatistics;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/location")
 */
class LocationController extends AbstractController
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
     * @Route("/", name="location_index", methods="GET|POST")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {

        return $this->render('admin/location/home.html.twig', [

        ]);
    }


    /**
     * @Route("/search", name="location_search", methods={"GET|POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request)
    {


        $search = new Search();

        $formSearch = $this->createForm(SearchType::class, $search);

        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {

            if ($search->getEquipment()) {

                $result = $this->em->getRepository(Location::class)->searchByEquipment($search->getEquipment());

            } else {
                $result = $this->em->getRepository(Location::class)->search(
                    $search->getEquipment(),
                    $search->getSite(),
                    $search->getBrand(),
                    $search->getCategory(),
                    $search->getContract()

                );
            }

            if (null === $search->getEquipment()) {
                $issues = null;
            } else {
                $issues = $search->getEquipment()->getIssues();
                return $this->render('admin/equipment/show.html.twig', [
                    'historicIssues' => $issues,
                    'locations' => $result,
                    'equipment' => $search->getEquipment(),
                ]);
            }


            return $this->render('admin/location/index.html.twig', [
                'historicIssues' => $issues,
                'locations' => $result,

            ]);


        }

        return $this->render('admin/location/search.html.twig', [
            'form' => $formSearch->createView()
        ]);


    }

    /**
     * @Route("/add/{id}", name="location_add", methods={"GET|POST"})
     * @param Request $request
     */
    public function addLocation(Request $request, Equipment $equipment)
    {

        $location = new Location();

        $form = $this->createForm(LocationEditFormType::class, $location);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location->setEquipment($equipment);
            $this->em->persist($location);
            $this->em->flush();

            $this->addFlash('success', "La nouvelle localisation a bien été enregistrée.");
            return $this->redirectToRoute('location_show', [
                'id' => $equipment->getId()
            ]);


        }

        return $this->render('admin/location/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/show/{id}", name="location_show", methods={"GET|POST"})
     * @param Equipment $equipement
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showLocations(Equipment $equipement)
    {

        $searchLocations = $this->em->getRepository(Location::class)->search($equipement);

        return $this->render('admin/equipment/show.html.twig', [
            'historicIssues' => $equipement->getIssues(),
            'locations' => $searchLocations,
            'equipment' => $equipement,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="location_delete", methods="DELETE")
     */
    public function delete(Request $request, Location $location)
    {
        $equipment = $location->getEquipment();

        if ($this->isCsrfTokenValid('delete-location', $request->request->get('_token'))) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($location);
            $em->flush();

            $this->addFlash('success', 'La localisation a bien été supprimée');
        }

        return $this->redirectToRoute('equipment_show', [
            'id' => $equipment->getId()
        ]);
    }


}
