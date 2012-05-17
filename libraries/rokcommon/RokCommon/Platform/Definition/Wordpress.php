<?php
/**
 * @version   $Id: Wordpress.php 48519 2012-02-03 23:18:52Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_Platform_Definition_Wordpress extends RokCommon_Platform_BaseDefinition
{
    /**
     * @var int
     */
    protected static $short_version_min_parts = 1;

    /**
     * Check to see if this is the current platform running
     * @static
     * @return bool true if this is the current platform, false if not.
     */
    public static function isCurrentlyRunning()
    {
        if (defined('ABSPATH')) {
            return true;
        }
        return false;
    }

    /**
     *
     */
    public function __construct()
    {
        $this->_name = 'wordpress';
        if (self::isCurrentlyRunning()) {
            global $wp_version;
            if (isset($wp_version)) {
                $this->_version        = $wp_version;
                $this->_shortversion = '3';
                $this->_javascriptInfo = new RokCommon_Platform_Javascript();
                $this->_javascriptInfo->setName(RokCommon_Platform_Definition::UNKNOWN);
                $this->_javascriptInfo->setVerison(RokCommon_Platform_Definition::UNKNOWN_VERSION);
            }
            $this->populateLoaderChecks();
        } else {
            $this->_version        = RokCommon_Platform_Definition::UNKNOWN_VERSION;
            $this->_javascriptInfo = new RokCommon_Platform_Javascript();
            $this->_javascriptInfo->setName(RokCommon_Platform_Definition::UNKNOWN);
            $this->_javascriptInfo->setVerison(RokCommon_Platform_Definition::UNKNOWN_VERSION);
        }
    }

    public function getOldVersionPlatformId()
    {
        return '3';
    }
}
