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
    .header {
        background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAAA3NCSVQICAjb4U/gAAAAJFBMVEX///9wcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHDRPAkXAAAADHRSTlMAESIziJmqu8zd7v+91kxoAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFnRFWHRDcmVhdGlvbiBUaW1lADIxLzEyLzEymvNa/wAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAArSURBVAiZY2DAB1iaoAy2XQZQVvRiKIMVLlQ9AU0EpoZjhwLUnEa81iABAFHzB8GYPzdNAAAAAElFTkSuQmCC");
        background-repeat: no-repeat;
    }
    .content { border:2px solid #ccc; width:300px; height: 100px; overflow-y: scroll; }
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
            if (!isset($siteGroup)) {$siteGroup = '';}
            $nav = new \GCI\Navigation();
            $palette = $app->getSite()->getPalette();
            $siteName = $app->getSite()->getSiteName();
            $siteUrl = $app->getSite()->getSiteUrl();
            $siteCode = $app->getSite()->getSiteCode();
            $busName = $app->getSite()->getBusName();
            $siteTopData = $app->getSite()->getTopLinks();
            $siteBottomData = $app->getSite()->getBottomLinks();
            if ($palette > 89 && empty($siteBottomData)) {
                $siteBottomData = $siteTopData;
            }
            echo $nav->getTopNavigation($siteUrl, $palette, $siteName, $siteTopData);
        ?>
        <nav role="navigation" class="collapse navbar-collapse bs-navbar-collapse side-navbar ">
        <div class="visible-xs">
        <h3 style="color:#3276B1;">Search Our Classifieds</h3>
            <div class="alert alert-danger" style="display: none;" id="searchAlert1"></div>
            <div class="input-group">
                <input id="fullTextBox1" type="text" name="search" class="form-control" value="<?php echo $fullText; ?>">
                        <span class="input-group-btn">
                            <button id="ftSearchbtn1" class="btn btn-primary" type="button">
                                <img src="img/white-magnifying-glass-20.png">
                            </button>
                        </span>
            </div>
            <div class="filter" style="color: white;">
                <input type="checkbox" id="allSites1" value="" <?php if(strtolower($siteGroup) == 'all') echo 'checked="checked"';?> /> Search Across All sites
                <div id="radius1" style="<?php if(strtolower($siteGroup) != 'all') echo 'display: none;';?>">
                    Limit Search Radius:
                    <select id="radSelect1" style="color:black">
                        <option value="all">all</option>
                        <option value="50" <?php if($radius == "50") echo "selected"; ?>>50 Miles</option>
                        <option value="100" <?php if($radius == "100") echo "selected"; ?>>100 Miles</option>
                        <option value="200" <?php if($radius == "200") echo "selected"; ?>>200 Miles</option>
                        <option value="250" <?php if($radius == "250") echo "selected"; ?>>250 Miles</option>
                        <option value="300" <?php if($radius == "300") echo "selected"; ?>>300 Miles</option>
                        <option value="400" <?php if($radius == "400") echo "selected"; ?>>400 Miles</option>
                        <option value="500" <?php if($radius == "500") echo "selected"; ?>>500 Miles</option>
                        <option value="750" <?php if($radius == "750") echo "selected"; ?>>750 Miles</option>
                        <option value="1000" <?php if($radius == "1000") echo "selected"; ?>>1000 Miles</option>
                    </select>
                </div>
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
                    <div class="alert alert-danger" style="display: none;" id="searchAlert2"></div>
                    <div class="input-group">
                        <input id="fullTextBox2" type="text" name="search" class="form-control" value="<?php echo $fullText; ?>">
                        <span class="input-group-btn">
                            <button id="ftSearchbtn2" class="btn btn-primary" type="button">
                                <img src="img/white-magnifying-glass-20.png">
                            </button>
                        </span>
				    </div>
                    <div class="filter" style="color: white">
                        <input type="checkbox" id="allSites2" value="" <?php if(strtolower($siteGroup) == 'all') echo 'checked="checked"';?> /> Search Across All sites
                        <div id="radius2" style="<?php if(strtolower($siteGroup) != 'all') echo 'display: none;';?>">
                            Limit Search Radius:
                            <select id="radSelect2" style="color:black">
                                <option value="all">all</option>
                                <option value="50" <?php if($radius == "50") echo "selected"; ?>>50 Miles</option>
                                <option value="100" <?php if($radius == "100") echo "selected"; ?>>100 Miles</option>
                                <option value="200" <?php if($radius == "200") echo "selected"; ?>>200 Miles</option>
                                <option value="250" <?php if($radius == "250") echo "selected"; ?>>250 Miles</option>
                                <option value="300" <?php if($radius == "300") echo "selected"; ?>>300 Miles</option>
                                <option value="400" <?php if($radius == "400") echo "selected"; ?>>400 Miles</option>
                                <option value="500" <?php if($radius == "500") echo "selected"; ?>>500 Miles</option>
                                <option value="750" <?php if($radius == "750") echo "selected"; ?>>750 Miles</option>
                                <option value="1000" <?php if($radius == "1000") echo "selected"; ?>>1000 Miles</option>
                            </select>
                        </div>
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
    function getDistance(lat1, lat2, lon1, lon2) {
        //var R = 6371; // km
        var R = 3959; //mile
        var ltrd1 = lat1.toRadians();
        var ltrd2 = lat2.toRadians();
        var difLat = (lat2-lat1).toRadians();
        var difLong = (lon2-lon1).toRadians();

        var a = Math.sin(difLat/2) * Math.sin(difLat/2) + Math.cos(ltrd1) * Math.cos(ltrd2) * Math.sin(difLong/2) * Math.sin(difLong/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

        var d = R * c;
        return d;
    }

    function doSearch(place, posit, ft, allSites, rad) {
        var path = '';

        if (place != '') {
            path += 'place='+encodeURIComponent(place);
        }

        if (posit != '') {
            if (path != '') {
                path += '&';
            }
            path += 'posit='+encodeURIComponent(posit);
        }

        if (ft != '') {
            if (path != '') {
                path += '&';
            }
            path += 'ft='+encodeURIComponent(ft);
        }

        if (allSites) {
            if (path != '') {
                path += '&';
            }
            path += 'sites=all';
        }

        if (rad != '' && rad.toLowerCase() != 'all') {
            if (path != '') {
                path += '&';
            }
            path += 'rad='+encodeURIComponent(rad);
        }

        if (path != '') {
            path = '?'+path;
        }

        window.location.href = 'category.php'+path;
    }

    $( document ).ready(function() {
        $(".header").click(function () {
            $header = $(this);
            //getting the next element
            $content = $header.next();
            //open up the content needed - toggle the slide- if visible, slide up, if not slide down.
            $content.slideToggle(500, function () {
                //execute this after slideToggle is done
                //change text of header based on visibility of content div
                $(".moreorless").text(function () {
                    //change text based on condition
                    return $content.is(":visible") ? "Less" : "More";
                });
                bckImg = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAAA3NCSVQICAjb4U/gAAAAJFBMVEX///9wcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHDRPAkXAAAADHRSTlMAESIziJmqu8zd7v+91kxoAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFnRFWHRDcmVhdGlvbiBUaW1lADIxLzEyLzEymvNa/wAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAArSURBVAiZY2DAB1iaoAy2XQZQVvRiKIMVLlQ9AU0EpoZjhwLUnEa81iABAFHzB8GYPzdNAAAAAElFTkSuQmCC";

                if ($content.is(":visible")) {
                    bckImg = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAAA3NCSVQICAjb4U/gAAAAJFBMVEX///9wcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHDRPAkXAAAADHRSTlMAESIziJmqu8zd7v+91kxoAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFnRFWHRDcmVhdGlvbiBUaW1lADIxLzEyLzEymvNa/wAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAAySURBVAiZY2AgCbA0MDCkgBgcWxlYd4AYjN0B0YvAclrbdxmAGcyrF0OVWxqQZjwDAwA8XgfBciyedgAAAABJRU5ErkJggg==";
                }

                $header.css("background-image", 'url('+bckImg+')');
            });

        });

        $(".filterContent").hide();
        //toggle the componenet with class msg_body
        $(".filterHeading").click(function()
        {
            $(this).next(".filterContent").slideToggle(500);
        });

        $("#ftSearchbtn1").click(function(e) {
            e.preventDefault();
            ft = $("#fullTextBox1").val().trim();
            place='';
            posit='';
            allSites = $("#allSites1").prop("checked") ? 1 : 0;
            rad = $('#radSelect1').val();

            if (ft == '') {
                $("#searchAlert1").html("Please provide a search term");
                $("#searchAlert1").toggle(true);
                $("#fullTextBox1").focus();
            } else {
                doSearch(place, posit, ft, allSites, rad);
            }
        });

        $('#fullTextBox1').keypress(function(e) {
            if (e.which == '13') {
                e.preventDefault();
                ft = $("#fullTextBox1").val().trim();
                place='';
                posit='';
                allSites = $("#allSites1").prop("checked") ? 1 : 0;
                rad = $('#radSelect1').val();

                if (ft == '') {
                    $("#searchAlert1").html("Please provide a search term");
                    $("#searchAlert1").toggle(true);
                    $("#fullTextBox1").focus();
                } else {
                    doSearch(place, posit, ft, allSites, rad);
                }
            }
        });

        $("#ftSearchbtn2").click(function(e) {
            e.preventDefault();
            ft = $("#fullTextBox2").val().trim();
            place='';
            posit='';
            allSites = $("#allSites2").prop("checked") ? 1 : 0;
            rad = $('#radSelect2').val();

            if (ft == '') {
                $("#searchAlert2").html("Please provide a search term");
                $("#searchAlert2").toggle(true);
                $("#fullTextBox2").focus();
            } else {
                doSearch(place, posit, ft, allSites, rad);
            }
        });

        $('#fullTextBox2').keypress(function(e) {
            if (e.which == '13') {
                e.preventDefault();
                ft = $("#fullTextBox2").val().trim();
                place='';
                posit='';
                allSites = $("#allSites2").prop("checked") ? 1 : 0;
                rad = $('#radSelect2').val();

                if (ft == '') {
                    $("#searchAlert2").html("Please provide a search term");
                    $("#searchAlert2").toggle(true);
                    $("#fullTextBox2").focus();
                } else {
                    doSearch(place, posit, ft, allSites, rad);
                }
            }
        });

        $("#allSites1").click(function(e) {
            $('#radius1').toggle(this.checked);
        });

        $("#allSites2").click(function(e) {
            $('#radius2').toggle(this.checked);
        });

        $("#ftSearchAdv").click(function(e) {
            e.preventDefault();
            $("#advancedsearch").toggle();
        });
    });

    function toggle(source, checkName) {
        checkboxes = document.getElementsByName(checkName);
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }
</script>
<?php if(!empty($masterBottom)){ echo $masterBottom; } ?>
<?php include("../includes/tracking.php"); ?>
</body>
</html>