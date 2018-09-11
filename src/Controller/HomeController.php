<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("member/", name="home")
     */
    public function index()
    {
//        get the current user logged in
        $user = $this->getUser();

//        find all issues by the current user
        $issues = $this->getDoctrine()->getRepository(Issue::class)->findByUser($user);

        return $this->render('home/index.html.twig', [
            'issues' => $issues,
        ]);
    }

    /**
     * @Route("member/autocomplete", name="home_autocomplete", defaults={"_format"="json"})
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
