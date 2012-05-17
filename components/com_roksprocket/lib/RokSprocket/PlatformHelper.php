<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

interface RokSprocket_PlatformHelper
{
	/**
	 * Get the parameters for the passes in module id
	 * @abstract
	 *
	 * @param $id
	 *
	 * @return RokCommon_Registry
	 */
	public function getModuleParameters($id);

	/**
	 * @abstract
	 *
	 * @param RokSprocket_ItemCollection $items
	 *
	 * @param \RokCommon_Registry        $parameters
	 *
	 * @return RokSprocket_ItemCollection
	 */
	public function processItemsForEvents(RokSprocket_ItemCollection $items, RokCommon_Registry $parameters);


	/**
	 * @abstract
	 *
	 * @param $callback
	 * @param $args
	 * @param $params
	 * @param $moduleid
	 *
	 * @return RokSprocket_ItemCollection|bool
	 */
	public function getFromCache($callback, $args, $params, $moduleid);

	/**
	 * Gets the cache directory for the platform
	 *
	 * @abstract
	 * @return string the absolute path to the cache dir
	 */
	public function getCacheDir();
	public function getCacheUrl();
}
