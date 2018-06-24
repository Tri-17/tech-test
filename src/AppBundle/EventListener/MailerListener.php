<?php

namespace AppBundle\EventListener;



use AppBundle\Event\SubscriptionEvent;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;

class MailerListener
{
    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function onNewsSubscription(SubscriptionEvent $e)
    {
        $subscriber = $e->getSubscriber();

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('noreply@news.io')
            ->setTo($subscriber->getEmail())
            ->setBody(
                $this->templating->render(
                // app/Resources/views/Emails/registration.html.twig
                    'Emails/subscription.html.twig',
                    array('subscribername' => $subscriber->getName(), 'newslettername' => $subscriber->getNewsletter()->getName())
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;

        $this->mailer->send($message);

    }
}