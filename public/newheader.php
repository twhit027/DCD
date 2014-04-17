<?php
/**
 * Created by PhpStorm.
 * User: JHICKS
 * Date: 4/15/14
 * Time: 1:27 PM
 */

/*
 * Legacy cobrand lite header cached at Gannett digital:
 * http://indystar.gannettdigital.com/LI-header.html
 *
 * Presto cobrand service:
 * http://www.indystar.com/services/cobrand/header/
 * http://www.indystar.com/services/cobrand/footer/
 *
 * Legacy cobrand header and footer lite from archive saxo site:
 * http://archive.indystar.com/section/cobrandheaderlite
 * http://www.indystar.com/section/cobrandheaderlite
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
$siteUrl = $app->getSite()->getSiteUrl();
$prestoUrl = $siteUrl.'/services/cobrand/header/';
$saxoUrl = $siteUrl.'/section/cobrandheaderlite/';

echo "URL:" . $prestoUrl. "<br />";

//simplehtmldom
require_once '../vendor/simplehtmldom/simple_html_dom.php';

// Create DOM from URL or file
//$html = file_get_html('http://www.google.com/');

$siteImage = $siteUrl.'/graphics/ody/cobrand_logo.gif';
$siteLinks = array();

if ($html = @file_get_html($prestoUrl)) {
    foreach($html->find('img.site-nav-logo-img') as $element) {
        $siteImage = $element->src;
    }

// Find all links
    foreach($html->find('a.site-nav-link') as $element) {
        if (trim($element->plaintext) != '') {
            $siteLinks[trim($element->plaintext)] = $element->href;
        }
    }
} elseif ($html = @file_get_html($saxoUrl)) {
    foreach($html->find('div.ody-cobrandLinksLite li a') as $element) {
        $siteLinks[trim($element->plaintext)] = $element->href;
    }
}

print_r($siteLinks);

$data = '<nav id="grad" role="navigation" class="collapse navbar-collapse bs-navbar-collapse top-navbar"><ul class="nav navbar-nav">';
$data .= '<li><a style="margin:0;padding:0;" href="'.$siteUrl.'/"><img style="height:50;" class="img-responsive" src="'.$siteImage.'"/></a></li>';
foreach ($siteLinks as $linkName => $linkHref) {
    $data .= '<li><a href="'.$linkHref.'">'.$linkName.'</a></li>';
}
$data .= '</ul></nav>';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<style type="text/css">
    body {min-width: 10px !important;}
    .gallery{display: inline-block;margin-top: 20px;}
</style>

<link href="3rdParty/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="3rdParty/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
</head>
<body>
<header role="banner" class="navbar navbar-inverse navbar-fixed-top bs-docs-nav">
    <div class="container">
<?php echo $data; ?>
    </div>
</header>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="3rdParty/bootstrap/js/bootstrap.min.js"></script>
<script src="3rdParty/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<script>



