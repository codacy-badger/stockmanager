<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 20/03/2019
 * Time: 10:31
 */

namespace App\Services;


use App\Entity\Category;
use App\Entity\Issue;
use App\Entity\Report;
use Doctrine\ORM\EntityManagerInterface;

class ReportGenerator
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var DateDiffHour
     */
    private $dateDiffHour;

    /**
     * @var MTTRStatistics
     */
    private $MTTRStatistics;
    /**
     * @var RateStatistics
     */
    private $rateStatistics;
    /**
     * @var MTBFGenerator
     */
    private $MTBFGenerator;

    /**
     * ReportGenerator constructor.
     * @param EntityManagerInterface $entityManager
     * @param DateDiffHour $dateDiffHour
     */
    public function __construct(EntityManagerInterface $entityManager, DateDiffHour $dateDiffHour, MTBFGenerator $MTBFGenerator, MTTRGenerator $MTTRStatistics, RateStatistics $rateStatistics)
    {
        $this->entityManager = $entityManager;
        $this->dateDiffHour = $dateDiffHour;

        $this->MTTRStatistics = $MTTRStatistics;
        $this->rateStatistics = $rateStatistics;
        $this->MTBFGenerator = $MTBFGenerator;
    }

    public function generate(\DateTime $startDate, \DateTime $endDate)
    {
        //delete content in table report

        $this->entityManager->getRepository(Report::class)->delete();
        $this->entityManager->flush();

        $categories = $this->entityManager->getRepository(Category::class)->findBy([
            'isContractual' => true
        ]);


        /** @var Category $category */
        foreach ($categories as $category) {

            $brands = $category->getBrands();

            $countIssuesCategory = 0;
            $countFakeIssuesCategory = 0;
            $repairTimeCategory = 0;


            foreach ($brands as $brand) {

                //get real issues
                $issues = $this->entityManager->getRepository(Issue::class)->findByBrand($brand, $startDate, $endDate);

                $countIssuesCategory = $countIssuesCategory + count($issues);


                //count fake issues
                $countFakeIssues = $this->entityManager->getRepository(Issue::class)->countFakeIssues($brand, $startDate, $endDate);

                $countFakeIssuesCategory = $countFakeIssuesCategory + $countFakeIssues;

                $repairTime = 0;

                /** @var Issue $issue */
                foreach ($issues as $issue) {

                    $dateRequest = $issue->getDateRequest();
                    $repairDate = $issue->getRepair()->getDateEnd();

                    $repairIssueTime = $this->dateDiffHour->getDiff($repairDate, $dateRequest);

                    $repairTime = $repairTime + $repairIssueTime;

                }

                $repairTimeCategory = $repairTimeCategory + $repairTime;


            }

            $numberOfDays = $startDate->diff($endDate);

            $mtbf = $this->MTBFGenerator->generate($numberOfDays->d, $category->getHoursPerDay(), $category->getContractualQuantity(), $countIssuesCategory);
            $mttr = $this->MTTRStatistics->generate($repairTimeCategory, $countIssuesCategory);

            //if mtbf is null then the rate is 100%
            if(null !== $mtbf) {
                $rate = $this->rateStatistics->getRate($mtbf, $mttr);
            }else{
                $rate = 1;
            }

            $report = new Report();
            $report->setIssueQuantity($countIssuesCategory)
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setCategory($category)
                ->setFakeIssueQuantity($countFakeIssuesCategory)
                ->setRepairTime($repairTimeCategory)
                ->setMtbf($mtbf)
                ->setMttr($mttr)
                ->setRate($rate);

            $this->entityManager->persist($report);
            $this->entityManager->flush();


        }


    }

}