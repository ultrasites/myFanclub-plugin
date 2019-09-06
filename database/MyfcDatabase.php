<?php

namespace myfanclub\database;

use myfanclub\core\MyfcCore;
use myfanclub\core\events\MyfcEventManager;

/**
 * Database
 *
 * It includes some database helper functions
 *
 * @package  core
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  alpha
 * @access   public
 * @see      https://myfanclub.ultra-sites.de
 */
class MyfcDatabase
{

    /**
     * dbError
     *
     * @access protected
     */
    public function myfcDbError($wpdb, $method)
    {
        if ( ! empty($wpdb->last_error)) {

            if (MyfcCore::getInstance()->isModuleRegistered('log')) {
                MyfcEventManager::getInstance()->trigger(new \myfanclub\modules\log\events\MyfcAddLogEvent(), [
                    'level'   => 'error',
                    'message' => 'Database error',
                    'data'    => $method . ' -- ' . $wpdb->last_error
                ]);
            }

            $wpdb->last_error = '';
        }
    }

    /**
     * selectAllFromTable
     *
     * @param $table
     * @param $orderBy
     *
     * @return array
     * @access protected
     */
    protected function myfcSelectAllFromTable($table, $orderBy)
    {
        global $wpdb;
        $results = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}{$table} ORDER BY %s;", $orderBy)
        );
        self::myfcDbError($wpdb, __METHOD__);

        return $results;
    }

    /**
     * countEntriesFromTable
     *
     * @param $table
     *
     * @return array
     * @access protected
     */
    public function myfcCountEntriesTable($table)
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT COUNT(*) FROM {$wpdb->prefix}{$table};");
        self::myfcDbError($wpdb, __METHOD__);

        return $results;
    }

    /**
     * selectWpUser
     *
     * @param $id of wp user
     *
     * @return array
     * @access protected
     */
    public function myfcSelectWpUser($id)
    {
        global $wpdb;
        $results = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT display_name, user_email, user_login FROM {$wpdb->prefix}users WHERE ID = %d;",
                $id), ARRAY_A);
        self::myfcDbError($wpdb, __METHOD__);

        return $results;
    }

    /**
     * selectUserCapability
     *
     * @param $id of user
     *
     * @return array
     * @access protected
     */
    public function myfcSelectUserCapability($id)
    {
        global $wpdb;
        $results = $wpdb->get_row(
            $wpdb->prepare("SELECT {$wpdb->prefix}usermeta.meta_value FROM {$wpdb->prefix}usermeta WHERE {$wpdb->prefix}usermeta.user_id = %s AND {$wpdb->prefix}usermeta.meta_key= %s;",
                $id,
                "{$wpdb->prefix}capabilities"),
            ARRAY_A);
        self::myfcDbError($wpdb, __METHOD__);

        return $results;
    }
}
