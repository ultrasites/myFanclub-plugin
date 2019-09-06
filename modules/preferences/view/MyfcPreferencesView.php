<?php
namespace myfanclub\modules\preferences\view;

use myfanclub\core\MyfcModuleView;
use myfanclub\core\MyfcCore;
use myfanclub\modules\preferences\service\MyfcPreferencesTemplateService;

/**
 * MemberAdministration
 *
 * View class for MemberAdministration
 *
 *
 * @package  views
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  alpha
 * @access   public
 * @see      http://myfanclub.ultra-sites.de
 */
class MyfcPreferencesView extends MyfcModuleView
{

    /**
     * @var MyfcCore $core;
     */
    private $core;

    /**
    * @var MyfcPreferencesTemplateService $templateService
    */
    private $templateService;

    private $tabMenue;


    public function __construct()
    {
        $this->core = MyfcCore::getInstance();
        $this->templateService = new MyfcPreferencesTemplateService();
    }



    /**
     * Render MemberAdministration user interface
     *
     * @access public
     */
    public function render($tabMenue, $tabContent, $tabContentSavedData, $request)
    {
        if (!empty($request['save'])) {
            $this->successAlert('Die Einstellungen wurden erfolgreich gespeichert!');
        }

        $this->tabMenue = $tabMenue; ?>
         <div class="wrap">
          <div class="ui raised segment wp-fanclub-raised-segment">
            <?php $this->header(); ?>
            <h1 class="wp-fanclub-h1">Einstellungen</h1>
              <hr class="wp-fanclub-hr" />
            <div class="ui secondary menu" id="preferences">

                <?php
                
                foreach ($this->tabMenue as $key => $tabItem) {
                    ?>
                <a class="item <?= $key === 0 ? "active" : ""; ?>" data-tab="<?= $tabItem ?>"><?= $tabItem ?></a>

                    <?php
                } ?>
                
            </div>

            <form action="<?= get_site_url() . '/wp-admin/admin.php?page=myfc_preferences&save=true'; ?>" method="Post">
            <?php
            foreach ($tabContent as $key => $tabContentItem) {
                echo  $this->templateService->generate($tabContentItem, $tabContentSavedData[$key]);
            } ?>
            <input type="submit" class="ui button green " value="Speichern" />
            </form>
          </div>
             <?php
                $this->footer(); ?>
        </div>
        <?php
    }
}

?>