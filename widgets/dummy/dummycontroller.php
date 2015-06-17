<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 16.12.14
 * Time: 08:02
 */

namespace OCA\Dashboard\Widgets\Dummy;

use OCA\Dashboard\Widgets\IWidgetController;
use OCA\Dashboard\Widgets\status;
use OCA\Dashboard\Widgets\WidgetController;


class DummyController extends WidgetController implements IWidgetController {


    // interface needed methods ------------------------------------


    /**
     * see IWidgetController interface
     */
    public function setBasicValues() {
        $this->icon     =           'icons/9.png';
        $this->refresh  =                       0;
        $this->wId      =                 'dummy';
        $this->name     = $this->L10N->t('Dummy');
    }


    /**
     *
     * return values as array as parameter for the template
     * always return
     *
     * @return array
     */
    public function getData() {
        return array(
            'wIId'      => $this->getConfig('wIId'),
            'status'    => Status::STATUS_OKAY,
            'settingOne'=> 'eins',
            'time'      => time()
        );
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