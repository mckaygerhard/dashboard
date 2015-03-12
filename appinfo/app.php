<?php
/**
 * ownCloud - dashboard
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Florian Steffens <webmaster@freans.de>
 * @copyright Florian Steffens 2014
 */

namespace OCA\Dashboard\AppInfo;


use OC_L10N;
use OCP\App;
use OCP\Util;

App::addNavigationEntry(array(
    // the string under which your app will be referenced in owncloud
    'id' => 'dashboard',

    // sorting weight for the navigation. The higher the number, the higher
    // will it be listed in the navigation
    'order' => 10,

    // the route that will be shown on startup
    'href' => Util::linkToRoute('dashboard.page.index'),

    // the icon that will be shown in the navigation
    // this file needs to exist in img/
    'icon' => Util::imagePath('dashboard', 'dashboard.svg'),

    // the title of your application. This will be used in the
    // navigation or on the settings page of your app
    'name' => OC_L10N::get('dashboard')->t('Dashboard')
));
