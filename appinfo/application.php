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


use OCA\Dashboard\Controller\WidgetController;
use OCA\Dashboard\Db\WidgetConfigDAO;
use OCA\Dashboard\Db\WidgetHashDAO;
use \OCP\AppFramework\App;
use \OCP\IContainer;
use \OCA\Dashboard\Controller\PageController;
use OCP\User;


class Application extends App {


	public function __construct (array $urlParams=array()) {
		parent::__construct('dashboard', $urlParams);

		$container = $this->getContainer();


		/**
		 * Controllers
		 */
        $container->registerService('PageController', function(IContainer $c) {
            return new PageController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('UserId'),
                $c->query('WidgetConfigDAO'),
                $c->query('L10N'),
                $c->query('WidgetController')
            );
        });

        $container->registerService('WidgetController', function(IContainer $c) {
            return new WidgetController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('UserId'),
                $c->query('WidgetConfigDAO'),
                $c->query('WidgetHashDAO'),
                $c->query('L10N')
            );
        });


        /**
         * Database Layer
         */
        $container->registerService('WidgetConfigDAO', function($c) {
            return new WidgetConfigDAO($c->query('ServerContainer')->getDb());
        });

        $container->registerService('WidgetHashDAO', function($c) {
            return new WidgetHashDAO($c->query('ServerContainer')->getDb());
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