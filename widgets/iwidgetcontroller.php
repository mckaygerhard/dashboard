<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 16.12.14
 * Time: 16:27
 */

namespace OCA\Dashboard\Widgets;


interface IWidgetController {

    /**
     *
     * always set:
     *  - icon      (icon for the widget)
     *  - refresh   (refresh interval; 0=none)
     *  - name      (name (not id) of the widget)
     *
     */
    public function getBasicConfig();

    /**
     *
     * all data from this widget for the template
     *
     * @return mixed
     */
    public function getData();

    /**
     *
     * tells you the status of this widget-instance
     *
     * @return mixed
     */
    public function getStatus();

    /**
     *
     * you can set config values
     * FOR THIS WIDGET-INSTANCE AND USER
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setConfig($key, $value);

    /**
     *
     * get config from db
     * FOR THIS WIDGET-INSTANCE AND USER
     *
     * @param $key
     * @return mixed
     */
    public function getConfig($key);

} 