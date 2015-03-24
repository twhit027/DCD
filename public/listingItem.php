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

function convertImages($listingResults) {
//<imgp src="0000005351-01-1.jpg">
    //<img src="0000005351-01-1.jpg">
    return preg_replace('/src="([^"]*)"/i', 'src="img/images/'.$listingResults['siteCode'].'/${1}"', $listingResults['adText']);
}

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

$busName = $app->getSite()->getBusName();

$cleanAdText = strip_tags($listings['AdText']);
$siteCode = urlencode($listings['SiteCode']);
$placement = urlencode($listings['Placement']);
$position = urlencode($listings['Position']);
$street = $listings['Street'];
$city = $listings['City'];
$state = $listings['State'];
$zip = $listings['Zip'];
$amenities = json_decode($listings['Amenities']);
$email = $listings['Email'];
$propType = $listings['PropType'];
$parking = $listings['Parking'];
$deposit = $listings['Deposit'];

$parkingList = '';
if (!empty($parking)) {
    $parkingList .= '<ul><li>'.$parking.'</li></ul>';
}

$bedRooms = $listings['BedRooms'];
$bathRooms = $listings['BathRooms'];
$rent = $listings['Rent'];
$phone = $listings['Phone'];
$neighborhood = $listings['Neighborhood'];
$squareFeet = $listings['SquareFeet'];

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

$neighborhoodShow = '';
if (!empty($neighborhood)) {
    $neighborhoodShow = "($neighborhood)";
}

$imageArray = array();
$imageArrayCnt = 0;
if (!empty($listings['Images'])) {
    $imageArray = explode(',', $listings['Images']);
    $imageArrayCnt = count($imageArray);
}

$emailGlyph = $emailTextOnly = '';
if (!empty($email)) {
    $emailGlyph ='<a class="btn btn-small" type="button" href="mailto:'.$email.'?subject='. str_replace("&","%26",substr($cleanAdText, 0, 80)) .'"><span class="glyphicon glyphicon glyphicon-envelope" aria-hidden="true"></span></a>';
    $emailTextOnly ='<a class="btn btn-small" type="button" href="mailto:'.$email.'?subject='. str_replace("&","%26",substr($cleanAdText, 0, 80)) .'">'.$email.'</a>';
}

if (!empty($amenities)) {
    $amenitiesList = '<h3>Amenities</h3><ul>';
    foreach($amenities as $amen) {
        $amenitiesList .= '<li>'.$amen.'</li>';
    }
    $amenitiesList .= '</ul>';
}

$server = $_SERVER['SERVER_NAME'];
$port = $_SERVER['SERVER_PORT'];
if (isset($_SERVER['CONTEXT_PREFIX'])) {
    $server .= $_SERVER['CONTEXT_PREFIX'];
}

$url = rtrim($server, "/");

if (!empty($port)) {
    $url= $url.':'.$port;
}

$imageCarouselIndicators = '';
$imageCarouselDivs = '';
if ($imageArrayCnt > 2) {
    $imgCnt = 0;
    foreach((array) $imageArray as $imgFile) {
        if (strpos($imgFile, 'http:') === false) {
            $imgSrc = 'http://' . $url . '/images/' . $listings['SiteCode'] . '/' . $imgFile;
        } else {
            $imgSrc =  $imgFile;
        }

        if ($imgCnt == 0) {
            $imageCarouselIndicators .= '<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>';
            $imageCarouselDivs .= '<div class="item active"><img alt="slide '.$imgCnt.'" src="'.$imgSrc.'"></div>';
        } else {
            $imageCarouselIndicators .= '<li data-target="#carousel-example-generic" data-slide-to="'.$imgCnt.'"></li>';
            $imageCarouselDivs .= '<div class="item"><img alt="slide '.$imgCnt.'" src="'.$imgSrc.'"></div>';
        }
        $imgCnt++;
    }
}

$imageCarousel = <<<EOS
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    $imageCarouselIndicators
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    $imageCarouselDivs
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
EOS;

if ($imageArrayCnt == 0) {
    $imageCarousel = '<img class="img-responsive" src="http://placehold.it/480x320&text=No+Image+available" alt="No Image Available">';
} else if ($imageArrayCnt == 1) {
    $imgFile = $imageArray[0];
        if (strpos($imgFile, 'http:') === false) {
            $imgSrc = 'http://' . $url . '/images/' . $listings['SiteCode'] . '/' . $imgFile;
        } else {
            $imgSrc =  $imgFile;
        }

    $imageCarousel = '<img class="img-responsive" style="margin: 0 auto;" src="'.$imgSrc.'" alt="No Image Available">';
}

