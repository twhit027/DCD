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
                        $data .= '<a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="map.php?place=' . urlencode($placement) . '&posit=' . urlencode($position) . '" title="Title">' . $position . ' <span class="badge">' . $vals['count'] . '</span></a>';
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

    function getTopNavigation($siteUrl, $paletteNumber = '', $siteName = '', $siteData = '', $imgSiteName = '', $wrap=true, $includeImage=true)
    {
        $top = $bottom = $border = $data = '';
        if (!empty($paletteNumber)&& $paletteNumber < 90) {
            $top = \GCI\site::$paletteArray[$paletteNumber]['top'];
            $bottom = \GCI\site::$paletteArray[$paletteNumber]['bottom'];
            $border = \GCI\site::$paletteArray[$paletteNumber]['border'];
        }

        //$siteImage = $siteUrl . '/graphics/ody/cobrand_logo.gif';
        //$siteImage = $siteUrl . '/graphics/ody/mast_logo.gif"';
        if (empty($imgSiteName)) {
            $siteImage = "http://www.gannett-cdn.com/sites/$siteName/images/site-nav-logo@2x.png";
        } else {
            $siteImage = "http://www.gannett-cdn.com/sites/$imgSiteName/images/site-nav-logo@2x.png";
        }

        $siteLinks = array(
            'JOBS' => $siteUrl . '/jobs',
            'CARS' => $siteUrl . '/cars',
            'HOMES' => $siteUrl . '/homes',
            'DATING' => $siteUrl . '/dating',
            'BUY & SELL' => $siteUrl . '/newclass/front/'
        );

        if (!empty($siteData)) {
            $siteLinks = json_decode($siteData, true);
        }

        if (!empty($top) && !empty($bottom) && !empty($border)) {
            $data .= '<style>.navbar-inverse {
                background: -webkit-linear-gradient(' . $top . ', ' . $bottom . '); /* For Safari */
                background: -o-linear-gradient(' . $top . ', ' . $bottom . '); /* For Opera 11.1 to 12.0 */
                background: -moz-linear-gradient(' . $top . ', ' . $bottom . '); /* For Firefox 3.6 to 15 */
                background: linear-gradient(' . $top . ', ' . $bottom . '); /* Standard syntax */
                border-bottom-color: ' . $border . ';
                }</style>';
        }

        $data .= '<ul class="nav navbar-nav">';

        if ($includeImage) {
            $data .= '<li style="height:40px"><a style="margin:5px;padding:0;" href="' . $siteUrl . '/"><img style="height:40px" class="img-responsive" src="' . $siteImage . '"/></a></li>';
        }

        //$data .= '<li style="height:40px"><a href="'.$siteUrl.'/" style="margin:0;padding:0;"><img style="padding-top:10px;height:40px" src="'.$siteImage.'"/></a></li>';

        foreach ($siteLinks as $linkName => $linkHref) {
            $data .= '<li><a href="'.$linkHref.'">'.$linkName.'</a></li>';
        }

        $data .= '</ul>';

        if ($wrap) {
            $data .= '<nav id="grad" role="navigation" class="collapse navbar-collapse bs-navbar-collapse top-navbar">' . $data . '</nav>';
        }

        return $data;
    }

    function getSideCheckBoxes($categories)
    {
        $data = '';
        $placementId = 0;
        foreach ($categories as $placement => $positions) {
            $placementId++;
            $data .= '<input type="checkbox" onClick=\'toggle(this, "foo'.$placementId.'")\' value="'.$placement.'" />' .$placement. '<br />';
            foreach ($positions as $position => $vals) {
                $data .= '&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="foo'.$placementId.'" value="'.$position.'" />' .$position. '<br />';
            }
        }

        return $data;
    }

    function getBottomNavigation($siteUrl, $paletteNumber = '', $siteName, $siteData = '')
    {
        $siteLinks = array(
            'News' => $siteUrl . '/news',
            'Sports' => $siteUrl . '/sports',
            'Business' => $siteUrl . '/business',
            'Entertainment' => $siteUrl . '/entertainment',
            'Life' => $siteUrl . '/life',
            'Communities' => $siteUrl . '/communities',
            'Opinion' => $siteUrl . '/opinion',
            'Obituaries' => 'http://www.legacy.com/obituaries/' .$siteName . '/',
            'Help' => $siteUrl . '/help',
        );

        if (!empty($siteData)) {
            $siteLinks = json_decode($siteData, true);
        }

        $data = '<hr /><div class="container" style="font-size:12px;line-height:16px;text-align: center">';
        $data .= '<div id="footlinks" class="footlinks"><ul>';
        foreach ($siteLinks as $linkName => $linkHref) {
            $data .= '<li><a href="'.$linkHref.'">'.$linkName.'</a></li>';
        }
        $data .= '</ul></div>';
        if ($paletteNumber < 90) {
            $data .= '<p>Copyright &copy; '.date('Y').' www.' . $siteName . '.com. All rights reserved. Users of this site agree to the ';
            $data .= '<a href="' . $siteUrl . '/section/terms">Terms of Service</a>, ';
            $data .= '<a href="' . $siteUrl . '/section/privacy">Privacy Notice</a>, and <a href="' . $siteUrl . '/section/privacy#adchoices">Ad Choices</a></p>';
        } else {
            $data .= '<p>All rights reserved. Users of this site agree to the <a href="'.$siteUrl.'/terms/">Terms of Service</a>,';
            $data .= ' <a href="'.$siteUrl.'/privacy/">Privacy Notice/Your California Privacy Rights</a>, and';
            $data .= ' <a href="'.$siteUrl.'/privacy/#adchoices">Ad Choices</a></p>';
        }

        $data .= '</div>';

        return $data;
    }
} 