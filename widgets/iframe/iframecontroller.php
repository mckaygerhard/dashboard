<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 16.12.14
 * Time: 08:02
 */

namespace OCA\Dashboard\Widgets\Iframe;

use OCA\Dashboard\Widgets\IWidgetController;
use OCA\Dashboard\Widgets\WidgetController;

class IframeController extends WidgetController implements IWidgetController {



    // interface needed methods ------------------------------------


    /**
     * see IWidgetController interface
     */
    public function setData() {
        $this->icon     =           'icons/71.png';
        $this->refresh  =                        0;
        $this->wId      =                 'iframe';
        $this->name     = $this->l10n->t('iFrame');
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
            'url'      => $this->getConfig('url')
        );

        // do not remove the following line
        // it creates the status information
        $this->setHash($data);
        return $data;
    }

    public function getName() {
        return $this->l10n->t( $this->getConfig('title', 'iFrame') );
    }

    // ajax call methods ---------------------------------------------

    // private services -------------------------------------------------

} 