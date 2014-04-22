<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 4/22/14
 * Time: 12:44 PM
 */

error_reporting(0);

include('../../vendor/klogger/KLogger.php');
include('../../vendor/Mobile_Detect/Mobile_Detect.php');
include('../../conf/constants.php');
include('../../includes/GCI/Database.php');
include('../../includes/GCI/Site.php');
include('../../includes/GCI/App.php');
include('../../includes/GCI/Navigation.php');
include('../../includes/GCI/Ads.php');

$app = new \GCI\App();
$siteCode = $app->getSite()->getSiteCode();

$data = @file_get_contents("../images/$siteCode/top.json");

if (!empty($_REQUEST['nocache'])) {
    $data = false;
}

if ($data === false) {
    $siteUrl = $app->getSite()->getSiteUrl();
    $palette = $app->getSite()->getPalette();
    $top = \GCI\site::$paletteArray[$palette]['top'];
    $bottom = \GCI\site::$paletteArray[$palette]['bottom'];
    $border = \GCI\site::$paletteArray[$palette]['border'];

    $prestoUrl = $siteUrl . '/services/cobrand/header/';
    $saxoUrl = $siteUrl . '/section/cobrandheaderlite/';

    //simplehtmldom
    require_once '../../vendor/simplehtmldom/simple_html_dom.php';

    // Create DOM from URL or file
    //$html = file_get_html('http://www.google.com/');

    //$siteImage = $siteUrl.'/graphics/ody/cobrand_logo.gif';
    $siteImage = $siteUrl . '/graphics/ody/mast_logo.gif"';

    $siteLinks = array();
    $data = '';

    if ($html = @file_get_html($prestoUrl)) {
        foreach ($html->find('img.site-nav-logo-img') as $element) {
            $siteImage = $element->src;
        }

        // Find all links
        foreach ($html->find('a.site-nav-link') as $element) {
            if (trim($element->plaintext) != '') {
                $siteLinks[trim($element->plaintext)] = $element->href;
            }
        }
    } elseif ($html = @file_get_html($saxoUrl)) {
        $data['saxo']['top'] = $top;
        $data['saxo']['bottom'] = $bottom;
        $data['saxo']['border'] = $border;

        foreach ($html->find('div.ody-cobrandLinksLite li a') as $element) {
            $siteLinks[trim($element->plaintext)] = $element->href;
        }
    }

    $data['siteUrl'] = $siteUrl;
    $data['siteImage'] = $siteImage;
    $data['siteLinks'] = $siteLinks;

    $data = json_encode($data);

    @file_put_contents("../images/$siteCode/top.json", $data );
}

$json = $data;

$jsonp_callback = isset($_GET['callback']) ? $_GET['callback'] : null;

header('Content-Type: application/json');
echo $jsonp_callback ? "$jsonp_callback($json)" : $json;
?>