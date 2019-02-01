<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 25/01/2019
 * Time: 17:22
 */

namespace App\Services;


use App\Entity\Operator;
use Twig\Environment;


class EmailNotificator
{

    private $mailer;
    private $twig;
    private $logoPath;

    public function __construct(\Swift_Mailer $mailer, Environment $twig, $logoPath)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->logoPath = $logoPath;
    }

    public function sendEmail($copyUsers, $destiantionUsers, $subject, $template, Operator $object)
    {
        //mail message
        $message = (new \Swift_Message($subject))
            ->setFrom('contact@siteoise.com')
            ->setTo($destiantionUsers)
            ->setCc($copyUsers);

        $myLogo = $message->embed(\Swift_Image::fromPath($this->logoPath));



        $message->setBody(
            $this->twig->render($template,
                [
                    'operator' => $object,
                    'image' => $myLogo

                ]
            ), 'text/html'
        );

        $this->mailer->send($message);
    }
}