<?php
include('../vendor/klogger/KLogger.php');
include('../vendor/Mobile_Detect/Mobile_Detect.php');
include('../conf/constants.php');
include('../includes/GCI/Database.php');
include('../includes/GCI/Site.php');
include('../includes/GCI/App.php');
include('../includes/GCI/Navigation.php');
include('../includes/GCI/Ads.php');

$startDate = '';

if (isset($_REQUEST['sd']) && ($_REQUEST['sd'] == '1')) {
    $startDate = 1;
}

if (isset($_POST['user']) && isset($_POST['pass'])) {
    if ($_POST['user'] == "reportusr" && $_POST['pass'] == "uscpclassifieds") {
        $app = new \GCI\App();
        $app->logInfo('Report Page(FORWARDED_FOR: ' . @$_SERVER['HTTP_X_FORWARDED_FOR'] . ', REMOTE_ADDR: ' . @$_SERVER['REMOTE_ADDR'] . ',HTTP_HOST: ' . @$_SERVER['HTTP_HOST'] . 'SERVER_NAME: ' . @$_SERVER['SERVER_NAME'] . ')');

        $results = $app->report($startDate);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=report.csv');
        $fp = fopen('php://output', 'w');
        foreach ($results as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
        exit;
    } else {
        echo "<h1> Invalid Password, please try again</h1>";
    }
}
?>

<form method="POST" action="report.php">
    <label for="user-password">User: </label><input id="user" name="user" />
    <label for="user-password">Password: </label><input type="password" id="pass" name="pass" />
    <input type="hidden" name="sd" value="<?php echo $startDate; ?>">
    <input type="submit" name="submit" value="Go">
</form>

