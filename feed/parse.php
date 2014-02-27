<?php
include('../includes/functions.php');
include('../includes/constants.php');


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

$usercount=0;
$userdata=array();

$file = "out-dcd.txt";

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
	
	if (!$state) {return;}
	
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
		
		for($i=0;$i<$usercount; $i++) 
		{
			
			$check = $this->checkAd($userdata[$i]["AD"]);
			
			$this->checkPlacement($userdata[$i]["PLACEMENT"]);
			$this->checkPosition($userdata[$i]["POSITION"], $userdata[$i]["PLACEMENT"]);
			
			
			if($check != "update")
			{
				$stmt = $this->db->prepare("INSERT INTO " . TBL_LISTING . "(`ID`, `StartDate`, `EndDate`, `Position`, `AdText`) VALUES(:ID, :StartDate, :EndDate, :Position, :AdText)");
						
				$stmt->execute(array(':ID' => $userdata[$i]["AD"], ':StartDate' => $userdata[$i]["START-DATE"], ':EndDate' => $userdata[$i]["END-DATE"]  , ':Position' => $userdata[$i]["POSITION"], ':AdText' => $userdata[$i]["AD-TEXT"]));
			}
			else
			{
				
				$stmt = $this->db->prepare("UPDATE " . TBL_LISTING . " SET `StartDate`=:StartDate, `EndDate`=:EndDate, `Position`=:Position, `AdText`=:AdText WHERE `ID`=:ID");
				
				$stmt->execute(array(':ID' => $userdata[$i]["AD"], ':StartDate' => $userdata[$i]["START-DATE"], ':EndDate' => $userdata[$i]["END-DATE"]  , ':Position' => $userdata[$i]["POSITION"], ':AdText' => $userdata[$i]["AD-TEXT"]));
				
			}
			
			
		}	
		$this->DeleteOld();
	}
	
	
	function checkAd($id)
	{
		$data = "";
		$stmt = $this->db->prepare("SELECT * FROM " . TBL_LISTING . " WHERE `ID` = :id ");
		$stmt->execute(array(':id' => $id));
		foreach ($stmt as $row) 
		{
			
			$data = "update";
		}
		
		return $data;
		
		
	}
	
	function DeleteOld()
	{
		$data = "";
		$date = date("Y-m-d");
		
		$stmt = $this->db->prepare("SELECT * FROM " . TBL_LISTING . " WHERE `EndDate` < :date ");
		$stmt->execute(array(':date' => $date));
		foreach ($stmt as $row) 
		{
			$stmt = $this->db->prepare("DELETE FROM " . TBL_LISTING . " WHERE `ID` = :id ");
			$stmt->execute(array(':id' => $row['ID'] ));
			echo "delete ". $row['ID']. " here <br/>";
		}
		
		return $data;
		
		
	}
	
	
	
	function checkPlacement($name)
	{
		$data = "";
		$stmt = $this->db->prepare("SELECT * FROM " .TBL_PLACEMENT . " WHERE `name` = :name ");
		$stmt->execute(array(':name' => $name));
		foreach ($stmt as $row) 
		{
			$data = "checked";
		}
		
		if($data != "checked")
		{
			$stmt = $this->db->prepare("INSERT INTO " .TBL_PLACEMENT . "(`name`) VALUES(:name)");	
			$stmt->execute(array(':name' => $name));
		}
		
		return $data;
		
		
	}
	
	function checkPosition($name, $placement)
	{
		
	
		$data = "";
		$stmt = $this->db->prepare("SELECT * FROM " .  TBL_POSITION . " WHERE `name` = :name AND `placement` = :placement ");
		$stmt->execute(array(':name' => $name, ':placement' => $placement));
		foreach ($stmt as $row) 
		{
			$data = "checked";
		}
		
		if($data != "checked")
		{
			$stmt = $this->db->prepare("INSERT INTO " .  TBL_POSITION . "(`name`, `placement`) VALUES(:name, :placement)");	
			$stmt->execute(array(':name' => $name, ':placement' => $placement));
		}
		
		return $data;
		
		
	}
	

}

	

$user = new Admin();

$uid = $user->putData();


	
	






?>