<?php
class Database
{	
	public $db;
	public $domain2SiteCode;
	public $palate;
	public $siteUrl;
	public $siteName;
	public $busName;
	public $palNum;	
	public $httpHost;	
	public $siteGroup;
	function __construct() {
		$this->db = new PDO('mysql:dbname='.DB_NAME.';host='.DB_SERVER.';port='.DB_PORT.';charset=utf8', DB_USER, DB_PASS);
		//$this->db = new PDO('mysql:dbname=classifiedsproje;host=localhost;port=3306;charset=utf8', 'classifiedsproj', 'Classdb13!');
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		//error mode on
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		
		
		$this->palate = array(
			1 => array('top' => '#292929', 'bottom' => '#080808', 'border' => '#2C2C2C'),
			2 => array('top' => '#01588d', 'bottom' => '#0b396b', 'border' => '#87ABC0'),
			3 => array('top' => '#01abf9', 'bottom' => '#038be6', 'border' => '#87ABC0'),
			4 => array('top' => '#851719', 'bottom' => '#701612', 'border' => '#151515'),
			5 => array('top' => '#000061', 'bottom' => '#00004c', 'border' => '#87ABC0'),
			6 => array('top' => '#000079', 'bottom' => '#000054', 'border' => '#87ABC0'),
			7 => array('top' => '#0000ae', 'bottom' => '#00007f', 'border' => '#87ABC0'),
			8 => array('top' => '#00007b', 'bottom' => '#00005b', 'border' => '#87ABC0')
		);
		
		
		$this->httpHost = $this->get_host();
		
		$site = $this->get_siteData($this->httpHost);
		
		if ($site =="") 
		{
			$site = $this->get_siteData("classifieds.desmoinesregister.com");
		}

		$this->siteCode = $site['siteCode'];
		$this->siteUrl = $site['siteUrl'];
		$this->siteName = $site['siteName'];
		$this->busName = $site['busName'];
		$this->palNum = $site['palette'];	
		$this->siteGroup = $site['sitegroup'];	
		
	}

	function __destruct() {
    	$this->db;
	}
	
	
	
		
		
	function get_host() 
	{
		if ($host = @$_SERVER['HTTP_X_FORWARDED_HOST'])
		{
			$elements = explode(',', $host);
			$host = trim(end($elements));
		} 
		else 
		{
			if (!$host = @$_SERVER['HTTP_HOST']) 
			{
				if (!$host = $_SERVER['SERVER_NAME']) 
				{
					$host = !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
				}
			}
		}
	
		// Remove port number from host
		$host = preg_replace('/:\d+$/', '', $host);
		
		return trim($host);
	}

	function get_domain($host)
	{  
	  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $host, $regs))
	  {
		return $regs['domain'];
	  }
	  
	  return false;
	}

	function get_siteData($code)
	{		
	
		$stmt = $this->db->prepare("SELECT * FROM `siteinfo` WHERE `url` = :code ");
		$stmt->execute(array(':code' => $code));
		$data = '';
		foreach ($stmt as $row) 
		{
				$data['siteUrl'] = $row['siteUrl'];
				$data['siteName'] = $row['siteName'];
				$data['busName'] = $row['busName'];
				$data['palette'] = $row['palette'];	
				$data['sitegroup'] = $row['siteGroup'];	
				$data['siteCode'] = $row['siteCode'];
		}
		return $data;
	}


}



class Navigation extends Database
{
	
	function getSideNavigation()
	{		
	
		$siteCodes['codes'] = explode(",", $this->siteGroup);
		

		$where = "SELECT DISTINCT (Placement), ID FROM `positions` where `site` IN(";
		$z = 0;
		foreach($siteCodes['codes'] as $cd)
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
		$where .= ") GROUP BY Placement ORDER BY Placement";
	
		$stmt = $this->db->prepare($where);

		foreach($siteCodes['codes'] as $key => &$value)
		{
			$count = $key + 1;
			
			$stmt->bindParam($count, $value);
		
		}
		
		$stmt->execute();
	
		$data = "";
		$count = 0;
		
		foreach ($stmt as $row)
		{	
			$random = rand(1, 1500);
			$data .= '<li>';
				$data .='<div class="accordion-heading" style="padding-bottom:5px;">';
				$data .= '<a data-toggle="collapse" class="btn btn-default" role="button" data-target="#accordion-heading-'.$random.'"  style="width:100%;" >';
				$data .= '<span class="nav-header-primary">'.$row['Placement'].'</span>';
				$data .= '</a>';
				$data .= '</div>';
				
				$data .='<ul class="nav nav-list collapse" id="accordion-heading-'.$random.'">';
				$data .= $this->getNavSubItems($row['Placement']);
				$data .= '</ul>';
			
			$data .= '</li>';
			
			
			$count += 1;
			
			
		}
		
	
		
		return $data;

	}
	
	
	function getNavSubItems($placement)
	{		
	
		$siteCodes['codes'] = explode(",", $this->siteGroup);

		$where = "SELECT * FROM `positions` where `Placement`= ? AND `site` IN(";
		$z = 0;
		foreach($siteCodes['codes'] as $cd)
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
		$where .= ") ORDER BY Position ";
	
		$stmt = $this->db->prepare($where);
		
		$stmt->bindParam(1, urldecode($placement));
		
		foreach($siteCodes['codes'] as $key => &$value)
		{
			$count = $key + 2;
			
			$stmt->bindParam($count, $value);
		
		}
		
		$stmt->execute();
		$data = '';
		
		foreach ($stmt as $row)
		{	
		
			
			$data .='<a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="category.php?x='.$row['Position'].'" title="Title">'.$row['Position'].'('.$row['Count'].')</a>';
		}
		

		
		return $data;


	}
	
	
	
