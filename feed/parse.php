<?php
/**
 *
 * EXIT CODES:
 * 0    - No errors
 * 1    - unable to connect to database (die)
 * 2    - DELETE FROM `listing` WHERE ID = :id failed
 * 4    - INSERT into `listing` failed
 * 6    - DELETE FROM `listing` WHERE `EndDate` < :date failed
 * 8    - TRUNCATE TABLE `position` failed
 * 10   - INSERT into `position` failed
 * 12   - SELECT from `listing` address failed
 * 14   - INSERT into `listing` Lat and Long failed
 *
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include(__DIR__ . '/../conf/constants.php');

$userCount = $return = 0;
$userData = array();
$state = $site = '';

$fileArray = array();

if (isset($argv[1])) {
    $fileArray = array_slice($argv, 1);
} elseif (isset($_GET['location'])) {
    $fileArray[1] = $_GET['location'];
}

function parseXMLFile($file)
{
    $parser = xml_parser_create();

    xml_set_element_handler($parser, "start", "stop");
    xml_set_character_data_handler($parser, "char");

    $fp = fopen($file, "r");

    while ($data = fread($fp, 4096)) {
        xml_parse($parser, $data, feof($fp)) or die (sprintf("XML Error: %s at line %d",
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
    }elseif ($state['name'] == "AD") {
        $userData[$userCount]["AD"] = $state['ID'];
    }elseif ($state['name'] == "START-DATE") {
        $userData[$userCount]["START-DATE"] .= $data;
    }elseif ($state['name'] == "END-DATE") {
        $userData[$userCount]["END-DATE"] .= $data;
    }elseif ($state['name'] == "PLACEMENT") {
        $userData[$userCount]["PLACEMENT"] .= $data;
    }elseif ($state['name'] == "POSITION") {
        $userData[$userCount]["POSITION"] .= $data;
    }elseif ($state['name'] == "AD-TEXT") {
        $userData[$userCount]["AD-TEXT"] .= $data;
    }elseif ($state['name'] == "GS_ADDRESS") {
        $userData[$userCount]["STREET"] .= $data;
    }elseif ($state['name'] == "GS_CITY") {
        $userData[$userCount]["CITY"] .= $data;
    }elseif ($state['name'] == "GS_STATE") {
        $userData[$userCount]["STATE"] .= $data;
    }elseif ($state['name'] == "GS_ZIPCODE") {
        $userData[$userCount]["ZIP"] .= $data;
    }elseif ($state['name'] == "EXTERNAL_URL") {
        $userData[$userCount]["EXTERNAL"] .= $data;
    }elseif ($state['name'] == "MORE_INFORMATION") {
        $userData[$userCount]["MORE_INFORMATION"] .= $data;
    }elseif ($state['name'] == "mondaydate") {
        $userData[$userCount][Days][1] .= $data;
    }elseif ($state['name'] == "tuesdaydate") {
        $userData[$userCount][Days][2] .= $data;
    }elseif ($state['name'] == "wednesdaydate") {
        $userData[$userCount][Days][3] .= $data;
    }elseif ($state['name'] == "thursdaydate") {
        $userData[$userCount][Days][4] .= $data;
    }elseif ($state['name'] == "fridaydate") {
        $userData[$userCount][Days][5] .= $data;
    }elseif ($state['name'] == "saturdaydate") {
        $userData[$userCount][Days][6] .= $data;
    }elseif ($state['name'] == "sundaydate") {
        $userData[$userCount][Days][7] .= $data;
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
            fwrite(STDERR, $logText . "\n");
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
            if (empty($userData[$i]["EXTERNAL"])) {
                $userData[$i]["EXTERNAL"] = '';
            }
            if (empty($userData[$i]["MORE_INFORMATION"])) {
                $userData[$i]["MORE_INFORMATION"] = '';
            }

            $imagesCSV ='';
            if (!empty($userData[$i]["AD-TEXT"])) {
                //get all img tags
                $regexp = '/<img[^>]*src="(.*?)"[^>]*>/i';
                //$regexp = '/<img[^>]*src="([^"]+)"[^>]*>/i';
                //$regexp = '/< *img[^>]*src *= *["\']?([^"\']*)/i';
                $iResults = preg_match_all($regexp, $userData[$i]["AD-TEXT"], $aMatches);
                if (!empty($aMatches[1])) {
                    $imagesCSV = implode(',', $aMatches[1]);
                }
                //then strip all html tags
                $userData[$i]["AD-TEXT"] = trim(strip_tags($userData[$i]["AD-TEXT"]));
            }

            //$userData[$i]["AD"] = $site.$userData[$i]["AD"];

            try {
                $stmt = $this->prepare("DELETE FROM `listing` WHERE ID = :ID");
                $stmt->execute(array(':ID' => $userData[$i]["AD"]));
            } catch (PDOException $e) {
                $logText = "Message:(" . $e->getMessage() . ") attempting to delete listing (" . $userData[$i]["AD"] . ") from the database";
                fwrite(STDERR, $logText . "\n");
                $return = 2;
            }

            try {
                $stmt = $this->prepare("DELETE FROM `dates` WHERE ListingId = :ListingId");
                $stmt->execute(array(':ListingId' => $userData[$i]["AD"]));
            } catch (PDOException $e) {
                $logText = "Message:(" . $e->getMessage() . ") attempting to delete listing dates (" . $userData[$i]["AD"] . ") from the database";
                fwrite(STDERR, $logText . "\n");
                $return = 3;
            }

            try {
                $stmt = $this->prepare("INSERT INTO `listing` (`ID`, `StartDate`, `EndDate`, `Placement`,`Position`, `AdText`, `Images`, `SiteCode`, `Street`, `City`, `State`, `Zip`, `ExternalURL`, `MoreInfo`) VALUES(:ID, :StartDate, :EndDate, :Placement, :Position, :AdText, :Images, :Site, :Street, :City, :State, :Zip, :ExternalURL, :MoreInfo)");
                $stmt->execute(array(':ID' => $userData[$i]["AD"], ':StartDate' => $userData[$i]["START-DATE"], ':EndDate' => $userData[$i]["END-DATE"], ':Placement' => $userData[$i]["PLACEMENT"], ':Position' => $userData[$i]["POSITION"], ':AdText' => $userData[$i]["AD-TEXT"], ':Images'=> $imagesCSV,':Site' => $site, ':Street' => $userData[$i]["STREET"], ':City' => $userData[$i]["CITY"], ':State' => $userData[$i]["STATE"], ':Zip' => $userData[$i]["ZIP"], ':ExternalURL' => $userData[$i]["EXTERNAL"], ':MoreInfo' => $userData[$i]["MORE_INFORMATION"]));
                $inserted++;
            } catch (PDOException $e) {
                $logText = "Message:(" . $e->getMessage() . ") attempting to insert listing (" . $userData[$i]["AD"] . ") into the database";
                fwrite(STDERR, $logText . "\n");
                $return = 4;
            }

            if (isset($userData[$i]["Days"])) {
                foreach($userData[$i]["Days"] as $dayOfWeek => $timeOfDay) {
                    $date = $startTime = $endTime = '';
                    list($startTime, $endTime) = explode('-', $timeOfDay);
                    try {
                        $stmt = $this->prepare("INSERT INTO `dates` (`ListingId`, `DayOfWeek`, `Date`, `StartTime`, `EndTime`) VALUES(:ListingId, :DayOfWeek, :Date, :StartTime, :EndTime)");
                        $stmt->execute(array(':ListingId' => $userData[$i]["AD"], ':DayOfWeek' => $dayOfWeek, ':Date' => $date, ':StartTime' => $startTime, ':EndTime' => $endTime));
                    } catch (PDOException $e) {
                        $logText = "Message:(" . $e->getMessage() . ") attempting to insert listing (" . $userData[$i]["AD"] . ") into the database";
                        fwrite(STDERR, $logText . "\n");
                        $return = 5;
                    }
                }
            }
        }

        $logText = "inserted $inserted out of $userCount rows in listing for $site";
        fwrite(STDOUT, $logText . "\n");
    }

    function insertListingsSimple($adData)
    {
        print_r($adData);

        $siteCode = (string)$adData['sitecode'];

        echo "siteCode: $siteCode \n";

        $inserted = 0;
        $userCount = count($adData->ad);
        foreach ($adData->ad as $ad) {
            $id = (string)$ad['id'];
            echo "id: $id \n";
            $startDate = $ad->start_date;
            $endDate = $ad->end_date;
            $placement = $ad['placement'];
            $position = $ad['position'];
            $adText = $ad['ad-text'];
            $street = isset($ad['GS_ADDRESS']) ? $ad['GS_ADDRESS'] : '';
            $state = isset($ad['GS_STATE']) ? $ad['GS_STATE'] : '';
            $city = isset($ad['GS_CITY']) ? $ad['GS_CITY'] : '';
            $zip = isset($ad['GS_ZIPCODE']) ? $ad['GS_ZIPCODE'] : '';

            if (!empty($id)) {
                try {
                    $stmt = $this->prepare("DELETE FROM `listing` WHERE ID = :ID");
                    $stmt->execute(array(':ID' => $id));
                } catch (PDOException $e) {
                    $logText = "Message:(" . $e->getMessage() . ") attempting to delete listing (" . $id . ") from the database";
                    fwrite(STDERR, $logText . "\n");
                    $return = 2;
                }
                print_r(array(':ID' => $id, ':StartDate' => $startDate, ':EndDate' => $endDate, ':Placement' => $placement,
                    ':Position' => $position, ':AdText' => $adText, ':Site' => $siteCode, ':Street' => $street, ':City' => $city,
                    ':State' => $state, ':Zip' => $zip));
                try {
                    $stmt = $this->prepare("INSERT INTO `listing` (`ID`, `StartDate`, `EndDate`, `Placement`,`Position`, `AdText`, `SiteCode`, `Street`, `City`, `State`, `Zip`) VALUES(:ID, :StartDate, :EndDate, :Placement, :Position, :AdText, :Site, :Street, :City, :State, :Zip)");
                    $stmt->execute(array(':ID' => $id, ':StartDate' => $startDate, ':EndDate' => $endDate, ':Placement' => $placement,
                        ':Position' => $position, ':AdText' => $adText, ':Site' => $siteCode, ':Street' => $street, ':City' => $city,
                        ':State' => $state, ':Zip' => $zip));
                    $inserted++;
                } catch (PDOException $e) {
                    $logText = "Message:(" . $e->getMessage() . ") attempting to insert listing (" . $id . ") into the database";
                    fwrite(STDERR, $logText . "\n");
                    $return = 4;
                }
            }
        }

        $logText = "inserted $inserted out of $userCount rows in listing for $siteCode";
        fwrite(STDOUT, $logText . "\n");
    }


    function deleteOldListings()
    {
        $date = date("Y-m-d");
        try {
            $stmt = $this->prepare("DELETE FROM `listing` WHERE `EndDate` < :date ");
            $stmt->execute(array(':date' => $date));
            $count = $stmt->rowCount();
            $logText = "deleted " . $count . " out of date rows from listing";
            fwrite(STDOUT, $logText . "\n");
        } catch (PDOException $e) {
            $logText = "Message:(" . $e->getMessage() . ") attempting to delete data prior to (" . $date . ") from the listing table";
            fwrite(STDERR, $logText . "\n");
            $return = 6;
        }
    }

    function buildNav()
    {
        try {
            $stmt = $this->prepare("TRUNCATE TABLE `position`");
            $stmt->execute();
            $count = $stmt->rowCount();
            $logText = "deleted " . $count . " rows from position";
            fwrite(STDOUT, $logText . "\n");
        } catch (PDOException $e) {
            $logText = "Message:(" . $e->getMessage() . ") attempting to truncate the positions table";
            fwrite(STDERR, $logText . "\n");
            $return = 8;
        }

        try {
            $startDate = date("Y-m-d");
            $sql = "INSERT into `position` (Placement, Position, SiteCode, ExternalURL, Count ) SELECT Placement, Position, SiteCode, MAX(ExternalURL), count( * ) FROM listing WHERE StartDate <= '$startDate' GROUP BY Placement, Position, SiteCode";
            $stmt = $this->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            $logText = "Message:(" . $e->getMessage() . ") attempting to insert the positions table";
            fwrite(STDERR, $logText . "\n");
            $return = 10;
        }
    }

    function getLocation($address)
    {
        $url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=";
        $json = file_get_contents($url . urlencode($address));
        $json = json_decode($json, true);

        if ($json['status'] == "OK") {
            $latlon = array(
                'lat' => $json["results"][0]["geometry"]["location"]["lat"],
                'lon' => $json["results"][0]["geometry"]["location"]["lng"]
            );
            return $latlon;
        } else {
            return false;
        }
    }

    function updateGeocodes()
    {
        $results = array();

        try {
            $stmt = $this->prepare("SELECT `ID`, `Street`, `City`, `State`, `Zip` FROM `listing` WHERE `Street` != '' AND (`Lat` IS NULL OR `Lat` = '')");
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
        } catch (PDOException $e) {
            $logText = "Message:(" . $e->getMessage() . ") Selecting addresses without Lat and Long from listing";
            fwrite(STDERR, $logText . "\n");
            $return = 12;
        }

        foreach ($results as $row) {
            $address = $row['Street'];
            if (!empty($row['City'])) {
                $address .= ", " . $row['City'];
            }
            if (!empty($row['State'])) {
                $address .= ", " . $row['State'];
            }
            if (!empty($row['Zip'])) {
                $address .= " " . $row['Zip'];
            }

            $latlon = $this->getLocation($address);

            if ($latlon === false) {
				$latlon['lat'] = '';
				$latlon['lon'] = '';
            }
			
			try {
				$stmt = $this->prepare("UPDATE `listing` SET `Lat` = :lat, `Long` = :lon, `ExternalURL` = '1' WHERE `ID` = :id ");
				$stmt->execute(array(":lat" => $latlon['lat'], ":lon" => $latlon['lon'], ":id" => $row['ID']));
			} catch (PDOException $e) {
				$logText = "Message:(" . $e->getMessage() . ") Updating listing, adding Long and Lat for ".$row['ID'];
				fwrite(STDERR, $logText . "\n");
				$return = 14;
			}

            //Slow this down so we don't run into problems with Google's Geocoding limits
            sleep(1);
        }
    }
}

$user = new ClassifiedsAdmin();

foreach ($fileArray as $file) {
    if ($useSimple) {
        $adData = simplexml_load_file($file,'SimpleXMLElement', LIBXML_NOCDATA);
        if (!empty($adData)) {
            $user->insertListingsSimple($adData);
        }
    } else {
        $userCount = 0;
        $userData = array();
        $state = $site = '';

        parseXMLFile($file);

        if ($userCount > 0) {
            $user->insertListings();
        }
    }
}

$user->deleteOldListings();
$user->updateGeocodes();
$user->buildNav();

exit($return);
?>