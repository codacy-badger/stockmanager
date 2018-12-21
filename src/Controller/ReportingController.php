<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Entity\Operator;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function pieSymptoms()
    {

        $symptoms = $this->getDoctrine()->getRepository(Issue::class)->getSymptoms();

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [

                [$symptoms[0]['name'], $symptoms[0][1]],
                [$symptoms[1]['name'], $symptoms[1][1]],
                [$symptoms[2]['name'], $symptoms[2][1]],
                [$symptoms[3]['name'], $symptoms[3][1]],
                [$symptoms[4]['name'], $symptoms[4][1]],
                [$symptoms[5]['name'], $symptoms[5][1]]


            ]
        );



        return $this->render('admin/reporting/_pieSymptoms.html.twig', [
            'piechart' => $pieChart
        ]);
    }
}
