<?php
include('../vendor/klogger/KLogger.php');
include('../vendor/Mobile_Detect/Mobile_Detect.php');
include('../conf/constants.php');
include('../includes/GCI/Database.php');
include('../includes/GCI/Site.php');
include('../includes/GCI/App.php');
include('../includes/GCI/Navigation.php');
include('../includes/GCI/Ads.php');

$us_state_abbrevs_names = array(
    'AL'=>'ALABAMA',
    'AK'=>'ALASKA',
    'AZ'=>'ARIZONA',
    'AR'=>'ARKANSAS',
    'CA'=>'CALIFORNIA',
    'CO'=>'COLORADO',
    'CT'=>'CONNECTICUT',
    'DE'=>'DELAWARE',
    'DC'=>'DISTRICT OF COLUMBIA',
    'FL'=>'FLORIDA',
    'GA'=>'GEORGIA',
    'GU'=>'GUAM GU',
    'HI'=>'HAWAII',
    'ID'=>'IDAHO',
    'IL'=>'ILLINOIS',
    'IN'=>'INDIANA',
    'IA'=>'IOWA',
    'KS'=>'KANSAS',
    'KY'=>'KENTUCKY',
    'LA'=>'LOUISIANA',
    'ME'=>'MAINE',
    'MD'=>'MARYLAND',
    'MA'=>'MASSACHUSETTS',
    'MI'=>'MICHIGAN',
    'MN'=>'MINNESOTA',
    'MS'=>'MISSISSIPPI',
    'MO'=>'MISSOURI',
    'MT'=>'MONTANA',
    'NE'=>'NEBRASKA',
    'NV'=>'NEVADA',
    'NH'=>'NEW HAMPSHIRE',
    'NJ'=>'NEW JERSEY',
    'NM'=>'NEW MEXICO',
    'NY'=>'NEW YORK',
    'NC'=>'NORTH CAROLINA',
    'ND'=>'NORTH DAKOTA',
    'OH'=>'OHIO',
    'OK'=>'OKLAHOMA',
    'OR'=>'OREGON',
    'PA'=>'PENNSYLVANIA',
    'PR'=>'PUERTO RICO',
    'RI'=>'RHODE ISLAND',
    'SC'=>'SOUTH CAROLINA',
    'SD'=>'SOUTH DAKOTA',
    'TN'=>'TENNESSEE',
    'TX'=>'TEXAS',
    'UT'=>'UTAH',
    'VT'=>'VERMONT',
    'VA'=>'VIRGINIA',
    'WA'=>'WASHINGTON',
    'WV'=>'WEST VIRGINIA',
    'WI'=>'WISCONSIN',
    'WY'=>'WYOMING'
);

$nav = new \GCI\Navigation();
$app = new \GCI\App();

$app->logInfo('PreLandin Page(FORWARDED_FOR: '.@$_SERVER['HTTP_X_FORWARDED_FOR'].', REMOTE_ADDR: '.@$_SERVER['REMOTE_ADDR'].',HTTP_HOST: '.@$_SERVER['HTTP_HOST'].'SERVER_NAME: '.@$_SERVER['SERVER_NAME'].')');

$palette = $app->getSite()->getPalette();
$siteName = $app->getSite()->getSiteName();
$imgSiteName = $app->getSite()->getImgSiteName();
$siteUrl = $app->getSite()->getSiteUrl();
$siteCode = $app->getSite()->getSiteCode();
$busName = $app->getSite()->getBusName();
$siteTopData = $app->getSite()->getTopLinks();
$siteBottomData = $app->getSite()->getBottomLinks();
$siteClassUrl = $app->getSite()->getUrl();
$siteState = $app->getSite()->getSiteState();
$siteCity = $app->getSite()->getSiteCity();

if (empty($imgSiteName)) {
    $siteImage = "http://www.gannett-cdn.com/sites/$siteName/images/site-nav-logo@2x.png";
} else {
    $siteImage = "http://www.gannett-cdn.com/sites/$imgSiteName/images/site-nav-logo@2x.png";
}

$qString = "http://www.apartments.com/search/?query=$siteCity,%20$siteState&amp;stype=CityStateOrZip&amp;frontdoor=$siteCity&amp;partner=$siteCity&amp;rentmin=0&amp;rentmax=99999";

if ($palette > 89 && empty($siteBottomData)) {
    $siteBottomData = $siteTopData;
}

