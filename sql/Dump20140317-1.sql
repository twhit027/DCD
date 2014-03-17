-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Host: 10.189.18.101
-- Generation Time: Mar 17, 2014 at 10:24 AM
-- Server version: 5.5.29
-- PHP Version: 5.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `lamp_louisvil`
--

-- --------------------------------------------------------

--
-- Table structure for table `listing`
--

DROP TABLE IF EXISTS `listing`;
CREATE TABLE `listing` (
  `ID` varchar(100) NOT NULL,
  `StartDate` varchar(50) DEFAULT NULL,
  `EndDate` varchar(50) DEFAULT NULL,
  `AdText` text,
  `Images` varchar(200) DEFAULT NULL,
  `Placement` varchar(100) DEFAULT NULL,
  `Position` varchar(100) DEFAULT NULL,
  `SiteCode` varchar(10) DEFAULT NULL,
  `Street` varchar(128) NOT NULL,
  `City` varchar(64) NOT NULL,
  `State` varchar(2) NOT NULL,
  `Zip` varchar(10) NOT NULL,
  `Lat` varchar(32) NOT NULL,
  `Long` varchar(32) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `listing`
--

INSERT INTO `listing` (`ID`, `StartDate`, `EndDate`, `AdText`, `Images`, `Placement`, `Position`, `SiteCode`, `Street`, `City`, `State`, `Zip`, `Lat`, `Long`) VALUES
('3564382', '2014-03-03', '2014-03-23', '\n    NOTICE OF SALE SU- PREME COURT COUNTY OF DUTCHESS JPMor- gan Chase Bank, NA, Plaintiff, against Nan- cey E. Walker; Marc I. Platt; et al., De- fendant(s). Pursuant to a Judgment of Foreclo- sure and Sale duly dat- ed January 10, 2014 I, the undersigned Refer- ee will sell at public auction  in the Dutchess County Courthouse, 10 Market Street, Pough- keepsie, New York on March 31, 2014 at 9:30AM, premises known as 28 Regency Drive, Poughkeepsie, NY 12603. All that cer- tain plot piece or parcel of land, with the build- ings and improvements erected, situate, lying and being in the Town of Poughkeepsie, Coun- ty of Dutchess and State of NY, Section 6262 Block 02 Lot 911804.  Approximate amount of judgment $426,957.01 plus inter- est and costs.  Premises will be sold subject to provisions of filed Judgment Index# 2010- 397. David D. Hagstrom, Esq., Referee Shapiro, DiCaro & Barak, LLC At- torney(s) for the Plain- tiff 250 Mile Crossing Boulevard, Suite One, Rochester, NY 14624 (877) 759-1835 Dated: February 25, 2014 1084379 3/3, 3/10, 3/17, 03/24/2014                                     4382\n  ', NULL, 'General', '2000', 'POU', '', '', '', '', '', ''),
('3561969', '2014-02-24', '2014-03-16', 'n  in the Dut- chess County Court- house, 10 Market Street, Poughkeepsie, New York on March 24, 2014 at 10:00AM, prem- ises known as 296 Ket- chamtown Road, Wap- pingers Falls, NY 12590. All that certain plot piece or parcel of land, with the buildings and improvements erected, situate, lying and being in the Town of Wap- pinger, County of Dut- chess and State of NY, Section: 6156 Block: 02 Lot: 570840. Approximate amount of judgment $249,232.43 plus interest and costs. Premises will be sold subject to provisions of filed Judgment Index# 2013-515. Samuel P. Brooke, Esq., Referee Shapiro, DiCaro & Bar- ak, LLC Attorney(s) for the Plaintiff 250 Mile Crossing Boulevard, Suite One, Rochester, NY 14624 (877) 759-1835 Dated: February 12, 2014 1082516 2/24, 3/3, 3/10, 03/17/2014                                     1969\n  ', NULL, 'General', '2000', 'POU', '', '', '', '', '', ''),
('3562154', '2014-02-24', '2014-03-16', '\n    NOTICE OF SALE SU- PREME COURT COUNTY OF DUTCHESS JPMor- gan Chase Bank, Na- tional Association, Plaintiff, against Philip J. Mallozzi a/k/a Philip Mallozzi; Susan Malloz- zi; et al., Defendant(s). Pursuant to a Judgment of Foreclosure and Sale duly dated December 17, 2013 I, the under- signed Referee will sell at public auction  at the Dutchess County Court- house, rear vestibule, 10 Market Street, Poughkeepsie, New York on March 24, 2014 at 9:30AM, premises known as 70 Van Wyck Lake Road, Fishkill, NY 12524. All that certain plot piece or parcel of land, with the buildings and improvements erected, situate, lying and being in the Town of Fishkill, County of Dutchess and State of NY, Section 6255 Block 00 Lot 311431. Approximate amount of judgment $331,136.22 plus interest and costs. Premises will be sold subject to provisions of filed Judgment Index# 2012-4467. Sharon M. Faulkner, Esq., Referee Shapiro, DiCaro & Bar- ak, LLC Attorney(s) for the Plaintiff 250 Mile Crossing Boulevard, Suite One, Rochester, NY 14624 (877) 759-1835 Dated: February 14, 2014 1083079 2/24, 3/3, 3/10, 03/17/2014                                     2154\n  ', NULL, 'General', '2000', 'POU', '', '', '', '', '', ''),
('3561973', '2014-02-24', '2014-03-16', '\n    NOTICE OF SALE  SUPREME COURT: DUT- CHESS COUNTY  RBS Citizens, N.A.; Plaintiff(s)  vs. MICHAEL G THOMP- SON; et al; Defendant(s)  Attorney (s) for Plaintiff (s): ROSICKI, ROSICKI & ASSOCIATES, P.C., 2 Summit Court, Suite 301, Fishkill, New York, 12524, 845.897.1600  Pursuant to judgment of foreclosure and sale granted herein on or about May 1, 2013, I will sell at Public Auction to the highest bidder at the Supreme Court (Du- tchess) 10 Market Street, Poughkeepsie, NY.  On March 25, 2014 at 10:00 AM  Premises known as 2 South Avenue, Pleasant Valley, NY 12569  Section: 6363 Block: 04 Lot: 849399  ALL that certain plot, piece or parcel of land, with the buildings and improvements thereon erected, situate, lying and being in the Town of Pleasant Valley, Dut- chess County, New Yor- k.  As more particularly de- scribed in the judgment of foreclosure and sale.  Sold subject to all of the terms and conditions contained in said judgment and terms of sale.  Approximate amount of judgment $109,702.37 plus interest and costs.  INDEX NO. 2012-3750  Patricia A. Cocchia, Esq., REFEREE                                     1973\n  ', NULL, 'General', '2000', 'POU', '', '', '', '', '', ''),
('3563450', '2014-03-03', '2014-03-18', 'sh;&mdash;&mdash;&mdash;&mdash;&mdash;X  CLAPP HILL, LLC,    Plaintiff,    -against-    RAYMOND PATIERNO; ANTHONY VALENTINO;  LARRY THEW, Individu- ally; JOHN THEW, Indi- vidually;  LARRY THEW and JOHN THEW, as Trustees of the  GERALD THEW FAMILY TRUST; LARRY THEW and  JOHN THEW, as Trust- ees of the R.W. THEW TRUST;  PEOPLE OF THE STATE OF NEW YORK by New York  State Tax Commission; UNITED STATES OF AMERICA, by  and through the Com- missioner of the Inter- nal Revenue Service;  JOHN DOE and JANE DOE being fictitious names, it being  the intention of Plaintiff to designate any and all persona or  parties in possession of or having an interest in the premisesIndex #: 2011-3719  other than named De- fendants,   Defendants.  &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;- &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;- &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;X  In pursuance and by vir- tue of a Judgment of Foreclosure and Sale dated November 4, 2013, duly granted by this Court and entered in the Dutchess County Clerk''s Office on the 12th day of December 2013 and pursuant to a Stipulation and Order of Substitution of Plaintiff and for Judgment of Foreclosure dated No- vember 4, 2013 and en- tered in the Dutchess County Clerk''s Office on December 12, 2013I, the undersigned referee, duly appointed in this action for such purpose, will offer for sale and sell at public auction to the highest bidder, at the Dutchess County Courthouse, 1st Floor, rear vestibule, 10 Mar- ket Street, Poughkeep- sie, New York 12601 on the 26th day of March 2014 at 10:30 o''clock in the forenoon of that day, the mortgaged premises directed in and by said judgment to be sold and in said judgment described as follows:   ALL that certain plot, piece or parcel of land identified as follows:  in the Town of Beekman, New York, a parcel to- taling approximately 128.1 acres bearing Grid Nos. 6559-00-018292; in the Town of Beekman and the Town of La- Grange, New York, two parcels of approxi- mately 47.43 acres and approximately 7.94 acres conveyed by deed on or about October 6, 2006 and recorded in the Dutchess County Clerk''s Office in deed book 22006 at page 8451; in the Town of Beekman, New York, a parcel totaling approxi- mately 8.5 acres bear- ing Grid No. 6559-00- 988522-00; in the Town of LaGrange, New York a parcel totaling ap- proximately 2.2 acres bearing Grid No. 6559- 04-597475; in the Town of LaGrange, New York a parcel totaling ap- proximately .58 acres bearing Grid No. 6559- 04-544442; and in the Town of Beekman, New York a parcel of about 6.9 acres bearing Grid No. 6559-00-993170, all of which properties are more particularly de- scribed in the Exhibit &ldquo;1&ldquo;  attached to the afo- resaid judgment of fore- closure on file in the Of- fice of the Clerk of the County of Dutchess.   Lee David Klein, Esq., Referee  11 Market Street  Poughkeepsie, NY 12601  (845) 454-9200   Locations of Property to be foreclosed:  Town of Beekman and Town of LaGrange, both Towns being in the County of Dutchess, State of New York, as more fully de- scribed above   DATED:  February 24, 2014                                 3450\n  ', NULL, 'General', '2000', 'POU', '', '', '', '', '', ''),
('3560944', '2014-03-03', '2014-03-18', '\n    REFEREE''S NOTICE OF SALE IN FORECLOSURE   STATE OF NEW YORK  SUPREME COURT- COUNTY OF DUTCHESS  HSBC BANK USA, N.A., Plaintiff against JOHN SPADAFORA, et al. De- fendants. Pursuant to a judgment of foreclosure and sale executed by the Honorable Christine A. Sproat, J.S.C., on No- vember 1, 2013, and filed with the Dutchess County Clerk on No- vember 8, 2013, I, the undersigned Referee will sell at public auc- tion at the Dutchess County Courthouse, 1st Floor rear vestibule, 10 Market Street, in the City of Poughkeepsie, Dutchess County, New York on 3/28/14 at 10:00 a.m. Premises common- ly known as 611 Route 376, East Fishkill, NY 12533. ALL that tract or parcel of land, situate and being in the Town of East Fishkill, County of Dutchess and State of New York. Section: 6457 Block: 01 Lot: 64936.  Approximate amount of lien $263,302.93 plus inter- est and costs. Premises will be sold subject to provisions of filed Judgment Index #2013- 259.   Dated:  February 10, 2014  THEONI STAMOS- SALOTTO, Esq., Referee    Jonathan D. Pincus, At- torney at Law  Attorney for Plaintiff  1387 Fairport Road, Bldg. 700, Suite 710  Fairport, NY  14450  Telephone: 585-256- 8000                                     0944\n  ', NULL, 'General', '2000', 'POU', '', '', '', '', '', ''),
('3562148', '2014-02-19', '2014-04-12', '\n    MILLBROOK Condo 2br,2ba,gar 53 Bennett Com, Exc Con- d-Nice View PriceTo- Sell 175,000 Lsnyd@o- ptonline.net 631-793-2092\n  ', NULL, 'General', '3120', 'POU', '', '', '', '', '', ''),
('3562012', '2014-02-14', '2014-03-14', '\n    WAPPINGERS FALLS Modern Mobile home, 3BR, Washer/ Dryer, Deck. Behind Galleria. Small Dogs allowed. New Roof & Carpeting. Lot Rent: $535/mo. $19,950. Owner financ- ing. 845-266-8261\n  ', NULL, 'General', '3123', 'POU', '', '', '', '', '', ''),
('3562219', '2014-02-19', '2014-03-15', '\n    LaGrange Professional Building: Rt 55 & Bush- wick Rd. 1200 sq ft, for- mer DR office with exam & reception area. Ample parking, $1700/mo. + Utils. Call Jeff 914-204-2282 Call Bill at 845-795-1990\n  ', NULL, 'General', '3131', 'POU', '', '', '', '', '', ''),
('3564690', '2014-03-01', '2014-03-28', '\n    BEACON 3BR, Or four, option fur- nished, 1.5BA, LR, fam. rm,, dishwasher, wash- er / dryer, deck,  patio, walk to train+ stores. $1600 + utils. or option to buy neg. Parking. 70 mins NYC. 845-440-3345\n  ', NULL, 'General', '3183', 'POU', '', '', '', '', '', ''),
('3564409', '2014-02-28', '2014-03-14', '\n    POK/C 2BR, 1BA House, enclosed porch, yard, basement. No smoking, $1300/mo. + Utilities & Security. 518-421-1462\n  ', NULL, 'General', '3183', 'POU', '', '', '', '', '', ''),
('3561864', '2014-02-13', '2014-03-14', 'll (845)795-5400.\n  ', NULL, 'General', '3206', 'POU', '', '', '', '', '', ''),
('3562120', '2014-02-15', '2014-03-14', '\n    DOVER PLAINS 14x70 3BR, pvt property, $850/month + utilities. Security required. No pets/smoking. Call (845) 325-3828\n  ', NULL, 'General', '3210', 'POU', '', '', '', '', '', ''),
('3563059', '2014-02-20', '2014-03-21', '\n    E. Fishkill/Hopewell 1BR apt, $875/month. includes Heat & Elec. near IBM and shop- ping. Extra Storage. Sec & Refs required. No pets or smoking. 845-635-3375\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3562314', '2014-02-20', '2014-03-21', '\n    HYDE PARK Nice Studio Apt. $650. FREE UTILITIES! No pets.  1 mo sec. Background check.  845-849-5102\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3564616', '2014-03-01', '2014-03-28', '\n    HYDE PARK Spring Sale! FIRST MONTH FREE WITH ONE YEAR LEASE. 1BR apt, $900 includes all utilities. 1.5 month security. 845-225-0875\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3564621', '2014-03-01', '2014-03-28', '\n    HYDE PARK Spring Sale! FIRST MONTH FREE WITH ONE YEAR LEASE. 2BR apt, $1075 includes all utilities. 1.5 month security. 845-225-0875\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3562350', '2014-02-26', '2014-03-22', '\n    PINE PLAINS 2BR. Lake view. $895/ month, Heat & Hot Water Included. 1 month rent + security required. Available immediately. Call (914)664-7559\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3559575', '2014-02-01', '2014-04-04', '\n    PLEASANT VALLEY: Brookside Meadows 1, 2 & 3 Bedroom Luxury Apartments. New Construction  Town Homes and Apartments available Now! Call (845) 635-2600 brooksidemeadows.com\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3563421', '2014-02-26', '2014-03-22', '\n    POK/C 2BR apt., 162 Parker Ave. $1100/mo. includes Ht/HW. Big backyard. Off-st Parking. No pets 1.5 mos. Sec req''d. 845-473-2644\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3564303', '2014-02-27', '2014-03-28', '\n    POK/C Smith St. near Morgan Lake. Newly renovated 3BR apt., in 2 Family house. $1000/mo. + Utils. off-st-pkng.  Private backyard. Call 914-489-9822\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3563534', '2014-03-01', '2014-03-30', '\n    POK KAAL ROCK Manor Apartments Luxury Studio $690; 1BR''s starting at $865; 2 BR starting at $925; 2BR Townhouses starting at $1250; 3BR  Townhouses starting at $1420. 1 year lease avail- able. Includes: Heat, hot water, wall to wall carpet, Air con- ditioning, dishwash- er,  Kenmore appli- ances. Night Securi- ty on staff. No pets. Country setting, some apartments have  river view. A warm & friendly  atmosphere. Tennis court, pool, all  private entrances. Commuter bus & Metro North Rail- road within walking distance. 845-471-1890\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3563533', '2014-02-26', '2014-03-28', '\n    POK/T: Bright & clean 2BR 1st floor apt. private car port, washer & dryer, deck,  $1100/month + utils. Avail. 3/1/2014. (845)440-6691, lve msg\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3563543', '2014-02-27', '2014-03-14', '\n    POK/T Cherry Hill Large 1BR, 2 large walk in closets, deck, Ht/HW included. $950/mo.  1 mo. sec. No pets. 845-981-9003\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3561993', '2014-02-14', '2014-03-30', '\n    POK/T Cherry Hill West Newly renovated 2BR Duplexes. Washer/ Dryer Hookups. $1250/mo. Call 845-485-4412\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3563334', '2014-02-22', '2014-03-30', '\n    POK/T: Lge 1BR, dish- washer, EiK, patio. $800 + utils. 845-463-1536\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3560901', '2014-02-08', '2014-04-04', '\n    Pok/Town  Dutchess Apts  Arlington area, Luxury 1BR & 2BR, Ht/HW incl. $1020-$1200/mo. A/C, Pool, Appliances  845 454-4878 Hours: M-F, 9am-8pm Sat & Sun 10am-3pm  www.hubrealty.com\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3560900', '2014-02-08', '2014-04-04', '\n    Pok/Town Ridgefield Apts  Luxury1BR and 2BR . $995-$1275/mo. Ht&HW included  New Kitchen & Bath w/Granite. 845 454-2290 Hours: M-F, 9am-8pm Sat & Sun, 10am-3pm  www.hubrealty.com\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3561994', '2014-02-14', '2014-03-14', '\n    Rhinebeck/Clinton One mile TSP, five miles Rhinebeck, 1BR, suit- able for one, incl Ht/HW & Elec. No smoking / pets. $750 / month. 845-266-4387\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3564687', '2014-03-01', '2014-03-28', '\n    STANFORDVILLE 1BR + office located on 8 acres. Beautiful country setting, very private. No dogs/smoking. $795/month+ utils. (845) 876-1151\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3562013', '2014-02-14', '2014-03-14', '\n    WAPPINGERS FALLS Modern Mobile home, 3BR, Washer/Dryer, behind Galleria. No Dogs. $1035/mo. (845)266-8261\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3564624', '2014-03-01', '2014-03-14', '\n    WAPPINGERS Studio, $695/mo. HT/HW incl''d. Freshly Painted, New carpet & New Appliances. Off-st Pkng. Some pets ok. Call 845-463-2614\n  ', NULL, 'General', '3218', 'POU', '', '', '', '', '', ''),
('3563314', '2014-02-22', '2014-03-26', '\n    Highland-Duplex  -Newly renovated 2BR, + sm office, lg yard, covered deck off kit.No pets, Smoke & drug free, 3 mi Bridge & Thruway. Ref''s,credit chk, 1st, last + 1mo. sec req''d. $1500 incl. Ht/HW. 845-691-2021.\n  ', NULL, 'General', '3307', 'POU', '', '', '', '', '', ''),
('3563294', '2014-02-22', '2014-03-21', '\n    HIGHLAND  Large 1BR, 1st floor end unit. Parking next to unit. On-site laundry. Private/Quiet neigh- borhood. Next to Lloyd Town Hall. Near Rt 9W. Mins. to Pok bridge, Metro North, Rt 9 & hospitals. $925/mo. Ht/HW included. 1 mo. security. 845-391-3747\n  ', NULL, 'General', '3307', 'POU', '', '', '', '', '', ''),
('3562360', '2014-02-20', '2014-03-30', '\n    BEACON: Nice area, close to downtown, I- 84 & train station, Pri- vate parking. Free ca- ble.  845-831-8360\n  ', NULL, 'General', '3330', 'POU', '', '', '', '', '', ''),
('3564688', '2014-03-01', '2014-03-28', '\n    MILAN/RED HOOK: Rooms w/pvt BA. Utils incl. Free WiFi. No smoking / pets. Close to TSP. Located in Milan near Red Hook, Rhinebeck, Pine Plains. Starting at $150/week.  For appt, (845)876-1151\n  ', NULL, 'General', '3330', 'POU', '', '', '', '', '', ''),
('3562270', '2014-02-19', '2014-03-15', '\n    POK- Rooms avail now! $150/week including  Utilities and Cable. Share Kitchen/Bath. No smoking/pets. Two Weeks Security Required. Call 845-235-5360\n  ', NULL, 'General', '3330', 'POU', '', '', '', '', '', ''),
('3563279', '2014-02-23', '2014-03-30', '\n    Wappingers StoneGate Nice furnished rooms. Rte 9. Private bath, internet, TV, refrigera- tor. 845-297-3149 or 914-474-5176\n  ', NULL, 'General', '3330', 'POU', '', '', '', '', '', ''),
('3564566', '2014-02-28', '2014-03-30', '\n    WE BUY JUNK CARS  Used parts & tires sold. ID#7087068 Greens 724-5362 or 724-3928\n  ', NULL, 'General', '3445', 'POU', '', '', '', '', '', ''),
('3553777', '2014-01-03', '2014-04-02', '\n    ROOFING PLUS 452-5981 / 309-4411\n  ', NULL, 'General', '6072', 'POU', '', '', '', '', '', ''),
('3563524', '2014-02-28', '2014-04-08', '\n    *AAAAAA BUYS*  ANTIQUES , COLLECTIBLES , PARTIAL OR ENTIRE ESTATES $ PAYS HIGHEST CASH $  FURNITURE, BOOKS PAINTING, BRONZES, SILVER, INSTRUMENTS, JEWELRY, CHINA, CAMERAS, COINS, RECORDS, CLOCKS, ALL MILITARY ITEMS, GUNS, SWORDS, HELMETS CALL M. GOODMAN.   (914) 671-2295, or (914) 713-8658\n  ', NULL, 'General', '5735', 'TJN', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

DROP TABLE IF EXISTS `position`;
CREATE TABLE `position` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Position` varchar(100) DEFAULT NULL,
  `Placement` varchar(100) DEFAULT NULL,
  `SiteCode` varchar(10) DEFAULT NULL,
  `Count` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Position` (`Position`),
  KEY `Placement` (`Placement`),
  KEY `SiteCode` (`SiteCode`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=80 ;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`ID`, `Position`, `Placement`, `SiteCode`, `Count`) VALUES
(17, 'Iowa City Announcement Superhd', 'Careers', 'IOW', '1'),
(18, 'Iowa City Careers Superhead', 'Careers', 'IOW', '1'),
(19, 'Iowa City Cars Superhead', 'Careers', 'IOW', '1'),
(20, 'Iowa City Farm Superhead', 'Careers', 'IOW', '1'),
(21, 'Iowa City Garage Sales', 'Careers', 'IOW', '1'),
(22, 'Iowa City Homes Superhead', 'Careers', 'IOW', '1'),
(23, 'Iowa City Rentals Superhead', 'Careers', 'IOW', '1'),
(24, 'Iowa City Services', 'Careers', 'IOW', '1'),
(25, 'Iowa City Shop Superhead', 'Careers', 'IOW', '1'),
(26, 'Back Page Farm Section', 'Farm', 'DES', '1'),
(27, 'Farm and Commercial Bldgs.', 'Farm', 'DES', '3'),
(28, 'Farm Auctions', 'Farm', 'DES', '1'),
(29, 'Farm Equip-Grain Harvesting', 'Farm', 'DES', '1'),
(30, 'Farm Equip-Hay,amp; Forage', 'Farm', 'DES', '1'),
(31, 'Farm Equip-Multiple Listings', 'Farm', 'DES', '2'),
(32, 'Farm Equip-Tillage Equipment', 'Farm', 'DES', '1'),
(33, 'Farm Equip-Tractors', 'Farm', 'DES', '1'),
(34, 'Farm Services', 'Farm', 'DES', '1'),
(35, 'Farm Trailers', 'Farm', 'DES', '2'),
(36, 'Farms and Acreage for Rent', 'Farm', 'DES', '1'),
(37, 'Farms for Sale', 'Farm', 'DES', '23'),
(38, 'Horses and Ponies', 'Farm', 'DES', '2'),
(39, 'Livestock', 'Farm', 'DES', '1'),
(40, 'Livestock Auctions', 'Farm', 'DES', '7'),
(41, 'Livestock Equip.', 'Farm', 'DES', '1'),
(42, 'Seeds, Feeds, Fertilizers', 'Farm', 'DES', '1'),
(43, '2000', 'General', 'POU', '18'),
(44, '3120', 'General', 'POU', '1'),
(45, '3123', 'General', 'POU', '1'),
(46, '3131', 'General', 'POU', '1'),
(47, '3183', 'General', 'POU', '3'),
(48, '3206', 'General', 'POU', '1'),
(49, '3210', 'General', 'POU', '1'),
(50, '3218', 'General', 'POU', '23'),
(51, '3307', 'General', 'POU', '3'),
(52, '3330', 'General', 'POU', '4'),
(53, '3445', 'General', 'POU', '1'),
(54, '5622', 'General', 'TJN', '1'),
(55, '5668', 'General', 'TJN', '1'),
(56, '5735', 'General', 'TJN', '5'),
(57, '6072', 'General', 'POU', '1'),
(58, 'Condos and Townhouses', 'Homes', 'IOW', '2'),
(59, 'Homes', 'Homes', 'IOW', '4'),
(60, 'Lots', 'Homes', 'IOW', '1'),
(61, 'Heavy Duty Trucks and Trailers', 'Industrial Trucks Equip', 'DES', '1'),
(62, 'Antiques and Collectibles', 'Merchandise', 'DES', '6'),
(63, 'Appliances', 'Merchandise', 'DES', '1'),
(64, 'Appliances', 'Merchandise', 'IOW', '1'),
(65, 'Auctions', 'Merchandise', 'DES', '5'),
(66, 'Cemetery Lots', 'Merchandise', 'DES', '1'),
(67, 'Guns, Hunting Accessories', 'Merchandise', 'DES', '2'),
(68, 'Home Furnishings', 'Merchandise', 'DES', '1'),
(69, 'Miscellaneous For Sale', 'Merchandise', 'DES', '2'),
(70, 'Miscellaneous For Sale', 'Merchandise', 'IOW', '1'),
(71, 'Pets and Animals', 'Merchandise', 'IOW', '1'),
(72, 'Repairables - Parts', 'Misc Auto', 'DES', '1'),
(73, 'Wanted', 'Misc Auto', 'DES', '1'),
(74, 'Property - Resorts and Lake', 'Misc Real Estate', 'DES', '3'),
(75, 'Legals', 'Notices', 'DES', '195'),
(76, 'Voice Personals', 'Notices', 'DES', '1'),
(77, 'Marine', 'Recreational', 'DES', '2'),
(78, 'Recreational Vehicles', 'Recreational', 'DES', '1'),
(79, 'Iowa City Service Directory', 'Services', 'IOW', '1');

-- --------------------------------------------------------

--
-- Table structure for table `siteinfo`
--

DROP TABLE IF EXISTS `siteinfo`;
CREATE TABLE `siteinfo` (
  `SiteCode` varchar(45) NOT NULL,
  `SiteName` varchar(100) NOT NULL,
  `Url` varchar(45) NOT NULL,
  `SiteUrl` varchar(256) NOT NULL,
  `BusName` varchar(200) NOT NULL,
  `Palette` varchar(200) NOT NULL,
  `SiteGroup` varchar(200) NOT NULL,
  `City` varchar(45) NOT NULL,
  `State` varchar(45) NOT NULL,
  PRIMARY KEY (`SiteCode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `siteinfo`
--

INSERT INTO `siteinfo` (`SiteCode`, `SiteName`, `Url`, `SiteUrl`, `BusName`, `Palette`, `SiteGroup`, `City`, `State`) VALUES
('DES', 'desmoinesregister', 'classifieds.desmoinesregister.com', 'http://www.desmoinesregister.com', 'The Des Moines Register', '2', 'DES,IOW', 'Des Moines', 'Iowa'),
('INI', 'indystar', 'classifieds.indystar.com', 'http://www.indystar.com', 'The Indianapolis Star', '1', 'INI', 'Indianapolis', 'Indiana'),
('IOW', 'press-citizen', 'classifieds.press-citizen.com', 'http://www.press-citizen.com', 'The Press-Citizen', '4', 'IOW', 'Iowa City', 'Iowa'),
('POU', 'poughkeepsiejournal', 'classifieds.poughkeepsiejournal.com', 'http://www.poughkeepsiejournal.com', 'Poughkeepsie Journal', '4', 'POU', 'Poughkeepsie', 'New York'),
('TJN', 'lohud', 'classifieds.lohud.com', 'http://www.lohud.com', 'The Journal News', '2', 'TJN', 'White Plains', 'New York');
