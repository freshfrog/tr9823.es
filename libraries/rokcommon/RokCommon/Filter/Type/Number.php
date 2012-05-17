<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_Filter_Type_Number extends RokCommon_Filter_Type
{
    /**
     * @var string
     */
    protected $type = 'number';
    /**
     * @var bool
     */
    protected $isselector = true;

    protected $selection_types = array(
        'equals'        => 'RokCommon_Filter_Type_NumberEntry',
        'greaterthan'   => 'RokCommon_Filter_Type_NumberEntry',
        'lessthan'      => 'RokCommon_Filter_Type_NumberEntry',
        'isnot'         => 'RokCommon_Filter_Type_NumberEntry'
    );

    protected $selection_labels = array(
        'equals'        => 'equals',
        'greaterthan'   => 'greater than',
        'lessthan'      => 'less than',
        'isnot'         => 'is not'
    );

    public function getChunkSelectionRender()
    {
        return rc__('ROKCOMMON_FILTER_NUMBER_RENDER', $this->getTypeDescription()) ;
    }

    public function render($name, $type, $values)
    {
        return rc__('ROKCOMMON_FILTER_NUMBER_RENDER', parent::render($name, $type, $values));
    }
}
