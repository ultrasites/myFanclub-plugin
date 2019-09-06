<?php
require_once 'vendor/autoload.php';
/*
  Plugin Name: myFanclub
  Plugin URI: https://myfanclub.ultra-sites.de
  Description: Dieses Plugin vereinfacht die Arbeit der Fanclubvorstände. Neben der Mitgliederverwaltung können u.a. auch Tickets oder Finanzen verwaltet werden.
  Version: Beta
  Author: Ultra Sites Medienagentur
  Author URI: https://www.ultra-sites.de
  Update Server: https://myfanclub.ultra-sites.de
  Min WP Version: 1.5
  Max WP Version: 2.0.4
  License: MIT
 */

function myfc_init_roles()
{
    // gets the author role
    $role = get_role('administrator');

    //Base Capabilities
    add_role('associationSecretary', 'Schriftführer');
    add_role('associationCashier', 'Kassierer');
    add_role('associationChairman', 'Vorsitzender');
    add_role('associationMember', 'Mitglied');


    $core = myfanclub\core\MyfcCore::getInstance();
    foreach ($core->getRegisteredModuleFactories() as $moduleFactory) {
        $class = new $moduleFactory();

        $moduleCaps[] = $class->myfcCapabilitiesAdministrator();
        $moduleCaps[] = $class->myfcCapabilitiesSecretary();
        $moduleCaps[] = $class->myfcCapabilitiesCashier();
        $moduleCaps[] = $class->myfcCapabilitiesMember();
        $moduleCaps[] = $class->myfcCapabilitiesChairman();


        foreach ($moduleCaps as $key => $capCollection) {
            if (! empty($capCollection)) {
                switch ($key) {
                    case 0:
                        $role = get_role('administrator');
                        break;
                    case 1:
                        $role = get_role('associationSecretary');
                        $role->add_cap('read');
                        break;
                    case 2:
                        $role = get_role('associationCashier');
                        $role->add_cap('read');
                        break;
                    case 3:
                        $role = get_role('associationMember');
                        break;
                    case 4:
                        $role = get_role('associationChairman');
                        $role->add_cap('read');
                        break;
                }

                foreach ($capCollection as $cap) {
                    $role->add_cap($cap);
                }
            }
        }

        $moduleCaps = null;
    }
}

//Init Plugin
add_action('admin_init', 'myfc_init_roles');
add_filter('admin_footer_text', '__return_false');
add_filter('update_footer', '__return_false', 11);


//Plugin Menu
function myfcPlugin_menu()
{
    add_menu_page("myFanclub", "myFanclub", '', 'admin-myfc', '', plugins_url() . '/myfanclub/res/images/icon.png');

    $core   = myfanclub\core\MyfcCore::getInstance();
    $menues = $core->registeredSubMenues();

    foreach ($menues as $menue) {
        add_submenu_page(
            'admin-myfc',
            $menue['pageTitle'],
            $menue['label'],
            $menue['capability'],
            $menue['menueSlug'],
            $menue['callback']
        );
    }
}


add_action('admin_menu', 'myfcPlugin_menu');


//Header
function myfcRegister_scripts()
{
    wp_enqueue_script('jquery');

    wp_register_script('myfc_semantic_ui', 'https://cdn.jsdelivr.net/npm/fomantic-ui@2.7.5/dist/semantic.min.js');
    wp_enqueue_script('myfc_semantic_ui');

    wp_register_script('myfc_mitgliederVerwaltung', plugins_url('/js/myfc_member_administration.js', __FILE__));
    wp_enqueue_script('myfc_mitgliederVerwaltung');

    wp_register_script('myfc_einstellungen', plugins_url('/js/myfc_preferences.js', __FILE__));
    wp_enqueue_script('myfc_einstellungen');

    wp_register_script('myfc_ultrasitesads', 'https://ultra-sites.de:8085/javascripts/UltraSitesAdsAPI.min.js');
    wp_enqueue_script('myfc_ultrasitesads');
}

add_action('admin_enqueue_scripts', 'myfcRegister_scripts');


function myfcRegister_styles()
{
    wp_register_style('css-myfc', plugins_url('/style.css', __FILE__));
    wp_enqueue_style('css-myfc');
}

add_action('admin_init', 'myfcRegister_styles', 20);


function myfcRegister_dependencyStyles()
{
    wp_register_style('myfc_css_semantic_ui', 'https://cdn.jsdelivr.net/npm/fomantic-ui@2.7.5/dist/semantic.min.css');
    wp_enqueue_style('myfc_css_semantic_ui');
}

add_action('admin_init', 'myfcRegister_dependencyStyles', 10);

//Plugin Activation
register_activation_hook(__FILE__, 'myfcActivate');

function myfcActivate()
{

    /**
     * @var MyfcCore $core
     */
    $core = myfanclub\core\MyfcCore::getInstance();
    $core->migrate(true);
}


function myfcDeactivate()
{
    remove_role('associationMember');
    remove_role('associationCashier');
    remove_role('associationSecretary');
    remove_role('associationChairman');

    $role = get_role('administrator');

    $role->remove_cap('myfc_app_preferences');
    $role->remove_cap('myfc_app_logs');

    $core = myfanclub\core\MyfcCore::getInstance();
    foreach ($core->getRegisteredModuleFactories() as $moduleFactory) {
        $class = new $moduleFactory();

        $moduleAdministratorCaps = $class->myfcCapabilitiesAdministrator();

        if (! empty($moduleAdministratorCaps)) {
            foreach ($moduleAdministratorCaps as $cap) {
                $role->remove_cap($cap);
            }
        }
    }
}

register_deactivation_hook(__FILE__, 'myfcDeactivate');

function myfcUninstall()
{
    myfcDeactivate();

    /**
     * @var MyfcCore $core
     */
    $core = myfanclub\core\MyfcCore::getInstance();
    $core->migrate(false);
}

register_uninstall_hook(__FILE__, 'myfcUninstall');


/**
 * @var MyfcCore $core
 */
$core = myfanclub\core\MyfcCore::getInstance();
$core->boot();
$providers = $core->searchModuleProviders();

foreach ($providers as $provider) {
    include "$provider";
}

require 'shortcodes.php';
