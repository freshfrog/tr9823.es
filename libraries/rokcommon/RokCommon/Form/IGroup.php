<?php
/**
 * @version   $Id: IGroup.php 48519 2012-02-03 23:18:52Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

interface RokCommon_Form_IGroup extends RokCommon_Form_IItem
{
    /**
     * @abstract
     *
     * @param string $prelabel_function
     * @param string $postlabel_function
     */
    public function setLabelWrapperFunctions($prelabel_function = null, $postlabel_function = null);

    /**
     * @abstract
     *
     * @param string $field
     */
    public function preLabel($field);

    /**
     * @abstract
     *
     * @param string $field
     */
    public function postLabel($field);
}
