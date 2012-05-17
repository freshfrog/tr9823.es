<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokSprocket_Layout_Lists extends RokSprocket_AbstractLayout
{
	/**
	 * @var bool
	 */
	protected static $instanceHeadersRendered = false;

	/**
	 * @var string
	 */
	protected $name = 'lists';


	protected function cleanItemParams()
	{
		foreach ($this->items as $item_id => &$item) {

			$item->setPrimaryImage($this->setupImage($item, 'lists_image_default', 'lists_image_default_custom', 'lists_item_image'));
			$item->setPrimaryLink($this->setupLink($item,'lists_link_default','lists_link_default_custom', 'lists_item_link'));
			$item->setTitle($this->setupText($item,'lists_title_default',false,'lists_item_title'));

			// clean for accordion/non-accordion mode

			$empty_title = !$item->getTitle() || !strlen($item->getTitle());
			if ($empty_title) $item->setTitle('&nbsp;');
			$item->custom_can_show_title = 1;
			$item->custom_can_have_link = 0;

			if (!$this->parameters->get('lists_enable_accordion')){
				if ($empty_title) $item->custom_can_show_title = 0;
			}

			if (!$this->parameters->get('lists_enable_accordion') && $item->getPrimaryLink()){
				$item->custom_can_have_link = 1;
			}

			// clean from tags and limit words amount
			$words_amount = $this->parameters->get('lists_previews_length', 20);
			$stripped = strip_tags($item->getText());
			$preview = $this->_getWords($stripped, $words_amount);

			if (strlen($stripped) != strlen($preview)) $item->setText($preview . "…");

			// resizing images if needed
			if ($item->getPrimaryImage() && $this->parameters->get('lists_resize_enable', false)){
				$width = $this->parameters->get('lists_resize_width', 0);
				$height = $this->parameters->get('lists_resize_height', 0);
				$item->getPrimaryImage()->resize($width, $height);
			}
		}
	}

	/**
	 * @return RokSprocket_ItemCollection
	 */
	public function getItems()
	{
		return $this->items;
	}


	/**
	 * @return RokCommon_Composite_Context
	 */
	public function getThemeContent()
	{
		return $this->theme_context;
	}
	/**
	 * @return bool|string
	 */
	public function renderBody()
	{

		$theme_basefile = $this->container[sprintf('roksprocket.layouts.%s.themes.%s.basefile', $this->name, $this->theme)];
		$items = $this->items->slice(0, $this->parameters->get('lists_items_per_page', 5));
		$pages = ceil($this->items->count() / count($items));

		return $this->theme_context->load($theme_basefile, array(
		                                                  'layout'    => $this,
		                                                  'items'     => $items,
		                                                  'pages'	  => $pages,
		                                                  'parameters'=> $this->parameters
		                                             ));
	}

	/**
	 * Called to render headers that should be included on a per module instance basis
	 */
	public function renderInstanceHeaders()
	{
		RokCommon_Header::addScript($this->theme_context->getUrl('lists.js'));
		RokCommon_Header::addStyle($this->theme_context->getUrl('lists.css'));

		$id						= $this->parameters->get('module_id');
		$settings				= new stdClass();
		$settings->accordion 	= $this->parameters->get('lists_enable_accordion', 1);
		$settings->autoplay  	= $this->parameters->get('lists_autoplay', 0);
		$settings->delay	 	= $this->parameters->get('lists_autoplay_delay', 5);
		$options				= json_encode($settings);

		$js   = array();
		$js[] = "window.addEvent('domready', function(){";
		$js[] = "	RokSprocket.instances.lists.attach(" . $id . ", '" . $options . "');";
		$js[] = "});";
		RokCommon_Header::addInlineScript(implode("\n", $js)."\n");
	}

	/**
	 * Called to render headers that should be included only once per Layout type used
	 */
	public function renderLayoutHeaders()
	{
		if (!self::$instanceHeadersRendered) {

			$root_assets = RokCommon_Composite::get($this->basePackage . '.assets.js');
			RokCommon_Header::addScript($root_assets->getUrl('roksprocket.request.js'));

			$instance   = array();
			$instance[] = "window.addEvent('domready', function(){";
			$instance[] = "		RokSprocket.instances.lists = new RokSprocket.Lists();";
			$instance[] = "});";

			RokCommon_Header::addInlineScript(implode("\n", $instance)."\n");

			self::$instanceHeadersRendered = true;
		}
	}

	public function _getWords($string, $amount = false)
	{
		if (!$amount) $amount = strlen($string);
		$words = explode(' ', $string, ($amount + 1));
		if(count($words) > $amount) array_pop($words);

		return implode(' ', $words);
	}
}
