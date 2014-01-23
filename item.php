<?php
include(dirname(__FILE__) . '/includes/KLogger.php');
include('includes/constants.php');

$log   = KLogger::instance(LOGGING_DIR, LOGGING_LEVEL);

$log->logInfo('Landing Page');

$log->logInfo('FORWARDED_FOR: '.@$_SERVER['HTTP_X_FORWARDED_FOR']);
$log->logInfo('REMOTE_ADDR: '.$_SERVER['REMOTE_ADDR']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="images/ico/favicon.png">
<style type="text/css">
body
{
	min-width:10px!important;
}
</style>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/jasny-bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">

var map;
var directionsDisplay = new google.maps.DirectionsRenderer;
var directionsService = new google.maps.DirectionsService;
var geocoder = new google.maps.Geocoder;
	
var dotlocation = new google.maps.LatLng(44.27618, -88.415222);	

function initialize() 
{          
    var myOptions = {
      zoom: 11,
      center: dotlocation,
 	  mapTypeId: google.maps.MapTypeId.ROADMAP
	  
    };
	
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);			
	
	//begin appleton
		
	var infowindow = new google.maps.InfoWindow({
		content: "<div id='content'>Address here<form onSubmit='return calcRoute();' ><input type='hidden' id='end' value='"+dotlocation +"'><input type='text' id='start'><input type='button' value='Find directions' onClick='return calcRoute();' ></form></div>"
	});

	var marker = new google.maps.Marker({
		position: dotlocation,
		map: map,
		title:"Appleton"
	});

	google.maps.event.addListener(marker, 'click', function() {
	  infowindow.open(map,marker);
	});

	  directionsDisplay.setMap(map);
	  directionsDisplay.setPanel(document.getElementById("directionsPanel"));

	/* begin oshkosh

	var infowindow2 = new google.maps.InfoWindow({
		content: '<div id="content"> Oshkosh </div>'
	});

	var marker2 = new google.maps.Marker({
		position: new google.maps.LatLng(43.99861, -88.5443),
		map: map,
		title:"Appleton"
	});

	google.maps.event.addListener(marker2, 'click', function() {
	  infowindow2.open(map,marker2);
	});
	*/
}

function calcRoute() {
    var start = document.getElementById("start").value;
    var end = document.getElementById("end").value;
    var request = {
        origin:start, 
        destination:end,
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) {

      if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
      }
 
    });
	return false;
  }

setTimeout("initialize()", 3000);
</script>
</head>
<body>
<header role="banner" class="navbar navbar-inverse navbar-fixed-top bs-docs-nav">
<div class="container">
	<?php
	include('includes/functions.php');
    include('includes/header.php'); 
	include('includes/mobilenavigation.php');
    include('includes/toggle.php'); 
		
	$ads = new Ads();
	echo $ads->InitializeAds();
    ?>
</div>            
</header>
  
<style type="text/css">
body
{
	min-width:10px!important;
}
</style>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/jasny-bootstrap.min.css" rel="stylesheet">

<?php
	echo $ads->getLaunchpad(); 	
?>

<div class="container" >     
    <div class="row" style="background-color:#000;">
        <div class="col-xs-11 col-sm-8" style="background-color:#FFF;">
    
            
                
             <div class="jumbotron">
              <p>1999 Grizly in great shape, blue 4x4 call 999-999-9999 this is a whole bunch of extra text about the ad that will go on and on extra text about the ad that will go on and on extra text about the ad that will go on and on extra text about the ad that will go on and on</p>
              
              <div id="map_canvas" style="width:100%;height:100%;"></div>
              <div id="directionsPanel" style="width:100%;"></div>
      
            </div>
        
          
        </div>
       
        <div class=" col-sm-4 card-suspender-color">
        	<div class="hidden-xs">
            <?php 
			include('includes/navigation.php'); 
			echo $ads->getFlex();
			?>
        	</div>
        </div>
    </div>
    
</div>

<?php echo $ads->getLeaderBottom(); ?>

<footer class="footer">
<?php
	include('includes/footer.php');
?>
</footer>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="scripts/bootstrap.min.js"></script>
    <script src="scripts/jasny-bootstrap.min.js"></script>
</body>
</html>