<?php
/**
 * @version   $Id: I18N.php 52337 2012-04-09 22:46:49Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// No direct access
defined('ROKCOMMON') or die;

/**
 *
 */
interface RokCommon_I18N
{
    /**
     * @abstract
     * @param  $string
     * @return string
     */
    public function translate($string);

	/**
	 * @abstract
	 *
	 * @param  $count
	 * @param  $string
	 *
	 * @internal param $multistring
	 * @return string
	 */
    public function translatePlural($count, $string);

    /**
     * @abstract
     * @param  $string
     * @param  mixed    Mixed number of arguments for the sprintf function.
     * @return string
     */
    public function translateFormatted($string);

	/**
	 * @abstract
	 *
	 * @param $domain
	 * @param $path
	 * @return mixed
	 */
	public function loadLanguageFiles($domain, $path);
}

