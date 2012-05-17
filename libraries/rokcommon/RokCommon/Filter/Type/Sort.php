<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_Filter_Type_Sort extends RokCommon_Filter_Type
{
    /**
     * @var string
     */
    protected $type = 'sort';

    protected $select_options = array(
        'ascending'=> 'ascending',
        'descending' => 'descending'
    );

    public function getChunkRender()
    {
        return $this->getSelectList();
    }

    protected function getSelectList($name = RokCommon_Filter_Type::JAVASCRIPT_NAME_VARIABLE, $value = null)
    {
        $options = array();
        $attribs = array('class'=> $this->type, 'data-key'=>$this->type);
        foreach ($this->select_options as $select_option_value => $select_option_label) {
            $option    = new RokCommon_HTML_Select_Option($select_option_value, $select_option_label, $value == $select_option_value);
            $options[] = $option;
        }
        $service = $this->selectRenderer;
        /** @var $renderer RokCommon_HTML_ISelect */
        $renderer = $this->container->$service;
        return $renderer->getList($name, $options, $attribs);
    }

    public function getChunkSelectionRender()
    {
        return rc__('ROKCOMMON_FILTER_SORT_RENDER', $this->getTypeDescription()) ;
    }

    public function render($name, $type, $values)
    {
        $value = (isset($values[$type]) ? $values[$type] : null);
        return rc__('ROKCOMMON_FILTER_SORT_RENDER',$this->getSelectList($name, $value));
    }
}
