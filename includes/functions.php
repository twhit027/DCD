<?php
class Database
{	
	public $db;
	
	function __construct() {
		try{
			$this->db = new PDO('mysql:dbname='.DB_NAME.';host='.DB_SERVER.';port='.DB_PORT.';charset=utf8', DB_USER, DB_PASS);
			$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			//error mode on
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $ex){
			//$log->logEmerg('Unable to connect to the database');
	    $db = null;
		}		
	}

	function __destruct() {
    	$this->db;
	}

}

class Navigation extends Database
{
	
	function getSideNavigation()
	{		
	
		$stmt = $this->db->prepare("SELECT * FROM `categories` where `placement_id` = 0 ");
		$stmt->execute();
		$data = '<h3 style="color:#3276B1;">View By Category</h3>';			
		$data .= '<div role="navigation" id="sidebar" >';
		$data .= '<ul class="nav nav-list accordion" id="sidenav-accordian" style="padding-bottom:10px;">';
		foreach ($stmt as $row) 
		{
			$data .='<li>';

			$data .='<div class="accordion-heading" style="padding-bottom:5px;">';
			$data .='<a data-toggle="collapse" class="btn btn-default"  style="width:100%;" role="button" data-target="#accordion-heading-'.$row['id'].'"><span class="nav-header-primary">'.$row['name'].'</span></a>';
			$data .='</div>';
		
			$data .='<ul class="nav nav-list collapse" id="accordion-heading-'.$row['id'].'">';
				$data .= $this->getChildNav($row['id']);
			$data .='</ul>';
			
			$data .='</li>';
		}
		
		$data .= '</ul>';
		
		$data .= '</div>';
		return $data;
	}
	
	function getChildNav($id)
	{
		$stmt = $this->db->prepare("SELECT * FROM `categories` where `placement_id` = :id ");
		$stmt->execute(Array(':id' => $id));
		$data ="";
		foreach ($stmt as $row) 
		{
			$data .='<a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="category.php?x='.$row['id'].'" title="Title">'.$row['name'].'</a>';
		}
		return $data;
				
	}
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