<?php
/**
 * Created by PhpStorm.
 * User: JHICKS
 * Date: 3/15/14
 * Time: 4:25 PM
 */

namespace GCI;


class Ads {
    function InitializeAds()
    {
        $data ="<script type='text/javascript'>
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
				</script>

				<script type='text/javascript'>
				googletag.cmd.push(function() {
				googletag.defineSlot('/7103/wi-fonddulac-C1516/728x90_1/news/main', [728, 90], 'div-gpt-ad-1387471826615-0').addService(googletag.pubads());
				googletag.defineSlot('/7103/wi-fonddulac-C1516/728x90_2/news/main', [728, 90], 'div-gpt-ad-1387471826615-1').addService(googletag.pubads());
				googletag.defineSlot('/7103/wi-fonddulac-C1516/flex_1/news/main', [[300, 250], [300, 600], [300, 800]], 'div-gpt-ad-1387471826615-2').addService(googletag.pubads());
				googletag.defineSlot('/7103/wi-fonddulac-C1516/launchpad_SF/news/main', [[728, 90], [940, 30], [960, 66], [970, 66], [980, 66]], 'div-gpt-ad-1387471826615-3').addService(googletag.pubads());
				googletag.pubads().enableSingleRequest();
				googletag.enableServices();
				});
				</script>";

        return $data;


    }
    function getLaunchpad()
    {
        $data ="<!-- wi-fonddulac-C1516/launchpad_SF/news/main -->
		<div style='padding-top:90px;padding-bottom:10px;text-align: center;'>
			<!-- wi-fonddulac-C1516/launchpad_SF/news/main -->
			<div id='div-gpt-ad-1387471826615-3'>
			<script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1387471826615-3'); });
			</script>
			</div>
		</div>";
        return $data;
    }
    function getLeaderBottom()
    {
        $data ="<div  style='text-align: center;padding-top:20px;'>
				<div id='div-gpt-ad-1387471826615-1'>
				<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1387471826615-1'); });
				</script>
				</div>
				</div>";
        return $data;
    }
    function getLeaderTop()
    {
        $data ="
		<div style='padding-top:90px;padding-bottom:10px;text-align: center;'>
			<div id='div-gpt-ad-1387471826615-0' style='width:728px; height:90px;'>
				<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1387471826615-0'); });
				</script>
			</div>
		</div>";
        return $data;
    }
    function getFlex()
    {
        $data ="<div style='text-align:center;' >
					<div id='div-gpt-ad-1387471826615-2'>
					<script type='text/javascript'>
					googletag.cmd.push(function() { googletag.display('div-gpt-ad-1387471826615-2'); });
					</script>
					</div>
				</div>";
        return $data;
    }
} 