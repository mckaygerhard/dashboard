<?php

\OCP\App::addNavigationEntry(array(

    // the string under which your app will be referenced in owncloud
    'id' => 'ocDashboard',

    // sorting weight for the navigation. The higher the number, the higher
    // will it be listed in the navigation
    'order' => 0,

    // the route that will be shown on startup
    'href' => \OCP\Util::linkToRoute('dashboard'),

    // the icon that will be shown in the navigation
    // this file needs to exist in img/example.png
    'icon' => \OCP\Util::imagePath('ocDashboard', 'img/icon.svg'),

    // the title of your application. This will be used in the
    // navigation or on the settings page of your app
    'name' => \OC_L10N::get('ocDashboard')->t('Dashboard')
));