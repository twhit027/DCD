<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 3/15/14
 * Time: 4:25 PM
 */

namespace GCI;


class Ads 
{
    function InitializeAds($dfpid, $dfpmobile)
    {
        $data = "<script type='text/javascript'>
		var googletag = googletag || {};
		googletag.cmd = googletag.cmd || [];
		(function() {
		var gads = document.createElement('script');
		gads.async = true;
		gads.type = 'text/javascript';
		var useSSL = 'https:' == document.location.protocol;
		gads.src = (useSSL ? 'https:' : 'http:') + 
		'//www.googletagservices.com/tag/js/gpt.js';
		var node = document.getElementsByTagName('script')[0];
		node.parentNode.insertBefore(gads, node);
		})();
		
		googletag.cmd.push(function() {
		
		googletag.defineSlot('/7103/".$dfpid."/728x90_2/classifieds/main', [728, 90], 'div-gpt-ad-1395329372590-1').addService(googletag.pubads());
		googletag.defineSlot('/7103/".$dfpid."/flex_1/classifieds/main', [[300, 250], [300, 600], [300, 800]], 'div-gpt-ad-1395329372590-2').addService(googletag.pubads());
		googletag.defineSlot('/7103/".$dfpid."/launchpad_SF/classifieds/main', [[728, 90], [940, 30], [960, 66], [970, 66], [980, 66]], 'div-gpt-ad-1404831578985-0').addService(googletag.pubads());
		googletag.defineSlot('/7103/".$dfpmobile."/html5_tablet/interstitial_landscape/classifieds/main', [1024, 675], 'div-gpt-ad-1395329372590-4').addService(googletag.pubads());
		googletag.defineSlot('/7103/".$dfpmobile."/html5_tablet/interstitial_portrait/classifieds/main', [768, 930], 'div-gpt-ad-1395329372590-5').addService(googletag.pubads());
		googletag.defineSlot('/7103/".$dfpmobile."/wap/banner_bottom/classifieds/main', [[6, 1], [320, 50]], 'div-gpt-ad-1395329372590-6').addService(googletag.pubads());
		googletag.defineSlot('/7103/".$dfpmobile."wap/banner_top/classifieds/main', [[6, 1], [320, 50]], 'div-gpt-ad-1395329372590-7').addService(googletag.pubads());
		googletag.pubads().enableSingleRequest();
		googletag.enableServices();
		});
		</script>";

        return $data;
    }
    function getLaunchpad()
    {
        $data ="<!-- ia-iowacity-C1033/launchpad_SF/classifieds/main -->
		<div align='center'  style='text-align:center;padding-bottom:20px;'>
		<div id='div-gpt-ad-1404831578985-0' style='width:730px;margin: 0 auto;'>
		<script type='text/javascript'>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1404831578985-0'); });
		</script>
		</div>
		</div>
		";

		return $data;
    }
    function getLeaderBottom()
    {
        $data ="<!-- ia-desmoines-C1150/728x90_2/classifieds/main -->
		<div align='center'  style='text-align:center;'>
		<div id='div-gpt-ad-1395329372590-1' style='width:730px;margin: 0 auto;'>
		<script type='text/javascript'>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1395329372590-1'); });
		</script>
		</div>
		</div>";

        return $data;
    }

    function getFlex()
    {
        $data ="<!-- ia-desmoines-C1150/flex_1/classifieds/main -->
		<div style='text-align:center;'>
		<div id='div-gpt-ad-1395329372590-2'>
		<script type='text/javascript'>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1395329372590-2'); });
		</script>
		</div>
		</div>";

        return $data;
    }
	function getLandscapeInterstitial()
	{
		$data = "<!-- ia-iowacity-mobile-C1033/html5_tablet/interstitial_landscape/classifieds/main -->
		<div id='div-gpt-ad-1395329372590-4' style='width:1024px; height:675px;'>
		<script type='text/javascript'>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1395329372590-4'); });
		</script>
		</div>";

        return $data;
	}
	function getPortraitInterstitial()
	{
		$data ="<!-- ia-iowacity-mobile-C1033/html5_tablet/interstitial_portrait/classifieds/main -->
		<div id='div-gpt-ad-1395329372590-5' style='width:768px; height:930px;'>
		<script type='text/javascript'>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1395329372590-5'); });
		</script>
		</div>";

        return $data;
	}
	function getMobileBannerBottom()
	{
		$data ="<!-- ia-iowacity-mobile-C1033/wap/banner_bottom/classifieds/main -->
		<div style='text-align:center;'>
		<div id='div-gpt-ad-1395329372590-6'>
		<script type='text/javascript'>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1395329372590-6'); });
		</script>
		</div>
		</div>";

        return $data;
	}
	function getMobileBannerTop()
	{
		$data ="<!-- ia-iowacity-mobile-C1033/wap/banner_top/classifieds/main -->
		<div style='text-align:center;'>
		<div id='div-gpt-ad-1395329372590-7'>
		<script type='text/javascript'>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1395329372590-7'); });
		</script>
		</div>
		</div>";

        return $data;
	}
} 