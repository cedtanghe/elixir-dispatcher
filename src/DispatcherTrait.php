<?php

namespace Elixir\Dispatcher;

use Elixir\Dispatcher\Event;
use Elixir\Dispatcher\SubscriberInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
trait DispatcherTrait 
{
    /**
     * @var mixed 
     */
    protected $target = null;

    /**
     * @var array 
     */
    protected $listeners = [];

    /**
     * @var array 
     */
    protected $serials = [];

    /**
     * @return mixed
     */
    public function getTarget() 
    {
        if (null === $this->target) 
        {
            $this->target = $this;
        }

        return $this->target;
    }

    /**
     * @param mixed $value 
     */
    public function setTarget($value) 
    {
        $this->target = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function addSubscriber(SubscriberInterface $subscriber) 
    {
        $subscriber->subscribe($this);
    }

    /**
     * {@inheritdoc}
     */
    public function removeSubscriber(SubscriberInterface $subscriber) 
    {
        $subscriber->unsubscribe($this);
    }

    /**
     * {@inheritdoc}
     */
    public function hasListener($type) 
    {
        return isset($this->listeners[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function addListener($type, callable $callback, $priority = 0) 
    {
        if ($this->hasListener($type)) 
        {
            foreach ($this->listeners[$type] as $listener) 
            {
                if ($listener['callback'] === $callback) 
                {
                    $listener['priority'] = (int)$priority;
                    $listener['serial'] = $this->serials[$type]++;
                    
                    return;
                }
            }

            $this->listeners[$type][] = [
                'callback' => $callback,
                'priority' => (int)$priority,
                'serial' => $this->serials[$type]++
            ];
        } 
        else 
        {
            $this->serials[$type] = 0;
            $this->listeners[$type] = [
                [
                    'callback' => $callback,
                    'priority' => (int)$priority,
                    'serial' => $this->serials[$type]++
                ]
            ];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeListener($type, callable $callback = null) 
    {
        if ($this->hasListener($type)) 
        {
            if (null === $callback) 
            {
                unset($this->listeners[$type]);
                return;
            }

            $i = count($this->listeners[$type]);

            while ($i--) 
            {
                if ($this->listeners[$type][$i]['callback'] === $callback) 
                {
                    array_splice($this->listeners[$type], $i, 1);
                    break;
                }
            }

            if (empty($this->listeners[$type])) 
            {
                unset($this->listeners[$type]);
            }
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasListeners() 
    {
        return count($this->listeners) > 0;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getListeners() 
    {
        return $this->listeners;
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeListeners() 
    {
        $this->listeners = [];
        $this->serials = [];
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(Event $event) 
    {
        if ($this->hasListener($event->getType())) 
        {
            $event->setTarget($this->getTarget());
            $listeners = &$this->listeners[$event->getType()];
            
            usort(
                $listeners, 
                function(array $p1, array $p2)
                {
                    if ($p1['priority'] == $p2['priority']) 
                    {
                        return ($p1['serial'] < $p2['serial']) ? -1 : 1;
                    }

                    return ($p1['priority'] > $p2['priority']) ? -1 : 1;
                }
            );
            
            foreach ($listeners as $listener) 
            {
                call_user_func_array($listener['callback'], [$event]);

                if ($event->isStopped()) 
                {
                    break;
                }
            }
        }
    }
}
