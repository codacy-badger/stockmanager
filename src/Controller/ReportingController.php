<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Issue;
use App\Entity\Operator;
use App\Entity\Repair;
use App\Entity\Report;
use App\Entity\ReportContract;
use App\Services\MTBFGenerator;
use App\Services\MTTRGenerator;
use App\Services\PieChartGenerator;
use App\Services\RateStatistics;
use App\Services\RepairExportXlsx;
use App\Services\ReportGenerator;
use App\Services\ReportGeneratorContract;
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
    public function report(Request $request, ReportGeneratorContract $reportGeneratorContract, ReportGenerator $reportGenerator, MTBFGenerator $MTBFGenerator, MTTRGenerator $MTTRGenerator, RateStatistics $rateStatistics)
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


            /** @var \DateTime $startDate */
            $startDate = $data['startDate'];
            /** @var \DateTime $endDate */
            $endDate = $data['endDate'];

            if ($startDate > $endDate) {
                $this->addFlash('danger', 'La date de début ne peut pas être supérieure à la date de fin');
                return $this->redirectToRoute('reporting_report');
            }

            //generation du tableau de dispo
            $reportGeneratorContract->generate($startDate, $endDate);

            $reportGenerator->generate($startDate, $endDate);

//            réccupération du tableau
            $availabilites = $this->em->getRepository(ReportContract::class)->findAllOrder();


            $availabilitesInfo = $this->em->getRepository(Report::class)->findAll();

            //calcul du taux de dispo global

            //variables pour les embarqués
            $embTotalContractual = 0;
            $embTotalIssue = 0;
            $embTotalRepairTime = 0;

            //variables globales

            $totalContractual = 0;
            $totalIssue = 0;
            $totalRepairTime = 0;

            //pour les embarqués
            $embHoursPerDay = 0;
            $embCountItem = 0;

            //globale
            $totalHoursPerDay = 0;
            $countItem = 0;


            foreach ($availabilites as $categoryAvalabilites) {

                if ($categoryAvalabilites->getCategory()->getIsEmbeded()) {

                    $issueQuantity = $categoryAvalabilites->getIssueQuantity();
                    $contractualQuantity = $categoryAvalabilites->getContractualQuantity();
                    $repairTime = $categoryAvalabilites->getRepairTime();


                    $embTotalIssue = $embTotalIssue + $issueQuantity;
                    $embTotalContractual = $embTotalContractual + $contractualQuantity;
                    $embTotalRepairTime = $embTotalRepairTime + $repairTime;

                    $embHoursPerDay = $embHoursPerDay + $categoryAvalabilites->getCategory()->getHoursPerDay();
                    $embCountItem++;
                }

                $totalIssue = $totalIssue + $categoryAvalabilites->getIssueQuantity();
                $totalContractual = $totalContractual + $categoryAvalabilites->getContractualQuantity();
                $totalRepairTime = $totalRepairTime + $categoryAvalabilites->getRepairTime();

                $totalHoursPerDay = $totalHoursPerDay + $categoryAvalabilites->getCategory()->getHoursPerDay();
                $countItem++;
            }


            $embAverage = $embHoursPerDay / $embCountItem;

            $totalAverage = $totalHoursPerDay / $countItem;

            $deltaDate = $startDate->diff($endDate);
            $numberOfDays = $deltaDate->days + 1;

            // embarqués
            $embMTBF = $MTBFGenerator->generate($numberOfDays, $embAverage, $embTotalContractual, $embTotalIssue);
            $embMTTR = $MTTRGenerator->generate($embTotalRepairTime, $embTotalIssue);

            $embRate = $rateStatistics->getRate($embMTBF, $embMTTR);


            //total
            $totalMTBF = $MTBFGenerator->generate($numberOfDays, $totalAverage, $totalContractual, $totalIssue);
            $totalMTTR = $MTTRGenerator->generate($totalRepairTime, $totalIssue);


            $totalRate = $rateStatistics->getRate($totalMTBF, $totalMTTR);


            return $this->render('admin/reporting/report.html.twig', [
                'totalMTBF' => $totalMTBF,
                'totalMTTR' => $totalMTTR,
                'totalRate' => $totalRate,
                'embMTBF' => $embMTBF,
                'embMTTR' => $embMTTR,
                'embRate' => $embRate,
                'availabilities' => $availabilites,
                'availabilitiesInfo' => $availabilitesInfo,
                'dateStart' => $startDate,
                'dateEnd' => $endDate,
            ]);


        }


        return $this->render('admin/reporting/form.html.twig', ['form' => $form->createView(),]);

    }

    /**
     * Contractual export
     *
     * @Route("admin/reporting/export/{startDate}/{endDate}", name="reporting_export", methods={"GET"})
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param RepairExportXlsx $repairExportXlsx
     */
    public function export(\DateTime $startDate, \DateTime $endDate, RepairExportXlsx $repairExportXlsx)
    {
        $issues = $this->em->getRepository(Issue::class)->findNewAll($startDate, $endDate);

        return $repairExportXlsx->export($issues);

    }

    /**
     * Export des réparations non traités
     *
     * @Route("admin/reporting/export/notFixed", name="reporting_notrepaired", methods={"GET"})
     * @param RepairExportXlsx $repairExportXlsx
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function exportRepairsNotFinished(RepairExportXlsx $repairExportXlsx)
    {
        $issues = $this->em->getRepository(Issue::class)->getNotRepaired();

        return $repairExportXlsx->export($issues);
    }

    /**
     * All export
     *
     * @Route("admin/reporting/exportAll/{startDate}/{endDate}", name="reporting_exportAll", methods={"GET"})
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param RepairExportXlsx $repairExportXlsx
     */
    public function allExport(\DateTime $startDate, \DateTime $endDate, RepairExportXlsx $repairExportXlsx)
    {
        $issues = $this->em->getRepository(Issue::class)->findAllByDate($startDate, $endDate);

        return $repairExportXlsx->export($issues);

    }


}
