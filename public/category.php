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

$app->logInfo('Category Page(FORWARDED_FOR: '.@$_SERVER['HTTP_X_FORWARDED_FOR'].', REMOTE_ADDR: '.@$_SERVER['REMOTE_ADDR'].',HTTP_HOST: '.@$_SERVER['HTTP_HOST'].'SERVER_NAME: '.@$_SERVER['SERVER_NAME'].')');

//$content = new Content();
$page = 1;
$fullText = $placement = $position = '';

if (isset($_REQUEST['page'])) {
    $page = urldecode($_REQUEST['page']);
}
if (isset($_REQUEST['ft'])) {
    $fullText = urldecode($_REQUEST['ft']);
}
if (isset($_REQUEST['place'])) {
    $placement = urldecode($_REQUEST['place']);
}
if (isset($_REQUEST['posit'])) {
    $position = urldecode($_REQUEST['posit']);
}
$search = "";
if(isset($_REQUEST['sites']))
{
	$sitegroup = urldecode($_REQUEST['sites']);
	$listings = $app->getListings($placement, $position, $page, $sitegroup);
	//$search = $app->getSearch($sitegroup);
}
else
{
	$listings = $app->getListings($placement, $position, $page, '', $fullText);	
	//$search = $app->getSearch();
}

$pagination = "";
if ($listings['totalRows'] > LISTINGS_PER_PAGE) {
    $numOfPages = ceil($listings['totalRows'] / LISTINGS_PER_PAGE);

    if ($page > 1)
        $pagination .= '<ul class="pagination"><li><a href="category.php?place=' . $placement . '&posit=' . $position . '&page=' . ($page - 1) . '&ft='.$fullText.'">&laquo;</a></li>';
    else
        $pagination .= '<ul class="pagination"><li class="disabled"><a href="#">&laquo;</a></li>';

    for ($pge = 1; $pge <= $numOfPages; $pge++) {
        if ($pge == $page)
            $pagination .= '<li class="active"><a href="category.php?place=' . $placement . '&posit=' . $position . '&page=' . $pge . '&ft='.$fullText.'">' . $pge . ' <span class="sr-only">(currecnt)</span></a></li>';
        else
            $pagination .= '<li><a href="category.php?place=' . $placement . '&posit=' . $position . '&page=' . $pge . '&ft='.$fullText.'">' . $pge . '</a></li>';
    }

    if ($page < $numOfPages)
        $pagination .= '<li><a href="category.php?place=' . $placement . '&posit=' . $position . '&page=' . ($page + 1) . '&ft='.$fullText.'">&raquo;</a></li></ul>';
    else
        $pagination .= '<li class="disabled"><a href="#">&raquo;</a></li></ul>';
}

$data = '';

if(!isset($listings['results']))
{
	$data ='<h1 style="color:#FC0000;"> No results found, please pick a different category or expand your advanced search</h1>';
}
else
{
	$count = 1;
    foreach ($listings['results'] as $row) {
        $map = '';
        $imageArray = array();
        if (!empty($row['images'])) {
            $imageArray = explode(',', $row['images']);
        }
        $row['adText'] = strip_tags($row['adText']);
		if (strlen($row['adText']) > 200) {
			//$string = substr($row['adText'], 0, 200) . "... <a  href='item.php?id=" . $row['id'] . "&place=".$placement."&posit=" . $position . "'>Click for full text</a>";
            $string = "<div id='dcd-short-".$count."'><p>".substr(strip_tags($row['adText']),0,200)."... </p></div>";
            $string .= "<div class='dcd-content-text' style='display: none' id='dcd-content-".$count."'><p>".$row['adText']."</p></div>";
            $string .= "<a href='item.php?id=" . $row['id'] . "&place=".$placement."&posit=" . $position . "' class='dcd-expand-text' data-id='".$count."'>Click for full text</a><br /><br />";
            $count++;
		} else {
			$string = '<p>'.$row['adText'].'</p>';
		}

        $dataInfo = '<div class=".small" style="padding-bottom:10px; color:#0052f4">'.$row['siteCode'];
        if (!empty($dataInfo)) $dataInfo .= "&nbsp;|&nbsp;";
        $dataInfo .= $row['position'];
        if (count($imageArray)>0) {
            if (!empty($dataInfo)) $dataInfo .= "&nbsp;|&nbsp;";
            $imgCnt = 0;
            foreach($imageArray as $imgSrc) {
                if ($imgCnt == 0) {
                    $dataInfo .= '<a class="fancybox" href="images/'.$row['siteCode'].'/'.$imgSrc.'" style="color:#FFA500;" rel="ligthbox '.$row['id'].'_group" title="Picture"><span class="glyphicon glyphicon-picture"></span></a>';
                } else {
                    $dataInfo .= '<div style="display: none"><a class="fancybox" href="images/'.$row['siteCode'].'/'.$imgSrc.'" style="color:#FFA500;" rel="ligthbox '.$row['id'].'_group" title="Picture"><span class="glyphicon glyphicon-picture"></span></a></div>';
                }
                $imgCnt++;
            }
        }
        if (!empty($map)) {
            if (!empty($dataInfo)) $dataInfo .= "&nbsp;|&nbsp;";
            $dataInfo .= '<a href="#" style="color:#00881A;" title="Map"><span class="glyphicon glyphicon-map-marker"></span></a>';
        }
        if (!empty($row['moreInfo'])) {
            if (!empty($dataInfo)) $dataInfo .= "&nbsp;|&nbsp;";
            $dataInfo .= '<a href="'.$row['moreInfo'].'" style="color:#0052f4;" title="More Information"><span class="glyphicon glyphicon-info-sign"></span></a>';
        }
        $dataInfo .= '</div>';
        $data .= "<div class='jumbotron' style='padding-top: 30px; word-wrap: break-word;'>";
        $data .= "$dataInfo";
		$data .= $string;
		if($row['externalURL'] === "1"){
			$data .= '<p><a href="map.php?place='.urlencode($row['placement']).'&posit='.urlencode($row['position']).'&ad='.urlencode($row['id']).'">View on map</a><p>';
		}
		$data .= '<a href="http://twitter.com/home?status=' . substr($row['adText'], 0, 120) . '" target="_blank"><img src="img/twitter-24.png" /></a>&nbsp';
		$data .= '<a href="https://www.facebook.com/sharer/sharer.php?u=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $row['id'] . '" target="_blank"><img src="img/facebook-24.png" /></a>&nbsp';
		$data .= '<a href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $row['id'] . '" target="_blank"><img src="img/google-plus-24.png" /></a>&nbsp';
        $data .= '<a href="mailto:emailaddress?subject='.substr($row['adText'], 0, 80).'&body='.substr($row['adText'], 0, 120).'%0D%0A%0D%0A http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $row['id'] .'" target="_top"><img src="img/email-24.png" /></span></a>';
		$data .= '</div>';
	}
}

$masterBottom = '<link rel="stylesheet" href="//frontend.reklamor.com/fancybox/jquery.fancybox.css" media="screen">
<script src="//frontend.reklamor.com/fancybox/jquery.fancybox.js"></script>
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
});

</script>';

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
            <h1>$position</h1>

            $pagination
            <br />$data
            $pagination
EOS;

include("../includes/master.php");
