<?php

namespace Elixir\Test\Dispatcher;

use PHPUnit_Framework_TestCase;

class Dummy extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $this->assertEquals('Hello world', 'Hello world');
    }
}
