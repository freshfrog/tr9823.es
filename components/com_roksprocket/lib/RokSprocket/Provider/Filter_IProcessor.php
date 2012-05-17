<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

interface RokSprocket_Provider_Filter_IProcessor extends RokCommon_Filter_IProcessor
{
	/**
	 * @abstract
	 *
	 */
	public function getQuery();
}
