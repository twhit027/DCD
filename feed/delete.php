<?php
include('../includes/functions.php');
include('../conf/constants.php');


global $db;
class Update
{
		
	function __construct() {
		$this->db = new PDO('mysql:dbname='.DB_NAME.';host='.DB_SERVER.';charset=utf8', DB_USER, DB_PASS);
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		//error mode on
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	function __destruct() {
		$this->db;
	}
	


	
	function delete()
	{
		$date = date("Y-m-d");	
		$stmt = $this->db->prepare("DELETE FROM " . TBL_LISTING . " WHERE `EndDate` < :date ");
		$stmt->execute(array(':date' => $date));
	}
}

$user = new Update();

$uid = $user->delete();






?>