<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<header role="banner" class="navbar navbar-inverse navbar-fixed-top bs-docs-nav">

<div class="container">

	<?php
	include('includes/constants.php');
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
			include('includes/navigation.php'); 
			echo $ads->getFlex();
			?>
        	</div>
        </div>
    </div>
    
</div>


<?php echo $ads->getLeaderBottom(); ?>


</body>
</html>