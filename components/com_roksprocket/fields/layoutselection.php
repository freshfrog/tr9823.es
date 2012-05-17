<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('JPATH_PLATFORM') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

require_once(dirname(__FILE__) . '/dynamicfields.php');

class JFormFieldLayoutSelection extends JFormFieldDynamicFields
{

	protected static $loaded_icons = array();

	protected $type = 'LayoutSelection';

	/**
	 * Method to get the field options for the list of installed editors.
	 *
	 * @return  array  The field option objects.
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$container = RokCommon_Service::getContainer();

		$fieldname = $this->element['name'];


		$configkey = (string)$this->element['configkey'];

		$options = array();

		$params = $container[$configkey];
		$params = get_object_vars($params);
		ksort($params);

		foreach ($params as $id => $info) {
			if (!in_array($id, self::$loaded_icons)) {
				$layout_composite_path = 'roksprocket_layout_' . $id;
				$priority              = 0;
				foreach ($info->paths as $path) {
					RokCommon_Composite::addPackagePath($layout_composite_path, $path, $priority);
					$priority++;
				}
				$iconurl = RokCommon_Composite::get($layout_composite_path)->getUrl($info->icon);
				if (empty($iconurl)) {
					$iconurl = "components/com_roksprocket/assets/images/default_layout_icon.png";
				}
				$css = sprintf('i.layout.%s {background-image: url(%s);background-position: 0 0;}', $id, $iconurl);
				RokCommon_Header::addInlineStyle($css);
				self::$loaded_icons[] = $id;
			}
			if ($this->value == $id) $selected = ' selected="selected"'; else $selected = "";
			$tmp = JHtml::_('select.option', $id, $info->displayname);
			// Set some option attributes.
			$tmp->attr = array(
				'class'=> $id,
				'rel'  => $fieldname . '_' . $id
			);
			$tmp->icon = $fieldname . ' ' . $id;
			$options[] = $tmp;
		}

		reset($options);
		return $options;
	}
}
