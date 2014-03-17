<?php
/**
 * Created by PhpStorm.
 * User: JHICKS
 * Date: 3/15/14
 * Time: 4:24 PM
 */

namespace GCI;


class Navigation {
    function getSideNavigation($categories)
    {
        $random = rand(1, 1500);
        $data = '';
        $placementId = 0;
        foreach ($categories as $placement => $positions) {
            $placementId++;
            $data .='<li>';
            $data .='<div class="accordion-heading" style="padding-bottom:5px;">';
            $data .='<a data-toggle="collapse" class="btn btn-default"  style="width:100%;" role="button" data-target="#accordion-heading-'.$placementId.''.$random.'"><span class="nav-header-primary">'.$placement.'</span></a>';
            $data .='</div>';

            $data .='<ul class="nav nav-list collapse" id="accordion-heading-'.$placementId.''.$random.'">';
            foreach ($positions as $position => $count) {
                $data .='<a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="category.php?place='.urlencode($placement).'&posit='.urlencode($position).'" title="Title">'.$position.'('.$count.')</a>';
            }
            $data .='</ul>';

            $data .='</li>';
        }


        return $data;
    }

    function getTopNavigationStatic($siteUrl, $top, $bottom, $border)
    {
        $data = '<style>.navbar-inverse {
			background: -webkit-linear-gradient('.$top.', '.$bottom.'); /* For Safari */
			background: -o-linear-gradient('.$top.', '.$bottom.'); /* For Opera 11.1 to 12.0 */
			background: -moz-linear-gradient('.$top.', '.$bottom.'); /* For Firefox 3.6 to 15 */
			background: linear-gradient('.$top.', '.$bottom.'); /* Standard syntax */
			border-bottom-color: '.$border.';
			}</style>';

        $data .= '<nav id="grad" role="navigation" class="collapse navbar-collapse bs-navbar-collapse top-navbar"><ul class="nav navbar-nav">';
        $data .= '<li><a href="'.$siteUrl.'/" style="margin:0;padding:0;"><img style="padding-top:10px" class="img-responsive" src="'.$siteUrl.'/graphics/ody/cobrand_logo.gif"/></a></li>';
        $data .= '<li><a href="'.$siteUrl.'/jobs">JOBS</a></li>';
        $data .= '<li><a href="'.$siteUrl.'/cars">CARS</a></li>';
        $data .= '<li><a href="'.$siteUrl.'/homes">HOMES</a></li>';
        $data .= '<li><a href="'.$siteUrl.'/apartments">APARTMENTS</a></li>';
        $data .= '<li><a href="'.$siteUrl.'/dating">DATING</a></li>';
        $data .= '<li><a href="'.$siteUrl.'/newclass/front/">BUY & SELL</a></li>';
        $data .= '</ul></nav>';

        return $data;
    }

    function getBottomNavigationStatic($siteUrl, $siteName) {
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
        return $data;
    }
} 