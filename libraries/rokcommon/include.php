<?php
/**
 * @version   $Id: include.php 48519 2012-02-03 23:18:52Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
if (!defined('ROKCOMMON')) {
    define('ROKCOMMON_ROOT_PATH', dirname(__FILE__));
    if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
    if (($loaderrors = require_once(ROKCOMMON_ROOT_PATH . '/requirements.php')) !== true) {
        return $loaderrors;
    }
    define('ROKCOMMON', '2.6.1');

    // Bootstrap the base classloader and overrides
    require_once(ROKCOMMON_ROOT_PATH . '/RokCommon/ClassLoader.php');
    RokCommon_ClassLoader::addPath(ROKCOMMON_ROOT_PATH . '/Overrides');

    // load up the supporting functions
    require_once(ROKCOMMON_ROOT_PATH . '/functions.php');
}
return "ROKCOMMON_LIB_INCLUDED";
