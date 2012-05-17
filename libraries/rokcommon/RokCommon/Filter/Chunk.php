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
class RokCommon_Filter_Chunk
{
    /**
     * @var string
     * @JSONEncodeIgnore
     */
    protected $id;

    /**
     * @var bool
     */
    protected $selector = false;


    /**
     * @var bool
     */
    protected $root = false;


    /**
     * @var string
     */
    protected $render;

    /**
     * @var string
     */
    protected $javascript;


    /**
     * @var RokCommon_Filter_Type_Option[]
     */
    protected $selections = array();

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
     * @param string $javascript
     */
    public function setJavascript($javascript)
    {
        $this->javascript = $javascript;
    }

    /**
     * @return string
     */
    public function getJavascript()
    {
        return $this->javascript;
    }

    /**
     * @param $selections
     *
     */
    public function setSelections($selections)
    {
        $this->selections = $selections;
    }

    /**
     * @return array|RokCommon_Filter_Type_Options[]
     */
    public function getSelections()
    {
        return $this->selections;
    }

    public function getAsOption()
    {

    }

    /**
     * @param RokCommon_Filter_Chunk_Selection $selection
     */
    public function addSelection(RokCommon_Filter_Chunk_Selection $selection)
    {
        $this->selections[$selection->getId()] = $selection;
    }

    /**
     * @param boolean $parent
     */
    public function setSelector($parent)
    {
        $this->selector = $parent;
    }

    /**
     * @return boolean
     */
    public function isSelector()
    {
        return $this->selector;
    }

    /**
     * @return boolean
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * @return boolean
     */
    public function isParent()
    {
        return $this->selector;
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

    /**
     * @param boolean $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * @return boolean
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @return boolean
     */
    public function isRoot()
    {
        return $this->root;
    }
}
