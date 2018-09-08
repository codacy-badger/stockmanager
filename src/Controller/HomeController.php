<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        $issues = $this->getDoctrine()->getRepository(Issue::class)->findAll();

        return $this->render('home/index.html.twig', [
            'issues' => $issues,
        ]);
    }

    /**
     * @Route("/autocomplete", name="home_autocomplete", defaults={"_format"="json"})
     * @param $term
     */
    public function autocomplete(Request $request)
    {
        $term = $request->query->get('term'); // use "term" instead of "q" for jquery-ui
        $results = $this->getDoctrine()->getRepository('App:Equipment')->findLike($term);

       return $this->render('equipment/search.json.twig', [
          'equipments' => $results
       ]);
    }

}
