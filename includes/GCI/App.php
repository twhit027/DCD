<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 3/15/14
 * Time: 4:21 PM
 */

namespace GCI;


class App
{
    private $database;
    private $site;
    private $categories;
    private $listings;
    private $rummages;
    private $host;
    private $domain;
    private $deviceType;
    private $log;

    function __construct($siteCode = '')
    {
        $this->database = new Database();

        $this->detectDevice();

        if (empty($siteCode)) {
            $this->setSiteFromDomain();
        } else {
            $this->setSiteFromSiteCode($siteCode);
        }
        $this->setCategories();

        if (empty($logDir)) {
            $logDir = LOGGING_DIR;
        }
        $this->setLog($logDir);
    }

    public function setLog($logDir = LOGGING_DIR, $logLevel = LOGGING_LEVEL)
    {
        $this->log = \KLogger::instance($logDir, $logLevel);
    }

    private function detectDeviceOld()
    {
        //Detect special conditions devices
        $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
        $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

        //do something with this information
        if ($iPod || $iPhone) {
            //browser reported as an iPhone/iPod touch -- do something here
        } else if ($iPad) {
            //browser reported as an iPad -- do something here
        } else if ($Android) {
            //browser reported as an Android device -- do something here
        } else if ($webOS) {
            //browser reported as a webOS device -- do something here
        }
    }

    private function detectDevice()
    {
        $detect = new \Mobile_Detect;
        $this->deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
    }

    function getDeviceType()
    {
        return $this->deviceType;
    }

    function getSite()
    {
        return $this->site;
    }

    function setSite($site)
    {
        $this->site = $site;
    }

    private function getDefaultSiteData()
    {
        $sql = "SELECT * FROM `siteinfo`";
        $data = $this->database->getAssoc($sql);
        return $data;
    }

    public function getRummages($place = '', $position = '', $route = '', $siteGroup = '')
    {
        if ($siteGroup == '') {
            $siteGroup = $this->site->getSiteGroup();
        }

        $siteGroupString = $this->createSiteGroupString($siteGroup);

        $sql = "SELECT * FROM `listing` WHERE Placement = :place AND Position = :position AND StartDate <= :startDate AND SiteCode IN ( " . $siteGroupString . " )";
        $params = array(':place' => $place, ':position' => $position, ':startDate' => date("Y-m-d") );

        if (!empty($route)) {
            $routeIDS = explode(",", $route);
            $rts = array();
            $c = 1;
            foreach ($routeIDS as $r) {
                $rts['string'][$c] = ':r' . $c;
                $rts['params'][':r' . $c] = $r;
                $c++;
            }
            $route = implode(",", $rts['string']);
            $sql .= " AND ID IN ( " . $route . " )";
            $params = array_merge($params, $rts['params']);
        }

        $results = $this->database->getAssoc($sql, $params);

        $dataArray = array();
        //$dataArray['totalRows'] = $this->database->getCount("SELECT FOUND_ROWS()");

        foreach ($results as $row) {
			if(!empty($row['Street']) && !empty($row['Lat']) && !empty($row['Long'])){
				$dataArray['list'][$row['ID']] = array('adText' => $row['AdText']);
				$dataArray['map'][$row['ID']] = array(
					"street" => $row['Street'],
					"city" => $row['City'],
					"state" => $row['State'],
					"zip" => $row['Zip'],
					"lat" => $row['Lat'],
					"lon" => $row['Long']
				);
			}
        }

        $this->rummages = $dataArray;
        return $this->rummages;
    }

    function setSiteFromSiteCode($siteCode)
    {
        $sql = "SELECT * FROM `siteinfo` where SiteCode = :siteCode";
        $params = array(':siteCode' => $siteCode);
        $data = $this->database->getAssoc($sql, $params);

        // probably serve up a 404
        if (empty($data)) {
            header("Location: ./err/error404.php");
        }

        $this->setSite(new Site($data[0]));
    }

    function setSiteFromDomain($domain = '')
    {
        if (empty($domain)) {
            $domain = $this->getDomain();
        }

        $sql = "SELECT * FROM `siteinfo` where Domain = :domain;";
        $params = array(':domain' => $domain);
        $data = $this->database->getAssoc($sql, $params);

        // probably serve up a 404
        if (empty($data)) {
            header("Location: ./err/error404.php");
        }

        $this->setSite(new Site($data[0]));
    }

