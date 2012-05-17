<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

abstract class RokSprocket_Provider_AbstarctJoomlaBasedProvider extends RokSprocket_Provider
{
	/**
	 * @return RokSprocket_ItemCollection
	 */
	public function getItems()
	{

		/** @var $filer_processor RokCommon_Filter_IProcessor */
		$filer_processor = $this->getFilterProcessor();
		$filer_processor->process($this->filters, $this->sort_filters, $this->showUnpublished);

		$query = $filer_processor->getQuery();
		$db    = JFactory::getDbo();
		$db->setQuery($query);
		$db->query();
		if ($error = $db->getErrorMsg()) {
			throw new RokSprocket_Exception($error);
		}
		$raw_results = $db->loadObjectList();


		$converted = $this->convertRawToItems($raw_results);
		$this->mapPerItemData($converted);
		return $converted;
	}

	/**
	 * @param array $data
	 *
	 * @return RokSprocket_ItemCollection
	 */
	protected function convertRawToItems(array $data)
	{
		$collection = new RokSprocket_ItemCollection();
		$dborder    = 0;
		foreach ($data as $raw_item) {
			$item                              = $this->convertRawToItem($raw_item, $dborder);
			$collection[$item->getArticleId()] = $item;
			$dborder++;
		}
		return $collection;
	}

	/**
	 * @abstract
	 *
	 * @param     $raw_item
	 * @param int $dborder
	 */
	abstract protected function convertRawToItem($raw_item, $dborder = 0);

	/**
	 * @param RokSprocket_ItemCollection $items
	 *
	 * @throws RokSprocket_Exception
	 */
	protected function getModuleItemSettings(RokSprocket_ItemCollection &$items)
	{
		//TODO move this to be a platform independent fucntion
		$item_ids = array_keys($items);
		$db       = JFactory::getDbo();
		$query    = $db->getQuery(true);
		$query->select('rsi.provider_id as id, rsi.order as order, rsi.params as params')->from('#__roksprocket_items as rsi');
		$query->where(sprintf('rsi.module_id = %d', $this->module_id));
		$query->where(sprintf('rsi.provider = %s', $db->quote($this->provider_name)));
		$query->where(sprintf('rsi.provider_id in (%s)', implode(',', $item_ids)));
		$query->order('rsi.order');
		$db->setQuery($query);
		$db->query();
		if ($error = $db->getErrorMsg()) {
			throw new RokSprocket_Exception($error);
		}
		$item_results = $db->loadObjectList('id');

		foreach ($item_results as $item_id => $item) {
			if (isset($items[$item_id])) {

			}
		}
	}

	/**
	 * @param RokSprocket_ItemCollection $items
	 *
	 * @throws RokSprocket_Exception
	 */
	protected function mapPerItemData(RokSprocket_ItemCollection &$items)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('i.provider_id as id, i.order, i.params')->from('#__roksprocket_items as i');
		$query->where('i.module_id = ' . $db->quote($this->module_id));
		$query->where('i.provider = ' . $db->quote($this->provider_name));
		$db->setQuery($query);
		$db->query();
		if ($error = $db->getErrorMsg()) {
			throw new RokSprocket_Exception($error);
		}
		$sprocket_items = $db->loadObjectList('id');

		/** @var $items RokSprocket_Item[] */
		foreach ($items as $item_id => &$item) {
			list($provider, $id) = explode('-', $item_id);
			if (array_key_exists($id, $sprocket_items)) {
				$items[$item_id]->setOrder((int)$sprocket_items[$id]->order);
				if (null != $sprocket_items[$id]->params) {
					$decoded = null;
					try {
						$decoded = RokCommon_Utils_ArrayHelper::fromObject(RokCommon_JSON::decode($sprocket_items[$id]->params));
					} catch (RokCommon_JSON_Exception $jse) {
						//TODO log that unable to get per item settings
					}
					$items[$item_id]->setParams($decoded);
				} else {
					$items[$item_id]->setParams(array());
				}
			}
		}
	}

	/**
	 * @param $id
	 *
	 * @return \RokSprocket_Item
	 */
	public function getArticlePreview($id)
	{
		$ret = $this->getArticleInfo($id);
		$ret->setText($this->_cleanPreview($ret->getText()));
		return $ret;
	}

	/**
	 * @param      $id
	 *
	 * @param bool $raw return the raw object not the RokSprocket_Item
	 *
	 * @return stdClass|RokSprocket_Item
	 * @throws RokSprocket_Exception
	 */
	public function getArticleInfo($id, $raw = false)
	{
		/** @var $filer_processor RokCommon_Filter_IProcessor */
		$filer_processor = $this->getFilterProcessor();
		$filer_processor->process(array('id'=> array($id)), array(), true);
		$query = $filer_processor->getQuery();
		$db    = JFactory::getDbo();
        $myvar = (string)$query;
		$db->setQuery($query);
		$db->query();
		if ($error = $db->getErrorMsg()) {
			throw new RokSprocket_Exception($error);
		}
		$ret = $db->loadObject();
		if ($raw) {
            $ret->preview = $this->_cleanPreview($ret->introtext);
            $ret->editUrl = $this->getArticleEditUrl($id);
			return $ret;
		} else {
			$item          = $this->convertRawToItem($ret);
			$item->editUrl = $this->getArticleEditUrl($id);
			$item->preview = $this->_cleanPreview($item->getText());
			return $item;
		}
	}

	/**
	 * @abstract
	 *
	 * @param $id
	 */
	abstract protected function getArticleEditUrl($id);

	/**
	 * @param $content
	 *
	 * @return mixed
	 */
	protected function _cleanPreview($content)
	{
		//Replace src links
		$base = JURI::root();

		$regex   = '#href="index.php\?([^"]*)#m';
		$content = preg_replace_callback($regex, array('self', '_route'), $content);

		$protocols = '[a-zA-Z0-9]+:'; //To check for all unknown protocals (a protocol must contain at least one alpahnumeric fillowed by :
		$regex     = '#(src|href)="(?!/|' . $protocols . '|\#|\')([^"]*)"#m';
		$content   = preg_replace($regex, "$1=\"$base\$2\" target=\"_blank\"", $content);

		$regex   = '#(onclick="window.open\(\')(?!/|' . $protocols . '|\#)([^/]+[^\']*?\')#m';
		$content = preg_replace($regex, '$1' . $base . '$2', $content);

		// ONMOUSEOVER / ONMOUSEOUT
		$regex   = '#(onmouseover|onmouseout)="this.src=([\']+)(?!/|' . $protocols . '|\#|\')([^"]+)"#m';
		$content = preg_replace($regex, '$1="this.src=$2' . $base . '$3$4"', $content);

		// Background image
		$regex   = '#style\s*=\s*[\'\"](.*):\s*url\s*\([\'\"]?(?!/|' . $protocols . '|\#)([^\)\'\"]+)[\'\"]?\)#m';
		$content = preg_replace($regex, 'style="$1: url(\'' . $base . '$2$3\')', $content);

		return $content;
	}

	/**
	 * @param $matches
	 *
	 * @return string
	 */
	protected function _route(&$matches)
	{
		$original = $matches[0];
		$url      = $matches[1];
		$url      = str_replace('&amp;', '&', $url);
		$route    = JURI::root() . 'index.php?' . $url;

		return 'target="_blank" href="' . $route;
	}
}
