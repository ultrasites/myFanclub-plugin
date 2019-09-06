<?php
namespace myfanclub\tests\core\model;

use myfanclub\core\events\MyfcEvent;

class ExampleEvent implements MyfcEvent
{
    public static function className()
    {
        return 'ExampleEvent';
    }

    public function handle($params = [])
    {
        return $params[0];
    }
}
