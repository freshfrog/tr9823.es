<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokSprocket_Provider_Joomla extends RokSprocket_Provider_AbstarctJoomlaBasedProvider
{
	/**
	 * @static
	 * @return bool
	 */
	public static function isAvailable()
	{
		return true;
	}

	/**
	 * @param array $filters
	 * @param array $sort_filters
	 */
	public function __construct($filters = array(), $sort_filters = array())
	{
		parent::__construct('joomla');
		$this->setFilterChoices($filters, $sort_filters);
	}

	/**
	 * @param     $raw_item
	 * @param int $dborder
	 *
	 * @return \RokSprocket_Item
	 */
	protected function convertRawToItem($raw_item, $dborder = 0)
	{
		require_once (JPath::clean(JPATH_SITE . '/components/com_content/helpers/route.php'));
		require_once (JPath::clean(JPATH_SITE . '/libraries/joomla/html/html/content.php'));

		$item = new RokSprocket_Item();

		$item->setProvider($this->provider_name);
		$item->setId($raw_item->id);
		$item->setAlias($raw_item->alias);
		$item->setAuthor($raw_item->author_name);
		$item->setTitle($raw_item->title);
		$item->setDate($raw_item->created);
		$item->setPublished(($raw_item->state == 1) ? true : false);
		$item->setText($raw_item->introtext);
		$item->setCategory($raw_item->category_title);
		$item->setHits($raw_item->hits);
		$item->setRating($raw_item->rating);
		$item->setMetaKey($raw_item->metakey);
		$item->setMetaDesc($raw_item->metadesc);
		$item->setMetaData($raw_item->metadata);

		$images = array();
		if (isset($raw_item->images) && !empty($raw_item->images)) {
			try {
				$raw_images = RokCommon_JSON::decode($raw_item->images);
				if (isset($raw_images->image_intro) && !empty($raw_images->image_intro)) {
					$image_intro = new RokSprocket_Item_Image();
					$image_intro->setSource($raw_images->image_intro);
					$image_intro->setIdentifier('image_intro');
					$image_intro->setCaption($raw_images->image_intro_caption);
					$image_intro->setAlttext($raw_images->image_intro_alt);
					$images[$image_intro->getIdentifier()] = $image_intro;
				}
				if (isset($raw_images->image_fulltext) && !empty($raw_images->image_fulltext)) {
					$image_fulltext = new RokSprocket_Item_Image();
					$image_fulltext->setSource($raw_images->image_fulltext);
					$image_fulltext->setIdentifier('image_fulltext');
					$image_fulltext->setCaption($raw_images->image_fulltext_caption);
					$image_fulltext->setAlttext($raw_images->image_fulltext_alt);
					$images[$image_fulltext->getIdentifier()] = $image_fulltext;
					$item->setPrimaryImage($image_fulltext);
				}
			} catch (RokCommon_JSON_Exception $jse) {
				$this->container->roksprocket_logger->warning('Unable to decode image JSON for article ' . $item->getArticleId());
			}
			$item->setImages($images);
		}

		$primary_link = new RokSprocket_Item_Link();
		$primary_link->setUrl(JRoute::_(ContentHelperRoute::getArticleRoute($raw_item->id, $raw_item->catid), false));
		$primary_link->getIdentifier('article_link');

		$item->setPrimaryLink($primary_link);


		$links = array();
		if (isset($raw_item->urls) && !empty($raw_item->urls)) {
			try {
				$raw_links = RokCommon_JSON::decode($raw_item->urls);
				if (isset($raw_links->urla)) {
					$linka = new RokSprocket_Item_Link();
					$linka->setUrl($raw_links->urla);
					$linka->setText($raw_links->urlatext);
					$linka->setIdentifier('urla');
					$links[$linka->getIdentifier()] = $linka;

				}
				if (isset($raw_links->urlb)) {
					$linkb = new RokSprocket_Item_Link();
					$linkb->setUrl($raw_links->urlb);
					$linkb->setText($raw_links->urlbtext);
					$linkb->setIdentifier('urlb');
					$links[$linkb->getIdentifier()] = $linkb;
				}
				if (isset($raw_links->urlc)) {
					$linkc = new RokSprocket_Item_Link();
					$linkc->setUrl($raw_links->urlc);
					$linkc->setText($raw_links->urlctext);
					$linkc->setIdentifier('urlc');
					$links[$linkc->getIdentifier()] = $linkc;
				}
				$item->setLinks($links);
			} catch (RokCommon_JSON_Exception $jse) {
				$this->container->roksprocket_logger->warning('Unable to decode link JSON for article ' . $item->getArticleId());
			}
		}

		$item->setDbOrder($dborder);

		// unknown joomla items
		$item->setCommentCount(0);
		$item->setTags(array());

		return $item;
	}

	/**
	 * @param $id
	 *
	 * @return string
	 */
	protected function getArticleEditUrl($id)
	{
		return JURI::root(true) . '/administrator/index.php?option=com_content&task=article.edit&id=' . $id;
	}

	/**
	 * @return array the array of image type and label
	 */
	public static function getImageTypes()
	{
		return array(
			'image_intro'   => array(
				'group'  => null,
				'display'=> 'Article Intro Image'
			),
			'image_fulltext'=> array(
				'group'  => null,
				'display'=> 'Article Full Image'
			)
		);
	}

	/**
	 * @return array the array of link types and label
	 */
	public static function getLinkTypes()
	{
		return array(
			'urla'   => array(
				'group'  => null,
				'display'=> 'Link A'
			),
			'urlb'   => array(
				'group'  => null,
				'display'=> 'Link B'
			),
			'urlc'   => array(
				'group'  => null,
				'display'=> 'Link C'
			)
		);
	}
}
