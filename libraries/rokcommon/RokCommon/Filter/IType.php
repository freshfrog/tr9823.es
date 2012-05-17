<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

interface RokCommon_Filter_IType
{
    /**
     * @static
     * @abstract
     *
     * @return RokCommon_Filter_Chunk
     */
    public function getChunks();

    public function getChunkSelections();

    public function getChunkRender();

    public function getChunkSelectionRender();

    public function render($name, $type, $values);

    public function getFieldRender(array $values, $parentname);


}
