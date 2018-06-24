<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Newsletter;
use AppBundle\Event\SubscriptionEvent;
use AppBundle\Form\SubscribeType;
use AppBundle\Repository\NewsletterRepository;
use AppBundle\Services\NewsletterPublisher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        return $this->redirectToRoute("form-subscribe");
    }

    /**
     * @Route("/subscribes", name="form-subscribe")
     * @Template("default/form-subscribe.html.twig")
     */
    public function subscribeAction(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $form = $this->createForm(SubscribeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $subscriber = $form->getData();
            $manager->persist($subscriber);
            $manager->flush();

            $eventDispatcher->dispatch(SubscriptionEvent::NAME, new SubscriptionEvent($subscriber));

            return $this->redirectToRoute("subscribe-confirm");
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/subscribe-confirm", name="subscribe-confirm")
     * @Template("default/subscribe-confirm.html.twig")
     */
    public function subscribeConfirmAction(Request $request)
    {
        return [];
    }
}
