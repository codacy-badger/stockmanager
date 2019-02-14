<?php

namespace App\Controller;

use App\Services\AvailabilityProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MassProcessingController extends AbstractController
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
     * @Route("/admin/mass-processing", name="mass_processing")
     */
    public function index()
    {
        return $this->render('admin/mass_processing/index.html.twig', [

        ]);
    }

    /**
     * @Route("/admin/mass-processing/availability", name="mass_availability", methods={"POST"})
     */
    public function setAvailability(Request $request, AvailabilityProcessor $availabilityProcessor)
    {

        $submitedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('mass-availability', $submitedToken)) {

            $availabilityProcessor->generateAvailability();
            $this->addFlash('info', 'Génération des dates de débuts d\'invalidités effectué');
        }


        return $this->redirectToRoute('mass_processing');
    }


    /**
     * @Route("/admin/mass-processing/availability", name="mass_availability", methods={"POST"})
     */
    public function setLocation(Request $request, AvailabilityProcessor $availabilityProcessor)
    {

        $submitedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('mass-availability', $submitedToken)) {

            $availabilityProcessor->generateAvailability();
            $this->addFlash('info', 'Génération des dates de débuts d\'invalidités effectué');
        }


        return $this->redirectToRoute('mass_processing');
    }
}
