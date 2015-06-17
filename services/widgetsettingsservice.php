<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 27.04.15
 * Time: 20:28
 */

namespace OCA\Dashboard\Services;


use OCA\Dashboard\Db\WidgetConfigDAO;

class WidgetSettingsService {

    private $widgetConfigDAO;

    // this key will be stored encrypted in the db
    protected $encryptAttributes = array(
        'password'
    );

    function __construct(WidgetConfigDAO $widgetConfigDAO) {
        $this->widgetConfigDAO  = $widgetConfigDAO;
    }

    /**
     *
     * set config to db
     * return true if successful
     *
     * @param $wIId
     * @param $key
     * @param $value
     * @return bool
     */
    public function setConfig($wIId, $key, $value) {
        // TODO
        return true;
    }

    /**
     *
     * get config from db
     * return null, if none exists
     *
     * @param $wIId
     * @param $key
     * @return null
     */
    public function getConfig($wIId, $key) {
        // TODO
        return null;
    }
}
