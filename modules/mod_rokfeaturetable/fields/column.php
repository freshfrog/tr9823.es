<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 *
 */
class JFormFieldColumn extends JFormField
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Column';

    /**
     * @var string
     */
    protected $type = 'Column';

    /**
     * @return string
     */
    public function getInput()
	{
		$rows = (string) $this->element['rows'];
		$cols = (string) $this->element['cols'];
		$class = ( $this->element['class'] ? 'class="'.(string) $this->element['class'].'"' : 'class="text_area"' );
		// convert <br /> tags so they are not visible when editing
		//$value = str_replace('<br />', "\n", $value);
		return "";
		return '<textarea name="'.$this->name.'" cols="'.$cols.'" rows="'.$rows.'" '.$class.' id="'.$this->id.'" >'.$this->value.'</textarea>';
	}
}