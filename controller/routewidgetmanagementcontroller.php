<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 27.04.15
 * Time: 20:54
 */

namespace OCA\Dashboard\Controller;

use OCP\AppFramework\Controller;
use OCP\IRequest;

class RouteWidgetManagementController extends Controller {

  public function __construct($appName, IRequest $request, $user){
    parent::__construct($appName, $request);
    $this->user = $user;
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
        return array('wIIds' => array('dummy-0'));
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
        return array('wIds' => array('dummy'));
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
