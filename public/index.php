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
<h1>$busName Classifieds</h1>
<h2>Introducing our new online system</h2>

<p>Now it’s easier than ever to place an ad and find what you’re looking for—24 hours a day, seven days a week.</p>
<p>In just a few clicks, you can place your ads online, in print or both. And with improved ad displays, your ad is sure to get noticed!</p>
<p>From vehicles to pets to garage sales to services, we provide the most effective ways to sell to potential local
buyers through our leading mobile, online and print solutions.</p>
<p><a class="button" href="http://$siteName.gannettclassifieds.com"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">Place an Ad</button></a></p>
<p><a class="button" href="http://$siteName.com/classifiedshelp" target="_blank"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">Classifieds Help</button></a></p>
<h1>Featured Partner Classified Services</h1>
<div class="row">
    <div class="col-md-4">
        <h4>Cars</h4>
        <a href="$siteUrl/cars"><img alt="Cars.com" src="img/partners/130-cars.gif"></a>
        <p><a class="button" href="$siteUrl/cars"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Autos</button></a></p>
    </div>
    <div class="col-md-4">
        <h4>Jobs</h4>
        <a href="$siteUrl/jobs"><img alt="micareerbuilder.com" src="img/partners/130-careerbuilder.gif"></a>
        <p><a class="button" href="$siteUrl/jobs"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Jobs</button></a></p>
    </div>
    <div class="col-md-4">
        <h4>Homes</h4>
        <a href="$siteUrl/homes"><img alt="homefinder.com" src="img/partners/130-homefinder.gif" ></a>
        <p><a class="button" href="$siteUrl/homes"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Homes</button></a></p>
    </div>
    
</div>
EOS;

include("../includes/master.php");
