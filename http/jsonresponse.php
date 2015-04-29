<?php

namespace OCA\Dashboard;

use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Response;

class JSONResponse extends Response {

    protected $data;
    private $widgetStatus;

    public function __construct($data=array(), $statusCode=Http::STATUS_OK) {
        $this->data = $data;
        $this->setStatus($statusCode);
        $this->addHeader('Content-Type', 'application/json; charset=utf-8');
    }

    public function render(){
        return json_encode($this->data);
    }

    public function setData($data){
        $this->data = $data;
    }

    public function getData(){
        return $this->data;
    }

    public function setWidgetStatus($widgetStatus){
        $this->data = $widgetStatus;
    }

    public function getWidgetStatus(){
        return $this->widgetStatus;
    }

}
