<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 3/28/14
 * Time: 1:06 PM
 */
include('../../vendor/klogger/KLogger.php');
include('../../vendor/Mobile_Detect/Mobile_Detect.php');
include('../../conf/constants.php');
include('../../includes/GCI/Database.php');

$dbConn = new \GCI\Database();

$siteResults = $dbConn->getAssoc("SELECT * FROM `siteinfo` order by State");

$siteData = '<table class="table table-striped">';
foreach($siteResults as $site) {
    $siteData .= '<tr><td><a href="http://'.$site['Url'].'">'.$site['SiteName'] .'</a></td></tr>';

$siteData .= '</table>';

$webmaster = "webmaster@gannett.com";
$host     = getenv("REMOTE_HOST");
$referrer = getenv("HTTP_REFERER");}
$path     = getenv("REQUEST_URI");

// time in this format: 13/Nov/2000:10:50:38
$time = strftime("%d/%b/%Y:%T");

if (!empty($referrer)) {
    $referrer = "<p>You came to this page from $referrer, this could be a broken link so please <a href=\"mailto:$webmaster?subject=Error 404 on $path from $referrer\">email the webmaster</a> to inform us of this.</p>";
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../img/ico/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="57x57" href="../img/ico/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../img/ico/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../img/ico/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../img/ico/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../img/ico/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../img/ico/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../img/ico/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../img/ico/apple-touch-icon-152x152.png">
    <link rel="icon" type="image/png" href="../img/ico/favicon-196x196.png" sizes="196x196">
    <link rel="icon" type="image/png" href="../img/ico/favicon-160x160.png" sizes="160x160">
    <link rel="icon" type="image/png" href="../img/ico/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="../img/ico/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="../img/ico/favicon-32x32.png" sizes="32x32">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <style type="text/css">
        body {
            min-width: 10px !important;
        }
        P { text-align: center }
    </style>

    <link href="../3rdParty/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../3rdParty/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <title>We could not find the Page you wanted.</title>
</head>

<body>
<h1>Page not found</h1>
<h2>Sorry, we couldn't find the page you were looking for on this website.</h2>
<?php echo $referrer; ?>
<p>Please use the navigation links to help locate what you're looking for.</p>
<p>
<?php echo $siteData ?>
</p>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="../3rdParty/bootstrap/js/bootstrap.min.js"></script>
<script src="../3rdParty/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
</body>

</html>
