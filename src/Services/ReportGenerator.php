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

                //réccupère tout les issues dont la date de fin est compris dans la plage
                $issues = $this->entityManager->getRepository(Issue::class)->findByBrand($brand, $startDate, $endDate);

                $countIssuesCategory = $countIssuesCategory + count($issues);


                //Compte les issues ou les pannes ne sont pas constatés
                $countFakeIssues = $this->entityManager->getRepository(Issue::class)->countFakeIssues($brand, $startDate, $endDate);

                $countFakeIssuesCategory = $countFakeIssuesCategory + $countFakeIssues;

                $repairTime = 0;

                /** @var Issue $issue */
                foreach ($issues as $issue) {

                    //réccupère la date de début de la panne
                    $dateRequest = $issue->getDateRequest();

                    //réccupère la date de fin de panne
                    $repairDate = $issue->getRepair()->getDateEnd();

                    //si la date de début de panne est avant la date du filtre, place la date du filtre à la place
                    if($dateRequest < $startDate){
                        $dateRequest = $startDate;
                    }

                    //delta en heure des deux dates
                    $repairIssueTime = $this->dateDiffHour->getDiff($repairDate, $dateRequest);

                    //cumul du temps d'indispo pour le modèle
                    $repairTime = $repairTime + $repairIssueTime;

                }
                //cumul du temps de réparation pour la catégorie
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