$baseUrl = defined('BASE_URL') ? BASE_URL : '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- for Google -->
    <meta name="description" content="search for rentals"/>
    <meta name="keywords" content="Rentals"/>
    <meta name="author" content="Gannett Media"/>
    <!-- for Facebook -->
    <meta property="og:title" content="Rentals"/>
    <meta property="og:type" content="article"/>
    <meta property="og:image" content="apartments/img/header.png"/>
    <meta property="og:url" content="apartments/"/>
    <meta property="og:description" content="search for rentals"/>
    <!-- for Twitter -->
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:title" content="Rentals"/>
    <meta name="twitter:description" content="search for rentals"/>
    <meta name="twitter:image" content="img/header.png"/>

    <link rel="shortcut icon" href="img/ico/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="57x57" href="img/ico/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/ico/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/ico/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="144x144" href="img/ico/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="60x60" href="img/ico/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/ico/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/ico/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/ico/apple-touch-icon-152x152.png">
    <link rel="icon" type="image/png" href="img/ico/favicon-196x196.png" sizes="196x196">
    <link rel="icon" type="image/png" href="img/ico/favicon-160x160.png" sizes="160x160">
    <link rel="icon" type="image/png" href="img/ico/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="img/ico/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="img/ico/favicon-32x32.png" sizes="32x32">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="css/apartments.css">

    <title><?php echo $siteName; ?> Rentals</title>

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<!-- modal -->
<div class="modal fade" id="prefmodal">
    <div class="modal-dialog" style="width: 340px;">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: None">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <a href="http://<?php echo "$siteClassUrl"; ?>/category.php?place=Rentals"
                   class="btn btn-primary btn-lg btn-responsive">
                    <span style="font-weight:bolder;font-size:24px;">Search Here</span> for Local listings</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-inverse navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" style="margin: 0; padding: 0px 10px;" href="<?php echo $siteUrl; ?>"><img alt="Gannett" style="height: 45px;" class="img-responsive" src="<?php echo $siteImage; ?>" /></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        <?php echo $nav->getTopNavigation($siteUrl, $palette, $siteName, $siteTopData, $imgSiteName, false, false); ?>
        </div>
    </div>
</nav>
<!-- Fixed navbar end-->

<div class="container main">
    <div class="row">
        <div class="col-md-6 col-lg-7 border">
            <img alt="Rentals" class="img-responsive" src="img/rental.png">
            <h1>Check out Rentals in Your Area.</h1>
            <h3>Easily find your next rental home, apartment, townhome or condo.</h3>
            <a style="margin-bottom:25px;"
               href="http://<?php echo "$siteClassUrl"; ?>/category.php?place=Rentals"
               class="btn btn-primary btn-lg btn-responsive">
                <span style="font-weight:bolder;font-size:24px;">Search Here</span> for Local listings</a>
        </div>
        <div class="col-md-1">&nbsp;</div>
        <div class="col-xs-8 col-sm-6 col-md-5 col-lg-4 border">
            <a href="http://www.apartments.com/">
                <img src="img/apartments.jpg" alt="APARTMENTS.COM" width="80%" />
            </a>
            <form name="qs" onSubmit="return deterLoad();">
                <div style="margin-top:10px;" id="apts_form_area">
                    <div class="field_col_1 input-group">
                        <input name="city" type="text" id="city" class="apts form-control input-group-lg" tabindex="1" placeholder="City or ZIP"/>
                        <input alt="Search" src="img/btn_search.gif" onclick="return deterLoader()" type="image" tabindex="8">
                    </div>
                    <input name="zip" id="zip" type="text" size="6" class="apts  input-lg" tabindex="3" style="display:none"/>
                    <div class="field_col_1">
                        <p class="form_label">State:</p>
                        <select name="state" id="state" class="apts form-control" tabindex="2">
                            <?php
                            foreach ($us_state_abbrevs_names as $abbrevs => $names) {
                                $stateOpt = '<option value="'.$abbrevs.'" ';
                                if (strtoupper($siteState) == $names) {$stateOpt .=  'selected="selected"';}
                                $stateOpt .= ">$abbrevs</option>";

                                echo $stateOpt;
                            }
                            ?>
                        </select>
                    </div>
                    <div style="display:none" class="field_col_3">
                        <p class="form_label">Radius:</p>
                        <select name="rad" id="rad" class="apts" tabindex="4">
                            <option value="0" selected="selected">Any</option>
                            <option value="5">5 miles</option>
                            <option value="10">10 miles</option>
                            <option value="20">20 miles</option>
                        </select>
                    </div>
                    <div style="display:none" class="field_col_4">
                        <p class="form_label">Beds:</p>
                        <select name="bedrooms" id="bedrooms" class="apts" tabindex="7">
                            <option value="" selected="selected">Any</option>
                            <option value="studio">Studio</option>
                            <option value="onebdrm">1</option>
                            <option value="twobdrm">2</option>
                            <option value="threebdrm">3</option>
                            <option value="fourbdrm">4</option>
                            <option value="fivebdrm">5</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                    <div class="aptQuickLinks field_col_1">
                        <strong>Quick Link:</strong> <a target="_blank" href="<?php echo $qString; ?>"><?php echo $siteCity; ?></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="3rdParty/jquery/jquery-1.11.2.min.js"><\/script>')</script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="js/apartments.js"></script>

<script>
    function GetParameterValues(param) {
        var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < url.length; i++) {
            var urlparam = url[i].split('=');
            if (urlparam[0] == param) {
                return urlparam[1];
            }
        }
    }

    $(function() {
        console.log("ready!");
        var modal = GetParameterValues('modal');
        if (modal == 1) {
            $('#prefmodal').modal('show');
        }
    });
</script>

<?php include("../includes/tracking.php"); ?>
</body>
</html>
