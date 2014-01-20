<?php
class Database
{	
	public $db;
	
	function __construct() {
		$this->db = new PDO('mysql:dbname='.DB_NAME.';host='.DB_SERVER.';port='.DB_PORT.';charset=utf8', DB_USER, DB_PASS);
		//$this->db = new PDO('mysql:dbname=classifiedsproje;host=localhost;port=3306;charset=utf8', 'classifiedsproj', 'Classdb13!');
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		//error mode on
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function __destruct() {
    	$this->db;
	}

}

$sites = array(
			'DES' => array('siteName' => 'desmoinesregister', 'siteUrl' => 'http://www.desmoinesregister.com', 'busName' => 'The Des Moines Register', 'palate' => 2),
			'INI' => array('siteName' => 'indystar', 'siteUrl' => 'http://www.indystar.com', 'busName' => 'The Indianapolis Star', 'palate' => 1),
			'IOW' => array('siteName' => 'press-citizen', 'siteUrl' => 'http://www.press-citizen.com', 'busName' => 'The Press-Citizen', 'palate' => 4),
			'POU' => array('siteName' => 'poughkeepsiejournal', 'siteUrl' => 'http://www.poughkeepsiejournal.com', 'busName' => 'The Poughkeepsie Journal', 'palate' => 4),
			'TJN' => array('siteName' => 'lohud', 'siteUrl' => 'http://www.lohud.com', 'busName' => 'The Journal News', 'palate' => 2)		
);
		
$url = $_SERVER['REQUEST_URI'];
$siteCode = 'DES';
if (isset($_GET['sc'])&&(isset($sites[strtoupper($_GET['sc'])]))) {
	$siteCode = strtoupper($_GET['sc']);
}
$siteUrl = $sites[$siteCode]['siteUrl'];
$siteName = $sites[$siteCode]['siteName'];
$busName = $sites[$siteCode]['busName'];
$palNum = $sites[$siteCode]['palate'];	

$palate = array(
	1 => array('top' => '#292929', 'bottom' => '#080808', 'border' => '#2C2C2C'),
	2 => array('top' => '#01588d', 'bottom' => '#0b396b', 'border' => '#87ABC0'),
	3 => array('top' => '#01abf9', 'bottom' => '#038be6', 'border' => '#87ABC0'),
	4 => array('top' => '#851719', 'bottom' => '#701612', 'border' => '#151515'),
	5 => array('top' => '#000061', 'bottom' => '#00004c', 'border' => '#87ABC0'),
	6 => array('top' => '#000079', 'bottom' => '#000054', 'border' => '#87ABC0'),
	7 => array('top' => '#0000ae', 'bottom' => '#00007f', 'border' => '#87ABC0'),
	8 => array('top' => '#00007b', 'bottom' => '#00005b', 'border' => '#87ABC0')
);

class Navigation extends Database
{
	function getTopNavigation()
	{
		
		$stmt = $this->db->prepare("SELECT * FROM `siteinfo`");
		$stmt->execute();
		$data = '';
			
			
		foreach ($stmt as $row) 
		{
			$data .= '<style>.navbar-inverse {	
			background-color:'.$row['color1'].'!important;
			border-color:'.$row['color2'].'important;
			}</style>';
			
			$data .= '<nav role="navigation" class="collapse navbar-collapse bs-navbar-collapse top-navbar "><ul class="nav navbar-nav">';
			$data .= '<li><img src="'.$row['CoBrandURL'].'"/></li>';
			
			if($row['link1'] != "")
			{
				$data .= '<li><a href="'.$row['link1'].'">'.$row['item1'].'</a></li>';
			}
			
			if($row['link2'] != "")
			{
				$data .= '<li><a href="'.$row['link2'].'">'.$row['item2'].'</a></li>';
			}
			
			if($row['link3'] != "")
			{
				$data .= '<li><a href="'.$row['link3'].'">'.$row['item3'].'</a></li>';
			}
			
			if($row['link4'] != "")
			{
				$data .= '<li><a href="'.$row['link4'].'">'.$row['item4'].'</a></li>';
			}
			
			if($row['link5'] != "")
			{
				$data .= '<li><a href="'.$row['link5'].'">'.$row['item5'].'</a></li>';
			}
			
			if($row['link6'] != "")
			{
				$data .= '<li><a href="'.$row['link6'].'">'.$row['item6'].'</a></li>';
			}
			
			$data .= '</ul></nav>';
			
		}
		
