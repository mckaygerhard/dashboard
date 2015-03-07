<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 14.12.14
 * Time: 20:48
 */

namespace OCA\Dashboard\Controller;


use OCA\Dashboard\Db\WidgetConfigDAO;
use OCA\Dashboard\Db\WidgetHashDAO;
use OCA\Dashboard\Widgets\IWidgetController;
use OCA\Dashboard\Widgets\IWidgetTemplate;
use OCP\AppFramework\Controller;
use OCP\IContainer;
use OCP\IL10N;
use OCP\IRequest;
use OCP\Util;

class WidgetController extends Controller {

    private $user;
    private $widgetControllerObjects    = array();
    private $widgetTemplateObjects      = array();
    private $widgetConfigDAO;
    private $widgetHashDAO;

    protected $l10n;

    public function __construct($appName, IRequest $request, $user, WidgetConfigDAO $widgetConfigDAO, WidgetHashDAO $widgetHashDAO, IL10N $l10n){
        parent::__construct($appName, $request);
        $this->user             = $user;
        $this->widgetConfigDAO  = $widgetConfigDAO;
        $this->widgetHashDAO    = $widgetHashDAO;
        $this->l10n             = $l10n;
    }


    // ROUTES ------------------------------------------------------

    /**
     *
     * get all contents
     *  - header
     *  - content html
     *  - settings
     *  - status
     *  - dimension
     *
     * @NoAdminRequired
     * @param $wIId
     * @return array
     */
    function getComplete($wIId) {
        $split  = explode('-', $wIId);
        $wId    = $split[0];

        $widgetController   = $this->getWidgetControllerObject($wIId);
        $widgetTemplate     = $this->getWidgetTemplateObject($wIId);

        $widgetHtml         = $widgetTemplate->getHtml($widgetController);
        $status             = $widgetController->getStatus();
        $dimension          = $widgetController->getConfig('dimension', '1x1');
        $refresh            = $widgetController->getConfig('refresh');
        return array(
            'widgetHtml'    => $widgetHtml,
            'status'        => $status,
            'dimension'     => $dimension,
            'refresh'       => $refresh,
            'wId'           => $wId
        );
    }

    /**
     *
     * get the content in html
     * for only the content part of a widget
     *
     * @NoAdminRequired
     * @param $wIId
     * @return array
     */
    function getContent($wIId) {
        $widgetController   = $this->getWidgetControllerObject($wIId);
        $widgetTemplate     = $this->getWidgetTemplateObject($wIId);

        $widgetHtml         = $widgetTemplate->getContentHtml($widgetController->getData());
        $status             = $widgetController->getStatus();
        $dimension          = $widgetController->getConfig('dimension', '1x1');
        return array(
            'widgetHtml'    => $widgetHtml,
            'status'        => $status,
            'dimension'     => $dimension
        );
    }

    /**
     *
     * call a method from the widget controller object by ajax
     * called by route from DI
     *
     * @NoAdminRequired
     * @param $wIId
     * @param $method
     * @param $value
     * @return array|null
     */
    function callMethod ($wIId, $method, $value) {
        $widgetController   = $this->getWidgetControllerObject($wIId);

        // call method if is set
        if ($method != null && $method != '' && method_exists($widgetController, $method)) {
            return array( 'success' => $widgetController->$method($value) );
        }
        return null;
    }

    /**
     *
     * set config for a wId
     * this is just a route to the widget controller object
     * called by a route from DI
     *
     * @NoAdminRequired
     * @param $wIId
     * @param $key
     * @param $value
     */
    function setConfig($wIId, $key, $value) {
        $widgetController = $this->getWidgetControllerObject($wIId);
        $widgetController->setConfig($key, $value);
    }

