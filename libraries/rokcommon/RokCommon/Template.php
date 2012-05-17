<?php
/**
 * @version   $Id: Template.php 48519 2012-02-03 23:18:52Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('ROKCOMMON') or die;

class RokCommon_Template
{
    public static function replace($token, $replacement, $string)
    {
        return str_replace('[%'.$token.'%]', $replacement, $string);
    }
}
