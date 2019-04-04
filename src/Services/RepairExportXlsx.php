<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 03/04/2019
 * Time: 17:46
 */

namespace App\Services;


use App\Entity\Issue;
use App\Entity\Repair;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;

class RepairExportXlsx
{

    public function __construct()
    {
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param array $repairs
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function export(array $issues)
    {

        if (is_array($issues)) {
            if (!$issues[0] instanceof Issue) {
                throw new \InvalidArgumentException('Expected object of type Repair');
            }
        }

        // phpSpreadsheet part
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //begin by the second line
        $i = 2;
        $symptom = null;

        //set table static first line
        $sheet->setCellValue('A1', 'Id');
        $sheet->setCellValue('B1', 'N° de série');
        $sheet->setCellValue('C1', 'Catégorie');
        $sheet->setCellValue('D1', 'Modèle');
        $sheet->setCellValue('E1', 'Utilisateur');
        $sheet->setCellValue('F1', 'Réseau de transport');
        $sheet->setCellValue('G1', 'Réseau de transport');
        $sheet->setCellValue('H1', 'Date déclaration');
        $sheet->setCellValue('I1', 'Date échange');
        $sheet->setCellValue('J1', 'Symptômes client');
        $sheet->setCellValue('K1', 'Fausse panne');
        $sheet->setCellValue('L1', 'Contrat');
        $sheet->setCellValue('M1', 'Degradation');
        $sheet->setCellValue('N1', 'Description réparation');
        $sheet->setCellValue('O1', 'Date réparation');
        $sheet->setCellValue('P1', 'Symptômes réparation');

        //set table dynamic lines
        /** @var Issue $issue */
        foreach ($issues as $issue) {

            $symptoms = $issue->getSymptoms();


            $repairSymptoms = $issue->getRepair()->getSymptoms() ?? 'aucun';



            $allSymptoms = null;
            foreach ($symptoms as $symptom) {


                $allSymptoms = $symptom->getName() . ', ' . $allSymptoms;
            }

            $allRepairSymptoms = null;

            foreach ($repairSymptoms as $symptom) {
                $allRepairSymptoms = $symptom->getName() . ', ' . $allRepairSymptoms;
            }

            $sheet->setCellValue('A' . $i, $issue->getId());
            $sheet->setCellValue('B' . $i, $issue->getEquipment()->getSerial());
            $sheet->setCellValue('C' . $i, $issue->getEquipment()->getBrand()->getCategory()->getName());
            $sheet->setCellValue('D' . $i, $issue->getEquipment()->getBrand()->getName());
            $sheet->setCellValue('E' . $i, $issue->getUser()->getFirstname() . ' ' . $issue->getUser()->getLastname());
            $sheet->setCellValue('F' . $i, $issue->getTransportation()->getTradeName());
            $sheet->setCellValue('G' . $i, $issue->getUser()->getOperator()->getName());
            $sheet->setCellValue('H' . $i, $issue->getDateRequest()->setTimezone(new \DateTimeZone('Europe/Paris')));
            $sheet->setCellValue('I' . $i, $issue->getDateEnd() ? $issue->getDateEnd()->setTimeZone(new \DateTimeZone('Europe/Paris')) : 'aucune');
            $sheet->setCellValue('J' . $i, $allSymptoms);
            $sheet->setCellValue('K' . $i, $issue->getRepair() ? $issue->getRepair()->getNoBreakdown() : 'aucune');
            $sheet->setCellValue('L' . $i, $issue->getEquipment()->getContract()->getName());
            $sheet->setCellValue('M' . $i, $issue->getRepair() ? $issue->getRepair()->getDegradation() : 'aucune');
            $sheet->setCellValue('N' . $i, $issue->getRepair() ? $issue->getRepair()->getDescription() : 'aucune');
            $sheet->setCellValue('O' . $i, $issue->getRepair() ? $issue->getRepair()->getDateEnd()->setTimezone(new \DateTimeZone('Europe/Paris')) : 'aucune');
            $sheet->setCellValue('P' . $i, $allRepairSymptoms);

            $allSymptoms = null;
            $i++;
        }

        $sheet->setTitle("Export");

        $writer = new Xlsx($spreadsheet);

        $fileName = 'export.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($tempFile);

        $response = new Response();

        $response->setContent(file_get_contents($tempFile));
        $response->headers->set(
            'Content-type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
        $response->headers->set('Content-disposition', 'filename=' . $fileName);

        return $response;
    }

}