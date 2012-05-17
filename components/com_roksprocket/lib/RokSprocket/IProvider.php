<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

interface RokSprocket_IProvider
{
	/**
	 * @static
	 * @abstract
	 * @return bool
	 */
	public static function isAvailable();

	/**
	 * @abstract
	 *
	 * @return RokSprocket_ItemCollection
	 */
	public function getItems();


	/**
	 * @abstract
	 *
	 * @param array $filters
	 * @param array $sort_filters
	 */
	public function setFilterChoices($filters, $sort_filters);


	/**
	 * @abstract
	 *
	 * @param $id
	 */
	public function setModuleId($id);

	/**
	 * @abstract
	 *
	 */
	public function getFilterProcessor();

	/**
	 * @abstract
	 *
	 * @param $id
	 * @return RokSprocket_Item
	 */
	public function getArticleInfo($id);

	/**
	 * @abstract
	 *
	 * @param $id
	 *
	 * @return RokSprocket_Item
	 */
	public function getArticlePreview($id);

	/**
	 * @abstract
	 *
	 * @param       $method
	 * @param array $options
	 */
	public function setSortInfo($method, array $options = array());

	/**
	 * @abstract
	 * @static
	 * @return array the array of image type and label
	 */
	public static function getImageTypes();

	/**
	 * @abstract
	 * @static
	 * @return array the array of link types and label
	 */
	public static function getLinkTypes();


	/**
	 * @abstract
	 * @param RokCommon_Registry $params
	 */
	public function setParams(RokCommon_Registry $params);

	/**
	 * @abstract
	 * @param bool $show
	 */
	public function setShowUnpublished($show = false);
}
