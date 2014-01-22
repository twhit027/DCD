<?php
include(dirname(__FILE__) . '/includes/KLogger.php');
include('includes/constants.php');

$log   = KLogger::instance(LOGGING_DIR, LOGGING_LEVEL);

$log->logInfo('Landing Page');
$log->logInfo('FORWARDED_FOR: '.$_SERVER['HTTP_X_FORWARDED_FOR']);
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
</head>
<body>
<header role="banner" class="navbar navbar-inverse navbar-fixed-top bs-docs-nav">
<div class="container">
	<?php
		$sites = array(
			'DES' => array('siteName' => 'desmoinesregister', 'siteUrl' => 'http://www.desmoinesregister.com', 'busName' => 'The Des Moines Register'),
			'INI' => array('siteName' => 'indystar', 'siteUrl' => 'http://www.indystar.com', 'busName' => 'The Indianapolis Star'),
			'IOW' => array('siteName' => 'press-citizen', 'siteUrl' => 'http://www.press-citizen.com', 'busName' => 'The Press-Citizen'),
			'POU' => array('siteName' => 'poughkeepsiejournal', 'siteUrl' => 'http://www.poughkeepsiejournal.com', 'busName' => 'The Poughkeepsie Journal'),
			'TJN' => array('siteName' => 'lohud', 'siteUrl' => 'http://www.lohud.com', 'busName' => 'The Journal News')		
		);
		
		$url = $_SERVER['REQUEST_URI'];
		$siteCode = 'DES';
		if (isset($_GET['sc'])&&(isset($sites[strtoupper($_GET['sc'])]))) {
			$siteCode = strtoupper($_GET['sc']);
		}
		$siteUrl = $sites[$siteCode]['siteUrl'];
		$siteName = $sites[$siteCode]['siteName'];
		$busName = $sites[$siteCode]['busName'];	

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
    		<?php
            echo $nav->getSideNavigation();
			?>            
            <h1>ATVs</h1>
        
            <div class="jumbotron">
              <p>1999 Grizly in great shape, blue 4x4 call 999-999-9999</p>
              <p><a class="btn btn-primary btn-lg" role="button" href="item.php?x=<?php echo $_GET['x']; ?>">Learn more</a></p>
            </div>
            
            <div class="jumbotron">
              <p>2010 Ranger in great shape, blue 4x4 call 999-999-9999</p>
              <p><a class="btn btn-primary btn-lg" role="button" href="item.php?x=<?php echo $_GET['x']; ?>">Learn more</a></p>
            </div>
            
            <div class="jumbotron">
              <p>1999 Grizly in great shape, blue 4x4 call 999-999-9999</p>
              <p><a class="btn btn-primary btn-lg" role="button" href="item.php?x=<?php echo $_GET['x']; ?>">Learn more</a></p>
            </div>
            
            <div class="jumbotron">
              <p>2010 Ranger in great shape, blue 4x4 call 999-999-9999</p>
              <p><a class="btn btn-primary btn-lg" role="button" href="item.php?x=<?php echo $_GET['x']; ?>">Learn more</a></p>
            </div>
            
            <div class="jumbotron">
              <p>1999 Grizly in great shape, blue 4x4 call 999-999-9999</p>
              <p><a class="btn btn-primary btn-lg" role="button" href="item.php?x=<?php echo $_GET['x']; ?>">Learn more</a></p>
            </div>
            
            <div class="jumbotron">
              <p>2010 Ranger in great shape, blue 4x4 call 999-999-9999</p>
              <p><a class="btn btn-primary btn-lg" role="button" href="item.php?x=<?php echo $_GET['x']; ?>">Learn more</a></p>
            </div>
                
      
          
        </div>
       
        <div class=" col-sm-4 card-suspender-color">
        	<div class="hidden-xs">
            <?php 
			$nav = new Navigation();
			echo $nav->getSideNavigation();
			echo $ads->getFlex();
			?>
        	</div>
        </div>
    </div>
    
</div>


<?php echo $ads->getLeaderBottom(); ?>


</body>
</html>