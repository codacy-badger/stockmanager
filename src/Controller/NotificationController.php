<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Entity\User;
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

        $users = $this->getDoctrine()->getRepository(User::class)->countNotSendedNotification();


        return $this->render('admin/notification/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("admin/notification/send-{id}", name="notification_send", methods="POST")
     */
    public function sendNotification(Request $request, User $user)
    {

        $submitedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('send-notification', $submitedToken)) {
            $myUser = $this->getDoctrine()->getRepository(User::class)->getNotSendedNotification($user);
            $date = new \DateTime();

            foreach ($myUser->getissues() as $issue) {
                $issue->setDateMessage($date);
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'La notification a été envoyée');

        }

        return $this->redirectToRoute('notification_index');
    }

}
