<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 27.04.15
 * Time: 20:29
 */

namespace OCA\Dashboard\Services;

use OCP\IL10N;


class WidgetContentService {

    private $user;
    private $l10n;
    private $widgetManagementService;
    private $widgetHashService;

    public function __construct($user, IL10N $l10n, WidgetManagementService $widgetManagementService)
    {
        $this->user                     = $user;
        $this->l10n                     = $l10n;
        $this->widgetManagementService  = $widgetManagementService;
        //$this->widgetHashService        = $widgetHashService;
    }

    /**
     *
     * returns the complete html code for the wIId
     *
     * @param String $wIId
     * @return String html
     */
    public function getComplete($wIId) {
        /** @var $widgetController \OCA\Dashboard\Widgets\IWidgetController */
        $widgetController   = $this->widgetManagementService->getInstance($wIId, 'controller');

        /** @var $widgetTemplate  \OCA\Dashboard\Widgets\IWidgetTemplate */
        $widgetTemplate     = $this->widgetManagementService->getInstance($wIId, 'template');

        $contentData        = $widgetController->getData();
        $basicValues        = $widgetController->getBasicValues();
        $data               = array_merge($contentData, $basicValues);
        $data['status']     = (isset($data['status'])) ? $data['status']: 1;
        $data['widgetHtml'] = $widgetTemplate->getCompleteHtml( $data );
        return $data;
    }

    /**
     *
     * return the html for the content part of the wIId
     *
     * @param String $wIId
     * @return string html
     */
    public function getContent($wIId) {
        /** @var $widgetController \OCA\Dashboard\Widgets\IWidgetController */
        $widgetController   = $this->widgetManagementService->getInstance($wIId, 'controller');

        /** @var $widgetTemplate  \OCA\Dashboard\Widgets\IWidgetTemplate */
        $widgetTemplate     = $this->widgetManagementService->getInstance($wIId, 'template');

        $contentData        = $widgetController->getData();
        $basicValues        = $widgetController->getBasicValues();
        $data               = array_merge($contentData, $basicValues);
        $data['status']     = (isset($data['status'])) ? $data['status']: 1;
        $data['widgetHtml'] = $widgetTemplate->getContentHtml( $data );
        return $data;
    }

    /**
     *
     * call a widget-method
     * you can define a key and value as strings
     *
     * @param $wIId
     * @param String $key
     * @param String $value
     * @return bool if execution success
     */
    public function callMethod($wIId, $key, $value)
    {
        // TODO
        return (true);
    }


}
