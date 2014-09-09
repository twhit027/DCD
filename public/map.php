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

$app->logInfo('Map Page(FORWARDED_FOR: '.@$_SERVER['HTTP_X_FORWARDED_FOR'].', REMOTE_ADDR: '.@$_SERVER['REMOTE_ADDR'].',HTTP_HOST: '.@$_SERVER['HTTP_HOST'].'SERVER_NAME: '.@$_SERVER['SERVER_NAME'].')');

$place = $position = $city = $paper = '';

if (isset($_REQUEST['place'])) {
    $place = urldecode($_REQUEST['place']);
}
if (isset($_REQUEST['posit'])) {
    $position = urldecode($_REQUEST['posit']);
}
if (isset($_REQUEST['city'])) {
    $city = urldecode($_REQUEST['city']);
}
if (isset($_REQUEST['paper'])) {
    $paper = urldecode($_REQUEST['paper']);
}

$listOfRummages = $app->getRummages($place,$position,'','',$city,$paper);
if(isset($_GET['ad']) && !empty($_GET['ad'])) {
    $showcase = $_GET['ad'];
	$listOfRummages['map'][$showcase]['showcase'] = true;
}
$mapPoints = json_encode($listOfRummages['map']);
$mapArray = $listOfRummages['map'];
$rummages = $listOfRummages['list'];
$rummageList = '';
$filter = array();
if(!empty($showcase) && !empty($rummages[$showcase])){
	$rummageList .= "
	<tr id='dcd-showcase'>
		<td><input type='button' value='Add' onclick=\"visit(this,'".$showcase."');\" class='add btn btn-default' id='".$showcase."' /></td>
		<td class='dcd-adText' dcd-id='". $showcase."'>".$rummages[$showcase]["adText"]."<br />";
	$rummageList .= '<a href="http://twitter.com/home?status=' . str_replace("&","%26",substr($rummages[$showcase]["adText"], 0, 120)) . '" target="_blank"><img src="img/twitter-16.png" /></a>&nbsp';
	$rummageList .= '<a href="https://www.facebook.com/sharer/sharer.php?u=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $showcase . '" target="_blank"><img src="img/facebook-16.png" /></a>&nbsp';
	$rummageList .= '<a href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $showcase . '" target="_blank"><img src="img/google-plus-16.png" /></a>&nbsp';
	$rummageList .= '<a href="mailto:?subject='. str_replace("&","%26",substr($rummages[$showcase]["adText"], 0, 80)) .'&body='. str_replace("&","%26",substr($rummages[$showcase]["adText"], 0, 120)) .'%0D%0A%0D%0A http://' . $_SERVER['SERVER_NAME'] . '/map.php?place='.urlencode($place).'%26posit='.urlencode($position).'%26ad='.$showcase.'" target="_top"><img src="img/social-email-16.png" /></span></a>';
	$rummageList .= "</td>
	</tr>
	";
	$filter['city'][$rummages[$showcase]['city']] = true;
	$filter['sites'][$rummages[$showcase]['siteCode']] = true;
	unset($rummages[$showcase]);
}
foreach($rummages as $k=>$v){
	$rummageList .= "<tr><td><input type='button' value='Add' onclick=\"visit(this,'".$k."');\" class='add btn btn-default' id='".$k."'";
    if (! isset($mapArray[$k])) {
        $rummageList .= "disabled='disabled'";
    }
    $rummageList .=" /></td><td class='dcd-adText' dcd-id='". $k."'>".$v["adText"]."<br />";
	$rummageList .= '<a href="http://twitter.com/home?status=' . str_replace("&","%26",substr($v["adText"], 0, 120)) . '" target="_blank"><img src="img/twitter-16.png" /></a>&nbsp';
	$rummageList .= '<a href="https://www.facebook.com/sharer/sharer.php?u=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $k . '" target="_blank"><img src="img/facebook-16.png" /></a>&nbsp';
	$rummageList .= '<a href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $k . '" target="_blank"><img src="img/google-plus-16.png" /></a>&nbsp';
	$rummageList .= '<a href="mailto:?subject='. str_replace("&","%26",substr($v["adText"], 0, 80)) .'&body='. str_replace("&","%26",substr($v["adText"], 0, 120)) .'%0D%0A%0D%0A http://' . $_SERVER['SERVER_NAME'] . '/map.php?place='.urlencode($place).'%26posit='.urlencode($position).'%26ad=' . $k .'" target="_top"><img src="img/social-email-16.png" /></span></a>';
	$rummageList .= "</td>
	</tr>
	";
	$filter['city'][$v['city']] = true;
	$filter['sites'][$v['siteCode']] = $v['siteName'];
}

