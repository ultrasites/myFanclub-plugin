<?php
namespace myfanclub\core;

use myfanclub\helper\MyfcJsonData;

/**
 * Module Collector
 *
 * Collects configuration files from the module
 *
 * @package  core
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  beta
 * @access   public
 * @see      https://myfanclub.ultra-sites.de
 */
class MyfcModuleCollector
{
    private static $_instance = null;

    private $registeredModules = [];

    private $DATA;

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }


    public function collect()
    {
        $this->registeredModules = MyfcCore::getInstance()->getRegisteredModulesList();

        foreach ($this->registeredModules as $registeredModule) {
            $moduleData = $this->loadModuleJsonFiles($registeredModule);

            if (!empty($moduleData)) {
                $this->DATA[$registeredModule]['INFO'] = $moduleData[0];
                $this->DATA[$registeredModule]['PREFERENCES']['CONFIG'] = $moduleData[1];
                $this->DATA[$registeredModule]['PREFERENCES']['VIEW'] = $moduleData[2];
            }
        }
    }

    private function loadModuleJsonFiles($module)
    {
        $filePath = WP_PLUGIN_DIR . '/myfanclub/modules/' . $module . '/module.json';
        $filePathConfig = WP_PLUGIN_DIR . '/myfanclub/modules/' . $module . '/preferencesConfig.json';
        $filePathView = WP_PLUGIN_DIR . '/myfanclub/modules/' . $module . '/preferencesView.json';

        if (file_exists($filePath) && file_exists($filePathConfig) && file_exists($filePathView)) {
            return [MyfcJsonData::myfcLoadData($filePath), MyfcJsonData::myfcLoadData($filePathConfig), MyfcJsonData::myfcLoadData($filePathView)];
        }
    }

    public function reCollect($module)
    {
        $moduleData = $this->loadModuleJsonFiles($module);

        if (!empty($moduleData)) {
            $this->DATA[$module]['INFO'] = $moduleData[0];
            $this->DATA[$module]['PREFERENCES']['CONFIG'] = $moduleData[1];
            $this->DATA[$module]['PREFERENCES']['VIEW'] = $moduleData[2];
        }
    }

    public function load($module)
    {
        return $this->DATA[$module];
    }

    public function loadAll()
    {
        return $this->DATA;
    }
}
