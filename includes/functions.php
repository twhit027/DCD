<?php
class Database
{	
	public $db;
	
	function __construct() {
		try {
		$this->db = new PDO('mysql:dbname='.DB_NAME.';host='.DB_SERVER.';port='.DB_PORT.';charset=utf8', DB_USER, DB_PASS);
		//$this->db = new PDO('mysql:dbname=classifiedsproje;host=localhost;port=3306;charset=utf8', 'classifiedsproj', 'Classdb13!');
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		//error mode on
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  }catch (PDOException $e) {
	    die($e->getMessage());
	  }
	}

	function __destruct() {
    	$this->db;
	}

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



$httpHost = get_host();
$sitedata = get_siteData($httpHost);

class Navigation extends Database
{
	
	function getSideNavigation($siteCode = 'IOW')
	{			
		$stmt = $this->db->prepare("SELECT * FROM `position` where `siteCode`= :siteCode");
		$stmt->execute(array(':siteCode' => urldecode($siteCode)));		
		
		$random = rand(1, 1500);
		$categories = array();
		
		foreach ($stmt as $row) {		
			$categories[$row['Placement']][] = array('id'=>$row['ID'],'position'=>$row['Position'], 'count'=> $row['Count']);	
		}					
		
		$data = '';
		foreach ($categories as $placement => $placementValues) {
			$data .='<li>';
			$data .='<div class="accordion-heading" style="padding-bottom:5px;">';
			$data .='<a data-toggle="collapse" class="btn btn-default"  style="width:100%;" role="button" data-target="#accordion-heading-'.$placementValues[0]['id'].''.$random.'"><span class="nav-header-primary">'.$placement.'</span></a>';
			$data .='</div>';		
			$data .='<ul class="nav nav-list collapse" id="accordion-heading-'.$placementValues[0]['id'].''.$random.'">';				
			foreach ($placementValues as $position) {
					$data .='<a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="category.php?x='.urlencode($position['position']).'" title="Title">'.$position['position'].'('.$position['count'].')</a>';
			}								
			$data .='</ul>';			
			$data .='</li>';		
			
		}
				
		return $data;
	}
	
