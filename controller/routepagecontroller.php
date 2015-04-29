<?php
/**
 * ownCloud - dashboard
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Florian Steffens <webmaster@freans.de>
 * @copyright Florian Steffens 2014
 */

namespace OCA\Dashboard\Controller;


use OCA\Dashboard\Db\WidgetConfigDAO;
use \OCP\IRequest;
use \OCP\AppFramework\Http\TemplateResponse;
use \OCP\AppFramework\Controller;
use \OCP\IL10N;

class RoutePageController extends Controller {

    private $user;
    private $loadedScripts  = array();
    private $loadedStyles   = array();
    private $l10n;

    public function __construct($appName, IRequest $request, $user, IL10N $l10n){
        parent::__construct($appName, $request);
        $this->user             = $user;
        $this->l10n             = $l10n;
    }

    /**
     *
     * main index
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function index() {
        $params = array(
            'user'              => $this->user,
        );
        return new TemplateResponse('dashboard', 'main', $params);
    }



    /**
     *
     * main index
     *
     * CAUTION: the @Stuff turn off security checks, for this page no admin is
     *          required and no CSRF check. If you don't know what CSRF is, read
     *          it up in the docs or you might create a security hole. This is
     *          basically the only required method to add this exemption, don't
     *          add it to any other method if you don't exactly know what it does
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function x_index() {
        $enabledWidgets     = $this->getEnabledWidgets();
        $this->loadWidgetJs($enabledWidgets);
        $this->loadWidgetCss($enabledWidgets);
        $params = array(
            'user'              => $this->user,
            'enabledWidgets'    => $enabledWidgets,
        );
        return new TemplateResponse('dashboard', 'main', $params);  // templates/main.php
    }



    // private services ----------------------------------------------------------------------

    /**
     *
     * return the enabled widgets as array (wIIds)
     *
     * @return array
     */
    private function x_getEnabledWidgets() {
        $enabledWidgets = $this->widgetConfigDAO->findEnabledWidgets($this->user);

        $widgets = array();
        foreach ($enabledWidgets as $wIId) {
            $split = explode('-', $wIId);
            $wId   = $split[0];
            $wNo   = $split[1];
            $order = $this->widgetConfigDAO->getConfig($wId, $wNo, $this->user, 'order');
            $order = ($order!=null) ? $order: 10;
            $widgets[] = array(
                'order'     => $order,
                'wIId'       => $wIId
            );
        }

        usort($widgets, array($this, 'sortWidgetOrder'));

        $return = array();
        foreach ($widgets as $wIId) {
            $return[] = $wIId['wIId'];
        }
        return $return;
    }

    /**
     *
     * order items by its array value for key 'order'
     *
     * @param $a
     * @param $b
     * @return int
     */
    private function x_sortWidgetOrder($a, $b) {
        if ($a['order'] == $b['order']) {
            return 0;
        }
        return ($a['order'] < $b['order']) ? -1 : 1;
    }

    /**
     *
     * load all javascript files from all enabled widgets
     *
     */
    private function x_loadWidgetJs() {
        $widgets = $this->widgetController->getAvailable();

        foreach ($widgets as $widget) {
            $wId = $widget['wId'];

            // is there a better solution?
            $scriptPath = $this->getWidgetJsPath($wId);

            // load only once
            if( !in_array($scriptPath, $this->loadedScripts) ) {
                \OC_Util::$scripts[]    = $scriptPath;
                $this->loadedScripts[]  = $scriptPath;
            }
        }
    }

    /**
     *
     * load all css files from all enabled widgets
     *
     */
    private function x_loadWidgetCss() {
        $widgets = $this->widgetController->getAvailable();

        foreach ($widgets as $widget) {
            $wId = $widget['wId'];

            // is there a better solution?
            $stylePath = $this->getWidgetCssPath($wId);

            // load only once
            if( !in_array($stylePath, $this->loadedStyles) ) {
                \OC_Util::$styles[]     = $stylePath;
                $this->loadedStyles[]   = $stylePath;
            }
        }
    }

    /**
     *
     * calculate the path to the js script from an app
     *
     * @param $wId
     * @return string
     */
    private function x_getWidgetJsPath($wId) {
        return 'dashboard/widgets/'.$wId.'/script';
    }

    /**
     *
     * calculate the path to the css files from an app
     *
     * @param $wId
     * @return string
     */
    private function x_getWidgetCssPath($wId) {
        return 'dashboard/widgets/'.$wId.'/style';
    }

}