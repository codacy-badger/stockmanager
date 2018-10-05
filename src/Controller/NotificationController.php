<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * @Route("admin/notification", name="notification_index")
     */
    public function index()
    {

       $issues = $this->getDoctrine()->getRepository('App:Issue')->countNonNotified();

dump($issues);
die();
        return $this->render('admin/notification/index.html.twig', [
            'users' => $users,

        ]);
    }
}
