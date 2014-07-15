<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 7/2/14
 * Time: 4:11 PM
 */

namespace GCI;


class SiteInfo Extends Crud {
    public static $paletteArray = array(
        1 => array('top' => '#292929', 'bottom' => '#080808', 'border' => '#2C2C2C'),
        2 => array('top' => '#01588d', 'bottom' => '#0b396b', 'border' => '#87ABC0'),
        3 => array('top' => '#01abf9', 'bottom' => '#038be6', 'border' => '#87ABC0'),
        4 => array('top' => '#851719', 'bottom' => '#701612', 'border' => '#151515'),
        5 => array('top' => '#000061', 'bottom' => '#00004c', 'border' => '#87ABC0'),
        6 => array('top' => '#000079', 'bottom' => '#000054', 'border' => '#87ABC0'),
        7 => array('top' => '#0000ae', 'bottom' => '#00007f', 'border' => '#87ABC0'),
        8 => array('top' => '#666156', 'bottom' => '#5A5347', 'border' => '#A9B55F'),
        9 => array('top' => '#00007b', 'bottom' => '#00005b', 'border' => '#87ABC0'),
        10 => array('top' => '#285737', 'bottom' => '#224F35', 'border' => '#151515'),
        91 => array('top' => '#141414', 'bottom' => '#141414', 'border' => '#141414'),
        92 => array('top' => '#141414', 'bottom' => '#141414', 'border' => '#141414'),
        93 => array('top' => '#141414', 'bottom' => '#141414', 'border' => '#141414'),
        94 => array('top' => '#141414', 'bottom' => '#141414', 'border' => '#141414'),
        95 => array('top' => '#141414', 'bottom' => '#141414', 'border' => '#141414'),
        96 => array('top' => '#141414', 'bottom' => '#141414', 'border' => '#141414'),
        97 => array('top' => '#141414', 'bottom' => '#141414', 'border' => '#141414'),
        98 => array('top' => '#141414', 'bottom' => '#141414', 'border' => '#141414'),
        99 => array('top' => '#141414', 'bottom' => '#141414', 'border' => '#141414'),
        100 => array('top' => '#141414', 'bottom' => '#141414', 'border' => '#141414')
    );

    protected $table = 'siteinfo';

    protected $pk	= 'SiteCode';
} 