<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokSprocket_Provider_K2 extends RokSprocket_Provider_AbstarctJoomlaBasedProvider
{
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
        $query->where('a.element = "com_k2"');
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
        parent::__construct('k2');
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
        //$app_type = $this->params->get('zoo_application_type');
        $textfield = $this->params->get('k2_articletext_field');
        $textfield = str_replace('k2_', '', $textfield);

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
        $item->setMetaKey($raw_item->metakey);
        $item->setMetaDesc($raw_item->metadesc);
        $item->setMetaData($raw_item->metadata);

        $image_sizes = array('_XS', '_S', '_M', '_L', '_XL', '_Generic');
        $images = array();
        foreach ($image_sizes as $image_size) {
            $image_uri = 'media/k2/items/cache/'.md5("Image" . $raw_item->id) . $image_size . '.jpg';
            if (JFile::exists(JPATH_SITE . '/'.$image_uri)) {
                $image = new RokSprocket_Item_Image();
                $image->setSource($image_uri);
                $image->setIdentifier('item_image' . $image_size);
                $image->setCaption('');
                $image->setAlttext('');
                $images[$image->getIdentifier()] = $image;
            }
            if (isset($images['item_image_S'])) {
                $item->setPrimaryImage($images['item_image_S']);
            }
        }
        if (isset($raw_item->category_image) && !empty($raw_item->category_image)) {
            $image = new RokSprocket_Item_Image();
            $image->setSource('media/k2/categories/' . $raw_item->category_image);
            $image->setIdentifier('item_image_category');
            $image->setCaption('');
            $image->setAlttext('');
            $images[$image->getIdentifier()] = $image;
        }
        $item->setImages($images);

        if (isset($raw_item->extra_fields)) {

            $link_fields = self::getFieldTypes("link");
            $raw_links = array();
            $extra_fields = json_decode($raw_item->extra_fields);
            foreach ($extra_fields as $field) {
                if (!in_array($field->id, $link_fields)) {
                    $raw_links[] = $field;
                }
            }
            $links = array();
            foreach ($raw_links as $raw_link) {
                if (isset($raw_link->value)) {
                    $link = new RokSprocket_Item_Link();
                    $link->setUrl($raw_link->value);
                    $link->setText('');
                    $link->setIdentifier('item_link_' . $raw_link->id);
                    $links[$link->getIdentifier()] = $link;
                }
                $item->setLinks($links);
            }

            $text_fields = self::getFieldTypes(array("textarea","textfield"));
            $raw_texts = array();
            foreach ($extra_fields as $field) {
                if (!in_array($field->id, $text_fields)) {
                    $raw_texts[] = $field;
                }
            }
            $texts = array();
            foreach ($raw_links as $raw_link) {
                if (isset($raw_link->value)) {
                    $texts['item_text_' . $raw_link->id] = $raw_link->value;
                }
            }
        }
        $texts['item_text_introtext'] = $raw_item->introtext;
        $texts['item_text_fulltext'] = $raw_item->fulltext;
        $item->setTextFields($texts);
        $item->setText((isset($texts['item_text_'.$textfield])) ? $texts['item_text_'.$textfield] : $texts['item_text_introtext']);

        $item->setDbOrder($dborder);

        require_once(JPATH_SITE . '/components/com_k2/helpers/route.php');
        $primary_link = new RokSprocket_Item_Link();
        $primary_link->setUrl(JRoute::_(K2HelperRoute::getItemRoute($raw_item->id.':'.$raw_item->alias, $raw_item->catid.':'.$raw_item->category_alias),false));
        $primary_link->getIdentifier('article_link');
        $item->setPrimaryLink($primary_link);

        // unknown joomla items
        $item->setCommentCount($raw_item->comment_count);
        if (isset($raw_item->tags)) {
            $tags = (explode(',', $raw_item->tags)) ? explode(',', $raw_item->tags) : array();
            $item->setTags($tags);
        }
        return $item;
    }

    /**
     * @param $id
     *
     * @return string
     */
    protected function getArticleEditUrl($id)
    {
        return JURI::root(true) . '/administrator/index.php?option=com_k2&view=item&cid=' . $id;
    }

    /**
     * @return array the array of image type and label
     */
    public static function getImageTypes()
    {
        $fields = self::getFieldTypes("image", false);

        $list = array();
        foreach ($fields as $field) {
            $list[$field->id] = array();
            $list[$field->id]['group'] = $field->group_id;
            $list[$field->id]['display'] = $field->field_name;
        }

        $static = array(
            'item_image_XS' => array('group' => null, 'display' => 'Extra Small Item Image'),
            'item_image_S' => array('group' => null, 'display' => 'Small Item Image'),
            'item_image_M' => array('group' => null, 'display' => 'Medium Item Image'),
            'item_image_L' => array('group' => null, 'display' => 'Large Item Image'),
            'item_image_XL' => array('group' => null, 'display' => 'Extra Large Item Image'),
            'item_image_category' => array('group' => null, 'display' => 'Category Image')
        );
        $list = array_merge($static, $list);
        return $list;
    }

    /**
     * @return array the array of link types and label
     */
    public static function getLinkTypes()
    {
        $fields = self::getFieldTypes("link", false);

        $list = array();
        foreach ($fields as $field) {
            $list[$field->id] = array();
            $list[$field->id]['group'] = $field->catid;
            $list[$field->id]['display'] = $field->field_name;
        }
        return $list;
    }

    /**
     * @return array the array of link types and label
     */
    public static function getTextTypes()
    {
        $fields = self::getFieldTypes(array("textarea","textfield"), false);

        $list = array();
        foreach ($fields as $field) {
            $list['k2_'.$field->id] = array();
            $list['k2_'.$field->id]['group'] =$field->catid;
            $list['k2_'.$field->id]['display'] = $field->category .' - ' . $field->field_name;
        }
        return $list;
    }

    /**
     * @static
     * @return array
     */
    public static function getCCKGroups()
	{
		$populator = new RokSprocket_Provider_K2_CategoryPopulator();
		$options = $populator->getPicklistOptions();
		return $options;
	}

    public static function getFieldTypes($field=false, $id_only=true){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('f.id');

        if($id_only==false){
            $query->select('f.name as field_name, fg.id as group_id, fg.name as group_name, cat.id as catid, cat.name as category');
        }

        $query->from('#__k2_extra_fields AS f');
        $query->join('LEFT', '#__k2_extra_fields_groups AS fg ON fg.id = f.group');
   	    $query->join('LEFT', '#__k2_categories AS cat ON cat.extraFieldsGroup = fg.id');

        if($field && is_array($field)){
            $wheres = array();
            foreach($field as $match){
                $wheres[] = ('f.type = "'.$match.'"');
            }
            $query->where('(' . implode(' OR ', $wheres) . ')');
        }
        else if ($field && is_string($field)){
            $query->where('f.type = "'.$field.'"');
        }

        $query->order('fg.name, f.name');

        $db->setQuery($query);

        return $db->loadObjectList();
    }
}

