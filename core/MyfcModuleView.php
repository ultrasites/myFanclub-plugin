<?php
namespace myfanclub\core;

/**
 * View
 *
 * Parent view class
 *
 *
 * @package  views
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  beta
 * @access   public
 * @see      https://myfanclub.ultra-sites.de
 */
class MyfcModuleView
{

    /**
     * Render successAlert
     *
     * @param $description
     * @access public
     */
    protected function successAlert($description)
    {
        ?>
        <div id="successAlert" class="ui green icon small message" role="alert" style="position:absolute; width:30%; top:5px; right:15px; z-index:10000;">
            <i class="check icon"></i>
            <div class="content">
                <?= $description ?>
            </div>
        </div>
        <script>
            jQuery("#successAlert").fadeTo(1500, 500).fadeOut(500, function () {
                jQuery("#successAlert").fadeOut(500);
            });
        </script>
        <?php
    }

    /**
     * Render errorAlert
     *
     * @param $description
     * @access public
     */
    protected function errorAlert($description)
    {
        ?>
       <div id="errorAlert" class="ui red icon small message" role="alert" style="position:absolute; width:30%; top:5px; right:15px; z-index:10000;">
            <i class="exclamation circle icon"></i>
            <div class="content">
                <?= $description ?>
            </div>
        </div>
        <script>
            jQuery("#errorAlert").fadeTo(1500, 500).fadeOut(500, function () {
                jQuery("#errorAlert").fadeOut(500);
            });
        </script>
        <?php
    }

    /**
     * Render header
     *
     * @access public
     */
    protected function header()
    {
        ?>
        <div class="ui grid">
            <div class="four wide column">
                <img class="ui wp-fanclub-logo" src="<?= get_site_url(); ?>/wp-content/plugins/myfanclub/res/images/logo.png" alt="myFanclub Logo" />
                <b>BETA</b>
            </div>
            <div class="twelve wide column">
                <script type="text/javascript">
                    document.addEventListener('DOMContentLoaded', function(){
                        UltraSitesAdsApi.prototype.api();
                    });
                </script>

                <div class="ultra-sites-ads" type="banner" subtype="standard" targetGroup="myfanclub" style="float: right;"></div>
            </div>
        </div>


        <?php
    }

    protected function footer()
    {
        ?>
        <div class="wp-fanclub-owner">
        <a href="https://myfanclub.ultra-sites.de" class="wp-fanclub-a" target="_blank">myFanclub</a> ist ein Produkt der <a href="https://www.ultra-sites.de" class="wp-fanclub-a" target="_blank">Ultra Sites Medienagentur</a> (2016-2019)
        </div>
        <?php
    }
}
