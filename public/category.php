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

$app->logInfo('Category Page(FORWARDED_FOR: ' . @$_SERVER['HTTP_X_FORWARDED_FOR'] . ', REMOTE_ADDR: ' . @$_SERVER['REMOTE_ADDR'] . ',HTTP_HOST: ' . @$_SERVER['HTTP_HOST'] . 'SERVER_NAME: ' . @$_SERVER['SERVER_NAME'] . ')');

//$content = new Content();
$page = 1;
$fullText = $placement = $position = $siteGroup = $radius = '';

if (isset($_REQUEST['page'])) {
    $page = trim(urldecode($_REQUEST['page']));
}
if (isset($_REQUEST['ft'])) {
    $fullText = trim(urldecode($_REQUEST['ft']));
}
if (isset($_REQUEST['place'])) {
    $placement = trim(urldecode($_REQUEST['place']));
}
if (isset($_REQUEST['posit'])) {
    $position = trim(urldecode($_REQUEST['posit']));
}
if (isset($_REQUEST['sites'])) {
    $siteGroup = trim(urldecode($_REQUEST['sites']));
}
if (isset($_REQUEST['rad'])) {
    $radius = trim(urldecode($_REQUEST['rad']));
}

$search = "";
$listings = $app->getListings($placement, $position, $page, $siteGroup, $fullText, $radius);

$pagination = "";
if ($listings['totalRows'] > LISTINGS_PER_PAGE) {
    $total_pages = $listings['totalRows'];
    // How many adjacent pages should be shown on each side?
    $adjacents = 3;

    /* Setup vars for query. */
    $targetPage = 'category.php';//your file name  (the name of this file)

    $urlStringArray = array();
    if ($placement != '') {
        $urlStringArray[] = 'place=' . urlencode($placement);
    }
    if ($position != '') {
        $urlStringArray[] = 'posit=' . urlencode($position);
    }
    if ($fullText != '') {
        $urlStringArray[] = 'ft=' . urlencode($fullText);
    }
    if ($siteGroup != '') {
        $urlStringArray[] = 'sites=' . urlencode($siteGroup);
    }
    if ($radius != '') {
        $urlStringArray[] = 'rad=' . urlencode($radius);
    }

    $urlString = implode( '&', $urlStringArray);

    if (! empty($urlString)) {
        $targetPage .= '?' . $urlString;
    }

    $limit = LISTINGS_PER_PAGE; //how many items to show per page
    //$page = urldecode($_REQUEST['page']);

    if ($page) {
        $start = ($page - 1) * $limit; //first item to display on this page
    } else {
        $start = 0; //if no page var is given, set start to 0
    }

    /* Setup page vars for display. */
    if ($page == 0) $page = 1; //if no page var is given, default to 1.
    $prev = $page - 1; //previous page is page - 1
    $next = $page + 1; //next page is page + 1
    $lastpage = ceil($total_pages / $limit); //lastpage is = total pages / items per page, rounded up.
    $lpm1 = $lastpage - 1; //last page minus 1

    if ($lastpage > 1) {
        //previous button
        if ($page > 1)
            $pagination .= '<ul class="pagination"><li><a href="' . $targetPage . '&page=' . ($page - 1) . '">&laquo;</a></li>';
        else
            $pagination .= '<ul class="pagination"><li class="disabled"><a href="#">&laquo;</a></li>';

        //pages
        if ($lastpage < 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination .= '<li class="active"><a href="' . $targetPage . '&page=' . $counter . '">' . $counter . ' <span class="sr-only">(currecnt)</span></a></li>';
                else
                    $pagination .= '<li><a href="' . $targetPage . '&page=' . $counter . '">' . $counter . '</a></li>';
            }
        } else { //enough pages to hide some
            //close to beginning; only hide later pages
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 3 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li class=\"active\"><a href=\"\">$counter</a></li>";
                    else
                        $pagination .= "<li><a href=\"$targetPage&page=$counter\">$counter</a></li>";
                }
                $pagination .= '<li class="disabled"><a href="#">...</a></li>';
                $pagination .= "<li><a href=\"$targetPage&page=$lpm1\">$lpm1</a></li>";
                $pagination .= "<li><a href=\"$targetPage&page=$lastpage\">$lastpage</a></li>";
            } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) { //in middle; hide some front and some back
                $pagination .= "<li><a href=\"$targetPage&page=1\">1</a></li>";
                $pagination .= "<li><a href=\"$targetPage&page=2\">2</a></li>";
                $pagination .= '<li class="disabled"><a href="#">...</a></li>';
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li class=\"active\"><a href=\"\">$counter</a></li>";
                    else
                        $pagination .= "<li><a href=\"$targetPage&page=$counter\">$counter</a></li>";
                }
                $pagination .= '<li class="disabled"><a href="#">...</a></li>';
                $pagination .= "<li><a href=\"$targetPage&page=$lpm1\">$lpm1</a></li>";
                $pagination .= "<li><a href=\"$targetPage&page=$lastpage\">$lastpage</a></li>";
            } else { //close to end; only hide early pages
                $pagination .= "<li><a href=\"$targetPage&page=1\">1</a></li>";
                $pagination .= "<li><a href=\"$targetPage&page=2\">2</a></li>";
                $pagination .= '<li class="disabled"><a href="#">...</a></li>';
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $pagination .= "<li class=\"active\"><a href=\"\">$counter</a></li>";
                    } else {
                        $pagination .= "<li><a href=\"$targetPage&page=$counter\">$counter</a></li>";
                    }
                }
            }
        }

        //next button
        if ($page < $counter - 1)
            $pagination .= '<li><a href="' . $targetPage . '&page=' . ($page + 1) . '">&raquo;</a></li></ul>';
        else
            $pagination .= '<li class="disabled"><a href="#">&raquo;</a></li></ul>';
    }
}

