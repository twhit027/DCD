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

$place = $position = $city = $paper = $day = '';

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
if (isset($_REQUEST['day'])) {
    $day = urldecode($_REQUEST['day']);
}

$busName = $app->getSite()->getBusName();

$metadata = '
<title>'.$busName.' Classifieds Listings</title>
<meta name="description" content="category listing page for '.$busName.'" />
<meta itemprop="name" content="category listing page">
<meta itemprop="description" content="category listing page for '.$busName.'">';

$dayArray = Array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday');
$dayAbrvArray = Array(1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun');

$listOfRummages = $app->getRummages($place,$position,'','',$city,$paper,$day);
if(isset($_GET['ad']) && !empty($_GET['ad'])) {
    $showcase = $_GET['ad'];
	$listOfRummages['map'][$showcase]['showcase'] = true;
}
$mapPoints = json_encode($listOfRummages['map']);
$mapArray = $listOfRummages['map'];
$rummages = $listOfRummages['list'];
$rummageList = '';
$rummageList1 = '';
//$filter = array();
$filter['days'] = array();
if(!empty($showcase) && !empty($rummages[$showcase])){
	$rummageList .= "<tr id='dcd-showcase'>
		<td><input type='button' value='Add' onclick=\"visit(this,'".$showcase."');\" class='add btn btn-default' id='".$showcase."' /></td>
		<td class='dcd-adText' dcd-id='". $showcase."'>".$rummages[$showcase]["adText"]."<br />";
	$rummageList .= '<a href="http://twitter.com/home?status=' . str_replace("&","%26",substr($rummages[$showcase]["adText"], 0, 120)) . '" target="_blank"><img src="img/twitter-16.png" /></a>&nbsp';
	$rummageList .= '<a href="https://www.facebook.com/sharer/sharer.php?u=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $showcase . '" target="_blank"><img src="img/facebook-16.png" /></a>&nbsp';
	$rummageList .= '<a href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $showcase . '" target="_blank"><img src="img/google-plus-16.png" /></a>&nbsp';
	$rummageList .= '<a href="mailto:?subject='. str_replace("&","%26",substr($rummages[$showcase]["adText"], 0, 80)) .'&body='. str_replace("&","%26",substr($rummages[$showcase]["adText"], 0, 120)) .'%0D%0A%0D%0A http://' . $_SERVER['SERVER_NAME'] . '/map.php?place='.urlencode($place).'%26posit='.urlencode($position).'%26ad='.$showcase.'" target="_top" id="'.$showcase.'-gs-mail"><img src="img/social-email-16.png" /></span></a>';
	$rummageList .= "</td></tr>";

	$filter['city'][$rummages[$showcase]['city']] = true;
	$filter['sites'][$rummages[$showcase]['siteCode']] = true;
	unset($rummages[$showcase]);
}
$picInt = 1;
foreach($rummages as $k=>$v) {
	$rummageList .= "<tr><td><input title='Add to Route' type='button' value='+' onclick=\"visit(this,'".$k."');\" class='add btn btn-default' id='".$k."'";
    if (! isset($mapArray[$k])) {
        $rummageList .= "disabled='disabled'";
    }

    $rummageList .= " />";
    $rummageList .= '<br /><br /><a style="padding:1px" href="http://twitter.com/home?status=' . str_replace("&","%26",substr($v["adText"], 0, 120)) . '" target="_blank"><img src="img/twitter-16.png" /></a><br />';
    $rummageList .= '<a style="padding:1px" href="https://www.facebook.com/sharer/sharer.php?u=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $k . '" target="_blank"><img src="img/facebook-16.png" /></a><br />';
    $rummageList .= '<a style="padding:1px" href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $k . '" target="_blank"><img src="img/google-plus-16.png" /></a><br />';
    $rummageList .= '<a style="padding:1px" href="mailto:?subject='. str_replace("&","%26",substr($v["adText"], 0, 80)) .'&body='. str_replace("&","%26",substr($v["adText"], 0, 120)) .'%0D%0A%0D%0A http://' . $_SERVER['SERVER_NAME'] . '/map.php?place='.urlencode($place).'%26posit='.urlencode($position).'%26ad=' . $k .'" target="_top" id="'.$k.'-gs-mail"><img src="img/social-email-16.png" /></span></a></td>';
    $rummageList .= '<td><img src="/DCD/images/INI/apt'.$picInt++.'-120x120.jpg" alt="http://classifieds-lc.indystar.com/DCD/images/INI/apt1-120x120.jpg" height="120" width="120"></td><td><table>';
    $rummageList .= "<tr><td style='font-weight: bold;color:blue; font-size: 150%' dcd-id='". $k."' colspan='2'>".$v["street"]."</td></tr>";
    $rummageList .= "<tr><td style='font-weight: bold;font-size: 120%' dcd-id='". $k."' colspan='2'>email".$v["email"]."</td></tr>";
    $rummageList .= "<tr><td class='dcd-adText' dcd-id='". $k."' colspan='2'>".$v["adText"]."</td></tr>";
    $rummageList .= "<tr><td align='right' style='font-weight: bold;' dcd-id='". $k."' colspan='2'><a href='#'>Click for full text</a></td></tr>";
    $rummageList .= '<td align="right">';

	$filter['city'][strtoupper($v['city'])] = true;
	$filter['sites'][$v['siteCode']] = strtoupper($v['siteName']);
    $filter['rents'][$v['rent']] = $v['rent'];
    $filter['bdrooms'][$v['bdrooms']] = $v['bdrooms'];
    $filter['bthrooms'][$v['bthrooms']] = $v['bthrooms'];

    $daysOpen = '';
    if (! empty($v['days'])) {
        foreach($v['days'] as $dayVal) {
            $daysOpen .= '&nbsp;<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="'.$dayVal['startTime'].'-'.$dayVal['endTime'].'">'.$dayAbrvArray[$dayVal['dayOfWeek']].'</button>';
            //$daysOpen .= '<a href="#" data-toggle="tooltip" title="'.$dayVal['startTime'].'-'.$dayVal['endTime'].'">'.$dayAbrvArray[$dayVal['dayOfWeek']].'</a>';
            $filter['days'][$dayVal['dayOfWeek']] = $dayArray[$dayVal['dayOfWeek']];
        }
    }
    if (! empty($daysOpen)) {
        $rummageList .= '<strong><small>Days: </small></strong><div class="btn-group btn-group-xs" role="group" aria-label="days">'.$daysOpen.'</div>';
    }
    $rummageList .= "</td></tr>";
    $rummageList .= "</table></td></tr>";


    $rummageList1 .= '<div class="row" style="margin-top: 0px;">';
    $rummageList1 .= '<div class="col-md-3">';
    $rummageList1 .= '<a href="#">';
    //$rummageList1 .= '<img class="img-responsive" src="http://placehold.it/150x150" alt="" height="150" width="150">';
    //$rummageList1 .= '<img class="img-responsive" src="img/no-photo-available-150x150.jpg" alt="" height="150" width="150">';
    $rummageList1 .= '<img class="img-responsive" src="img/no-image-available-150x150.jpg" alt="" height="150" width="150">';


    $rummageList1 .= '</a>';

    $rummageList1 .= '<button title="Add to Route" style="margin-top: 5px;" type="button" class="add btn btn-default btn-xs" onclick="visit(this,\''.$k.'\');" id="'.$k.'"';
    if (! isset($mapArray[$k])) {
        $rummageList1 .= " disabled='disabled'";
    }
    $rummageList1 .= " />";
    $rummageList1 .= '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>&nbsp;&nbsp;&nbsp;';
    $rummageList1 .= '<a style="padding:1px" href="http://twitter.com/home?status=' . str_replace("&","%26",substr($v["adText"], 0, 120)) . '" target="_blank"><img src="img/twitter-16.png" /></a>&nbsp;';
    $rummageList1 .= '<a style="padding:1px" href="https://www.facebook.com/sharer/sharer.php?u=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $k . '" target="_blank"><img src="img/facebook-16.png" /></a>&nbsp;';
    $rummageList1 .= '<a style="padding:1px" href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $k . '" target="_blank"><img src="img/google-plus-16.png" /></a>&nbsp;';
    $rummageList1 .= '<a style="padding:1px" href="mailto:?subject='. str_replace("&","%26",substr($v["adText"], 0, 80)) .'&body='. str_replace("&","%26",substr($v["adText"], 0, 120)) .'%0D%0A%0D%0A http://' . $_SERVER['SERVER_NAME'] . '/map.php?place='.urlencode($place).'%26posit='.urlencode($position).'%26ad=' . $k .'" target="_top" id="'.$k.'-gs-mail"><img src="img/social-email-16.png" /></span></a>';

    $rummageList1 .= '</div>';
    $rummageList1 .= '<div class="col-md-9">';
    if (!empty($v["street"])) {
        $rummageList1 .= '<h3>' . $v["street"] . '</h3>';
    }
    if (!empty($v["email"])) {
        $rummageList1 .= '<h4>' . $v["email"] . '</h4>';
    }
    $rummageList1 .= '<p>'.$v["adText"].'</p>';
    $rummageList1 .= '<a class="btn btn-primary" href="newItem.php?id='.$k.'">View Listing <span class="glyphicon glyphicon-chevron-right"></span></a>';
    $rummageList1 .= '</div>';
    $rummageList1 .= '</div>';
    $rummageList1 .= '<hr>';

}

$filter['days'] = array_unique($filter['days']);

$filterForm = "";
if(empty($_GET['city'])) {
    if(count($filter['city']) > 1){
        $filterForm .= '<div class="btn-group"><button title="Add Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuCity" data-toggle="dropdown">';
        $filterForm .= ' City <span class="caret"></span></button>';
        $filterForm .= '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuCity">';
        ksort($filter['city']);
        foreach($filter['city'] as $k=>$v){
            $filterForm .= '<li role="presentation"><a role="menuitem" tabindex="-1" onClick="setGetParameter(\'city\', \'' . $k . '\')" href="javascript:void(0)">' . $k . '</a></li>';
        }
        $filterForm .= '</ul></div>';
    }
}else{
    $filterForm .= '<div class="btn-group"><button title="Remove Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuCity" data-toggle="dropdown" onClick="removeSitesAndReloadPage(\'city\')" href="javascript:void(0)">';
    $filterForm .= 'City - <strong>'.$_GET['city'].'</strong> <span class="glyphicon glyphicon-remove-circle" style="color:#d43f3a;"></span></button></div>';
}

if (! empty($filterForm)) {
    $filterForm .= '&nbsp;';
}

if(empty($_GET['paper'])) {
    if(count($filter['sites']) > 1){
        $filterForm .= '<div class="btn-group"><button title="Add Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuPaper" data-toggle="dropdown">';
        $filterForm .= ' Newspaper <span class="caret"></span></button>';
        $filterForm .= '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuPaper">';
        foreach ($filter['sites'] as $k=>$v) {
            $filterForm .= '<li role="presentation"><a role="menuitem" tabindex="-1" onClick="setGetParameter(\'paper\', \'' . $k . '\')" href="javascript:void(0)">' . $v . '</a></li>';
        }
        $filterForm .= '</ul></div>';
    }
} else {
    $selectedSiteArray = $app->getSiteFromSiteCode($_GET['paper']);
    $selectedBusName = $selectedSiteArray[0]['BusName'];
    $filterForm .= '<div class="btn-group"><button title="Remove Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuPaper" data-toggle="dropdown" onClick="removeSitesAndReloadPage(\'paper\')" href="javascript:void(0)">';
    $filterForm .= ' Newspaper - <strong>'.$selectedBusName.'</strong> <span class="glyphicon glyphicon-remove-circle" style="color:#d43f3a;"></span></button></div>';
}

if (! empty($filterForm)) {
    $filterForm .= '&nbsp;';
}

if(empty($_GET['day'])) {
    if(count($filter['days']) > 1){
        $filterForm .= '<div class="btn-group"><button title="Add Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuPaper" data-toggle="dropdown">';
        $filterForm .= ' Days <span class="caret"></span></button>';
        $filterForm .= '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuPaper">';
        foreach($filter['days'] as $k=>$v){
            $filterForm .= '<li role="presentation"><a role="menuitem" tabindex="-1" onClick="setGetParameter(\'day\', \'' . $k . '\')" href="javascript:void(0)">' . $v . '</a></li>';
        }
        $filterForm .= '</ul></div>';
    }
} else {
    $dayString = isset($dayArray[$_GET['day']])?$dayArray[$_GET['day']]:'';
    $filterForm .= '<div class="btn-group"><button title="Remove Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuPaper" data-toggle="dropdown" onClick="removeSitesAndReloadPage(\'day\')" href="javascript:void(0)">';
    $filterForm .= ' Days - <strong>'.$dayString.'</strong> <span class="glyphicon glyphicon-remove-circle" style="color:#d43f3a;"></span></button></div>';
}

if(empty($_GET['bdrooms'])) {
    if(count($filter['bdrooms']) > 1){
        $filterForm .= '<div class="btn-group"><button title="Add Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuPaper" data-toggle="dropdown">';
        $filterForm .= ' Bed Rooms <span class="caret"></span></button>';
        $filterForm .= '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuPaper">';
        ksort($filter['bdrooms']);
        foreach($filter['bdrooms'] as $k=>$v){
            $filterForm .= '<li role="presentation"><a role="menuitem" tabindex="-1" onClick="setGetParameter(\'bdrooms\', \'' . $k . '\')" href="javascript:void(0)">' . $v . '</a></li>';
        }
        $filterForm .= '</ul></div>';
    }
} else {
    $bdrooms = isset($dayArray[$_GET['bdrooms']])?$dayArray[$_GET['bdrooms']]:'';
    $filterForm .= '<div class="btn-group"><button title="Remove Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuPaper" data-toggle="dropdown" onClick="removeSitesAndReloadPage(\'bdrooms\')" href="javascript:void(0)">';
    $filterForm .= ' Min. Bed Rooms - <strong>'.$bdrooms.'</strong> <span class="glyphicon glyphicon-remove-circle" style="color:#d43f3a;"></span></button></div>';
}

if(empty($_GET['bthrooms'])) {
    if(count($filter['bthrooms']) > 1){
        $filterForm .= '&nbsp;&nbsp;<div class="btn-group"><button title="Add Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuPaper" data-toggle="dropdown">';
        $filterForm .= ' Bath Rooms <span class="caret"></span></button>';
        $filterForm .= '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuPaper">';
        ksort($filter['bthrooms']);
        foreach($filter['bthrooms'] as $k=>$v){
            $filterForm .= '<li role="presentation"><a role="menuitem" tabindex="-1" onClick="setGetParameter(\'bthrooms\', \'' . $k . '\')" href="javascript:void(0)">' . $v . '</a></li>';
        }
        $filterForm .= '</ul></div>';
    }
} else {
    $bthrooms = isset($dayArray[$_GET['bthrooms']])?$dayArray[$_GET['bthrooms']]:'';
    $filterForm .= '<div class="btn-group"><button title="Remove Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuPaper" data-toggle="dropdown" onClick="removeSitesAndReloadPage(\'bthrooms\')" href="javascript:void(0)">';
    $filterForm .= ' Bath Rooms - <strong>'.$bthrooms.'</strong> <span class="glyphicon glyphicon-remove-circle" style="color:#d43f3a;"></span></button></div>';
}

if(count($filter['rents']) > 2) {
    $minRent = min($filter['rents']);
    $maxRent = max($filter['rents']);
    $halfRent = ceil($maxRent / 2) . '.00';

    $newRent = array(
        $minRent => $minRent,
        $halfRent => $halfRent,
        $maxRent => $maxRent
    );

    if(count($filter['rents']) > 3) {
        $quarterRent = ceil($maxRent / 4) . '.00';
        $threeQuarterRent = ($halfRent + $quarterRent) . '.00';
        $newRent[$quarterRent] = $quarterRent;
        $newRent[$threeQuarterRent] = $threeQuarterRent;
        ksort($newRent);
    }
}

if (empty($_GET['minrent'])) {
    if(count($filter['rents']) > 2){
        $filterForm .= '<div class="btn-group"><button title="Add Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuMinRent" data-toggle="dropdown">';
        $filterForm .= ' Min. Rent <span class="caret"></span></button>';
        $filterForm .= '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuPaper">';
        foreach($newRent as $k=>$v){
            $filterForm .= '<li role="presentation"><a role="menuitem" tabindex="-1" onClick="setGetParameter(\'minrent\', \'' . $k . '\')" href="javascript:void(0)">' . $v . '</a></li>';
        }
        $filterForm .= '</ul></div>';
    }
} else {
    $minrent = isset($dayArray[$_GET['maxrent']])?$dayArray[$_GET['maxrent']]:'';
    $filterForm .= '<div class="btn-group"><button title="Remove Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuMinRent" data-toggle="dropdown" onClick="removeSitesAndReloadPage(\'minrent\')" href="javascript:void(0)">';
    $filterForm .= ' Min. Rent - <strong>'.$minrent.'</strong> <span class="glyphicon glyphicon-remove-circle" style="color:#d43f3a;"></span></button></div>';
}

if (empty($_GET['maxrent'])) {
    if(count($filter['rents']) > 2){
        $filterForm .= '<div class="btn-group"><button title="Add Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuMaxRent" data-toggle="dropdown">';
        $filterForm .= ' Max. Rent <span class="caret"></span></button>';
        $filterForm .= '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuPaper">';
        foreach($newRent as $k=>$v){
            $filterForm .= '<li role="presentation"><a role="menuitem" tabindex="-1" onClick="setGetParameter(\'maxrent\', \'' . $k . '\')" href="javascript:void(0)">' . $v . '</a></li>';
        }
        $filterForm .= '</ul></div>';
    }
} else {
    $maxrent = isset($dayArray[$_GET['maxrent']])?$dayArray[$_GET['maxrent']]:'';
    $filterForm .= '<div class="btn-group"><button title="Remove Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuMaxRent" data-toggle="dropdown" onClick="removeSitesAndReloadPage(\'maxrent\')" href="javascript:void(0)">';
    $filterForm .= ' Max. Rent - <strong>'.$maxrent.'</strong> <span class="glyphicon glyphicon-remove-circle" style="color:#d43f3a;"></span></button></div>';
}

$filterLine = '';
if (!empty($filterForm)) {
    $filterLine = '<div><label>Filter by:&nbsp;</label>'.$filterForm.'</div>';
}

$routPanelForm = <<<EOS
<div>
    <div class="panel panel-default" id="panel2">
	    <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse1" class="collapsed">Map Route</a>
            </h4>
        </div>
        <div id="collapse1" class="panel-collapse collapse">
            <div class="panel-body">
            <form action="route.php" method="post" onsubmit="mapRoute();" class="form-horizontal" role="form">
                <input type="hidden" name="place" value="$place" />
                <input type="hidden" name="posit" value="$position" />
                <input type="hidden" id="locations" name="locations" value="" />
                <h5><strong>Please enter a starting address and select up to 8 places to visit, then click on 'Map Route'.</strong></h5>
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
            </form>
            </div>
        </div>
    </div>
</div>
EOS;

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
$routPanelForm
	<p><strong>Click or Tap on any entry to find on the map.</strong></p>
	<table class="table table-striped">
		$rummageList1
	</table>
EOS;

$mainContent = <<<EOS
	<ol class="breadcrumb">
		<li><a href="./">Home</a></li>
		<li class="active">$place</li>
	</ol>
	$filterLine
	$data
EOS;

include("../includes/master.php");
