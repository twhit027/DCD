<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 4/7/14
 * Time: 5:38 PM
 */

error_reporting(0);

include('../../vendor/klogger/KLogger.php');
include('../../vendor/Mobile_Detect/Mobile_Detect.php');
include('../../conf/constants.php');
include('../../includes/GCI/Database.php');
include('../../includes/GCI/Site.php');
include('../../includes/GCI/App.php');
include('../../includes/GCI/Navigation.php');
include('../../includes/GCI/Ads.php');

$app = new \GCI\App();

$placement = $position = '';

if (isset($_REQUEST['place'])) {
    $placement = urldecode($_REQUEST['place']);
}
if (isset($_REQUEST['posit'])) {
    $position = urldecode($_REQUEST['posit']);
}

$json = json_encode($app->getCategories());

$jsonp_callback = isset($_GET['callback']) ? $_GET['callback'] : null;

header('Content-Type: application/json');
echo $jsonp_callback ? "$jsonp_callback($json)" : $json;