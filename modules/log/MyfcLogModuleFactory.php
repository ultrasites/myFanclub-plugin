<?php
namespace myfanclub\modules\log;

use myfanclub\core\events\MyfcEventManager;
use myfanclub\core\MyfcModuleFactory;
use myfanclub\modules\log\events\MyfcAddLogEvent;

class MyfcLogModuleFactory implements MyfcModuleFactory
{
    public static $MODULE_NAME = 'log';


    public function myfcCapabilitiesAdministrator()
    {
        return [
            "myfc_app_log"
        ];
    }

    public function myfcCapabilitiesSecretary()
    {
        return [];
    }

    public function myfcCapabilitiesCashier()
    {
        return [];
    }

    public function myfcCapabilitiesMember()
    {
        return [];
    }

    public function myfcCapabilitiesChairman()
    {
        return [];
    }

    public function myfcSubMenueEntry()
    {
        return [
            "label" => 'Logs',
            "pageTitle" => 'Logs',
            "capability" => 'myfc_app_'.self::$MODULE_NAME,
            "menueSlug" => "myfc_".self::$MODULE_NAME,
            "callback" => "myfc_provide_".self::$MODULE_NAME,
            "menuePosition" => 2
        ];
    }

    public function myfcEventRegister()
    {
        MyfcEventManager::getInstance()->attach(MyfcAddLogEvent::className());
    }
}
