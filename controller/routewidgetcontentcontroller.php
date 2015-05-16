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
use OCA\Dashboard\Services\WidgetContentService;
use OCA\Dashboard\Widgets\IWidgetController;
use OCA\Dashboard\Widgets\IWidgetTemplate;
use OCP\AppFramework\Controller;
use OCP\IL10N;
use OCP\IRequest;
use OCP\Util;

class RouteWidgetContentController extends Controller {

    private $user;
    private $l10n;
    private $widgetContentService;


    public function __construct($appName, IRequest $request, $user)
    {
        parent::__construct($appName, $request);
        $this->user = $user;
        //$this->l10n = $l10n;
    }

    /**
     *
     * returns the complete html code for the wIId
     *
     * @param String $wIId
     * @NoCSRFRequired
     * @return String html
     */
    public function getComplete($wIId)
    {
        // TODO
        return array(
            'wId'       => 'dummy',
            'wIId'      => 'dummy-0',
            'status'    => 1,
            'dimension' => '1x1',
            'refresh'   => '50',
            'widgetHtml'=> '<div class="content">test html (wIId: '.$wIId.')</div>'
        );
    }

    /**
     *
     * return the html for the content part of the wIId
     *
     * @NoCSRFRequired
     * @param String $wIId
     * @return string html
     */
    public function getContent($wIId)
    {
        // TODO
        return array(
            'wIId'      => 'dummy-0',
            'status'    => 2,
            'widgetHtml'=> '<div class="content">'.time().' (wIId: '.$wIId.')</div>'
        );
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
    function x_getComplete($wIId) {
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
    function x_getContent($wIId) {
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
    function x_callMethod ($wIId, $method, $value) {
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
    function x_setConfig($wIId, $key, $value) {
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
    function x_getAvailable() {
        $widgets = array();
        $dir = str_replace('controller'.DIRECTORY_SEPARATOR.'widgetcontroller.php', '', __FILE__).'widgets'.DIRECTORY_SEPARATOR;
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
    function x_addNew($wId) {
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
    function x_remove($wIId) {
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
    private function x_createWidgetControllerObject ($wIId) {
        $split = explode('-', $wIId);
        $wId = $split[0];
        $wNo = $split[1];

        $controllerClass = 'OCA\Dashboard\Widgets\\' . ucwords($wId) . '\\' . ucwords($wId) . 'Controller';

        if ( class_exists($controllerClass) ) {
            $this->widgetControllerObjects[$wIId] = new $controllerClass($wNo, $this->widgetConfigDAO, $this->widgetHashDAO, $this->user, $this->l10n);
            if( !$this->widgetControllerObjects[$wIId] ) {
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
    private function x_getWidgetControllerObject($wIId) {
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
    private function x_createWidgetTemplateObject ($wIId) {
        $split = explode('-', $wIId);
        $wId   = $split[0];
        $templateClass = 'OCA\Dashboard\Widgets\\' . ucwords($wId) . '\\' . ucwords($wId) . 'Template';

        if ( class_exists($templateClass) ) {
            $this->widgetTemplateObjects[$wIId] = new $templateClass($wIId, $this->widgetConfigDAO, $this->l10n);
            if( !$this->widgetTemplateObjects[$wIId] ) {
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
    private function x_getWidgetTemplateObject($wIId) {
        if( !isset($this->widgetTemplateObjects[$wIId]) ) {
            $this->createWidgetTemplateObject($wIId);
        }
        return $this->widgetTemplateObjects[$wIId];
    }

    private function x_dirToArray($dir) {
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