$metadata = '
<title>'.$busName.' - Classifieds Listing - ($id)</title>
<meta name="description" content="'.substr($cleanAdText, 0, 150).'" />
<meta itemprop="name" content="'.substr($cleanAdText, 0, 70).'">
<meta itemprop="description" content="'.substr($cleanAdText, 0, 150).'">';

$urlEncodedPlacement = urlencode($placement);
$urlEncodedPosition = urlencode($position);

$propInfo = '';
if (!empty($bedRooms)) {
    $propInfo .= $bedRooms . ' Bed Rooms';
}
if (!empty($bathRooms)) {
    if (! empty($propInfo)) {$propInfo .= '$nbsp;|$nbsp;';}
    $propInfo .= $bathRooms . ' Bath Rooms';
}
if (!empty($squareFeet)) {
    if (! empty($propInfo)) {$propInfo .= '$nbsp;|$nbsp;';}
    $propInfo .= $squareFeet . ' Square Feet';
}
if (!empty($propType)) {
    if (! empty($propInfo)) {$propInfo .= '$nbsp;|$nbsp;';}
    $propInfo .= $propType;
}
if (!empty($rent)) {
    if (! empty($propInfo)) {$propInfo .= '$nbsp;|$nbsp;';}
    $propInfo .= '$ '.$rent. ' Rent';
}
if (!empty($deposit)) {
    if (! empty($propInfo)) {$propInfo .= '$nbsp;|$nbsp;';}
    $propInfo .= '$'.$deposit.' Deposit';
}

$depositDiv = '';
if (! empty($deposit) && ($deposit != 'null')) {
    $depositDiv = '<div class="col-sm-3 col-xs-6"><h4><i class="fa fa-money"></i>&nbsp;Deposit</h4>'.$deposit.'</div>';
}

$propTypeDiv = '';
if (! empty($propType) && ($propType != 'null')) {
    $propTypeDiv = '<div class="col-sm-3 col-xs-6"><h4><i class="fa fa-university"></i>&nbsp;Property Type</h4>'.$propType.'</div>';
}

$mainContent = <<<EOS
<ol class="breadcrumb">
    <li><a href="./">Home</a></li>
    <li><a href="map.php?place=$urlEncodedPlacement&posit=$urlEncodedPosition">$position</a></li>
    <li class="active">listing - ($id)</li>
</ol>
<!-- Portfolio Item Heading -->
        <div class="row" style="margin-top: 0px;">
            <div class="col-lg-12">
                <h1 class="page-header">$street
                    <small>$neighborhoodShow</small>
                    <small>$emailGlyph</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

            <div class="col-md-8">
                $imageCarousel
                <br />
                <i class="fa fa-map-marker fa-2x"></i>&nbsp;&nbsp;$street, $city, $state $zip<a id="gotomap" href="#">(view Map)</a>
            </div>

            <div class="col-md-4">
                <h3>Description</h3>
                <p>$cleanAdText</p>
                $amenitiesList
            </div>

        </div>

        <div class="panel panel-default" style="margin-top: 20px">
            <div class="panel-body">$propInfo</div>
        </div>
        <!-- /.row -->

        <!-- Related Projects Row -->
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">Additional Features</h3>
            </div>
            <div class="col-sm-3 col-xs-6">
                <h4><i class="fa fa-car"></i>&nbsp;Parking</h4>
                    $parkingList
            </div>
            <div class="col-sm-3 col-xs-6">
                <h4><i class="fa fa-paw"></i>&nbsp;Pets</h4>
                    $petsList
            </div>
            <div class="col-sm-3 col-xs-6">
                <h4><i class="fa fa-futbol-o"></i>&nbsp;Recreation</h4>
                    $recsList
            </div>
            <div class="col-sm-3 col-xs-6">
                <h4><i class="fa fa-building-o"></i>&nbsp;Features</h4>
                    $featsList
            </div>
        </div>
        <!-- /.row -->

        <!-- Related Projects Row -->
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">Contact</h3>
            </div>
            <div class="col-sm-6 col-xs-12">
                <strong>Phone:</strong>&nbsp;<a href="tel:$phone">$phone</a>
            </div>
            <div class="col-sm-6 col-xs-12">
                <strong>Email:</strong>&nbsp; $emailTextOnly
            </div>
        </div>

        <div class"row">
            <div class="col-lg-12" id="map">
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

$masterBottom = '<link type="text/css" rel="stylesheet" href="3rdParty/fancybox/source/jquery.fancybox.css" media="screen">
<script type="text/javascript" src="3rdParty/fancybox/source/jquery.fancybox.pack.js"></script>
<script src="js/rummage.js"></script>';

include("../includes/master.php");
