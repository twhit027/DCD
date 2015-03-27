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

$app->logInfo('Category Page');
$app->logInfo('FORWARDED_FOR: ' . @$_SERVER['HTTP_X_FORWARDED_FOR']);
$app->logInfo('REMOTE_ADDR: ' . @$_SERVER['REMOTE_ADDR']);
$app->logInfo('HTTP_HOST: ' . @$_SERVER['HTTP_HOST']);
$app->logInfo('SERVER_NAME: ' . @$_SERVER['SERVER_NAME']);



$data = $app->getSearch();



$mainContent = <<<EOS
                <ol class="breadcrumb">
                <li><a href="./">Home</a></li>
                <li class="active">Search</li>
            </ol>

            <br />$data
EOS;

include("../includes/master.php");



		
		