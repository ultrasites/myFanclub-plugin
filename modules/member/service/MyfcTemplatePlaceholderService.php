<?php

namespace myfanclub\modules\member\service;

use myfanclub\core\MyfcCore;
use myfanclub\helper\MyfcFormat;

class MyfcTemplatePlaceholderService
{
    /**
     * replace placeholders with data
     *
     * @param  $params
     * @param  $template current template
     * @param  $memberData
     * @return string
     * @access public
     */
    public static function myfcGenerateTemplate($template, $memberData)
    {
        $suchmuster[0] = '{{forename}}';
        $suchmuster[1] = '{{lastname}}';
        $suchmuster[2] = '{{street}}';
        $suchmuster[3] = '{{housenumber}}';
        $suchmuster[4] = '{{plz}}';
        $suchmuster[5] = '{{city}}';
        $suchmuster[6] = '{{birthday}}';
        $suchmuster[7] = '{{birthdayInYears}}';
        $suchmuster[8] = '{{phone}}';
        $suchmuster[9] = '{{email}}';
        $suchmuster[10] = '{{start}}';
        $suchmuster[11] = '{{startInYears}}';

        $ersetzung[0] = $memberData['forename'];
        $ersetzung[1] = $memberData['lastname'];
        $ersetzung[2] = $memberData['street'];
        $ersetzung[3] = $memberData['housenumber'];
        $ersetzung[4] = $memberData['plz'];
        $ersetzung[5] = $memberData['city'];
        $ersetzung[6] = MyfcFormat::myfcFormatDateToEuropean($memberData['birthday']);
        $ersetzung[7] = MyfcFormat::myfcDateInYears($memberData['birthday']);
        $ersetzung[8] = $memberData['phone'];
        $ersetzung[9] = $memberData['email'];
        $ersetzung[10] = MyfcFormat::myfcFormatDateToEuropean($memberData['start']);
        $ersetzung[11] = MyfcFormat::myfcDateInYears($memberData['start']);


        $modulesDir = MyfcCore::getInstance()->getModulesDirectory();

        $url = $modulesDir . '/member/templates' . $template;

        $content = file_get_contents($url);

        return preg_replace($suchmuster, $ersetzung, $content);
    }
}
