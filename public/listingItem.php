<?php
/**
 * Created by PhpStorm.
 * User: jhicks
 * Date: 2/19/2015
 * Time: 5:01 PM
 */

include('../vendor/klogger/KLogger.php');
include('../vendor/Mobile_Detect/Mobile_Detect.php');
include('../conf/constants.php');
include('../includes/GCI/Database.php');
include('../includes/GCI/Site.php');
include('../includes/GCI/App.php');
include('../includes/GCI/Navigation.php');
include('../includes/GCI/Ads.php');

$app = new \GCI\App();

$app->logInfo('Item Page(ID: '.urldecode($_REQUEST['id']).' FORWARDED_FOR: '.@$_SERVER['HTTP_X_FORWARDED_FOR'].', REMOTE_ADDR: '.@$_SERVER['REMOTE_ADDR'].',HTTP_HOST: '.@$_SERVER['HTTP_HOST'].'SERVER_NAME: '.@$_SERVER['SERVER_NAME'].')');

$id = urldecode($_REQUEST['id']);

if (isset($_REQUEST['place'])) {
    $placement = $_REQUEST['place'];
}
if (isset($_REQUEST['posit'])) {
    $position = $_REQUEST['posit'];
}

$listings = $app->getSingleListing($id);

$cleanAdText = strip_tags($listings['AdText']);
$siteCode = urlencode($listings['SiteCode']);
$placement = urlencode($listings['Placement']);
$position = urlencode($listings['Position']);
$street = $listings['Street'];
$city = $listings['City'];
$state = $listings['State'];
$amenities = json_decode($listings['Amenities']);
$email = $listings['Email'];
$propType = $listings['PropType'];
$parking = $listings['Parking'];

$parkingList = '';
if (!empty($parking)) {
    $parkingList .= '<ul><li>'.$parking.'</li></ul>';
}

$bedRooms = $listings['BedRooms'];
$bathRooms = $listings['BathRooms'];
$rent = $listings['Rent'];
$phone = $listings['Phone'];
$neighborhood = $listings['Neighborhood'];

$pets = $listings['Pets'];

$petsList = '';
if (!empty($pets)) {
    $petsList .= '<ul><li>'.$pets.'</li></ul>';
}

$recs = json_decode($listings['ExerciseRec']);
$recsList = '';
if (!empty($recs)) {
    $recsList = '<ul>';
    foreach($recs as $rectx) {
        $recsList .= '<li>'.$rectx.'</li>';
    }
    $recsList .= '</ul>';
}

$feats = json_decode($listings['CommFeat']);
$featsList = '';
if (!empty($feats)) {
    $featsList = '<ul>';
    foreach($feats as $rectx) {
        $featsList .= '<li>'.$rectx.'</li>';
    }
    $featsList .= '</ul>';
}

$propTypeShow = '';
if (!empty($propType)) {
    $propTypeShow = "($propType)";
}

$imageArray = array();

if (!empty($listings['Images'])) {
    $imageArray = explode(',', $listings['Images']);
}


$metadata = '
<title>'.substr($cleanAdText, 0, 70).'</title>
<meta name="description" content="'.substr($cleanAdText, 0, 150).'" />

<meta itemprop="name" content="'.substr($cleanAdText, 0, 70).'">
<meta itemprop="description" content="'.substr($cleanAdText, 0, 150).'">';

function convertImages($listingResults) {
//<imgp src="0000005351-01-1.jpg">
    //<img src="0000005351-01-1.jpg">
    return preg_replace('/src="([^"]*)"/i', 'src="img/images/'.$listingResults['siteCode'].'/${1}"', $listingResults['adText']);
    }


if (!empty($email)) {
    $email ='<a class="btn btn-small" type="button" href="mailto:'.$email.'?subject='. str_replace("&","%26",substr($cleanAdText, 0, 80)) .'"><span class="glyphicon glyphicon glyphicon-envelope" aria-hidden="true"></span></a>';
}

if (!empty($amenities)) {
    $amenitiesList = '<h3>Amenities</h3><ul>';
    foreach($amenities as $amen) {
        $amenitiesList .= '<li>'.$amen.'</li>';
    }
    $amenitiesList .= '</ul>';
}

$mainContent = <<<EOS
<!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">$street
                    <small>$propTypeShow</small>
                    <small>$email</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

            <div class="col-md-8">
                <img class="img-responsive" src="http://placehold.it/480x320" alt="">
                <br />
                <i class="fa fa-map-marker fa-2x"></i>&nbsp;&nbsp;$street, $city, $state <a href="#">(view Map)</a>
            </div>

            <div class="col-md-4">
                <h3>Description</h3>
                <p>$cleanAdText</p>
                $amenitiesList
            </div>

        </div>

        <div class="row">
        <div class="col-sm-3 col-xs-6">
        $bedRooms&nbsp<strong>Bedrooms</strong>
        </div>

        <div class="col-sm-3 col-xs-6">
        $bathRooms&nbsp<strong>Bathrooms</strong>
        </div>

        <div class="col-sm-3 col-xs-6">
        <strong>$</strong>$rent
        </div>

        <div class="col-sm-3 col-xs-6">
        $neighborhood
        </div>

        </div>
        <!-- /.row -->

        <!-- Related Projects Row -->
        <div class="row">

            <div class="col-lg-12">
                <h3 class="page-header">Additional Features</h3>
            </div>

            <div class="col-sm-3 col-xs-6">
                <h4>Parking</h4>
                    $parkingList
            </div>

            <div class="col-sm-3 col-xs-6">
                <h4>Pets</h4>
                    $petsList
            </div>

            <div class="col-sm-3 col-xs-6">
                <h4>Recreation</h4>
                    $recsList
            </div>

            <div class="col-sm-3 col-xs-6">
                <h4>Features</h4>
                    $featsList
            </div>


        </div>
        <!-- /.row -->


        <div class"row">
            <div class="col-lg-12">
                <h3 class="page-header">Area Map</h3>
            </div>
            <br />
            <div id="dcd-map-container"></div>
        </div>

        <hr />
EOS;

$mapPoints = '{
    "APTSTEST": {
        "street": "619 Virginia Ave",
        "city": "Indianapolis",
        "state": "In",
        "zip": "46203",
        "lat": "39.7583596",
        "lon": "-86.14653"
    }}';

$googleApiScript = <<<EOS
	<link rel="stylesheet" href="css/rummage.css">
    <!-- Google Maps API V3 -->
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <!-- Google Maps API V3 -->
    <script type="text/javascript">
        //setup global namespace
        var DCDMAPGLOBAL = {};
        DCDMAPGLOBAL.points = $mapPoints;
    </script>
EOS;

$masterBottom = '<script src="js/rummage.js"></script>';

include("../includes/master.php");
