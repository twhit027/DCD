<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 5/7/14
 * Time: 10:09 AM
 */

//error_reporting(0);

include(__DIR__ . '/../vendor/klogger/KLogger.php');
include(__DIR__ . '/../vendor/Mobile_Detect/Mobile_Detect.php');
include(__DIR__ . '/../conf/constants.php');
include(__DIR__ . '/../includes/GCI/Database.php');
include(__DIR__ . '/../includes/GCI/Site.php');
include(__DIR__ . '/../includes/GCI/App.php');
include(__DIR__ . '/../includes/GCI/Navigation.php');
include(__DIR__ . '/../includes/GCI/Ads.php');

$options = getopt("s:nw");

var_dump($options);

$siteId = $options['s'];
$noCache = isset($options['n']) ? true : false;
$write = isset($options['w']) ? true : false;

function url_exists($url){
    if ((strpos($url, "http")) === false) $url = "http://" . $url;
    $headers = @get_headers($url);
    if (is_array($headers)){
        if(strpos($headers[0], '404')) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

$app = new \GCI\App($siteId);

$siteCode = $app->getSite()->getSiteCode();
$siteLinks = $app->getSite()->getTopLinks();
//$bottomLinks = $app->getSite()->getBottomLinks();
$siteUrl = $app->getSite()->getSiteUrl();
$palette = $app->getSite()->getPalette();
$siteName = $app->getSite()->getSiteName();
//$siteImage = $siteUrl.'/graphics/ody/cobrand_logo.gif';
//$siteImage = $siteUrl . '/graphics/ody/mast_logo.gif';
$siteImage = "http://www.gannett-cdn.com/sites/$siteName/images/site-nav-logo@2x.png";

if ($noCache) {
    $prestoUrl = rtrim($siteUrl,'/') . '/services/cobrand/header/';
    $saxoUrl = rtrim($siteUrl, '/') . '/section/cobrandheaderlite/';

    //simplehtmldom
    require_once __DIR__ . '/../vendor/simplehtmldom/simple_html_dom.php';

    $siteLinks = array();
    $data = '';

    if (url_exists($prestoUrl)) {
        if ($html = @file_get_html($prestoUrl)) {
            // Find all links
            foreach ($html->find('a.site-nav-link') as $element) {
                if (trim($element->plaintext) != '') {
                    $siteLinks[trim($element->plaintext)] = $element->href;
                }
            }
        }
    } elseif (url_exists($saxoUrl)) {
        if ($html = @file_get_html($saxoUrl)) {
            $saxo = true;
            foreach ($html->find('div.ody-cobrandLinksLite li a') as $element) {
                $siteLinks[trim($element->plaintext)] = $element->href;
            }
        }
    }

    if (!empty($siteLinks)) {
        $data = json_encode($siteLinks);

        if ($write) {
            $app->setTopLinks($siteCode, $data);
        }
    }
}

$newData['siteUrl'] = $siteUrl;
$newData['siteImage'] = $siteImage;
$newData['sitelinks'] = $siteLinks;
if ($palette < 90) {
    $newData['saxo']['top'] = \GCI\site::$paletteArray[$palette]['top'];
    $newData['saxo']['bottom'] = \GCI\site::$paletteArray[$palette]['bottom'];
    $newData['saxo']['border'] = \GCI\site::$paletteArray[$palette]['border'];
}

$json = json_encode($newData);

$jsonp_callback = isset($_GET['callback']) ? $_GET['callback'] : null;

//header('Content-Type: application/json');
echo $jsonp_callback ? "$jsonp_callback($json)" : $json;
