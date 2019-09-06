<?php
/**
 * Render a template with member data
 *
 * @return string|void
 */
function myfc_shortcode_ownData()
{
    if (is_user_logged_in()) {
        $database = new \myfanclub\modules\member\database\myfcMemberDatabase();

        $response = '';

        if ($_POST['type'] == 'myfc_edit_ownData') {
            $database->myfcUpdateMemberData(
                get_current_user_id(),
                $_POST['forename'],
                $_POST['lastname'],
                $_POST['street'],
                $_POST['housenumber'],
                $_POST['plz'],
                $_POST['city'],
                $_POST['email'],
                $_POST['phone'],
                $_POST['birthday'],
                ''
            );

            $response = \myfanclub\helpers\myfcShortcode::myfcSuccessAlert('Die Änderungen wurden gespeichert!');
            unset($_POST['type']);
        }

        $config = \myfanclub\core\myfcModuleCollector::getInstance()->load('member')['PREFERENCES']['CONFIG'][0]->config;


        $response .= \myfanclub\modules\member\service\myfcShortcodeTemplatePlaceholderService::myfcGenerateTemplate(
            $config->member_ownDataTemplate,
            $database->myfcSelectMemberData()
        );

        $response .= \myfanclub\helpers\myfcShortcode::myfcAddJS();

        return $response;
    } else {
        return 'Cannot render template, because the current user is not logged in.';
    }
}

add_shortcode('myfc_tpl_ownData', 'myfc_shortcode_ownData');
