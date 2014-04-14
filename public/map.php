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

$place = $_GET['place'];
$position = $_GET['posit'];
$listOfRummages = $app->getRummages($place,$position);
if(isset($_GET['ad']) && !empty($_GET['ad'])) {
    $showcase = $_GET['ad'];
	$listOfRummages['map'][$showcase]['showcase'] = true;
}
$mapPoints = json_encode($listOfRummages['map']);
$rummages = $listOfRummages['list'];
$rummageList = '';
if(!empty($showcase) && !empty($rummages[$showcase])){
	$rummageList .= "
	<tr id='dcd-showcase'>
		<td><input type='button' value='Add' onclick=\"visit(this,'".$showcase."');\" class='add btn btn-default' /></td>
		<td>".$rummages[$showcase]["adText"]."</td>
	</tr>
	";
	unset($rummages[$showcase]);
}
foreach($rummages as $k=>$v){
	$rummageList .= "
	<tr>
		<td><input type='button' value='Add' onclick=\"visit(this,'".$k."');\" class='add btn btn-default' /></td>
		<td>".$v["adText"]."</td>
	</tr>
	";
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
<div id="map">
	<div id="dcd-map-container"></div>
</div>
<br>
<form action="route.php" method="post" onsubmit="mapRoute();" class="form-horizontal" role="form">
	<input type="hidden" name="place" value="$place" />
	<input type="hidden" name="posit" value="$position" />
	<input type="hidden" id="locations" name="locations" value="" />
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
				<div class="checkbox">
					<label>
						<input type="checkbox" value="true" name="avoidHighways"> Avoid Highways
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="true" name="avoidTolls"> Avoid Tolls
					</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">Map Route</button>
			</div>
		</div>
	</div>
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
