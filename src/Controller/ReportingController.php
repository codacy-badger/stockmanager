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
        $i = 0;
        $table = [];

        foreach ($symptoms as $symptom) {

            if (!empty($symptom[$i]['name'])) {

                $table = [$symptom[$i]['name'], intval([$symptom[$i][1]])];

            }
            $i++;

        }

        dump($table);


        $pieChart->getData()->setArrayToDataTable(
            $table
        );


        return $this->render('admin/reporting/_pieSymptoms.html.twig', ['piechart' => $pieChart]);
    }
}
