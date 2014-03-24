<!-- Start SiteCatalyst code version: H.3. -->



<script type="text/javascript" src="http://content.gannettonline.com/global/scripts/revsci.js"></script>
<script type="text/javascript" src="http://js.revsci.net/gateway/gw.js?csid=J06575" charset="ISO-8859-1"></script>
<script type="text/javascript">
<!--
s_account="<?= VAR_GPAPER ?>,gntbcstglobal";
//--> 
</script>
<!-- SiteCatalyst code version: H.3.
Copyright 1997-2005 Omniture, Inc. More info available at
http://www.omniture.com -->
<script type="text/javascript" src="http://content.gannettonline.com/global/s_code/s_code.js"></script>
<script type="text/javascript"><!--
/* You may give each page an identifying name, server, and channel on
the next lines. */
s.server="publicus"
if (typeof s_pageName!='undefined') { s.pageName=s_pageName; } else { s.pageName="<?= VAR_SPAGENAME ?>"; }
if (typeof s_channel!='undefined') { s.channel=s_channel; }
if (typeof s_pageType!='undefined') { s.pageType=s_pageType; }
if (typeof s_prop1!='undefined') { s.prop1=s_prop1; } else { s.prop1=""; }
s.prop2="<?= VAR_SPROP2 ?>"
s.prop3="<?= VAR_SPROP3 ?>"
if (typeof s_prop4!='undefined') { s.prop4=s_prop4; } else { s.prop4=""; }
if (typeof s_prop5!='undefined') { s.prop5=s_prop5; } else { s.prop5=""; }
s.prop6="<?= VAR_SPROP6 ?>"
s.prop7="<?= VAR_SPROP7 ?>"
if (typeof s_prop8!='undefined') { s.prop8=s_prop8; } else { s.prop8=""; }
s.prop23=document.location;
s.prop16='article';
s.prop25="<?= VAR_GPAPER ?>"
s.prop50="Newspaper";

var currentTime=new Date();
var gciYear = currentTime.getFullYear();
DM_addToLoc("zipcode", escape(s.prop30));
DM_addToLoc("age", escape((gciYear-s.prop31)));
DM_addToLoc("gender", escape(s.prop32));
var gci_ssts=OAS_sitepage;

gci_ssts=gci_ssts.replace(/\/article\.htm.*$/,'');
gci_ssts=gci_ssts.replace(/\/front\.htm.*$/,'');
gci_ssts=gci_ssts.replace(/\/index\.htm.*$/,'');
gci_ssts=gci_ssts.replace(/\@.*$/,'');
gci_ssts=gci_ssts.replace(/^.*\.com\//,'');

var gci_tempossts=gci_ssts; 
var gci_ossts=gci_tempossts.split("/")
gci_ssts=gci_ssts.replace(/\//g,' > ');
gci_ssts='newspaper > '+gci_ssts;

if
(  gci_ossts[0] == "life"
|| gci_ossts[0] == "money"
|| gci_ossts[0] == "news"
|| gci_ossts[0] == "sports"
|| gci_ossts[0] == "tech"
|| gci_ossts[0] == "travel"
|| gci_ossts[0] == "weather"
|| gci_ossts[0] == "umbrella"
)

{
  DM_cat(gci_ssts);
}
else
{
  DM_cat('newspaper > other');
}

var gci_osstslen=gci_ossts.length;
for(var i=0; i<gci_osstslen; i++) {
if(i==0)
s.prop17=gci_ossts[i];   // section
if(i==1)
s.prop18=gci_ossts[i];   // subsection
if(i==2)
s.prop19=gci_ossts[i];   // topic
if(i==3)
s.prop20=gci_ossts[i];   // Subtopic
}
DM_tag();

// sets the RevSci cookie in GCION domain
if (rsinetsegs.length > 0)
{
  if (!RevSci.HasSegmentCookie())
  {
    RevSci.Rpc.Send(RevSci.RequestUrl(rsinetsegs));
    RevSci.Cookie.Set(revsci_Cookie, true);
  }
}
if (typeof rsinetsegs != 'undefined') { s.prop48 = (rsinetsegs.join('|')).replace(/J06575_/g,''); } else { s.prop48 = 'no segment'; }

//-->
</script>
<script type="text/javascript"><!--
/************* DO NOT ALTER ANYTHING BELOW THIS LINE ! **************/
var s_code=s.t();if(s_code)document.write(s_code);
//--></script>
<script type="text/javascript"><!--
if (navigator.appVersion.indexOf('MSIE') >= 0) document.write(unescape('%3C')+'\!-'+'-');
//--></script>
<noscript><img src="http://gntbcstglobal.112.2O7.net/b/ss/gntbcstglobal,<?= VAR_GPAPER ?>/1/H.3--NS/0" height="1" width="1" border="0" alt="" /></noscript><!--/DO NOT REMOVE/-->
<!-- End SiteCatalyst code version: H.3. -->