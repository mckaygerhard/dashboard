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
use OCA\Dashboard\Controller\RouteWidgetContentController;
use OCA\Dashboard\Controller\RouteWidgetManagementController;
use OCA\Dashboard\Db\WidgetConfigDAO;
use OCA\Dashboard\Db\WidgetHashDAO;
use OCA\Dashboard\Services\WidgetManagementService;
use OCA\Dashboard\Services\WidgetSettingsService;
use OCA\Dashboard\Services\WidgetContentService;
use OCA\Dashboard\Services\WidgetHashService;
use \OCP\AppFramework\App;
use \OCP\IContainer;
use OCP\User;


class Application extends App {


	public function __construct (array $urlParams=array()) {
		parent::__construct('dashboard', $urlParams);

		$container = $this->getContainer();


		/**
		 * Route Controllers
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

        $container->registerService('RouteWidgetContentController', function(IContainer $c) {
            return new RouteWidgetContentController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('UserId'),
                $c->query('L10N'),
                $c->query('WidgetContentService')
            );
        });


        /**
         *  Services
         */
        $container->registerService('WidgetContentService', function(IContainer $c) {
            return new WidgetContentService(
                $c->query('UserId'),
                $c->query('L10N'),
                $c->query('WidgetManagementService')//,
                //$c->query('WidgetHashService')
            );
        });

        $container->registerService('WidgetManagementService', function(IContainer $c) {
            return new WidgetManagementService(
                $c->query('UserId'),
                $c->query('L10N'),
                $c->query('WidgetSettingsService')
            );
        });

        $container->registerService('WidgetHashService', function(IContainer $c) {
            return new WidgetHashService(
                $c->query('WidgetHashDAO')
            );
        });

        $container->registerService('WidgetSettingsService', function(IContainer $c) {
            return new WidgetSettingsService(
                $c->query('WidgetConfigDAO')
            );
        });


        /**
         * DAO
         */
        $container->registerService('WidgetHashDAO', function(IContainer $c) {
            return new WidgetHashDAO(
                $c->query('ServerContainer')->getDb()
            );
        });

        $container->registerService('WidgetConfigDAO', function(IContainer $c) {
            return new WidgetConfigDAO(
                $c->query('ServerContainer')->getDb()
            );
        });


        /**
		 * Core
		 */
        $container->registerService('L10N', function($c) {
            return $c->query('ServerContainer')->getL10N($c->query('AppName'));
        });
	}


}