<?php
 /**
  * @version   $Id: Unsupported.php 52337 2012-04-09 22:46:49Z btowles $
  * @author    RocketTheme http://www.rockettheme.com
  * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  */

class RokCommon_I18N_Unsupported implements RokCommon_I18N
{

    /**
	 * javascript strings
	 */
	protected static $strings=array();

    /**
     * @param  $string
     * @return string
     */
    public function translateFormatted($string)
    {
        return $string;
    }

    /**
     * @param  $count
     * @param  $string
     * @return string
     */
    public function translatePlural($string, $count)
    {
        return $string;
    }

    /**
     * @param  $string
     * @return string
     */
    public function translate($string)
    {
        return $string;
    }

	/**
	 *
	 * @param $domain
	 * @param $path
	 *
	 * @return bool
	 */
	public function loadLanguageFiles($domain, $path)
	{
		return true;
	}
	
}
	