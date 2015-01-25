<?php

/*
 * shows bitcoin price from Bitstamp
 * copyright 2014
 *
 * non-commercial only
 * moreinfos:
 * 	bitstamp.net
 *
 * @version 0.1
 * @date 11-20-2014
 * @author José Ignacio Amelivia(www.namelivia.com)
 */
class bitcoin extends ocdWidget implements interfaceWidget {
	private $json;
	private $url = "https://www.bitstamp.net/api/ticker/";
	
	// ======== INTERFACE METHODS ================================
	
	/*
	 * @return Array of all data for output
	 * this array will be routed to the subtemplate for this widget 
	 */
	public function getWidgetData() {
		if($this->getJSON()) {
			$return = Array(
					"last" => $this->json->last,
					"low" => $this->json->low,
					"high" => $this->json->high
					);
			return $return;
		} else {
			$this->status = 3;
			return null;
		}
	}
	
	// ======== END INTERFACE METHODS =============================
	
	/*
	 * loads the json data from Bitstamp
	 */
	private function getJSON() {
		$con = @file_get_contents($this->url);
		if($con != "") {
			$this->json = json_decode($con);
			return true;
		} else {
			OCP\Util::writeLog('ocDashboard',"Bitcoin price could not be loaded: ".$url, \OCP\Util::WARN);
			$this->errorMsg = "The API response is wrong.";
			return false;
		}
	}	
}
