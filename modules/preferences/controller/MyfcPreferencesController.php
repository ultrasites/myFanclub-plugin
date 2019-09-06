<?php
namespace myfanclub\modules\preferences\controller;

use myfanclub\core\events\MyfcEventManager;
use myfanclub\helper\MyfcSecure;
use myfanclub\modules\preferences\view\MyfcPreferencesView;
use myfanclub\helper\MyfcJsonData;
use myfanclub\core\MyfcCore;
use myfanclub\core\MyfcModuleCollector;
use myfanclub\modules\preferences\service\MyfcPreferencesPaymentCollectorService;

/**
 * Controller for member administration
 *
 *
 * @package  controller
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  alpha
 * @access   public
 * @see      http://myfanclub.ultra-sites.de
 */
class MyfcPreferencesController
{
    private $tabMenue;
    private $tabContent;
    private $tabContentSavedData;

    /**
     * MyfcCore $core
     */
    private $core;

    /**
     * MyfcPreferencesPaymentCollectorService $paymentCollectorService
     */
    private $paymentCollectorService;


    public function __construct()
    {
        $this->core = MyfcCore::getInstance();
        $this->loadTabMenues();
        $this->paymentCollectorService = new MyfcPreferencesPaymentCollectorService();
    }

    private function loadTabMenues()
    {
        $registeredModules = $this->core->getRegisteredModulesList();

        //Base Preferences at first
        $key = array_search('preferences', $registeredModules);
        $this->loadData($registeredModules[$key]);
        unset($registeredModules[$key]);

        foreach ($registeredModules as $registeredModule) {
            $this->loadData($registeredModule);
        }
    }

    private function loadData($module)
    {
        $preferences = MyfcModuleCollector::getInstance()->load($module)['PREFERENCES'];

        $dataConfig = $preferences['CONFIG'];
        $dataView = $preferences['VIEW'];

        foreach ($dataConfig as $tabItems) {
            $this->tabMenue[] = $tabItems->name;
            $this->tabContentSavedData[] = $tabItems;
        }

        foreach ($dataView as $tabContent) {
            $this->tabContent[] = $tabContent;
        }
    }

    public function render($request)
    {

        /**
         * @var MyfcPreferencesView $view
         */
        $view = new MyfcPreferencesView();


        $view->render($this->tabMenue, $this->tabContent, $this->tabContentSavedData, $request);
    }


    public function myfcSaveData($request)
    {
        $collections = MyfcModuleCollector::getInstance()->loadAll();

        $paymentModeSolid = $request['preferences_payment_mode'];
        unset($request['preferences_payment_mode']);

        foreach ($request as $paramKey => $paramValue) {
            foreach ($collections as $moduleName => $collection) {
                if (strpos($paramKey, $moduleName) !== false) {
                    $config = $collection['PREFERENCES']['CONFIG'];

                    foreach ($config as $configElem) {
                        if (strpos($paramKey, strtolower($configElem->name)) !== false) {
                            if (is_array($paramValue)) {
                                //TODO make it dynamic, current only payment
                                if ($paymentModeSolid) {
                                    $request['preferences_payment_age'] = null;
                                    $request['preferences_payment_description'] = null;

                                    $payment = $this->paymentCollectorService->collectSolidPayment($request['preferences_payment_amount']);
                                    $configElem->config = $payment;
                                } elseif ($request['preferences_payment_age'] != null || $request['preferences_payment_description'] != null) {
                                    $payment = $this->paymentCollectorService->collectDynamicPayment(
                                        $request['preferences_payment_amount'],
                                        $request['preferences_payment_age'],
                                        $request['preferences_payment_description']
                                    );

                                    $configElem->config = $payment[0];
                                }
                            } else {
                                //STMP Password
                                if ($paramKey == 'mail_smtpPassword' && !empty($paramValue)) {
                                    $myfcSecure = new MyfcSecure();

                                    if ($paramValue != $configElem->config->{$paramKey}) {
                                        $paramValue = $myfcSecure->myfcEncrypt($paramValue);
                                        $configElem->config->{$paramKey} = $paramValue;
                                        continue;
                                    }
                                }

                                if ($paramValue !== $configElem->config->{$paramKey}) {
                                    $configElem->config->{$paramKey} = $paramValue;
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($collections as $moduleName => $collection) {
            $this->myfcSaveConfig($moduleName, $collection['PREFERENCES']['CONFIG']);

            if (MyfcCore::getInstance()->isModuleRegistered('log')) {
                MyfcEventManager::getInstance()->trigger(new \myfanclub\modules\log\events\MyfcAddLogEvent(), [
                    'level' => 'info',
                    'message' => 'Preferences saved',
                    'data' => __METHOD__.json_encode($collection['PREFERENCES']['CONFIG'][0]->config)
                ]);
            }
        }
    }

    private function myfcSaveConfig($module, $config)
    {
        $filePathConfig = WP_PLUGIN_DIR . '/myfanclub/modules/' . $module . '/preferencesConfig.json';

        if (file_exists($filePathConfig)) {
            MyfcJsonData::myfcSaveData($config, $filePathConfig);
        }
    }
}
