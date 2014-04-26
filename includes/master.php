<?php
/**
 * Created by DCDGroup
 * User: JHICKS
 * Date: 3/16/14
 * Time: 2:34 AM
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <?php   
if(isset($metadata))
{ 
 echo $metadata; 
}
 ?>

    <link rel="shortcut icon" href="img/ico/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="57x57" href="img/ico/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/ico/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/img/ico/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/img/ico/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/img/ico/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/img/ico/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/ico/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/img/ico/apple-touch-icon-152x152.png">
    <link rel="icon" type="image/png" href="/img/ico/favicon-196x196.png" sizes="196x196">
    <link rel="icon" type="image/png" href="/img/ico/favicon-160x160.png" sizes="160x160">
    <link rel="icon" type="image/png" href="/img/ico/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="img/ico/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="img/ico/favicon-32x32.png" sizes="32x32">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <style type="text/css">
    body {min-width: 10px !important;}
    .gallery{display: inline-block;margin-top: 20px;}
    </style>

    <link href="3rdParty/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="3rdParty/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
	<?php if(!empty($googleApiScript)){ echo $googleApiScript; } ?>
    <link type="text/css" href="css/dcd.css" rel="stylesheet">
</head>
<body>
<!--[if lt IE 8]>
<div class="browser-warning-container">
    <div class="browser-warning">
        <h2 class="heading">oops!</h2>
        <p>
            It appears that your version of Internet Explorer is out of date.<br>
            <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> for a better sitewide experience.
        </p>
    </div>
</div>
<![endif]-->
<header role="banner" class="navbar navbar-inverse navbar-fixed-top bs-docs-nav">
    <div class="container">
        <?php
            if (!isset($fullText)) {$fullText = '';}
            $nav = new \GCI\Navigation();
            $palette = $app->getSite()->getPalette();
            $siteName = $app->getSite()->getSiteName();
            $siteUrl = $app->getSite()->getSiteUrl();
            $siteCode = $app->getSite()->getSiteCode();
            $busName = $app->getSite()->getBusName();
            $siteTopData = $app->getSite()->getTopLinks();
            $siteBottomData = $app->getSite()->getBottomLinks();
            echo $nav->getTopNavigation($siteUrl, $palette, $siteName, $siteTopData);
        ?>
        <nav role="navigation" class="collapse navbar-collapse bs-navbar-collapse side-navbar ">
        <div class="visible-xs">
        <h3 style="color:#3276B1;">Search Our Classifieds</h3>
            <div class="input-group">
                <input id="fullTextBox1" type="text" name="search" class="form-control" value="<?php echo $fullText; ?>">
                        <span class="input-group-btn">
                            <button id="ftSearchbtn1" class="btn btn-primary" type="button">
                                <img src="img/white-magnifying-glass-20.png">
                            </button>
                        </span>
            </div>
            <h3 style="color:#3276B1;">Or Select A Category</h3>
        <ul class="nav nav-list accordion" id="sidenav-accordian" style="padding-bottom:10px;">
        <?php echo $nav->getSideNavigation($app->getCategories()) ?>
        </ul></div></nav>
        <?php
            include('../includes/toggle.php');
            $ads = new \GCI\Ads();
            echo $ads->InitializeAds($app->getSite()->getDFP(), $app->getSite()->getDFPmobile());
        ?>
    </div>
</header>
<div style="padding-top:60px;">
<?php
$device =  $app->getDeviceType();
if($device =="computer")
{
	echo $ads->getLaunchpad();
}
else if($device =="phone")
{
	echo $ads->getMobileBannerTop();
}

?>
</div>
<div class="container">
    <div class="row" style="background-color:#FFF;">
        <div class="col-xs-11 col-sm-8">
            <?php echo $mainContent; ?>
        </div>

        <div class=" col-sm-4 card-suspender-color">
            <div class="hidden-xs">	
			
                <div role="navigation" id="sidebar" style="background-color:#000; padding-left:15px; padding-right:15px; padding-top:5px">
                    <h3 style="color:#3276B1;">Search Our Classifieds</h3>
                    <div class="input-group">
                        <input id="fullTextBox2" type="text" name="search" class="form-control" value="<?php echo $fullText; ?>">
                        <span class="input-group-btn">
                            <button id="ftSearchbtn2" class="btn btn-primary" type="button">
                                <img src="img/white-magnifying-glass-20.png">
                            </button>
                        </span>
				    </div>

                    <h3 style="color:#3276B1;">Or Select A Category</h3>
                    <ul class="nav nav-list accordion" id="sidenav-accordian" style="padding-bottom:10px;">
                        <?php echo $nav->getSideNavigation($app->getCategories()); ?>
                    </ul>
                </div>
                <div style="padding:10px">
                    <?php 
							if($device =="computer")
							{
								echo $ads->getFlex();
							}
					?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

if($device =="computer")
{
	echo $ads->getLeaderBottom();
}
else if($device =="phone")
{
	echo $ads->getMobileBannerBottom();
}
else if($device == "tablet")
{
 	echo $ads->getLandscapeInterstitial();	
}

//include('../includes/tracking.php');
?>
<footer class="footer">
<?php echo $nav->getBottomNavigation($siteUrl, $palette, $siteName, $siteBottomData); ?>
</footer>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="3rdParty/bootstrap/js/bootstrap.min.js"></script>
<script src="3rdParty/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<script>
    $( document ).ready(function() {
        $("#ftSearchbtn1").click(function(e) {
            e.preventDefault();
            ft = $("#fullTextBox1").val().trim();
            place='';
            posit='';
            window.location.href = 'category.php?place='+encodeURIComponent(place)+'&posit='+encodeURIComponent(posit)+'&ft='+encodeURIComponent(ft);
        });

        $('#fullTextBox1').keypress(function(e) {
            if (e.which == '13') {
                e.preventDefault();
                ft = $("#fullTextBox1").val().trim();
                place='';
                posit='';
                window.location.href = 'category.php?place='+encodeURIComponent(place)+'&posit='+encodeURIComponent(posit)+'&ft='+encodeURIComponent(ft);
            }
        });

        $("#ftSearchbtn2").click(function(e) {
            e.preventDefault();
            ft = $("#fullTextBox2").val().trim();
            place='';
            posit='';
            window.location.href = 'category.php?place='+encodeURIComponent(place)+'&posit='+encodeURIComponent(posit)+'&ft='+encodeURIComponent(ft);
        });

        $('#fullTextBox2').keypress(function(e) {
            if (e.which == '13') {
                e.preventDefault();
                ft = $("#fullTextBox2").val().trim();
                place='';
                posit='';
                window.location.href = 'category.php?place='+encodeURIComponent(place)+'&posit='+encodeURIComponent(posit)+'&ft='+encodeURIComponent(ft);
            }
        });

        $("#ftSearchAdv").click(function(e) {
            e.preventDefault();
            $("#advancedsearch").toggle();
        });
    });
</script>
<?php if(!empty($masterBottom)){ echo $masterBottom; } ?>
<?php include("../includes/tracking.php"); ?>
</body>
</html>