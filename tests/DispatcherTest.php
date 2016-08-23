<?php

namespace Elixir\Test\Dispatcher;

use Elixir\Dispatcher\Dispatcher;
use PHPUnit_Framework_TestCase;

class DispatcherTest extends PHPUnit_Framework_TestCase
{
    public function testOrderListeners()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->addListener(ModifyEvent::MODIFY, function (ModifyEvent $e) {
            $e->setStr('second pass');
        }, 1);

        $dispatcher->addListener(ModifyEvent::MODIFY, function (ModifyEvent $e) {
            $e->setStr('first pass');
        }, 2);
        
        $event = new ModifyEvent('modify', ['str' => '']);
        $dispatcher->dispatch($event);

        $this->assertEquals($event->getStr(), 'second pass');
    }

    public function testSubscriber()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->addSubscriber(new Subscriber());

        $this->assertTrue($dispatcher->hasListener(ModifyEvent::MODIFY));
        
        $this->assertCount(2, $dispatcher->getListeners()[ModifyEvent::MODIFY]);
    }
}
