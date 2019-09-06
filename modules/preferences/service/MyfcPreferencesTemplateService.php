<?php
namespace myfanclub\modules\preferences\service;

use myfanclub\helper\MyfcPreferenceData;

/**
 * Controller for member administration
 *
 *
 * @package  controller
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  alpha
 * @access   public
 * @see      http://myfanclub.ultra-sites.de
 */
class MyfcPreferencesTemplateService
{
    private function text($labelName, $name, $placeholder, $value = "")
    {
        return "<div class=\"ui labeled fluid input wp-fanclub-margin-bottom-10\">
                <div class=\"ui label\">{$labelName}</div>
                <input type=\"text\" placeholder=\"{$placeholder}\" name=\"{$name}\" value=\"{$value}\"/>
                </div>";
    }

    private function password($labelName, $name, $placeholder, $value = "")
    {
        return "<div class=\"ui labeled fluid input wp-fanclub-margin-bottom-10\">
                <div class=\"ui label\">{$labelName}</div>
                <input type=\"password\" placeholder=\"{$placeholder}\" name=\"{$name}\" value=\"{$value}\"/>
                </div>";
    }


    private function dropdown($labelName, $name, $options, $value)
    {
        $bigValue = strtoupper($value);

        $result = "
                <div class=\"ui labeled fluid input wp-fanclub-margin-bottom-10\">
                <div class=\"ui label\">{$labelName}</div>
                <div class=\"ui fluid selection dropdown\">
                <input type=\"hidden\" name=\"{$name}\" tabindex=\"-1\" />
                <i class=\"dropdown icon\"></i>
                <div class=\"text\">{$bigValue}</div>
                <div class=\"menu\">";

        foreach ($options as $option) {
            $result .= "<div class=\"item\" data-value=\"{$option->value}\">{$option->name}</div>";
        }

        $result .= "</div></div></div>";

        return $result;
    }



    private function solidPayment($labelName, $value = "", $checked = true)
    {
        $checkedAttr = $checked ? "checked = \"checked\"" : "";

        return "<div class=\"ui toggle checkbox input wp-fanclub-margin-bottom-10\">
                    <input type=\"checkbox\" {$checkedAttr} id=\"myfc_preferences_payment_mode\" name=\"preferences_payment_mode\" value=\"true\" />
                    <label>Fester Beitrag</label>
                </div><br />
                <div id=\"myfc_preferences_payment_mode_solidPayment\" class=\"ui right labeled input wp-fanclub-margin-bottom-10\">
                <div class=\"ui label\">{$labelName}</div>
                <input type=\"text\" name=\"preferences_payment_amount[]\" value=\"{$value}\"/>
                <div class=\"ui basic label\">€</div>
                </div>";
    }

    private function dynamicPayment($labelName, $numberOfFields, $values)
    {
        $result = "<div id=\"myfc_preferences_payment_dynamicPayment\" class=\"wp-fanclub-margin-bottom-10\">
                    Dynamischer Beitrag
                    <div class=\"ui relaxed grid\">";

        for ($i = 0; $i < $numberOfFields; $i++) {
            $result .= "<div class=\"three column row\">";

            $result .= "<div class=\"column\">";

            $value = MyfcPreferenceData::myfcIsSolidPayment($values) ? "" : $values[$i]->amount;

            $result .= "<div class=\"ui right labeled input\">
            <div class=\"ui label\">{$labelName}</div>
            <input type=\"text\" name=\"preferences_payment_amount[]\" value=\"{$value}\"/>
            <div class=\"ui basic label\">€</div>
            </div>";

            $result .= "</div>";


            $result .= "<div class=\"column\">";
            $result .= "<div class=\"ui right labeled input\">
            <div class=\"ui label\">Alter bis</div>
            <input type=\"text\" name=\"preferences_payment_age[]\" value=\"{$values[$i]->age}\"/>
            <div class=\"ui basic label\">Jahr/e</div>
            </div>";

            $result .= "</div>";



            $result .= "<div class=\"column\">";
            $result .= "<div class=\"ui labeled input\">
            <div class=\"ui label\">Sonderstellung</div>
            <input type=\"text\" name=\"preferences_payment_description[]\" value=\"{$values[$i]->description}\"/>
            </div>";

            $result .= "</div></div>";
        }


        $result .= "</div></div>";

        return $result;
    }

    private function media($labelName, $dataSrc, $dataUrl, $type, $name)
    {
        if (intval($dataUrl) > 0) {
            $image = wp_get_attachment_image($dataUrl, 'medium', false, array('id' => 'myfc-preview-image', 'class' => 'ui large image'));
        } else {
            $image = "<img class=\"ui large image\" id=\"myfc-preview-image\" src=\"" . get_site_url() . "/wp-content/plugins/myfanclub/modules/" . $dataSrc . $dataUrl . "\">";
        }


        $result = "<div class=\"ui card wp-fanclub-margin-bottom-10\" style=\"padding:0;\">
        <div class=\"ui label\" style=\"font-size:1rem; width: 100%;\">{$labelName}</div>
        <div class=\"blurring dimmable image\">
          <div class=\"ui inverted dimmer\">
            <div class=\"content\">
              <div class=\"center\">
                <div class=\"ui primary button\" id=\"myfc_media_manager\">Bild ändern</div>
              </div>
            </div>
          </div>
          {$image}
          <input type=\"hidden\" name=\"{$name}\" id=\"myfc_image_id\" value=\"{$dataUrl}\" class=\"regular-text\" />
        </div>
      </div>";


        return $result;
    }


    private function generateTypes($views, $savedData)
    {
        $result = '';
        foreach ($views as $view) {
            switch ($view->type) {
                case 'text':
                    $value = $this->getTextValue($savedData, $view->name);
                    $result .= $this->text($view->label, $view->name, $view->placeholder, $value);
                    break;

                case 'password':
                    $value = $this->getTextValue($savedData, $view->name);
                    $result .= $this->password($view->label, $view->name, $view->placeholder, $value);
                    break;

                case 'dropdown':
                    $value = $this->getTextValue($savedData, $view->name);
                    $result .= $this->dropdown($view->label, $view->name, $view->options, $value);
                    break;

                case 'solidPayment':
                    $value = MyfcPreferenceData::myfcIsSolidPayment($savedData) ? $savedData[0]->amount : "";

                    $checked = false;

                    if (!empty($value)) {
                        $checked = true;
                    }

                    $result .= $this->solidPayment($view->label, $value, $checked);
                    break;

                case 'dynamicPayment':
                    $result .= $this->dynamicPayment($view->label, $view->fields, $savedData);
                    break;

                case 'media':
                    $result .= $this->media(
                        $view->label,
                        $view->dataSrc,
                        $this->getTextValue($savedData, $view->name),
                        $view->type,
                        $view->name
                    );
                    break;
            }
        }

        return $result;
    }

    private function getTextValue($savedData, $viewName)
    {
        return $savedData->{$viewName};
    }


    public function generate($data, $tabContentSavedItemData)
    {
        $active = $data->name == 'Grundeinstellungen' ? 'active' : '';

        $result = "<div class=\"ui tab {$active} segment\" data-tab=\"{$data->name}\">";
        $result .= $this->generateTypes($data->views, $tabContentSavedItemData->config);
        $result .= "</div>";

        return $result;
    }
}
