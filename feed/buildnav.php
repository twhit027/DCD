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
	
	

class Admin extends Database
{
		
	function get_sites()
	{		
	
		$stmt = $this->db->prepare("SELECT * FROM `siteinfo`");
		$stmt->execute();

		foreach ($stmt as $row) 
		{	
			$this->clear($row['siteCode']);
			$this->get_categories($row['siteCode'],$row['siteGroup']);
		}

		$this->delete_empty();
	}
	

	public function get_categories($site, $group)
	{	


		$stmt = $this->db->prepare("SELECT Position,ID FROM `positions` where `Site` = :site");
		
		$stmt->execute(array(':site' => $site));

		foreach ($stmt as $row)
		{	
			echo $row['Position'] ." : ".$site;
			$this->get_count($site, $group, $row['Position'], $row['ID']);
			echo "<br />";
		}
		
		
	}
	
	
	
	public function get_count($site, $group, $position, $id)
	{	

		$siteCodes = explode(",", $group);
		
		$where = "SELECT * FROM `listing` where `Position`= ? AND `site` IN(";
		$z = 0;
		foreach($siteCodes as $cd)
		{
		
			if($z == 0)
			{
				$where .= "?";
			}
			else
			{
				$where .= ", ?";
			}
			$z += 1;
		}
		$where .= ")";
	
		$stmt = $this->db->prepare($where);
		
		$stmt->bindParam(1, urldecode($position));
		
		
		
		foreach($siteCodes as $key => &$value)
		{
			$count = $key + 2;
			
			$stmt->bindParam($count, $value);
		
		}
		
		$stmt->execute();
		$count = 1;	
		
		foreach ($stmt as $row)
		{	
			
			echo $row['ID']."<br />";
			$stmt = $this->db->prepare("UPDATE `positions` SET `Count`=:count WHERE `ID`=:ID");
			$stmt->execute(array(':count' => $count, ':ID' => $id));
			$count += 1;	
		}
		
		
	}
	
	
	public function clear($site)
	{	
		
		$stmt = $this->db->prepare("UPDATE `positions` SET `Count`=0 WHERE `Site`=:site");
		$stmt->execute(array(':site' => $site));

	}
	
	public function delete_empty()
	{
		$stmt = $this->db->prepare("DELETE FROM `positions` WHERE `Count` = 0 ");
		$stmt->execute();	
	}

}

	

$user = new Admin();

$uid = $user->get_sites();


	
	






?>