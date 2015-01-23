<?php
/**
 * Created by PhpStorm.
 * User: flost
 * Date: 16.12.14
 * Time: 16:25
 */

namespace OCA\Dashboard\Widgets;


use OC;
use OCA\Dashboard\Db\WidgetConfigDAO;
use OCA\Dashboard\Db\WidgetHashDAO;
use OCP\AppFramework\Controller;
use OCP\IL10N;

abstract class WidgetController extends Controller {

    const STATUS_OKAY       = 0;
    const STATUS_NEW        = 1;
    const STATUS_PROBLEM    = 2;
    const STATUS_ERROR      = 3;

    protected $icon;
    protected $refresh;
    protected $name;
    protected $wId;
    protected $user;
    protected $wNo;
    protected $dataHash;
    protected $link;
    protected $status       =  0;

    protected $widgetConfigDAO;
    protected $widgetHashDAO;
    protected $l10n;

    // this key will be stored encrypted in the db
    protected $encryptAttributes = array(
        'password'
    );


    // abstract and magic methods ----------------------------------------------

    public abstract function setData();

    public abstract function getData();

    /**
     * @param $wNo
     * @param WidgetConfigDAO $widgetConfigDAO
     * @param WidgetHashDAO $widgetHashDAO
     * @param $user
     * @param IL10N $l10n
     */
    function __construct($wNo,WidgetConfigDAO $widgetConfigDAO, WidgetHashDAO $widgetHashDAO, $user, IL10N $l10n) {
        $this->wNo              = intval($wNo);
        $this->widgetConfigDAO  = $widgetConfigDAO;
        $this->widgetHashDAO    = $widgetHashDAO;
        $this->user             = $user;
        $this->l10n             = $l10n;
        $this->setData();
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
     * remove old hashes
     * insert or update actual hash
     * set status, if hash is new
     *
     * @param $data
     */
    protected function setHash($data) {
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
                $value = $this->widgetConfigDAO->getConfig($this->wId, $this->wNo, $this->user, $key);
                if( isset($value) && in_array($key, $this->encryptAttributes) ) {
                    /** @noinspection PhpUndefinedClassInspection */
                    $value = OC::$server->getCrypto()->decrypt($value);
                }
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
     * set a config value for THIS WIDGET AND USER
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public function setConfig ( $key, $value ) {
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