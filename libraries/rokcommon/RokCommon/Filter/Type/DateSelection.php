<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_Filter_Type_DateSelection extends RokCommon_Filter_Type
{
    /**
     * @var string
     */
    protected $type = 'dateselection';

    public function getChunkRender()
    {
        return $this->getInputBox();
    }

    public function getChunkSelectionRender()
    {
        return rc__('ROKCOMMON_FILTER_DATESELECTION_RENDER', $this->getTypeDescription());
    }

    public function render($name, $type, $values)
    {
        $value = (isset($values[$type])?$values[$type]:null);
        return rc__('ROKCOMMON_FILTER_DATESELECTION_RENDER', $this->getInputBox($name, $value));
    }

    protected function getInputBox($name = RokCommon_Filter_Type::JAVASCRIPT_NAME_VARIABLE, $value = null)
    {
        if (null == $value) {
            $date  = new RokCommon_Date();
            $value = $date->toFormat('%Y-%m-%d');
        }
        return '<input type="text" name="' . $name . '" value="' . $value . '" class="' . $this->type . '" data-key="' . $this->type .'"/><a href="#" title="Select Date" class="date-picker"><i class="icon tool date"></i></a>';
    }

    /**
     * @return string
     */
    protected function getJavascript($name = self::JAVASCRIPT_NAME_VARIABLE, $value = null)
    {
        $this->javascript = "";
        $this->javascript .= "(function(){";
        $this->javascript .= "RokSprocket.datepicker.attach(%.date-picker !~ input.".$this->type."%, %.date-picker%);";
        $this->javascript .= "});";
        return $this->javascript;
    }

}
