<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 27.04.15
 * Time: 20:54
 */

namespace OCA\Dashboard\Services;

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

    public function getInstance($wIId, $type='controller') {
        $parts          = explode('-', $wIId);
        $wId            = $parts[0];
        $instanceNumber = $parts[1];
        $class  = $this->getFullClassName($wIId, $type);
        if( class_exists($class) ) {
            if( $type === strtolower('controller') ) {
                $widgetControllerObject = new $class($instanceNumber, $this->widgetSettingsService, $this->user, $this->l10n);
                if( $widgetControllerObject ) {
                    return $widgetControllerObject;
                }
            } elseif ( $type === strtolower('template') ) {
                $widgetTemplateObject = new $class($this->l10n);
                if( $widgetTemplateObject ) {
                    return $widgetTemplateObject;
                }
            }
        }
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

    private function getFullClassName($wIId, $type) {
        $parts          = explode('-', $wIId);
        $wId            = $parts[0];
        return 'OCA\Dashboard\Widgets\\'.ucwords($wId).'\\'.ucwords($wId).ucwords($type);
    }

}
