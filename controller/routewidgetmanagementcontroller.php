<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 27.04.15
 * Time: 20:54
 */

namespace OCA\Dashboard\Controller;

use OCA\Dashboard\Services\WidgetManagementService;
use OCP\AppFramework\Controller;
use OCP\IL10N;
use OCP\IRequest;
use OCP\Util;

class RouteWidgetManagementController extends Controller {

    private $widgetManagementService;
    private $L10N;

    public function __construct($appName, IRequest $request, $user, WidgetManagementService $widgetManagementService, IL10N $l10n){
    parent::__construct($appName, $request);
        $this->user                     = $user;
        $this->widgetManagementService  = $widgetManagementService;
        $this->L10N                     = $l10n;
    }

    /**
    *
    * return a array of widgets for this user
    *
    * @NoAdminRequired
    * @return array
    */
    public function getEnabledWidgets() {
        // TODO
        return array('wIIds' => array('dummy-0', 'dummy-1'));
    }

    /**
     *
     * return a array of widgets that are available
     *
     * @NoAdminRequired
     * @return array
     */
    public function getAvailableWidgets() {
        // TODO
        return array('dummy');
    }

    /**
     *
     * returns the basic conf as array
     * includes wId, name, refresh, icon
     *
     * @param $wId
     * @return array
     */
    public function getBasicConf( $wId ) {
        /** @var $controllerClass \OCA\Dashboard\Widgets\IWidgetController */
        $controllerClass    = $this->widgetManagementService->getFullClassName($wId.'-', 'controller');
        $return = $controllerClass::getBasicConf($this->L10N);

        return $return;
    }

    public function enable($wId) {
    // TODO
    return true;
    }

    public function disable($wId) {
    // TODO
    return true;
    }

    public function addNewInstance($wId) {
    // TODO
    return 'dummy-0';
    }

    public function removeInstance($wIId) {
    // TODO
    return true;
    }

}
