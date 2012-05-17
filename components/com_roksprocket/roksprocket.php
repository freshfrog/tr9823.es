<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// no direct access
defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JTable::addIncludePath(JPATH_SITE.'/components/com_roksprocket/tables');
RokCommon_Composite::addPackagePath(RokSprocket::BASE_PACKAGE_NAME,dirname(__FILE__));

$controller = JController::getInstance('RokSprocket');
$app        = JFactory::getApplication();
$input      = $app->input;
$task       = $input->get('task', null, 'CMD');
$controller->execute($task);
$controller->redirect();
