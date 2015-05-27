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

//$search = $app->getSearch();
$siteName = $app->getSite()->getSiteName();
$siteUrl = $app->getSite()->getSiteUrl();
$busName = $app->getSite()->getBusName();
$siteLinks = $app->getSite()->getSiteLinks();

$metadata = '
<title>'.$busName.' - Classifieds Landing Page</title>
<meta name="description" content="Classifieds Landing page for '.$busName.'" />
<meta itemprop="name" content="Classifieds Landing page">
<meta itemprop="description" content="Classifieds Landing page for '.$busName.'">';

$thirdPartyPlace = isset($siteLinks['place']) ? $siteLinks['place'] : 'http://'.$siteName.'.gannettclassifieds.com';
$thirdPartyHelp = isset($siteLinks['help']) ? $siteLinks['help'] : $siteUrl.'/classifiedshelp';
$thirdPartyCars = isset($siteLinks['cars']) ? $siteLinks['cars'] : $siteUrl.'/cars';
$thirdPartyJobs = isset($siteLinks['jobs']) ? $siteLinks['jobs'] : $siteUrl.'/jobs';
$thirdPartyHomes = isset($siteLinks['homes']) ? $siteLinks['homes'] : $siteUrl.'/homes';

$cars = $jobs = $homes = $linkRow = $placeRow = $helpRow = '';

if ($thirdPartyCars) {
    $cars = '<h4>Cars</h4><a href="'.$thirdPartyCars.'"><img alt="Cars.com" src="img/partners/130-cars.gif"></a><p><a class="button" href="'.$thirdPartyCars.'"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Autos</button></a></p>';
}

if ($thirdPartyJobs) {
    $jobs = '<h4>Jobs</h4><a href="'.$thirdPartyJobs.'"><img alt="careerbuilder.com" src="img/partners/130-careerbuilder.gif"></a><p><a class="button" href="'.$thirdPartyJobs.'"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Jobs</button></a></p>';
}

if ($thirdPartyHomes) {
    $homes = '<h4>Homes</h4><a href="'.$thirdPartyHomes.'"><img alt="homefinder.com" src="img/partners/130-homefinder.gif" ></a><p><a class="button" href="'.$thirdPartyHomes.'"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Homes</button></a></p>';
}

if ($thirdPartyCars || $thirdPartyJobs || $thirdPartyHomes) {
    $linkRow = '<h1>Featured Partner Classified Services</h1><div class="row"><div class="col-md-4">'.$cars.'</div><div class="col-md-4">'.$jobs.'</div><div class="col-md-4">'.$homes.'</div></div>';
}

if ($thirdPartyPlace) {
    $placeRow = '<p><a class="button" href="'.$thirdPartyPlace.'"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">Place an Ad</button></a></p>';
}

if ($thirdPartyHelp) {
    $helpRow = '<p><a class="button" href="'.$thirdPartyHelp.'" target="_blank"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">Classifieds Help</button></a></p>';
}

$mainContent = <<<EOS
<h1>$busName Classifieds</h1>
<h2>Introducing our new online system</h2>

<p>Now it’s easier than ever to place an ad and find what you’re looking for—24 hours a day, seven days a week.</p>
<p>In just a few clicks, you can place your ads online, in print or both. And with improved ad displays, your ad is sure to get noticed!</p>
<p>From vehicles to pets to garage sales to services, we provide the most effective ways to sell to potential local
buyers through our leading mobile, online and print solutions.</p>
$placeRow
$helpRow
$linkRow
EOS;

include("../includes/master.php");
