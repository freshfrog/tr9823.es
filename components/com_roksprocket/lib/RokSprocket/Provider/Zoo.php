<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokSprocket_Provider_Zoo extends RokSprocket_Provider_AbstarctJoomlaBasedProvider
{
    /**
     * @return RokSprocket_ItemCollection
     */
    public function getItems()
    {
        if ($this->params->exists('zoo_application_type')) {
            $this->filters['zoo_application_type'][] = $this->params->get('zoo_application_type');
        }
        return parent::getItems();
    }

    /**
     * @static
     * @return bool
     */
    public static function isAvailable()
    {
        if (!class_exists('JFactory')) {
            return false;
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('a.extension_id');
        $query->from('#__extensions AS a');
        $query->where('a.type = "component"');
        $query->where('a.element = "com_zoo"');
        $query->where('a.enabled = 1');

        $db->setQuery($query);

        if ($db->loadResult()) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param array $filters
     * @param array $sort_filters
     */
    public function __construct($filters = array(), $sort_filters = array())
    {
        parent::__construct('zoo');
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
        $app_type = $this->params->get('zoo_application_type');
        $textfield = $this->params->get('zoo_articletext_field');

        $item = new RokSprocket_Item();

        $item->setProvider($this->provider_name);
        $item->setId($raw_item->id);
        $item->setAlias($raw_item->alias);
        $item->setAuthor($raw_item->author_name);
        $item->setTitle($raw_item->title);
        $item->setDate($raw_item->created);
        $item->setPublished(($raw_item->published == 1) ? true : false);
        $item->setCategory($raw_item->category_title);
        $item->setHits($raw_item->hits);
        $item->setRating($raw_item->rating);
        $item->setMetaKey('');
        $item->setMetaDesc('');
        $item->setMetaData('');

        require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

        $app = App::getInstance('zoo');
        $applications = $app->application->getApplications();
        $application = $applications[$raw_item->application_id];
        $text_ids = array();
        $image_ids = array();
        $link_ids = array();
        $types = $application->getTypes();
        foreach ($types as $type) {
            $elements = $type->getElements();
            foreach ($elements as $element) {
                if ($element->config->type == 'image') {
                    $image_ids[] = $element->identifier;
                }
                if ($element->config->type == 'link') {
                    $link_ids[] = $element->identifier;
                }
                if ($element->config->type == 'textarea' || $element->config->type == 'text') {
                    $text_ids[] = $element->identifier;
                }
            }
        }

        $els = json_decode($raw_item->elements);
        $texts = array();
        $images = array();
        $links = array();
        foreach ($els as $ident => $el) {
            if (in_array($ident, $image_ids)) {
                $image = new RokSprocket_Item_Image();
                $image->setSource($el->file);
                $image->setIdentifier('image_field_' . $ident);
                $image->setCaption((isset($el->title))?$el->title:'');
                $image->setAlttext((isset($el->title))?$el->title:'');
                $images[$image->getIdentifier()] = $image;
                if (isset($images['image_field_' . $ident]) && !$item->getPrimaryImage()) {
                    $item->setPrimaryImage($image);
                }
            }

            if (in_array($ident, $link_ids)) {
                $link = new RokSprocket_Item_Link();
                $link->setUrl((isset($el->url))?$el->url:((isset($el->value))?$el->value:''));
                $link->setText('');
                $link->setIdentifier('link_field_' . $ident);
                $links[$link->getIdentifier()] = $link;
                if (isset($links['link_field_' . $ident]) && !$item->getPrimaryLink()) {
                    $item->setPrimaryLink($link);
                }
            }

            if (in_array($ident, $text_ids) && is_object($el)) {
                foreach ($el as $val)
                    $texts['text_field_' . $ident] = $val->value;
            }
            else if (in_array($ident, $text_ids) && !is_object($el)){
                    $texts['text_field_' . $ident] = $el->value;
            }
        }
        $item->setLinks($images);
        $item->setLinks($links);
        $item->setTextFields($texts);
        $item->setText((isset($texts[$textfield])) ? $texts[$textfield] : '');

        $primary_link = new RokSprocket_Item_Link();
        $primary_link->setUrl(JRoute::_('index.php?option=com_zoo&task=item&item_id=' . $raw_item->id, false));
        $primary_link->getIdentifier('article_link');

        $item->setPrimaryLink($primary_link);

        $item->setCommentCount($raw_item->comment_count);
        $tags = (explode(',', $raw_item->tags)) ? explode(',', $raw_item->tags) : array();
        $item->setTags($tags);

        $item->setDbOrder($dborder);

        return $item;
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
        $filer_processor->process(array('id' => array($id)));
        $query = $filer_processor->getQuery();
        $db = JFactory::getDbo();
        $db->setQuery($query);
        $db->query();
        if ($error = $db->getErrorMsg()) {
            throw new RokSprocket_Exception($error);
        }
        $ret = $db->loadObject();
        if ($raw) {
            $ret->preview = $this->_cleanPreview($ret->articletext);
            $ret->editUrl = $this->getArticleEditUrl($id);
            return $ret;
        } else {
            $item = $this->convertRawToItem($ret);
            $item->editUrl = $this->getArticleEditUrl($id);
            $item->preview = $this->_cleanPreview($item->getText());
            return $item;
        }
    }

    /**
     * @param $id
     *
     * @return string
     */
    protected function getArticleEditUrl($id)
    {
        return JURI::root(true) . '/administrator/index.php?option=com_zoo&controller=item&changeapp=1&task=edit&cid[]=' . $id;
    }

    /**
     * @return array the array of image type and label
     */
    public static function getImageTypes()
    {
        require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

        $app = App::getInstance('zoo');
        $applications = $app->application->getApplications();
        $list = array();
        foreach ($applications as $application) {
            $types = $application->getTypes();
            foreach ($types as $type) {
                $elements = $type->getElements();
                foreach ($elements as $element) {
                    if ($element->config->type == 'image') {
                        $key = 'image_field_' . $element->identifier;
                        $list[$key] = array();
                        $list[$key]['group'] = $application->id . '_' . $type->id;
                        $list[$key]['display'] = $element->config->name;
                    }
                }
            }
        }
        return $list;
    }

    /**
     * @return array the array of link types and label
     */
    public static function getLinkTypes()
    {
        require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

        $app = App::getInstance('zoo');
        $applications = $app->application->getApplications();
        $list = array();
        foreach ($applications as $application) {
            $types = $application->getTypes();
            foreach ($types as $type) {
                $elements = $type->getElements();
                foreach ($elements as $element) {
                    if ($element->config->type == 'link') {
                        $key = 'link_field_' . $element->identifier;
                        $list[$key] = array();
                        $list[$key]['group'] = $application->id . '_' . $type->id;
                        $list[$key]['display'] = $element->config->name;
                    }
                }
            }
        }
        return $list;
    }

    /**
     * @return array the array of link types and label
     */
    public static function getTextTypes()
    {
        require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');
        $app = App::getInstance('zoo');
        $applications = $app->application->getApplications();
        $list = array();
        foreach ($applications as $application) {
            $types = $application->getTypes();
            foreach ($types as $type) {
                $elements = $type->getElements();
                foreach ($elements as $element) {
                    if ($element->config->type == 'textarea' || $element->config->type == 'text') {
                        $key = 'text_field_' . $element->identifier;
                        $list[$key] = array();
                        $list[$key]['group'] = $application->id . '_' . $type->id;
                        $list[$key]['display'] = $element->config->name;
                    }
                }
            }
        }
        return $list;
    }

    /**
     * @static
     * @return array
     */
    public static function getCCKGroups()
    {
        require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');
        $app = App::getInstance('zoo');
        $applications = $app->application->getApplications();
        $list = array();
        foreach ($applications as $application) {
            $types = $application->getTypes();
            foreach ($types as $type) {
                $list[$application->id . '_' . $type->id] = $application->name . ' - ' . $type->name;
            }
        }
        return $list;
    }
}

