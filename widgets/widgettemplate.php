<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 16.12.14
 * Time: 16:25
 */

namespace OCA\Dashboard\Widgets;


use OC_Util;
use OCA\Dashboard\Db\WidgetConfigDAO;
use OCP\IL10N;
use OCP\Util;

abstract class WidgetTemplate {

    protected $wIId;
    protected $widgetConfigDAO;
    protected $l10n;

    function __construct($wIId, WidgetConfigDAO $widgetConfigDAO, IL10N $l10n) {
        $this->wIId             = $wIId;
        $this->widgetConfigDAO  = $widgetConfigDAO;
        $this->l10n             = $l10n;
    }

    abstract function getSettingsArray();

    abstract function getContentHtml();


    /**
     *
     * return the complete html for a widget
     * included
     *  - header
     *  - content
     *  - settings
     *
     * @param IWidgetController $widgetController
     * @return string
     */
    public function getHtml(IWidgetController $widgetController) {
        $html = '';
        $html .= '<div class="heading">';
        $html .= $this->getHeadHtml($widgetController);
        $html .= '</div>';
        $html .= '<div class="content">';
        $html .= $this->getContentHtml($widgetController->getData());
        $html .= '</div>';
        $html .= '<div class="settings">';
        $html .= $this->getSettingsHtml($widgetController);
        $html .= '</div>';
        return $html;
    }

    /**
     *
     * all output strings and values should be passed by this
     * to avoid XSS and other security things
     *
     * @param $string
     * @return array|string
     */
    protected function p( $string ) {
        return OC_Util::sanitizeHTML($string);
    }

    // private services ---------------------------------------------------------------

    /**
     *
     * return the settings html
     *
     * @param IWidgetController $widgetController
     * @return string
     */
    private function getSettingsHtml(IWidgetController $widgetController) {
        // this settings are available for every widget instance
        $defaultSettings        = $this->getDefaultSettings();

        // settings specially for this widget type
        $specialSettingsArray   = $this->getSettingsArray();

        // all settings combined
        $settingsArray          = array_merge($defaultSettings, $specialSettingsArray);

        $return = '<table>';
        foreach( $settingsArray as $key => $setting) {
            $return .= '<tr><td>'.$this->l10n->t($setting['name']).'</td><td>'.$this->getSettingsField($setting, $key, $widgetController).'</td></tr>';
            if( isset($setting['info']) ) {
                $return .= '<tr><td colspan="2"><div class="settingsInfo">'.$this->l10n->t($setting['info']).'</div></td></tr>';
            }
        }
        $return .= '<tr><td>'.$this->l10n->t('Remove widget').'</td><td><input class="removeWidget" data-wiid="'.$this->wIId.'" type="button" value="'.$this->l10n->t('remove now').'" /></td></tr>';
        if( method_exists($this, 'getLicenseInfo') ) {
            $return .= '<tr><td class="key">'.$this->l10n->t('License').'</td><td><div class="value">'.$this->getLicenseInfo().'</div></td></tr>';
        }
        $return .= '</table>';
        return $return;
    }

    /**
     *
     * return the html for one field
     * depending on its type
     *
     * @param $setting
     * @param $key
     * @param IWidgetController $widgetController
     * @return string
     */
    private function getSettingsField($setting, $key, IWidgetController $widgetController) {
        $type   = (isset($setting['type'])) ? $setting['type']: '';
        $value  = $this->getValueForField($key, $setting['default'], $widgetController);
        $wIId    = $widgetController->getConfig('wIId');
        $html   = '&nbsp;';
        switch($type) {
            case 'select':
                $html .= '<select name="'.$key.'" data-wiid="'.$wIId.'" class="setting">';
                if(is_array($setting['options'])) {
                    foreach( $setting['options'] as $key => $option) {
                        $html .= '<option value="'.$key.'"';
                        if( $value == $key ) {
                            $html .= ' selected ';
                        }
                        $html .= '>'.$this->l10n->t($option).'</option>';
                    }
                }
                $html .= '</select>';
                break;
            case 'text':
                $html .= '<input type="text" name="'.$key.'" data-wiid="'.$wIId.'" class="setting" value="'.$this->l10n->t($value).'" />';
                break;
            case 'password':
                $html .= '<input type="password" name="'.$key.'" data-wiid="'.$wIId.'" class="setting" />';
                break;
            default:
                $html .= 'error';
        }
        return $html;
    }

    /**
     *
     * if a value is set in the db, get and return it
     * else return default
     *
     * @param $key
     * @param $default
     * @param IWidgetController $widgetController
     * @return int|null|string
     */
    private function getValueForField($key, $default, IWidgetController $widgetController) {
        return $widgetController->getConfig($key, $default);
    }

    /**
     *
     * return the default array entries for the widget settings
     *
     * @return array
     */
    private function getDefaultSettings() {
        return array(
            'dimension'     => array(
                'type'          => 'select',
                'options'       => array(
                    '1x1'           => '1 x 1',
                    '1x2'           => '1 x 2',
                    '1x3'           => '1 x 3',
                    '2x1'           => '2 x 1',
                    '2x2'           => '2 x 2',
                    '2x3'           => '2 x 3',
                    '3x1'           => '3 x 1',
                    '3x2'           => '3 x 2',
                    '3x3'           => '3 x 3'
                ),
                'name'          => 'Dimension',
                'default'       => '1x1'
            ),
            'order'         => array(
                'type'          => 'text',
                'name'          => 'Order',
                'default'       => '10',
                'info'          => 'Refresh to apply changes.'
            )
        );
    }

    /**
     *
     * return the html of the head
     * included
     *  - name (title)
     *  - reload icon
     *  - settings icon
     *  - icon
     *
     * @param IWidgetController $widgetController
     * @return string
     */
    private function getHeadHtml(IWidgetController $widgetController) {
        $refresh    = $widgetController->getConfig('refresh');
        $icon       = $widgetController->getConfig('icon');
        $name       = $widgetController->getConfig('wName');
        if( method_exists($widgetController, 'getName')) {
            $name     = $widgetController->getName();
        }
        $html       = '<h1 class="hoverInfo" data-opacitynormal="0.5">';

        $link = $widgetController->getConfig('link', '');
        if( $link != null && $link != '' ) {
            $html .= '<a href="'.$link.'">'.$this->l10n->t($name).'</a>';
        } else {
            $html .= $this->l10n->t($name);
        }

        if( $refresh && $refresh != 0 ) {
            $html .= '<span class="hoverInfo icon-play iconReload" data-wiid="'.$this->wIId.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
        }

        $html .= '<span class="hoverInfo icon-settings iconSettings">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
        $html .= '</h1>';

        if( $icon != '' ) {
            $html .= '<div class="icon"><img src="'.Util::imagePath('dashboard', $icon).'" alt="'.$this->wIId.' icon" /></div>';
        }

        return $html;
    }

}