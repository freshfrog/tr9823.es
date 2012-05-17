<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_Filter_Type_DateHiddenEnabled extends RokCommon_Filter_Type
{
    /**
     * @var string
     */
    protected $type = 'datehiddenenabled';

    public function getChunkRender()
    {
        return $this->getInput();
    }

    public function render($name, $type, $values)
    {
        $value = (isset($values[$type]) ? $values[$type] : '');
        return rc__('ROKCOMMON_FILTER_DATEHIDDENENABLED_RENDER', $this->getInput($name, $value));
    }

    public function getChunkSelectionRender()
    {
        return rc__('ROKCOMMON_FILTER_DATEHIDDENENABLED_RENDER', $this->getTypeDescription());
    }

    public function getInput($name = RokCommon_Filter_Type::JAVASCRIPT_NAME_VARIABLE, $value = '')
    {
        return '<input type="hidden" name="'.$name.'" data-key="' . $this->type .'" value="1"/>';
    }
}
