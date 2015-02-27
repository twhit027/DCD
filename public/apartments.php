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
    'AS'=>'AMERICAN SAMOA',
    'AZ'=>'ARIZONA',
    'AR'=>'ARKANSAS',
    'CA'=>'CALIFORNIA',
    'CO'=>'COLORADO',
    'CT'=>'CONNECTICUT',
    'DE'=>'DELAWARE',
    'DC'=>'DISTRICT OF COLUMBIA',
    'FM'=>'FEDERATED STATES OF MICRONESIA',
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
    'MH'=>'MARSHALL ISLANDS',
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
    'MP'=>'NORTHERN MARIANA ISLANDS',
    'OH'=>'OHIO',
    'OK'=>'OKLAHOMA',
    'OR'=>'OREGON',
    'PW'=>'PALAU',
    'PA'=>'PENNSYLVANIA',
    'PR'=>'PUERTO RICO',
    'RI'=>'RHODE ISLAND',
    'SC'=>'SOUTH CAROLINA',
    'SD'=>'SOUTH DAKOTA',
    'TN'=>'TENNESSEE',
    'TX'=>'TEXAS',
    'UT'=>'UTAH',
    'VT'=>'VERMONT',
    'VI'=>'VIRGIN ISLANDS',
    'VA'=>'VIRGINIA',
    'WA'=>'WASHINGTON',
    'WV'=>'WEST VIRGINIA',
    'WI'=>'WISCONSIN',
    'WY'=>'WYOMING',
    'AE'=>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
    'AA'=>'ARMED FORCES AMERICA (EXCEPT CANADA)',
    'AP'=>'ARMED FORCES PACIFIC'
);

$nav = new \GCI\Navigation();
$app = new \GCI\App();

$app->logInfo('Landin Page(FORWARDED_FOR: '.@$_SERVER['HTTP_X_FORWARDED_FOR'].', REMOTE_ADDR: '.@$_SERVER['REMOTE_ADDR'].',HTTP_HOST: '.@$_SERVER['HTTP_HOST'].'SERVER_NAME: '.@$_SERVER['SERVER_NAME'].')');

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
    <meta name="description" content="Win free tickets to this free contest"/>
    <meta name="keywords" content="Rentals"/>
    <meta name="author" content="Gannett Media"/>
    <!-- for Facebook -->
    <meta property="og:title" content="Rentals"/>
    <meta property="og:type" content="article"/>
    <meta property="og:image" content="apartments/img/header.png"/>
    <meta property="og:url" content="apartments/"/>
    <meta property="og:description" content="Win free tickets to this free contest"/>
    <!-- for Twitter -->
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:title" content="Rentals"/>
    <meta name="twitter:description" content="search for apartments"/>
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

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="css/apartments.css">

    <title>Rentals</title>

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script>
        <!--
        // ============================================================================
        // ======================= Classified Ventures, 2000 ==========================
        // ============================================================================


        // Determine whether to do a zipcode search or city/state
        //     zipcode overrides city/state
        function deterLoad() {
            theCity = window.document.qs.city.value;
            theState = window.document.qs.state.options[window.document.qs.state.selectedIndex].value;
            theZip = window.document.qs.zip.value;
            theZip = encodeURIComponent(theZip);
            //Rent_Minimum = window.document.qs.Rent_Minimum.value;
            //Rent_Maximum = window.document.qs.Rent_Maximum.value;

            Rent_Minimum = '0';
            Rent_Maximum = '99999';

            if (theZip.length > 0) {
                return loadZS();
            }
            else if (theState != "") {
                return loadQS();
            }

            else {
                alert("Please enter a \"city and state\" or \"zipcode\".");
                return false;
            }
        }

        function loadQS(w) {
            var theCity;
            var theState;
            var radius;
            var bool;

            //Rent_Minimum = window.document.qs.Rent_Minimum.value;
            //Rent_Maximum = window.document.qs.Rent_Maximum.value;

            Rent_Minimum = '0';
            Rent_Maximum = '99999';

            bedrooms = window.document.qs.bedrooms.options[window.document.qs.bedrooms.selectedIndex].value;
            //bedrooms = 'AllSizes';

            if (bedrooms == 'studio') {
                bedrooms1 = '&studio=0';
            }
            else if (bedrooms == 'onebdrm') {
                bedrooms1 = '&onebdrm=0';
            }
            else if (bedrooms == 'twobdrm') {
                bedrooms1 = '&twobdrm=0';
            }
            else if (bedrooms == 'threebdrm') {
                bedrooms1 = '&threebdrm=0';
            }
            else if (bedrooms == 'fourbdrm') {
                bedrooms1 = '&fourbdrm=0';
            }
            else if (bedrooms == 'fivebdrm') {
                bedrooms1 = '&fivebdrm=0';
            }
            else {
                bedrooms1 = ''
            }

            if (Rent_Minimum == "" || Rent_Maximum == "") {
                alert('Rent fields cannot be empty');
                return false;
            }
            else if (!IsNumeric(Rent_Minimum)) {
                alert('Rent fields should have numeric values only');
                document.qs.Rent_Minimum.focus();
                document.qs.Rent_Minimum.value = 0;

                return false;
            }
            else if (!IsNumeric(Rent_Maximum)) {
                alert('Rent fields should have numeric values only');
                document.qs.Rent_Maximum.focus();
                document.qs.Rent_Maximum.value = 99999;

                return false;
            }

            theCity = window.document.qs.city.value;
            theState = window.document.qs.state.options[window.document.qs.state.selectedIndex].value;
            radius = window.document.qs.rad.options[window.document.qs.rad.selectedIndex].value;

            if (radius == '5' || radius == '10' || radius == '20') {
                if (theCity == "") {
                    alert("Please enter a City");
                    document.qs.city.focus();
                    return false;
                }
            }

            bool = validateForm(theState);
            if (bool == "n") {
                return false;
            }
            else {
                theCity = encodeURIComponent(theCity);

                var qString;
                if (document.qs.rad.value == '5' || document.qs.rad.value == '10' || document.qs.rad.value == '20') {
                    qString = "http://www.apartments.com/partner/Rent.aspx?page=rent&stype=city&city=" + theCity + "&state=" + theState + "&rad=" + radius + "&partner=green%20bay" + "&Rent_Minimum=" + Rent_Minimum + "&Rent_Maximum=" + Rent_Maximum + bedrooms1;
                }
                else {
                    qString = "http://www.apartments.com/partner/Results.aspx?page=results&stype=city&city=" + theCity + "&state=" + theState + "&partner=green%20bay" + "&Rent_Minimum=" + Rent_Minimum + "&Rent_Maximum=" + Rent_Maximum + bedrooms1;
                }
                window.document.location = qString;

                return false;
            }
        }

        function validateForm(st) {
            var daState = st;

            if (daState == "") {
                alert("Please Select a State from the menu")
                return ("n");
            }
            else {
                return ("y");
            }
        }
        //NxB

        function loadZS() {
            var theZip;
            var bool;
            //Rent_Minimum = window.document.qs.Rent_Minimum.value;
            //Rent_Maximum = window.document.qs.Rent_Maximum.value;
            Rent_Minimum = '0';
            Rent_Maximum = '99999';
            bedrooms = window.document.qs.bedrooms.options[window.document.qs.bedrooms.selectedIndex].value;
            //bedrooms = 'AllSizes';
            if (bedrooms == 'studio') {
                bedrooms1 = '&studio=0';
            }
            else if (bedrooms == 'onebdrm') {
                bedrooms1 = '&onebdrm=0';
            }
            else if (bedrooms == 'twobdrm') {
                bedrooms1 = '&twobdrm=0';
            }
            else if (bedrooms == 'threebdrm') {
                bedrooms1 = '&threebdrm=0';
            }
            else {
                bedrooms1 = ''
            }

            if (Rent_Minimum == "" || Rent_Maximum == "") {
                alert('Rent fields cannot be empty');
                return false;
            }
            else if (!IsNumeric(Rent_Minimum)) {
                alert('Rent fields should have numeric values only');
                document.qs.Rent_Minimum.focus();
                document.qs.Rent_Minimum.value = 0;

                return false;
            }
            else if (!IsNumeric(Rent_Maximum)) {
                alert('Rent fields should have numeric values only');
                document.qs.Rent_Maximum.focus();
                document.qs.Rent_Maximum.value = 99999;

                return false;
            }

            theZip = document.qs.zip.value;
            theZip = encodeURIComponent(theZip);

            if (theZip.length < 5) {
                alert("Please enter one/multiple 5 digit zip code/s seperated by commas");
                return false;
            }

            var qString;
            if (document.qs.rad.value == '5' || document.qs.rad.value == '10' || document.qs.rad.value == '20') {
                qString = "http://www.apartments.com/partner/Results.aspx?page=results&stype=zip&zip=" + theZip + "&rad=" + document.qs.rad.value + "&partner=green%20bay" + "&Rent_Minimum=" + Rent_Minimum + "&Rent_Maximum=" + Rent_Maximum + bedrooms1;
            }
            else {
                qString = "http://www.apartments.com/partner/Results.aspx?page=results&stype=zip&zip=" + theZip + "&partner=green%20bay" + "&Rent_Minimum=" + Rent_Minimum + "&Rent_Maximum=" + Rent_Maximum + bedrooms1;
            }
            window.document.location = qString;
            return false;
        }

        function ValidateZip(WebSearchFrm) {
            if (WebSearchFrm.txtZipCode.value == "") {
                alert('Missing ZIP information. Please enter a ZIP code to post a listing')
                WebSearchFrm.txtZipCode.focus();
                return false;
            }

            if (!IsNumeric(WebSearchFrm.txtZipCode.value)) {
                alert('Please enter only numbers in the Zip Code')
                WebSearchFrm.txtZipCode.focus();
                return false;
            }
            return true;
        }

        function Form_Validation(WebSearchFrm) {
            if (WebSearchFrm.txtContactEmail.value == "") {
                alert("Please enter a value for the Email field.");
                WebSearchFrm.txtContactEmail.focus();
                return (false);
            }

            var checkOK = "_ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz&#402;&#352;&#338;&#353;&#339;&#376;Ãƒâ‚¬ÃƒÂÃƒâ€šÃƒÆ’ÃƒÃƒâ€¦Ãƒâ€ Ãƒâ€¡ÃƒË†Ãƒâ€°ÃƒÅ Ãƒâ€¹ÃƒÅ’ÃƒÂÃƒÅ½ÃƒÂÃƒÂÃƒ'Ãƒ'ÃƒÃƒÃƒâ€¢Ãƒâ€“ÃƒËœÃƒâ„¢ÃƒÅ¡Ãƒâ€ºÃƒÅ“ÃƒÂÃƒÅ¾ÃƒÅ¸Ãƒ ÃƒÂ¡ÃƒÂ¢ÃƒÂ£ÃƒÂ¤ÃƒÂ¥ÃƒÂ¦ÃƒÂ§ÃƒÂ¨ÃƒÂ©ÃƒÂªÃƒÂ«ÃƒÂ¬ÃƒÂ­ÃƒÂ®ÃƒÂ¯ÃƒÂ°ÃƒÂ±ÃƒÂ²ÃƒÂ³ÃƒÂ´ÃƒÂµÃƒÂ¶ÃƒÂ¸ÃƒÂ¹ÃƒÂºÃƒÂ»ÃƒÂ¼ÃƒÂ½ÃƒÂ¾0123456789-@. \t\r\n\f";
            var checkStr = WebSearchFrm.txtContactEmail.value;
            var allValid = true;
            for (i = 0; i < checkStr.length; i++) {
                ch = checkStr.charAt(i);
                for (j = 0; j < checkOK.length; j++)
                    if (ch == checkOK.charAt(j))
                        break;
                if (j == checkOK.length) {
                    allValid = false;
                    break;
                }
            }
            if (!allValid) {
                alert("Please enter only letter, digit, whitespace and \"@.\" characters in the Contact Email field.");
                WebSearchFrm.txtContactEmail.focus();
                return (false);
            }

            if (!(IsEmailValid(WebSearchFrm.txtContactEmail)))
                return (false);

            return true;
        }

        function IsNumeric(sText) {
            var ValidChars = "0123456789";
            var IsNumber = true;
            var Char;

            for (i = 0; i < sText.length && IsNumber == true; i++) {
                Char = sText.charAt(i);
                if (ValidChars.indexOf(Char) == -1) {
                    IsNumber = false;
                }
            }
            return IsNumber;
        }

        function IsEmailValid(ElemName) {
            var EmailOk = true;
            var Temp = ElemName;
            var AtSym = Temp.value.indexOf('@');
            var Period = Temp.value.lastIndexOf('.');
            var Space = Temp.value.indexOf(' ');
            var Length = Temp.value.length - 1;   // Array is from 0 to length-1
            if ((AtSym < 1) ||                     // '@' cannot be in first position
                (Period <= AtSym + 1) ||             // Must be atleast one valid char btwn '@' and '.'
                (Period == Length ) ||             // Must be atleast one valid char after '.'
                (Space != -1))                    // No empty spaces permitted
            {
                EmailOk = false;
                alert('Please enter a valid e-mail address!');
                Temp.focus();
            }
            return EmailOk;
        }

        //~NxB
        //-->

    </script>
