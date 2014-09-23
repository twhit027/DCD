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
	$rummageList .= '<a href="mailto:?subject='. str_replace("&","%26",substr($rummages[$showcase]["adText"], 0, 80)) .'&body='. str_replace("&","%26",substr($rummages[$showcase]["adText"], 0, 120)) .'%0D%0A%0D%0A http://' . $_SERVER['SERVER_NAME'] . '/map.php?place='.urlencode($place).'%26posit='.urlencode($position).'%26ad='.$showcase.'" target="_top" id="'.$showcase.'-gs-mail"><img src="img/social-email-16.png" /></span></a>';
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
	$rummageList .= '<a href="mailto:?subject='. str_replace("&","%26",substr($v["adText"], 0, 80)) .'&body='. str_replace("&","%26",substr($v["adText"], 0, 120)) .'%0D%0A%0D%0A http://' . $_SERVER['SERVER_NAME'] . '/map.php?place='.urlencode($place).'%26posit='.urlencode($position).'%26ad=' . $k .'" target="_top" id="'.$k.'-gs-mail"><img src="img/social-email-16.png" /></span></a>';
	$rummageList .= "</td>
	</tr>
	";
	$filter['city'][strtoupper($v['city'])] = true;
	$filter['sites'][$v['siteCode']] = strtoupper($v['siteName']);
}

$cOptions = "";
if(count($filter['city']) > 1){
	$cOptions .= '<div class="dropdown pull-left">';
	$cOptions .= '<button title="Add Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuCity" data-toggle="dropdown">';
	$cOptions .= '<strong>Filter:</strong> City <span class="caret"></span></button>';
	$cOptions .= '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuCity">';
	foreach($filter['city'] as $k=>$v){
		$cOptions .= '<li role="presentation"><a role="menuitem" tabindex="-1" onClick="setGetParameter(\'city\', \'' . $k . '\')" href="javascript:void(0)">' . $k . '</a></li>';
	}
	$cOptions .= '</ul></div>';
}
$sOptions = "";
if(count($filter['sites']) > 1){
	$sOptions .= '<div class="dropdown pull-left">';
	$sOptions .= '<button title="Add Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuPaper" data-toggle="dropdown">';
	$sOptions .= '<strong>Filter:</strong> Paper <span class="caret"></span></button>';
	$sOptions .= '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuPaper">';
	foreach($filter['sites'] as $k=>$v){
		$sOptions .= '<li role="presentation"><a role="menuitem" tabindex="-1" onClick="setGetParameter(\'paper\', \'' . $k . '\')" href="javascript:void(0)">' . $v . '</a></li>';
	}
	$sOptions .= '</ul></div>';
}
$filterForm = "";
if(!empty($cOptions) || !empty($sOptions)){
	if(!empty($cOptions)){
		$filterForm .= $cOptions;
	}
	if(!empty($sOptions)){
		$filterForm .= $sOptions;
	}
}
if(!empty($_GET['city']) || !empty($_GET['paper'])){
	if(!empty($_GET['city'])){
        $filterForm .= '<div class="pull-left">';
        $filterForm .= '<button title="Remove Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuCity" data-toggle="dropdown" onClick="removeSitesAndReloadPage(\'city\')" href="javascript:void(0)">';
        $filterForm .= '<span class="glyphicon glyphicon-remove-circle" style="color:#d43f3a;"></span><strong> Filter:</strong> City </button></div>';
	}
	if(!empty($_GET['paper'])){
        $filterForm .= '<div class="pull-left">';
        $filterForm .= '<button title="Remove Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuPaper" data-toggle="dropdown" onClick="removeSitesAndReloadPage(\'paper\')" href="javascript:void(0)">';
        $filterForm .= '<span class="glyphicon glyphicon-remove-circle" style="color:#d43f3a;"></span><strong> Filter:</strong> Paper </button></div>';
	}
}
$filterForm .= "<br>";

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
