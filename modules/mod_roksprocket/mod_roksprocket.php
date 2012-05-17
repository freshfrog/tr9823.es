<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// no direct access
defined('_JEXEC') or die;
try {
	if (defined('ROKSPROCKET')) {

		$lang = JFactory::getLanguage();
		$lang->load('com_roksprocket', JPATH_BASE, null, false, false)
		|| $lang->load('com_roksprocket', JPATH_SITE.'/components/com_roksprocket', null, false, false)
		|| $lang->load('com_roksprocket', JPATH_BASE, $lang->getDefault(), false, false)
		|| $lang->load('com_roksprocket', JPATH_SITE.'/components/com_roksprocket', $lang->getDefault(), false, false);

		RokCommon_ClassLoader::addPath(dirname(__FILE__) . '/lib');

		$containter = RokCommon_Service::getContainer();

		/** @var $logger logger */
		$logger            = $containter->logger;
		$module_parameters = RokCommon_Registry_Converter::convert($params);
		$module_parameters->set('module_id', $module->id);
		$roksprocket = new ModRokSprocket($module_parameters);
		$items       = $roksprocket->getData();
		echo $content_items = $roksprocket->render($items);

	}
} catch (Exception $e) {
	JError::raiseWarning(100, $e->getMessage());
}