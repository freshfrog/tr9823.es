<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_HTML_Select implements RokCommon_HTML_ISelect
{
    /**
     * @abstract
     *
     * @param string                         $name
     * @param RokCommon_HTML_Select_Option[] $options
     * @param array                          $attribs
     *
     * @return string the html rendered select list
     */
    public function getList($name, array $options = array(), $attribs = array())
    {
        $html = array();
        $attribs_html = array();
        foreach ($attribs as $attrib_name => $attrib_value) {
            $attribs_html[] = $attrib_name . '="' . $attrib_value . '"';
        }
        $attribs_html = implode(' ', $attribs_html);

        $html[] = '<select name="' . $name . '" ' . $attribs_html . '>';
        foreach ($options as $option) {
            $html[] = $option->getHTML();
        }
        $html[] = '</select>';
        return implode('', $html);
    }
}
