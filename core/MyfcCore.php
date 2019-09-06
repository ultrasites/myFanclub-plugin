<?php

namespace myfanclub\core;

use myfanclub\core\events\MyfcEventManager;
use myfanclub\modules\mail\events\MyfcMailSendMailEvent;

/**
 * core class
 *
 * Base class of the plugin. E.g. It collects the module factories or providers
 *
 * @package  core
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  beta
 * @access   public
 * @see      https://myfanclub.ultra-sites.de
 */
class MyfcCore
{
    private static $_instance = null;

    private $registeredModules = [];
    private $registeredModuleFactories = [];

    public $modulesDir = WP_PLUGIN_DIR . '/myfanclub/modules';


    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function boot()
    {

        //Register Modules
        $foundedModules = preg_grep('/^([^.])/', scandir($this->modulesDir));

        if (empty($this->registeredModules)) {
            $this->registeredModules = $foundedModules;
        }

        $this->searchModuleFactories();

        //Collect Module Informations
        MyfcModuleCollector::getInstance()->collect();


        /**
         * @var MyfcEventManager $eventManager
         */
        $eventManager = MyfcEventManager::getInstance();

        $eventManager->attach(MyfcMailSendMailEvent::className());
    }

    public function migrate($create)
    {
        foreach ($this->registeredModules as $registeredModule) {
            $foundedMigrations = preg_grep('/^([^.])/', scandir($this->modulesDir . '/' . $registeredModule . '/database/migrations'));

            foreach ($foundedMigrations as $key => $migration) {
                $foundedMigrations[$key] = substr($migration, 0, -4);
            }

            foreach ($foundedMigrations as $migration) {
                $specClass = "myfanclub\modules\\" . $registeredModule . "\database\migrations\\$migration";
                $class = new $specClass();

                if ($create) {
                    $class->create();
                } else {
                    $class->delete();
                }
            }
        }
    }

    private function searchModuleFactories()
    {
        $foundedFactories = [];

        foreach ($this->registeredModules as $module) {
            $foundedFactory = preg_grep('/^([^.])/', scandir($this->modulesDir . '/' . $module));

            foreach ($foundedFactory as $factory) {
                $needed = 'Myfc' . ucfirst($module) . 'ModuleFactory.php';

                if ($factory == $needed) {
                    $foundedFactories[] = "myfanclub\modules\\" . $module . "\\" . substr($factory, 0, -4);
                    break;
                }
            }
        }

        $this->registeredModuleFactories = $foundedFactories;
    }

    public function registeredSubMenues()
    {
        $subMenues = [];

        foreach ($this->registeredModuleFactories as $moduleFactory) {

            /**
             * @var MyfcModuleFactory $class
             */
            $class = new $moduleFactory();

            $subMenueEntry = $class->myfcSubMenueEntry();

            if (!empty($subMenueEntry)) {
                $subMenues[$subMenueEntry["menuePosition"]] = $subMenueEntry;
            }

            //Register Events
            $class->myfcEventRegister();
        }

        ksort($subMenues);

        return $subMenues;
    }

    public function searchModuleProviders()
    {
        $foundedProviders = [];

        foreach ($this->registeredModules as $module) {
            $foundedProvider = preg_grep('/^([^.])/', scandir($this->modulesDir . '/' . $module));

            foreach ($foundedProvider as $provider) {
                $needed = 'Myfc' . ucfirst($module) . 'ModuleProvider.php';

                if ($provider == $needed) {
                    $foundedProviders[] = "modules/" . $module . "/" . $needed;
                    break;
                }
            }
        }

        return $foundedProviders;
    }

    public function isModuleRegistered($module)
    {
        if (in_array($module, $this->registeredModules)) {
            return true;
        }

        return false;
    }

    public function getRegisteredModulesList()
    {
        return $this->registeredModules;
    }

    public function getModulesDirectory()
    {
        return $this->modulesDir;
    }

    public function getRegisteredModuleFactories()
    {
        return $this->registeredModuleFactories;
    }
}
