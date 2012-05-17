<?php
/**
 * @version   $Id: File.php 39425 2011-07-04 00:32:54Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokSprocketAdminAjaxModelArticles extends RokCommon_Ajax_AbstractModel
{
	/**
	 * Delete the file and all associated rows (done by foreign keys) and files
	 * $params object should be a json like
	 * <code>
	 * {
	 *  "page": 3,
	 *  "items_per_page":6,
	 *  "module_id": 5,
	 *  "provider":"joomla",
	 *  "filters":  {"1":{"root":{"access":"1"}},"2":{"root":{"author":"43"}}},
	 *  "sortby":"date",
	 *  "get_remaining": true
	 * }
	 * </code>
	 *
	 * @param $params
	 *
	 * @throws #C\Exception|?
	 * @throws RokCommon_Ajax_Exception
	 * @return \RokCommon_Ajax_Result
	 */
	public function getItems($params)
	{
		$result = new RokCommon_Ajax_Result();
		try {
			$html = '';

			$provider_filters  = RokCommon_JSON::decode($params->filters);
			$provider_articles = RokCommon_JSON::decode($params->articles);

			$decoded_sort_parameters = array();
			try {
				$decoded_sort_parameters = RokCommon_Utils_ArrayHelper::fromObject(RokCommon_JSON::decode($params->sort));
			} catch (RokCommon_JSON_Exception $jse) {
				throw new RokCommon_Ajax_Exception('Invalid Sort Parameters passed in.');
			}
			$sort_params  = new RokCommon_Registry($decoded_sort_parameters);
			$sort_filters = RokCommon_Utils_ArrayHelper::fromObject($sort_params->get('rules'));
			$sort_append  = $sort_params->get('append', 'after');
			$sort_type    = $sort_params->get('type');

			$extras = array();
			if (isset($params->extras)) {
				$extras = $params->extras;
			}

			$items             = RokSprocket::getItemsWithFilters($params->module_id, $params->provider, $provider_filters, $provider_articles, $sort_filters, $sort_type, $sort_append, new RokCommon_Registry($extras), false, true);
			$total_items_count = $items->count();
			$page              = $params->page;
			$more              = false;
			$limit             = 10;

			$offset = ($page - 1) * $limit;
			if ($params->load_all) {
				$limit = $total_items_count - $offset;
			}
			$items     = $items->slice($offset, $limit);
			$page      = ((int)$page == 0) ? 1 : $page;
			$next_page = $page;
			if ($total_items_count > $offset + $limit) {
				$more      = true;
				$next_page = $page + 1;
			}
			$order = 0;

			$this->loadLayoutLanguage($params->layout);
			ob_start();
			foreach ($items as $article):
				$per_item_form = $this->getPerItemsForm($params->layout);
				$per_item_form->setFormControl(sprintf('items[%s]', $article->getArticleId()));
				$per_item_form->bind(array('params'=> $article->getParams()));
				echo RokCommon_Composite::get('roksprocket.module.edit')->load('edit_article.php', array(
				                                                                                        'itemform' => $per_item_form,
				                                                                                        'article'  => $article,
				                                                                                        'order'    => $order
				                                                                                   ));
				$order++;
			endforeach;
			$html .= ob_get_clean();


			$result->setPayload(array(
			                         'more'     => $more,
			                         'page'     => $page,
			                         'next_page'=> $next_page,
			                         'amount'   => $total_items_count,
			                         'html'     => $html
			                    ));
		} catch (Exception $e) {

			throw $e;
		}
		return $result;
	}

	/**
	 * Returns the informations related to an article
	 * $params object should be a json like
	 * <code>
	 * {
	 *  "id":"joomla-71"
	 * }
	 * </code>
	 *
	 * @param $params
	 *
	 * @return RokCommon_Ajax_Result
	 */
	public function getInfo($params)
	{
		$result = new RokCommon_Ajax_Result();
		try {
			$html = '';

			list($provider_type, $id) = explode('-', $params->id);

			$container = RokCommon_Service::getContainer();
			//$provider_type = $params->provider;

			/** @var $provider RokSprocket_IProvider */
			$provider_service = $container['roksprocket.providers.registered.' . $provider_type . '.service'];
			$provider         = $container->$provider_service;


			$article = $provider->getArticleInfo($id, true);

			ob_start();
			echo RokCommon_Composite::get('roksprocket.module.edit')->load('edit_article_info_' . $provider_type . '.php', array('article'=> $article));
			$html .= ob_get_clean();

			$result->setPayload(array('html' => $html));
		} catch (Exception $e) {
			throw $e;
		}
		return $result;
	}

	/**
	 * Returns the preview of an article
	 * $params object should be a json like
	 * <code>
	 * {
	 *  "id":"joomla-71"
	 * }
	 * </code>
	 *
	 * @param $params
	 *
	 * @throws #C\Exception|?
	 * @return \RokCommon_Ajax_Result
	 */
	public function getPreview($params)
	{
		$result = new RokCommon_Ajax_Result();
		try {
			$html = '';

			list($provider_type, $id) = explode('-', $params->id);

			$container = RokCommon_Service::getContainer();
			//$provider_type = $params->provider;

			/** @var $provider RokSprocket_IProvider */
			$provider_service = $container['roksprocket.providers.registered.' . $provider_type . '.service'];
			$provider         = $container->$provider_service;

			if (isset($params->extras)) {
				$extras = new RokCommon_Registry($params->extras);
				$provider->setParams($extras);
			}
			$article = $provider->getArticlePreview($id);

			ob_start();
			echo RokCommon_Composite::get('roksprocket.module.edit')->load('edit_article_preview.php', array('article'=> $article));
			$html .= ob_get_clean();

			$result->setPayload(array('html' => $html));
		} catch (Exception $e) {
			throw $e;
		}
		return $result;
	}

	/**
	 * @param $type
	 *
	 * @return RokCommon_Config_Form
	 */
	protected function getPerItemsForm($type)
	{
		JForm::addFieldPath(JPATH_SITE . '/components/com_roksprocket/fields');
		$options   = new RokCommon_Options();
		$container = RokCommon_Service::getContainer();
		// load up the layouts
		$layoutinfo = $container['roksprocket.layouts.' . $type];
		if (isset($layoutinfo->options->peritem)) {
			$section = new RokCommon_Options_Section('peritem_' . $type, $layoutinfo->options->peritem);
			foreach ($layoutinfo->paths as $layoutpath) {
				$section->addPath($layoutpath);
			}
			$options->addSection($section);
		}

		$rcform    = $rcform = new RokCommon_Config_Form(new JForm('roksprocket_peritem'));
		$xml       = $options->getJoinedXml();
		$jxml      = new JXMLElement($xml->asXML());
		$fieldsets = $jxml->xpath('/config/fields[@name = "params"]/fieldset');
		foreach ($fieldsets as $fieldset) {
			$overwrite = ((string)$fieldset['overwrite'] == 'true') ? true : false;
			$rcform->load($fieldset, $overwrite, '/config');
		}
		return $rcform;
	}

	protected function loadLayoutLanguage($layout)
	{
		$container = RokCommon_Service::getContainer();
		/** @var $i18n RokCommon_I18N */
		$i18n              = $container->i18n;
		$layout_lang_paths = $container[sprintf('roksprocket.layouts.%s.paths', $layout)];
		foreach ($layout_lang_paths as $lang_path) {
			@$i18n->loadLanguageFiles('roksprocket_layout_' . $layout, $lang_path);
		}
	}
}
