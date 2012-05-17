<?php
/**
 * @version   $Id: functions.php 50618 2012-03-07 05:22:04Z djamil $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

if (!defined('ROKCOMMON_FUNCTIONS')) {

	define('ROKCOMMON_FUNCTIONS', __FILE__);

	/**
	 * @param  $string
	 *
	 * @return string
	 */
	function rc__($string)
	{
		try {
			$container = RokCommon_Service::getContainer();
			$i18n      = $container->i18n;
			$args      = func_get_args();
			if (count($args) == 1) {
				return call_user_func_array(array($i18n, 'translate'), $args);
			} else {
				return call_user_func_array(array($i18n, 'translateFormatted'), $args);
			}
		} catch (RokCommon_Loader_Exception $le) {
			//TODO: log a failure to load a translation driver
			return $string;
		}
	}

	/**
	 * @param $string
	 */
	function rc_e($string)
	{
		$args = func_get_args();
		$out  = call_user_func_array('rc__', $args);
		echo $out;
	}

	/**
	 * @param $string
	 * @param $n
	 *
	 * @return string
	 */
	function rc_n($string, $n)
	{
		try {
			$container = RokCommon_Service::getContainer();
			$i18n      = $container->i18n;
			$args      = func_get_args();
			return call_user_func_array(array($i18n, 'translatePlural'), $args);
		} catch (RokCommon_Loader_Exception $le) {
			//TODO: log a failure to load a translation driver
			return $string;
		}
	}

	/**
	 * @param $string
	 * @param $n
	 */
	function rc_ne($string, $n)
	{
		echo rc_n($string, $n);
	}

}
