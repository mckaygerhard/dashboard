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
use OCP\Util;


class DummyController extends WidgetController implements IWidgetController {


    // interface needed methods ------------------------------------

    public static function getBasicConf($L10N) {
        return array(
            'icon'      => Util::imagePath('dashboard', 'icons/9.png'),
            'refresh'   =>                                           0,
            'wId'       =>                                     'dummy',
            'name'      =>             $L10N->t('Dummy')->__toString()
        );
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