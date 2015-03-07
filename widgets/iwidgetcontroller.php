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
     * you have to implement this method!
     * always set:
     *  - icon      (icon for the widget)
     *  - refresh   (refresh interval; 0=none)
     *  - name      (name (not id) of the widget)
     *
     * this method will be automatically called from the constructor
     *
     */
    public function setData();

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
    function getStatus();

    /**
     *
     * you can set config values FOR THIS WIDGET-INSTANCE AND USER
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    function setConfig($key, $value);

    function getConfig($key);

} 