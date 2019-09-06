<?php
namespace myfanclub\core\events;

/**
 * EventManager for core events
 *
 *
 * @package  core/events
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  beta
 * @access   public
 * @see      https://myfanclub.ultra-sites.de
 */
class MyfcEventManager
{
    private static $_instance = null;
    private $events = [];

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function attach($className)
    {
        $this->events[$className] = true;
    }
        
    public function trigger(MyfcEvent $event, $params = [])
    {
        foreach ($this->events as $className => $attachedEvent) {
            if ($event::className() == $className) {
                return $event->handle($params);
            }
        }

        return false;
    }
}
