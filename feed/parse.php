<?php
include('../includes/functions.php');
include('../conf/constants.php');
	
global $usercount;
global $userdata;
global $state;
global $site;

$usercount=0;
$userdata=array();

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
	
	global $usercount;
	global $userdata;
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
	global $usercount;
	global $userdata;
	global $state;
	$state='';
	
	if($element_name == "AD")
	{
		$usercount++;
	}
}

function char($parser,$data)
{
	global $usercount;
	global $userdata;
	global $state;
	global $site;
	if (!$state) {return;}
	
	if ($state['name']=="DCD") {  $site = $state['SITECODE']; }
	if ($state['name']=="AD") {  $userdata[$usercount]["AD"] = $state['ID'];}
	if ($state['name']=="START-DATE") {  $userdata[$usercount]["START-DATE"] = $data;}
	if ($state['name']=="END-DATE") { $userdata[$usercount]["END-DATE"] = $data;}
	if ($state['name']=="PLACEMENT") { $userdata[$usercount]["PLACEMENT"] = $data;}
	if ($state['name']=="POSITION") { $userdata[$usercount]["POSITION"] = $data;}
	if ($state['name']=="AD-TEXT") { $userdata[$usercount]["AD-TEXT"] = strip_tags ($data);}
	if ($state['name']=="STREET") { $userdata[$usercount]["STREET"] = $data;}
	if ($state['name']=="CITY") { $userdata[$usercount]["CITY"] = $data;}
	if ($state['name']=="STATE") { $userdata[$usercount]["STATE"] = $data;}
	if ($state['name']=="ZIP") { $userdata[$usercount]["ZIP"] = $data;}
}

class Admin extends Database
{

	function insertListings()
	{
		global $usercount;
		global $userdata;
		global $state;
		global $site;
		for($i=0;$i<$usercount; $i++) 
		{
			echo 'user '.$i.'<br />';
			echo 'usercount '.$usercount.'<br />';
			
			$stmt = $this->prepare("DELETE FROM `listing` WHERE ID = :ID");
			$stmt->execute(array(':ID' => $userdata[$i]["AD"]));
						
			$stmt = $this->prepare("INSERT INTO `listing` (`ID`, `StartDate`, `EndDate`, `Placement`,`Position`, `AdText`, `SiteCode`, `Street`, `City`, `State`, `Zip`) VALUES(:ID, :StartDate, :EndDate, :Placement, :Position, :AdText, :Site, :Street, :City, :State, :Zip)");						
			$stmt->execute(array(':ID' => $userdata[$i]["AD"], ':StartDate' => $userdata[$i]["START-DATE"], ':EndDate' => $userdata[$i]["END-DATE"]  , ':Placement' => $userdata[$i]["PLACEMENT"], ':Position' => $userdata[$i]["POSITION"], ':AdText' => $userdata[$i]["AD-TEXT"] , ':Site' => $site, ':Street' =>  $userdata[$i]["STREET"], ':City' =>  $userdata[$i]["CITY"], ':State' =>  $userdata[$i]["STATE"], ':Zip' =>  $userdata[$i]["ZIP"] ));									
		}	
		echo "inserted $usercount rows in listing<br />";
	}
	
	function deleteOldListings()
	{
		$date = date("Y-m-d");	
		$stmt = $this->prepare("DELETE FROM `listing` WHERE `EndDate` < :date ");	
		$del = $stmt->execute(array(':date' => $date));
		echo "deleted ". $del . " rows from listing<br />";
	}	
	
	function buildNav()
	{		
		$stmt = $this->prepare("TRUNCATE TABLE `position`");
		$del = $stmt->execute();
		echo "deleted ". $del . " rows from position<br />";
		$stmt = $this->prepare("INSERT into `position` (Placement, Position, SiteCode, Count) SELECT Placement, Position, SiteCode, count( * ) FROM listing GROUP BY Placement, Position, SiteCode");
		$stmt->execute();
	}	
	
}

$user = new Admin();

if ($usercount) {
	$user->insertListings();
}

$user->deleteOldListings();
$user->buildNav();

?>