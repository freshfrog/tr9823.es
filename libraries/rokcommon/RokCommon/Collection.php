<?php
/**
 * @version   $Id: Collection.php 50274 2012-03-04 06:18:37Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('ROKCOMMON') or die;

class RokCommon_Collection implements IteratorAggregate, ArrayAccess, Countable
{
    protected $items = array();

    public function __construct()
    {

    }

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    public function offsetExists($offset)
    {
        return isset($this->items);
    }

    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function count()
    {
        return count($this->items);
    }

    public function slice($offset = 0, $length = null)
    {
        if ($length === null) $length = $this->count();
	    $classtype = get_class($this);
	    $output = new $classtype();
	    $slices =array_slice($this->items, $offset, $length, true);
        foreach ( $slices as $sliced_item_id => &$sliced_item){
	        $output[$sliced_item_id] = $sliced_item;
        }
	    return $output;
    }

	public function trim($length)
	{
		return $this->slice(0, $length);
	}
}
