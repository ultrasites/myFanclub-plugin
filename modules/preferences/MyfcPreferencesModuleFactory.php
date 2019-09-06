<?php
namespace myfanclub\modules\preferences;

use myfanclub\core\MyfcModuleFactory;
use myfanclub\core\MyfcModuleCollector;

class MyfcPreferencesModuleFactory implements MyfcModuleFactory
{
    public static $MODULE_NAME = 'preferences';
    public static $MEMBER_ADMINISTRATION_CAPABILITY = 'myfc_member_administration';


    public function myfcCapabilitiesAdministrator()
    {
        return [
            "myfc_app_preferences"
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
        return [
            "myfc_app_preferences"
        ];
    }
    
    public function myfcSubMenueEntry()
    {
        $data = MyfcModuleCollector::getInstance()->load(self::$MODULE_NAME)['INFO'];

        return [
            "label" => $data->menueEntry->de,
            "pageTitle" => $data->menueEntry->de,
            "capability" => 'myfc_app_preferences',
            "menueSlug" => "myfc_".self::$MODULE_NAME,
            "callback" => "myfc_provide_".self::$MODULE_NAME,
            "menuePosition" => 1
        ];
    }

    public function myfcEventRegister()
    {
        // TODO: Implement myfcEventRegister() method.
    }
}
