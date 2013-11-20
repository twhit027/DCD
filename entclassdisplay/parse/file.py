from xml.dom.minidom import parseString
from datetime import datetime
import MySQLdb

FILE_OUT = "write.sql"
FILE_IN = "current.xml"
TABLE = "class_listings"

#open file to read
file = open(FILE_IN,'r')

#read in file and save it to string
data = file.read()

#close read file after it's done reading
file.close()

#let the program know we are playing with an XML file
dom = parseString(data)

#split the string at each pub-code
rows=dom.getElementsByTagName('AdBaseInfo')


#connect to database
db = MySQLdb.connect(host="localhost", user="gawauser", passwd="Gawauser13!", db="entclassdb")
mark = db.cursor()



#loop through each item (pub-code)
for node in rows:

	#without these try excepts you will get a no data element error need to add one for each field in the end
	try:
		placement = node.getElementsByTagName('publication-placement')[0].firstChild.data
	except AttributeError:
		placement = ""
	
	try:
		publication = node.getElementsByTagName('publication')[0].firstChild.data
	except AttributeError:
		publication = ""
	
	try:
		position = node.getElementsByTagName('publication-position')[0].firstChild.data
	except AttributeError:
		position = ""
			

#`placementdescription`,`adnumber`,`startdate`,`enddate`,`linecount`,`runcount`,`customertype`,`accountnumber`,`accountname`,`addr1`,`addr2`,`city`,`state`,`zip`,`county`,`phone`,`fax`,`url`,`email`,`idche`,`pay`,`addescription`,`ordersource`,`orderstatus`,`payoraraccout`,`agencyflag`,`ratenote`,`edition`,`zone`,`adcontent`
			
	mark.execute("""INSERT INTO `entclassdb`.`entclassdisplay_class_listings`(`publication`,`pubplacement`,`pubposition`)VALUES(%s ,%s ,%s );""",
		(publication,
	     placement,
		 position
		 )) 
		
	#	placement, 
	#	node.getElementsByTagName('ad-number')[0].firstChild.data, 
	#	start,
	#	 end, 
	#	 node.getElementsByTagName('line-count')[0].firstChild.data, 
	#	 node.getElementsByTagName('run-count')[0].firstChild.data,  
	#	 node.getElementsByTagName('customer-type')[0].firstChild.data, 
	##	 node.getElementsByTagName('account-name')[0].firstChild.data, 
	#	 node.getElementsByTagName('addr-1')[0].firstChild.data, 
	#	 addr2, 
	#	 node.getElementsByTagName('city')[0].firstChild.data, 
	#	 node.getElementsByTagName('state')[0].firstChild.data, 
	#	 node.getElementsByTagName('postal-code')[0].firstChild.data, 
	#	 node.getElementsByTagName('country')[0].firstChild.data, 
	#	 phone, 
	#	 fax, 
	#	 urladd, 
	#	 emailaddr, 
	#	 idche, 
	#	 node.getElementsByTagName('pay-flag')[0].firstChild.data, 
	#	 node.getElementsByTagName('ad-description')[0].firstChild.data, 
	#	 ordersource, 
	#	 node.getElementsByTagName('order-status')[0].firstChild.data, 
	#	 node.getElementsByTagName('payor-acct')[0].firstChild.data, 
	#	 node.getElementsByTagName('agency-flag')[0].firstChild.data, 
	#	 ratenote, 
	#	 edition, 
	#	 zone, 
	#	 node.getElementsByTagName('ad-content')[0].firstChild.data))
#


db.commit()
db.close()