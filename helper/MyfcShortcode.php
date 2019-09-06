<?php
namespace myfanclub\helper;

/**
 * Shortcode
 *
 * Helper Class for Shortcodes
 *
 *
 * @package  helpers
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  alpha
 * @access   public
 * @see      http://myfanclub.ultra-sites.de
 */
class MyfcShortcode
{


    /**
     * Render success alert for shortcode
     *
     * @param $description
     * @access public
     */
    public static function myfcSuccessAlert($description)
    {
        ?>
        <div id="myfc_web_ownData_successAlert" class="alert alert-success" role="alert">
            <b><i class="fa fa-check" aria-hidden="true"></i></b> <?= $description ?>
        </div>
        <script>
            $("#myfc_web_ownData_successAlert").fadeTo(2500, 500).fadeOut(500, function () {
                $("#myfc_web_ownData_successAlert").fadeOut(500);
            });
        </script>
        <?php
    }

    /**
     * Render Js Script
     *
     * @access public
     */
    public static function myfcAddJS()
    {
        ?>
        <script src="<?= plugin_dir_url(dirname(__FILE__)).'/js/myfc_web_ownData.js' ?>"></script>
        <?php
    }
}