    function getDomain()
    {
        if (empty($this->domain)) {
            $this->setDomain();
        }
        return $this->domain;
    }

    function setDomain($host = '')
    {
        if (empty($host)) {
            //$this->host = gethostname();
            $host = $this->getHost();
        }

        $this->host = $host;
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $host, $regs)) {
            $this->domain = $regs['domain'];
        }
    }

    function getCategories()
    {
        return $this->categories;
    }

    private
    function createSiteGroupString($siteGroup)
    {
        $siteArray = explode(',', $siteGroup);
        $siteGroupString = '';
        foreach ($siteArray as $siteCode) {
            if (!empty($siteGroupString)) {
                $siteGroupString .= ', ';
            }
            $siteGroupString .= "'$siteCode'";
        }
        return $siteGroupString;
    }

    function setCategories()
    {
        $siteGroupString = $this->createSiteGroupString($this->getSite()->getSiteGroup());

        $sql = "SELECT * FROM `position` WHERE SiteCode in( $siteGroupString )";
        $params = array();

        $results = $this->database->getAssoc($sql, $params);

        $categoriesArray = array();
        foreach ($results as $row) {
            @$categoriesArray[$row['Placement']][$row['Position']]['count'] += $row['Count'];
			@$categoriesArray[$row['Placement']][$row['Position']]['url'] = $row['ExternalURL'];

        }

        $this->categories = $categoriesArray;
    }

    function getSitemap()
    {
        $siteGroupString = $this->createSiteGroupString($this->getSite()->getSiteGroup());

        $sql = "SELECT LEFT(AdText,50) AS ATEXT, ID, Placement, Position, ExternalURL FROM `listing` WHERE SiteCode in( $siteGroupString )";
        $params = array();

        $results = $this->database->getAssoc($sql, $params);

        $sitemap = array();
        foreach ($results as $row) {
            @$sitemap[$row['Placement']][$row['Position']][$row['ExternalURL']][] = array("id"=>$row['ID'],"adText"=>$row['ATEXT']);
        }

        return $sitemap;
    }

    function getListings($placement = '', $position = '', $page = 1, $siteGroup = '', $fullText = '')
    {
        if ($siteGroup == '') {
            $siteGroup = $this->site->getSiteGroup();
        }
        if (empty($this->listings) && (isset($placement) && isset($position) && isset($siteGroup))) {
            $rowCnt = (defined(LISTINGS_PER_PAGE)) ? LISTINGS_PER_PAGE : 10;
            $offSet = (($page) - 1) * 10;

            $siteGroupString = $this->createSiteGroupString($siteGroup);

            $sql = "SELECT SQL_CALC_FOUND_ROWS *";
            if (!empty($fullText)) {
                $sql .= ", MATCH(adText) AGAINST('$fullText') AS score";
            }

            $sql .= " FROM `listing` where StartDate <= :startDate and siteCode in ( $siteGroupString ) ";
            $params[':startDate'] = date("Y-m-d");

            if (!empty($placement)) {
                $sql .= ' and placement = :placement';
                $params[':placement'] = $placement;
            }
            if (!empty($position)) {
                $sql .= ' and position = :position ';
                $params[':position'] = $position;
            }
            if (!empty($fullText)) {
                $sql .= " and MATCH(adText) AGAINST( :fulltext ) ORDER BY score DESC";
                $params[':fulltext'] = $fullText;
            }

            $sql .= " LIMIT :offSet, :rowCnt";
            $params[':offSet'] = $offSet;
            $params[':rowCnt'] = $rowCnt;
            $results = $this->database->getAssoc($sql, $params);
            $dataArray['totalRows'] = $this->database->getCount("SELECT FOUND_ROWS()");

            foreach ($results as $row) {
                $dataArray['results'][] = array(
                    'id' => $row['ID'],
                    'adText' => $row['AdText'],
                    'siteCode' => $row['SiteCode'],
                    'images' => $row['Images'],
                    'position' => $row['Position'],
                    'placement' => $row['Placement'],
					'externalURL' => $row['ExternalURL']
                );
            }

            $this->listings = $dataArray;
        }

        return $this->listings;
    }

    public function getSingleListing($id)
    {
        $sql = "SELECT ID, AdText, SiteCode, Placement, Position, Images FROM `listing` where ID = :id";
        $params = array(':id' => $id);
        $results = $this->database->getAssoc($sql, $params);

        $retArray['id'] = $results[0]['ID'];
        $retArray['adText'] = $results[0]['AdText'];
        $retArray['siteCode'] = $results[0]['SiteCode'];
        $retArray['placement'] = $results[0]['Placement'];
        $retArray['position'] = $results[0]['Position'];
        $retArray['images'] = $results[0]['Images'];

        return $retArray;
    }

    public static function getHost()
    {
        if ($host = @$_SERVER['HTTP_X_FORWARDED_HOST']) {
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

    function logInfo($logText)
    {
        $this->log->logInfo($logText);
    }

    function getSearch($siteGroup = '')
    {
        $data = "<script>
		window.onload=function(){
			
			$('#searchform')[0].reset();
			$('.advbtn' ).show();
			
			var sitearray = new Array(); 
			
			
			$('.advbtn').click(function() {
				$('#advancedsearch' ).toggle();
			});


			$('#placement').change(function() {
				var tring = $('#placement').val();
				$('#positions' ).hide();
				$('#position').val($('#'+tring).val() );
				
				$('.'+tring).show();
				
			}).change();
		
			$('input:checkbox').change(function(){
	
				var toggle= '';
				var sitecodes ='';
				for ( var i in sitearray ) 
				{
					if(sitearray[i] == this.value)
					{
						toggle = i;
						sitearray[i] = '';
					}
					
						sitecodes = sitecodes + ','+ sitearray[i];
					
				}
				
				if(toggle == '')
				{
					sitearray.push(this.value);
					sitecodes = sitecodes + ','+ this.value
				}
				
				$('#sites').val(sitecodes);
				
			});


		}
		 </script>";

        if ($siteGroup == '') {
            $siteGroup = $this->site->getSiteGroup();
        }

        $siteArray = explode(',', $siteGroup);

        $results = $this->database->prepare("SELECT DISTINCT (Placement) from `position`");
        $results->execute();
        $end = "";
        $data .= "<form action='category.php' method='get' id='searchform' role='form' class='form-horizontal'>";
        $data .= "<select id='placement' name='place' class='form-control'>";
        $data .= "<option >Pick A Category</option>";
        foreach ($results as $row) {
            $data .= "<option value='" . $row['Placement'] . "'>" . $row['Placement'] . "</option>";
            $end .= $this->getSearchSubcats($row['Placement']);
        }
        $data .= "</select>";
        $data .= $end;
        $data .= $this->createSitesTable($this->getSites());
        $data .= "<br /><input type='submit' class='btn btn-primary' value='Search'>";
        $data .= '<input type="hidden" name="posit" id="position">';
        $data .= '<input type="hidden" name="sites" id="sites">';
        $data .= "</form>";
        return $data;
    }

    function getSearchSubcats($placement)
    {
        $results = $this->database->prepare("SELECT DISTINCT Position FROM `position` WHERE `Placement` = :placement");
        $results->execute(array(':placement' => $placement));

        $data = "<div style='display:none;' id='positions' class='" . $placement . "' ><select  id='" . $placement . "' class='form-control'>";
        foreach ($results as $row) {
            $data .= "<option value='" . $row['Position'] . "'>" . $row['Position'] . "</option>";

        }
        $data .= "</select>";
        $data .= "</div>";
        return $data;


    }

    function getSites()
    {
        return $this->database->getAssoc("SELECT * FROM `siteinfo` order by State");
    }

    function createSitesTable($results)
    {
        $state = "";

        $data = "<table class='table'>";
        $data .= "<tr>";
        $x = 1;
        $z = 0;
        $w = 0;
        foreach ($results as $row) {
            if ($x == 4) {
                $data .= "</tr>";
                $data .= "<tr>";
                $x = 1;
            }


            if ($state != $row['State']) {
                if ($z == 1) {
                    $data .= "</td>";
                }
                $z = 1;
                $state = $row['State'];
                $data .= "<td>";
                $data .= "<h4>" . $row['State'] . "</h4>";

            }

            $data .= "<p><label class='checkbox-inline'><input type='checkbox' id='" . $row['SiteCode'] . "' value='" . $row['SiteCode'] . "'> : " . $row['City'] . "</label></p>";

            $x += 1;

        }
        $data .= "</td>";
        $data .= "</tr>";
        $data .= "</table>";

        return $data;
    }


} 