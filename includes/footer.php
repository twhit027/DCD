<?php
	$data = '<hr /><div class="container" style="font-size: 12px;line-height: 16px;text-align: center"><p>';
	$data .= '<a href="'.$siteUrl.'/news">News</a>&nbsp;|&nbsp;';
	$data .= '<a href="'.$siteUrl.'/sports">Sports</a>&nbsp;|&nbsp;';
	$data .= '<a href="'.$siteUrl.'/business">Business</a>&nbsp;|&nbsp;';
	$data .= '<a href="'.$siteUrl.'/entertainment">Entertainment</a>&nbsp;|&nbsp;';
	$data .= '<a href="'.$siteUrl.'/life">Life</a>&nbsp;|&nbsp;';
	$data .= '<a href="'.$siteUrl.'/communities">Communities</a>&nbsp;|&nbsp;';
	$data .= '<a href="'.$siteUrl.'/opinion">Opinion</a>&nbsp;|&nbsp;';						
	$data .= '<a href="http://www.legacy.com/obituaries/'.$siteName.'/">Obituaries</a>&nbsp;|&nbsp;';	
	$data .= '<a href="'.$siteUrl.'/help">Help</a></p>';
	$data .= '<p>Copyright &copy; 2014 www.'.$siteName.'.com. All rights reserved. Users of this site agree to the ';
	$data .= '<a href="'.$siteUrl.'/section/terms">Terms of Service</a>, ';
	$data .= '<a href="'.$siteUrl.'/section/privacy">Privacy Notice</a>, and <a href="'.$siteUrl.'/section/privacy#adchoices">Ad Choices</a></p></div>';				
			
	echo $data;
?>