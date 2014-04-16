<?php
/**
 * Created by PhpStorm.
 * User: JHICKS
 * Date: 4/15/14
 * Time: 1:27 PM
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
$url = $siteUrl.'/services/cobrand/header/';

//goutte
//require_once '../vendor/goutte/goutte.phar';

//use Goutte\Client;

//$client = new Client();

//$crawler = $client->request('GET', $url);

//simplehtmldom
require_once '../vendor/simplehtmldom/simple_html_dom.php';

// Create DOM from URL or file
//$html = file_get_html('http://www.google.com/');

$html = file_get_html('http://www.desmoinesregister.com/services/cobrand/header/');

// Find all links
foreach($html->find('a') as $element) {
    echo $element->plaintext .': '. $element->href . '<br />';
}

//straight
$doc = new DOMDocument();
$load = $doc->loadHTMLFile($url);
$elements = $doc->getElementsByTagName('a');
print_r($elements);
if (!is_null($elements)) {
    foreach ($elements as $element) {
        echo "<br/>". $element->nodeName. ": ";

        $nodes = $element->childNodes;
        foreach ($nodes as $node) {
            echo $node->nodeValue. "<br />";
        }
    }
}



