<?php
include(dirname(__FILE__) . '/3rdParty/klogger/KLogger.php');
include(dirname(__FILE__) . '/3rdParty/Mobile_Detect/Mobile_Detect.php');
include('conf/constants.php');
include('includes/GCI/Database.php');
include('includes/GCI/Site.php');
include('includes/GCI/App.php');
include('includes/GCI/Navigation.php');
include('includes/GCI/Ads.php');

$app = new \GCI\App();

//echo 'Cat: '; print_r($app->getCategories());

$app->logInfo('Landing Page');
$app->logInfo('FORWARDED_FOR: '.@$_SERVER['HTTP_X_FORWARDED_FOR']);
$app->logInfo('REMOTE_ADDR: '.@$_SERVER['REMOTE_ADDR']);
$app->logInfo('HTTP_HOST: '.@$_SERVER['HTTP_HOST']);
$app->logInfo('SERVER_NAME: '.@$_SERVER['SERVER_NAME']);
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

    include('includes/toggle.php');

	$ads = new \GCI\Ads();
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
            <a href="<?=$siteUrl?>" target="_blank"><img alt="<?=$siteName?> Logo" title="<?=$siteName?>" style="margin-bottom: 10px;background-color: black" src="<?=$siteUrl?>/graphics/ody/cobrand_logo.gif"></a>
            <p>We have many ad packages to suit your classified needs.</p>
            <p>Place a classified ad in <?=$busName?> in-paper and online. List all kinds of items including Merchandise, Pets, Garage Sales, Services, and much more. </p>
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
include('includes/tracking.php');
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