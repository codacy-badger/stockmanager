<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Entity\User;
use App\Form\IssueType;
use App\Repository\IssueRepository;
use http\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class IssueController extends AbstractController
{
    /**
     * @Route("admin/issue", name="issue_index", methods="GET")
     * @param IssueRepository $issueRepository
     * @return Response
     */
    public function index(IssueRepository $issueRepository): Response
    {
        return $this->render('admin/issue/index.html.twig', ['issues' => $issueRepository->findAll()]);
    }


    /**
     * @Route("member/issue/new", name="issue_new", methods="GET|POST")
     * @param Request $request
     * @param Security $security
     * @return Response
     */
    public function new(Request $request, Security $security, \Swift_Mailer $mailer): Response
    {
        $issue = new Issue();

        $user = $security->getUser();

        if (!$user) {
            throw new \LogicException(
                'Le formulaire ne peut être utilisé sans un utilisateur authentifié!'
            );
        }
        $myUser = $this->getDoctrine()->getRepository(User::class)->find($user);
        $issue->setUser($myUser);
        $issue->getUser()->getOperator();


        $form = $this->createForm(IssueType::class, $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //adds the current user into object issue

            $issue->setUser($myUser);

            //save into database
            $em = $this->getDoctrine()->getManager();
            $em->persist($issue);
            $em->flush();

            //get all technicians
            $technicians = $this->getDoctrine()->getRepository(User::class)->getTechnicians();

            $emails = [];
            //get all emails from technicians
            foreach ($technicians as $technician) {
                $emails[] = $technician->getEmail();
            }

            //send email to technicians
            $message = (new \Swift_Message('Nouveau ticket de ' . $myUser->getOperator()->getName()))
                ->setFrom('maintenance.siteoise@gmail.com')
                ->setTo($emails)
                ->setBody(
                    $this->renderView(
                        'admin/notification/emailTechnician.html.twig',
                        [
                            'issue' => $issue
                        ]
                    ), 'text/html'
                );
            $mailer->send($message);

            $this->addFlash('success', "La panne a bien été enregistrée");
            return $this->redirectToRoute('member_index');
        }

        return $this->render('member/issue/new.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
            'isActiveNew' => true
        ]);
    }

    /**
     * @Route("admin/issue/show-{status}", name="issue_showByStatus")
     * @return Response
     */
    public function showByStatus($status)
    {
        if ($status == 'new') {
            $issues = $this->getDoctrine()->getRepository(Issue::class)->findBy([
                'dateChecked' => null
            ]);
        } elseif ($status == 'check') {
            $issues = $this->getDoctrine()->getRepository(Issue::class)->getChecked();
        } elseif ($status == 'ready') {
            $issues = $this->getDoctrine()->getRepository(Issue::class)->getReady();
        } elseif ($status == 'end') {
            $issues = $this->getDoctrine()->getRepository(Issue::class)->getEnd();
        }

        return $this->render('admin/issue/show.html.twig', [
            'issues' => $issues,
            'status' => $status
        ]);
    }


    /**
     * @Route("admin/issue/{id}", name="issue_show", methods="GET")
     * @param Issue $issue
     * @return Response
     */
    public function show(Issue $issue): Response
    {
        return $this->render('admin/issue/show.html.twig', ['issue' => $issue]);
    }

    /**
     * @Route("admin/issue/{id}/edit", name="issue_edit", methods="GET|POST")
     * @param Request $request
     * @param Issue $issue
     * @return Response
     */
    public function edit(Request $request, Issue $issue): Response
    {
        $form = $this->createForm(IssueType::class, $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('issue_edit', ['id' => $issue->getId()]);
        }

        return $this->render('admin/issue/edit.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/issue/{id}", name="issue_delete", methods="DELETE")
     * @param Request $request
     * @param Issue $issue
     * @return Response
     */
    public function delete(Request $request, Issue $issue): Response
    {
        if ($this->isCsrfTokenValid('delete-issue', $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($issue);
            $em->flush();
        }

        return $this->redirectToRoute('issue_index');
    }

    /**
     * @Route("admin/issue/change-status/{status}-{id}", name="issue_changeStatus", methods="POST")
     * @param Request $request
     * @param Issue $issue
     * @param $status
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeStatus(Request $request, Issue $issue, $status)
    {


        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('check-issue', $submittedToken)) {

            $dateTime = new \DateTime();

            if ($status == 'new') {
                $technician = $this->getUser();
                $issue->setDateChecked($dateTime);
                $issue->setTechnician($technician);
            } elseif ($status == 'check') {
                $issue->setDateReady($dateTime);

            } elseif ($status == 'ready') {
                $issue->setDateEnd($dateTime);
            }


            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', "Demande validée");
        }

        return $this->redirectToRoute('issue_showByStatus', [
            'status' => $status
        ]);

    }


    /**
     * @Route("admin/issue/count-widget", name="issue_countWidget")
     * @return Response
     */
    public function countWidget()
    {
        $countNew = $this->getDoctrine()->getRepository(Issue::class)->countNew();
        $countCheck = $this->getDoctrine()->getRepository(Issue::class)->countCheck();
        $countReady = $this->getDoctrine()->getRepository(Issue::class)->countReady();
        $countEnd = $this->getDoctrine()->getRepository(Issue::class)->countEnd();

        return $this->render('admin/issue/countWidget.html.twig', [
            'countNew' => $countNew,
            'countCheck' => $countCheck,
            'countReady' => $countReady,
            'countEnd' => $countEnd
        ]);
    }


}
