<?php

//use OCP\Util;
//
//Util::writeLog('dashboard', 'Start update script.',1);
//
//try {
//    // Changes for a lower case db
//    $stmt = \OCP\DB::prepare( 'ALTER TABLE IF EXISTS `*PREFIX*dashboard_used_hashs` RENAME TO `*PREFIX*dashboard_used_hashs_hallo2`');
//
// if exists (SELECT * FROM oc_dashboard_usedHashs)
//BEGIN
//DROP TABLE IF EXISTS oc_dashboard_used_hashs;
//ALTER TABLE oc_dashboard_usedHashs RENAME TO oc_dashboard_used_hashs;
//END
//
//    $result = $stmt->execute();
//    $str = $result->errorInfo();
//} catch (\Exception $e) {
//
//}
//
//Util::writeLog('dashboard', 'End update script.',1);