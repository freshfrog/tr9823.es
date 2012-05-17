<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

class JFormFieldLabel extends JFormField
{
	protected $type = 'Label';
	protected static $css_loaded = false;

	protected function getInput()
	{
		return ' ';
	}

	protected function getLabel()
	{

		$this->_loadAssets();
		$html = array();
		$class = $this->element['class'] ? (string) $this->element['class'] : '';

		$html[] = '<div class="spacer-wrapper '.$class.'">';
		if ((string) $this->element['hr'] == 'true') {
			$html[] = '<hr class="'.$class.'" />';
		}
		else {
			$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
			$text = JText::_($text);

			$class = $this->required == true ? $class.' required' : $class;


			$label = '<h6>'.$text.'</h6>';
			$html[] = $label;
		}
		$html[] = '</div>';
		return implode('',$html);
	}

	protected function getTitle()
	{
		return $this->getLabel();
	}

	public function _loadAssets(){
		if (!self::$css_loaded){
			$type = strtolower($this->type);
			$assets = JURI::root() . 'components/' . JRequest::getString('option') . '/fields/' . $type . '/';

			$css =  $assets . 'css/' . $type . '.css';
			JFactory::getDocument()->addStyleSheet($css);

			self::$css_loaded = true;
		}
	}
}

