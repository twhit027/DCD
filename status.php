<?php
include(dirname(__FILE__) . '/3rdParty/klogger/KLogger.php');
include('conf/constants.php');

$log   = KLogger::instance(LOGGING_DIR, LOGGING_LEVEL);

$connectionStatus = 'Passed';
$queryStatus = 'Passed';
$connBgColor = '#00FF00';
$queryBgStatus = '#00FF00';

try {
		$dbh = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT.';charset=utf8', DB_USER, DB_PASS);
		$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {	
				$retArray = $dbh->query("SELECT * FROM `siteinfo`");
		} catch(PDOException $ex) {
		    $queryStatus = 'Failed';
		    $log->logEmerg('Query Status: '. $queryStatus);
		}			
    $dbh = null;
} catch (PDOException $e) {
	$connectionStatus = 'Failed';
	$queryStatus = 'Failed';	
	$log->logEmerg('Connection Status: '. $connectionStatus);
}

if ($connectionStatus == 'Failed') {
	$connBgColor = '#FF0000';	
}
if ($queryStatus == 'Failed') {
	$queryBgStatus = '#FF0000';	
}

$log->logInfo('status Page');
$log->logInfo('Connection Status: '. $connectionStatus);
$log->logInfo('Query Status: '. $queryStatus);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<link rel="shortcut icon" href="images/ico/favicon.png">

<style type="text/css">
body
{
	min-width:10px!important;
}
</style>

<link href="3rdParty/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="3rdParty/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<table>
<tr><td>Apache status </td><td bgcolor="#00FF00">Passed</td></tr>		
<tr><td>database Connection status </td><td bgcolor="<?php echo $connBgColor?>"><?php echo $connectionStatus?></td></tr>
<tr><td>database Query status </td><td bgcolor="<?php echo $queryBgStatus?>"><?php echo $queryStatus?></td></tr>
<tr><td>DCD Version </td><td><?php echo DCD_VERSION ?> </td>
</table>
</body>
</html>