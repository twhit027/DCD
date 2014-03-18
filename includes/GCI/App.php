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
        if (isset($siteCode)) {
            $this->setSiteFromSiteCode($siteCode);
        } else {
            $this->setSiteFromDomain();
        }
        $this->setCategories();
        $this->log = \KLogger::instance(LOGGING_DIR, LOGGING_LEVEL);
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

    public function getRummages($siteGroup = '')
    {
        if ($siteGroup == '') {
            $siteGroup = $this->site->getSiteGroup();
        }

        $siteGroupString = $this->createSiteGroupString($siteGroup);

        $sql = "SELECT * FROM `listing` where position = :position and siteCode in ( $siteGroupString )";
        $params = array(':position' => 'Rummage Sale');
        $results = $this->database->getAssoc($sql, $params);

        $dataArray = array();
        //$dataArray['totalRows'] = $this->database->getCount("SELECT FOUND_ROWS()");

        foreach ($results as $row) {
            $dataArray['results'][] = array('id' => $row['ID'], 'adText' => $row['AdText']);
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
            $data = $this->getDefaultSiteData();
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
            $data = $this->getDefaultSiteData();
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
            @$categoriesArray[$row['Placement']][$row['Position']] += $row['Count'];
        }

        $this->categories = $categoriesArray;
    }

    function getListings($placement = '', $position = '', $page = 1, $siteGroup = '')
    {
        if ($siteGroup == '') {
            $siteGroup = $this->site->getSiteGroup();
        }
        if (empty($this->listings) && (isset($placement) && isset($position) && isset($siteGroup))) {
            $rowCnt = LISTINGS_PER_PAGE;
            $offSet = (($page) - 1) * 10;

            $siteGroupString = $this->createSiteGroupString($siteGroup);

            $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `listing` where placement = :placement and position = :position and siteCode in ( $siteGroupString ) LIMIT :offSet, :rowCnt";
            $params = array(':placement' => $placement, ':position' => $position, ':offSet' => $offSet, ':rowCnt' => $rowCnt);
            $results = $this->database->getAssoc($sql, $params);
            $dataArray['totalRows'] = $this->database->getCount("SELECT FOUND_ROWS()");

            foreach ($results as $row) {
                $dataArray['results'][] = array('id' => $row['ID'], 'adText' => $row['AdText']);
            }

            $this->listings = $dataArray;
        }

        return $this->listings;
    }

    public
    function getSingleListing($id)
    {
        $sql = "SELECT ID, AdText FROM `listing` where ID = :id";
        $params = array(':id' => $id);
        $results = $this->database->getAssoc($sql, $params);

        $retArray['id'] = $results[0]['ID'];
        $retArray['adText'] = $results[0]['AdText'];

        return $retArray;
    }

    function getHost()
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
} 