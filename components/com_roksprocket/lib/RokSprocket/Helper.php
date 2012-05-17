<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokSprocket_Helper
{
	public static function getRedirectionOption(){
		$session = JFactory::getSession();
		$option = $session->get('com_roksprocket.redirected.from', 'com_modules');
		$session->set('com_roksprocket.redirected.from', null);
		return $option;
	}
}
