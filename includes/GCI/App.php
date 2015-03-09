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

    function __construct($siteCode = '', $logDir = LOGGING_DIR, $logLevel = LOGGING_LEVEL)
    {
        $this->database = new Database($logDir, $logLevel);

        $this->detectDevice();

        if (empty($siteCode)) {
            $this->setSiteFromDomain();
        } else {
            $this->setSiteFromSiteCode($siteCode);
        }
        $this->setCategories();
        $this->setLog($logDir, $logLevel);
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

    public function getRummages($place = '', $position = '', $route = '', $siteGroup = '', $city = '', $siteCode = '', $day ='')
    {
        if ($siteGroup == '') {
            $siteGroup = $this->site->getSiteGroup();
        }

        $siteGroupString = $this->createSiteGroupString($siteGroup);

        //$sql = "SELECT t1.*, t2.BusName FROM `listing` AS t1, `siteinfo` AS t2, `day` AS t3 WHERE Placement = :place AND Position = :position AND StartDate <= :startDate AND t1.SiteCode IN ( " . $siteGroupString . " ) AND t2.SiteCode = t1.SiteCode AND t1.ID = t3.ListingId";
        $sql = "SELECT t1.*, t2.BusName, t2.Domain, t3.DayOfWeek, t3.StartTime, t3.EndTime FROM `listing` AS t1 JOIN `siteinfo` AS t2 on t1.SiteCode = t2.SiteCode LEFT JOIN `day` AS t3 on t1.ID = t3.ListingId WHERE t1.Placement = :place AND t1.Position = :position AND t1.StartDate <= :startDate AND t1.SiteCode IN ( " . $siteGroupString . " )";
        $params = array(':place' => $place, ':position' => $position, ':startDate' => date("Y-m-d"));

        if (!empty($city)) {
            $sql .= " AND t1.City = :city";
            $params = array_merge($params, array(':city' => $city));
        }

        if (!empty($siteCode)) {
            $sql .= " AND t1.SiteCode = :siteCode";
            $params = array_merge($params, array(':siteCode' => $siteCode));
        }

        if (!empty($day)) {
            $sql .= " AND t3.DayOfWeek = :day";
            $params = array_merge($params, array(':day' => $day));
        }

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
            $sql .= " AND t1.ID IN ( " . $route . " )";
            $params = array_merge($params, $rts['params']);
        }

        //Move iteration 16 fix to come after the route part of the sql string
        $sql .= " ORDER BY t1.AdText";

        $results = $this->database->getAssoc($sql, $params);

        $dataArray = array();
        //$dataArray['totalRows'] = $this->database->getCount("SELECT FOUND_ROWS()");

        foreach ($results as $row) {
            if (isset($dataArray['list'][$row['ID']]) && !empty($row['DayOfWeek'])) {
                $dataArray['list'][$row['ID']]['days'][] = array(
                    'dayOfWeek' => trim($row['DayOfWeek']),
                    'startTime' => trim($row['StartTime']),
                    'endTime' =>  trim($row['EndTime'])
                );
            } else {
                $dataArray['list'][$row['ID']] = array(
                    'id' => $row['ID'],
                    'adText' => $row['AdText'],
                    'images' => $row['Images'],
                    'siteCode' => $row['SiteCode'],
                    'siteName' => $row['BusName'],
                    'domain' => $row['Domain'],
                    'city' => trim($row['City']),
                    'proptype' => $row['PropType'],
                    'rent' => $row['Rent'],
                    'bdrooms' => $row['BedRooms'],
                    'bthrooms' => $row['BathRooms'],
                    'email' => $row['Email'],
                    'street' => $row['Street']
                );
                if (!empty($row['DayOfWeek'])) {
                    $dataArray['list'][$row['ID']]['days'][] = array(
                        'dayOfWeek' => trim($row['DayOfWeek']),
                        'startTime' => trim($row['StartTime']),
                        'endTime' =>  trim($row['EndTime'])
                    );
                }
                if (!empty($row['Street']) && !empty($row['Lat']) && !empty($row['Long'])) {
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
        }

        $this->rummages = $dataArray;
        return $this->rummages;
    }

    function setSiteFromSiteCode($siteCode)
    {
        $data = $this->getSiteFromSiteCode($siteCode);

        // probably serve up a 404
        if (empty($data)) {
            header("Location: ./err/error404.php");
        }

        $this->setSite(new Site($data[0]));
    }

    function getSiteFromSiteCode($siteCode)
    {
        $sql = "SELECT * FROM `siteinfo` where SiteCode = :siteCode";
        $params = array(':siteCode' => $siteCode);
        return $this->database->getAssoc($sql, $params);
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
            $siteCode = trim($siteCode);
            $siteGroupString .= "'$siteCode'";
        }
        return $siteGroupString;
    }

    function setCategories()
    {
        $siteGroupString = $this->createSiteGroupString($this->getSite()->getSiteGroup());
        $siteCode = $this->getSite()->getSiteCode();

        $sql = "SELECT * FROM `position` WHERE SiteCode in( $siteGroupString )";
        $params = array();

        $results = $this->database->getAssoc($sql, $params);

        $categoriesArray = array();
        foreach ($results as $row) {
            if ((!empty($row['ExternalURL'])) && ($row['ExternalURL'] != 1)) {
                if ($row['SiteCode'] == $siteCode) {
                    @$categoriesArray[$row['Placement']][$row['Position']]['count'] += $row['Count'];
                    @$categoriesArray[$row['Placement']][$row['Position']]['url'] = $row['ExternalURL'];
                }
            } else {
                @$categoriesArray[$row['Placement']][$row['Position']]['count'] += $row['Count'];
                if (!isset($categoriesArray[$row['Placement']][$row['Position']]['url']) || ($row['ExternalURL'] == 1)) {
                    @$categoriesArray[$row['Placement']][$row['Position']]['url'] = $row['ExternalURL'];
                }
            }
        }

        $this->categories = $categoriesArray;
    }

    function getSitemap()
    {
        $siteGroupString = $this->createSiteGroupString($this->getSite()->getSiteGroup());

        $sql = "SELECT LEFT(AdText,50) AS ATEXT, ID, Placement, Position, ExternalURL FROM `listing` WHERE StartDate <= :startDate and EndDate >= :endDate and SiteCode in( $siteGroupString )";
        $params[':startDate'] = $params[':endDate'] = date("Y-m-d");

        $results = $this->database->getAssoc($sql, $params);

        $sitemap = array();
        foreach ($results as $row) {
            @$sitemap[$row['Placement']][$row['Position']][$row['ExternalURL']][] = array("id" => $row['ID'], "adText" => $row['ATEXT']);
        }

        return $sitemap;
    }

    function getListings($placement = '', $position = '', $page = 1, $siteGroup = '', $fullText = '', $radius = '')
    {
        $siteGroup = trim($siteGroup);
        if ($siteGroup == '') {
            $siteGroup = $this->site->getSiteGroup();
        } elseif (strtolower($siteGroup) == 'all') {
            $siteGroup = '';
        }

        if (!empty($radius)) {
            $siteGroup = '';
            $orgLat = $this->site->getLat();
            $orgLng = $this->site->getLng();
            $preSql = 'SELECT SiteCode, ( 3959 * acos( cos( radians(' . $orgLat . ') ) * cos( radians( Lat ) ) * cos( radians( Lng ) - radians(' . $orgLng . ') ) + sin( radians(' . $orgLat . ') ) * sin( radians( lat ) ) ) ) AS distance FROM `siteinfo` HAVING distance < ' . $radius . ' ORDER BY distance';
            $preResults = $this->database->getAssoc($preSql);
            if ($preResults !== false) {
                foreach ($preResults as $row) {
                    if (!empty($siteGroup)) {
                        $siteGroup .= ',';
                    }
                    $siteGroup .= $row['SiteCode'];
                }
            }
            $radius = '';
        }

        if (empty($this->listings) && (isset($placement) && isset($position) && isset($siteGroup))) {
            $rowCnt = (defined(LISTINGS_PER_PAGE)) ? LISTINGS_PER_PAGE : 10;
            $offSet = (($page) - 1) * 10;

            $siteGroupString = '';

            if (!empty($siteGroup)) {
                $siteGroupString = $this->createSiteGroupString($siteGroup);
            }

            $preSQL1 = "SELECT SQL_CALC_FOUND_ROWS l.*, s.BusName, s.Domain";
            $preSQL2 = "SELECT DISTINCT l.SiteCode, s.BusName";
            $sql = '';
            if (!empty($radius)) {
                $orgLat = $this->site->getLat();
                $orgLng = $this->site->getLng();
                //SELECT SiteCode, SiteName, City, State, ( 3959 * acos( cos( radians(39.1031182) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(-84.5120196) ) + sin( radians(39.1031182) ) * sin( radians( lat ) ) ) ) AS distance FROM siteInfo HAVING distance < 250 ORDER BY distance LIMIT 0, 200;
                $preSQL1 .= ', ( 3959 * acos( cos( radians(' . $orgLat . ') ) * cos( radians( s.lat ) ) * cos( radians( s.lng ) - radians(' . $orgLng . ') ) + sin( radians(' . $orgLat . ') ) * sin( radians( s.lat ) ) ) ) AS distance';
            }

            if (!empty($fullText)) {
                $preSQL1 .= ", MATCH(AdText) AGAINST( :fulltext1 ) AS score";
                $params[':fulltext1'] = $fullText;
            }

            $sql .= ' FROM `listing` l, `siteinfo` s where l.SiteCode = s.SiteCode AND l.StartDate <= :startDate';
            $params[':startDate'] = date("Y-m-d");

            if (empty($siteGroupString)) {
                $sql .= " and l.Position not in ( 'Apartments' )";
            } else {
                $sql .= " and l.SiteCode in ( $siteGroupString )";
            }

            if (!empty($placement)) {
                $sql .= ' and l.Placement = :placement';
                $params[':placement'] = $placement;
            }
            if (!empty($position)) {
                $sql .= ' and l.Position = :position ';
                $params[':position'] = $position;
            }

            if (!empty($radius)) {
                $sql .= " HAVING distance < $radius";
            }

            if (!empty($fullText)) {
                $sql .= " and MATCH(AdText) AGAINST( :fulltext )";
                $params[':fulltext'] = $fullText;
            }

            if ((!empty($siteGroup)) && (count(explode(',', $siteGroup)) > 1)) {
                $sql2 = $preSQL2 . $sql;
                $params2 = $params;
                unset($params2[':fulltext1']);
                $results2 = $this->database->getAssoc($sql2, $params2);
                foreach ($results2 as $row2) {
                    $dataArray['sites'][] = array(
                        'siteCode' => $row2['SiteCode'],
                        'busName' => $row2['BusName']
                    );
                }
            }

            if (empty($fullText)) {
                $sql .= ' ORDER BY l.AdText';
            } else {
                $sql .= ' ORDER BY score DESC';
            }
            $sql .= " LIMIT :offSet, :rowCnt";
            $params[':offSet'] = $offSet;
            $params[':rowCnt'] = $rowCnt;
            $sql1 = $preSQL1 . $sql;
            $results = $this->database->getAssoc($sql1, $params);

            $dataArray['totalRows'] = $this->database->getCount("SELECT FOUND_ROWS()");

            foreach ($results as $row) {
                $dataArray['results'][] = array(
                    'id' => $row['ID'],
                    'adText' => $row['AdText'],
                    'siteCode' => $row['SiteCode'],
                    'images' => $row['Images'],
                    'position' => $row['Position'],
                    'placement' => $row['Placement'],
                    'externalURL' => $row['ExternalURL'],
                    'moreInfo' => $row['MoreInfo'],
                    'busName' => $row['BusName'],
                    'domain' => $row['Domain']
                );
            }

            $this->listings = $dataArray;
        }

        return $this->listings;
    }

    public function getSingleListing($id)
    {
        $sql = "SELECT ID, AdText, SiteCode, Placement, Position, Images, Street, City, State FROM `listing` where ID = :id";
        $params = array(':id' => $id);
        $results = $this->database->getAssoc($sql, $params);

        $retArray['id'] = $results[0]['ID'];
        $retArray['adText'] = $results[0]['AdText'];
        $retArray['siteCode'] = $results[0]['SiteCode'];
        $retArray['placement'] = $results[0]['Placement'];
        $retArray['position'] = $results[0]['Position'];
        $retArray['images'] = $results[0]['Images'];
        $retArray['street'] = $results[0]['Street'];
        $retArray['state'] = $results[0]['State'];
        $retArray['city'] = $results[0]['City'];

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
        $host = strtolower(preg_replace('/:\d+$/', '', $host));
        return trim($host);
    }
    
    public static function getHost2()
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $elements = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
            $host = trim(end($elements));
        } elseif (!empty($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } elseif (!empty($_SERVER['SERVER_NAME'])) {
            $host = $_SERVER['SERVER_NAME'];
        } elseif (!empty($_SERVER['SERVER_ADDR'])) {
            $host = $_SERVER['SERVER_ADDR'];
        } else {
            $host = '';
        }

        // Remove port number from host
        $host = strtolower(preg_replace('/:\d+$/', '', $host));
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

    function report($startDate = '')
    {
        $siteGroupString = $this->createSiteGroupString($this->getSite()->getSiteGroup());
        $sql = "SELECT * FROM `listing` WHERE `SiteCode` in ( $siteGroupString )";

        $params = array();

        if (!empty($startDate)) {
            $sql .= " AND StartDate <= :startDate";
            $params[':startDate'] = date("Y-m-d");
        }

        return $this->database->getAssoc($sql, $params);
    }

    // these functions will only work on the feed side
    public function setTopLinks($siteCode, $jsonString)
    {
        try {
            $stmt = $this->database->prepare("UPDATE `siteinfo` SET TopLinks = :jsonString where SiteCode = :siteCode");
            $stmt->execute(array(':jsonString' => $jsonString, ':siteCode' => $siteCode));
            echo "ErrorCode: " . $this->database->errorCode();
            echo "ErrorInfo: " . $this->database->errorInfo();
        } catch (\PDOException $e) {
            $logText = "Message:(" . $e->getMessage() . ") attempting to insert topLinks into the siteInfo table";
            $this->log->logError($logText);
            fwrite(STDERR, $logText . "\n");
        }
    }

    public function setBottomLinks($siteCode, $jsonString)
    {
        try {
            $stmt = $this->database->prepare("UPDATE `siteinfo` SET BottomLinks = :jsonString where SiteCode = :siteCode");
            $stmt->execute(array(':jsonString' => $jsonString, ':siteCode' => $siteCode));
            echo "ErrorCode: " . $this->database->errorCode();
            echo "ErrorInfo: " . $this->database->errorInfo();
        } catch (\PDOException $e) {
            $logText = "Message:(" . $e->getMessage() . ") attempting to insert BottomLinks into the siteInfo table";
            $this->log->logError($logText);
        }
    }

    function getAllSite()
    {
        $sql = "SELECT * FROM `siteinfo`";
        $data = $this->database->getAssoc($sql);

        // probably serve up a 404
        if (empty($data)) {
            header("Location: ./err/error404.php");
        }

        return $data;
    }
}