		return $data;
	}

	function getTopNavigationStatic($siteUrl, $palateNum)
	{							
		  global $palate;
		  //background:url("'.$siteUrl.'/odygci/p2/spritesheet_x.png") repeat-x scroll 0 -332px -371px rgba(0, 0, 0, 0);
			$data = '<style>.navbar-inverse {							
			background: -webkit-linear-gradient('.$palate[$palateNum]['top'].', '.$palate[$palateNum]['bottom'].'); /* For Safari */
			background: -o-linear-gradient('.$palate[$palateNum]['top'].', '.$palate[$palateNum]['bottom'].'); /* For Opera 11.1 to 12.0 */
			background: -moz-linear-gradient('.$palate[$palateNum]['top'].', '.$palate[$palateNum]['bottom'].'); /* For Firefox 3.6 to 15 */
			background: linear-gradient('.$palate[$palateNum]['top'].', '.$palate[$palateNum]['bottom'].'); /* Standard syntax */
			border-bottom-color: '.$palate[$palateNum]['border'].';
			}</style>';			
			
			$data .= '<nav id="grad" role="navigation" class="collapse navbar-collapse bs-navbar-collapse top-navbar"><ul class="nav navbar-nav">';
			$data .= '<li><img style="padding-top:10px" class="img-responsive" src="'.$siteUrl.'/graphics/ody/cobrand_logo.gif"/></li>';
			$data .= '<li><a href="'.$siteUrl.'/jobs">JOBS</a></li>';
			$data .= '<li><a href="'.$siteUrl.'/cars">CARS</a></li>';
			$data .= '<li><a href="'.$siteUrl.'/homes">HOMES</a></li>';
			$data .= '<li><a href="'.$siteUrl.'/apartments">APARTMENTS</a></li>';
			$data .= '<li><a href="'.$siteUrl.'/dating">DATING</a></li>';
			$data .= '<li><a href="'.$siteUrl.'/newclass/front/">BUY & SELL</a></li>';						
			$data .= '</ul></nav>';			
		
		return $data;
	}		
	
	function getBottomNavigationStatic() 
	{
		
	}
}

