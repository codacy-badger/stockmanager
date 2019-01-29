<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Entity\Equipment;
use App\Entity\EquipmentStatus;
use App\Entity\Issue;
use App\Entity\Location;
use App\Entity\User;
use App\Form\IssueEditType;
use App\Form\IssueType;
use App\Form\ReplaceType;
use App\Repository\IssueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class IssueController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
     * Create an issue
     *
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

            $equipmentStatus = new EquipmentStatus();
            $equipmentStatus->setEquipment($issue->getEquipment());
            $equipmentStatus->setStartFailure(new \DateTime());

            //save into database
            $this->em->persist($equipmentStatus);
            $this->em->persist($issue);
            $this->em->flush();

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
     * Show issues by status
     * @Route("admin/issue/show-{status}", name="issue_showByStatus")
     * @return Response
     */
    public function showByStatus($status)
    {
//        create new object contract to get the constant and send it into view
        $contract = new Contract();

        if ($status == 'new') {
            $issues = $this->getDoctrine()->getRepository(Issue::class)->getNew();
        } elseif ($status == 'check') {
            $issues = $this->getDoctrine()->getRepository(Issue::class)->getChecked();
        } elseif ($status == 'ready') {
            $issues = $this->getDoctrine()->getRepository(Issue::class)->getReady();
        } elseif ($status == 'end') {
            $issues = $this->getDoctrine()->getRepository(Issue::class)->getEnd();
        }

        return $this->render('admin/issue/show.html.twig', [
            'issues' => $issues,
            'status' => $status,
            'contract' => $contract
        ]);
    }



    /**
     * show issue
     *
     * @Route("admin/issue/{id}", name="issue_show", methods="GET")
     * @param Issue $issue
     * @return Response
     */
    public function show(Issue $issue): Response
    {
        return $this->render('admin/issue/show.html.twig', ['issue' => $issue]);
    }

    /**
     * Edit issue
     *
     * @Route("admin/issue/{id}/edit", name="issue_edit", methods="GET|POST")
     * @param Request $request
     * @param Issue $issue
     * @return Response
     */
    public function edit(Request $request, Issue $issue): Response
    {
        $form = $this->createForm(IssueEditType::class, $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le numéro de série a été modifié.');

            return $this->redirectToRoute('issue_showByStatus', [
                'status' => 'ready'
            ]);
        }

        return $this->render('admin/issue/edit.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete issue
     *
     * @Route("admin/issue/{id}", name="issue_delete", methods="DELETE")
     * @param Request $request
     * @param Issue $issue
     * @return Response
     */
    public function delete(Request $request, Issue $issue): Response
    {
        if ($this->isCsrfTokenValid('delete-issue', $request->request->get('_token'))) {

            $this->em->remove($issue);
            $this->em->flush();
        }

        return $this->redirectToRoute('issue_index');
    }


    /**
     * Set current datetime into dateChecked variable
     *
     * @Route("admin/issue/setChecked/{id}", name="issue_setChecked", methods="POST")
     * @param Issue $issue
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function setChecked(Issue $issue, Request $request)
    {
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('check-issue-check', $submittedToken)) {

            $dateTime = new \DateTime();

            $technician = $this->getUser();
            $issue->setDateChecked($dateTime);
            $issue->setTechnician($technician);


            $this->em->flush();

            $this->addFlash('success', "Demande validée");

        }

        return $this->redirectToRoute('issue_showByStatus', [
            'status' => 'new'
        ]);
    }

    /**
     * Add equipment replace and set current datetime into dateReady variable
     *
     * @Route("admin/issue/setReadyForm/{id}", name="issue_setReadyForm")
     * @param Issue $issue
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function setReadyForm(Issue $issue, Request $request)
    {

        $form = $this->createForm(ReplaceType::class, $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $dateTime = new \DateTime();
            $issue->setDateReady($dateTime);

            $this->em->flush();

            $this->addFlash('success', "Demande validée");

            return $this->redirectToRoute('issue_showByStatus', [
                'status' => 'check'
            ]);
        }

        return $this->render('admin/issue/addReplace.html.twig', [
            'form' => $form->createView(),
            'issue' => $issue
        ]);
    }

    /**
     * Redirect to setReadyForm
     *
     * @Route("admin/issue/setReady/{id}", name="issue_setReady", methods="POST")
     * @param Request $request
     * @param Issue $issue
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setReady(Request $request, Issue $issue)
    {
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('check-issue-ready', $submittedToken)) {

            return $this->redirectToRoute('issue_setReadyForm', [
                'id' => $issue->getId()
            ]);

        }

        return $this->redirectToRoute('issue_showByStatus', [
            'status' => 'check'
        ]);
    }

    /**
     * set to ready status without adding a replacement equipment
     *
     * @Route("admin/issue/setReadyWithoutReplace/{id}", name="issue_setReadyWithoutReplace", methods="POST")
     * @param Request $request
     * @param Issue $issue
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function setReadyWithoutReplace(Request $request, Issue $issue)
    {
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('check-issue-ready-without-replace', $submittedToken)) {

            $dateTime = new \DateTime();
            $issue->setDateReady($dateTime);

            $this->em->flush();

            $this->addFlash('success', "Demande validée");

            return $this->redirectToRoute('issue_showByStatus', [
                'status' => 'check'
            ]);

        }
    }

    /**
     * Set current datetime into dateEnd variable
     *
     * @Route("admin/issue/setEnd/{id}", name="issue_setEnd", methods="POST")
     * @param Issue $issue
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public
    function setEnd(Issue $issue, Request $request)
    {
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('check-issue-end', $submittedToken)) {

            if (null === $issue->getDateMessage()) {
                $this->addFlash('danger', 'Attention le ticket n\'a pas été notifié à l\'exploitant');
            } else {
                $dateTime = new \DateTime();

                $location = new Location();
                $location
                    ->setDate($dateTime)
                    ->setEquipment($issue->getEquipmentReplace())
                    ->setSite($issue->getUser()->getOperator()->getSite())
                    ->setIsOk(true);


                $issue->setDateEnd($dateTime);

                $this->em->persist($location);
                $this->em->flush();

                $this->addFlash('success', 'Ticket cloturé');
            }
            return $this->redirectToRoute('issue_showByStatus', [
                'status' => 'ready'
            ]);

        }

        return $this->redirectToRoute('issue_showByStatus', [
            'status' => 'ready'
        ]);

    }

    /**
     * Count number of issues by status
     *
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

    /**
     * @Route("admin/issue/allOpenIssues", name="issue_allOpenIssues")
     * @return Response
     */
    public function countAllOpenIssues()
    {

        $number = $this->getDoctrine()->getRepository('App:Issue')->countAllOpenIssues();

        return $this->render('admin/issue/_countOpenIssues.html.twig', [
            'number' => $number
        ]);

    }

    /**
     * @Route("admin/issue/userOpenIssues", name="issue_userOpenIssues")
     * @return Response
     */
    public function countUserOpenIssues()
    {
        $user = $this->getUser();
        $number = $this->getDoctrine()->getRepository('App:Issue')->countUserOpenIssues($user->getOperator());

        return $this->render('admin/issue/_countOpenIssues.html.twig', [
            'number' => $number
        ]);

    }

    /**
     * @Route("admin/issue/countNonNotified", name="issue_countNonNotified")
     * @return Response
     */
    public function countNonNotified()
    {
        $number = $this->getDoctrine()->getRepository(Issue::class)->countNonNotifed();

        return $this->render('admin/issue/_countNonNotifiedIssues.html.twig', [
            'number' => $number
        ]);
    }



}
