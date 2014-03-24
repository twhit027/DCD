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

$app->logInfo('Landing Page(FORWARDED_FOR: '.@$_SERVER['HTTP_X_FORWARDED_FOR'].', REMOTE_ADDR: '.@$_SERVER['REMOTE_ADDR'].',HTTP_HOST: '.@$_SERVER['HTTP_HOST'].'SERVER_NAME: '.@$_SERVER['SERVER_NAME'].')');
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
        body {
            min-width: 10px !important;
        }
    </style>

    <link href="3rdParty/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="3rdParty/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
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
        $nav = new \GCI\Navigation();
        $palette = $app->getSite()->getPalette();
        $top = \GCI\site::$paletteArray[$palette]['top'];
    $bottom = \GCI\site::$paletteArray[$palette]['bottom'];
	$border = \GCI\site::$paletteArray[$palette]['border'];
	$siteName = $app->getSite()->getSiteName();
	$siteUrl = $app->getSite()->getSiteUrl();
	$busName = $app->getSite()->getBusName();
	echo $nav->getTopNavigationStatic($siteUrl, $top, $bottom, $border);

    echo '<nav role="navigation" class="collapse navbar-collapse bs-navbar-collapse side-navbar ">';
    echo '<div class="visible-xs">';
    echo '<h3 style="color:#3276B1;">View By Category</h3>';
    echo '<ul class="nav nav-list accordion" id="sidenav-accordian" style="padding-bottom:10px;">';
	echo $nav->getSideNavigation($app->getCategories());
	echo '</ul></div></nav>';

    include('../includes/toggle.php');

	$ads = new \GCI\Ads();
	echo $ads->InitializeAds();
	
	$search = $app->getSearch();
	
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
            <div class="jumbotron" id="advancedsearch" style="display:none;">
            <?php 
			$search = $app->getSearch();
			
			echo  $search 
			?>
            </div>
                
            <a href="<?=$siteUrl?>" target="_blank"><img alt="<?=$siteName?> Logo" title="<?=$siteName?>" style="margin-bottom: 10px;background-color: black" src="<?=$siteUrl?>/graphics/ody/cobrand_logo.gif"></a>
            <p>SELL easy and SELL fast!</p>
            <p>As the leading local media and trusted marketing solutions provider, we have a range of effective advertising packages to meet your needs.</p>
            <p>From VEHICLES to PETS to GARAGE SALES to SERVICES, we provide the most effective ways to sell to potential local buyers through our leading mobile, online and print solutions.</p>
            <p>Develop and launch your advertising program within minutes with just a few clicks, and begin connecting with local buyers TODAY!</p>
            <p><a class="button" href="<?=$siteUrl?>/placead"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">Place an Ad</button></a></p>
    <h1>Featured Partner Classified Services</h1>
		<div class="row">
			<div class="col-md-3">
				<h4>Cars</h4>
				<a href="<?=$siteUrl?>/cars"><img alt="Cars.com" src="img/partners/130-cars.gif"></a>
				<p><a class="button" href="<?=$siteUrl?>/cars"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Autos</button></a></p>
			</div>
			<div class="col-md-3">
				<h4>Jobs</h4>
				<a href="<?=$siteUrl?>/jobs"><img alt="micareerbuilder.com" src="img/partners/130-careerbuilder.gif"></a>
				<p><a class="button" href="<?=$siteUrl?>/jobs"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Jobs</button></a></p>
			</div>
			<div class="col-md-3">
				<h4>Homes</h4>
				<a href="<?=$siteUrl?>/homes"><img alt="homefinder.com" src="img/partners/130-homefinder.gif" ></a>
				<p><a class="button" href="<?=$siteUrl?>/homes"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Homes</button></a></p>
			</div>
			<div class="col-md-3">
				<h4>Rentals</h4>
				<a href="<?=$siteUrl?>/apartments"><img alt="apartments.com" src="img/partners/130-apartments.gif" ></a>
				<p><a class="button" href="<?=$siteUrl?>/apartments"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Listings</button></a></p>
			</div>
		</div>
        </div>

        <div class=" col-sm-4 card-suspender-color" >
        	<div class="hidden-xs">
          <?php
						echo '<div role="navigation" id="sidebar" style="background-color:#000; padding-left:15px; padding-right:15px; padding-top:5px">';
						echo '<h3 style="color:#3276B1;">Search Our Classifieds</h3>';
						
						echo '<div class="input-group">';
				
				echo '<input type="text" class="form-control">';
				echo '<span class="input-group-btn">';
				echo '<button class="btn btn-default"  type="button">Search</button>';
				echo '</span>';
				echo '</div>';
		
		
						echo '<div class="advbtn btn btn-default" style="width:100%;display:none;">Advanced Search</div>';
		
		
                
						echo '<ul class="nav nav-list accordion" id="sidenav-accordian" style="padding-bottom:10px;">';

						echo $nav->getSideNavigation($app->getCategories());

						echo '</ul>';
						echo '</div>';
					?>
        	<div style="padding:10px">
        		<?php
        		echo $ads->getFlex();
        		?>
        	</div>
        	</div>
        </div>
    </div>
</div>
<?php
echo $ads->getLeaderBottom();
include('../includes/tracking.php');
?>
<footer class="footer">
<?php
	echo $nav->getBottomNavigationStatic($siteUrl, $siteName);
?>
</footer>
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="3rdParty/bootstrap/js/bootstrap.min.js"></script>
  <script src="3rdParty/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
</body>
</html>