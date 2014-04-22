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

$app->logInfo('Item Page(ID: '.urldecode($_REQUEST['id']).' FORWARDED_FOR: '.@$_SERVER['HTTP_X_FORWARDED_FOR'].', REMOTE_ADDR: '.@$_SERVER['REMOTE_ADDR'].',HTTP_HOST: '.@$_SERVER['HTTP_HOST'].'SERVER_NAME: '.@$_SERVER['SERVER_NAME'].')');

$id = urldecode($_REQUEST['id']);
$placement = urldecode($_REQUEST['place']);
$position = urldecode($_REQUEST['posit']);

$listings = $app->getSingleListing($id);

$cleanAdText = strip_tags($listings['adText']);
$siteCode = $listings['position'];
$placement = $listings['placement'];
$position = $listings['position'];
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

$data = " <div class='jumbotron' ><p>" . $cleanAdText . "</p>";

if (count($imageArray)>0) {
    if (!empty($dataInfo)) $dataInfo .= "&nbsp;|&nbsp;";
    $imgCnt = 0;
    $data .= '<p>';
    foreach($imageArray as $imgSrc) {
        $data .= '<a class="fancybox" href="images/'.$siteCode.'/'.$imgSrc.'" style="color:#FFA500;" rel="ligthbox 1_group"><img src="images/'.$siteCode.'/'.$imgSrc.'" class="img-responsive" alt="image '.$imgSrc.'"></a>';
    }
    $data .= '</p>';
}

$data .= '<a class="btn btn-primary" href="http://twitter.com/home?status=' . substr($cleanAdText, 0, 120) . '" target="_blank"><img src="img/twitter1.png" /></a>';
$data .= '<a class="btn btn-primary" href="https://www.facebook.com/sharer/sharer.php?u=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $listings['id'] . '" target="_blank"><img src="img/facebook2.png" /></a>';
$data .= '<a class="btn btn-primary" href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $listings['id'] . '" target="_blank"><img src="img/google-plus2.png" /></a>';
$data .= '<a class="btn btn-primary" href="mailto:emailaddress?subject='.substr($cleanAdText, 0, 80).'&body='.substr($cleanAdText, 0, 120).'%0D%0A%0D%0A http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $listings['id'] .'" target="_top"><img src="img/email2.png" /></a>';
$data .= "</div>";

$mainContent = <<<EOS
            <input type="hidden" id="place" name="place" value="$placement">
            <input type="hidden" id="posit" name="posit" value="$position">
                <ol class="breadcrumb">
                <li><a href="./">Home</a></li>
                <li><a href="./category.php?place=$placement&posit=$position">Category</a></li>
                <li class="active">Item</li>
            </ol>

            <br />$data
EOS;

include("../includes/master.php");
