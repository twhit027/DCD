<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 4/7/14
 * Time: 5:38 PM
 */

include('../../vendor/klogger/KLogger.php');
include('../../vendor/Mobile_Detect/Mobile_Detect.php');
include('../../conf/constants.php');
include('../../includes/GCI/Database.php');
include('../../includes/GCI/Site.php');
include('../../includes/GCI/App.php');
include('../../includes/GCI/Navigation.php');
include('../../includes/GCI/Ads.php');

$app = new \GCI\App('', '../../logs');

$placement = $position = '';

if (isset($_REQUEST['place'])) {
    $placement = urldecode($_REQUEST['place']);
}
if (isset($_REQUEST['posit'])) {
    $position = urldecode($_REQUEST['posit']);
}

header('Content-Type: application/json');
echo json_encode($app->getCategories());