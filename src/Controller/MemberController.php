<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Entity\Issue;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/member")
 * Class MemberController
 * @package App\Controller
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/", name="member_index")
     */
    public function index()
    {
//        get the current user logged in
        $user = $this->getUser();

//        find all issues by the current user
        $issues = $this->getDoctrine()->getRepository(Issue::class)->findByUser($user);

        return $this->render('member/index.html.twig', [
            'issues' => $issues,
//            bootstrap active link
            'isActiveDash' => true
        ]);
    }


    /**
     * @Route("/count-issue", name="member_countIssue")
     * @return Response
     */
    public function countIssue()
    {
        $count = $this->getDoctrine()->getRepository(Issue::class)->countByUser($this->getUser());

        return $this->render('member/issue/_countIssue.html.twig', [
            'count' => $count
        ]);
    }


    /**
     * @Route("/equipment_search", name="equipment_search", defaults={"_format"="json"})
     * @param $term
     */
    public function searchEquipmeent(Request $request)
    {
        $term = $request->query->get('term'); // use "term" instead of "q" for jquery-ui
        $results = $this->getDoctrine()->getRepository('App:Equipment')->findLike($term);

        return $this->render('admin/equipment/search.json.twig', [
            'equipments' => $results
        ]);
    }

    /**
     * @Route("/equipment_get", name="equipment_get")
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getEquipment($id = null)
    {
        $equipement = $this->getDoctrine()->getRepository(Equipment::class)->find($id);

        return $this->json($equipement->getSerial());
    }

}