	function getSideNavigationBuild()
	{			
		$stmt = $this->db->prepare("SELECT DISTINCT(ClassCode) FROM `listing`");
		$stmt->execute();
		$random = rand(1, 1500);
		$data = '';

		foreach ($stmt as $row) 
		{
			preg_match( '/-(.+)-/' , $row['ClassCode'], $matches);
			
			echo 'matches: ';
			print_r($matches);
			
			if ($matches[1]) {
				$data .='<li>';
	
				$data .='<div class="accordion-heading" style="padding-bottom:5px;">';
				$data .='<a data-toggle="collapse" class="btn btn-default"  style="width:100%;" role="button" data-target="#accordion-heading-'.$random.'"><span class="nav-header-primary">'.$matches[1].'</span></a>';
				$data .='</div>';
			
				$data .='<ul class="nav nav-list collapse" id="accordion-heading-'.$random.'">';					
					$data .= $this->getChildNavByClassCode($row['id']);
				$data .='</ul>';
			
			$data .='</li>';
			}
		}
		
		
		return $data;
	}
	
	function getChildNavByClassCode($classCode)
	{
		$stmt = $this->db->prepare("SELECT SubclassCode FROM `listing` where `ClassCode` = :classCode ");
		$stmt->execute(Array(':classCode' => $classCode));
		$data ="";
		foreach ($stmt as $row) 
		{
			$data .='<a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="category.php?x='.$row['SubclassCode'].'" title="Title">'.$row['SubclassCode'].'</a>';
		}
		return $data;
				
	}		
		
	function getChildNav($name)
	{
		$stmt = $this->db->prepare("SELECT * FROM `positions` where `placement` = :name ");
		$stmt->execute(Array(':name' => $name));
		$data ="";
		foreach ($stmt as $row) 
		{
			$count = $this->categoryAdCheck($row['Position']);
			if($count != 0)
			{
				$data .='<a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="category.php?x='.urlencode($row['Position']).'" title="Title">'.$row['Position'].'('.$count.')</a>';
			}
		}
		return $data;
				
	}
	

	public function categoryAdCheck($name)
	{		
		$stmt = $this->db->prepare("SELECT * FROM `listing` where `Position`= :name");
		$stmt->execute(array(':name' => urldecode($name)));
		$count = 0;	
			
		foreach ($stmt as $row) 
		{		
			$count += 1;		
		}
		
		return $count;
	}
				
	function getTopNavigationStatic()
	{					
			
		  $this->palate;
		  //background:url("'.$siteUrl.'/odygci/p2/spritesheet_x.png") repeat-x scroll 0 -332px -371px rgba(0, 0, 0, 0);
			$data = '<style>.navbar-inverse {							
			background: -webkit-linear-gradient('.$this->palate[$this->palNum]['top'].', '.$this->palate[$this->palNum]['bottom'].'); /* For Safari */
			background: -o-linear-gradient('.$this->palate[$this->palNum]['top'].', '.$this->palate[$this->palNum]['bottom'].'); /* For Opera 11.1 to 12.0 */
			background: -moz-linear-gradient('.$this->palate[$this->palNum]['top'].', '.$this->palate[$this->palNum]['bottom'].'); /* For Firefox 3.6 to 15 */
			background: linear-gradient('.$this->palate[$this->palNum]['top'].', '.$this->palate[$this->palNum]['bottom'].'); /* Standard syntax */
			border-bottom-color: '.$this->palate[$this->palNum]['border'].';
			}</style>';			
			
