<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class ModRokSprocket extends RokSprocket
{
	public function __construct(RokCommon_Registry $params)
	{
		parent::__construct($params);
		$this->context_base = self::BASE_PACKAGE_NAME;
		RokCommon_Composite::addPackagePath($this->context_base,JPATH_SITE.'/components/com_roksprocket',10);
		RokCommon_Composite::addPackagePath($this->context_base,JPATH_SITE.'/modules/mod_roksprocket',15);
		RokCommon_Composite::addPackagePath($this->context_base,$this->container['roksprocket.template.override.path'],20);
	}

	/**
	 * @return RokSprocket_ItemCollection
	 */
	public function getData()
	{
		$container = RokCommon_Service::getContainer();
		/** @var $platformHelper RokSprocket_PlatformHelper */
		$platformHelper = $container->roksprocket_platformhelper;
		$items = $platformHelper->getFromCache(array($this, '_realGetData'), array(), $this->params, $this->params->get('module_id',0));

		// get the data to present to the layout
		$provider_type = $this->params->get('provider', 'joomla');
		$sort_type         = $this->params->get($provider_type . '_sort', 'automatic');
		if ($sort_type == RokSprocket_ItemCollection::SORT_METHOD_RANDOM)
		{
			$items->sort($sort_type);
		}
		$items = $platformHelper->processItemsForEvents($items, $this->params);
		return $items;
	}

	public function _realGetData(){
		return parent::getData();
	}


}
