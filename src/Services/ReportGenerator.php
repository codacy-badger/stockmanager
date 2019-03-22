<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 20/03/2019
 * Time: 10:31
 */

namespace App\Services;


use App\Entity\Category;
use App\Entity\Contract;
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

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
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

            $countNewIssuesCategory = 0;
            $countCurrentIssuesCategory = 0;
            $countSubcontractorIssuesCategory = 0;
            $countFakeIssuesCategory = 0;
            $repairTimeCategory = 0;

            //contrat hors sismo
            $contract = new Contract();
            $contratHorsSismo = $contract::CONTRAT_HORS_SISMO;


            foreach ($brands as $brand) {

                //réccupère tout les issues dont la date de début est compris dans la plage
                $newIssues = $this->entityManager->getRepository(Issue::class)->findNewByBrand($brand, $startDate, $endDate);

                //réccupère tout les issues qui ne sont pas encore été traités
                $currentIssues = $this->entityManager->getRepository(Issue::class)->findCurrentByBrand($brand, $startDate);

                //récupère tout les issues qui sont encore chez le sous traitant
                $subcontractorIssues = $this->entityManager->getRepository(Issue::class)->findSubcontractorRepairsByBrand($brand, $startDate);

                //Compte les issues ou les pannes ne sont pas constatés
                $countFakeIssues = $this->entityManager->getRepository(Issue::class)->countFakeIssues($brand, $startDate, $endDate);

                //Compte le nombre de  nouvelles dégradations
                $countDegradations = $this->entityManager->getRepository(Issue::class)->countDegradations($brand, $startDate, $endDate);

                //comptage
                $countFakeIssuesCategory = $countFakeIssuesCategory + $countFakeIssues;

                $countNewIssuesCategory = $countNewIssuesCategory + count($newIssues);

                $countCurrentIssuesCategory = $countCurrentIssuesCategory + count($currentIssues);

                $countSubcontractorIssuesCategory = $countSubcontractorIssuesCategory + count($subcontractorIssues);


                //somme de toute les pannes
                $totalIssues = $countNewIssuesCategory + $countCurrentIssuesCategory + $countSubcontractorIssuesCategory;


                $repairSubTime = 0;

                /** @var Issue $issue */
                foreach ($subcontractorIssues as $issue) {

                    //si l'équipepment a subit des dégradations ou est un contrat hors sismo  on ne le prend pas en compte
                    if ($issue->getRepair()->getDegradation() || $issue->getEquipment()->getContract()->getId() == $contratHorsSismo) {


                    } else {


                        $repairDate = $issue->getRepair()->getSubcontractorRepair()->getDateReturn();

                        if ($repairDate >= $endDate) {
                            $repairDate = $endDate;
                        }

                        //delta en heure des deux dates
                        $repairIssueTime = $this->dateDiffHour->getDiff($repairDate, $startDate);

                        //cumul du temps d'indispo pour le modèle
                        $repairSubTime = $repairSubTime + $repairIssueTime;
                    }
                }

                $repairCurTime = 0;

                /** @var Issue $issue */
                foreach ($currentIssues as $issue) {

                    //si l'équipement n'a pas de panne constaté ou a subit des dégradations ou est un contrat hors SISMO on ne le prend pas en compte
                    if ($issue->getRepair()->getNoBreakdown() || $issue->getRepair()->getDegradation() || $issue->getEquipment()->getContract()->getId() == $contratHorsSismo) {


                    } else {

                        $repairDate = $issue->getRepair()->getDateEnd();

                        if ($repairDate >= $endDate) {
                            $repairDate = $endDate;
                        }

                        //delta en heure des deux dates
                        $repairIssueTime = $this->dateDiffHour->getDiff($repairDate, $startDate);

                        //cumul du temps d'indispo pour le modèle
                        $repairCurTime = $repairCurTime + $repairIssueTime;

                    }

                }


                $repairNewTime = 0;

                /** @var Issue $issue */
                foreach ($newIssues as $issue) {

                    if ($issue->getRepair()->getNoBreakdown() || $issue->getRepair()->getDegradation() || $issue->getEquipment()->getContract()->getId() == $contratHorsSismo) {

                    } else {

                        //réccupère la date de début de la panne

                        $dateRequest = $issue->getDateRequest();

                        //réccupère la date de fin de panne
                        if ($issue->getRepair()->getIsGoingToSubcontractor()) {

                            //si la date de fin de panne n'est pas null
                            if (!null === $issue->getRepair()->getSubcontractorRepair()->getDateReturn()) {
                                $repairDate = $issue->getRepair()->getSubcontractorRepair()->getDateReturn();
                            } else {
                                $repairDate = $endDate;
                            }

                        } else {
                            $repairDate = $issue->getRepair()->getDateEnd();
                        }

                        //si la date de fin de panne est après la date du filtre, place la date du filtre à la place
                        if ($repairDate >= $endDate) {
                            $repairDate = $endDate;
                        }

                        //delta en heure des deux dates
                        $repairIssueTime = $this->dateDiffHour->getDiff($repairDate, $dateRequest);

                        //cumul du temps d'indispo pour le modèle
                        $repairNewTime = $repairNewTime + $repairIssueTime;
                    }

                }
                //cumul du temps de réparation pour la catégorie
                $repairTimeCategory = $repairTimeCategory + $repairNewTime + $repairSubTime + $repairCurTime;


            }

            $deltaDate = $startDate->diff($endDate);
            $numberOfDays = $deltaDate->d + 1;


            $mtbf = $this->MTBFGenerator->generate($numberOfDays, $category->getHoursPerDay(), $category->getContractualQuantity(), $totalIssues);
            $mttr = $this->MTTRStatistics->generate($repairTimeCategory, $totalIssues);

            //if mtbf is null then the rate is 100%
            if (null !== $mtbf) {
                $rate = $this->rateStatistics->getRate($mtbf, $mttr);
            } else {
                $rate = 1;
            }


            $report = new Report();
            $report->setIssueQuantity($totalIssues)
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setCategory($category)
                ->setFakeIssueQuantity($countFakeIssuesCategory)
                ->setCurrentIssueQuantity($countCurrentIssuesCategory)
                ->setSubcontractorIssueQuantity($countSubcontractorIssuesCategory)
                ->setDegradationQuantity($countDegradations)
                ->setRepairTime($repairTimeCategory)
                ->setMtbf($mtbf)
                ->setMttr($mttr)
                ->setRate($rate);

            $this->entityManager->persist($report);
            $this->entityManager->flush();


        }


    }

}