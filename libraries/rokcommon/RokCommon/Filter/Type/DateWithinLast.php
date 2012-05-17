<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_Filter_Type_DateWithinLast extends RokCommon_Filter_Type
{
    /**
     * @var string
     */
    protected $type = 'datewithinlast';

    /**
     * @var RokCommon_Filter_Type_DateEntry
     */
    protected $dateentry;

    /**
     * @var RokCommon_Filter_Type_DateRange
     */
    protected $daterange;

    /**
     * @param null|SimpleXMLElement $xmlnode
     */
    public function __construct(SimpleXMLElement &$xmlnode = null, $renderer = null)
    {
        parent::__construct($xmlnode, $renderer);
        $this->dateentry = new RokCommon_Filter_Type_DateEntry($this->xmlnode);
        $this->daterange = new RokCommon_Filter_Type_DateRange($this->xmlnode);

    }

    /**
     * @return RokCommon_Filter_Chunk[]
     */
    public function getChunks()
    {
        $chunks = parent::getChunks();
        $chunks = array_merge($chunks, $this->dateentry->getChunks());
        $chunks = array_merge($chunks, $this->daterange->getChunks());
        return $chunks;
    }

    public function getChunkRender()
    {
        return '';
    }

    public function getChunkSelectionRender()
    {
        return rc__('ROKCOMMON_FILTER_DATEWITHINLAST_RENDER', $this->dateentry->getTypeDescription(null, 'value'), $this->daterange->getTypeDescription(null, 'range'));
    }

    public function render($name, $type, $values)
    {
        $subvalues = $values[$type];
        return rc__('ROKCOMMON_FILTER_DATEWITHINLAST_RENDER', $this->dateentry->render($name . '[value]', 'value', $subvalues), $this->daterange->render($name . '[range]', 'range', $subvalues));
    }
}
