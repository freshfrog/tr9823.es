<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class RokSprocketController extends JController
{
	public function ajax()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		try {
			$container = RokCommon_Service::getContainer();
			foreach ($container['roksprocket.layouts'] as $layout) {
				if (isset($layout->paths) && isset($layout->ajax->dir)) {
					$paths    = $layout->paths;
					$ajax_dir = $layout->ajax->dir;
					foreach ($paths as $priority => $path) {
						$ajax_path = $path . '/' . $ajax_dir;
						if (is_dir($ajax_path)) {
							RokCommon_Ajax::addModelPath($ajax_path, 'RokSprocketSiteLayoutAjaxModel', $priority);
						}
					}
				}
			}

			$model  = $input->get('model', null, 'word');
			$action = $input->get('action', null, 'word');
			if (isset($_REQUEST['params'])) {
				$params = RokCommon_Ajax::smartStripSlashes($_REQUEST['params']);
			}
			echo RokCommon_Ajax::run($model, $action, $params);
		} catch (Exception $e) {
			$result = new RokCommon_Ajax_Result();
			$result->setAsError();
			$result->setMessage($e->getMessage());
			echo json_encode($result);
		}
	}


}
