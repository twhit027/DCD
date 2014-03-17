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

$app->logInfo('Category Page');
$app->logInfo('FORWARDED_FOR: ' . @$_SERVER['HTTP_X_FORWARDED_FOR']);
$app->logInfo('REMOTE_ADDR: ' . @$_SERVER['REMOTE_ADDR']);
$app->logInfo('HTTP_HOST: ' . @$_SERVER['HTTP_HOST']);
$app->logInfo('SERVER_NAME: ' . @$_SERVER['SERVER_NAME']);

//$content = new Content();
$page = 1;

if (isset($_REQUEST['page'])) {
    $page = urldecode($_REQUEST['page']);
}
$placement = urldecode($_REQUEST['place']);
$position = urldecode($_REQUEST['posit']);

$siteCodes = array();

$listings = $app->getListings($placement, $position, $page);

$pagination = "";
if ($listings['totalRows'] > LISTINGS_PER_PAGE) {
    $numOfPages = ceil($listings['totalRows'] / LISTINGS_PER_PAGE);

    if ($page > 1)
        $pagination .= '<ul class="pagination"><li><a href="category.php?place=' . $placement . '&posit=' . $position . '&page=' . ($page - 1) . '">&laquo;</a></li>';
    else
        $pagination .= '<ul class="pagination"><li class="disabled"><a href="#">&laquo;</a></li>';

    for ($pge = 1; $pge <= $numOfPages; $pge++) {
        if ($pge == $page)
            $pagination .= '<li class="active"><a href="category.php?place=' . $placement . '&posit=' . $position . '&page=' . $pge . '">' . $pge . ' <span class="sr-only">(currecnt)</span></a></li>';
        else
            $pagination .= '<li><a href="category.php?place=' . $placement . '&posit=' . $position . '&page=' . $pge . '">' . $pge . '</a></li>';
    }

    if ($page < $numOfPages)
        $pagination .= '<li><a href="category.php?place=' . $placement . '&posit=' . $position . '&page=' . ($page + 1) . '">&raquo;</a></li></ul>';
    else
        $pagination .= '<li class="disabled"><a href="#">&raquo;</a></li></ul>';
}

$data = '';
foreach ($listings['results'] as $row) {
    $row['adText'] = htmlspecialchars($row['adText']);
    if (strlen($row['adText']) > 200) {
        $string = substr($row['adText'], 0, 200) . "... <a  href='item.php?id=" . $row['id'] . "&place=".$placement."&posit=" . $position . "'>Click for full text</a>";
    } else {
        $string = $row['adText'];
    }

    $data .= "<div class='jumbotron'>";
    $data .= "<p>" . $string . "</p>";
    $data .= '<a class="btn btn-primary" href="http://twitter.com/home?status=' . substr($row['adText'], 0, 120) . '" target="_blank">twitter</a>';
    $data .= "<a class='btn btn-primary' href='https://www.facebook.com/sharer/sharer.php?u=http://" . $_SERVER['SERVER_NAME'] . "/item.php?id=" . $row['id'] . "' target='_blank'>Facebook</a>";
    $data .= '<a class="btn btn-primary" href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://' . $_SERVER['SERVER_NAME'] . '/item.php?id=' . $row['id'] . '" target="_blank">google</a>';
    $data .= '</div>';
}

$mainContent = <<<EOS
                <ol class="breadcrumb">
                <li><a href="./">Home</a></li>
                <li class="active">Category</li>
            </ol>
            <h1>$position</h1>

            $pagination
            <br />$data
            $pagination
EOS;

include("master.php");
