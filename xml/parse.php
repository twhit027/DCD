<?php
include('../includes/functions.php');
include('../includes/constants.php');


global $db;
	
function __construct() {
	$this->db = new PDO('mysql:dbname='.DB_NAME.';host='.DB_SERVER.';charset=utf8', DB_USER, DB_PASS);
	$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	//error mode on
	$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

function __destruct() {
	$this->db;
}
	
	
global $usercount;
global $userdata;
global $state;

$usercount=0;
$userdata=array();

$file = "classExport.xml";

$parser=xml_parser_create();

xml_set_element_handler($parser,"start","stop");

xml_set_character_data_handler($parser,"char");



function start($parser,$element_name,$element_attrs)
{
	
	global $usercount;
	global $userdata;
	global $state;

	$state = $element_name;

}

function stop($parser,$element_name)
{
	global $usercount;
	global $userdata;
	global $state;
	$state='';
	
	if($element_name == "PUB-CODE")
	{
		
		$usercount++;
	}
}

function char($parser,$data)
{
	global $usercount;
	global $userdata;
	global $state;
	
	if (!$state) {return;}


	if ($state=="RUN-DATE") {  $userdata[$usercount]["RUN-DATE"] = $data;}
	if ($state=="PUB-CODE") { $userdata[$usercount]["PUB-CODE"] = $data;}
	if ($state=="AD-TYPE") { $userdata[$usercount]["AD-TYPE"] = $data;}
	if ($state=="CAT-CODE") { $userdata[$usercount]["CAT-CODE"] = $data;}
	if ($state=="CLASS-CODE") { $userdata[$usercount]["CLASS-CODE"] = $data;}
	if ($state=="SUBCLASS-CODE") { $userdata[$usercount]["SUBCLASS-CODE"] = $data;}
	if ($state=="POSITION-DESCRIPTION") { $userdata[$usercount]["POSITION-DESCRIPTION"] = $data;}
	if ($state=="AD-NUMBER") { $userdata[$usercount]["AD-NUMBER"] = $data;}
	if ($state=="START-DATE") { $userdata[$usercount]["START-DATE"] = $data;}
	if ($state=="END-DATE") { $userdata[$usercount]["END-DATE"] = $data;}
	if ($state=="LINE-COUNT") { $userdata[$usercount]["LINE-COUNT"] = $data;}
	if ($state=="RUN-COUNT") { $userdata[$usercount]["RUN-COUNT"] = $data;}
	if ($state=="CUSTOMER-TYPE") { $userdata[$usercount]["CUSTOMER-TYPE"] = $data;}
	if ($state=="ACCOUNT-NUMBER") { $userdata[$usercount]["ACCOUNT-NUMBER"] = $data;}
	if ($state=="ACCOUNT-NAME") { $userdata[$usercount]["ACCOUNT-NAME"] = $data;}
	if ($state=="ADDR-1") { $userdata[$usercount]["ADDR-1"] = $data;}
	if ($state=="ADDR-2") { $userdata[$usercount]["ADDR-2"] = $data;}
	if ($state=="BLOCK-HOUSE-NUMBER") { $userdata[$usercount]["BLOCK-HOUSE-NUMBER"] = $data;}
	if ($state=="UNIT-NUMBER") { $userdata[$usercount]["UNIT-NUMBER"] = $data;}
	if ($state=="FLOOR-NUMBER") { $userdata[$usercount]["FLOOR-NUMBER"] = $data;}
	if ($state=="POBOX-NUMBER") { $userdata[$usercount]["POBOX-NUMBER"] = $data;}
	if ($state=="ATTENTION-TO") { $userdata[$usercount]["ATTENTION-TO"] = $data;}
	if ($state=="CITY") { $userdata[$usercount]["CITY"] = $data;}
	if ($state=="STATE") { $userdata[$usercount]["STATE"] = $data;}
	if ($state=="POSTAL-CODE") { $userdata[$usercount]["POSTAL-CODE"] = $data;}
	if ($state=="COUNTY") { $userdata[$usercount]["COUNTY"] = $data;}
	if ($state=="PHONE-NUMBER") { $userdata[$usercount]["PHONE-NUMBER"] = $data;}
	if ($state=="FAX-NUMBER") { $userdata[$usercount]["FAX-NUMBER"] = $data;}
	if ($state=="URL-ADDR") { $userdata[$usercount]["URL-ADDR"] = $data;}
	if ($state=="EMAIL-ADDR") { $userdata[$usercount]["EMAIL-ADDR"] = $data;}
	if ($state=="PAY-FLAG") { $userdata[$usercount]["PAY-FLAG"] = $data;}
	if ($state=="AD-DESCRIPTION") { $userdata[$usercount]["AD-DESCRIPTION"] = $data;}
	if ($state=="ORDER-SOURCE") { $userdata[$usercount]["ORDER-SOURCE"] = $data;}
	if ($state=="ORDER-STATUS") { $userdata[$usercount]["ORDER-STATUS"] = $data;}
	if ($state=="PAYOR-ACCT") { $userdata[$usercount]["PAYOR-ACCT"] = $data;}
	if ($state=="AGENCY-FLAG") { $userdata[$usercount]["AGENCY-FLAG"] = $data;}
	if ($state=="RATE-NOTE") { $userdata[$usercount]["RATE-NOTE"] = $data;}
	if ($state=="USERDATE1LABEL") { $userdata[$usercount]["USERDATE1LABEL"] = $data;}
	if ($state=="USERDATE2LABEL") { $userdata[$usercount]["USERDATE2LABEL"] = $data;}
	if ($state=="USERDATE3LABEL") { $userdata[$usercount]["USERDATE3LABEL"] = $data;}
	if ($state=="USERDATE4LABEL") { $userdata[$usercount]["USERDATE4LABEL"] = $data;}
	if ($state=="AD-CONTENT") { $userdata[$usercount]["AD-CONTENT"] = $data;}

}




$fp=fopen($file,"r");

while ($data=fread($fp,4096))
{

	xml_parse($parser,$data,feof($fp)) or
	die (sprintf("XML Error: %s at line %d",
	xml_error_string(xml_get_error_code($parser)),
	xml_get_current_line_number($parser)));
	
}


xml_parser_free($parser);

class Admin extends Database
{
	function getPositions()
	{
		
		$stmt = $this->db->prepare("SELECT * FROM `placement` ORDER BY id");
		$stmt->execute();
		
		foreach ($stmt as $row) 
		{
			$data[$row['id']] =  $row['name'];
		}
		
		return $data;
	
	}
	
	function getPlacements()
	{
		
		$stmt = $this->db->prepare("SELECT * FROM `position` ORDER BY id");
		$stmt->execute();
		
		foreach ($stmt as $row) 
		{
			$data[$row['id']] =  $row['name'];
		}
		return $data;
	
	}
	
	function putData($placemenet, $position)
	{
		global $usercount;
		global $userdata;
		global $state;
	
		for($i=0;$i<$usercount; $i++) 
		{
				//<pub-code>DES-RM Des Moines Register
				//<ad-type>DES-6COL
				//<cat-code>General Classified</cat-code>
				//<class-code>DES-Garage Sale-B</class-code>
				//<subclass-code>Event and Fairs</subclass-code>
				//<placement-description>DES GARAGE SALE BROADSHEET</placement-description>
				//<position-description>Garage Sale - Event and Fairs</position-description>
				
				$pieces = explode(" - ", $userdata[$i]["POSITION-DESCRIPTION"]);
				
				
				
				/* start here monday */
				/* start here monday */
				/* start here monday */
				if (!in_array($pieces[0], $placement)) 
				{
					//insert new placement with newest id
					$placementnumber += 1;
					$placement[$placementnumber] = $pieces[0];
					
					$stmt = $this->db->prepare("INSERT INTO  'placement' (`id`, `name`) VALUES(:id, :name)");
					$stmt->execute(array(':id' => $userdata[$i]["RUN-DATE"], ':PubCode' => $userdata[$i]["PUB-CODE"]));
			
			
				}

				if(!in_array($pieces[1], $position)) 
				{
					//insert new position with newest id link it to it's parent placement
					$positionnumber += 1;
					$position[$positionnumber] = $pieces[1];	
				}
				/* start here monday */
				/* start here monday */
				/* start here monday */
				
				
				
				
			$stmt = $this->db->prepare("INSERT INTO " . TBL_LISTING . "(`RunDate`, `PubCode`, `AdType`, `CatCode`, `ClassCode`, `SubclassCode`, `PositionDescription`, `Adnumber`, `StartDate`, `EndDate`, `LineCount`, `RunCount`, `CustomerType`, `AccountNumber`, `AccountName`, `Addr1`, `Addr2`, `BlockHouseNumber`, `UnitNumber`, `FloorNumber`, `POBoxNumber`, `AttentionTo`, `City`, `State`, `PostalCode`, `Country`, `PhoneNumber`, `FaxNumber`, `URLAddr`, `EmailAddr`, `PayFlag`, `AdDescription`, `OrderSource`, `OrderStatus`, `PayorAcct`, `AgencyFlag`, `RateNote`, `UserDate1Label`, `UserDate2Label`, `UserDate3Label`, `UserDate4Label`, `AdContent`) VALUES(:RunDate, :PubCode, :AdType, :CatCode, :ClassCode, :SubclassCode, :PostionDescription, :Adnumber, :Startdate, :Enddate, :LineCount, :RunCount, :CustomerType, :AccountNumber, :AccountName, :Addr1, :Addr2, :BlockHouseNumber, :UnitNumber, :FloorNumber, :POBoxNumber, :AttentionTo, :City, :State, :PostalCode, :Country, :PhoneNumber, :FaxNumber, :URLAddr, :EmailAddr, :PayFlag, :AdDescription, :OrderSource, :OrderStatus, :PayorAcct, :AgencyFlag, :RateNote, :UserDate1Label, :UserDate2Label, :UserDate3Label, :UserDate4Label, :AdContent)");
						
			$stmt->execute(array(':RunDate' => $userdata[$i]["RUN-DATE"], ':PubCode' => $userdata[$i]["PUB-CODE"], ':AdType' => $userdata[$i]["AD-TYPE"], ':CatCode' => $userdata[$i]["CAT-CODE"] , ':ClassCode' => $userdata[$i]["CLASS-CODE"] , ':SubclassCode' => $userdata[$i]["SUBCLASS-CODE"], ':PostionDescription' => $userdata[$i]["POSITION-DESCRIPTION"], ':Adnumber' => $userdata[$i]["AD-NUMBER"], ':Startdate' => $userdata[$i]["START-DATE"], ':Enddate' => $userdata[$i]["END-DATE"], ':LineCount' => $userdata[$i]["LINE-COUNT"], ':RunCount' => $userdata[$i]["RUN-COUNT"] , ':CustomerType' => $userdata[$i]["CUSTOMER-TYPE"] , ':AccountNumber' => $userdata[$i]["ACCOUNT-NUMBER"], ':AccountName' => $userdata[$i]["ACCOUNT-NAME"] , ':Addr1' => $userdata[$i]["ADDR-1"]  , ':Addr2' => $userdata[$i]["ADDR-2"], ':BlockHouseNumber' => $userdata[$i]["BLOCK-HOUSE-NUMBER"], ':UnitNumber' => $userdata[$i]["UNIT-NUMBER"] , ':FloorNumber' => $userdata[$i]["FLOOR-NUMBER"] , ':POBoxNumber' => $userdata[$i]["POBOX-NUMBER"], ':AttentionTo' => $userdata[$i]["ATTENTION-TO"] , ':City' => $userdata[$i]["CITY"] , ':State' => $userdata[$i]["STATE"], ':PostalCode' => $userdata[$i]["POSTAL-CODE"] , ':Country' => $userdata[$i]["COUNTRY"], ':PhoneNumber' => $userdata[$i]["PHONE-NUMBER"], ':FaxNumber' => $userdata[$i]["FAX-NUMBER"], ':URLAddr' => $userdata[$i]["URL-ADDR"], ':EmailAddr' => $userdata[$i]["EMAIL-ADDR"], ':PayFlag' => $userdata[$i]["PAY-FLAG"], ':AdDescription' => $userdata[$i]["AD-DESCRIPTION"], ':OrderSource' => $userdata[$i]["ORDER-SOURCE"] , ':OrderStatus' => $userdata[$i]["ORDER-STATUS"], ':PayorAcct' => $userdata[$i]["PAYOR-ACCT"], ':AgencyFlag' => $userdata[$i]["AGENCY-FLAG"], ':RateNote' => $userdata[$i]["RATE-NOTE"], ':UserDate1Label' => $userdata[$i]["USERDATE1LABEL"], ':UserDate2Label' => $userdata[$i]["USERDATE2LABEL"], ':UserDate3Label' => $userdata[$i]["USERDATE13LABEL"], ':UserDate4Label' => $userdata[$i]["USERDATE4LABEL"], ':AdContent' => $userdata[$i]["AD-CONTENT"] ));
						
		
			echo $userdata[$i]["RUN-DATE"];
			echo $userdata[$i]["PUB-CODE"];
			echo $userdata[$i]["AD-TYPE"];
			echo $userdata[$i]["CAT-CODE"];
			echo $userdata[$i]["CLASS-CODE"];
			echo $userdata[$i]["SUBCLASS-CODE"];
			echo $userdata[$i]["POSITION-DESCRIPTION"];
			echo $userdata[$i]["AD-NUMBER"];
			echo $userdata[$i]["START-DATE"];
			echo $userdata[$i]["END-DATE"];
			echo $userdata[$i]["LINE-COUNT"];
			echo $userdata[$i]["RUN-COUNT"];
			echo $userdata[$i]["CUSTOMER-TYPE"];
			echo $userdata[$i]["ACCOUNT-NUMBER"];
			echo $userdata[$i]["ACCOUNT-NAME"];
			echo $userdata[$i]["ADDR-1"];
			echo $userdata[$i]["ADDR-2"];
			echo $userdata[$i]["BLOCK-HOUSE-NUMBER"];
			echo $userdata[$i]["UNIT-NUMBER"];
			echo $userdata[$i]["FLOOR-NUMBER"];
			echo $userdata[$i]["POBOX-NUMBER"];
			echo $userdata[$i]["ATTENTION-TO"];
			echo $userdata[$i]["CITY"];
			echo $userdata[$i]["STATE"];
			echo $userdata[$i]["POSTAL-CODE"];
			echo $userdata[$i]["COUNTRY"];
			echo $userdata[$i]["PHONE-NUMBER"];
			echo $userdata[$i]["FAX-NUMBER"];
			echo $userdata[$i]["URL-ADDR"];   
			echo $userdata[$i]["EMAIL-ADDR"];
			echo $userdata[$i]["PAY-FLAG"];
			echo $userdata[$i]["AD-DESCRIPTION"];
			echo $userdata[$i]["ORDER-SOURCE"];
			echo $userdata[$i]["ORDER-STATUS"];
			echo $userdata[$i]["PAYOR-ACCT"];
			echo $userdata[$i]["AGENCY-FLAG"];
			echo $userdata[$i]["RATE-NOTE"];
			echo $userdata[$i]["USERDATE1LABEL"];
			echo $userdata[$i]["USERDATE2LABEL"];
			echo $userdata[$i]["USERDATE3LABEL"];
			echo $userdata[$i]["USERDATE4LABEL"];
			echo $userdata[$i]["AD-CONTENT"];
			echo "<br />";
			echo "<br />";
		
			   
		}	
	}

}

	

$user = new Admin();
$positions = $user->getPositions();
$placements = $user->getPlacements();
$uid = $user->putData($placements, $positions);


	
	






?>