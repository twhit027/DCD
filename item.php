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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

</head>

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
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="scripts/bootstrap.min.js"></script>
    <script src="scripts/jasny-bootstrap.min.js"></script>
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
		   $content = new Content();
		   
           echo $content->getAd($_GET['x']);
        
       
		   
		    ?>
        
          
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

<footer class="footer">
<?php
	include('includes/footer.php');
?>
</footer>

</body>
</html>