<?php
/**
*
* EXIT CODES:
* 0  	- No errors
* 1 	- unable to connect to database (die)
* 2 	- DELETE FROM `listing` WHERE ID = :id failed 
* 4 	- INSERT into `listing` failed 
* 6 	- DELETE FROM `listing` WHERE `EndDate` < :date failed
* 8 	- TRUNCATE TABLE `position` failed
* 10 	- INSERT into `position` failed
*
*/

include(__DIR__.'../conf/constants.php');

$userCount = $return = 0;
$userData = array();
$state = $site = '';

$file = '';

if (isset($argv[1])) {
    $file = $argv[1];
} elseif (isset($_GET['location'])) {
    $file = $_GET['location'];
}

if ($file != '') {
    $parser = xml_parser_create();

    xml_set_element_handler($parser, "start", "stop");

    xml_set_character_data_handler($parser, "char");

    $fp = fopen($file, "r");

    while ($data = fread($fp, 4096)) {

        xml_parse($parser, $data, feof($fp)) or
        die (sprintf("XML Error: %s at line %d",
            xml_error_string(xml_get_error_code($parser)),
            xml_get_current_line_number($parser)));

    }


    xml_parser_free($parser);
}

function start($parser, $element_name, $element_attrs)
{
    global $state;

    $state['name'] = $element_name;
    if (count($element_attrs) >= 1) {
        foreach ($element_attrs as $x => $y) {

            $state[$x] = $y;

        }
    }
}

function stop($parser, $element_name)
{
    global $userCount;
    global $state;
    $state = '';

    if ($element_name == "AD") {
        $userCount++;
    }
}

function char($parser, $data)
{
    global $userCount;
    global $userData;
    global $state;
    global $site;

    if (empty($state)) {
        return;
    }

    if ($state['name'] == "DCD") {
        $site = $state['SITECODE'];
    }
    if ($state['name'] == "AD") {
        $userData[$userCount]["AD"] = $state['ID'];
    }
    if ($state['name'] == "START-DATE") {
        $userData[$userCount]["START-DATE"] = $data;
    }
    if ($state['name'] == "END-DATE") {
        $userData[$userCount]["END-DATE"] = $data;
    }
    if ($state['name'] == "PLACEMENT") {
        $userData[$userCount]["PLACEMENT"] = $data;
    }
    if ($state['name'] == "POSITION") {
        $userData[$userCount]["POSITION"] = $data;
    }
    if ($state['name'] == "AD-TEXT") {
        $userData[$userCount]["AD-TEXT"] = strip_tags($data, '<img><imgp>');
    }
    if ($state['name'] == "GS_ADDRESS") {
        $userData[$userCount]["STREET"] = $data;
    }
    if ($state['name'] == "GS_CITY") {
        $userData[$userCount]["CITY"] = $data;
    }
    if ($state['name'] == "GS_STATE") {
        $userData[$userCount]["STATE"] = $data;
    }
    if ($state['name'] == "GS_ZIPCODE") {
        $userData[$userCount]["ZIP"] = $data;
    }
}

class ClassifiedsAdmin extends PDO
{
    private $con;
    private $connection_string = NULL;
    private $db_host = DB_HOST;
    private $db_user = DB_USER;
    private $db_pass = DB_PASS;
    private $db_name = DB_NAME;    
    public function __construct()
    {
        try {
            $this->connection_string = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8';
            parent::__construct($this->connection_string, $this->db_user, $this->db_pass);
            $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->con = true;
        } catch (PDOException $e) {
            $logText = "Message:(" . $e->getMessage() . ") attempting to connect to database";
            fwrite(STDERR, $logText."\n");
            exit(1);
        }
    }

