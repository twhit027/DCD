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
  
<?php
	echo $ads->getLaunchpad(); 	
?>

<div class="container" >     
    <div class="row" style="background-color:#FFF;">
        <div class="col-xs-11 col-sm-8">
    
            <h1><?=$busName?> &amp; Online Classifieds</h1>
            
            <a href="<?=$siteUrl?>"><img target="_blank" alt="<?=$siteName?> Logo" title="<?=$siteName?>" style="margin-bottom: 10px;background-color: black" src="<?=$siteUrl?>/graphics/ody/cobrand_logo.gif"></a>
                        
            <p>We have many ad packages to suit your classified needs.</p>
            
            <p>Place a classified ad in <?=$busName?> in-paper and online. List all kinds of items including Merchandise, Pets, Garage Sales, Services, and much more. </p>
            
           	<?php
            	$nav = new Content();
							echo $nav->getPartnersString($siteUrl);
            ?>
        </div>
       
        <div class=" col-sm-4 card-suspender-color" style="background-color:#000;">
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

<input type="hidden" name="SC" value="<?=$siteCode?>">

<?php 
echo $ads->getLeaderBottom(); 
include('includes/tracking.php'); 
?>

<footer>
<?php
	include('includes/footer.php');
?>
</footer>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="scripts/bootstrap.min.js"></script>
    <script src="scripts/jasny-bootstrap.min.js"></script>
</body>
</html>