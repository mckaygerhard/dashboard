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



use OCA\Dashboard\Controller\RoutePageController;
use OCA\Dashboard\Controller\RouteWidgetManagementController;
use \OCP\AppFramework\App;
use \OCP\IContainer;
use OCP\User;


class Application extends App {


	public function __construct (array $urlParams=array()) {
		parent::__construct('dashboard', $urlParams);

		$container = $this->getContainer();


		/**
		 * Controllers
		 */
        $container->registerService('RoutePageController', function(IContainer $c) {
            return new RoutePageController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('UserId')
            );
        });

        $container->registerService('RouteWidgetManagementController', function(IContainer $c) {
            return new RouteWidgetManagementController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('UserId')
            );
        });


        /**
		 * Core
		 */
		$container->registerService('UserId', function(IContainer $c) {
			return User::getUser();
		});

        $container->registerService('L10N', function($c) {
            return $c->query('ServerContainer')->getL10N($c->query('AppName'));
        });
	}


}