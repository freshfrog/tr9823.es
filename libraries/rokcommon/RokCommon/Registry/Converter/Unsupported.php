<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_Registry_Converter_Unsupported implements RokCommon_Registry_IConverter
{
    /**
     * Convert a registry type object to a RokCommon_Registry
     * @static
     *
     * @param RokCommon_Registry $original The original registry type object to convert to a RokCommon_Registry
     *
     * @return \RokCommon_Registry
     */
    public function convert($original)
    {
        return $original;
    }
}
