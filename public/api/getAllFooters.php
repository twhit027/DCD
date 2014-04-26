<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 4/24/14
 * Time: 1:32 PM
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

$app = new \GCI\App();

$sitesArray = $app->getAllSite();

foreach($sitesArray as $site) {
    $siteCode = $site['SiteCode'];
    $siteLinks = $site['TopLinks'];
    $siteUrl = $site['SiteUrl'];
    $palette = $site['Palette'];
    $siteName = $site['SiteName'];
    $foundPalette = '';
    $saxo = false;
    $noData = false;

    $siteImage = "http://www.gannett-cdn.com/sites/$siteName/images/site-nav-logo@2x.png";

    if (isset($_REQUEST['nocache']) && ($_REQUEST['nocache'] == '1')) {
        $prestoUrl = rtrim($siteUrl,'/') . '/services/cobrand/footer/';
        $saxoUrl = rtrim($siteUrl, '/') . '/section/cobrandheaderlite/';

        //simplehtmldom
        require_once '../../vendor/simplehtmldom/simple_html_dom.php';

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
                foreach ($html->find('div.ody-footLite li a') as $element) {
                    $siteLinks[trim($element->plaintext)] = $element->href;
                }
            }
        }

        if (!empty($siteLinks)) {
            $data = json_encode($siteLinks);

            if (isset($_REQUEST['write']) && ($_REQUEST['write'] == 'True')) {
                $app->setBottomLinks($siteCode, $data);
            }
        }
    }

    $newData[$siteCode]['siteUrl'] = $siteUrl;
    $newData[$siteCode]['siteImage'] = $siteImage;
    $newData[$siteCode]['sitelinks'] = $siteLinks;
    if ($noData) {
        $newData[$siteCode]['error'] = $siteLinks;
    }
    if (isset($_REQUEST['nocache']) && ($_REQUEST['nocache'] == '1')) {
        if (!empty($foundPalette) && ($foundPalette != $palette)) {
            $newData[$siteCode]['saxo']['error']['palette'] = $palette;
            $newData[$siteCode]['saxo']['error']['foundPalette'] = $foundPalette;
        }
        if (!$saxo && ($palette < 90)) {
            $newData[$siteCode]['saxo']['error']['text'] = 'not saxo';
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
