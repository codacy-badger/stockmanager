<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Entity\Operator;
use App\Entity\Symptom;
use App\Services\PieChartGenerator;
use App\Services\Statistics;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportingController extends AbstractController
{
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


}
