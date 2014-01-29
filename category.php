<?php
include(dirname(__FILE__) . '/includes/KLogger.php');
include('includes/constants.php');

$log = KLogger::instance(LOGGING_DIR, LOGGING_LEVEL);

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
</head>
<body>
<header role="banner" class="navbar navbar-inverse navbar-fixed-top bs-docs-nav">
<div class="container">
<?php
	include('includes/functions.php');
  include('includes/header.php'); 
	$nav = new Navigation();
		
		echo '<nav role="navigation" class="collapse navbar-collapse bs-navbar-collapse side-navbar ">';
    	echo '<div class="visible-xs">';
      	echo '<h3 style="color:#3276B1;">View By Category</h3>';
        echo '<ul class="nav nav-list accordion" id="sidenav-accordian" style="padding-bottom:10px;">';
		echo $nav->getSideNavigation();
		
		echo '</ul>';
		echo '</div>';
		echo '</nav>';
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
    <div class="row" style="background-color:#000;">
        <div class="col-xs-11 col-sm-8" style="background-color:#FFF;">
    		<?php  
		   $content = new Content();
		   
           echo $content->getCategoryTitle($_GET['x']);
        
           
		   
		   echo $content->getCategoryListing($_GET['x']);
		   
		    ?>
           
                
      
          
        </div>
       
        <div class=" col-sm-4 card-suspender-color">
        	<div class="hidden-xs">
            <?php 
			echo '<h3 style="color:#3276B1;">View By Category</h3>';			
			echo '<div role="navigation" id="sidebar" >';
			echo '<ul class="nav nav-list accordion" id="sidenav-accordian" style="padding-bottom:10px;">';

			echo $nav->getSideNavigation();
			
			echo '</ul>';
			echo '</div>';
			echo $ads->getFlex();
			?>
        	</div>
        </div>
    </div>
    
</div>

<input type="hidden" name="SC" value="<?php echo $siteCode;?>">

	<?php 
	echo $ads->getLeaderBottom(); 
	include('includes/tracking.php'); 
	?>

	<footer class="footer">
		<?php include('includes/footer.php');?>
	</footer>

	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="scripts/bootstrap.min.js"></script>
	<script src="scripts/jasny-bootstrap.min.js"></script>
</body>
</html>