<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 16.12.14
 * Time: 08:02
 */

namespace OCA\Dashboard\Widgets\Bookmarks;

use OC_Bookmarks_Bookmarks;
use OCA\Dashboard\Widgets\IWidgetController;
use OCA\Dashboard\Widgets\WidgetController;
use OCP\App;

class BookmarksController extends WidgetController implements IWidgetController {



    // interface needed methods ------------------------------------


    /**
     * see IWidgetController interface
     */
    public function setData() {
        $this->icon     =              'icons/83.png';
        $this->refresh  =                         360;
        $this->wId      =                 'bookmarks';
        $this->name     = $this->l10n->t('Bookmarks');
    }

    /**
     *
     * returns all the needed data as array
     * you can access them in the widgetTemplate->getContentHtml with $data['abc']
     *
     * @return array
     */
    public function getData() {
        if( $this->checkDepedencies() ) {
            $data = array(
                'bookmarks' => $this->getBookmarks()
            );
        } else {
            $this->setStatus($this::STATUS_PROBLEM);
            $data = array(
                'msg'     => 'Bookmarks app must be enabled.'
            );
        }

        // do not remove the following line
        // it creates the status information
        $this->setHash($data);
        return $data;
    }


    // ajax call methods ---------------------------------------------


    // private services -------------------------------------------------

    private function checkDepedencies() {
        return App::isEnabled('bookmarks');
    }

    /**
     * @return array
     */
    private function getBookmarks() {
        $filters = $this->getConfig('tagKeyword', 'Dashboard');
        /** @var array $bookmarks */
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $bookmarks = OC_Bookmarks_Bookmarks::findBookmarks(0, 'clickcount', $filters, true, -1);
        return $bookmarks;
    }
} 