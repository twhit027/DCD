<?php
include('../vendor/klogger/KLogger.php');
include('../vendor/Mobile_Detect/Mobile_Detect.php');
include('../conf/constants.php');
include('../includes/GCI/Database.php');
include('../includes/GCI/Site.php');
include('../includes/GCI/App.php');
include('../includes/GCI/Navigation.php');
include('../includes/GCI/Ads.php');

class SiteMap{
	function createSitemap($dataArray){
		$data = '';
		foreach ($dataArray as $placement => $positions) {
			$data .= '<h3>'.$placement.'</h3>';
			foreach ($positions as $position => $vals) {
				foreach($vals as $eURL => $ids){
					if($eURL == '1'){
						$data .='<strong><a class="" role="button" style="width:100%;margin-bottom:2px;" href="map.php?place='.urlencode($placement).'&posit='.urlencode($position).'" title="Title">'.$position.'</a></strong><br>';
					}
					else{
						$data .='<strong><a class="" role="button" style="width:100%;margin-bottom:2px;" href="category.php?place='.urlencode($placement).'&posit='.urlencode($position).'" title="Title">'.$position.'</a></strong><br>';
					}
					foreach($ids as $id){
						$data .='<a class="" role="button" style="width:100%;margin-bottom:2px;" href="item.php?id='.urlencode($id['id']).'" title="Title">'.$id['adText'].'</a><br>';
					}
				}
			}
		}
		return $data;
	}
	function createSitemapXML($dataArray){
		$server = $_SERVER['SERVER_NAME'];
		$data = "<?xml version='1.0' encoding='UTF-8'?>
<sitemapindex xmlns='http://www.sitemaps.org/schemas/sitemap/0.9' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd'>\r\n";
		foreach ($dataArray as $placement => $positions) {
			foreach ($positions as $position => $vals) {
				foreach($vals as $eURL => $ids){
					if($eURL == '1'){
						$data .="<sitemap><loc> http://".$server."/map.php?place=".urlencode($placement)."&amp;posit=".urlencode($position)."</loc></sitemap>\r\n";
					}
					else{
						$data .="<sitemap><loc> http://".$server."/category.php?place=".urlencode($placement)."&amp;posit=".urlencode($position)."</loc></sitemap>\r\n";
					}
					foreach($ids as $id){
						$data .="<sitemap><loc> http://".$server."/item.php?id=".urlencode($id["id"])."</loc></sitemap>\r\n";
					}
				}
			}
		}
		$data .= "</sitemapindex>";
		return $data;
	}
}

$app = new \GCI\App();

$app->logInfo('Sitemap Page(FORWARDED_FOR: '.@$_SERVER['HTTP_X_FORWARDED_FOR'].', REMOTE_ADDR: '.@$_SERVER['REMOTE_ADDR'].',HTTP_HOST: '.@$_SERVER['HTTP_HOST'].'SERVER_NAME: '.@$_SERVER['SERVER_NAME'].')');

$search = $app->getSearch();
$siteName = $app->getSite()->getSiteName();
$siteUrl = $app->getSite()->getSiteUrl();
$busName = $app->getSite()->getBusName();

$sm = new SiteMap();
$data = $app->getSitemap();
if(!empty($_GET['links']) && $_GET['links'] == '1'){
	$sitemap = $sm->createSitemap($data);
$mainContent = <<<EOS
<ol class="breadcrumb">
	<li><a href="./">Home</a></li>
	<li class="active">Sitemap</li>
</ol>
$sitemap
EOS;
	
	include("../includes/master.php");
}
else{
	$sitemap = $sm->createSitemapXML($data);
	header('Content-type: text/xml');
	print($sitemap);
}