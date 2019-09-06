<?php
namespace myfanclub\modules\log;

use myfanclub\modules\log\controller\MyfcLogController;

class MyfcLogModuleRouter
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
         * @var MyfcLogController $controller
         */
        $controller = MyfcLogController::getInstance();

        if (isset($request['page']) && $request['page'] == 'myfc_log') {
            $controller->render();
        }
    }
}
