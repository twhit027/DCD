<?php
include('../includes/functions.php');
include('../conf/constants.php');
	
class Admin extends Database
{
		
	function get_sites()
	{		
		$stmt = $this->db->prepare("INSERT into `position` (Placement, Position, SiteCode, Count) SELECT Placement, Position, SiteCode, count( * ) FROM listing GROUP BY Placement, Position, SiteCode");
		$stmt->execute();
	}

}

$user = new Admin();

$uid = $user->get_sites();


	
	






?>