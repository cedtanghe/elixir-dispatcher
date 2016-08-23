<?php

namespace Elixir\Test\Dispatcher;

use Elixir\Dispatcher\DispatcherInterface;
use Elixir\Dispatcher\SubscriberInterface;

class Subscriber implements SubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function subscribe(DispatcherInterface $dispatcher)
    {
        $dispatcher->addListener(ModifyEvent::MODIFY, function (ModifyEvent $e) {
            // No need
        });
        
        $dispatcher->addListener(ModifyEvent::MODIFY, function (ModifyEvent $e) {
            // No need
        });
    }

    /**
     * {@inheritdoc}
     */
    public function unsubscribe(DispatcherInterface $dispatcher)
    {
        $dispatcher->removeListener(ModifyEvent::MODIFY);
    }
}
