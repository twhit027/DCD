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

$app->logInfo('Landin Page(FORWARDED_FOR: '.@$_SERVER['HTTP_X_FORWARDED_FOR'].', REMOTE_ADDR: '.@$_SERVER['REMOTE_ADDR'].',HTTP_HOST: '.@$_SERVER['HTTP_HOST'].'SERVER_NAME: '.@$_SERVER['SERVER_NAME'].')');

$search = $app->getSearch();
$siteName = $app->getSite()->getSiteName();
$siteUrl = $app->getSite()->getSiteUrl();
$busName = $app->getSite()->getBusName();

$mainContent = <<<EOS
<h1>$busName &amp; Online Classifieds</h1>
<div class="jumbotron" id="advancedsearch" style="display:none;">
    $search
</div>

<a href="$siteUrl" target="_blank"><img alt="$siteName Logo" title="$siteName" style="margin-bottom: 10px;background-color: black" src="$siteUrl/graphics/ody/cobrand_logo.gif"></a>
<p>SELL easy and SELL fast!</p>
<p>As the leading local media and trusted marketing solutions provider, we have a range of effective advertising packages to meet your needs.</p>
<p>From VEHICLES to PETS to GARAGE SALES to SERVICES, we provide the most effective ways to sell to potential local buyers through our leading mobile, online and print solutions.</p>
<p>Develop and launch your advertising program within minutes with just a few clicks, and begin connecting with local buyers TODAY!</p>
<p><a class="button" href="$siteUrl/placead"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">Place an Ad</button></a></p>
<h1>Featured Partner Classified Services</h1>
<div class="row">
    <div class="col-md-3">
        <h4>Cars</h4>
        <a href="$siteUrl/cars"><img alt="Cars.com" src="img/partners/130-cars.gif"></a>
        <p><a class="button" href="$siteUrl/cars"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Autos</button></a></p>
    </div>
    <div class="col-md-3">
        <h4>Jobs</h4>
        <a href="$siteUrl/jobs"><img alt="micareerbuilder.com" src="img/partners/130-careerbuilder.gif"></a>
        <p><a class="button" href="$siteUrl/jobs"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Jobs</button></a></p>
    </div>
    <div class="col-md-3">
        <h4>Homes</h4>
        <a href="$siteUrl/homes"><img alt="homefinder.com" src="img/partners/130-homefinder.gif" ></a>
        <p><a class="button" href="$siteUrl/homes"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Homes</button></a></p>
    </div>
    <div class="col-md-3">
        <h4>Rentals</h4>
        <a href="$siteUrl/apartments"><img alt="apartments.com" src="img/partners/130-apartments.gif" ></a>
        <p><a class="button" href="$siteUrl/apartments"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Listings</button></a></p>
    </div>
</div>
EOS;

include("../includes/master.php");
