<?php
namespace myfanclub\modules\log\events;

use myfanclub\core\events\MyfcEvent;
use myfanclub\modules\log\controller\MyfcLogController;

class MyfcAddLogEvent implements MyfcEvent
{
    public static function className()
    {
        return 'myfcAddLogEvent';
    }

    public function handle($params = [])
    {

        /**
         * @var MyfcLogController $myfcLogController
         */
        $myfcLogController = MyfcLogController::getInstance();

        $additionalInfo = [
            'username' => wp_get_current_user()->display_name,
            'data' => $params['data']
        ];

        switch ($params['level']) {
            case 'info':
                $myfcLogController->myfcInfo($params['message'], $additionalInfo);
                break;
            case 'warning':
                $myfcLogController->myfcWarning($params['message'], $additionalInfo);
                break;
            case 'error':
                $myfcLogController->myfcError($params['message'], $additionalInfo);
                break;
        }
    }
}