	function getChildNav($name)
	{
		$stmt = $this->db->prepare("SELECT * FROM `position` where `placement` = :name ");
		$stmt->execute(Array(':name' => $name));
		$data ="";
		foreach ($stmt as $row) 
		{
				$data .='<a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="category.php?x='.urlencode($row['Placement']).'" title="Title">'.$row['Placement'].'('.$row['Count'].')</a>';
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
	
	function getBottomNavigationStatic($siteUrl, $siteName) 
	{
		$data = '<hr /><div class="container" style="font-size: 12px;line-height: 16px;text-align: center"><p>';
		$data .= '<a href="'.$siteUrl.'/news">News</a>&nbsp;|&nbsp;';
		$data .= '<a href="'.$siteUrl.'/sports">Sports</a>&nbsp;|&nbsp;';
		$data .= '<a href="'.$siteUrl.'/business">Business</a>&nbsp;|&nbsp;';
		$data .= '<a href="'.$siteUrl.'/Entertainment">Entertainment</a>&nbsp;|&nbsp;';
		$data .= '<a href="'.$siteUrl.'/life">Life</a>&nbsp;|&nbsp;';
		$data .= '<a href="'.$siteUrl.'/Communities">Communities</a>&nbsp;|&nbsp;';
		$data .= '<a href="'.$siteUrl.'/opinion">Opinion</a>&nbsp;|&nbsp;';						
		$data .= '<a href="http://www.legacy.com/obituaries/'.$siteName.'/">Obituaries</a>&nbsp;|&nbsp;';	
		$data .= '<a href="'.$siteUrl.'/help">Help</a></p>';
		$data .= '<p>Copyright &copy; 2014 www.'.$siteName.'.com. All rights reserved. Users of this site agree to the ';
		$data .= '<a href="'.$siteUrl.'/section/terms">Terms of Service</a>, ';
		$data .= '<a href="'.$siteUrl.'/section/privacy">Privacy Notice</a>, and <a href="'.$siteUrl.'/section/privacy#adchoices">Ad Choices</a></p></div>';
	
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
	
	
	public function getCategoryListing($name,$page=0)
	{		
		//Add pagination
		//First find how many rows we have
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM `listing` where `Position`= :name");
		$stmt->execute(array(':name' => urldecode($name)));
		$numOfRows = $stmt->fetchColumn();
		//Set the max to show per page
		$maxPerPage = 10;
		//Get what page we're on
		$page = (int)$page;
		//If there was no page passed, it'll be zero. Need to bump it to page 1
		if($page < 1)
			$page = 1;
		//Find where to start our limit for the LIMIT SQL call below
		$limitStart = ($page-1)*$maxPerPage;
		//Get our pagination code
		$pagination = "";
		if($numOfRows > $maxPerPage){
			$numOfPages = ceil($numOfRows/$maxPerPage);
			
			if($page > 1)
				$pagination .= '<ul class="pagination"><li><a href="category.php?x='.$name.'&page='.($page-1).'">&laquo;</a></li>';
			else
				$pagination .= '<ul class="pagination"><li class="disabled"><a href="#">&laquo;</a></li>';
			
			for($pge = 1; $pge <= $numOfPages; $pge++){
				if($pge == $page)
					$pagination .= '<li class="active"><a href="category.php?x='.$name.'&page='.$pge.'">'.$pge.' <span class="sr-only">(currecnt)</span></a></li>';
				else
					$pagination .= '<li><a href="category.php?x='.$name.'&page='.$pge.'">'.$pge.'</a></li>';
			}
			
			if($page < $numOfPages)
				$pagination .= '<li><a href="category.php?x='.$name.'&page='.($page+1).'">&raquo;</a></li></ul>';
			else
				$pagination .= '<li class="disabled"><a href="#">&raquo;</a></li></ul>';
		}
		
		$stmt = $this->db->prepare("SELECT * FROM `listing` where `Position`= :name LIMIT :page,:max");
		$stmt->execute(array(':name' => urldecode($name), ':page' => $limitStart, ':max' => $maxPerPage));
		$data = '';	
		
		//Add pagination
		$data .= $pagination;
			
		foreach ($stmt as $row) 
		{		
			if(strlen($row['AdText']) > 200)
			{  
				$string = substr($row['AdText'],0,200)."... <a  href='item.php?x=". $row['ID']."&c=".$name."'>Click for full text</a>";
				
			}
			else
			{	
				$string = $row['AdText'];
				
			}
			
			
			
			
			$data .= "<div class='jumbotron'>";
			$data .= "<p>".$string."</p>";
			
			$status = substr($row['AdText'],0,120);
			
			
			
			$data .= '<a class="btn btn-primary" href="http://twitter.com/home?status='.$status.'" target="_blank">twitter</a>';

			$data.="<a class='btn btn-primary' href='https://www.facebook.com/sharer/sharer.php?u=http://".$_SERVER['SERVER_NAME']."/item.php?x=". $row['ID']."' target='_blank'>Facebook</a>";

			$data .= '<a class="btn btn-primary" href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://'.$_SERVER['SERVER_NAME'].'/item.php?x='. $row['ID'].'" target="_blank">google</a>';
			
			

			$data .='</div>';	
		}
		
		//Add pagination
		$data .= $pagination;
		
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
	
	public function getPartnersString($siteUrl)
	{		
		$data ='		
		<p><a class="button" href="'.$siteUrl.'/placead"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">Place an Ad</button></a></p>             
    <h1>Featured Partner Classified Services</h1>
		<div class="row">
			<div class="col-md-3">
				<h4>Cars</h4>
				<a href="'.$siteUrl.'/cars"><img alt="Cars.com" src="img/partners/130-cars.gif"></a>
				<p><a class="button" href="'.$siteUrl.'/cars" target="_blank"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Autos</button></a></p>
			</div> 		
			<div class="col-md-3">
				<h4>Jobs</h4>
				<a href="'.$siteUrl.'/jobs"><img alt="micareerbuilder.com" src="img/partners/130-careerbuilder.gif"></a>
				<p><a class="button" href="'.$siteUrl.'/jobs" target="_blank"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Jobs</button></a></p>		
			</div>		
			<div class="col-md-3">
				<h4>Homes</h4>
				<a href="'.$siteUrl.'/homes"><img alt="homefinder.com" src="img/partners/130-homefinder.gif" ></a>
				<p><a class="button" href="'.$siteUrl.'/homes" target="_blank"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Homes</button></a></p>
			</div>
			<div class="col-md-3">
				<h4>Rentals</h4>
				<a href="'.$siteUrl.'/apartments"><img alt="apartments.com" src="img/partners/130-apartments.gif" ></a>
				<p><a class="button" href="'.$siteUrl.'/apartments" target="_blank"><button type="button" class="btn btn-primary btn-lg" style="width:100%;">View Listings</button></a></p>
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

class FindRummages extends Database {	
	private function createWhere($column,$values){
		foreach($values as $k=>$v)
			$values[$k] = sprintf("'%s'",$v);
		
		$where = $column." IN (".implode(",",$values).")";
		
		return $where;
	}
	
	public function getRummages($params){
		$data = array();
		$where = '';

		if (!empty($params['where'])) {
				$where = "AND ";
				foreach($params['where'] as $k=>$v) {
					$where .= $this->createWhere($k,$v);
				}
		}
		
		$stmt = $this->db->prepare("SELECT * FROM `listing` WHERE SiteCode in ('IOW') ".$where);
		$stmt->execute(array(':siteCode' => $params['PubCode']));
			
		$results = $stmt->fetchAll();
					
		foreach ($results as $row) {
			$data[$row['ID']] = array(
				"street"=>$row['Street'],
				"city"=>$row['City'],
				"state"=>$row['State'],
				"zip"=>$row['Zip'],
				"lat"=>$row['Lat'],
				"lon"=>$row['Lon']
			);
		}

		return $data;
	}	
}

?>