<?php
/**
 * ContentType, Custom Param
 *
 * @package RocketTheme
 * @subpackage rokstories.elements
 * @version   1.7 March 22, 2012
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html');
jimport('joomla.form.formfield');
JHTML::_('behavior.mootools');

class JFormFieldContentType extends JFormField
{
    protected $type = 'contenttype';

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

        $options = array();
        // build the html list for content type
        $options[] = JHTML::_('select.option', 'joomla', 'Joomla Core Content', 'value', 'text');
        $options[] = JHTML::_('select.option', 'k2', 'K2 Content', 'value', 'text');

        if (K2_CHECK) {
            $js = "window.addEvent('domready', function() {
                $$('.ifk2').getParent('li').setStyle('display','block');
                var start = '" . $this->value . "';
                $$('.source').getParent('li').setStyle('display','none');
                $$('.'+start).getParent('li').setStyle('display','block');
                $('" . $this->id . "').addEvent('change', function(){
                    var sel = this.getSelected().get('value');
                    $$('.source').getParent('li').setStyle('display','none');
                    $$('.'+sel).getParent('li').setStyle('display','block');
                }).fireEvent('change');
        });";
        }
        else {
            $js = "window.addEvent('domready', function() {
                $('" . $this->id . "').set('value', 'joomla');
                $('" . $this->id . "').getParent('li').setStyle('display','none');
                $$('.source').getParent('li').setStyle('display','none');
                $$('.joomla').getParent('li').setStyle('display','block');
            });";
        }

        $doc->addScriptDeclaration($js);

        return JHTML::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
    }
}

?>


