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
			'DES' => array('siteName' => 'lohud', 'siteUrl' => 'http://www.lohud.com', 'busName' => 'The Journal News')		
		);
		
		$url = $_SERVER['REQUEST_URI'];
		$siteCode = 'DES';
		if (isset($_GET['sc'])&&(isset($sites[$_GET['sc']]))) {
			$siteCode = $_GET['sc'];
		}
		$siteUrl = $sites[$siteCode]['siteUrl'];
		$siteName = $sites[$siteCode]['siteName'];
		$busName = $sites[$siteCode]['busName'];	

		include('includes/constants.php');
		include('includes/functions.php');
    //include('includes/header.php'); 
		include('includes/mobilenavigation.php');
    include('includes/toggle.php'); 
		
		$ads = new Ads();
		echo $ads->InitializeAds();	
	?>
	<div class="row-fluid">
		<iframe src="http://<?=$siteName?>.gannettdigital.com/LI-header.html" style="position: absolute; border-width: 0px; height: 40px; width: 100%"></iframe>
	</div>
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
						include('includes/navigation.php'); 
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
<!-- start of (4) footer -->
<div class="row-fluid">
	<iframe src="http://<?=$siteName?>.gannettdigital.com/LI-footer.html" style="position: absolute; border-width: 0px; height: 250px; width: 100%" ></iframe>
</div>
<!-- end of (4) footer -->	
</footer>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="scripts/bootstrap.min.js"></script>
    <script src="scripts/jasny-bootstrap.min.js"></script>
</body>
</html>