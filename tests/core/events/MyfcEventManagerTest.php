<?php
namespace myfanclub\tests\core\events;

use PHPUnit\Framework\TestCase;
use myfanclub\tests\core\model\ExampleEvent;
use myfanclub\core\events\MyfcEventManager;

final class MyfcEventManagerTest extends TestCase
{
    public function testEventManagerWithExampleEvent()
    {

        /**
         * @var MyfcEventManager $em
         */
        $em = MyfcEventManager::getInstance();

        $em->attach(ExampleEvent::className());
        
        $response = $em->trigger(new ExampleEvent(), [true]);

        $this->assertTrue($response);
    }
}
