<?php
include('../vendor/klogger/KLogger.php');
include('../vendor/Mobile_Detect/Mobile_Detect.php');
include('../conf/constants.php');
include('../includes/GCI/Database.php');
include('../includes/GCI/Site.php');
include('../includes/GCI/App.php');
include('../includes/GCI/Navigation.php');
include('../includes/GCI/Ads.php');


if(isset($_POST['user']) && isset($_POST['pass']))
{
	if( $_POST['user'] == "reportusr" && $_POST['pass'] == "uscpclassifieds")
	{
		
		
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename=report.csv');
		$fp = fopen('php://output', 'w');
						
		$app = new \GCI\App();
		
		$app->logInfo('Landin Page(FORWARDED_FOR: '.@$_SERVER['HTTP_X_FORWARDED_FOR'].', REMOTE_ADDR: '.@$_SERVER['REMOTE_ADDR'].',HTTP_HOST: '.@$_SERVER['HTTP_HOST'].'SERVER_NAME: '.@$_SERVER['SERVER_NAME'].')');
		
		$search = $app->getSearch();
		$siteName = $app->getSite()->getSiteName();
		$siteUrl = $app->getSite()->getSiteUrl();
		$busName = $app->getSite()->getBusName();

		echo $app->report($app, $fp);
	}
	else
	{
		
		echo "<h1> Invalid Password, please try again</h1>";
		echo '
		<form method="POST" action="report.php">
User <input type="text" name="user"></input><br/>
Pass <input type="password" name="pass"></input><br/>
<input type="submit" name="submit" value="Go"></input>
</form>
';
		
	}
}
else
{
		echo '
		<form method="POST" action="report.php">
		User <input type="text" name="user"></input><br/>
		Pass <input type="password" name="pass"></input><br/>
		<input type="submit" name="submit" value="Go"></input>
		</form>
		';
}
?>

