<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Entity\Repair;
use App\Entity\SubcontractorRepair;
use App\Services\WarningPartThreshold;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function index()
    {
        return $this->render('admin/dashboard.html.twig', [
            'isActiveDash' => true
        ]);
    }

    /**
     * @Route("/admin/menInfoJson", name="admin_menuInfoJson", methods={"POST"})
     */
    public function menuInfoJson(WarningPartThreshold $warningPartThreshold)
    {
        $issueRepo = $this->getDoctrine()->getRepository(Issue::class);
        $subcontractorRepo = $this->getDoctrine()->getRepository(SubcontractorRepair::class);

        $warningThreshold = $warningPartThreshold->getWarningThreshold();
        $countAllIssues = $issueRepo->countAllOpenIssues();
        $countNonNotified = $issueRepo->countNonNotifed();
        $countRepair = $issueRepo->countNotRepaired();
        $countSubcontractor = $subcontractorRepo->countNotEnded();

        $response = new JsonResponse([
            'warningThreshold' => $warningThreshold,
            'countAllIssues' => $countAllIssues,
            'countNonNotified' => $countNonNotified,
            'countRepair' => $countRepair,
            'countSubcontractor' => $countSubcontractor
        ]);

        return $response;

    }

}
