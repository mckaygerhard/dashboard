<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 16.12.14
 * Time: 16:25
 */

namespace OCA\Dashboard\Widgets;


use OC;
use OCA\Dashboard\Services\WidgetSettingsService;
use OCP\AppFramework\Controller;
use OCP\IL10N;

abstract class WidgetController extends Controller {

    protected $icon;
    protected $refresh;
    protected $name;
    protected $wId;
    protected $user;
    protected $wNo;
    protected $dataHash;
    protected $link;
    protected $status;

    protected $L10N;
    protected $widgetSettingsService;


    // abstract and magic methods ----------------------------------------------

    // each widget controller implements this (IWidgetController)
    public abstract function setBasicValues();


    function __construct($wNo, WidgetSettingsService $widgetSettingsService, $user, IL10N $l10n) {
        $this->wNo                      = intval($wNo);
        $this->user                     = $user;
        $this->L10N                     = $l10n;
        $this->widgetSettingsService    = $widgetSettingsService;
        $this->status                   = Status::STATUS_OKAY;

        // load widget specific values
        $this->setBasicValues();
    }


    // public services ---------------------------------------------------

    /**
     *
     * returns the status of this widget
     *
     * @return int
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     *
     * update status only if the new status
     * is more important that the old one
     *
     * @param $status
     */
    protected function setStatus($status) {
        if( $status > $this->status ) {
            $this->status = $status;
        }
    }

    /**
     *
     * returns all the needed data as array
     * you can access them in the widgetTemplate->getContentHtml with $data['abc']
     *
     * @return array
     */
    public function getBasicValues() {
        return array(
            'wId'       => $this->getConfig('wId'),
            'wNo'       => $this->getConfig('wNo'),
            'wIId'      => $this->getConfig('wIId'),
            'name'      => $this->getConfig('name'),
            'dimension' => $this->getConfig('dimension', '1x1'),
            'refresh'   => $this->getConfig('refresh', '30', 'int')
        );
    }

    /**
     *
     * tells you the chosen value for a key
     * if no value is set yet, the default will return
     *
     * @param $key
     * @param string $default
     * @param string $returnType
     * {'string', 'int', 'bool'}
     * @return string
     */
    public function getConfig ( $key, $default = '', $returnType = 'string' ) {
        $value = null;
        switch( $key ) {
            case 'wIId':
                $value = $this->getConfig('wId').'-'.$this->getConfig('wNo');
                break;
            case 'wName':
                $value = $this->name;
                break;
            case 'name':
                $value = $this->name;
                break;
            case 'wNo':
                $value = $this->wNo;
                break;
            case 'user':
                $value = $this->user;
                break;
            case 'icon':
                $value = $this->icon;
                break;
            case 'refresh':
                $value = $this->refresh;
                break;
            case 'wId':
                $value = $this->wId;
                break;
            case 'link':
                $value = $this->link;
                break;
            default:
                $value = null;
                //$value = $this->widgetConfigDAO->getConfig($this->wId, $this->wNo, $this->user, $key);
                //if( isset($value) && in_array($key, $this->encryptAttributes) ) {
                //    /** @noinspection PhpUndefinedClassInspection */
                //    $value = OC::$server->getCrypto()->decrypt($value);
                //}
                break;
        }
        $return = isset($value) ? $value: $default;

        switch( $returnType ) {
            case 'int':
                return intval($return);
                break;
            case 'bool':
                if( $return == '1' || $return || $return == 'true' ) {
                    return true;
                } else {
                    return false;
                }
            default:
                return ''.$return;
        }
    }

















    /**
     *
     * remove old hashes
     * insert or update actual hash
     * set status, if hash is new
     *
     * @param $data
     */
    protected function x_setHash($data) {
        $this->widgetHashDAO->removeOldHashes();
        $hash = sha1(json_encode($data));
        $usedHash = $this->widgetHashDAO->getHash($this->getConfig('wIId'), $this->user);
        if( $usedHash == $hash ) {
            // update timestamp
            $this->widgetHashDAO->updateHash($this->getConfig('wIId'), $this->user, $hash);
        } else {
            // insert new and mark as new widget content
            $this->widgetHashDAO->removeWidgetHashes($this->getConfig('wIId'), $this->user);
            $this->widgetHashDAO->insertHash($this->getConfig('wIId'), $this->user, $hash);
            $this->setStatus($this::STATUS_NEW);
        }
    }

    /**
     *
     * set a config value for THIS WIDGET AND USER
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public function x_setConfig ( $key, $value ) {
        if( in_array($key, $this->encryptAttributes) ) {
            /** @noinspection PhpUndefinedClassInspection */
            $value = OC::$server->getCrypto()->encrypt($value);
        }
        if( $value != null ) {
            return $this->widgetConfigDAO->insertOrUpdateConfig($this->wId, $this->wNo, $this->user, $key, $value);
        } else {
            \OC_Log::write('dashboard', 'could not setConfig (key='.$key.', value=null)', \OC_Log::INFO);
            return false;
        }
    }

} 