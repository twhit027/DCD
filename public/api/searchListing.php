<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 3/23/14
 * Time: 3:23 PM
 */

include('../../vendor/klogger/KLogger.php');
include('../../vendor/Mobile_Detect/Mobile_Detect.php');
include('../../conf/constants.php');
include('../../includes/GCI/Database.php');
include('../../includes/GCI/Site.php');
include('../../includes/GCI/App.php');
include('../../includes/GCI/Navigation.php');
include('../../includes/GCI/Ads.php');

$app = new \GCI\App();



$query = $_REQUEST['q'];

