<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Entity\Issue;
use App\Entity\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Yectep\PhpSpreadsheetBundle\Factory;

/**
 * @Route("/member")
 * Class MemberController
 * @package App\Controller
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/", name="member_index")
     */
    public function index()
    {
//        get the current user logged in
        $user = $this->getUser();

//        find all issues by the current user
        $issues = $this->getDoctrine()->getRepository(Issue::class)->findByUser($user);

        return $this->render('member/issue/actualList.html.twig', [
            'issues' => $issues,
//            bootstrap active link
            'isActiveDash' => true
        ]);
    }


    /**
     * @Route("/count-issue", name="member_countIssue")
     * @return Response
     */
    public function countIssue()
    {
        $count = $this->getDoctrine()->getRepository(Issue::class)->countByUser($this->getUser());

        return $this->render('member/issue/_countIssue.html.twig', [
            'count' => $count
        ]);
    }


    /**
     * @Route("/equipment_search", name="equipment_search", defaults={"_format"="json"})
     * @param $term
     */
    public function searchEquipmeent(Request $request)
    {
        $term = $request->query->get('term'); // use "term" instead of "q" for jquery-ui
        $results = $this->getDoctrine()->getRepository('App:Equipment')->findLike($term);

        return $this->render('admin/equipment/search.json.twig', [
            'equipments' => $results
        ]);
    }

    /**
     * @Route("/equipment_get", name="equipment_get")
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getEquipment($id = null)
    {
        $equipement = $this->getDoctrine()->getRepository(Equipment::class)->find($id);

        return $this->json($equipement->getSerial());
    }


    /**
     * @Route("/historic", name="member_historic")
     */
    public function historic(Security $security)
    {

        //get the current logged user
        $currentUser = $security->getUser();

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'username' => $currentUser->getUsername()
        ]);

        $issues = $this->getDoctrine()->getRepository(Issue::class)->findByOperator($user->getOperator());

        return $this->render('member/issue/historicList.html.twig', [
            'issues' => $issues,
            'isActiveHistoric' => true
        ]);
    }

    /**
     * @Route("/export", name="member_export")
     * @param Security $security
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(Security $security)
    {

        //get the current logged user
        $currentUser = $security->getUser();

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'username' => $currentUser->getUsername()
        ]);

        $issues = $this->getDoctrine()->getRepository(Issue::class)->findByOperator($user->getOperator());

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
        $sheet->setCellValue('E1', 'Réseau de transport');
        $sheet->setCellValue('F1', 'Date');
        $sheet->setCellValue('G1', 'Symptomes');

        //set table dynamic lines
        foreach ($issues as $issue) {

            $symptoms = $issue->getSymptoms();

            foreach ($symptoms as $symptom) {
                $allSymptoms = $symptom->getName() . ' ';
            }

            $sheet->setCellValue('A' . $i, $issue->getEquipment()->getId());
            $sheet->setCellValue('B' . $i, $issue->getEquipment()->getSerial());
            $sheet->setCellValue('C' . $i, $issue->getEquipment()->getBrand()->getCategory()->getName());
            $sheet->setCellValue('D' . $i, $issue->getEquipment()->getBrand()->getName());
            $sheet->setCellValue('E' . $i, $issue->getTransportation()->getTradeName());
            $sheet->setCellValue('F' . $i, $issue->getDateRequest()->setTimezone(new \DateTimeZone('Europe/Paris')));
            $sheet->setCellValue('G' . $i, $allSymptoms);

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
