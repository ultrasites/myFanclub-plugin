<?php
namespace myfanclub\helper;

/**
 * Loads the config.json
 *
 *
 * @package  helpers
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  beta
 * @access   public
 * @see      https://myfanclub.ultra-sites.de
 */
class MyfcJsonData
{


    /**
     * load the data
     *
     * @return string
     * @access public
     */
    public static function myfcLoadData($filePath)
    {
        $file = file_get_contents($filePath);
        return json_decode($file);
    }

    /**
     * save the data
     *
     * @param  $date
     * @access public
     */
    public static function myfcSaveData($data, $filePath)
    {
        $fp = fopen($filePath, 'w+');
        fwrite($fp, json_encode($data));
        fclose($fp);
    }
}

;
