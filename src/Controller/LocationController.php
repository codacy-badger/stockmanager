<?php

namespace App\Controller;


use App\Entity\Location;
use App\Form\LocationType;
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

        $locations = $this->em->getRepository(Location::class)->findAll();


        return $this->render('admin/location/index.html.twig', [
            'locations' => $locations,
        ]);
    }


    /**
     * @Route("/search", name="location_search")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request)
    {

        $location = new Location();

        $form = $this->createForm(LocationType::class, $location);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $result = $this->em->getRepository(Location::class)->findBy([
                'equipment' => $location->getEquipment()

            ]);



            return $this->render('admin/location/index.html.twig', [
                'form' => $form->createView(),
                'locations' => $result
            ]);


        }

        return $this->render('admin/location/search.html.twig', [
            'form' => $form->createView(),

        ]);


    }


}
