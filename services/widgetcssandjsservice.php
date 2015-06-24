<?php
/**
* Created by PhpStorm.
* User: flost
* Date: 27.04.15
* Time: 20:29
*/

namespace OCA\Dashboard\Services;


use OC_Util;

class WidgetCssAndJsService {

    private $widgetManagementService;

    private $loadedStyles = array();
    private $loadedScripts= array();

    function __construct(WidgetManagementService $widgetManagementService) {
        $this->widgetManagementService = $widgetManagementService;
    }

    public function loadAll() {
        /** @var $wIdsToLoadCss \OCA\Dashboard\Services\WidgetManagementService */
        $wIdsToLoad = $this->widgetManagementService->getAvailable();

        foreach ($wIdsToLoad as $wId) {
            $this->loadWidgetJs($wId);
            $this->loadWidgetCss($wId);
        }
    }

    public function loadWidgetCss($wId) {
        $stylePath = $this->getWidgetCssPath($wId);

        // load only once
        if( !in_array($stylePath, $this->loadedStyles) ) {
            OC_Util::$styles[]      = $stylePath;
            $this->loadedStyles[]   = $stylePath;
        }
    }

    public function loadWidgetJs($wId) {
        $scriptPath = $this->getWidgetJsPath($wId);

        // load only once
        if( !in_array($scriptPath, $this->loadedScripts) ) {
            OC_Util::$scripts[]     = $scriptPath;
            $this->loadedStyles[]   = $scriptPath;
        }
    }


    // ---- private methods --------------------------------------

    /**
     *
     * calculate the path to the js script from an app
     *
     * @param $wId
     * @return string
     */
    private function getWidgetJsPath($wId) {
        return 'dashboard'.DIRECTORY_SEPARATOR.'widgets'.DIRECTORY_SEPARATOR.$wId.DIRECTORY_SEPARATOR.'script';
    }

    /**
     *
     * calculate the path to the css files from an app
     *
     * @param $wId
     * @return string
     */
    private function getWidgetCssPath($wId) {
        return 'dashboard'.DIRECTORY_SEPARATOR.'widgets'.DIRECTORY_SEPARATOR.$wId.DIRECTORY_SEPARATOR.'style';
    }

}
