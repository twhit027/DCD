
-- --------------------------------------------------------

--
-- Table structure for table `gcas_version`
--

DROP TABLE IF EXISTS `gcas_version`;
CREATE TABLE IF NOT EXISTS `gcas_version` (
  `schema_version` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `listing`
--

DROP TABLE IF EXISTS `listing`;
CREATE TABLE IF NOT EXISTS `listing` (
  `ID` varchar(100) NOT NULL,
  `StartDate` varchar(50) DEFAULT NULL,
  `EndDate` varchar(50) DEFAULT NULL,
  `AdText` text,
  `Images` varchar(200) DEFAULT NULL,
  `Placement` varchar(100) DEFAULT NULL,
  `Position` varchar(100) DEFAULT NULL,
  `SiteCode` varchar(10) DEFAULT NULL,
  `Street` varchar(128) DEFAULT NULL,
  `City` varchar(64) DEFAULT NULL,
  `State` varchar(2) DEFAULT NULL,
  `Zip` varchar(10) DEFAULT NULL,
  `Lat` varchar(32) DEFAULT NULL,
  `Long` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `StartDate` (`StartDate`),
  KEY `EndDate` (`EndDate`),
  KEY `Placement` (`Placement`),
  KEY `Position` (`Position`),
  KEY `SiteCode` (`SiteCode`),
  FULLTEXT KEY `AdText` (`AdText`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `position`
--

DROP TABLE IF EXISTS `position`;
CREATE TABLE IF NOT EXISTS `position` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Position` varchar(100) DEFAULT NULL,
  `Placement` varchar(100) DEFAULT NULL,
  `SiteCode` varchar(10) DEFAULT NULL,
  `Count` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Position` (`Position`),
  KEY `Placement` (`Placement`),
  KEY `SiteCode` (`SiteCode`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;



--
-- Table structure for table `siteinfo`
--

DROP TABLE IF EXISTS `siteinfo`;
CREATE TABLE IF NOT EXISTS `siteinfo` (
  `SiteCode` varchar(45) NOT NULL,
  `SiteName` varchar(100) NOT NULL,
  `Domain` varchar(45) NOT NULL,
  `SiteUrl` varchar(256) NOT NULL,
  `BusName` varchar(200) NOT NULL,
  `Palette` varchar(200) NOT NULL,
  `SiteGroup` varchar(200) NOT NULL,
  `City` varchar(45) NOT NULL,
  `State` varchar(45) NOT NULL,
  `Url` varchar(40) NOT NULL,
  PRIMARY KEY (`SiteCode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

