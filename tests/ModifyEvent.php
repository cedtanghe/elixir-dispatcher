<?php

namespace Elixir\Test\Dispatcher;

use Elixir\Dispatcher\Event;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class ModifyEvent extends Event
{
    /**
     * @var string
     */
    const MODIFY = 'modify';

    /**
     * @var string
     */
    protected $str;

    /**
     * {@inheritdoc}
     *
     * @param array $params
     */
    public function __construct($type, array $params = [])
    {
        parent::__construct($type);

        $params += [
            'str' => null,
        ];

        $this->str = $params['str'];
    }

    /**
     * @return string
     */
    public function getStr()
    {
        return $this->str;
    }

    /**
     * @param string $value
     */
    public function setStr($value)
    {
        $this->str = $value;
    }
}
