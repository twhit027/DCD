<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 4/22/14
 * Time: 12:44 PM
 */

error_reporting(0);
set_time_limit(0);

include('../../vendor/klogger/KLogger.php');
include('../../vendor/Mobile_Detect/Mobile_Detect.php');
include('../../conf/constants.php');
include('../../includes/GCI/Database.php');
include('../../includes/GCI/Site.php');
include('../../includes/GCI/App.php');
include('../../includes/GCI/Navigation.php');
include('../../includes/GCI/Ads.php');

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

$siteId = '';
$noCache = false;
$write = false;
$getAllSites = false;

if (isset($_REQUEST['siteid'])) {
    $siteId = $_REQUEST['siteid'];
}
if (isset($_REQUEST['nocache']) && ($_REQUEST['nocache'] == '1')) {
    $noCache = true;
}
if (isset($_REQUEST['write']) && ($_REQUEST['write'] == '1')) {
    $write = true;
}
if (isset($_REQUEST['getallsites']) && ($_REQUEST['getallsites'] == '1')) {
    $getAllSites = true;
}

$app = new \GCI\App($siteId);

if ($getAllSites) {
    $sitesArray = $app->getAllSite();
} else {
    $sitesArray[1]['SiteCode'] = $app->getSite()->getSiteCode();
    $sitesArray[1]['TopLinks'] = $app->getSite()->getTopLinks();
    $sitesArray[1]['BottomLinks'] = $app->getSite()->getBottomLinks();
    $sitesArray[1]['SiteUrl'] = $app->getSite()->getSiteUrl();
    $sitesArray[1]['Palette'] = $app->getSite()->getPalette();
    $sitesArray[1]['SiteName'] = $app->getSite()->getSiteName();
}

foreach($sitesArray as $site) {
    $siteCode = $site['SiteCode'];
    $topLinks = $site['TopLinks'];
    $bottomLinks = $site['BottomLinks'];
    $siteUrl = $site['SiteUrl'];
    $palette = $site['Palette'];
    $siteName = $site['SiteName'];
    $foundPalette = '';
    $saxo = false;

    $siteImage = "http://www.gannett-cdn.com/sites/$siteName/images/site-nav-logo@2x.png";

    if ($noCache) {
        $prestoHeaderUrl = rtrim($siteUrl,'/') . '/services/cobrand/header/';
        //$prestoFooterUrl = rtrim($siteUrl,'/') . '/services/cobrand/footer/';
        $saxoUrl = rtrim($siteUrl, '/') . '/section/cobrandheaderlite/';

        //simplehtmldom
        require_once '../../vendor/simplehtmldom/simple_html_dom.php';

        $topLinks = array();
        $bottomLinks = array();

        if (url_exists($prestoHeaderUrl)) {
            if ($html = @file_get_html($prestoHeaderUrl)) {
                // Find all links
                foreach ($html->find('a.site-nav-link') as $element) {
                    if (trim($element->plaintext) != '') {
                        $topLinks[trim($element->plaintext)] = $element->href;
                    }
                }
            }
        } elseif (url_exists($saxoUrl)) {
            if ($html = @file_get_html($saxoUrl)) {
                $saxo = true;
                //<link rel="stylesheet" type="text/css" href="//archive.indystar.com/odygci/p1/ody-styles-min.css"/>
                foreach ($html->find('link') as $element) {
                    if (preg_match('#odygci/p(\d+)/ody-#', $element->href, $matches)) {
                        $foundPalette = $matches[1];
                    }
                }

                foreach ($html->find('div.ody-cobrandLinksLite li a') as $element) {
                    $topLinks[trim($element->plaintext)] = $element->href;
                }
                foreach ($html->find('div.ody-footLite li a') as $element) {
                    $bottomLinks[trim($element->plaintext)] = $element->href;
                }
            }
        }

        if ($write) {
            if (!empty($topLinks)) {
                $app->setTopLinks($siteCode, json_encode($topLinks));
            }
            if (!empty($bottomLinks)) {
                $app->setBottomLinks($siteCode, json_encode($bottomLinks));
            }
        }
    }

    $newData[$siteCode]['siteUrl'] = $siteUrl;
    $newData[$siteCode]['siteImage'] = $siteImage;
    $newData[$siteCode]['topLinks'] = $topLinks;
    $newData[$siteCode]['bottomLinks'] = $bottomLinks;

    if ($noCache) {
        if (!empty($foundPalette) && ($foundPalette != $palette)) {
            $newData[$siteCode]['saxo']['error']['setPalette'] = $palette;
            $newData[$siteCode]['saxo']['error']['foundPalette'] = $foundPalette;
        }
        if (!$saxo && ($palette < 90)) {
            $newData[$siteCode]['saxo']['error']['nonSaxoPalette'] = $palette;
        } elseif ($saxo && ($palette > 89)) {
            $newData[$siteCode]['saxo']['error']['nonGDPPalette'] = $palette;
        }
    }
    if ($palette < 90) {
        $newData[$siteCode]['saxo']['top'] = \GCI\site::$paletteArray[$palette]['top'];
        $newData[$siteCode]['saxo']['bottom'] = \GCI\site::$paletteArray[$palette]['bottom'];
        $newData[$siteCode]['saxo']['border'] = \GCI\site::$paletteArray[$palette]['border'];
    }
}

$json = json_encode($newData);

$jsonp_callback = isset($_GET['callback']) ? $_GET['callback'] : null;

header('Content-Type: application/json');
echo $jsonp_callback ? "$jsonp_callback($json)" : $json;



