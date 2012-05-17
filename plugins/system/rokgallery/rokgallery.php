<?php
/**
  * @version   $Id:$
  * @author    RocketTheme http://www.rockettheme.com
  * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  */

defined('_JEXEC') or die('Restricted index access');
jimport('joomla.plugin.plugin');
jimport('joomla.utilities.utility');

class plgSystemRokGallery extends JPlugin {

	function plgSystemRokGallery(&$subject, $config){
		parent::__construct($subject, $config);
	}

	public function onAfterInitialise()
	{

		$app =& JFactory::getApplication();
		$db  =& JFactory::getDBO();

		if (!defined('ROKCOMMON') || !defined('ROKCOMMON_PLUGIN_LOADED')) {
			$error_string = 'RokGallery needs the RokCommon Library and Plug-in installed and enabled.';
		}
		else if (!preg_match('/project.version/',ROKCOMMON) && version_compare(preg_replace('/-SNAPSHOT/','',ROKCOMMON),'2.5', '<'))
		{
			$error_string = 'RokGallery needs at least RokCommon Version 2.5.  You currently have RokCommon Version ' . ROKCOMMON;
		}
		if (!empty($error_string))
		{
			if (JError::$legacy) {
				return JError::raiseWarning(500, $error_string);
			} else {
				throw new Exception($error_string);
			}
		}
		define('ROKGALLERY', '2.10');

	}

	function onBeforeCompileHead()
    {
        $version = new JVersion();

        if ($version->getShortVersion() < '1.5.23')
            return;

        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');
        $app =& JFactory::getApplication();

        if ($option == 'com_rokgallery' && $view == 'gallerypicker' && $app->isSite()){
            $this->_cleanView();
		}
	}

    function _cleanView()
    {
        $path = (JFactory::getApplication()->isSite()) ? JPATH_COMPONENT_ADMINISTRATOR : JPATH_COMPONENT;
        require_once ($path.'/helpers/rokgallery.php');

        RokGalleryHelper::setupGalleryPicker();

		return true;
    }
}