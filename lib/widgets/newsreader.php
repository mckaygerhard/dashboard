<?php

/*
 * displays new from newsreader by ownCLoud
 * copyright 2013
 *
 * @version 0.2
 * @date 01-08-2013
 * @author Florian Steffens (flost@live.no)
 */

class newsreader extends widget implements interfaceWidget {

    private $itembusinesslayer;

// ======== INTERFACE METHODS ================================
	
	/*
	 * @return Array of all data for output
	 * this array will be routed to the subtemplate for this widget 
	 */
	public function getWidgetData() {
		return $this->getNews();
	}	
	
// ======== END INTERFACE METHODS =============================
	
	
	/*
	 * this is called by the ajaxService from frontend
	 * has to be public!
	 * 
	 * @param $data dummy
	 * @return true if mark success 
	 */
	public function markAsRead($data) {
        if(!$this->itembusinesslayer) {
            $this->getNewsapi();
        }

        $id = OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_newsreader_lastItemId");
        $this->itembusinesslayer->read($id, true, $this->user);

		return true;
	}


	/*
	 * get the next newsitem from the news app
	 *
	 * @return array
	 */
    public function getNews() {
        if(!$this->itembusinesslayer) {
            $this->getNewsapi();
        }

        $lastId = OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_newsreader_lastItemId",0);

        $items = $this->itembusinesslayer->findAllNew(0, \OCA\News\Db\FeedType::SUBSCRIPTIONS , 0, false,  $this->user);
        $items = array_reverse($items);

        $newsitemfound = false;
        $itemcount = 0;
        foreach($items as $item) {
            $itemdata = $item->toAPI();
            $itemcount++;

            // if the last newsitem was the las showen item => this is the next
            if($newsitemfound) {
                OCP\Config::setUserValue($this->user, "ocDashboard", "ocDashboard_newsreader_lastItemId", $itemdata['id']);
                $itemdata["count"] = count($items);
                $itemdata["actual"] = $itemcount;
                return $itemdata;
            }

            // if newsitem is the last one
            if($itemdata['id'] == $lastId) {
                $newsitemfound = true;
            }
        }

        if(reset($items)) {
            $itemdata = reset($items)->toAPI();
            OCP\Config::setUserValue($this->user, "ocDashboard", "ocDashboard_newsreader_lastItemId", $itemdata['id']);
            $itemdata["count"] = count($items);
            $itemdata["actual"] = 1;
            return $itemdata;
        } else {
            return null;
        }
    }

    private function getNewsapi() {
        $api = new \OCA\AppFramework\Core\API("News");
        $mapper = new \OCA\News\Db\ItemMapper($api);
        $statusflag = new \OCA\News\Db\StatusFlag();
        $timefactory = new \OCA\AppFramework\Utility\TimeFactory();
        $logger = new OCA\News\Core\Logger("news");
        $config = new \OCA\News\Utility\Config(null, $logger);
        $this->itembusinesslayer = new \OCA\News\BusinessLayer\ItemBusinessLayer($mapper, $statusflag, $timefactory, $config);
    }
			
}