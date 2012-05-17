<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
jimport('joomla.application.module.helper');

class RokSprocket_PlatformHelper_Joomla implements RokSprocket_PlatformHelper
{
	/**
	 * Get the parameters for the passes in module id
	 *
	 * @param $id
	 *
	 * @return RokCommon_Registry
	 */
	public function getModuleParameters($id)
	{
		/** @var $module JTableModule */
		$module = JTable::getInstance('Module', 'JTable', array());
		$module->load($id);
		$params = new RokCommon_Registry($module->params);
		return $params;
	}

	/**
	 * @param $callback
	 * @param $args
	 * @param $params
	 * @param $moduleid
	 *
	 * @return RokSprocket_ItemCollection|bool
	 */
	public function getFromCache($callback, $args, $params, $moduleid)
	{
		$conf = JFactory::getConfig();
		if ($conf->get('caching') && $params->get('module_cache', 1)) {
			$cache = JFactory::getCache('mod_roksprocket');
			$cache->setCaching(true);
			$cache->setLifeTime($params->get('cache_time', 900));
			$user   = JFactory::getUser();
			$levels = $user->getAuthorisedViewLevels();
			$key    = 'mod_roksprocket' . (string)$callback . (string)$args . (string)$params . implode(',', $levels) . '.' . $moduleid;

			$return = $cache->get($callback, $args, $key);
		} else {
			$return = call_user_func_array($callback, $args);
		}
		return $return;
	}

	/**
	 * @param RokSprocket_ItemCollection $items
	 *
	 * @param \RokCommon_Registry        $parameters
	 *
	 * @return RokSprocket_ItemCollection
	 */
	public function processItemsForEvents(RokSprocket_ItemCollection $items, RokCommon_Registry $parameters)
	{
		// process content plugins
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('content');

		$parameters = new JRegistry((string)$parameters);
		/** @var $item RokSprocket_Item */
		foreach ($items as &$item) {
			$article       = new stdClass();
			$article->text = $item->getText();
			$results       = $dispatcher->trigger('onContentPrepare', array(
			                                                               'mod_roksprocket.article',
			                                                               &$article,
			                                                               &$parameters,
			                                                               $item->getOrder()
			                                                          ));
			$item->setText($article->text);
		}
		return $items;
	}

	/**
	 * Gets the cache directory for the platform
	 *
	 * @return string the absolute path to the cache dir
	 */
	public function getCacheDir()
	{
		return JPATH_CACHE.'/mod_roksprocket';
	}

	public function getCacheUrl()
	{
		return 'cache/mod_roksprocket';
	}


}


$app    = JFactory::getApplication();
$input  = $app->input;
$format = $input->get('format', null, 'word');
if ($format == 'raw') {
	/**
	 * JDocument Module renderer
	 *
	 * @package     Joomla.Platform
	 * @subpackage  Document
	 * @since       11.1
	 */
	class JDocumentRendererModule extends JDocumentRenderer
	{
		/**
		 * Renders a module script and returns the results as a string
		 *
		 * @param   string  $module   The name of the module to render
		 * @param   array   $attribs  Associative array of values
		 * @param   string  $content  If present, module information from the buffer will be used
		 *
		 * @return  string  The output of the script
		 *
		 * @since   11.1
		 */
		public function render($module, $attribs = array(), $content = null)
		{
			if (!is_object($module)) {
				$title = isset($attribs['title']) ? $attribs['title'] : null;

				$module = JModuleHelper::getModule($module, $title);

				if (!is_object($module)) {
					if (is_null($content)) {
						return '';
					} else {
						/**
						 * If module isn't found in the database but data has been pushed in the buffer
						 * we want to render it
						 */
						$tmp            = $module;
						$module         = new stdClass;
						$module->params = null;
						$module->module = $tmp;
						$module->id     = 0;
						$module->user   = 0;
					}
				}
			}

			// Get the user and configuration object
			// $user = JFactory::getUser();
			$conf = JFactory::getConfig();

			// Set the module content
			if (!is_null($content)) {
				$module->content = $content;
			}

			// Get module parameters
			$params = new JRegistry;
			$params->loadString($module->params);

			// Use parameters from template
			if (isset($attribs['params'])) {
				$template_params = new JRegistry;
				$template_params->loadString(html_entity_decode($attribs['params'], ENT_COMPAT, 'UTF-8'));
				$params->merge($template_params);
				$module         = clone $module;
				$module->params = (string)$params;
			}

			$contents = '';
			// Default for compatibility purposes. Set cachemode parameter or use JModuleHelper::moduleCache from within the
			// module instead
			$cachemode = $params->get('cachemode', 'oldstatic');

			if ($params->get('cache', 0) == 1 && $conf->get('caching') >= 1 && $cachemode != 'id' && $cachemode != 'safeuri') {

				// Default to itemid creating method and workarounds on
				$cacheparams               = new stdClass;
				$cacheparams->cachemode    = $cachemode;
				$cacheparams->class        = 'JModuleHelper';
				$cacheparams->method       = 'renderModule';
				$cacheparams->methodparams = array($module, $attribs);

				$contents = JModuleHelper::ModuleCache($module, $params, $cacheparams);

			} else {
				$contents = JModuleHelper::renderModule($module, $attribs);
			}

			return $contents;
		}
	}

}