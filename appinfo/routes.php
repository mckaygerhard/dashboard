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

$application->registerRoutes($this, array('routes' => array(
	array('name' => 'page#index',           'url' => '/',                           'verb' => 'GET'),
    array('name' => 'widget#get_complete',  'url' => '/widget/complete',            'verb' => 'POST'),
    array('name' => 'widget#get_content',   'url' => '/widget/content',             'verb' => 'POST'),
    array('name' => 'widget#call_method',   'url' => '/widget/call',                'verb' => 'POST'),
    array('name' => 'widget#set_config',    'url' => '/widget/config',              'verb' => 'POST'),
    array('name' => 'widget#get_available', 'url' => '/widget/getAvailable',        'verb' => 'POST'),
    array('name' => 'widget#add_new',       'url' => '/widget/addNew',              'verb' => 'POST'),
    array('name' => 'widget#remove',        'url' => '/widget/remove',              'verb' => 'POST'),
)));
