<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Entity\Operator;
use App\Entity\Repair;
use App\Entity\Symptom;
use App\Services\PieChartGenerator;
use App\Services\Statistics;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportingController extends AbstractController
{


    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em){

        $this->em = $em;
    }

    /**
     * @Route("admin/reporting/get-opened-issues-by-operator", name="reporting_getIssueByOperator")
     */
    public function getIssuesByOperator()
    {
        $operators = $this->getDoctrine()->getRepository(Operator::class)->getOperatorWithNotEndedIssues();

        return $this->render('admin/reporting/_tablesOpenIssues.html.twig', [
            'operators' => $operators
        ]);
    }

    /**
     * @Route("admin/reporting/pieSymptoms", name="reporting_pieSymptoms")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pieSymptoms(PieChartGenerator $pieChartGenerator)
    {

        $symptoms = $this->getDoctrine()->getRepository(Issue::class)->getSymptoms();


        $pieChart = $pieChartGenerator->generate($symptoms, 300, 500);


        return $this->render('admin/reporting/_pieSymptoms.html.twig', ['piechart' => $pieChart]);
    }


    /**
     * @Route("admin/reporting/", name="reporting_index")
     */
    public function index()
    {
        $countRealIssues = $this->em->getRepository(Repair::class)->countRealIssues();
        $countFakeIssues = $this->em->getRepository(Repair::class)->countFakeIssues();

        return $this->render('admin/reporting/index.html.twig', [
            'countRealIssues' => $countRealIssues,
            'countFakeIssues' => $countFakeIssues
        ]);

    }




}
