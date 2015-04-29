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

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
$application = new Application();

$application->registerRoutes(
    $this,
    array(
        'routes' => array(
            // index
            array('name' => 'routePage#index',                              'url' => '/',                                   'verb' => 'GET'   ),

            // widget content
            array('name' => 'routeWidgetContent#get_complete',              'url' => '/widget/content/getComplete/{wiid}',  'verb' => 'GET'   ),
            array('name' => 'routeWidgetContent#get_content',               'url' => '/widget/content/getContent/{wiid}',   'verb' => 'GET'   ),
            array('name' => 'routeWidgetContent#call_method',               'url' => '/widget/content/callMethod',          'verb' => 'POST'  ),

            // widget settings
            array('name' => 'routeWidgetSettings#set_config',               'url' => '/widget/settings/setConfig',          'verb' => 'POST'  ),
            array('name' => 'routeWidgetSettings#get_config',               'url' => '/widget/settings/getConfig',          'verb' => 'GET'   ),

            // widget management
            array('name' => 'routeWidgetManagement#get_enabled_widgets',    'url' => '/widget/management/enabled',          'verb' => 'GET'   ),
            array('name' => 'routeWidgetManagement#get_available_widgets',  'url' => '/widget/management/available',        'verb' => 'GET'   ),
            array('name' => 'routeWidgetManagement#add_new_instance',       'url' => '/widget/management/add/{wid}',        'verb' => 'PUT'   ),
            array('name' => 'routeWidgetManagement#remove_instance',        'url' => '/widget/management/remove/{wiid}',    'verb' => 'DELETE'),
            array('name' => 'routeWidgetManagement#enable_widget',          'url' => '/widget/management/enable/{wid}',     'verb' => 'PUT'   ),
            array('name' => 'routeWidgetManagement#disable_widget',         'url' => '/widget/management/disable/{wid}',    'verb' => 'PUT'   ),

            // settings
            array('name' => 'routeSettings#get_config',                     'url' => '/settings/{key}',                     'verb' => 'GET'   ),
            array('name' => 'routeSettings#set_config',                     'url' => '/settings',                           'verb' => 'POST'  ),
        )
    )
);
