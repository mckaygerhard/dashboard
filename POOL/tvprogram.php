<?php
/*
 * tvprogramm use rss feed
 * copyright 2013
 * 
 * 
 * @version 0.1
 * @date 01-08-2013
 * @author Florian Steffens (flost@live.no)
 */
class tvprogram extends widget implements interfaceWidget {


	// ======== INTERFACE METHODS ================================

	/*
	 * @return Array of all data for output
	 * this array will be routed to the subtemplate for this widget 
	 */
	public function getWidgetData() {
		$from = date("d.m.Y");
		$to = date("d.m.Y", time()+24*60*60);
		$programs = "SRTL";
		$url = "http://www.onlinetvrecorder.com/index.php?aktion=epg_export&format=xml&btn_ok=OK&stations=".$programs."&from=".$from."&to=".$to;
		
		$rss = new DOMDocument();
		$rss->load($url);
		$feed = array();
		foreach ($rss->getElementsByTagName('ITEM') as $node) {
			$item = array (
					'TITEL' => $node->getElementsByTagName('TITEL')->item(0)->nodeValue,
					'EPGId' => $node->getElementsByTagName('EPGId')->item(0)->nodeValue,
					'BEGINN' => $node->getElementsByTagName('BEGINN')->item(0)->nodeValue,
					'ENDE' => $node->getElementsByTagName('ENDE')->item(0)->nodeValue,
					'DAUER' => $node->getElementsByTagName('DAUER')->item(0)->nodeValue,
					'SENDER' => $node->getElementsByTagName('SENDER')->item(0)->nodeValue,
					'TYP' => $node->getElementsByTagName('TYP')->item(0)->nodeValue,
					'TEXT' => $node->getElementsByTagName('TEXT')->item(0)->nodeValue,
					'HIGHLIGHT' => $node->getElementsByTagName('HIGHLIGHT')->item(0)->nodeValue,
					'FSK' => $node->getElementsByTagName('FSK')->item(0)->nodeValue,
					'WEEKDAY' => $node->getElementsByTagName('WEEKDAY')->item(0)->nodeValue,
					'NICEDATE' => $node->getElementsByTagName('NICEDATE')->item(0)->nodeValue,
					'DOWNLOADLINK' => $node->getElementsByTagName('DOWNLOADLINK')->item(0)->nodeValue,
					'INFOLINK' => $node->getElementsByTagName('INFOLINK')->item(0)->nodeValue,
					'PROGRAMLINK' => $node->getElementsByTagName('PROGRAMLINK')->item(0)->nodeValue
			);			
			
			array_push($feed, $item);
		}
		

		foreach ($feed as $node) {
			$content .= "<br>".$node['TITEL'];
		}		
		
		return Array("content" => $content);
	}
	
	// ======== END INTERFACE METHODS =============================
	
}