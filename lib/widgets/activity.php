<?php
/*
 * displays activitys from owncloud activity app by ownCloud
 * copyright 2013
 *
 * @version 0.1
 * @date 18-03-2014
 * @author Florian Steffens (flost@live.no)
 */
class activity extends widget implements interfaceWidget {

	// ======== INTERFACE METHODS ================================
	
	/*
	 * @return Array of all data for output
	 * this array will be routed to the subtemplate for this widget 
	 */
	public function getWidgetData() {
        $test = OCA\Activity\OCS::getActivities();
        $test = \OC_Util::sanitizeHTML($test->getData()[0]['subject']);
		return Array("activitys" => Array($test));
	}	
	
	// ======== END INTERFACE METHODS =============================

	
	/*
	 * called by ajaxService
	 * 
	 * @param $id
	 * @return if success
	 */
	public function dummy($id) {
	}

}