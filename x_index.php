<?php

\OCP\User::checkLoggedIn();
\OCP\App::checkAppEnabled('ocDashboard');
\OCP\App::setActiveNavigationEntry( 'ocDashboard' );

OCP\Util::addscript('ocDashboard', 'ocDashboard');
//OCP\Util::addscript('ocDashboard', 'ajaxService');
OCP\Util::addStyle('ocDashboard', 'ocDashboard');

$user = OCP\User::getUser();
$l=new OC_L10N('ocDashboard');

//OC::$CLASSPATH['widgets'] = 'ocDashboard/appinfo/widgetConfigs.php';
OC::$CLASSPATH['factory'] = 'ocDashboard/lib/factory.php';
//OC::$CLASSPATH['api'] = 'ocDashboard/lib/api.php';

$widgetObjects = Array();
foreach (factory::getEnabledWidgetIds($user) as $widgetId) {
	$widgetObjects[] = factory::getWidgetObject($widgetId);
}

$tpl = new OCP\Template("ocDashboard", "main", "user");
$tpl->assign('widgets', $widgets);

//if all deactivated
if(empty($widgetObjects)) {
	OCP\Util::addStyle('ocDashboard', 'ocDashboardWelcome');
	$tpl->assign('welcome',1);
}

$tpl->printPage();