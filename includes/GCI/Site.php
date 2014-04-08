<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 3/15/14
 * Time: 4:23 PM
 */

namespace GCI;


class Site {
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
    private $domain;
    private $siteUrl;
    private $busName;
    private $palette;
    private $siteGroup;
    private $city;
    private $state;
    private $dfp;
    private $dfpm;
	private $gpaper;
	
    public function __construct($data = '') {
        if (isset($data)) {
            $this->setSiteData($data);
        }
    }

    public function setSiteData($data) {
        $this->siteCode = $data['SiteCode'];
        $this->siteName = $data['SiteName'];
        $this->domain = $data['Domain'];
        $this->siteUrl = $data['SiteUrl'];
        $this->busName = $data['BusName'];
        $this->palette = $data['Palette'];
        $this->siteGroup = $data['SiteGroup'];
        $this->city = $data['City'];
        $this->state = $data['State'];
		$this->dfp = $data['DFP'];
		$this->dfpm = $data['DFPmobile'];
		$this->gpaper = $data['Gpaper'];
    }

    public function getSiteCode() {
        return $this->siteCode;
    }

    public function getSiteName() {
        return $this->siteName;
    }

    public function getSiteUrl() {
        return $this->siteUrl;
    }

    public function getBusName() {
        return $this->busName;
    }

    public function getPalette() {
        return $this->palette;
    }

    public function getSiteGroup() {
        return $this->siteGroup;
    }
	
	public function getDFP() {
        return $this->dfp;
    }

    public function getDFPmobile() {
        return $this->dfpm;
    }
	
	public function getGpaper() {
        return $this->gpaper;
    }
	
	
	
} 