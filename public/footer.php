<?php
/**
 * Created by PhpStorm.
 * User: JHICKS
 * Date: 4/3/14
 * Time: 1:08 AM
 */

include('../vendor/klogger/KLogger.php');
include('../vendor/Mobile_Detect/Mobile_Detect.php');
include('../conf/constants.php');
include('../includes/GCI/Database.php');
include('../includes/GCI/Site.php');
include('../includes/GCI/App.php');
include('../includes/GCI/Navigation.php');
include('../includes/GCI/Ads.php');

$app = new \GCI\App();
$nav = new \GCI\Navigation();
$palette = $app->getSite()->getPalette();
$top = \GCI\site::$paletteArray[$palette]['top'];
$bottom = \GCI\site::$paletteArray[$palette]['bottom'];
$border = \GCI\site::$paletteArray[$palette]['border'];
$siteName = $app->getSite()->getSiteName();
$siteUrl = $app->getSite()->getSiteUrl();
$busName = $app->getSite()->getBusName();
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
</head>
<body>
<!-- footer begin-->
<footer class="footer">
    <?php echo $nav->getBottomNavigationStatic($siteUrl, $siteName); ?>
</footer>
<!-- footer end-->
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="3rdParty/bootstrap/js/bootstrap.min.js"></script>
<script src="3rdParty/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<script>
    $( document ).ready(function() {
        $("#ftSearch").click(function(e) {
            e.preventDefault();
            ft = $("#fullTextBox").val().trim();
            place='';
            posit='';
            /*if ($('#place').val()) {
             place= $('#place').val();
             }
             if ($('#place').val()) {
             posit= $('#posit').val();
             }*/
            window.location.href = 'category.php?place='+encodeURIComponent(place)+'&posit='+encodeURIComponent(posit)+'&ft='+encodeURIComponent(ft);
        });

        $("#ftSearchAdv").click(function(e) {
            e.preventDefault();
            $("#advancedsearch").toggle();
        });
    });
</script>
<?php if(!empty($masterBottom)){ echo $masterBottom; } ?>
</body>
</html>