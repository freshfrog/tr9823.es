<?php
 /**
  * @version   $Id: controller.php 39359 2011-07-02 19:02:14Z btowles $
  * @author    RocketTheme http://www.rockettheme.com
  * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');



/**
 * rokgallery Component Controller
 */
class RokGalleryController extends JController {

    function __construct($config = array())
	{
		// RokGallery image picker proxying:
		if (JRequest::getCmd('view') === 'gallerypicker') {
			$config['base_path'] = JPATH_COMPONENT_ADMINISTRATOR;
            RokCommon_Composite::addPackagePath('rokgallery',JPATH_COMPONENT_ADMINISTRATOR.'/templates');
		}

		parent::__construct($config);
	}
    
    function display() {
        // Make sure we have a default view
        if( !JRequest::getVar( 'view' )) {
		    JRequest::setVar('view', 'gallery' );
        }
		parent::display();
	}

    public function ajax()
    {
        try
        {
            RokCommon_Ajax::addModelPath(JPATH_SITE . '/components/com_rokgallery/lib/RokGallery/Site/Ajax/Model', 'RokGallerySiteAjaxModel');
            $model = JRequest::getString('model');
            $action = JRequest::getString('action');
            $params = JRequest::getString('params');

            echo RokCommon_Ajax::run($model, $action, $params);
        }
        catch (Exception $e)
        {
            $result = new RokCommon_Ajax_Result();
            $result->setAsError();
            $result->setMessage($e->getMessage());
            echo json_encode($result);
        }
    }
}
