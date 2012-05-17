<?php
/**
 * @version   $Id: Definition.php 48519 2012-02-03 23:18:52Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

interface RokCommon_Platform_Definition
{
    const UNSUPPORTED_DEFINITION = 'unsupported';
    const UNKNOWN = 'unknown';
    const UNKNOWN_VERSION = '0.0.0';

    /**
     * Check to see if this is the current platform running
     * @static
     * @abstract
     * @return bool true if this is the current platform, false if not.
     */
    public static function isCurrentlyRunning();

    /**
     * Get the platform specific version currently running
     * @abstract
     * @return string the current running platform version
     */
    public function getVersion();

    /**
     * @abstract
     * @return array array of possible checks for Platform files/classname extensions
     */
    public function getLoaderChecks();


    /**
     * @abstract
     * @return RokCommon_Platform_Javascript
     */
    public function getJavascriptInfo();

    /**
     * @abstract
     * @return string The platform's name.
     */
    public function getName();

    /**
     * @abstract
     * @return string the short version number
     */
    public function getShortVersion();

    /**
     * @abstract
     * @return string
     */
    public function getOldVersionPlatformId();
}
