<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Issue;
use App\Entity\Operator;
use App\Entity\Repair;
use App\Entity\Statistics;
use App\Services\PieChartGenerator;
use App\Services\ReportGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

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

    /**
     * Formulaire de reporting
     * 
     * @Route("admin/reporting/report", name="reporting_report")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function report(Request $request, ReportGenerator $reportGenerator)
    {

        $defaultData = null;

        $form = $this->createFormBuilder($defaultData)
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

//            Compte le nombre de ticket fermés
            $issueEnd = $this->em->getRepository(Issue::class)->countResolvedIssues(
                $data['startDate'],
                $data['endDate']
            );

//            Compte le nombre de tickets ouverts
            $issueStart = $this->em->getRepository(Issue::class)->countOpenedIssues(
                $data['startDate'],
                $data['endDate']
            );

//            Compte le nombre de réparations traités
            $repairs = $this->em->getRepository(Repair::class)->countRepaired(
                $data['startDate'],
                $data['endDate']
            );


//            Réccupération de touts les issues correspondants à la periode souhaitée
            $issues = $this->em->getRepository(Issue::class)->getOperatorIssuesByPeriod(
                $data['startDate'],
                $data['endDate']
            );


            //affichage du rapport global
            $report = $reportGenerator->generate();




            return $this->render('admin/reporting/report.html.twig', [
                'issueEnd' => $issueEnd,
                'issueStart' => $issueStart,
                'issues' => $issues,
                'repaired' => $repairs
            ]);


        }


        return $this->render('admin/reporting/form.html.twig', [
            'form' => $form->createView(),

        ]);

    }


}
