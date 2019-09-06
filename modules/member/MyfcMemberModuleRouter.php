<?php
namespace myfanclub\modules\member;

use myfanclub\core\MyfcCore;
use myfanclub\modules\member\controller\MyfcMemberController;

class MyfcMemberModuleRouter
{
    private static $_instance = null;

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }


    public function route($request)
    {

        /**
         * @var MyfcMemberController $controller
         */
        $controller = new MyfcMemberController();

        if (isset($request['page']) && $request['page'] == 'myfc_member') {
            if (isset($request['save']) && $request['save'] == true) {
                $controller->myfcSaveMember($request);
            }

            if (isset($request['delete']) && $request['delete'] == true) {
                $controller->myfcDeleteMember($request);
            }

            if (isset($request['edit']) && $request['edit'] == true) {
                $controller->myfcUpdateMember($request['id'], $request);
            }

            if (isset($request['mail']) && MyfcCore::getInstance()->isModuleRegistered('mail')) {
                $controller->myfcSendMail($request);
            }

            if (isset($request['exportPdf']) && $request['exportPdf'] == true) {
                $controller->renderExportPdf();
            } else {
                $controller->render($request);
            }
        }
    }
}