$data = '';

if (!isset($listings['results'])) {
    $data = '<h1 style="color:#d43f3a;"> No results found, please pick a different category or expand your advanced search</h1>';
} else {
    $count = 1;
    $siteDropDown = '';
    if (empty($siteGroup)) {
        if ((!empty($listings['sites'])) && (count($listings['sites']) > 1)) {
            $siteDropDown .= '<div class="dropdown col-lg-8">';
            $siteDropDown .= '<button title="Add Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">';
            $siteDropDown .= '<strong>Filter Options - Select:</strong> Newspaper <span class="caret"></span></button>';
            $siteDropDown .= '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
            foreach ($listings['sites'] as $row) {
                $siteDropDown .= '<li role="presentation"><a role="menuitem" tabindex="-1" onClick="setGetParameter(\'sites\', \'' . $row['siteCode'] . '\')" href="javascript:void(0)">' . $row['busName'] . '</a></li>';
            }

            $siteDropDown .= '</ul></div>';
        }
    } elseif ($siteGroup != 'all') {
        $siteDropDown .= '<div class="col-lg-8">';
        $siteDropDown .= '<button title="Remove Filter" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" onClick="removeSitesAndReloadPage()" href="javascript:void(0)">';
        $siteDropDown .= '<span class="glyphicon glyphicon-remove-circle" style="color:#d43f3a;"></span><strong>Filter Options - Select:</strong> Newspaper </button></div>';
    }

    foreach ($listings['results'] as $row) {
        $map = '';
        $imageArray = array();
        if (!empty($row['images'])) {
            $imageArray = explode(',', $row['images']);
        }

        if ($app->getSite()->getDomain() == $row['domain']) {
            $server = $_SERVER['SERVER_NAME'];
            if (isset($_SERVER['CONTEXT_PREFIX'])) {
                $server .= $_SERVER['CONTEXT_PREFIX'];
            }
        } else {
            $server = 'classifieds.' . $row['domain'];
        }

        $url = rtrim($server, "/");

        $row['adText'] = strip_tags($row['adText']);
        if (strlen($row['adText']) > 200) {
            $string = '<div id="dcd-short-' . $count . '"><p>' . substr(strip_tags($row['adText']), 0, 200) . '... </p></div>';
            $string .= '<div class="dcd-content-text" style="display: none" id="dcd-content-' . $count . '"><p>' . $row['adText'] . '</p></div>';
            $string .= '<a href="http://' . $server . '/item.php?id=' . $row['id'] . '&place=' . $placement . '&posit=' . $position . '" class="dcd-expand-text" data-id="' . $count . '">Click for full text</a><br /><br />';
            $count++;
        } else {
            $string = '<p>' . $row['adText'] . '</p>';
        }

        $dataInfo = '<div class=".small" style="padding-bottom:10px; color:#0052f4"><a href="http://' . $server . '/" target="_blank">' . $row['busName'] . '</a>';
        if (!empty($dataInfo)) $dataInfo .= "&nbsp;|&nbsp;";
        $dataInfo .= '<a href="http://' . $server . '/category.php?place=' . urlencode($row['placement']) . '&posit=' . urlencode($row['position']) . '" target="_blank">' . $row['position'] . '</a>';
        if (count($imageArray) > 0) {
            if (!empty($dataInfo)) $dataInfo .= "&nbsp;|&nbsp;";
            $imgCnt = 0;
            $images = '';
            foreach ($imageArray as $imgSrc) {
                $images .= '<a class="fancybox" href="http://' . $server . '/images/' . $row['siteCode'] . '/' . $imgSrc . '" style="color:#FFA500;" rel="ligthbox ' . $row['id'] . '_group" title="Picture">';
                $images .= '<img src="http://' . $server . '/images/' . $row['siteCode'] . '/' . $imgSrc . '" height="42" width="42" />';
                $images .= '</a>';
                $imgCnt++;
            }
        }

        if ($row['externalURL'] === "1") {
            if (!empty($dataInfo)) $dataInfo .= "&nbsp;|&nbsp;";
            $dataInfo .= '<a href="http://' . $server . '/map.php?place=' . urlencode($row['placement']) . '&posit=' . urlencode($row['position']) . '&ad=' . urlencode($row['id']) . '" style="color:#00881A;" title="Map" target="_blank"><span class="glyphicon glyphicon-map-marker"></span>Map</a>';
        }
        if (!empty($row['moreInfo'])) {
            if (!empty($dataInfo)) $dataInfo .= "&nbsp;|&nbsp;";
            $dataInfo .= '<a href="' . $row['moreInfo'] . '" style="color:#0052f4;" title="More Information" target="_blank"><span class="glyphicon glyphicon-info-sign"></span>More Info</a>';
        }
        $dataInfo .= '</div>';
        $data .= "<div class='jumbotron' style='padding-top: 30px; word-wrap: break-word;'>";
        $data .= "$dataInfo";
        $data .= $string;
        $data .= $images.'<br />';
        $data .= '<a href="http://twitter.com/home?status=' . substr($row['adText'], 0, 120) . '" target="_blank"><img src="img/twitter-24.png" /></a>&nbsp';
        $data .= '<a href="https://www.facebook.com/sharer/sharer.php?u=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $row['id'] . '" target="_blank"><img src="img/facebook-24.png" /></a>&nbsp';
        $data .= '<a href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $row['id'] . '" target="_blank"><img src="img/google-plus-24.png" /></a>&nbsp';
        $data .= '<a href="mailto:emailaddress?subject=' . substr($row['adText'], 0, 80) . '&body=' . substr($row['adText'], 0, 120) . '%0D%0A%0D%0A http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $row['id'] . '" target="_top"><img src="img/email-24.png" /></span></a>';
        $data .= '</div>';
    }
}

