<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class BetaHeaderSubscriber implements EventSubscriberInterface
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $content = $response->getContent();

        $html='<div style="background: orange; width: 100%; text-align: center; padding: 0.5em;">Version Beta</div>';

        // Insertion du code dans la page, au début du <body>
        $content = str_replace(
            '</nav>',
            '</nav> '.$html,
            $content
        );


        $response->setContent($content);

    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.response' => 'onKernelResponse',
        ];
    }
}