class status extends Database
{
	function getStatus() {
		try {	
				$stmt = $this->db->prepare("SELECT * FROM `siteinfo`");
				$stmt->execute();
		} catch(PDOException $ex) {
		    return false;
		}	
		
		return true;
	}
}
class Ads extends Database
{
	function InitializeAds()
	{
		$data ="<script type='text/javascript'>
				var googletag = googletag || {};
				googletag.cmd = googletag.cmd || [];
				(function() {
				var gads = document.createElement('script');
				gads.async = true;
				gads.type = 'text/javascript';
				var useSSL = 'https:' == document.location.protocol;
				gads.src = (useSSL ? 'https:' : 'http:') + 
				'//www.googletagservices.com/tag/js/gpt.js';
				var node = document.getElementsByTagName('script')[0];
				node.parentNode.insertBefore(gads, node);
				})();
				</script>
				
				<script type='text/javascript'>
				googletag.cmd.push(function() {
				googletag.defineSlot('/7103/wi-fonddulac-C1516/728x90_1/news/main', [728, 90], 'div-gpt-ad-1387471826615-0').addService(googletag.pubads());
				googletag.defineSlot('/7103/wi-fonddulac-C1516/728x90_2/news/main', [728, 90], 'div-gpt-ad-1387471826615-1').addService(googletag.pubads());
				googletag.defineSlot('/7103/wi-fonddulac-C1516/flex_1/news/main', [[300, 250], [300, 600], [300, 800]], 'div-gpt-ad-1387471826615-2').addService(googletag.pubads());
				googletag.defineSlot('/7103/wi-fonddulac-C1516/launchpad_SF/news/main', [[728, 90], [940, 30], [960, 66], [970, 66], [980, 66]], 'div-gpt-ad-1387471826615-3').addService(googletag.pubads());
				googletag.pubads().enableSingleRequest();
				googletag.enableServices();
				});
				</script>";
				
		return $data;		
				
				
	}
	function getLaunchpad()
	{
		$data ="<!-- wi-fonddulac-C1516/launchpad_SF/news/main -->
		<div style='padding-top:90px;padding-bottom:10px;text-align: center;'>
			<!-- wi-fonddulac-C1516/launchpad_SF/news/main -->
			<div id='div-gpt-ad-1387471826615-3'>
			<script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1387471826615-3'); });
			</script>
			</div>
		</div>";
		return $data;
	}
	function getLeaderBottom()
	{
		$data ="<div  style='text-align: center;padding-top:20px;'>
				<div id='div-gpt-ad-1387471826615-1'>
				<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1387471826615-1'); });
				</script>
				</div>
				</div>";
		return $data;
	}
	function getLeaderTop()
	{
		$data ="
		<div style='padding-top:90px;padding-bottom:10px;text-align: center;'>
			<div id='div-gpt-ad-1387471826615-0' style='width:728px; height:90px;'>
				<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1387471826615-0'); });
				</script>
			</div>
		</div>";
		return $data;
	}
	function getFlex()
	{
		$data ="<div style='text-align:center;' >
					<div id='div-gpt-ad-1387471826615-2'>
					<script type='text/javascript'>
					googletag.cmd.push(function() { googletag.display('div-gpt-ad-1387471826615-2'); });
					</script>
					</div>
				</div>";
		return $data;
	}
	
}
class Content extends Database
{	
	public function getPartners()
	{		
		$stmt = $this->db->prepare("SELECT * FROM `siteinfo`");
		$stmt->execute();
		$data = '';	
			
		foreach ($stmt as $row) 
		{			
		$data.='
		
		<p><a class="button" href="'.$row['placead'].'"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">Place an Ad</button></a></p>
             
        <h1>Featured Partner Classified Services</h1>
		<div class="row">
		<div class="col-sm-3">
		<h4>Cars</h4>
		<a href="'.$row['cars'].'"><img alt="Cars.com" src="images/130-cars.gif"></a>
		<p><a class="button" href="'.$row['cars'].'"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Autos</button></a></p>
		
		</div> 
		
		<div class="col-md-3">
		<h4>Jobs</h4>
		<a href="'.$row['jobs'].'"><img alt="micareerbuilder.com" src="images/130-careerbuilder.gif"></a>
		<p><a class="button" href="'.$row['jobs'].'"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Jobs</button></a></p>
		
		</div>
		
		<div class="col-md-3">
		<h4>Homes</h4>
		<a href="'.$row['homes'].'"><img alt="homefinder.com" src="images/130-homefinder.gif" ></a>
		<p><a class="button" href="'.$row['homes'].'"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Homes</button></a></p>
		
		</div>
		
		<div class="col-md-3">
		<h4>Rentals</h4>
		<a href="'.$row['rentals'].'"><img alt="apartments.com" src="images/130-apartments.gif" ></a>
		<p><a class="button" href="'.$row['rentals'].'"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Listings</button></a></p>
		
		</div>
		</div>';
		
		}
		return $data;
	}
	
	public function getPartnersString($siteUrl)
	{		
		$data ='		
		<p><a class="button" href="'.$siteUrl.'/placead"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">Place an Ad</button></a></p>             
    <h1>Featured Partner Classified Services</h1>
		<div class="row">
			<div class="col-md-3">
				<h4>Cars</h4>
				<a href="'.$siteUrl.'/cars"><img alt="Cars.com" src="images/130-cars.gif"></a>
				<p><a class="button" href="'.$siteUrl.'/cars"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Autos</button></a></p>
			</div> 		
			<div class="col-md-3">
				<h4>Jobs</h4>
				<a href="'.$siteUrl.'/jobs"><img alt="micareerbuilder.com" src="images/130-careerbuilder.gif"></a>
				<p><a class="button" href="'.$siteUrl.'/jobs"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Jobs</button></a></p>		
			</div>		
			<div class="col-md-3">
				<h4>Homes</h4>
				<a href="'.$siteUrl.'/homes"><img alt="homefinder.com" src="images/130-homefinder.gif" ></a>
				<p><a class="button" href="'.$siteUrl.'/homes"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Homes</button></a></p>
			</div>		
			<div class="col-md-3">
				<h4>Rentals</h4>
				<a href="'.$siteUrl.'/apartments"><img alt="apartments.com" src="images/130-apartments.gif" ></a>
				<p><a class="button" href="'.$siteUrl.'/apartments"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Listings</button></a></p>
			</div>
		</div>';
		
		return $data;
	}	
	
}
class Tracking extends Database
{
	function getTracking()
	{
	}

}

?>