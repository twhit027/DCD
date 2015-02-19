<?php
include('../vendor/klogger/KLogger.php');
include('../vendor/Mobile_Detect/Mobile_Detect.php');
include('../conf/constants.php');
include('../includes/GCI/Database.php');
include('../includes/GCI/Site.php');
include('../includes/GCI/App.php');
include('../includes/GCI/Navigation.php');
include('../includes/GCI/Ads.php');

$app = new \GCI\App();

$app->logInfo('Directions Page(FORWARDED_FOR: '.@$_SERVER['HTTP_X_FORWARDED_FOR'].', REMOTE_ADDR: '.@$_SERVER['REMOTE_ADDR'].',HTTP_HOST: '.@$_SERVER['HTTP_HOST'].'SERVER_NAME: '.@$_SERVER['SERVER_NAME'].')');

$place = $_POST['place'];
$position = $_POST['posit'];
$placeEnc = urlencode($_POST['place']);
$positionEnc = urlencode($_POST['posit']);
$route = $_POST['locations'];
$listOfRummages = $app->getRummages($place,$position,$route);
$mapPoints = json_encode($listOfRummages['map']);
$address = array(
    "street" => $_POST['address'],
    "city" => $_POST['city'],
    "zip" => $_POST['zip']
);

$avoidHighways = '';
$avoidTolls = '';
$address = json_encode($address);
if(!empty($_POST['avoidHighways'])) {
    $avoidHighways = "DCDMAPGLOBAL.avoidHighways = true;\r\n";
}
if(!empty($_POST['avoidTolls'])) {
    $avoidTolls = "DCDMAPGLOBAL.avoidTolls = true;\r\n";
}

$masterBottom = '<script src="js/route.js"></script>';

$googleApiScript = <<<EOS
	<link rel="stylesheet" href="css/rummage.css">
    <!-- Google Maps API V3 -->
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <!-- Google Maps API V3 -->
    <script type="text/javascript">
        //setup global namespace
        var DCDMAPGLOBAL = {};
        DCDMAPGLOBAL.address = $address;
        DCDMAPGLOBAL.points = $mapPoints;
        $avoidHighways
        $avoidTolls
    </script>
EOS;

$data = <<<EOS
<div class="col-xs-11 col-sm-8">
	<div id="direction-canvas" style="width:100%; height:400px;"></div>
	<div id="directions-panel" style="width:100%"></div>
</div>
EOS;

$mainContent = <<<EOS
	<ol class="breadcrumb">
		<li><a href="./">Home</a></li>
		<li><a href="map.php?place=$placeEnc&posit=$positionEnc">$place</a></li>
		<li class="active">$position Directions</li>
	</ol>
	
	<br />
	
	$data
EOS;

include("../includes/master.php");
