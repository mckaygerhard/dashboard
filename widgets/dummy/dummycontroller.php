<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 16.12.14
 * Time: 08:02
 */

namespace OCA\Dashboard\Widgets\Dummy;

use OCA\Dashboard\Widgets\IWidgetController;
use OCA\Dashboard\Widgets\WidgetController;

class DummyController extends WidgetController implements IWidgetController {



    // interface needed methods ------------------------------------


    /**
     * see IWidgetController interface
     */
    public function setData() {
        $this->icon     = 'icons/9.png';
        $this->refresh  =            60;
        $this->wId      =       'dummy';
        $this->name     =       'Dummy';
    }

    /**
     *
     * returns all the needed data as array
     * you can access them in the widgetTemplate->getContentHtml with $data['abc']
     *
     * @return array
     */
    public function getData() {
        $data = array(
            'time'          => time(),
            'settingOne'    => $this->getConfig('settingOne', 'valueOne')
        );

        // do not remove the following line
        // it creates the status information
        $this->setHash($data);
        return $data;
    }


    // ajax call methods ---------------------------------------------

    /**
     *
     * ajax example
     *
     * @param $status
     * @return mixed
     */
    public function generateStatus( $status ) {
        return $status;
    }


    // private services -------------------------------------------------

} 