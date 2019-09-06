<?php
namespace myfanclub\modules\log\controller;

use Dubture\Monolog\Reader\LogReader;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use myfanclub\modules\log\view\MyfcLogView;

/**
 * Created by IntelliJ IDEA.
 * User: manueldierkes
 * Date: 01.09.17
 * Time: 23:45
 */
class MyfcLogController
{
    private static $_instance = null;

    private $logger;
    private $logDir;


    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }


    public function __construct()
    {
        $this->logDir = plugin_dir_path(__FILE__).'../../../logs/myfanclub.log';


        $this->logger = new Logger('myFanclubLogger');
        $this->logger->pushHandler(new StreamHandler($this->logDir, Logger::INFO));
    }


    private function myfcGetLogsForDays($filter = null, $days = 1)
    {
        try {
            $reader =  new LogReader(plugin_dir_path(__FILE__).'../../../logs/myfanclub.log', $days);
            return $reader;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function myfcInfo($message, $additionalInfoArray)
    {
        $this->logger->info($message, $additionalInfoArray);
    }

    public function myfcWarning($message, $additionalInfoArray)
    {
        $this->logger->warning($message, $additionalInfoArray);
    }

    public function myfcError($message, $additionalInfoArray)
    {
        $this->logger->error($message, $additionalInfoArray);
    }

    public function render()
    {

        /**
         * @var myfcLogView $MyfcLogview
         */
        $myfcLogview = new MyfcLogView();
        $myfcLogview->render($this->myfcGetLogsForDays());
    }
}
