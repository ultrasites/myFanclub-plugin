<?php

namespace myfanclub\modules\member\database;

use myfanclub\database\MyfcDatabase;

class MyfcMemberDatabase extends MyfcDatabase
{
    private $table = 'myfc_member';

    public function table()
    {
        return $this->table;
    }


    public function myfcSelectAllFromTable($orderBy)
    {
        return parent::myfcSelectAllFromTable($this->table, $orderBy);
    }

    /**
     * intersectionOfWpUsersAndFanclubMembers
     *
     * @return array
     * @access public
     */
    public function myfcIntersectionOfWpUsersAndFanclubMembers()
    {
        global $wpdb;
        $sql = "SELECT {$wpdb->prefix}users.ID, {$wpdb->prefix}users.display_name, {$wpdb->prefix}users.user_email FROM {$wpdb->prefix}users WHERE {$wpdb->prefix}users.ID NOT IN (SELECT {$wpdb->prefix}{$this->table}.id FROM {$wpdb->prefix}{$this->table});";
        $results = $wpdb->get_results($sql);
        self::myfcDbError($wpdb, __METHOD__);

        return $results;
    }

    /**
     * selectMemberData
     *
     * @return array
     * @access public
     */
    public function myfcSelectMemberData()
    {
        global $wpdb;
        $currentUserId = get_current_user_id();
        $results       = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}{$this->table} WHERE id = %d;",
                $currentUserId
            ),
            ARRAY_A
        );
        self::myfcDbError($wpdb, __METHOD__);

        return $results;
    }

    /**
     * insertFanclubMember
     *
     * @param $id
     * @param $forename
     * @param $lastname
     * @param $street
     * @param $housenumber
     * @param $plz
     * @param $city
     * @param $email
     * @param $phone
     * @param $birthday
     * @param $start
     * @param $paymentSpecial
     *
     * @access public
     */
    public function myfcInsertFanclubMember(
        $id,
        $forename,
        $lastname,
        $street,
        $housenumber,
        $plz,
        $city,
        $email,
        $phone,
        $birthday,
        $start,
        $paymentSpecial
    ) {
        global $wpdb;
        $tbl_fanclub_members = $wpdb->prefix . $this->table;
        $sql                 = "INSERT INTO {$tbl_fanclub_members} VALUES(%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,'',%s);";

        $wpdb->query(
            $wpdb->prepare(
                $sql,
                $id,
                $forename,
                $lastname,
                $street,
                $housenumber,
                $plz,
                $city,
                $email,
                $phone,
                $birthday,
                $start,
                $paymentSpecial
            )
        );
        self::myfcDbError($wpdb, __METHOD__);
    }

    /**
     * deleteMember
     *
     * @param $id of user
     *
     * @access public
     */
    public function myfcDeleteMember($id)
    {
        global $wpdb;
        $tbl_fanclub_members = $wpdb->prefix . $this->table;
        $sql                 = "DELETE FROM {$tbl_fanclub_members} WHERE id = %d;";

        $wpdb->query(
            $wpdb->prepare(
                $sql,
                $id
            )
        );
        self::myfcDbError($wpdb, __METHOD__);
    }

    /**
     * updateMemberData
     *
     * @param $id
     * @param $forename
     * @param $lastname
     * @param $street
     * @param $housenumber
     * @param $plz
     * @param $city
     * @param $email
     * @param $phone
     * @param $birthday
     * @param $paymentSpecial
     *
     * @access public
     */
    public function myfcUpdateMemberData(
        $id,
        $forename,
        $lastname,
        $street,
        $housenumber,
        $plz,
        $city,
        $email,
        $phone,
        $birthday,
        $paymentSpecial
    ) {
        global $wpdb;

        $sql = "UPDATE {$wpdb->prefix}{$this->table} SET forename = %s, lastname = %s, street = %s, housenumber = %s, plz = %s, city = %s, email = %s, phone = %s, birthday = %s, payment_special = %s WHERE id = %d;";

        $wpdb->query(
            $wpdb->prepare(
                $sql,
                $forename,
                $lastname,
                $street,
                $housenumber,
                $plz,
                $city,
                $email,
                $phone,
                date('Y-m-d', strtotime($birthday)),
                $paymentSpecial,
                $id
            )
        );

        self::myfcDbError($wpdb, __METHOD__);
    }
}