</head>

<body>
<header role="banner" class="navbar navbar-inverse bs-docs-nav">
    <div class="container">
        <?php echo $nav->getTopNavigation($siteUrl, $palette, $siteName, $siteTopData, $imgSiteName); ?>
    </div>
</header>

<div class="container main">
    <div class="col-md-8 border">
        <img class="img-responsive" src="img/rental.png">

        <h1>Check out Rentals in Your Area.</h1>

        <h3>Easily find your next rental home, apartment, townhome or condo.</h3>
        <a style="margin-bottom:25px;"
           href="http://<?php echo "$siteClassUrl"; ?>/category.php?place=Rentals"
           class="btn btn-primary btn-lg">
            <span style="font-weight:bolder;font-size:24px;">Search Here</span> for Local listings</a>
    </div>
    <div class="col-md-1">&nbsp;</div>
    <div class="col-xs-8 col-sm-4 col-md-3 border">
        <a href="http://www.apartments.com/"><img src="img/apartments.jpg" alt="APARTMENTS.COM" width="80%"
                                                  height="80%"/></a>

        <form name="qs" onSubmit="return deterLoad();">
            <div style="margin-top:10px;" id="apts_form_area">
                <div class="field_col_1 input-group">
                    <input name="city" type="text" size="20" id="city" class="apts form-control input-lg" tabindex="1"
                           placeholder="City or ZIP"/>
                    <input src="img/btn_search.gif" onclick="return deterLoader()" type="image" tabindex="8">
                </div>
                <input name="zip" type="text" size="6" class="apts  input-lg" tabindex="3" style="display:none"/>
                <div class="field_col_1">
                    <p class="form_label">State:</p>
                    <select name="state" class="apts form-control" tabindex="2">
                        <?php
                        foreach ($us_state_abbrevs_names as $abbrevs => $names) {
                            echo '<option value="'.$abbrevs.'" ';
                            if (strtoupper($siteState) == $names) {echo 'selected="selected"';}
                            echo ">$abbrevs</option>";
                        }
                        ?>
                    </select>
                </div>
                <div style="display:none" class="field_col_3">
                    <p class="form_label">Radius:</p>
                    <select name="rad" class="apts" tabindex="4">
                        <option value="0" selected="selected">Any</option>
                        <option value="5">5 miles</option>
                        <option value="10">10 miles</option>
                        <option value="20">20 miles</option>
                    </select>
                </div>
                <div style="display:none" class="field_col_4">
                    <p class="form_label">Beds:</p>
                    <select name="bedrooms" class="apts" tabindex="7">
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
                    <?php
                        //$qString = "http://www.apartments.com/partner/Results.aspx?page=results&stype=city&city=" + theCity + "&state=" + theState + "&partner=green%20bay" + "&Rent_Minimum=" + Rent_Minimum + "&Rent_Maximum=" + Rent_Maximum + bedrooms1;
                        $qString = "http://www.apartments.com/search/?query=$siteCity,%20$siteState&stype=CityStateOrZip&frontdoor=$siteCity&partner=$siteCity&rentmin=0&rentmax=99999";
                    ?>
                    <strong>Quick Link:</strong> <a target="_blank" href="<?php echo $qString; ?>"><?php echo $siteCity; ?></a>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

</div>
<!--<div class="box"> <span class="heading">OTHER RENTALS</span> -->
<!--include virtual="/realestate/incs/rentals-classlinks.inc"-->
<!--</div>-->
</div>

</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="3rdParty/jquery/jquery.min.js"><\/script>')</script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

