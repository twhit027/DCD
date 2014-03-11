<?php
class Database extends PDO
{	
	private $db = NULL;
	private $connection_string = NULL;
	private $db_host = DB_HOST;        
	private $db_user = DB_USER;        
	private $db_pass = DB_PASS;        
	private $db_name = DB_NAME;        
	private $con = false;
	private $result = array();
	
	public function __construct() {
		try {
			//		$this->db = new PDO('mysql:dbname='.DB_NAME.';host='.DB_SERVER.';port='.DB_PORT.';charset=utf8', DB_USER, DB_PASS);
			$this->connection_string = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT.';charset=utf8';
	    parent::__construct($this->connection_string,$this->db_user, $this->db_pass);
	    $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	    $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $this->con = true;
	  }
	  catch (PDOException $e) {
	    die($e->getMessage());
	  }
	}

}

class App
{
	private $database;
	private $siteCode;
	private $site;
	private $catagories;
	private $search;
	private $host;
	private $domain;
	
	function __construct() {
		$this->database = new Database();		
		$this->setSite();
		//$this->setCategories();
	}	
	
	function getSite() {
		return $this->site;
	}	
	
	function setSite($siteCode = '') {		
		if (empty($siteCOde)) {
			$siteCOde = $this->getSiteCOde();
		}
		$this->site = new Site($this->database, $siteCode);
	}		
	
	function setSitefromSiteCode($siteCode) {		
		$this->site = new Site($this->database, $siteCode);
	}		
	
	function getSiteCode() {
		if (empty($this->siteCOde)) {	
			$this->siteCode = $this->getSiteCodeFromDomain();
		}
		
		return $this->siteCode;
	}

	function setSiteCode($siteCode = '') {	
		if (empty($siteCode)) {
				$siteCOde = $this->getSiteCode();
		}	
		$this->siteCode = $siteCOde;
	}	
	
	function getDomain() {
			if (empty($this->domain)) {
				$this->setDomain();
			}		
			return $this->domain;
	}
	
	function setDomain($host = '') { 
		if (empty($host)) {
			//$this->host = gethostname();
			$this->host = $this->getHost();
		}
	  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $host, $regs)) {
	    $this->domain = $regs['domain'];
	  }	  
	}			
	
	function getSiteCodeFromDomain($domain = '') {
		if (empty($domain)) {
				$domain = $this->getDomain();
		}
		$stmt = $this->database->prepare("SELECT siteCode FROM `siteinfo` where url = 'classifieds.:domain'");
		$stmt->execute(array(':domain' => urldecode($this->domain)));	
		
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		if (empty($data['Id'])) {
			return 'DES';
		}
		return $data['Id'];		
	}
	
	function setCategories() {
		$stmt = $this->database->prepare("SELECT * FROM `categories` where `siteCode`= :siteCode");
		$stmt->execute(array(':siteCode' => urldecode($this->siteCode)));	
			
		foreach ($stmt as $row) {		
			$this->categories[$row['position']] = array('placement'=>$row['placement'], 'count'=> $row['count']);	
		}						
	}
	
	function getAds ($placement, $position, $siteGroup = null) {
		if ($siteGroup == null) {
			$siteGroup = 	$this->site->getSiteGroup();
		}
		$stmt = $this->database->prepare("SELECT * FROM `listings` where placement = ':placement' and position = ':position' siteCode in (:siteGroup)");
		$stmt->execute(array(':placement' => $placement, ':position' => $position, ':siteGroup' => implode(', ', $siteGroup)));		
		$dataArray = array();
		foreach ($stmt as $row) {		
			$dataArray[] = array('id'=>$row['id'], 'adtext'=>$row['adText']);
		}			
		
		return $dataArray;
	}
	
	function getHost() {
	    if ($host = @$_SERVER['HTTP_X_FORWARDED_HOST'])
	    {
	        $elements = explode(',', $host);
	        $host = trim(end($elements));
	    } else {
	        if (!$host = @$_SERVER['HTTP_HOST']) {
	            if (!$host = $_SERVER['SERVER_NAME']) {
	                $host = !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
	            }
	        }
	    }
	
	    // Remove port number from host
	    $host = preg_replace('/:\d+$/', '', $host);
	    return trim($host);
	}		
}

class Site
{
	static public $paletteArray = array(
		1 => array('top' => '#292929', 'bottom' => '#080808', 'border' => '#2C2C2C'),
		2 => array('top' => '#01588d', 'bottom' => '#0b396b', 'border' => '#87ABC0'),
		3 => array('top' => '#01abf9', 'bottom' => '#038be6', 'border' => '#87ABC0'),
		4 => array('top' => '#851719', 'bottom' => '#701612', 'border' => '#151515'),
		5 => array('top' => '#000061', 'bottom' => '#00004c', 'border' => '#87ABC0'),
		6 => array('top' => '#000079', 'bottom' => '#000054', 'border' => '#87ABC0'),
		7 => array('top' => '#0000ae', 'bottom' => '#00007f', 'border' => '#87ABC0'),
		8 => array('top' => '#00007b', 'bottom' => '#00005b', 'border' => '#87ABC0')
	);
	
	private $siteCode;
	private $siteName;
	private $siteUrl;
	private $busName;
	private $palette;
	private $city;
	private $state;
	
	function __construct($db, $siteCode) {
		$this->setSiteData($db, $siteCode);
	}	
	
	function setSiteData($db, $siteCode = '') {
		if (empty($siteCode)) {
			$siteCOde = $this->getSiteCode();
		}
		$stmt = $db->prepare("SELECT * FROM `siteInfo` where `siteCode`= :siteCode");
		$stmt->execute(array(':siteCode' => urldecode($siteCOde)));		
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		print_r($data);
		$sitID = $data['siteCode'];
		$siteName = $data['siteName'];
		$siteUrl = $data['siteUrl'];		
		$busName = $data['busName'];			
		$palette = $data['palette'];			
		$city = $data['city'];
		$state = $data['state'];		
	}	
	
	function getSiteCode() {
		return $this->siteCOde;
	}
	
	function getSiteUrl() {
		return $this->siteUrl;
	}
}	

?>