<?php
/**
 * ContentType, Custom Param
 *
 * @package RocketTheme
 * @subpackage rokstories.elements
 * @version   1.9 March 20, 2012
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html');
jimport('joomla.form.formfield');
JHTML::_('behavior.mootools');

class JFormFieldSelectAll extends JFormField
{
    protected $type = 'selectall';

    protected function getInput()
    {
        $doc =& JFactory::getDocument();

        // Initialize some field attributes.
        $attr = '';
        $attr .= $this->element['class'] ? ' class="' . (string)$this->element['class'] . '"' : '';
        // To avoid user's confusion, readonly="true" should imply disabled="true".
        if ((string)$this->element['readonly'] == 'true' || (string)$this->element['disabled'] == 'true') {
            $attr .= ' disabled="disabled"';
        }
        $attr .= $this->element['size'] ? ' size="' . (int)$this->element['size'] . '"' : '';
        $attr .= $this->element['multiple'] ? ' multiple="multiple"' : '';
        // Initialize JavaScript field attributes.
        $attr .= $this->element['onchange'] ? ' onchange="' . (string)$this->element['onchange'] . '"' : '';

        $select1 = $this->element['select'] ? 'jform_params_'.$this->element['select'] : '';
        $select2 = $this->element['select'] ? 'jformparams'.$this->element['select'] : '';

        $options = array();
        // build the html list for select
        $options[] = JHTML::_('select.option', '0', 'Select');
        $options[] = JHTML::_('select.option', '1', 'All');

        $js = "window.addEvent('domready', function() {
            if (!'" . $this->id . "0') return;
            $('" . $this->id . "1').addEvent('click', function(){
                $$('select#" . $select1 . " option', 'select#" . $select2 . " option').each(function(el) {
                    el.setProperty('selected', 'selected');
                });
            })

            $('" . $this->id . "0').addEvent('click', function(){
                $$('select#" . $select1 . " option', 'select#" . $select2 . " option').each(function(el) {
                    el.removeProperty('selected');
                });
            })

            if ($('" . $this->id . "1').checked) {
                $$('select#" . $select1 . " option', 'select#" . $select2 . " option').each(function(el) {
                    el.setProperty('selected', 'selected');
                });
            }
        });

		";

        $css ="
        label.radiobtn {clear:none !important; min-width:60px !important;}
        ";

        $doc->addScriptDeclaration($js);
        $doc->addStyleDeclaration($css);
        return JHTML::_('select.radiolist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
    }
}

?>


