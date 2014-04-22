<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 3/15/14
 * Time: 4:24 PM
 */

namespace GCI;


class Navigation
{
    function getSideNavigation($categories)
    {
        $random = rand(1, 1500);
        $data = '';
        $placementId = 0;
        foreach ($categories as $placement => $positions) {
            $placementId++;
            $data .= '<li>';
            $data .= '<div class="accordion-heading" style="padding-bottom:5px;">';
            $data .= '<a data-toggle="collapse" class="btn btn-default"  style="width:100%;" role="button" data-target="#accordion-heading-' . $placementId . '' . $random . '"><span class="nav-header-primary">' . $placement . '</span></a>';
            $data .= '</div>';

            $data .= '<ul class="nav nav-list collapse" id="accordion-heading-' . $placementId . '' . $random . '">';
            foreach ($positions as $position => $vals) {

                if ($vals['url'] != '') {
                    if ($vals['url'] == '1') {
                        $data .= '<a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="map.php?place=' . urlencode($placement) . '&posit=' . urlencode($position) . '" title="Title">' . $position . '(' . $vals['count'] . ')</a>';
                    } else {
                        $data .= '<a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="' . $vals['url'] . '" target="_blank" title="Title">' . $position . '<img src="img/link.png" style="padding-left:10px;" /></a>';
                    }
                } else {
                    $data .= '<a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="category.php?place=' . urlencode($placement) . '&posit=' . urlencode($position) . '" title="Title">' . $position . ' <span class="badge">' . $vals['count'] . '</span></a>';
                }

            }
            $data .= '</ul>';

            $data .= '</li>';
        }

        return $data;
    }

    function getTopNavigationStatic($siteUrl, $top, $bottom, $border, $siteCode = '')
    {
        $siteData = @file_get_contents("./images/$siteCode/top.json");
        $siteImage = $siteUrl . '/graphics/ody/cobrand_logo.gif';
        //$siteImage = $siteUrl . '/graphics/ody/mast_logo.gif"';

        $siteLinks = array(
            'JOBS' => $siteUrl . '/jobs',
            'CARS' => $siteUrl . '/cars',
            'HOMES' => $siteUrl . '/homes',
            'APARTMENTS' => $siteUrl . '/apartments',
            'DATING' => $siteUrl . '/dating',
            'BUY & SELL' => $siteUrl . '/newclass/front/'
        );

        if ($siteData !== false) {
            $siteDataArray = json_decode($siteData, true);

            $siteUrl = $siteDataArray['siteUrl'];
            $siteImage = $siteDataArray['siteImage'];
            $siteLinks = $siteDataArray['siteLinks'];

            $top = $bottom = $border = '';
            if (isset($siteDataArray['saxo'])) {
                $top = $siteDataArray['saxo']['top'];
                $bottom = $siteDataArray['saxo']['bottom'];
                $border = $siteDataArray['saxo']['border'];

                //$imageLi = '<li><a href="'.$siteUrl.'/"><img style="height:40;" class="img-responsive" src="'.$siteImage.'"/></a></li>';
            }
        }

        $data = '';

        if (isset($top) && isset($bottom) && isset($border)) {
            $data .= '<style>.navbar-inverse {
                background: -webkit-linear-gradient(' . $top . ', ' . $bottom . '); /* For Safari */
                background: -o-linear-gradient(' . $top . ', ' . $bottom . '); /* For Opera 11.1 to 12.0 */
                background: -moz-linear-gradient(' . $top . ', ' . $bottom . '); /* For Firefox 3.6 to 15 */
                background: linear-gradient(' . $top . ', ' . $bottom . '); /* Standard syntax */
                border-bottom-color: ' . $border . ';
                }</style>';
        }

        $data .= '<nav id="grad" role="navigation" class="collapse navbar-collapse bs-navbar-collapse top-navbar"><ul class="nav navbar-nav">';

        $data .= '<li style="height:40px"><a href="'.$siteUrl.'/" style="margin:0;padding:0;"><img style="padding-top:10px;height:40px" src="'.$siteImage.'"/></a></li>';

        foreach ($siteLinks as $linkName => $linkHref) {
            $data .= '<li><a href="'.$linkHref.'">'.$linkName.'</a></li>';
        }

        $data .= '</ul></nav>';

        return $data;
    }

    function getBottomNavigationStatic($siteUrl, $siteName)
    {
        $data = '<hr /><div class="container" style="font-size: 12px;line-height: 16px;text-align: center"><p>';
        $data .= '<a href="' . $siteUrl . '/news">News</a>&nbsp;|&nbsp;';
        $data .= '<a href="' . $siteUrl . '/sports">Sports</a>&nbsp;|&nbsp;';
        $data .= '<a href="' . $siteUrl . '/business">Business</a>&nbsp;|&nbsp;';
        $data .= '<a href="' . $siteUrl . '/entertainment">Entertainment</a>&nbsp;|&nbsp;';
        $data .= '<a href="' . $siteUrl . '/life">Life</a>&nbsp;|&nbsp;';
        $data .= '<a href="' . $siteUrl . '/communities">Communities</a>&nbsp;|&nbsp;';
        $data .= '<a href="' . $siteUrl . '/opinion">Opinion</a>&nbsp;|&nbsp;';
        $data .= '<a href="http://www.legacy.com/obituaries/' . $siteName . '/">Obituaries</a>&nbsp;|&nbsp;';
        $data .= '<a href="' . $siteUrl . '/help">Help</a></p>';
        $data .= '<p>Copyright &copy; 2014 www.' . $siteName . '.com. All rights reserved. Users of this site agree to the ';
        $data .= '<a href="' . $siteUrl . '/section/terms">Terms of Service</a>, ';
        $data .= '<a href="' . $siteUrl . '/section/privacy">Privacy Notice</a>, and <a href="' . $siteUrl . '/section/privacy#adchoices">Ad Choices</a></p></div>';
        return $data;
    }
} 