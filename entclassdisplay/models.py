from django.db import models

class class_listings(models.Model):
#	class_code = models.IntegerField(primary_key=True)
#	class_name = models.CharField(max_length=200)
   id = models.AutoField(primary_key=True)
   publication  = models.CharField(max_length=60)
   pubplacement = models.CharField(max_length=100)
   pubposition  = models.CharField(max_length=100)
#					<publication>DES-Montezuma Supplement</publication>
#				<publication-placement>DES-Automotive</publication-placement>
#				<publication-position>Cars </publication-position>

#class subclass_display(models.Model):
#	class_code = models.ForeignKey(class_display)
#	subclass_code = models.IntegerField(primary_key=True)
#	subclass_name = models.CharField(max_length=200)


#class listings(models.Model):
#	id = models.AutoField(primary_key=True)
#	catcode = models.CharField(max_length=200)
#	classcode = models.CharField(max_length=200)
#	subclasscode = models.CharField(max_length=200)
#	placementdescription = models.CharField(max_length=200)
#	adnumber = models.CharField(max_length=200)
#	startdate = models.DateTimeField('date_published')
##	linecount = models.CharField(max_length=200)
#	runcount = models.CharField(max_length=200)
#	customertype = models.CharField(max_length=200)
#	accountnumber= models.CharField(max_length=200)
##	addr1 = models.CharField(max_length=200)
#	addr2 = models.CharField(max_length=200)
###	zip = models.CharField(max_length=200)
#	county = models.CharField(max_length=200)
#	phone = models.CharField(max_length=200)
#	fax = models.CharField(max_length=200)
#	url = models.CharField(max_length=200)
#	email = models.CharField(max_length=200)
#	idche = models.CharField(max_length=200)
#	pay = models.CharField(max_length=200)
#	addescription = models.CharField(max_length=200)
#	ordersource = models.CharField(max_length=200)
#	orderstatus = models.CharField(max_length=200)
#	payoraraccout = models.CharField(max_length=200)
#	agencyflag = models.CharField(max_length=200)
#	ratenote = models.CharField(max_length=200)
#	edition = models.CharField(max_length=200)
#	zone = models.CharField(max_length=200)
#	adcontent = models.CharField(max_length=10000)
	
#	def __unicode__(self):
#		return "<adnumber '%s'>" % self.adnumber

