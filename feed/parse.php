<?php
include('../includes/functions.php');
include('../conf/constants.php');


global $db;
	
function __construct() {
	$this->db = new PDO('mysql:dbname='.DB_NAME.';host='.DB_SERVER.';charset=utf8', DB_USER, DB_PASS);
	$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	//error mode on
	$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

function __destruct() {
	$this->db;
}
	
	
global $usercount;
global $userdata;
global $state;
global $site;

$usercount=0;
$userdata=array();

//$file = "IOW/dcd-IOW.xml";
$file = $_GET['location'];


$parser=xml_parser_create();

xml_set_element_handler($parser,"start","stop");

xml_set_character_data_handler($parser,"char");



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

}




$fp=fopen($file,"r");

while ($data=fread($fp,4096))
{

	xml_parse($parser,$data,feof($fp)) or
	die (sprintf("XML Error: %s at line %d",
	xml_error_string(xml_get_error_code($parser)),
	xml_get_current_line_number($parser)));
	
}


xml_parser_free($parser);

class Admin extends Database
{

	function putData()
	{
		global $usercount;
		global $userdata;
		global $state;
		global $site;
		for($i=0;$i<$usercount; $i++) 
		{
			
			$check = $this->checkAd($userdata[$i]["AD"]);

			$this->checkPosition($userdata[$i]["POSITION"], $userdata[$i]["PLACEMENT"], $site);
			
			
			if($check != "update")
			{
				$stmt = $this->db->prepare("INSERT INTO " . TBL_LISTING . "(`ID`, `StartDate`, `EndDate`, `Placement`,`Position`, `AdText`, `Site`) VALUES(:ID, :StartDate, :EndDate, :Placement, :Position, :AdText, :Site)");
						
				$stmt->execute(array(':ID' => $userdata[$i]["AD"], ':StartDate' => $userdata[$i]["START-DATE"], ':EndDate' => $userdata[$i]["END-DATE"]  , ':Placement' => $userdata[$i]["PLACEMENT"], ':Position' => $userdata[$i]["POSITION"], ':AdText' => $userdata[$i]["AD-TEXT"] , ':Site' => $site ));
			}
			else
			{
				
				$stmt = $this->db->prepare("UPDATE " . TBL_LISTING . " SET `StartDate`=:StartDate, `EndDate`=:EndDate, `Placement`=:Placement, `Position`=:Position, `AdText`=:AdText, `Site` = :Site WHERE `ID`=:ID");
				
				$stmt->execute(array(':ID' => $userdata[$i]["AD"], ':StartDate' => $userdata[$i]["START-DATE"], ':EndDate' => $userdata[$i]["END-DATE"]  , ':Placement' => $userdata[$i]["PLACEMENT"], ':Position' => $userdata[$i]["POSITION"], ':AdText' => $userdata[$i]["AD-TEXT"], ':Site' => $site));
				
			}
			
			
		}	
	
	}
	
	
	function checkAd($id)
	{
		$data = "";
		echo $id ."<br />";
		$stmt = $this->db->prepare("SELECT * FROM " . TBL_LISTING . " WHERE `ID` = :id ");
		$stmt->execute(array(':id' => $id));
		foreach ($stmt as $row) 
		{
			
			$data = "update";
		}
		
		return $data;

	}

	function checkPosition($position, $placement, $site)
	{
		
		
		$data = "";
		$stmt = $this->db->prepare("SELECT * FROM " .  TBL_POSITION . " WHERE `Position` = :position AND `Placement` = :placement AND `Site` = :site");
		$stmt->execute(array(':position' => $position, ':placement' => $placement, ':site' => $site));
		
		foreach ($stmt as $row) 
		{
			$data = "checked";

			$id = $row['ID'];

			echo "<br />";
			echo $id;
		}
		

		if($data != "checked")
		{
			$stmt = $this->db->prepare("INSERT INTO " .  TBL_POSITION . "(`Position`, `Placement`, `Site`) VALUES(:position, :placement, :site)");	
			$stmt->execute(array(':position' => $position, ':placement' => $placement , ':site' => $site));
		}
		
		
		return $data;
		
		
	}
	

}

	

$user = new Admin();

$uid = $user->putData();


	
	






?>