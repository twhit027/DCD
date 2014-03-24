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
    <meta name="description" content="">
    <meta name="author" content="">
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
	<?php echo $googleApiScript; ?>
</head>
<body>
<header role="banner" class="navbar navbar-inverse navbar-fixed-top bs-docs-nav">
    <div class="container">
        <?php
            if (!isset($fullText)) {$fullText = '';}
            $nav = new \GCI\Navigation();
            $palette = $app->getSite()->getPalette();
            $top = \GCI\site::$paletteArray[$palette]['top'];
            $bottom = \GCI\site::$paletteArray[$palette]['bottom'];
            $border = \GCI\site::$paletteArray[$palette]['border'];
            $siteName = $app->getSite()->getSiteName();
            $siteUrl = $app->getSite()->getSiteUrl();
            $busName = $app->getSite()->getBusName();
            echo $nav->getTopNavigationStatic($siteUrl, $top, $bottom, $border);
        ?>
        <nav role="navigation" class="collapse navbar-collapse bs-navbar-collapse side-navbar ">
        <div class="visible-xs">
        <h3 style="color:#3276B1;">Search Our Classifieds</h3>
        <ul class="nav nav-list accordion" id="sidenav-accordian" style="padding-bottom:10px;">
        <?php echo $nav->getSideNavigation($app->getCategories()) ?>
        </ul></div></nav>
        <?php
            include('../includes/toggle.php');
            $ads = new \GCI\Ads();
            echo $ads->InitializeAds();
        ?>
    </div>
</header>
<?php
echo $ads->getLaunchpad();
?>
<div class="container">
    <div class="row" style="background-color:#FFF;">
        <div class="col-xs-11 col-sm-8">
            <?php echo $mainContent; ?>
        </div>

        <div class=" col-sm-4 card-suspender-color">
            <div class="hidden-xs">	
			
                <div role="navigation" id="sidebar" style="background-color:#000; padding-left:15px; padding-right:15px; padding-top:5px">
				<div class="input-group" style="margin-bottom:5px;">
					<input type="text" class="form-control">
					<span class="input-group-btn">
						<button class="btn btn-default"  type="button">Search</button>
					</span>
				</div>	
				<div class="advbtn btn btn-default" style="width:100%;display:none;margin-bottom:5px;">Advanced Search</div>			
                    <h3 style="color:#3276B1;">Or Pick A Category</h3>
                    <ul class="nav nav-list accordion" id="sidenav-accordian" style="padding-bottom:10px;">
                        <?php echo $nav->getSideNavigation($app->getCategories()); ?>
                    </ul>
                </div>
<br />
                <div role="navigation" id="sidebar" style="background-color:#000; padding-left:15px; padding-right:15px; padding-top:10px">
                    <ul class="nav nav-list accordion" id="sidenav-accordian" style="padding-bottom:10px;vertical-align: bottom;width: 100%;">
                        <input id="fullTextBox" type="text" name="search" style="height:32px;font-size:14pt;width: 66%;" value="<?php echo $fullText; ?>">
                        <a id="ftSearch" class="btn btn-primary" style="vertical-align: bottom;"><img src="img/white-magnifying-glass-20.png"></a>
                        <a id="ftSearchAdv" class="btn btn-primary" style="vertical-align: bottom;">+</a>
                    </ul>
                </div>

                <div style="padding:10px">
                    <?php echo $ads->getFlex(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo $ads->getLeaderBottom();
//include('../includes/tracking.php');
?>
<footer class="footer">
<?php echo $nav->getBottomNavigationStatic($siteUrl, $siteName); ?>
</footer>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="3rdParty/bootstrap/js/bootstrap.min.js"></script>
<script src="3rdParty/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<<<<<<< HEAD
<?php if(!empty($masterBottom)){ echo $masterBottom; } ?>
=======
<script>
    $( document ).ready(function() {
        $("#ftSearch").click(function(e) {
            e.preventDefault();
            ft = trim($("#fullTextBox").val());
            place= $('#place').val();
            posit= $('#posit').val();
            window.location.href = 'category.php?place='+encodeURIComponent(place)+'&posit='+encodeURIComponent(posit)+'&ft='+encodeURIComponent(ft);
        });

        $("#ftSearchAdv").click(function(e) {
            e.preventDefault();
            $("#advSearch").toggle();
        });
    });
</script>
>>>>>>> e0e8f877bb5feedd7782fff4a3935a9395be29f4
</body>
</html>