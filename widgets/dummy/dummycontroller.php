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
     *
     * set basic values
     *
     * will be executed at end of
     * constructor of the superclass
     *
     */
    public function setBasicValues() {
        $this->icon     =           'icons/9.png';
        $this->refresh  =                      20;
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
        if( $this->getConfig('wNo') == Status::STATUS_OKAY ) {
            $this->setStatus(Status::STATUS_OKAY);
        } else {
            $this->setStatus(Status::STATUS_NEW);
        }

        return array(
            'wIId'      => $this->getConfig('wIId'),
            'status'    => $this->getStatus(),
            'values'    => array(
                'status'    =>  $this->getStatus(),
                'wNo'       =>  $this->getConfig('wNo'),
                'refresh'   =>  $this->getConfig('refresh'),
                'wId'       =>  $this->getConfig('wId'),
                'user'      =>  $this->getConfig('user'),
                'link'      =>  $this->getConfig('link')
            ),
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