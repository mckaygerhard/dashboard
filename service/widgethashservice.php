<?php
/**
* Created by PhpStorm.
* User: flost
* Date: 27.04.15
* Time: 20:29
*/

namespace OCA\Dashboard\Services;

use OCA\Dashboard\Db\WidgetHashDAO;

class WidgetHashService {

    private $widgetHashDAO;

    function __construct(WidgetHashDAO $widgetHashDAO) {
        $this->widgetHashDAO    = $widgetHashDAO;
    }

    /**
     *
     * write a new hash to the db
     * if hash exists, timestamp will be updated
     *
     * @param $wIId
     * @param $data
     * @return bool
     */
    public function registerDataset($wIId, $data) {
        // TODO
        return true;
    }

    /**
     *
     * remove all hashes that are older than 24 hours
     *
     * @return bool
     */
    public function removeOldHashes() {
        // TODO
        return true;
    }
}
