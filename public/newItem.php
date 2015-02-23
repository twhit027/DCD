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

$cleanAdText = strip_tags($listings['adText']);
$siteCode = urlencode($listings['siteCode']);
$placement = urlencode($listings['placement']);
$position = urlencode($listings['position']);
$street = $listings['street'];
$city = $listings['city'];
$state = $listings['state'];


$imageArray = array();

if (!empty($listings['images'])) {
    $imageArray = explode(',', $listings['images']);
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





$mainContent = <<<EOS
<!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Listing
                    <small>($street)</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

            <div class="col-md-8">
                <img class="img-responsive" src="http://placehold.it/750x500" alt="">
                <br />
                <i class="fa fa-map-marker fa-2x"></i>&nbsp;&nbsp;$street, $city, $state <a href="#">(view Map)</a>
            </div>

            <div class="col-md-4">
                <h3>Description</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae. Sed dui lorem, adipiscing in adipiscing et, interdum nec metus. Mauris ultricies, justo eu convallis placerat, felis enim.</p>
                <h3>Details</h3>
                <ul>
                    <li>Lorem Ipsum</li>
                    <li>Dolor Sit Amet</li>
                    <li>Consectetur</li>
                    <li>Adipiscing Elit</li>
                </ul>
            </div>

        </div>
        <!-- /.row -->

        <!-- Related Projects Row -->
        <div class="row">

            <div class="col-lg-12">
                <h3 class="page-header">Additional Images</h3>
            </div>

            <div class="col-sm-3 col-xs-6">
                <a href="#">
                    <img class="img-responsive portfolio-item" src="http://placehold.it/500x300" alt="">
                </a>
            </div>

            <div class="col-sm-3 col-xs-6">
                <a href="#">
                    <img class="img-responsive portfolio-item" src="http://placehold.it/500x300" alt="">
                </a>
            </div>

            <div class="col-sm-3 col-xs-6">
                <a href="#">
                    <img class="img-responsive portfolio-item" src="http://placehold.it/500x300" alt="">
                </a>
            </div>

            <div class="col-sm-3 col-xs-6">
                <a href="#">
                    <img class="img-responsive portfolio-item" src="http://placehold.it/500x300" alt="">
                </a>
            </div>

        </div>
        <!-- /.row -->

        <hr />
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
