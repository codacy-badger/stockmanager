<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Entity\Issue;
use App\Entity\Operator;
use App\Entity\User;
use App\Services\EmailNotificator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * @Route("admin/notification", name="notification_index")
     */
    public function index()
    {

        $operators = $this->getDoctrine()->getRepository(Operator::class)->getOperatorWithNonNotifiedIssues();


        return $this->render('admin/notification/index.html.twig', [
            'operators' => $operators,
        ]);
    }

    /**
     * @Route("admin/notification/send-{id}", name="notification_send", methods="POST")
     *
     * @param Request $request
     * @param Operator $operator
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function sendNotification(Request $request, Operator $operator, EmailNotificator $mailer)
    {

        //get the token generated by twig form
        $submitedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('send-notification', $submitedToken)) {


            //get operator with only non notified issues from user repository
            $myOperator = $this->getDoctrine()->getRepository(Operator::class)->getOneOperatorWithNonNotifedIssues($operator);

            //get all users from operator form mailing
            $users = $this->getDoctrine()->getRepository(User::class)->getUsersByOperator($operator);

            $destEmails = [];

            //get all destiation emails
            /** @var User $user */
            foreach ($users as $user) {
                $destEmails[] = $user->getEmail();

            }


            //get all technicians
            $technicians = $this->getDoctrine()->getRepository(User::class)->getTechnicians();

            //get all cc emails
            $ccEmails = [];
            //get all emails from technicians
            foreach ($technicians as $technician) {
                $ccEmails[] = $technician->getEmail();
            }

            $subject = 'Equipements prêts, remplacement imminent';
            $template = 'admin/notification/email.html.twig';

            //send email service
            $mailer->sendEmail($ccEmails, $destEmails, $subject, $template, $myOperator);

            //set the current date to dateMessage
            $date = new \DateTime();

            foreach ($myOperator->getUsers() as $user) {

                foreach ($user->getissues() as $issue) {
                    $issue->setDateMessage($date);

                }
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'La notification a été envoyée');

        }

        return $this->redirectToRoute('notification_index');
    }


}
