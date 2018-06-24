<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Subscriber;

/**
 * The order.placed event is dispatched each time an order is created
 * in the system.
 */
class SubscriptionEvent extends Event
{
    const NAME = 'news.subscriber';

    protected $subscriber;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function getSubscriber()
    {
        return $this->subscriber;
    }
}