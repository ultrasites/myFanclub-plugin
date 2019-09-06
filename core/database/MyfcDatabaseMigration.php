<?php
namespace myfanclub\core\database;

/**
 * class myfcDatabaseMigration
 *
 * Base class to migrate or delete sql structures
 *
 * @package  core/database
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  beta
 * @access   public
 * @see      https://myfanclub.ultra-sites.de
 */
class MyfcDatabaseMigration
{
    protected function up($sql)
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    protected function down($table)
    {
        global $wpdb;
        $sql = "DROP TABLE " .$table;
        $wpdb->query($sql);
    }
}
