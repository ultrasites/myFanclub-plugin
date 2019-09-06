<?php
namespace myfanclub\modules\preferences;

use myfanclub\modules\preferences\controller\MyfcPreferencesController;

class MyfcPreferencesModuleRouter
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
         * @var MyfcPreferencesController $controller
         */
        $controller = new MyfcPreferencesController();

        if (isset($request['page']) && $request['page'] == 'myfc_preferences') {
            if (isset($request['save']) && $request['save'] == true) {
                $controller->myfcSaveData($request);
            }

            $controller->render($request);
        }
    }
}