<script language="JavaScript" type="text/javascript">
    var s_account = "gpaper186"
    var s = s_gi(s_account)
    /************************** CONFIG SECTION **************************/
    /* You may add or alter any code config here. */
    s.charSet = "ISO-8859-1"
    /* Conversion Config */
    s.currencyCode = "USD"
    /* Link Tracking Config */
    s.trackDownloadLinks = true
    s.trackExternalLinks = true
    s.trackInlineStats = true
    s.linkDownloadFileTypes = "exe,zip,wav,mp3,mov,mpg,avi,wmv,pdf,doc,docx,xls,xlsx,ppt,pptx"
    s.linkInternalFilters = "javascript:,wisinfo,apartments,cars,carcast,homefinder,homescape,homehunter,newhomenetwork,careers.usatoday,usatodaycareers,gcijobs,careerpath,space,event411,infi,wire.ap.org,datemaker,e-thepeople,asp.usatoday,employers.usatoday,resume.usatoday,usatoday.salary,jobmanager.usatoday,gannettnewsservices,customwire.ap.org,careerbuilder,customcoupon.com,winwithhollywood,Glimpse.com,Matchnet.com,Jdate.com,Eharmony.com,newslibrary.com,newsbank.com,Americansingles.com,hosted.ap,infi.net,gbahn.net,gannettonline.com,careerbuilder.com,cars.com,apartments.com,homescape.com,shoplocal.com,gmti.com,postcrescent,momslikeme.com,highschoolsports.net,metromix"
    s.linkLeaveQueryString = false
    s.linkTrackVars = "None"
    s.linkTrackEvents = "None"

    /* WARNING: Changing any of the below variables will cause drastic
     changes to how your visitor data is collected.  Changes should only be
     made when instructed to do so by your account manager.*/
    //s.trackingServer="gannett.112.2o7.net"

    s.trackingServer = "repdata.postcrescent.com"


    /************* DO NOT ALTER ANYTHING BELOW THIS LINE ! **************/
    var s_code = '', s_objectID;
    function s_gi(un, pg, ss) {
        var c = "s.version='H.27.2';s.an=s_an;s.logDebug=function(m){var s=this,tcf=new Function('var e;try{console.log(\"'+s.rep(s.rep(s.rep(m,\"\\\\\",\"\\\\"
                + "\\\\\"),\"\\n\",\"\\\\n\"),\"\\\"\",\"\\\\\\\"\")+'\");}catch(e){}');tcf()};s.cls=function(x,c){var i,y='';if(!c)c=this.an;for(i=0;i<x.length;i++){n=x.substring(i,i+1);if(c.indexOf(n)>=0)y+=n}retur"
                + "n y};s.fl=function(x,l){return x?(''+x).substring(0,l):x};s.co=function(o){return o};s.num=function(x){x=''+x;for(var p=0;p<x.length;p++)if(('0123456789').indexOf(x.substring(p,p+1))<0)return 0;ret"
                + "urn 1};s.rep=s_rep;s.sp=s_sp;s.jn=s_jn;s.ape=function(x){var s=this,h='0123456789ABCDEF',f=\"+~!*()'\",i,c=s.charSet,n,l,e,y='';c=c?c.toUpperCase():'';if(x){x=''+x;if(s.em==3){x=encodeURIComponent("
                + "x);for(i=0;i<f.length;i++) {n=f.substring(i,i+1);if(x.indexOf(n)>=0)x=s.rep(x,n,\"%\"+n.charCodeAt(0).toString(16).toUpperCase())}}else if(c=='AUTO'&&('').charCodeAt){for(i=0;i<x.length;i++){c=x.su"
                + "bstring(i,i+1);n=x.charCodeAt(i);if(n>127){l=0;e='';while(n||l<4){e=h.substring(n%16,n%16+1)+e;n=(n-n%16)/16;l++}y+='%u'+e}else if(c=='+')y+='%2B';else y+=escape(c)}x=y}else x=s.rep(escape(''+x),'+"
                + "','%2B');if(c&&c!='AUTO'&&s.em==1&&x.indexOf('%u')<0&&x.indexOf('%U')<0){i=x.indexOf('%');while(i>=0){i++;if(h.substring(8).indexOf(x.substring(i,i+1).toUpperCase())>=0)return x.substring(0,i)+'u00"
                + "'+x.substring(i);i=x.indexOf('%',i)}}}return x};s.epa=function(x){var s=this,y,tcf;if(x){x=s.rep(''+x,'+',' ');if(s.em==3){tcf=new Function('x','var y,e;try{y=decodeURIComponent(x)}catch(e){y=unesc"
                + "ape(x)}return y');return tcf(x)}else return unescape(x)}return y};s.pt=function(x,d,f,a){var s=this,t=x,z=0,y,r;while(t){y=t.indexOf(d);y=y<0?t.length:y;t=t.substring(0,y);r=s[f](t,a);if(r)return r"
                + ";z+=y+d.length;t=x.substring(z,x.length);t=z<x.length?t:''}return ''};s.isf=function(t,a){var c=a.indexOf(':');if(c>=0)a=a.substring(0,c);c=a.indexOf('=');if(c>=0)a=a.substring(0,c);if(t.substring("
                + "0,2)=='s_')t=t.substring(2);return (t!=''&&t==a)};s.fsf=function(t,a){var s=this;if(s.pt(a,',','isf',t))s.fsg+=(s.fsg!=''?',':'')+t;return 0};s.fs=function(x,f){var s=this;s.fsg='';s.pt(x,',','fsf'"
                + ",f);return s.fsg};s.mpc=function(m,a){var s=this,c,l,n,v;v=s.d.visibilityState;if(!v)v=s.d.webkitVisibilityState;if(v&&v=='prerender'){if(!s.mpq){s.mpq=new Array;l=s.sp('webkitvisibilitychange,visi"
                + "bilitychange',',');for(n=0;n<l.length;n++){s.d.addEventListener(l[n],new Function('var s=s_c_il['+s._in+'],c,v;v=s.d.visibilityState;if(!v)v=s.d.webkitVisibilityState;if(s.mpq&&v==\"visible\"){whil"
                + "e(s.mpq.length>0){c=s.mpq.shift();s[c.m].apply(s,c.a)}s.mpq=0}'),false)}}c=new Object;c.m=m;c.a=a;s.mpq.push(c);return 1}return 0};s.si=function(){var s=this,i,k,v,c=s_gi+'var s=s_gi(\"'+s.oun+'\")"
                + ";s.sa(\"'+s.un+'\");';for(i=0;i<s.va_g.length;i++){k=s.va_g[i];v=s[k];if(v!=undefined){if(typeof(v)!='number')c+='s.'+k+'=\"'+s_fe(v)+'\";';else c+='s.'+k+'='+v+';'}}c+=\"s.lnk=s.eo=s.linkName=s.li"
                + "nkType=s.wd.s_objectID=s.ppu=s.pe=s.pev1=s.pev2=s.pev3='';\";return c};s.c_d='';s.c_gdf=function(t,a){var s=this;if(!s.num(t))return 1;return 0};s.c_gd=function(){var s=this,d=s.wd.location.hostnam"
                + "e,n=s.fpCookieDomainPeriods,p;if(!n)n=s.cookieDomainPeriods;if(d&&!s.c_d){n=n?parseInt(n):2;n=n>2?n:2;p=d.lastIndexOf('.');if(p>=0){while(p>=0&&n>1){p=d.lastIndexOf('.',p-1);n--}s.c_d=p>0&&s.pt(d,'"
                + ".','c_gdf',0)?d.substring(p):d}}return s.c_d};s.c_r=function(k){var s=this;k=s.ape(k);var c=' '+s.d.cookie,i=c.indexOf(' '+k+'='),e=i<0?i:c.indexOf(';',i),v=i<0?'':s.epa(c.substring(i+2+k.length,e<"
                + "0?c.length:e));return v!='[[B]]'?v:''};s.c_w=function(k,v,e){var s=this,d=s.c_gd(),l=s.cookieLifetime,t;v=''+v;l=l?(''+l).toUpperCase():'';if(e&&l!='SESSION'&&l!='NONE'){t=(v!=''?parseInt(l?l:0):-6"
                + "0);if(t){e=new Date;e.setTime(e.getTime()+(t*1000))}}if(k&&l!='NONE'){s.d.cookie=k+'='+s.ape(v!=''?v:'[[B]]')+'; path=/;'+(e&&l!='SESSION'?' expires='+e.toGMTString()+';':'')+(d?' domain='+d+';':''"
                + ");return s.c_r(k)==v}return 0};s.eh=function(o,e,r,f){var s=this,b='s_'+e+'_'+s._in,n=-1,l,i,x;if(!s.ehl)s.ehl=new Array;l=s.ehl;for(i=0;i<l.length&&n<0;i++){if(l[i].o==o&&l[i].e==e)n=i}if(n<0){n=i"
                + ";l[n]=new Object}x=l[n];x.o=o;x.e=e;f=r?x.b:f;if(r||f){x.b=r?0:o[e];x.o[e]=f}if(x.b){x.o[b]=x.b;return b}return 0};s.cet=function(f,a,t,o,b){var s=this,r,tcf;if(s.apv>=5&&(!s.isopera||s.apv>=7)){tc"
                + "f=new Function('s','f','a','t','var e,r;try{r=s[f](a)}catch(e){r=s[t](e)}return r');r=tcf(s,f,a,t)}else{if(s.ismac&&s.u.indexOf('MSIE 4')>=0)r=s[b](a);else{s.eh(s.wd,'onerror',0,o);r=s[f](a);s.eh(s"
                + ".wd,'onerror',1)}}return r};s.gtfset=function(e){var s=this;return s.tfs};s.gtfsoe=new Function('e','var s=s_c_il['+s._in+'],c;s.eh(window,\"onerror\",1);s.etfs=1;c=s.t();if(c)s.d.write(c);s.etfs=0"
                + ";return true');s.gtfsfb=function(a){return window};s.gtfsf=function(w){var s=this,p=w.parent,l=w.location;s.tfs=w;if(p&&p.location!=l&&p.location.host==l.host){s.tfs=p;return s.gtfsf(s.tfs)}return "
                + "s.tfs};s.gtfs=function(){var s=this;if(!s.tfs){s.tfs=s.wd;if(!s.etfs)s.tfs=s.cet('gtfsf',s.tfs,'gtfset',s.gtfsoe,'gtfsfb')}return s.tfs};s.mrq=function(u){var s=this,l=s.rl[u],n,r;s.rl[u]=0;if(l)fo"
                + "r(n=0;n<l.length;n++){r=l[n];s.mr(0,0,r.r,r.t,r.u)}};s.flushBufferedRequests=function(){};s.mr=function(sess,q,rs,ta,u){var s=this,dc=s.dc,t1=s.trackingServer,t2=s.trackingServerSecure,tb=s.trackin"
                + "gServerBase,p='.sc',ns=s.visitorNamespace,un=s.cls(u?u:(ns?ns:s.fun)),r=new Object,l,imn='s_i_'+s._in+'_'+un,im,b,e;if(!rs){if(t1){if(t2&&s.ssl)t1=t2}else{if(!tb)tb='2o7.net';if(dc)dc=(''+dc).toLow"
                + "erCase();else dc='d1';if(tb=='2o7.net'){if(dc=='d1')dc='112';else if(dc=='d2')dc='122';p=''}t1=un+'.'+dc+'.'+p+tb}rs='http'+(s.ssl?'s':'')+'://'+t1+'/b/ss/'+s.un+',gntbcstglobal/'+(s.mobile?'5.1':'1')+'/'+s.vers"
                + "ion+(s.tcn?'T':'')+'/'+sess+'?AQB=1&ndh=1'+(q?q:'')+'&AQE=1';if(s.isie&&!s.ismac)rs=s.fl(rs,2047)}if(s.d.images&&s.apv>=3&&(!s.isopera||s.apv>=7)&&(s.ns6<0||s.apv>=6.1)){if(!s.rc)s.rc=new Object;if"
                + "(!s.rc[un]){s.rc[un]=1;if(!s.rl)s.rl=new Object;s.rl[un]=new Array;setTimeout('if(window.s_c_il)window.s_c_il['+s._in+'].mrq(\"'+un+'\")',750)}else{l=s.rl[un];if(l){r.t=ta;r.u=un;r.r=rs;l[l.length]"
                + "=r;return ''}imn+='_'+s.rc[un];s.rc[un]++}if(s.debugTracking){var d='AppMeasurement Debug: '+rs,dl=s.sp(rs,'&'),dln;for(dln=0;dln<dl.length;dln++)d+=\"\\n\\t\"+s.epa(dl[dln]);s.logDebug(d)}im=s.wd["
                + "imn];if(!im)im=s.wd[imn]=new Image;im.alt=\"\";im.s_l=0;im.onload=im.onerror=new Function('e','this.s_l=1;var wd=window,s;if(wd.s_c_il){s=wd.s_c_il['+s._in+'];s.bcr();s.mrq(\"'+un+'\");s.nrs--;if(!"
                + "s.nrs)s.m_m(\"rr\")}');if(!s.nrs){s.nrs=1;s.m_m('rs')}else s.nrs++;im.src=rs;if(s.useForcedLinkTracking||s.bcf){if(!s.forcedLinkTrackingTimeout)s.forcedLinkTrackingTimeout=250;setTimeout('if(window"
                + ".s_c_il)window.s_c_il['+s._in+'].bcr()',s.forcedLinkTrackingTimeout);}else if((s.lnk||s.eo)&&(!ta||ta=='_self'||ta=='_top'||ta=='_parent'||(s.wd.name&&ta==s.wd.name))){b=e=new Date;while(!im.s_l&&e"
                + ".getTime()-b.getTime()<500)e=new Date}return ''}return '<im'+'g sr'+'c=\"'+rs+'\" width=1 height=1 border=0 alt=\"\">'};s.gg=function(v){var s=this;if(!s.wd['s_'+v])s.wd['s_'+v]='';return s.wd['s_'"
                + "+v]};s.glf=function(t,a){if(t.substring(0,2)=='s_')t=t.substring(2);var s=this,v=s.gg(t);if(v)s[t]=v};s.gl=function(v){var s=this;if(s.pg)s.pt(v,',','glf',0)};s.rf=function(x){var s=this,y,i,j,h,p,"
                + "l=0,q,a,b='',c='',t;if(x&&x.length>255){y=''+x;i=y.indexOf('?');if(i>0){q=y.substring(i+1);y=y.substring(0,i);h=y.toLowerCase();j=0;if(h.substring(0,7)=='http://')j+=7;else if(h.substring(0,8)=='ht"
                + "tps://')j+=8;i=h.indexOf(\"/\",j);if(i>0){h=h.substring(j,i);p=y.substring(i);y=y.substring(0,i);if(h.indexOf('google')>=0)l=',q,ie,start,search_key,word,kw,cd,';else if(h.indexOf('yahoo.co')>=0)l="
                + "',p,ei,';if(l&&q){a=s.sp(q,'&');if(a&&a.length>1){for(j=0;j<a.length;j++){t=a[j];i=t.indexOf('=');if(i>0&&l.indexOf(','+t.substring(0,i)+',')>=0)b+=(b?'&':'')+t;else c+=(c?'&':'')+t}if(b&&c)q=b+'&'"
                + "+c;else c=''}i=253-(q.length-c.length)-y.length;x=y+(i>0?p.substring(0,i):'')+'?'+q}}}}return x};s.s2q=function(k,v,vf,vfp,f){var s=this,qs='',sk,sv,sp,ss,nke,nk,nf,nfl=0,nfn,nfm;if(k==\"contextDat"
                + "a\")k=\"c\";if(v){for(sk in v)if((!f||sk.substring(0,f.length)==f)&&v[sk]&&(!vf||vf.indexOf(','+(vfp?vfp+'.':'')+sk+',')>=0)&&(!Object||!Object.prototype||!Object.prototype[sk])){nfm=0;if(nfl)for(n"
                + "fn=0;nfn<nfl.length;nfn++)if(sk.substring(0,nfl[nfn].length)==nfl[nfn])nfm=1;if(!nfm){if(qs=='')qs+='&'+k+'.';sv=v[sk];if(f)sk=sk.substring(f.length);if(sk.length>0){nke=sk.indexOf('.');if(nke>0){n"
                + "k=sk.substring(0,nke);nf=(f?f:'')+nk+'.';if(!nfl)nfl=new Array;nfl[nfl.length]=nf;qs+=s.s2q(nk,v,vf,vfp,nf)}else{if(typeof(sv)=='boolean'){if(sv)sv='true';else sv='false'}if(sv){if(vfp=='retrieveLi"
                + "ghtData'&&f.indexOf('.contextData.')<0){sp=sk.substring(0,4);ss=sk.substring(4);if(sk=='transactionID')sk='xact';else if(sk=='channel')sk='ch';else if(sk=='campaign')sk='v0';else if(s.num(ss)){if(s"
                + "p=='prop')sk='c'+ss;else if(sp=='eVar')sk='v'+ss;else if(sp=='list')sk='l'+ss;else if(sp=='hier'){sk='h'+ss;sv=sv.substring(0,255)}}}qs+='&'+s.ape(sk)+'='+s.ape(sv)}}}}}if(qs!='')qs+='&.'+k}return "
                + "qs};s.hav=function(){var s=this,qs='',l,fv='',fe='',mn,i,e;if(s.lightProfileID){l=s.va_m;fv=s.lightTrackVars;if(fv)fv=','+fv+','+s.vl_mr+','}else{l=s.va_t;if(s.pe||s.linkType){fv=s.linkTrackVars;fe"
                + "=s.linkTrackEvents;if(s.pe){mn=s.pe.substring(0,1).toUpperCase()+s.pe.substring(1);if(s[mn]){fv=s[mn].trackVars;fe=s[mn].trackEvents}}}if(fv)fv=','+fv+','+s.vl_l+','+s.vl_l2;if(fe){fe=','+fe+',';if"
                + "(fv)fv+=',events,'}if (s.events2)e=(e?',':'')+s.events2}for(i=0;i<l.length;i++){var k=l[i],v=s[k],b=k.substring(0,4),x=k.substring(4),n=parseInt(x),q=k;if(!v)if(k=='events'&&e){v=e;e=''}if(v&&(!fv|"
                + "|fv.indexOf(','+k+',')>=0)&&k!='linkName'&&k!='linkType'){if(k=='supplementalDataID')q='sdid';else if(k=='timestamp')q='ts';else if(k=='dynamicVariablePrefix')q='D';else if(k=='visitorID')q='vid';e"
                + "lse if(k=='marketingCloudVisitorID')q='mid';else if(k=='analyticsVisitorID')q='aid';else if(k=='audienceManagerLocationHint')q='aamlh';else if(k=='audienceManagerBlob')q='aamb';else if(k=='pageURL'"
                + "){q='g';if(v.length>255){s.pageURLRest=v.substring(255);v=v.substring(0,255);}}else if(k=='pageURLRest')q='-g';else if(k=='referrer'){q='r';v=s.fl(s.rf(v),255)}else if(k=='vmk'||k=='visitorMigratio"
                + "nKey')q='vmt';else if(k=='visitorMigrationServer'){q='vmf';if(s.ssl&&s.visitorMigrationServerSecure)v=''}else if(k=='visitorMigrationServerSecure'){q='vmf';if(!s.ssl&&s.visitorMigrationServer)v=''}"
                + "else if(k=='charSet'){q='ce';if(v.toUpperCase()=='AUTO')v='ISO8859-1';else if(s.em==2||s.em==3)v='UTF-8'}else if(k=='visitorNamespace')q='ns';else if(k=='cookieDomainPeriods')q='cdp';else if(k=='co"
                + "okieLifetime')q='cl';else if(k=='variableProvider')q='vvp';else if(k=='currencyCode')q='cc';else if(k=='channel')q='ch';else if(k=='transactionID')q='xact';else if(k=='campaign')q='v0';else if(k=='"
                + "resolution')q='s';else if(k=='colorDepth')q='c';else if(k=='javascriptVersion')q='j';else if(k=='javaEnabled')q='v';else if(k=='cookiesEnabled')q='k';else if(k=='browserWidth')q='bw';else if(k=='br"
                + "owserHeight')q='bh';else if(k=='connectionType')q='ct';else if(k=='homepage')q='hp';else if(k=='plugins')q='p';else if(k=='events'){if(e)v+=(v?',':'')+e;if(fe)v=s.fs(v,fe)}else if(k=='events2')v=''"
                + ";else if(k=='contextData'){qs+=s.s2q('c',s[k],fv,k,0);v=''}else if(k=='lightProfileID')q='mtp';else if(k=='lightStoreForSeconds'){q='mtss';if(!s.lightProfileID)v=''}else if(k=='lightIncrementBy'){q"
                + "='mti';if(!s.lightProfileID)v=''}else if(k=='retrieveLightProfiles')q='mtsr';else if(k=='deleteLightProfiles')q='mtsd';else if(k=='retrieveLightData'){if(s.retrieveLightProfiles)qs+=s.s2q('mts',s[k"
                + "],fv,k,0);v=''}else if(s.num(x)){if(b=='prop')q='c'+n;else if(b=='eVar')q='v'+n;else if(b=='list')q='l'+n;else if(b=='hier'){q='h'+n;v=s.fl(v,255)}}if(v)qs+='&'+s.ape(q)+'='+(k.substring(0,3)!='pev"
                + "'?s.ape(v):v)}}return qs};s.ltdf=function(t,h){t=t?t.toLowerCase():'';h=h?h.toLowerCase():'';var qi=h.indexOf('?'),hi=h.indexOf('#');if(qi>=0){if(hi>=0&&hi<qi)qi=hi;}else qi=hi;h=qi>=0?h.substring("
                + "0,qi):h;if(t&&h.substring(h.length-(t.length+1))=='.'+t)return 1;return 0};s.ltef=function(t,h){t=t?t.toLowerCase():'';h=h?h.toLowerCase():'';if(t&&h.indexOf(t)>=0)return 1;return 0};s.lt=function("
                + "h){var s=this,lft=s.linkDownloadFileTypes,lef=s.linkExternalFilters,lif=s.linkInternalFilters;lif=lif?lif:s.wd.location.hostname;h=h.toLowerCase();if(s.trackDownloadLinks&&lft&&s.pt(lft,',','ltdf',"
                + "h))return 'd';if(s.trackExternalLinks&&h.indexOf('#')!=0&&h.indexOf('about:')!=0&&h.indexOf('javascript:')!=0&&(lef||lif)&&(!lef||s.pt(lef,',','ltef',h))&&(!lif||!s.pt(lif,',','ltef',h)))return 'e'"
                + ";return ''};s.lc=new Function('e','var s=s_c_il['+s._in+'],b=s.eh(this,\"onclick\");s.lnk=this;s.t();s.lnk=0;if(b)return this[b](e);return true');s.bcr=function(){var s=this;if(s.bct&&s.bce)s.bct.d"
                + "ispatchEvent(s.bce);if(s.bcf){if(typeof(s.bcf)=='function')s.bcf();else if(s.bct&&s.bct.href)s.d.location=s.bct.href}s.bct=s.bce=s.bcf=0};s.bc=new Function('e','if(e&&e.s_fe)return;var s=s_c_il['+s"
                + "._in+'],f,tcf,t,n,nrs,a,h;if(s.d&&s.d.all&&s.d.all.cppXYctnr)return;if(!s.bbc)s.useForcedLinkTracking=0;else if(!s.useForcedLinkTracking){s.b.removeEventListener(\"click\",s.bc,true);s.bbc=s.useFor"
                + "cedLinkTracking=0;return}else s.b.removeEventListener(\"click\",s.bc,false);s.eo=e.srcElement?e.srcElement:e.target;nrs=s.nrs;s.t();s.eo=0;if(s.nrs>nrs&&s.useForcedLinkTracking&&e.target){a=e.targe"
                + "t;while(a&&a!=s.b&&a.tagName.toUpperCase()!=\"A\"&&a.tagName.toUpperCase()!=\"AREA\")a=a.parentNode;if(a){h=a.href;if(h.indexOf(\"#\")==0||h.indexOf(\"about:\")==0||h.indexOf(\"javascript:\")==0)h="
                + "0;t=a.target;if(e.target.dispatchEvent&&h&&(!t||t==\"_self\"||t==\"_top\"||t==\"_parent\"||(s.wd.name&&t==s.wd.name))){tcf=new Function(\"s\",\"var x;try{n=s.d.createEvent(\\\\\"MouseEvents\\\\\")}"
                + "catch(x){n=new MouseEvent}return n\");n=tcf(s);if(n){tcf=new Function(\"n\",\"e\",\"var x;try{n.initMouseEvent(\\\\\"click\\\\\",e.bubbles,e.cancelable,e.view,e.detail,e.screenX,e.screenY,e.clientX"
                + ",e.clientY,e.ctrlKey,e.altKey,e.shiftKey,e.metaKey,e.button,e.relatedTarget)}catch(x){n=0}return n\");n=tcf(n,e);if(n){n.s_fe=1;e.stopPropagation();if (e.stopImmediatePropagation) {e.stopImmediateP"
                + "ropagation();}e.preventDefault();s.bct=e.target;s.bce=n}}}}}');s.oh=function(o){var s=this,l=s.wd.location,h=o.href?o.href:'',i,j,k,p;i=h.indexOf(':');j=h.indexOf('?');k=h.indexOf('/');if(h&&(i<0||"
                + "(j>=0&&i>j)||(k>=0&&i>k))){p=o.protocol&&o.protocol.length>1?o.protocol:(l.protocol?l.protocol:'');i=l.pathname.lastIndexOf('/');h=(p?p+'//':'')+(o.host?o.host:(l.host?l.host:''))+(h.substring(0,1)"
                + "!='/'?l.pathname.substring(0,i<0?0:i)+'/':'')+h}return h};s.ot=function(o){var t=o.tagName;if(o.tagUrn||(o.scopeName&&o.scopeName.toUpperCase()!='HTML'))return '';t=t&&t.toUpperCase?t.toUpperCase()"
                + ":'';if(t=='SHAPE')t='';if(t){if((t=='INPUT'||t=='BUTTON')&&o.type&&o.type.toUpperCase)t=o.type.toUpperCase();else if(!t&&o.href)t='A';}return t};s.oid=function(o){var s=this,t=s.ot(o),p,c,n='',x=0;"
                + "if(t&&!o.s_oid){p=o.protocol;c=o.onclick;if(o.href&&(t=='A'||t=='AREA')&&(!c||!p||p.toLowerCase().indexOf('javascript')<0))n=s.oh(o);else if(c){n=s.rep(s.rep(s.rep(s.rep(''+c,\"\\r\",''),\"\\n\",''"
                + "),\"\\t\",''),' ','');x=2}else if(t=='INPUT'||t=='SUBMIT'){if(o.value)n=o.value;else if(o.innerText)n=o.innerText;else if(o.textContent)n=o.textContent;x=3}else if(o.src&&t=='IMAGE')n=o.src;if(n){o"
                + ".s_oid=s.fl(n,100);o.s_oidt=x}}return o.s_oid};s.rqf=function(t,un){var s=this,e=t.indexOf('='),u=e>=0?t.substring(0,e):'',q=e>=0?s.epa(t.substring(e+1)):'';if(u&&q&&(','+u+',').indexOf(','+un+',')"
                + ">=0){if(u!=s.un&&s.un.indexOf(',')>=0)q='&u='+u+q+'&u=0';return q}return ''};s.rq=function(un){if(!un)un=this.un;var s=this,c=un.indexOf(','),v=s.c_r('s_sq'),q='';if(c<0)return s.pt(v,'&','rqf',un)"
                + ";return s.pt(un,',','rq',0)};s.sqp=function(t,a){var s=this,e=t.indexOf('='),q=e<0?'':s.epa(t.substring(e+1));s.sqq[q]='';if(e>=0)s.pt(t.substring(0,e),',','sqs',q);return 0};s.sqs=function(un,q){v"
                + "ar s=this;s.squ[un]=q;return 0};s.sq=function(q){var s=this,k='s_sq',v=s.c_r(k),x,c=0;s.sqq=new Object;s.squ=new Object;s.sqq[q]='';s.pt(v,'&','sqp',0);s.pt(s.un,',','sqs',q);v='';for(x in s.squ)if"
                + "(x&&(!Object||!Object.prototype||!Object.prototype[x]))s.sqq[s.squ[x]]+=(s.sqq[s.squ[x]]?',':'')+x;for(x in s.sqq)if(x&&(!Object||!Object.prototype||!Object.prototype[x])&&s.sqq[x]&&(x==q||c<2)){v+"
                + "=(v?'&':'')+s.sqq[x]+'='+s.ape(x);c++}return s.c_w(k,v,0)};s.wdl=new Function('e','var s=s_c_il['+s._in+'],r=true,b=s.eh(s.wd,\"onload\"),i,o,oc;if(b)r=this[b](e);for(i=0;i<s.d.links.length;i++){o="
                + "s.d.links[i];oc=o.onclick?\"\"+o.onclick:\"\";if((oc.indexOf(\"s_gs(\")<0||oc.indexOf(\".s_oc(\")>=0)&&oc.indexOf(\".tl(\")<0)s.eh(o,\"onclick\",0,s.lc);}return r');s.wds=function(){var s=this;if(s"
                + ".apv>3&&(!s.isie||!s.ismac||s.apv>=5)){if(s.b&&s.b.attachEvent)s.b.attachEvent('onclick',s.bc);else if(s.b&&s.b.addEventListener){if(s.n&&((s.n.userAgent.indexOf('WebKit')>=0&&s.d.createEvent)||(s."
                + "n.userAgent.indexOf('Firefox/2')>=0&&s.wd.MouseEvent))){s.bbc=1;s.useForcedLinkTracking=1;s.b.addEventListener('click',s.bc,true)}s.b.addEventListener('click',s.bc,false)}else s.eh(s.wd,'onload',0,"
                + "s.wdl)}};s.vs=function(x){var s=this,v=s.visitorSampling,g=s.visitorSamplingGroup,k='s_vsn_'+s.un+(g?'_'+g:''),n=s.c_r(k),e=new Date,y=e.getYear();e.setYear(y+10+(y<1900?1900:0));if(v){v*=100;if(!n"
                + "){if(!s.c_w(k,x,e))return 0;n=x}if(n%10000>v)return 0}return 1};s.dyasmf=function(t,m){if(t&&m&&m.indexOf(t)>=0)return 1;return 0};s.dyasf=function(t,m){var s=this,i=t?t.indexOf('='):-1,n,x;if(i>=0"
                + "&&m){var n=t.substring(0,i),x=t.substring(i+1);if(s.pt(x,',','dyasmf',m))return n}return 0};s.uns=function(){var s=this,x=s.dynamicAccountSelection,l=s.dynamicAccountList,m=s.dynamicAccountMatch,n,"
                + "i;s.un=s.un.toLowerCase();if(x&&l){if(!m)m=s.wd.location.host;if(!m.toLowerCase)m=''+m;l=l.toLowerCase();m=m.toLowerCase();n=s.pt(l,';','dyasf',m);if(n)s.un=n}i=s.un.indexOf(',');s.fun=i<0?s.un:s.u"
                + "n.substring(0,i)};s.sa=function(un){var s=this;if(s.un&&s.mpc('sa',arguments))return;s.un=un;if(!s.oun)s.oun=un;else if((','+s.oun+',').indexOf(','+un+',')<0)s.oun+=','+un;s.uns()};s.m_i=function(n"
                + ",a){var s=this,m,f=n.substring(0,1),r,l,i;if(!s.m_l)s.m_l=new Object;if(!s.m_nl)s.m_nl=new Array;m=s.m_l[n];if(!a&&m&&m._e&&!m._i)s.m_a(n);if(!m){m=new Object,m._c='s_m';m._in=s.wd.s_c_in;m._il=s._"
                + "il;m._il[m._in]=m;s.wd.s_c_in++;m.s=s;m._n=n;m._l=new Array('_c','_in','_il','_i','_e','_d','_dl','s','n','_r','_g','_g1','_t','_t1','_x','_x1','_rs','_rr','_l');s.m_l[n]=m;s.m_nl[s.m_nl.length]=n}"
                + "else if(m._r&&!m._m){r=m._r;r._m=m;l=m._l;for(i=0;i<l.length;i++)if(m[l[i]])r[l[i]]=m[l[i]];r._il[r._in]=r;m=s.m_l[n]=r}if(f==f.toUpperCase())s[n]=m;return m};s.m_a=new Function('n','g','e','if(!g)"
                + "g=\"m_\"+n;var s=s_c_il['+s._in+'],c=s[g+\"_c\"],m,x,f=0;if(s.mpc(\"m_a\",arguments))return;if(!c)c=s.wd[\"s_\"+g+\"_c\"];if(c&&s_d)s[g]=new Function(\"s\",s_ft(s_d(c)));x=s[g];if(!x)x=s.wd[\\'s_\\"
                + "'+g];if(!x)x=s.wd[g];m=s.m_i(n,1);if(x&&(!m._i||g!=\"m_\"+n)){m._i=f=1;if((\"\"+x).indexOf(\"function\")>=0)x(s);else s.m_m(\"x\",n,x,e)}m=s.m_i(n,1);if(m._dl)m._dl=m._d=0;s.dlt();return f');s.m_m="
                + "function(t,n,d,e){t='_'+t;var s=this,i,x,m,f='_'+t,r=0,u;if(s.m_l&&s.m_nl)for(i=0;i<s.m_nl.length;i++){x=s.m_nl[i];if(!n||x==n){m=s.m_i(x);u=m[t];if(u){if((''+u).indexOf('function')>=0){if(d&&e)u=m"
                + "[t](d,e);else if(d)u=m[t](d);else u=m[t]()}}if(u)r=1;u=m[t+1];if(u&&!m[f]){if((''+u).indexOf('function')>=0){if(d&&e)u=m[t+1](d,e);else if(d)u=m[t+1](d);else u=m[t+1]()}}m[f]=1;if(u)r=1}}return r};"
                + "s.m_ll=function(){var s=this,g=s.m_dl,i,o;if(g)for(i=0;i<g.length;i++){o=g[i];if(o)s.loadModule(o.n,o.u,o.d,o.l,o.e,1);g[i]=0}};s.loadModule=function(n,u,d,l,e,ln){var s=this,m=0,i,g,o=0,f1,f2,c=s."
                + "h?s.h:s.b,b,tcf;if(n){i=n.indexOf(':');if(i>=0){g=n.substring(i+1);n=n.substring(0,i)}else g=\"m_\"+n;m=s.m_i(n)}if((l||(n&&!s.m_a(n,g)))&&u&&s.d&&c&&s.d.createElement){if(d){m._d=1;m._dl=1}if(ln){"
                + "if(s.ssl)u=s.rep(u,'http:','https:');i='s_s:'+s._in+':'+n+':'+g;b='var s=s_c_il['+s._in+'],o=s.d.getElementById(\"'+i+'\");if(s&&o){if(!o.l&&s.wd.'+g+'){o.l=1;if(o.i)clearTimeout(o.i);o.i=0;s.m_a("
                + "\"'+n+'\",\"'+g+'\"'+(e?',\"'+e+'\"':'')+')}';f2=b+'o.c++;if(!s.maxDelay)s.maxDelay=250;if(!o.l&&o.c<(s.maxDelay*2)/100)o.i=setTimeout(o.f2,100)}';f1=new Function('e',b+'}');tcf=new Function('s','c"
                + "','i','u','f1','f2','var e,o=0;try{o=s.d.createElement(\"script\");if(o){o.type=\"text/javascript\";'+(n?'o.id=i;o.defer=true;o.onload=o.onreadystatechange=f1;o.f2=f2;o.l=0;':'')+'o.src=u;c.appendC"
                + "hild(o);'+(n?'o.c=0;o.i=setTimeout(f2,100)':'')+'}}catch(e){o=0}return o');o=tcf(s,c,i,u,f1,f2)}else{o=new Object;o.n=n+':'+g;o.u=u;o.d=d;o.l=l;o.e=e;g=s.m_dl;if(!g)g=s.m_dl=new Array;i=0;while(i<g"
                + ".length&&g[i])i++;g[i]=o}}else if(n){m=s.m_i(n);m._e=1}return m};s.voa=function(vo,r){var s=this,l=s.va_g,i,k,v,x;for(i=0;i<l.length;i++){k=l[i];v=vo[k];if(v||vo['!'+k]){if(!r&&(k==\"contextData\"|"
                + "|k==\"retrieveLightData\")&&s[k])for(x in s[k])if(!v[x])v[x]=s[k][x];s[k]=v}}};s.vob=function(vo,onlySet){var s=this,l=s.va_g,i,k;for(i=0;i<l.length;i++){k=l[i];vo[k]=s[k];if(!onlySet&&!vo[k])vo['!"
                + "'+k]=1}};s.dlt=new Function('var s=s_c_il['+s._in+'],d=new Date,i,vo,f=0;if(s.dll)for(i=0;i<s.dll.length;i++){vo=s.dll[i];if(vo){if(!s.m_m(\"d\")||d.getTime()-vo._t>=s.maxDelay){s.dll[i]=0;s.t(vo)}"
                + "else f=1}}if(s.dli)clearTimeout(s.dli);s.dli=0;if(f){if(!s.dli)s.dli=setTimeout(s.dlt,s.maxDelay)}else s.dll=0');s.dl=function(vo){var s=this,d=new Date;if(!vo)vo=new Object;s.vob(vo);vo._t=d.getTi"
                + "me();if(!s.dll)s.dll=new Array;s.dll[s.dll.length]=vo;if(!s.maxDelay)s.maxDelay=250;s.dlt()};s._waitingForMarketingCloudVisitorID = false;s._doneWaitingForMarketingCloudVisitorID = false;s._marketi"
                + "ngCloudVisitorIDCallback=function(marketingCloudVisitorID) {var s=this;s.marketingCloudVisitorID = marketingCloudVisitorID;s._doneWaitingForMarketingCloudVisitorID = true;s._callbackWhenReadyToTrac"
                + "kCheck();};s._waitingForAnalyticsVisitorID = false;s._doneWaitingForAnalyticsVisitorID = false;s._analyticsVisitorIDCallback=function(analyticsVisitorID) {var s=this;s.analyticsVisitorID = analytic"
                + "sVisitorID;s._doneWaitingForAnalyticsVisitorID = true;s._callbackWhenReadyToTrackCheck();};s._waitingForAudienceManagerLocationHint = false;s._doneWaitingForAudienceManagerLocationHint = false;s._a"
                + "udienceManagerLocationHintCallback=function(audienceManagerLocationHint) {var s=this;s.audienceManagerLocationHint = audienceManagerLocationHint;s._doneWaitingForAudienceManagerLocationHint = true;"
                + "s._callbackWhenReadyToTrackCheck();};s._waitingForAudienceManagerBlob = false;s._doneWaitingForAudienceManagerBlob = false;s._audienceManagerBlobCallback=function(audienceManagerBlob) {var s=this;s"
                + ".audienceManagerBlob = audienceManagerBlob;s._doneWaitingForAudienceManagerBlob = true;s._callbackWhenReadyToTrackCheck();};s.isReadyToTrack=function() {var s=this,readyToTrack = true,visitor = s.v"
                + "isitor;if ((visitor) && (visitor.isAllowed())) {if ((!s._waitingForMarketingCloudVisitorID) && (!s.marketingCloudVisitorID) && (visitor.getMarketingCloudVisitorID)) {s._waitingForMarketingCloudVisi"
                + "torID = true;s.marketingCloudVisitorID = visitor.getMarketingCloudVisitorID([s,s._marketingCloudVisitorIDCallback]);if (s.marketingCloudVisitorID) {s._doneWaitingForMarketingCloudVisitorID = true;}"
                + "}if ((!s._waitingForAnalyticsVisitorID) && (!s.analyticsVisitorID) && (visitor.getAnalyticsVisitorID)) {s._waitingForAnalyticsVisitorID = true;s.analyticsVisitorID = visitor.getAnalyticsVisitorID(["
                + "s,s._analyticsVisitorIDCallback]);if (s.analyticsVisitorID) {s._doneWaitingForAnalyticsVisitorID = true;}}if ((!s._waitingForAudienceManagerLocationHint) && (!s.audienceManagerLocationHint) && (vis"
                + "itor.getAudienceManagerLocationHint)) {s._waitingForAudienceManagerLocationHint = true;s.audienceManagerLocationHint = visitor.getAudienceManagerLocationHint([s,s._audienceManagerLocationHintCallba"
                + "ck]);if (s.audienceManagerLocationHint) {s._doneWaitingForAudienceManagerLocationHint = true;}}if ((!s._waitingForAudienceManagerBlob) && (!s.audienceManagerBlob) && (visitor.getAudienceManagerBlob"
                + ")) {s._waitingForAudienceManagerBlob = true;s.audienceManagerBlob = visitor.getAudienceManagerBlob([s,s._audienceManagerBlobCallback]);if (s.audienceManagerBlob) {s._doneWaitingForAudienceManagerBl"
                + "ob = true;}}if (((s._waitingForMarketingCloudVisitorID)     && (!s._doneWaitingForMarketingCloudVisitorID)     && (!s.marketingCloudVisitorID)) ||((s._waitingForAnalyticsVisitorID)          && (!s."
                + "_doneWaitingForAnalyticsVisitorID)          && (!s.analyticsVisitorID)) ||((s._waitingForAudienceManagerLocationHint) && (!s._doneWaitingForAudienceManagerLocationHint) && (!s.audienceManagerLocati"
                + "onHint)) ||((s._waitingForAudienceManagerBlob)         && (!s._doneWaitingForAudienceManagerBlob)         && (!s.audienceManagerBlob))) {readyToTrack = false;}}return readyToTrack;};s._callbackWhen"
                + "ReadyToTrackQueue = null;s._callbackWhenReadyToTrackInterval = 0;s.callbackWhenReadyToTrack=function(callbackThis,callback,args) {var s=this,callbackInfo;callbackInfo = {};callbackInfo.callbackThis"
                + " = callbackThis;callbackInfo.callback     = callback;callbackInfo.args         = args;if (s._callbackWhenReadyToTrackQueue == null) {s._callbackWhenReadyToTrackQueue = [];}s._callbackWhenReadyToTra"
                + "ckQueue.push(callbackInfo);if (s._callbackWhenReadyToTrackInterval == 0) {s._callbackWhenReadyToTrackInterval = setInterval(s._callbackWhenReadyToTrackCheck,100);}};s._callbackWhenReadyToTrackCheck"
                + "=new Function('var s=s_c_il['+s._in+'],callbackNum,callbackInfo;if (s.isReadyToTrack()) {if (s._callbackWhenReadyToTrackInterval) {clearInterval(s._callbackWhenReadyToTrackInterval);s._callbackWhen"
                + "ReadyToTrackInterval = 0;}if (s._callbackWhenReadyToTrackQueue != null) {while (s._callbackWhenReadyToTrackQueue.length > 0) {callbackInfo = s._callbackWhenReadyToTrackQueue.shift();callbackInfo.ca"
                + "llback.apply(callbackInfo.callbackThis,callbackInfo.args);}}}');s._handleNotReadyToTrack=function(variableOverrides) {var s=this,args,varKey,variableOverridesCopy = null,setVariables = null;if (!s."
                + "isReadyToTrack()) {args = [];if (variableOverrides != null) {variableOverridesCopy = {};for (varKey in variableOverrides) {variableOverridesCopy[varKey] = variableOverrides[varKey];}}setVariables ="
                + " {};s.vob(setVariables,true);args.push(variableOverridesCopy);args.push(setVariables);s.callbackWhenReadyToTrack(s,s.track,args);return true;}return false;};s.gfid=function(){var s=this,d='01234567"
                + "89ABCDEF',k='s_fid',fid=s.c_r(k),h='',l='',i,j,m=8,n=4,e=new Date,y;if(!fid||fid.indexOf('-')<0){for(i=0;i<16;i++){j=Math.floor(Math.random()*m);h+=d.substring(j,j+1);j=Math.floor(Math.random()*n);"
                + "l+=d.substring(j,j+1);m=n=16}fid=h+'-'+l;}y=e.getYear();e.setYear(y+2+(y<1900?1900:0));if(!s.c_w(k,fid,e))fid=0;return fid};s.track=s.t=function(vo,setVariables){var s=this,notReadyToTrack,trk=1,tm"
                + "=new Date,sed=Math&&Math.random?Math.floor(Math.random()*10000000000000):tm.getTime(),sess='s'+Math.floor(tm.getTime()/10800000)%10+sed,y=tm.getYear(),vt=tm.getDate()+'/'+tm.getMonth()+'/'+(y<1900?"
                + "y+1900:y)+' '+tm.getHours()+':'+tm.getMinutes()+':'+tm.getSeconds()+' '+tm.getDay()+' '+tm.getTimezoneOffset(),tcf,tfs=s.gtfs(),ta=-1,q='',qs='',code='',vb=new Object;if ((!s.supplementalDataID) &&"
                + " (s.visitor) && (s.visitor.getSupplementalDataID)) {s.supplementalDataID = s.visitor.getSupplementalDataID(\"AppMeasurement:\" + s._in,(s.expectSupplementalData ? false : true));}if(s.mpc('t',argum"
                + "ents))return;s.gl(s.vl_g);s.uns();s.m_ll();notReadyToTrack = s._handleNotReadyToTrack(vo);if (!notReadyToTrack) {if (setVariables) {s.voa(setVariables);}if(!s.td){var tl=tfs.location,a,o,i,x='',c='"
                + "',v='',p='',bw='',bh='',j='1.0',k=s.c_w('s_cc','true',0)?'Y':'N',hp='',ct='',pn=0,ps;if(String&&String.prototype){j='1.1';if(j.match){j='1.2';if(tm.setUTCDate){j='1.3';if(s.isie&&s.ismac&&s.apv>=5)"
                + "j='1.4';if(pn.toPrecision){j='1.5';a=new Array;if(a.forEach){j='1.6';i=0;o=new Object;tcf=new Function('o','var e,i=0;try{i=new Iterator(o)}catch(e){}return i');i=tcf(o);if(i&&i.next){j='1.7';if(a."
                + "reduce){j='1.8';if(j.trim){j='1.8.1';if(Date.parse){j='1.8.2';if(Object.create)j='1.8.5'}}}}}}}}}if(s.apv>=4)x=screen.width+'x'+screen.height;if(s.isns||s.isopera){if(s.apv>=3){v=s.n.javaEnabled()?"
                + "'Y':'N';if(s.apv>=4){c=screen.pixelDepth;bw=s.wd.innerWidth;bh=s.wd.innerHeight}}s.pl=s.n.plugins}else if(s.isie){if(s.apv>=4){v=s.n.javaEnabled()?'Y':'N';c=screen.colorDepth;if(s.apv>=5){bw=s.d.do"
                + "cumentElement.offsetWidth;bh=s.d.documentElement.offsetHeight;if(!s.ismac&&s.b){tcf=new Function('s','tl','var e,hp=0;try{s.b.addBehavior(\"#default#homePage\");hp=s.b.isHomePage(tl)?\"Y\":\"N\"}ca"
                + "tch(e){}return hp');hp=tcf(s,tl);tcf=new Function('s','var e,ct=0;try{s.b.addBehavior(\"#default#clientCaps\");ct=s.b.connectionType}catch(e){}return ct');ct=tcf(s)}}}else r=''}if(s.pl)while(pn<s.p"
                + "l.length&&pn<30){ps=s.fl(s.pl[pn].name,100)+';';if(p.indexOf(ps)<0)p+=ps;pn++}s.resolution=x;s.colorDepth=c;s.javascriptVersion=j;s.javaEnabled=v;s.cookiesEnabled=k;s.browserWidth=bw;s.browserHeigh"
                + "t=bh;s.connectionType=ct;s.homepage=hp;s.plugins=p;s.td=1}if(vo){s.vob(vb);s.voa(vo)}if(!s.analyticsVisitorID&&!s.marketingCloudVisitorID)s.fid=s.gfid();if((vo&&vo._t)||!s.m_m('d')){if(s.usePlugins"
                + ")s.doPlugins(s);if(!s.abort){var l=s.wd.location,r=tfs.document.referrer;if(!s.pageURL)s.pageURL=l.href?l.href:l;if(!s.referrer&&!s._1_referrer){s.referrer=r;s._1_referrer=1}s.m_m('g');if(s.lnk||s."
                + "eo){var o=s.eo?s.eo:s.lnk,p=s.pageName,w=1,t=s.ot(o),n=s.oid(o),x=o.s_oidt,h,l,i,oc;if(s.eo&&o==s.eo){while(o&&!n&&t!='BODY'){o=o.parentElement?o.parentElement:o.parentNode;if(o){t=s.ot(o);n=s.oid("
                + "o);x=o.s_oidt}}if(!n||t=='BODY')o='';if(o){oc=o.onclick?''+o.onclick:'';if((oc.indexOf('s_gs(')>=0&&oc.indexOf('.s_oc(')<0)||oc.indexOf('.tl(')>=0)o=0}}if(o){if(n)ta=o.target;h=s.oh(o);i=h.indexOf("
                + "'?');h=s.linkLeaveQueryString||i<0?h:h.substring(0,i);l=s.linkName;t=s.linkType?s.linkType.toLowerCase():s.lt(h);if(t&&(h||l)){s.pe='lnk_'+(t=='d'||t=='e'?t:'o');s.pev1=(h?s.ape(h):'');s.pev2=(l?s."
                + "ape(l):'')}else trk=0;if(s.trackInlineStats){if(!p){p=s.pageURL;w=0}t=s.ot(o);i=o.sourceIndex;if(o.dataset&&o.dataset.sObjectId){s.wd.s_objectID=o.dataset.sObjectId;}else if(o.getAttribute&&o.getAt"
                + "tribute('data-s-object-id')){s.wd.s_objectID=o.getAttribute('data-s-object-id');}else if(s.useForcedLinkTracking){s.wd.s_objectID='';oc=o.onclick?''+o.onclick:'';if(oc){var ocb=oc.indexOf('s_object"
                + "ID'),oce,ocq,ocx;if(ocb>=0){ocb+=10;while(ocb<oc.length&&(\"= \\t\\r\\n\").indexOf(oc.charAt(ocb))>=0)ocb++;if(ocb<oc.length){oce=ocb;ocq=ocx=0;while(oce<oc.length&&(oc.charAt(oce)!=';'||ocq)){if(o"
                + "cq){if(oc.charAt(oce)==ocq&&!ocx)ocq=0;else if(oc.charAt(oce)==\"\\\\\")ocx=!ocx;else ocx=0;}else{ocq=oc.charAt(oce);if(ocq!='\"'&&ocq!=\"'\")ocq=0}oce++;}oc=oc.substring(ocb,oce);if(oc){o.s_soid=n"
                + "ew Function('s','var e;try{s.wd.s_objectID='+oc+'}catch(e){}');o.s_soid(s)}}}}}if(s.gg('objectID')){n=s.gg('objectID');x=1;i=1}if(p&&n&&t)qs='&pid='+s.ape(s.fl(p,255))+(w?'&pidt='+w:'')+'&oid='+s.a"
                + "pe(s.fl(n,100))+(x?'&oidt='+x:'')+'&ot='+s.ape(t)+(i?'&oi='+i:'')}}else trk=0}if(trk||qs){s.sampled=s.vs(sed);if(trk){if(s.sampled)code=s.mr(sess,(vt?'&t='+s.ape(vt):'')+s.hav()+q+(qs?qs:s.rq()),0,"
                + "ta);qs='';s.m_m('t');if(s.p_r)s.p_r();s.referrer=s.lightProfileID=s.retrieveLightProfiles=s.deleteLightProfiles=''}s.sq(qs)}}}else s.dl(vo);if(vo)s.voa(vb,1);}s.abort=0;s.supplementalDataID=s.pageU"
                + "RLRest=s.lnk=s.eo=s.linkName=s.linkType=s.wd.s_objectID=s.ppu=s.pe=s.pev1=s.pev2=s.pev3='';if(s.pg)s.wd.s_lnk=s.wd.s_eo=s.wd.s_linkName=s.wd.s_linkType='';return code};s.trackLink=s.tl=function(o,t"
                + ",n,vo,f){var s=this;s.lnk=o;s.linkType=t;s.linkName=n;if(f){s.bct=o;s.bcf=f}s.t(vo)};s.trackLight=function(p,ss,i,vo){var s=this;s.lightProfileID=p;s.lightStoreForSeconds=ss;s.lightIncrementBy=i;s."
                + "t(vo)};s.setTagContainer=function(n){var s=this,l=s.wd.s_c_il,i,t,x,y;s.tcn=n;if(l)for(i=0;i<l.length;i++){t=l[i];if(t&&t._c=='s_l'&&t.tagContainerName==n){s.voa(t);if(t.lmq)for(i=0;i<t.lmq.length;"
                + "i++){x=t.lmq[i];y='m_'+x.n;if(!s[y]&&!s[y+'_c']){s[y]=t[y];s[y+'_c']=t[y+'_c']}s.loadModule(x.n,x.u,x.d)}if(t.ml)for(x in t.ml)if(s[x]){y=s[x];x=t.ml[x];for(i in x)if(!Object.prototype[i]){if(typeo"
                + "f(x[i])!='function'||(''+x[i]).indexOf('s_c_il')<0)y[i]=x[i]}}if(t.mmq)for(i=0;i<t.mmq.length;i++){x=t.mmq[i];if(s[x.m]){y=s[x.m];if(y[x.f]&&typeof(y[x.f])=='function'){if(x.a)y[x.f].apply(y,x.a);e"
                + "lse y[x.f].apply(y)}}}if(t.tq)for(i=0;i<t.tq.length;i++)s.t(t.tq[i]);t.s=s;return}}};s.wd=window;s.ssl=(s.wd.location.protocol.toLowerCase().indexOf('https')>=0);s.d=document;s.b=s.d.body;if(s.d.ge"
                + "tElementsByTagName){s.h=s.d.getElementsByTagName('HEAD');if(s.h)s.h=s.h[0]}s.n=navigator;s.u=s.n.userAgent;s.ns6=s.u.indexOf('Netscape6/');var apn=s.n.appName,v=s.n.appVersion,ie=v.indexOf('MSIE ')"
                + ",o=s.u.indexOf('Opera '),i;if(v.indexOf('Opera')>=0||o>0)apn='Opera';s.isie=(apn=='Microsoft Internet Explorer');s.isns=(apn=='Netscape');s.isopera=(apn=='Opera');s.ismac=(s.u.indexOf('Mac')>=0);if"
                + "(o>0)s.apv=parseFloat(s.u.substring(o+6));else if(ie>0){s.apv=parseInt(i=v.substring(ie+5));if(s.apv>3)s.apv=parseFloat(i)}else if(s.ns6>0)s.apv=parseFloat(s.u.substring(s.ns6+10));else s.apv=parse"
                + "Float(v);s.em=0;if(s.em.toPrecision)s.em=3;else if(String.fromCharCode){i=escape(String.fromCharCode(256)).toUpperCase();s.em=(i=='%C4%80'?2:(i=='%U0100'?1:0))}if(s.oun)s.sa(s.oun);s.sa(un);s.vl_l="
                + "'supplementalDataID,timestamp,dynamicVariablePrefix,visitorID,marketingCloudVisitorID,analyticsVisitorID,audienceManagerLocationHint,fid,vmk,visitorMigrationKey,visitorMigrationServer,visitorMigrat"
                + "ionServerSecure,ppu,charSet,visitorNamespace,cookieDomainPeriods,cookieLifetime,pageName,pageURL,referrer,contextData,currencyCode,lightProfileID,lightStoreForSeconds,lightIncrementBy,retrieveLight"
                + "Profiles,deleteLightProfiles,retrieveLightData';s.va_l=s.sp(s.vl_l,',');s.vl_mr=s.vl_m='timestamp,charSet,visitorNamespace,cookieDomainPeriods,cookieLifetime,contextData,lightProfileID,lightStoreFo"
                + "rSeconds,lightIncrementBy';s.vl_t=s.vl_l+',variableProvider,channel,server,pageType,transactionID,purchaseID,campaign,state,zip,events,events2,products,audienceManagerBlob,linkName,linkType';var n;"
                + "for(n=1;n<=75;n++){s.vl_t+=',prop'+n+',eVar'+n;s.vl_m+=',prop'+n+',eVar'+n}for(n=1;n<=5;n++)s.vl_t+=',hier'+n;for(n=1;n<=3;n++)s.vl_t+=',list'+n;s.va_m=s.sp(s.vl_m,',');s.vl_l2=',tnt,pe,pev1,pev2,p"
                + "ev3,resolution,colorDepth,javascriptVersion,javaEnabled,cookiesEnabled,browserWidth,browserHeight,connectionType,homepage,pageURLRest,plugins';s.vl_t+=s.vl_l2;s.va_t=s.sp(s.vl_t,',');s.vl_g=s.vl_t+"
                + "',trackingServer,trackingServerSecure,trackingServerBase,fpCookieDomainPeriods,disableBufferedRequests,mobile,visitorSampling,visitorSamplingGroup,dynamicAccountSelection,dynamicAccountList,dynamic"
                + "AccountMatch,trackDownloadLinks,trackExternalLinks,trackInlineStats,linkLeaveQueryString,linkDownloadFileTypes,linkExternalFilters,linkInternalFilters,linkTrackVars,linkTrackEvents,linkNames,lnk,eo"
                + ",lightTrackVars,_1_referrer,un';s.va_g=s.sp(s.vl_g,',');s.pg=pg;s.gl(s.vl_g);s.contextData=new Object;s.retrieveLightData=new Object;if(!ss)s.wds();if(pg){s.wd.s_co=function(o){return o};s.wd.s_gs="
                + "function(un){s_gi(un,1,1).t()};s.wd.s_dc=function(un){s_gi(un,1).t()}}",
            w = window, l = w.s_c_il, n = navigator, u = n.userAgent, v = n.appVersion, e = v.indexOf('MSIE '), m = u.indexOf('Netscape6/'), a, i, j, x, s;
        if (un) {
            un = un.toLowerCase();
            if (l)for (j = 0; j < 2; j++)for (i = 0; i < l.length; i++) {
                s = l[i];
                x = s._c;
                if ((!x || x == 's_c' || (j > 0 && x == 's_l')) && (s.oun == un || (s.fs && s.sa && s.fs(s.oun, un)))) {
                    if (s.sa)s.sa(un);
                    if (x == 's_c')return s
                } else s = 0
            }
        }
        w.s_an = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        w.s_sp = new Function("x", "d", "var a=new Array,i=0,j;if(x){if(x.split)a=x.split(d);else if(!d)for(i=0;i<x.length;i++)a[a.length]=x.substring(i,i+1);else while(i>=0){j=x.indexOf(d,i);a[a.length]=x.subst"
        + "ring(i,j<0?x.length:j);i=j;if(i>=0)i+=d.length}}return a");
        w.s_jn = new Function("a", "d", "var x='',i,j=a.length;if(a&&j>0){x=a[0];if(j>1){if(a.join)x=a.join(d);else for(i=1;i<j;i++)x+=d+a[i]}}return x");
        w.s_rep = new Function("x", "o", "n", "return s_jn(s_sp(x,o),n)");
        w.s_d = new Function("x", "var t='`^@$#',l=s_an,l2=new Object,x2,d,b=0,k,i=x.lastIndexOf('~~'),j,v,w;if(i>0){d=x.substring(0,i);x=x.substring(i+2);l=s_sp(l,'');for(i=0;i<62;i++)l2[l[i]]=i;t=s_sp(t,'');d"
        + "=s_sp(d,'~');i=0;while(i<5){v=0;if(x.indexOf(t[i])>=0) {x2=s_sp(x,t[i]);for(j=1;j<x2.length;j++){k=x2[j].substring(0,1);w=t[i]+k;if(k!=' '){v=1;w=d[b+l2[k]]}x2[j]=w+x2[j].substring(1)}}if(v)x=s_jn("
        + "x2,'');else{w=t[i]+' ';if(x.indexOf(w)>=0)x=s_rep(x,w,t[i]);i++;b+=62}}}return x");
        w.s_fe = new Function("c", "return s_rep(s_rep(s_rep(c,'\\\\','\\\\\\\\'),'\"','\\\\\"'),\"\\n\",\"\\\\n\")");
        w.s_fa = new Function("f", "var s=f.indexOf('(')+1,e=f.indexOf(')'),a='',c;while(s>=0&&s<e){c=f.substring(s,s+1);if(c==',')a+='\",\"';else if((\"\\n\\r\\t \").indexOf(c)<0)a+=c;s++}return a?'\"'+a+'\"':"
        + "a");
        w.s_ft = new Function("c", "c+='';var s,e,o,a,d,q,f,h,x;s=c.indexOf('=function(');while(s>=0){s++;d=1;q='';x=0;f=c.substring(s);a=s_fa(f);e=o=c.indexOf('{',s);e++;while(d>0){h=c.substring(e,e+1);if(q){i"
        + "f(h==q&&!x)q='';if(h=='\\\\')x=x?0:1;else x=0}else{if(h=='\"'||h==\"'\")q=h;if(h=='{')d++;if(h=='}')d--}if(d>0)e++}c=c.substring(0,s)+'new Function('+(a?a+',':'')+'\"'+s_fe(c.substring(o+1,e))+'\")"
        + "'+c.substring(e+1);s=c.indexOf('=function(')}return c;");
        c = s_d(c);
        if (e > 0) {
            a = parseInt(i = v.substring(e + 5));
            if (a > 3)a = parseFloat(i)
        } else if (m > 0)a = parseFloat(u.substring(m + 10)); else a = parseFloat(v);
        if (a < 5 || v.indexOf('Opera') >= 0 || u.indexOf('Opera') >= 0)c = s_ft(c);
        if (!s) {
            s = new Object;
            if (!w.s_c_in) {
                w.s_c_il = new Array;
                w.s_c_in = 0
            }
            s._il = w.s_c_il;
            s._in = w.s_c_in;
            s._il[s._in] = s;
            w.s_c_in++;
        }
        s._c = 's_c';
        (new Function("s", "un", "pg", "ss", c))(s, un, pg, ss);
        return s
    }
    function s_giqf() {
        var w = window, q = w.s_giq, i, t, s;
        if (q)for (i = 0; i < q.length; i++) {
            t = q[i];
            s = s_gi(t.oun);
            s.sa(t.un);
            s.setTagContainer(t.tagContainerName)
        }
        w.s_giq = 0
    }
    s_giqf()


    < !--
    /* You may give each page an identifying name, server, and channel on
     the next lines. */


    var d = new Date();
    var weekday = new Array(7);

    weekday[0] = "Sunday";
    weekday[1] = "Monday";
    weekday[2] = "Tuesday";
    weekday[3] = "Wednesday";
    weekday[4] = "Thursday";
    weekday[5] = "Friday";
    weekday[6] = "Saturday";

    var curr_date = weekday[d.getDay()];
    var curr_hour = d.getHours();
    if (curr_hour > 12) {
        var am_pm = "PM";
        curr_hour = curr_hour - 12;
    }
    else if (curr_hour == 12) {
        var am_pm = "PM";
    }
    else {
        var am_pm = "AM";
    }

    var curr_min = d.getMinutes();
    var curr_time = curr_hour + ":" + curr_min + "" + am_pm;


    if (typeof s_pageName != 'undefined') {
        s.pageName = s_pageName;
    } else {
        s.pageName = "Standard Template CB";
    }
    s.server = "http://www.postcrescent.com/"
    s.channel = ""
    s.pageType = ""
    s.prop12 = curr_time;
    s.prop13 = curr_date;
    s.prop23 = document.location;
    s.prop25 = "gpaper186"
    s.prop35 = "news:local"
    s.prop48 = "999|100|132|141|149|150|155|156|158|159|1006|1007|1008"
    s.prop50 = "newspaper"
    s.prop61 = "front"


    /* Conversion Variables */
    s.campaign = ""
    s.state = ""
    s.zip = ""
    s.events = ""
    s.products = ""
    s.purchaseID = ""
    s.eVar1 = "e1"
    s.eVar2 = "e2"
    s.eVar3 = "e3"
    s.eVar4 = "e4"
    s.eVar5 = "e5"

    //s.hier1="news:local"
    //s.hier4="D=ch"

    /************* DO NOT ALTER ANYTHING BELOW THIS LINE ! **************/
    var s_code = s.t();
    if (s_code)document.write(s_code)//--></script>
<script language="JavaScript" type="text/javascript"><!--
    if (navigator.appVersion.indexOf('MSIE') >= 0)document.write(unescape('%3C') + '\!-' + '-')
    //--></script>
<noscript><img src="http://gannett.112.2o7.net/b/ss/gpaper186/1/H.27.2--NS/0"
               height="1" width="1" border="0" alt=""/></noscript>
<!--/DO NOT REMOVE/-->
<!-- End SiteCatalyst code version: H.27.2. -->
</body>
</html>
