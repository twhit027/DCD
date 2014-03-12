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
	private $site;
	private $catagories;
	private $search;
	private $host;
	private $domain;
	
	function __construct() {
		$this->database = new Database();		
		$this->setSitefromDomain();
		$this->setCategories();
	}	
	
	function getSite() {
		return $this->site;
	}	
	
	function setSite($site) {		
		$this->site = $site;
	}		
	
	function setSitefromDomain($domain = '') {	
		if (empty($domain)) {
			$domain = $this->getDomain();
		}
		$this->site = new Site($this->database, $domain);
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
			$host = $this->getHost();						
		}
		
		$this->host = $host;
	  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $host, $regs)) {
	    $this->domain = $regs['domain'];
	  }	  
	}		
	
	function getCategories() {	
		return $this->categories;
	}	
	
	function setCategories() {	
		$sitecode = $this->getSite()->getSiteCode();
		$stmt = $this->database->prepare("SELECT * FROM `positions` where SiteCode = :siteCode");
		$stmt->execute(array(':siteCode' => $sitecode));	
		
		$results = $stmt->fetchAll();
			
		foreach ($results as $row) {		
			$this->categories[$row['Placement']][] = array('id' => $row['ID'], 'position'=>$row['Position'], 'count'=> $row['Count']);	
		}						
	}
	
	function getAds ($placement, $position, $siteGroup = null) {
		if ($siteGroup == null) {
			$siteGroup = 	$this->site->getSiteGroup();
		}
		$stmt = $this->database->prepare("SELECT * FROM `listings` where placement = ':placement' and position = :position siteCode in (:siteGroup)");
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
	public static $paletteArray = array(
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
	private $url;
	private $siteUrl;
	private $busName;
	private $palette;
	private $siteGroup;
	private $city;
	private $state;
	
	function __construct($db, $domain) {
		$this->setSiteData($db, $domain);
	}	
	
	function setSiteData($db, $domain) {
		$url = 'classifieds.'.$domain;
		
		echo "URL: $url";
		
		$stmt = $db->prepare("SELECT * FROM `siteinfo` where Url = :url");
		$stmt->execute(array(':url' => $url));		
				
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		
		echo "Data:";
		print_r($data);
		echo "Data:";
		
		if (empty($data)) {
			$stmt = $db->prepare("SELECT * FROM `siteinfo`");
			$stmt->execute();						
			$data = $stmt->fetch(PDO::FETCH_ASSOC);			
		}

		$this->siteCode = $data['SiteCode'];
		$this->siteName = $data['SiteName'];
		$this->url = $data['Url'];
		$this->siteUrl = $data['SiteUrl'];		
		$this->busName = $data['BusName'];			
		$this->palette = $data['Palette'];			
		$this->siteGroup = $data['SiteGroup'];
		$this->city = $data['City'];
		$this->state = $data['State'];		
	}	
	
	function getSiteCode() {
		return $this->siteCode;
	}
	
	function getSiteName() {
		return $this->siteName;
	}	
	
	function getSiteUrl() {
		return $this->siteUrl;
	}
	
	function getBusName() {
		return $this->busName;
	}
	
	function getPalette() {
		return $this->palette;
	}		
}	

class Navigation
{
	
	function getSideNavigation($catagories)
	{		
		$random = rand(1, 1500);
		$data = '';
		$placementId = 0;
		foreach ($catagories as $placement => $placementValue) {	
			$placementId++;			
			$data .='<li>';
			$data .='<div class="accordion-heading" style="padding-bottom:5px;">';
			$data .='<a data-toggle="collapse" class="btn btn-default"  style="width:100%;" role="button" data-target="#accordion-heading-'.$placementId.''.$random.'"><span class="nav-header-primary">'.$placement.'</span></a>';
			$data .='</div>';
		
			$data .='<ul class="nav nav-list collapse" id="accordion-heading-'.$placementId.''.$random.'">';
			foreach ($placementValue as $position) {
					$data .='<a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="category.php?x='.urlencode($position['position']).'" title="Title">'.$position['position'].'('.$position['count'].')</a>';
			}
			$data .='</ul>';
			
			$data .='</li>';
		}
		
		
		return $data;
	}

	function getTopNavigationStatic($siteUrl, $top, $bottom, $border)
	{							
		  global $palate;
		  //background:url("'.$siteUrl.'/odygci/p2/spritesheet_x.png") repeat-x scroll 0 -332px -371px rgba(0, 0, 0, 0);
			$data = '<style>.navbar-inverse {							
			background: -webkit-linear-gradient('.$top.', '.$bottom.'); /* For Safari */
			background: -o-linear-gradient('.$top.', '.$bottom.'); /* For Opera 11.1 to 12.0 */
			background: -moz-linear-gradient('.$top.', '.$bottom.'); /* For Firefox 3.6 to 15 */
			background: linear-gradient('.$top.', '.$bottom.'); /* Standard syntax */
			border-bottom-color: '.$border.';
			}</style>';			
			
			$data .= '<nav id="grad" role="navigation" class="collapse navbar-collapse bs-navbar-collapse top-navbar"><ul class="nav navbar-nav">';
			$data .= '<li><a href="'.$siteUrl.'/" style="margin:0;padding:0;"><img style="padding-top:10px" class="img-responsive" src="'.$siteUrl.'/graphics/ody/cobrand_logo.gif"/></a></li>';
			$data .= '<li><a href="'.$siteUrl.'/jobs">JOBS</a></li>';
			$data .= '<li><a href="'.$siteUrl.'/cars">CARS</a></li>';
			$data .= '<li><a href="'.$siteUrl.'/homes">HOMES</a></li>';
			$data .= '<li><a href="'.$siteUrl.'/apartments">APARTMENTS</a></li>';
			$data .= '<li><a href="'.$siteUrl.'/dating">DATING</a></li>';
			$data .= '<li><a href="'.$siteUrl.'/newclass/front/">BUY & SELL</a></li>';						
			$data .= '</ul></nav>';			
		
		return $data;
	}		
	
	function getBottomNavigationStatic($siteUrl, $siteName) {
                $data = '<hr /><div class="container" style="font-size: 12px;line-height: 16px;text-align: center"><p>';
                $data .= '<a href="'.$siteUrl.'/news">News</a>&nbsp;|&nbsp;';
                $data .= '<a href="'.$siteUrl.'/sports">Sports</a>&nbsp;|&nbsp;';
                $data .= '<a href="'.$siteUrl.'/business">Business</a>&nbsp;|&nbsp;';
                $data .= '<a href="'.$siteUrl.'/entertainment">Entertainment</a>&nbsp;|&nbsp;';
                $data .= '<a href="'.$siteUrl.'/life">Life</a>&nbsp;|&nbsp;';
                $data .= '<a href="'.$siteUrl.'/communities">Communities</a>&nbsp;|&nbsp;';
                $data .= '<a href="'.$siteUrl.'/opinion">Opinion</a>&nbsp;|&nbsp;';                                                                                    
                $data .= '<a href="http://www.legacy.com/obituaries/'.$siteName.'/">Obituaries</a>&nbsp;|&nbsp;';                
                $data .= '<a href="'.$siteUrl.'/help">Help</a></p>';
                $data .= '<p>Copyright &copy; 2014 www.'.$siteName.'.com. All rights reserved. Users of this site agree to the ';
                $data .= '<a href="'.$siteUrl.'/section/terms">Terms of Service</a>, ';
                $data .= '<a href="'.$siteUrl.'/section/privacy">Privacy Notice</a>, and <a href="'.$siteUrl.'/section/privacy#adchoices">Ad Choices</a></p></div>';
		return $data;
	}
	
}

?>