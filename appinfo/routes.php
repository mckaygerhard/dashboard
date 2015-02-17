<?php

$this->create('ocDashboard_index', '/')->action(
    function($params){
        require __DIR__ . '/../index.php';
    }
);

$this->create('ocDashboard_appuserconfig', 'ajax/AppUserConfig.php')
    ->actionInclude('ocDashboard/ajax/AppUserConfig.php');

$this->create('ocDashboard_ajaxservice', 'ajax/ajaxService.php')
    ->actionInclude('ocDashboard/ajax/ajaxService.php');

$this->create('ocDashboard_reloadwidget', 'ajax/reloadWidget.php')
    ->actionInclude('ocDashboard/ajax/reloadWidget.php');
