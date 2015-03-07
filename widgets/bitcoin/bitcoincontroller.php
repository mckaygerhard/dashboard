<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 16.12.14
 * Time: 08:02
 */

namespace OCA\Dashboard\Widgets\Bitcoin;

use OCA\Dashboard\Widgets\IWidgetController;
use OCA\Dashboard\Widgets\WidgetController;

class BitcoinController extends WidgetController implements IWidgetController {

    private $json;
    private $url = "https://www.bitstamp.net/api/ticker/";

    // interface needed methods ------------------------------------


    /**
     * see IWidgetController interface
     */
    public function setData() {
        $this->icon     =           'icons/201.png';
        $this->refresh  =                       360;
        $this->wId      =                 'bitcoin';
        $this->name     = $this->l10n->t('Bitcoin');
    }

    /**
     *
     * returns all the needed data as array
     * you can access them in the widgetTemplate->getContentHtml with $data['abc']
     *
     * @return array
     */
    public function getData() {
        $data = array();
        if($this->getJSON()) {
            $data = array(
                'last'      => $this->json->last,
                'low'       => $this->json->low,
                'high'      => $this->json->high,
            );
        } else {
            $this->status = 2;
            $data['msg'] = 'The API response is wrong.';
        }

        // do not remove the following line
        // it creates the status information
        $this->setHash($data);
        return $data;
    }


    // ajax call methods ---------------------------------------------

    // private services -------------------------------------------------

    /*
	 * loads the json data from Bitstamp
	 */
    private function getJSON() {
        $con = @file_get_contents($this->url);
        if($con != '') {
            $this->json = json_decode($con);
            return true;
        } else {
            \OC_Log::write('dashboard',"Bitcoin price could not be loaded.", \OC_Log::WARN);
            return false;
        }
    }
} 