    function insertListings()
    {
        global $userCount;
        global $userData;
        global $site;
        $inserted = 0;
        for ($i = 0; $i < $userCount; $i++) {
            if (empty($userData[$i]["STREET"])) {
                $userData[$i]["STREET"] = '';
            }
            if (empty($userData[$i]["STATE"])) {
                $userData[$i]["STATE"] = '';
            }
            if (empty($userData[$i]["CITY"])) {
                $userData[$i]["CITY"] = '';
            }
            if (empty($userData[$i]["ZIP"])) {
                $userData[$i]["ZIP"] = '';
            }

            try {
                $stmt = $this->prepare("DELETE FROM `listing` WHERE ID = :ID");
                $stmt->execute(array(':ID' => $userData[$i]["AD"]));
            } catch (PDOException $e) {
                $logText = "Message:(" . $e->getMessage() . ") attempting to delete listing (" . $userData[$i]["AD"] . ") from the database";
                fwrite(STDERR, $logText."\n");
                $return = 2;
            }

            try {
                $stmt = $this->prepare("INSERT INTO `listing` (`ID`, `StartDate`, `EndDate`, `Placement`,`Position`, `AdText`, `SiteCode`, `Street`, `City`, `State`, `Zip`) VALUES(:ID, :StartDate, :EndDate, :Placement, :Position, :AdText, :Site, :Street, :City, :State, :Zip)");
                $stmt->execute(array(':ID' => $userData[$i]["AD"], ':StartDate' => $userData[$i]["START-DATE"], ':EndDate' => $userData[$i]["END-DATE"], ':Placement' => $userData[$i]["PLACEMENT"], ':Position' => $userData[$i]["POSITION"], ':AdText' => $userData[$i]["AD-TEXT"], ':Site' => $site, ':Street' => $userData[$i]["STREET"], ':City' => $userData[$i]["CITY"], ':State' => $userData[$i]["STATE"], ':Zip' => $userData[$i]["ZIP"]));
                $inserted++;
            } catch (PDOException $e) {
                $logText = "Message:(" . $e->getMessage() . ") attempting to insert listing (" . $userData[$i]["AD"] . ") into the database";
                fwrite(STDERR, $logText."\n");
                $return = 4;
            }
        }

        $logText = "inserted $inserted out of $userCount rows in listing for $site";
        fwrite(STDOUT, $logText."\n");
    }

    function deleteOldListings()
    {
        $date = date("Y-m-d");
        try {
            $stmt = $this->prepare("DELETE FROM `listing` WHERE `EndDate` < :date ");
            $stmt->execute(array(':date' => $date));
            $count = $stmt->rowCount();
            $logText = "deleted " . $count . " out of date rows from listing";
            fwrite(STDOUT, $logText."\n");
        } catch (PDOException $e) {
            $logText = "Message:(" . $e->getMessage() . ") attempting to delete data prior to (" . $date . ") from the listing table";
            fwrite(STDERR, $logText."\n");
            $return = 6;
        }
    }

    function buildNav()
    {
        try {
            $stmt = $this->prepare("TRUNCATE TABLE `position`");
            $stmt->execute();
            $count = $stmt->rowCount();
            $logText = "deleted " . $count . " rows from position<";
            fwrite(STDOUT, $logText."\n");
        } catch (PDOException $e) {
            $logText = "Message:(" . $e->getMessage() . ") attempting to truncate the positions table";
            fwrite(STDERR, $logText."\n");
            $return = 8;
        }

        try {
            $stmt = $this->prepare("INSERT into `position` (Placement, Position, SiteCode, Count) SELECT Placement, Position, SiteCode, count( * ) FROM listing GROUP BY Placement, Position, SiteCode");
            $stmt->execute();
        } catch (PDOException $e) {
            $logText = "Message:(" . $e->getMessage() . ") attempting to insert the positions table";
            fwrite(STDERR, $logText."\n");
            $return = 10;
        }
    }
}

$user = new ClassifiedsAdmin();

if ($userCount > 0) {
    $user->insertListings();
}

$user->deleteOldListings();
$user->buildNav();

exit($return);
?>