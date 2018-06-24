<?php

namespace AppBundle\EventListener;



use AppBundle\Event\SubscriptionEvent;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

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
                    'Emails/subscription.html.twig',
                    array('subscribername' => $subscriber->getName(), 'newslettername' => $subscriber->getNewsletter()->getName())
                ),
                'text/html'
            )

        ;

        $this->mailer->send($message);

    }
}