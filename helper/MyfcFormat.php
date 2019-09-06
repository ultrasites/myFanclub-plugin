<?php
namespace myfanclub\helper;

/**
 * Format Helper
 *
 * Helper Class to format some data
 *
 *
 * @package  helper
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  alpha
 * @access   public
 * @see      http://myfanclub.ultra-sites.de
 */
class MyfcFormat
{

    /**
     * format the unix date format to european date format
     *
     * @param  $date
     * @return string
     * @access public
     */
    public static function myfcFormatDateToEuropean($date)
    {
        $datetime = new \DateTime($date);
        return $datetime->format('d.m.Y');
    }

    /**
     * format the european date format to unix date format
     *
     * @param  $date
     * @return string
     * @access public
     */
    public static function myfcFormatDateToMySQLDate($date)
    {
        $dateTime = new \DateTime($date);
        return $dateTime->format('Y-m-d');
    }

    /**
     * calculate the unix date in years
     *
     * @param  $date
     * @return string
     * @access public
     */
    public static function myfcDateInYears($date)
    {
        $birthday = new \DateTime($date);
        $today = new \DateTime(date('Y-m-d'));
        $diff = $birthday->diff($today);
        return $diff->format('%y');
    }

    /**
     * splits the display name to an array with fore- and lastname
     *
     * @param  $date
     * @return array
     * @access public
     */
    public static function myfcSplitDisplayName($displayName)
    {
        $nameArray = explode(' ', $displayName);

        if (count($nameArray) > 2) {
            return [
                'forename' => $nameArray[0],
                'lastname' => $nameArray[1] . ' ' . $nameArray[2]
            ];
        } else {
            return [
                'forename' => $nameArray[0],
                'lastname' => $nameArray[1]
            ];
        }
    }
}
