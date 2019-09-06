<?php

namespace myfanclub\modules\member\database\migrations;

use myfanclub\core\database\MyfcDatabaseMigration;

class MyfcMigrationModuleMember extends MyfcDatabaseMigration
{
    private $table = 'myfc_member';

    public function __construct()
    {
        global $wpdb;
        $this->table = $wpdb->prefix.$this->table;
    }

    public function create()
    {
        $this->up("CREATE TABLE IF NOT EXISTS $this->table (
                id mediumint(9) NOT NULL,
                lastname varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
                forename varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
                street varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                housenumber varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
                plz varchar(5) NOT NULL,
                city varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
                email varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                phone varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                birthday date NOT NULL DEFAULT '0000-00-00',
                start date NOT NULL DEFAULT '0000-00-00',
                last_login date NOT NULL DEFAULT '0000-00-00',
                payment_special varchar(100) COLLATE utf8mb4_unicode_ci,
                UNIQUE KEY id (id))");
    }

    public function delete()
    {
        $this->down($this->table);
    }
}
