<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_Filter_Type_Text extends RokCommon_Filter_Type
{
    /**
     * @var string
     */
    protected $type = 'text';
    /**
     * @var bool
     */
    protected $isselector = true;

    protected $selection_types = array(
//        'matches'    => 'RokCommon_Filter_Type_TextEntry',
        'contains'   => 'RokCommon_Filter_Type_TextEntry',
        'beginswith' => 'RokCommon_Filter_Type_TextEntry',
        'endswith'   => 'RokCommon_Filter_Type_TextEntry',
        'is'         => 'RokCommon_Filter_Type_TextEntry'
    );

    protected $selection_labels = array(
//        'matches'    => 'matches',
        'contains'   => 'contains',
        'beginswith' => 'begins with',
        'endswith'   => 'ends with',
        'is'         => 'is'
    );

    public function getChunkSelectionRender()
    {
        return rc__('ROKCOMMON_FILTER_TEXT_RENDER', $this->getTypeDescription()) ;
    }

    public function render($name, $type, $values)
    {
        return rc__('ROKCOMMON_FILTER_TEXT_RENDER', parent::render($name, $type, $values));
    }
}
