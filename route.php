<?php
include(dirname(__FILE__) . '/3rdParty/klogger/KLogger.php');
include(dirname(__FILE__) . '/3rdParty/Mobile_Detect/Mobile_Detect.php');
include('conf/constants.php');
include('includes/GCI/Database.php');
include('includes/GCI/Site.php');
include('includes/GCI/App.php');
include('includes/GCI/Navigation.php');
include('includes/GCI/Ads.php');

$app = new \GCI\App();

$app->logInfo('Route Page');
$app->logInfo('FORWARDED_FOR: ' . @$_SERVER['HTTP_X_FORWARDED_FOR']);
$app->logInfo('REMOTE_ADDR: ' . @$_SERVER['REMOTE_ADDR']);
$app->logInfo('HTTP_HOST: ' . @$_SERVER['HTTP_HOST']);
$app->logInfo('SERVER_NAME: ' . @$_SERVER['SERVER_NAME']);

$params = array(
    'PubCode' => 'DES-RM Des Moines Register'
);
if (isset($_POST['locations'])) {
    $ids = explode(",",$_POST['locations']);
    foreach($ids as $i) {
        $params['where']['ID'][] = $i;
    }
}

$listOfRummages = $app->getRummages();

$address = array(
    "street" => $_POST['address'],
    "city" => $_POST['city'],
    "zip" => $_POST['zip']
);

$avoidHighways = '';
$avoidTolls = '';
$address = json_encode($address);
$listOfRummages = json_encode($listOfRummages);

if($_POST['avoidHighways'] == "true")
    $avoidHighways = "DCDMAPGLOBAL.avoidHighways = true;\r\n";
if($_POST['avoidTolls'] == "true")
    $avoidTolls = "DCDMAPGLOBAL.avoidTolls = true;\r\n";

$googleApiScript = <<<EOS
    <!-- Google Maps API V3 -->
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <!-- Google Maps API V3 -->
    <script type="text/javascript">
        //setup global namespace
        var DCDMAPGLOBAL = {};
        DCDMAPGLOBAL.address = "$address";
        DCDMAPGLOBAL.points = "$listOfRummages";
        $avoidHighways
        $avoidTolls
    </script>
EOS;

$data = '';

$mainContent = <<<EOS
                <ol class="breadcrumb">
                <li><a href="./">Home</a></li>
                <li class="active">Rummage</li>
            </ol>

            <br />$data

            $googleApiScript
EOS;

include("includes/master.php");
