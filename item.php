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

$app->logInfo('Category Page');
$app->logInfo('FORWARDED_FOR: ' . @$_SERVER['HTTP_X_FORWARDED_FOR']);
$app->logInfo('REMOTE_ADDR: ' . @$_SERVER['REMOTE_ADDR']);
$app->logInfo('HTTP_HOST: ' . @$_SERVER['HTTP_HOST']);
$app->logInfo('SERVER_NAME: ' . @$_SERVER['SERVER_NAME']);

$id = urldecode($_REQUEST['id']);
$placement = urldecode($_REQUEST['place']);
$position = urldecode($_REQUEST['posit']);

$listings = $app->getSingleListing($id);

$data = " <div class='jumbotron' ><p>" . htmlspecialchars($listings['adText']) . "</p>";
$data .= '<a class="btn btn-primary" href="http://twitter.com/home?status=' . substr($listings['adText'], 0, 120) . '" target="_blank"><img src="img/twitter1.png" /></a>';
$data .= '<a class="btn btn-primary" href="https://www.facebook.com/sharer/sharer.php?u=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $row['id'] . '" target="_blank"><img src="img/facebook2.png" /></a>';
$data .= '<a class="btn btn-primary" href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $row['id'] . '" target="_blank"><img src="img/google-plus2.png" /></a>';
$data .= '<a class="btn btn-primary" href="mailto:youremailaddress" target="_blank"><img src="img/email2.png" /></a>';
$data .= "</div>";

$mainContent = <<<EOS
                <ol class="breadcrumb">
                <li><a href="./">Home</a></li>
                <li><a href="./category.php?place=$placement&posit=$position">Category</a></li>
                <li class="active">Item</li>
            </ol>

            <br />$data
EOS;

include("includes/master.php");
