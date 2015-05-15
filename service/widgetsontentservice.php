<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 27.04.15
 * Time: 20:29
 */

namespace OCA\Dashboard\Services;

use OCA\Dashboard\Service\WidgetManagementService;
use OCP\IL10N;


class WidgetContentService {

    private $user;
    private $l10n;
    private $widgetManagementService;
    private $widgetHashService;

    public function __construct($user, IL10N $l10n, WidgetManagementService $widgetManagementService, WidgetHashService $widgetHashService)
    {
        $this->user                     = $user;
        $this->l10n                     = $l10n;
        $this->widgetManagementService  = $widgetManagementService;
        $this->widgetHashService        = $widgetHashService;
    }

    /**
     *
     * returns the complete html code for the wIId
     *
     * @param String $wIId
     * @return String html
     */
    public function getComplete($wIId)
    {
        // TODO
        return 'ToDo';
    }

    /**
     *
     * return the html for the content part of the wIId
     *
     * @param String $wIId
     * @return string html
     */
    public function getContent($wIId)
    {
        // TODO
        return 'ToDo';
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