    /**
     *
     * return all available widgets with icon-path and name
     * filtered by activated widgets (by admin)
     *
     * @NoAdminRequired
     * @return array
     */
    function getAvailable() {
        $widgets = array();
        $dir = str_replace('controller/widgetcontroller.php', '', __FILE__).'widgets/';
        $directories    = $this->dirToArray($dir);
        foreach ($directories as $key => $dir) {
            $widgetObject   = $this->getWidgetControllerObject($key.'-0');
            $widgets[] = array(
                'wId'   => $widgetObject->getConfig('wId'),
                'icon'  => Util::imagePath('dashboard', $widgetObject->getConfig('icon')),
                'name'  => $widgetObject->getConfig('wName')
            );
        }
        return $widgets;
    }

    /**
     *
     * register new widget in DB
     * and return wIId
     *
     * @NoAdminRequired
     * @param $wId
     * @return array
     */
    function addNew($wId) {
        $highestNo  = $this->widgetConfigDAO->getHighestNo($wId, $this->user);
        $wNo        = intval($highestNo) + 1;
        $wIId       = $wId.'-'.$wNo;
        $this->widgetConfigDAO->insertOrUpdateConfig($wId, $wNo, $this->user, 'enabled', '1');
        return array( 'wIId' => $wIId);
    }

    /**
     *
     * remove all items from DB
     *
     * @NoAdminRequired
     * @param $wIId
     * @return array
     */
    function remove($wIId) {
        $split = explode('-', $wIId);
        $wId = $split[0];
        $wNo = $split[1];
        $this->widgetConfigDAO->removeWidgetConfigs($wId, $wNo, $this->user);
        $this->widgetHashDAO->removeWidgetHashes($wIId, $this->user);
        return array();
    }



    // PRIVATE SERVICES ------------------------------------------------------------

    /**
     *
     * is only called once to create the wId controller object
     *
     * @param $wIId
     */
    private function createWidgetControllerObject ($wIId) {
        $split = explode('-', $wIId);
        $wId = $split[0];
        $wNo = $split[1];

        $controllerClass = 'OCA\Dashboard\Widgets\\' . ucwords($wId) . '\\' . ucwords($wId) . 'Controller';

        if ( class_exists($controllerClass) ) {
            if( !$this->widgetControllerObjects[$wIId] = new $controllerClass($wNo, $this->widgetConfigDAO, $this->widgetHashDAO, $this->user, $this->l10n) ) {
                Util::writeLog('dashboard', 'Could not create widget controller object (wIId = '.$wIId.')',1);
            }
        }
    }

    /**
     *
     * call this to get a instance of the wId controller object
     *
     * @param $wIId
     * @return IWidgetController
     */
    private function getWidgetControllerObject($wIId) {
        if( !isset($this->widgetControllerObjects[$wIId]) ) {
            $this->createWidgetControllerObject($wIId);
        }
        return $this->widgetControllerObjects[$wIId];
    }

    /**
     *
     * is only called once to create the wId controller object
     *
     * @param $wIId
     */
    private function createWidgetTemplateObject ($wIId) {
        $split = explode('-', $wIId);
        $wId   = $split[0];
        $templateClass = 'OCA\Dashboard\Widgets\\' . ucwords($wId) . '\\' . ucwords($wId) . 'Template';

        if ( class_exists($templateClass) ) {
            if( !$this->widgetTemplateObjects[$wIId] = new $templateClass($wIId, $this->widgetConfigDAO, $this->l10n) ) {
                Util::writeLog('dashboard', 'Could not create widget template object (wIId = '.$wIId.')',1);
            }
        }
    }

    /**
     *
     * call this to get a instance of the wId controller object
     *
     * @param $wIId
     * @return IWidgetTemplate
     */
    private function getWidgetTemplateObject($wIId) {
        if( !isset($this->widgetTemplateObjects[$wIId]) ) {
            $this->createWidgetTemplateObject($wIId);
        }
        return $this->widgetTemplateObjects[$wIId];
    }

    private function dirToArray($dir) {
        $result = array();
        $cDir = scandir($dir);
        foreach ($cDir as $key => $value)
        {
            if (!in_array($value,array(".","..")) && is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = $dir . DIRECTORY_SEPARATOR . $value;
            }
        }
        return $result;
    }

} 