<?php

namespace AppBundle\Services;

use AppBundle\Entity\Newsletter;
use AppBundle\Entity\Subscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class NewsletterPublisher
{
    private $mailer;

    private $em;

    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function publish(Newsletter $newsletter)
    {
        foreach ($newsletter->getSubscribers() as $subscriber) {
            $this->send($newsletter, $subscriber);
        }
    }

    private function send(Newsletter $newsletter, Subscriber $subscriber)
    {
        $message = (new \Swift_Message('Newsletter "'.$newsletter->getName().'"'))
            ->setFrom('noreply@news.io')
            ->setTo($subscriber->getEmail())
            ->setBody(
                $this->templating->render(
                // app/Resources/views/Emails/registration.html.twig
                    'Emails/newsletter.html.twig',
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
