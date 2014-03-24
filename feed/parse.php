<?php
require_once('../conf/constants.php');
include('../includes/GCI/Database.php');

global $userCount;
global $userData;
global $state;
global $site;

$userCount=0;
$userData=array();

$file = '';

if (isset($argv[1])) {
    $file = $argv[1];
} elseif (isset($_GET['location'])) {
    $file = $_GET['location'];
}

if ($file != '') {
    $parser=xml_parser_create();

    xml_set_element_handler($parser,"start","stop");

    xml_set_character_data_handler($parser,"char");

    $fp=fopen($file,"r");

    while ($data=fread($fp,4096))
    {

        xml_parse($parser,$data,feof($fp)) or
        die (sprintf("XML Error: %s at line %d",
            xml_error_string(xml_get_error_code($parser)),
            xml_get_current_line_number($parser)));

    }


    xml_parser_free($parser);
}

function start($parser,$element_name,$element_attrs)
{
    global $state;

    $state['name'] = $element_name;
    if(count($element_attrs)>=1)
    {
        foreach($element_attrs as $x=>$y)
        {

            $state[$x] = $y;

        }
    }
}

function stop($parser,$element_name)
{
    global $userCount;
    global $state;
    $state='';

    if($element_name == "AD") {
        $userCount++;
    }
}

function char($parser,$data)
{
    global $userCount;
    global $userData;
    global $state;
    global $site;
    if (!$state) {return;}

    if ($state['name']=="DCD") {  $site = $state['SITECODE']; }
    if ($state['name']=="AD") {  $userData[$userCount]["AD"] = $state['ID'];}
    if ($state['name']=="START-DATE") {  $userData[$userCount]["START-DATE"] = $data;}
    if ($state['name']=="END-DATE") { $userData[$userCount]["END-DATE"] = $data;}
    if ($state['name']=="PLACEMENT") { $userData[$userCount]["PLACEMENT"] = $data;}
    if ($state['name']=="POSITION") { $userData[$userCount]["POSITION"] = $data;}
    if ($state['name']=="AD-TEXT") { $userData[$userCount]["AD-TEXT"] = strip_tags ($data);}
    if ($state['name']=="STREET") { $userData[$userCount]["STREET"] = $data;}
    if ($state['name']=="CITY") { $userData[$userCount]["CITY"] = $data;}
    if ($state['name']=="STATE") { $userData[$userCount]["STATE"] = $data;}
    if ($state['name']=="ZIP") { $userData[$userCount]["ZIP"] = $data;}
}

class ClassifiedsAdmin extends \GCI\Database
{
    function insertListings()
    {
        global $userCount;
        global $userData;
        global $site;
        for($i=0;$i<$userCount; $i++)
        {
            if (empty($userData[$i]["STREET"])) {$userData[$i]["STREET"] = '';}
            if (empty($userData[$i]["STATE"])) {$userData[$i]["STATE"] = '';}
            if (empty($userData[$i]["CITY"])) {$userData[$i]["CITY"] = '';}
            if (empty($userData[$i]["ZIP"])) {$userData[$i]["ZIP"] = '';}

            $stmt = $this->prepare("DELETE FROM `listing` WHERE ID = :ID");
            $stmt->execute(array(':ID' => $userData[$i]["AD"]));

            $stmt = $this->prepare("INSERT INTO `listing` (`ID`, `StartDate`, `EndDate`, `Placement`,`Position`, `AdText`, `SiteCode`, `Street`, `City`, `State`, `Zip`) VALUES(:ID, :StartDate, :EndDate, :Placement, :Position, :AdText, :Site, :Street, :City, :State, :Zip)");
            $stmt->execute(array(':ID' => $userData[$i]["AD"], ':StartDate' => $userData[$i]["START-DATE"], ':EndDate' => $userData[$i]["END-DATE"]  , ':Placement' => $userData[$i]["PLACEMENT"], ':Position' => $userData[$i]["POSITION"], ':AdText' => $userData[$i]["AD-TEXT"] , ':Site' => $site, ':Street' =>  $userData[$i]["STREET"], ':City' =>  $userData[$i]["CITY"], ':State' =>  $userData[$i]["STATE"], ':Zip' =>  $userData[$i]["ZIP"] ));
        }
        echo "inserted $userCount rows in listing<br />";
    }

    function deleteOldListings()
    {
        $date = date("Y-m-d");
        $stmt = $this->prepare("DELETE FROM `listing` WHERE `EndDate` < :date ");
        $stmt->execute(array(':date' => $date));
        $count = $stmt->rowCount();
        echo "deleted ". $count . " rows from listing<br />";
    }

    function buildNav()
    {
        $stmt = $this->prepare("TRUNCATE TABLE `position`");
        $stmt->execute();
        $count = $stmt->rowCount();
        echo "deleted ". $count . " rows from position<br />";
        $stmt = $this->prepare("INSERT into `position` (Placement, Position, SiteCode, Count) SELECT Placement, Position, SiteCode, count( * ) FROM listing GROUP BY Placement, Position, SiteCode");
        $stmt->execute();
    }
	
	function getLocation($address){
		$url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=";
		$json = file_get_contents($url.urlencode($address));
		$json = json_decode($json,true);
		if($json['status'] == "OK"){
			$latlon = array(
				'lat' => $json["results"][0]["geometry"]["location"]["lat"],
				'lon' => $json["results"][0]["geometry"]["location"]["lng"]
			);
			return $latlon;
		}
		else{
			return false;
		}
	}
	
	function updateGeocodes(){
		$sql = "SELECT ID, Street, City, State, Zip FROM `listing` WHERE `Street` != '' AND `Lat` = '' ";
		$results = $this->getAssoc($sql);
		
		foreach($results as $row){
			$address = $row['Street'];
			if(!empty($row['City']))
				$address .= ", ".$row['City'];
			if(!empty($row['State']))
				$address .= ", ".$row['State'];
			if(!empty($row['Zip']))
				$address .= " ".$row['Zip'];
			
			$latlon = $this->getLocation($address);
			if($latlon !== false){
				$stmt = $this->prepare("UPDATE `listing` SET `Lat` = :lat, `Long` = :lon WHERE `ID` = :id ");
				$stmt->execute(array(":lat"=>$latlon['lat'],":lon"=>$latlon['lon'],":id"=>$row['ID']));
			}
			//Slow this down so we don't run into problems with Google's Geocoding limits
			sleep(1);
		}
	}

}

$user = new ClassifiedsAdmin();

if ($userCount > 0) {
    $user->insertListings();
}

$user->deleteOldListings();
$user->buildNav();

$user->updateGeocodes();

?>