<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Issue;
use App\Entity\Operator;
use App\Entity\Repair;
use App\Entity\Statistics;
use App\Services\PieChartGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReportingController extends AbstractController
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
        $countCurrentMonthIssues = $this->em->getRepository(Repair::class)->findAll();

        $categories = $this->em->getRepository(Category::class)->findAll();

        $now = new \DateTime();
        $currentDate = new \DateTime();
        $oldDate = $currentDate->sub(new \DateInterval('P30D'));

        foreach ($categories as $category) {

            $unavailbility[$category->getId()] = $this->em->getRepository(Repair::class)->getUnavaillabilityByCategoryByPeriod($category, $oldDate, $now);

//            $numberIssue[$category->getId()] = $this->em->getRepository(Repair::class)->
        }


        return $this->render('admin/reporting/index.html.twig', [
            'countRealIssues' => $countRealIssues,
            'countFakeIssues' => $countFakeIssues,
            'unavaibilityArray' => $unavailbility
        ]);

    }


}
