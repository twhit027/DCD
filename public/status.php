<?php
set_time_limit(0);

include('../vendor/klogger/KLogger.php');
include('../conf/constants.php');
include('../includes/GCI/App.php');
include('../conf/version.php');

$log   = KLogger::instance(LOGGING_DIR, LOGGING_LEVEL);

use GCI\App;

$connectionStatus = 'Passed';
$queryStatus = 'Passed';
$connBgColor = '#00FF00';
$queryBgStatus = '#00FF00';
$serverInfoArray = array();
$siteInfoArray = array();

try {
    $dbh = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT.';charset=utf8', DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $sth = $dbh->prepare("SELECT * FROM `siteinfo`");
        $sth->execute();
        $siteInfoArray  = $sth->fetchAll();
        //$siteInfoArray = $dbh->query("SELECT * FROM `siteinfo`");
        $serverInfoArray = $dbh->getAttribute(PDO::ATTR_SERVER_INFO);
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

function url_exists($url) {
    $headers = @get_headers($url);
    if((strpos($headers[0],'200')===false) && (strpos($headers[0],'302')===false)) {
        return false;
    }
    return true;
    //if (!$fp = curl_init($url)) return false;
    //return true;
}

function getSubDomain ($domain) {
    $eDom = explode('.', $domain);
    return $eDom[0];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" href="img/ico/favicon-16x16.png">

    <style type="text/css">
        tr.headlin {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
            font-weight      : bold;
            color            : #FFFFFF;
            background-color : #888888;
            text-indent      : 2pt;
        }
        td.small {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
            font-weight      : normal;
        }
        td.smallred {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
            font-weight      : bold;
            color            : red;
        }
        td.smallbold {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
            font-weight      : bold;
        }
        tr.threadheader {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
            font-weight      : bold;
            color            : #000000;
            background-color : #CCCCCC;
        }
        tr.thread {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
        }
        tr.threaditalic {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
            font-style       : italic;
            color            : #808080;
        }
        tr.threadbold {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
            font-weight      : bold;
        }
        tr.threadred {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
            color            : White;
            background-color : Red;
        }
        tr.threadboldred {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
            color            : White;
            background-color : Red;
            font-weight      : bold;
        }
        tr.statusheader {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
            font-weight      : bold;
            color            : #FFFFFF;
            background-color : #808080;
        }
        tr.status {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
        }
        tr.statusitalicred {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
            font-style       : italic;
            color            : Red;
        }
        tr.statusitalic {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
            font-style       : italic;
            color            : #808080;
        }
        tr.statusred {
            font-family      : Arial, Helvetica, Sans-Serif;
            font-size        : 8pt;
            color            : Red;
        }
    </style>

    <link href="3rdParty/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="3rdParty/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
</head>
<BODY style="background-color:#FFFFFF">
<div class="container">
    <table cellpadding="1" cellspacing="0">
        <tr class="headlin">
            <td colspan="3">Status information</td>
        </tr>
        <tr>
            <td class="smallbold">Host name:</td>
            <td style="width:10px"></td>
            <td class="small"><?php echo $_SERVER['HTTP_HOST']; ?></td>
        </tr>
        <tr>
            <td class="smallbold">Server admin:</td>
            <td style="width:10px"></td>
            <td class="small"><?php echo $_SERVER['SERVER_ADMIN']; ?></td>
        </tr>
        <tr>
            <td class="smallbold">Request Time:</td>
            <td style="width:10px"></td>
            <td class="small"><?php echo $_SERVER['REQUEST_TIME_FLOAT'];?></td>
        </tr>
        <tr>
            <td class="smallbold">DCD Version:</td>
            <td style="width:10px"></td>
            <td class="small"><?php echo DCD_VERSION_STATUS;?></td>
        </tr>
        <tr class="headlin">
            <td colspan="3">Memory</td>
        </tr>
        <tr>
            <td class="smallbold">Memory usage:</td>
            <td style="width:10px"></td>
            <td class="small"><?php echo memory_get_peak_usage(); ?></td>
        </tr>
        <tr>
            <td class="smallbold">Peak memory usage:</td>
            <td style="width:10px"></td>
            <td class="small"><?php echo memory_get_usage(); ?></td>
        </tr>
        <tr class="headlin">
            <td colspan="3">Database</td>
        </tr>
        <tr>
            <td class="smallbold">Connection:</td>
            <td style="width:10px"></td>
            <td class="small"><?php echo $connectionStatus;?></td>
        </tr>
        <tr>
            <td class="smallbold">Query:</td>
            <td style="width:10px"></td>
            <td class="small"><?php echo $queryStatus;?></td>
        </tr>
        <tr>
            <td class="smallbold">Information:</td>
            <td style="width:10px"></td>
            <td class="small"><?php echo $serverInfoArray;?></td>
        </tr>
        <tr class="headlin">
            <td colspan="3">Setup Information</td>
        </tr>
        <tr>
            <td class="smallbold">Document root:</td>
            <td style="width:10px"></td>
            <td class="small"><?php echo $_SERVER['DOCUMENT_ROOT'];?></td>
        </tr>
        <tr>
            <td class="smallbold">Context prefix:</td>
            <td style="width:10px"></td>
            <td class="small"><?php echo $_SERVER['CONTEXT_PREFIX'];?></td>
        </tr>
        <tr>
            <td class="smallbold">Context document root:</td>
            <td style="width:10px"></td>
            <td class="small"><?php echo $_SERVER['CONTEXT_DOCUMENT_ROOT'];?></td>
        </tr>
        <?php
        $siteUrl= 'http://maps.googleapis.com/maps/api/js?sensor=false';
        $siteStatus = 'Failed';
        if (url_exists("http://maps.googleapis.com/maps/api/js?sensor=false")) {
            $siteStatus = 'Passed';
        }
        echo '<tr class="headlin"><td colspan="3">Google API</td></tr>';
        echo '<tr><td class="smallbold">'.$siteUrl.'</td><td style="width:10px"></td><td class="small">'.$siteStatus.'</td></tr>';

        if (!empty($_GET['dns'])) {
            $default_opts = array(
                'http'=>array(
                    'timeout'=>"5"
                )
            );

            $default = stream_context_set_default($default_opts);
            echo '<tr class="headlin"><td colspan="3">Classifieds URLs</td></tr>';
            foreach($siteInfoArray as $siteInfo) {
                //$subDomain = 'classifieds';
                $host = App::getHost();
                if (!empty($host)) {
                    $subDomain = getSubDomain($host);
                }
                $siteUrl = 'http://'.$subDomain.'.'.$siteInfo['Domain'];
                $siteStatus = 'Failed';
                if (url_exists($siteUrl)) {
                    $siteStatus = 'Passed';
                }
                echo '<tr><td class="smallbold">'.$siteUrl.'</td><td style="width:10px"></td><td class="small">'.$siteStatus.'</td></tr>';
            }
            // http://desmoinesregister.gannettclassifieds.com/
            echo '<tr class="headlin"><td colspan="3">AdbaseE URLs</td></tr>';
            foreach($siteInfoArray as $siteInfo) {
                $siteUrl = 'http://'.$siteInfo['SiteName'].'.gannettclassifieds.com/';
                $siteStatus = 'Failed';
                if (url_exists($siteUrl)) {
                    $siteStatus = 'Passed';
                }
                echo '<tr><td class="smallbold">'.$siteUrl.'</td><td style="width:10px"></td><td class="small">'.$siteStatus.'</td></tr>';
            }
        }
        ?>
    </table>
</div>
</body>
</html>