$masterBottom = '<link type="text/css" rel="stylesheet" href="3rdParty/fancybox/source/jquery.fancybox.css" media="screen">
<script type="text/javascript" src="3rdParty/fancybox/source/jquery.fancybox.pack.js"></script>
<script>
$(document).ready(function(){
    //FANCYBOX
    //https://github.com/fancyapps/fancyBox
    $(".fancybox").fancybox({
        openEffect: "none",
        closeEffect: "none"
    });
	$(".dcd-expand-text").click(function(){
		$("#dcd-short-"+$(this).data("id")).slideToggle("slow");
		$("#dcd-content-"+$(this).data("id")).slideToggle("slow");
		$orgText = "Click for full text";
		if ($orgText == $(this).html()) {
		    $(this).html("Click for less text");
		} else {
		    $(this).html($orgText);
		}

		return false;
	});
    //$("#sitesdd").on("change", function() { window.location.href = window.location.href + "&sites=" + encodeURIComponent(this.value); return false;} );
});

function setGetParameter(paramName, paramValue) {
    var url = window.location.href;
    url = url.replace(/&?page=([^&]$|[^&]*)/i, "");
    if (url.indexOf(paramName + "=") >= 0)
    {
        var prefix = url.substring(0, url.indexOf(paramName));
        var suffix = url.substring(url.indexOf(paramName));
        suffix = suffix.substring(suffix.indexOf("=") + 1);
        suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
        url = prefix + paramName + "=" + paramValue + suffix;
    }
    else
    {
        if (url.indexOf("?") < 0) {
            url += "?" + paramName + "=" + encodeURIComponent(paramValue);
        } else {
            url += "&" + paramName + "=" + encodeURIComponent(paramValue);
        }
    }
    window.location.href = url;
    return false;
}

function addSitesAndReloadPage(paramValue) {
    setGetParameter("sites", paramValue);
}

function removeSitesAndReloadPage() {
    var url = window.location.href;
    url = url.replace(/&?page=([^&]$|[^&]*)/i, "");
    url = url.replace(/&?sites=([^&]$|[^&]*)/i, "");
    window.location.href = url;
    return false;
}
</script>';

$filter = <<<EOS
<div class="panel panel-default">
    <div class="filterHeading panel-heading">Filter</div>
    <div class="filterContent panel-body" style="display: none;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit orem ipsum dolor sit amet, consectetuer adipiscing elit</div>
</div>
EOS;

$mainContent = <<<EOS
            <input type="hidden" id="place" name="place" value="$placement">
            <input type="hidden" id="posit" name="posit" value="$position">
            <input type="hidden" id="page" name="page" value="$page">
            <input type="hidden" id="fullText" name="fullText" value="$fullText">
            <ol class="breadcrumb">
                <li><a href="./">Home</a></li>
                <li class="active">Category</li>
            </ol>
			<div class="jumbotron" id="advancedsearch" style="display:none;">
				$search
	        </div>
            <div class="row">
                <div class="col-lg-8"><h1>$position</h1></div>
                $siteDropDown
            </div>


            $pagination
            <br />$data
            $pagination
EOS;

include("../includes/master.php");
