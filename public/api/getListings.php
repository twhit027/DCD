<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 3/23/14
 * Time: 3:23 PM
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

//$content = new Content();
$page = 1;
$fullText = $placement = $position = '';

if (isset($_REQUEST['page'])) {
    $page = urldecode($_REQUEST['page']);
}
if (isset($_REQUEST['ft'])) {
    $fullText = urldecode($_REQUEST['ft']);
}
if (isset($_REQUEST['place'])) {
    $placement = urldecode($_REQUEST['place']);
}
if (isset($_REQUEST['posit'])) {
    $position = urldecode($_REQUEST['posit']);
}
$search = "";
if(isset($_REQUEST['sites']))
{
    $sitegroup = urldecode($_REQUEST['sites']);
    $listings = $app->getListings($placement, $position, $page, $sitegroup);
}
else
{
    $listings = $app->getListings($placement, $position, $page, '', $fullText);
}

$json = json_encode($listings);

$jsonp_callback = isset($_GET['callback']) ? $_GET['callback'] : null;

header('Content-Type: application/json');
echo $jsonp_callback ? "$jsonp_callback($json)" : $json;


