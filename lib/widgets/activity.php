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

	private $tasks = Array();
	
	// ======== INTERFACE METHODS ================================
	
	/*
	 * @return Array of all data for output
	 * this array will be routed to the subtemplate for this widget 
	 */
	public function getWidgetData() {
		return "test";
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