			$data .= '<nav id="grad" role="navigation" class="collapse navbar-collapse bs-navbar-collapse top-navbar"><ul class="nav navbar-nav">';
			$data .= '<li><a href="'.$this->siteUrl.'/" style="margin:0;padding:0;"><img style="padding-top:10px" class="img-responsive" src="'.$this->siteUrl.'/graphics/ody/cobrand_logo.gif"/></a></li>';
			$data .= '<li><a href="'.$this->siteUrl.'/jobs">JOBS</a></li>';
			$data .= '<li><a href="'.$this->siteUrl.'/cars">CARS</a></li>';
			$data .= '<li><a href="'.$this->siteUrl.'/homes">HOMES</a></li>';
			$data .= '<li><a href="'.$this->siteUrl.'/apartments">APARTMENTS</a></li>';
			$data .= '<li><a href="'.$this->siteUrl.'/dating">DATING</a></li>';
			$data .= '<li><a href="'.$this->siteUrl.'/newclass/front/">BUY & SELL</a></li>';						
			$data .= '</ul></nav>';			
		
		return $data;
	}		
	
	function getBottomNavigationStatic() 
	{
		
		$data ='<input type="hidden" name="SC" value="'. $this->siteCode.'">';
		$data .='<input type="hidden" name="HH" value="'. $this->httpHost.'">';
		//cut out because domain no longer set is this ok?
		//$data .='<input type="hidden" name="DM" value="'. $this->domain.'">';

		$data .= '<hr /><div class="container" style="font-size: 12px;line-height: 16px;text-align: center"><p>';
		$data .= '<a href="'.$this->siteUrl.'/news">News</a>&nbsp;|&nbsp;';
		$data .= '<a href="'.$this->siteUrl.'/sports">Sports</a>&nbsp;|&nbsp;';
		$data .= '<a href="'.$this->siteUrl.'/business">Business</a>&nbsp;|&nbsp;';
		$data .= '<a href="'.$this->siteUrl.'/entertainment">Entertainment</a>&nbsp;|&nbsp;';
		$data .= '<a href="'.$this->siteUrl.'/life">Life</a>&nbsp;|&nbsp;';
		$data .= '<a href="'.$this->siteUrl.'/communities">Communities</a>&nbsp;|&nbsp;';
		$data .= '<a href="'.$this->siteUrl.'/opinion">Opinion</a>&nbsp;|&nbsp;';						
		$data .= '<a href="http://www.legacy.com/obituaries/'.$this->siteName.'/">Obituaries</a>&nbsp;|&nbsp;';	
		$data .= '<a href="'.$this->siteUrl.'/help">Help</a></p>';
		$data .= '<p>Copyright &copy; 2014 www.'.$this->siteName.'.com. All rights reserved. Users of this site agree to the ';
		$data .= '<a href="'.$this->siteUrl.'/section/terms">Terms of Service</a>, ';
		$data .= '<a href="'.$this->siteUrl.'/section/privacy">Privacy Notice</a>, and <a href="'.$this->siteUrl.'/section/privacy#adchoices">Ad Choices</a></p></div>';				
				
		return $data;
		
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
	public function introText()
	{
		$data = "<h1>".$this->busName." Online Classifieds</h1>";
        $data.="<a href='".$this->siteUrl."'><img target='_blank' alt='".$this->siteName."' title='".$this->siteName."' style='margin-bottom:10px;background-color:black' src='".$this->siteUrl."/graphics/ody/cobrand_logo.gif'></a>";
        $data.="<p>We have many ad packages to suit your classified needs.</p>";
        $data.=" <p>Place a classified ad in ".$this->busName." in-paper and online. List all kinds of items including Merchandise, Pets, Garage Sales, Services, and much more. </p>";
        
		return $data;
	}
	public function getAd($id)
	{		
		$stmt = $this->db->prepare("SELECT * FROM `listing` where `ID`= :id");
		$stmt->execute(array(':id' => $id));
		$data = '';	
			
		foreach ($stmt as $row) 
		{		
			$data .= " <div class='jumbotron' >
              <p>".$row['AdText']."</p>";
			  
			  $status = substr($row['AdText'],0,120);
			$data .= '<a class="btn btn-primary" href="http://twitter.com/home?status='.$status.'" target="_blank">twitter</a>';

			$data.="<a class='btn btn-primary' href='https://www.facebook.com/sharer/sharer.php?u=http://".$_SERVER['SERVER_NAME']."/item.php?x=". $row['ID']."' target='_blank'>Facebook</a>";
	
			$data .= '<a class="btn btn-primary" href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://'.$_SERVER['SERVER_NAME'].'/item.php?x='. $row['ID'].'" target="_blank">google</a>';
				
			$data .= "</div>";	
		}
		
		return $data;
	}
	
	public function getMeta($id)
	{		
		$stmt = $this->db->prepare("SELECT * FROM `listing` where `ID`= :id");
		$stmt->execute(array(':id' => $id));
		$data = '';	
			
		foreach ($stmt as $row) 
		{		
			$data .= "<meta property='og:title' content='".substr($row['AdText'],0,200)."' />";
			$data .= "<meta property='og:url' content='".$_SERVER['SERVER_NAME']."/item.php?x=". $row['ID']."' />";
			$data .= "<meta property='og:description' content='".$row['AdText']."' />";
				
		}
		
		return $data;
	}
	
	
	public function getCategoryListing($name)
	{	
	
		
		
		$siteCodes['codes'] = explode(",", $this->siteGroup);
		
		//works go to http://classifieds.desmoinesregister.com/category.php?x=Appliances for example
		//$siteCodes['codes'] = array('DES','IOW');
		//$siteCodes['codes'] = array('DES');
		
		
		$where = "SELECT * FROM `listing` where `Position`= ? AND `site` IN(";
		$z = 0;
		foreach($siteCodes['codes'] as $cd)
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
		
		$stmt->bindParam(1, urldecode($name));
		
		
		
		foreach($siteCodes['codes'] as $key => &$value)
		{
			$count = $key + 2;
			
			$stmt->bindParam($count, $value);
		
		}
		
		$stmt->execute();
		$data = '';	
		
		foreach ($stmt as $row)
		{	
		
			if(strlen($row['AdText']) > 200)
			{
				$string = substr($row['AdText'],0,200)."... <a href='item.php?x=". $row['ID']."'>Click for full text</a>";
			}
			else
			{	
				$string = $row['AdText'];
			}
			$data .= " <div class='jumbotron'>
			<p>".$string."</p>
			<p>
			<button class='btn btn-primary btn-lg' class='btn btn-default'>Add To List</button>
			<button class='btn btn-primary btn-lg' class='btn btn-default'>Tweet</button>
			<button class='btn btn-primary btn-lg' class='btn btn-default'>Facebook</button>
			</p>
			</div>";	
		}
		
		return $data;
	}
	
	
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
		<a href="'.$row['cars'].'"><img alt="Cars.com" src="img/partners/130-cars.gif"></a>
		<p><a class="button" href="'.$row['cars'].'"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Autos</button></a></p>
		
		</div> 
		
		<div class="col-md-3">
		<h4>Jobs</h4>
		<a href="'.$row['jobs'].'"><img alt="micareerbuilder.com" src="img/partners/130-careerbuilder.gif"></a>
		<p><a class="button" href="'.$row['jobs'].'"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Jobs</button></a></p>
		
		</div>
		
		<div class="col-md-3">
		<h4>Homes</h4>
		<a href="'.$row['homes'].'"><img alt="homefinder.com" src="img/partners/130-homefinder.gif" ></a>
		<p><a class="button" href="'.$row['homes'].'"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Homes</button></a></p>
		
		</div>
		
		<div class="col-md-3">
		<h4>Rentals</h4>
		<a href="'.$row['rentals'].'"><img alt="apartments.com" src="img/partners/130-apartments.gif" ></a>
		<p><a class="button" href="'.$row['rentals'].'"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Listings</button></a></p>
		
		</div>
		</div>';
		
		}
		return $data;
	}
	
	public function getPartnersString()
	{		
		$data ='		
		<p><a class="button" href="'.$this->siteUrl.'/placead"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">Place an Ad</button></a></p>             
    <h1>Featured Partner Classified Services</h1>
		<div class="row">
			<div class="col-md-3">
				<h4>Cars</h4>
				<a href="'.$this->siteUrl.'/cars"><img alt="Cars.com" src="img/partners/130-cars.gif"></a>
				<p><a class="button" href="'.$this->siteUrl.'/cars"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Autos</button></a></p>
			</div> 		
			<div class="col-md-3">
				<h4>Jobs</h4>
				<a href="'.$this->siteUrl.'/jobs"><img alt="micareerbuilder.com" src="img/partners/130-careerbuilder.gif"></a>
				<p><a class="button" href="'.$this->siteUrl.'/jobs"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Jobs</button></a></p>		
			</div>		
			<div class="col-md-3">
				<h4>Homes</h4>
				<a href="'.$this->siteUrl.'/homes"><img alt="homefinder.com" src="img/partners/130-homefinder.gif" ></a>
				<p><a class="button" href="'.$this->siteUrl.'/homes"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Homes</button></a></p>
			</div>
			<div class="col-md-3">
				<h4>Rentals</h4>
				<a href="'.$this->siteUrl.'/apartments"><img alt="apartments.com" src="img/partners/130-apartments.gif" ></a>
				<p><a class="button" href="'.$this->siteUrl.'/apartments"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Listings</button></a></p>
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