$cOptions = "";
if(count($filter['city']) > 1){
	$cOptions = '<select name="city" class="form-control">';
	$cOptions .= '<option value="">All</option>';
	foreach($filter['city'] as $k=>$v){
		$cOptions .= '<option value="'.$k.'">'.$k.'</option>';
	}
	$cOptions .= '</select>';
}
$sOptions = "";
if(count($filter['sites']) > 1){
	$sOptions = '<select name="paper" class="form-control">';
	$sOptions .= '<option value="">All</option>';
	foreach($filter['sites'] as $k=>$v){
		$sOptions .= '<option value="'.$k.'">'.$v.'</option>';
	}
	$sOptions .= '</select>';
}
$filterForm = "";
if(!empty($cOptions) || !empty($sOptions)){
	$filterForm = '<form method="get" action="'.$_SERVER['SCRIPT_NAME'].'" class="form-inline" role="form">';
	$filterForm .= '<input type="hidden" name="place" value="'.$_GET['place'].'">';
	$filterForm .= '<input type="hidden" name="posit" value="'.$_GET['posit'].'">';
	if(!empty($cOptions))
		$filterForm .= $cOptions;
	elseif(!empty($_GET['city']))
		$filterForm .= '<input type="hidden" name="city" value="'.$_GET['city'].'">';
	if(!empty($sOptions))
		$filterForm .= $sOptions;
	elseif(!empty($_GET['paper']))
		$filterForm .= '<input type="hidden" name="paper" value="'.$_GET['paper'].'">';
	$filterForm .= '<input type="submit" value="Filter" class="btn btn-default"></form>';
}

$masterBottom = '<script src="js/rummage.js"></script>';

$googleApiScript = <<<EOS
	<link rel="stylesheet" href="css/rummage.css">
    <!-- Google Maps API V3 -->
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <!-- Google Maps API V3 -->
    <script type="text/javascript">
        //setup global namespace
        var DCDMAPGLOBAL = {};
        DCDMAPGLOBAL.points = $mapPoints;
    </script>
EOS;

$data = <<<EOS
<div id="map-options">
	$filterForm
	<ul id="map-resize">
		<li><strong>Map Size:</strong></li>
		<li><a href="#" data-size="small">Small</a></li>
		<li><a href="#" data-size="medium">Medium</a></li>
		<li><a href="#" data-size="large">Large</a></li>
	</ul>
	<div class="clear"></div>
</div>
<div id="map">
	<div id="dcd-map-container"></div>
</div>
<br>
<form action="route.php" method="post" onsubmit="mapRoute();" class="form-horizontal" role="form">
	<input type="hidden" name="place" value="$place" />
	<input type="hidden" name="posit" value="$position" />
	<input type="hidden" id="locations" name="locations" value="" />
	<h4>Please enter a starting address and select up to 8 places to visit, then click on 'Map Route'.</h4>
	<div id="map-it">
		<div class="form-group">
			<label for="Address" class="col-sm-2 control-label">Address</label>
			<div class="col-sm-10">
				<input type="text" name="address" class="form-control" id="Address" placeholder="Address">
			</div>
		</div>
		<div class="form-group">
			<label for="City" class="col-sm-2 control-label">City</label>
			<div class="col-sm-10">
				<input type="text" name="city" class="form-control" id="City" placeholder="City">
			</div>
		</div>
		<div class="form-group">
			<label for="Zip" class="col-sm-2 control-label">Zip</label>
			<div class="col-sm-10">
				<input type="text" name="zip" class="form-control" id="Zip" placeholder="Zip">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="checkbox-inline">
					<label>
						<input type="checkbox" value="true" name="avoidHighways"> Avoid Highways
					</label>
				</div>
				<div class="checkbox-inline">
					<label>
						<input type="checkbox" value="true" name="avoidTolls"> Avoid Tolls
					</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" id="dcd-route" class="btn btn-default">Map Route</button>
			</div>
		</div>
	</div>
	<p><strong>Click or Tap on any entry to find on the map.</strong></p>
	<table class="table table-striped">
		$rummageList
	</table>
</form>
EOS;

$mainContent = <<<EOS
	<ol class="breadcrumb">
		<li><a href="./">Home</a></li>
		<li class="active">$place</li>
	</ol>
	
	<br />
	
	$data
EOS;

include("../includes/master.php");
