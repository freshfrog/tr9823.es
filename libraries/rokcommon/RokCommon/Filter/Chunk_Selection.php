<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

/**
 * @JSONDefaultKey('id')
 */
class RokCommon_Filter_Chunk_Selection
{
    public function __construct($id, $render)
    {
        $this->id = $id;
        $this->render = $render;
    }

    /**
     * @var string
     * @JSONEncodeIgnore
     */
    protected $id;

    /**
     * @var string
     */
    protected $render;


    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $render
     */
    public function setRender($render)
    {
        $this->render = $render;
    }

    /**
     * @return string
     */
    public function getRender()
    {
        return $this->render;
    }
}
