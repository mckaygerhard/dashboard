<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 27.04.15
 * Time: 20:54
 */

namespace OCA\Dashboard\Service;

use OCP\IL10N;

class WidgetManagementService {
  
    private $widgetSettingsService;
    private $l10n;
    private $user;
  
    public function __construct($user, IL10N $l10n, WidgetSettingsService $widgetSettingsService){
        $this->user = $user;
        $this->l10n = $l10n;
        $this->widgetSettingsService = $widgetSettingsService;
    }

    public function getEnabled() {
        // TODO
        return array('dummy');
    }

    public function getAvailable() {
        // TODO
        return array('dummy');
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

    public function getNumberOfInstances($wId) {
        // TODO
        return 5;
    }


    // PRIVATE METHODS -------------------------------------


    private function refreshAvailable() {
        // TODO
        return true;
    }

    private function getNextInstanceNumber($wId) {
        // TODO
        return 6;
    }
  
}
