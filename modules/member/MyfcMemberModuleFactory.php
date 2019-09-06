<?php
namespace myfanclub\modules\member;

use myfanclub\core\MyfcModuleFactory;
use myfanclub\core\MyfcModuleCollector;

class MyfcMemberModuleFactory implements MyfcModuleFactory
{
    public static $MODULE_NAME = 'member';
    public static $MEMBER_ADMINISTRATION_CAPABILITY = 'myfc_member_administration';



    public function myfcCapabilitiesAdministrator()
    {
        return [
            "myfc_app_member",
            "myfc_app_member_add",
            "myfc_app_member_edit",
            "myfc_app_member_delete",
            "myfc_app_member_mail"
        ];
    }

    public function myfcCapabilitiesSecretary()
    {
        return [
            "myfc_app_member",
            "myfc_app_member_add",
            "myfc_app_member_edit",
            "myfc_app_member_delete",
            "myfc_app_member_mail"
        ];
    }

    public function myfcCapabilitiesCashier()
    {
        return [
            "myfc_app_member",
            "myfc_app_member_mail"
        ];
    }

    public function myfcCapabilitiesChairman()
    {
        return [
            "myfc_app_member",
            "myfc_app_member_add",
            "myfc_app_member_edit",
            "myfc_app_member_delete",
            "myfc_app_member_mail"
        ];
    }

    public function myfcCapabilitiesMember()
    {
        return [];
    }
    
    public function myfcSubMenueEntry()
    {
        $data = MyfcModuleCollector::getInstance()->load(self::$MODULE_NAME)['INFO'];

        return [
            "label" => $data->menueEntry->de,
            "pageTitle" => $data->menueEntry->de,
            "capability" => 'myfc_app_'.self::$MODULE_NAME,
            "menueSlug" => "myfc_".self::$MODULE_NAME,
            "callback" => "myfc_provide_".self::$MODULE_NAME,
            "menuePosition" => 0
        ];
    }

    public function myfcEventRegister()
    {
        // TODO: Implement myfcEventRegister() method.
    }
}
