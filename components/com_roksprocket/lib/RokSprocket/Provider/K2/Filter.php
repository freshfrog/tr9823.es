<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokSprocket_Provider_K2_Filter extends RokSprocket_Provider_AbstractJoomlaPlatformFilter
{
	/**
	 *
	 */
	protected function setBaseQuery()
	{
		$this->query->select('a.id, a.title, a.alias, a.introtext, a.`fulltext`, a.catid' . ', a.published, a.access, a.created, a.modified, a.extra_fields, a.extra_fields_search, a.created_by, a.featured, a.language, a.hits' . ', a.publish_up, a.publish_down, a.language, a.metakey, a.metadesc, a.metadata');
		$this->query->from('#__k2_items as a');

		$this->query->select('c.name AS category_title, c.image AS category_image, c.alias as category_alias');
		$this->query->join('LEFT', '#__k2_categories AS c ON c.id = a.catid');

		$this->query->join('LEFT', '#__k2_extra_fields_groups AS fg ON fg.id = c.extraFieldsGroup');

		$this->query->select('CONCAT_WS(",", f.id) AS field_ids, CONCAT_WS(",", f.name) AS field_names');
		$this->query->join('LEFT', '#__k2_extra_fields AS f ON f.group = fg.id');

		$this->query->join('LEFT', '#__k2_tags_xref AS tx ON tx.itemID = a.id');

		$this->query->select('CONCAT_WS(",", t.id) AS tag_ids, CONCAT_WS(",", t.name) AS tag_names');
		$this->query->join('LEFT', '#__k2_tags AS t ON t.id = tx.tagID');

		$this->query->select('COUNT(co.id) AS comment_count');
		$this->query->join('LEFT', '#__k2_comments AS co ON co.itemID = a.id');

		$this->query->select('ua.name AS author_name');
		$this->query->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

		$this->query->select('um.name AS last_modified_by');
		$this->query->join('LEFT', '#__users AS um ON um.id = a.modified_by');

		$this->query->select('ROUND(v.rating_sum / v.rating_count, 0) AS rating, v.rating_count as rating_count');
		$this->query->join('LEFT', '#__k2_rating AS v ON a.id = v.itemID');

		$this->query->select('vl.title AS access_title');
		$this->query->join('LEFT', '#__viewlevels AS vl ON a.access = vl.id');

		$this->query->select('l.title AS language_title');
		$this->query->join('LEFT', '#__languages AS l ON a.language = l.lang_code');
		$this->query->group('a.id');
	}

    /**
     * @param $data
     */
    protected function k2_category($data)
    {
        require_once (JPATH_SITE.'/components/com_k2/models/itemlist.php');
        $wheres = array();
        foreach($data as $match){
            $categories = K2ModelItemlist::getCategoryTree($match);
            $sql = @implode(',', $categories);
            $wheres[] = "a.catid IN ({$sql})";
        }
        $this->filter_where[] = '(' . implode(' OR ', $wheres) . ')';
    }


	/**
	 *
	 */
	protected function setAccessWhere()
	{
		$user                 = &JFactory::getUser();
		$this->access_where[] = 'a.access IN(' . implode(',', $user->getAuthorisedViewLevels()) . ')';
		$this->access_where[] = 'a.trash = 0';
		if (!$this->showUnpublished)
		{
			$this->access_where[] = 'a.published = 1';
		}
	}

	/**
	 * @param $data
	 */
	protected function id($data)
	{
		$this->article_where[] = 'a.id IN (' . implode(',', $data) . ')';
	}

	/**
	 * @param $data
	 */
	protected function article($data)
	{
		$this->article_where[] = 'a.id IN (' . implode(',', $data) . ')';
	}

	/**
	 * @param $data
	 */
	protected function author($data)
	{
		$this->filter_where[] = 'a.created_by IN (' . implode(',', $data) . ')';
	}

	/**
	 * @param $data
	 */
	protected function modifiedby($data)
	{
		$this->filter_where[] = 'a.modified_by IN (' . implode(',', $data) . ')';
	}

	/**
	 * @param $data
	 */
	protected function category($data)
	{
        require_once (JPATH_SITE.'/components/com_k2/models/itemlist.php');
        $wheres = array();
        foreach($data as $match){
            $categories = K2ModelItemlist::getCategoryTree($match);
            $sql = @implode(',', $categories);
            $wheres[] = "a.catid IN ({$sql})";
        }
        $this->filter_where[] = '(' . implode(' OR ', $wheres) . ')';
	}

	/**
	 * @param $data
	 */
	protected function access($data)
	{
		$this->filter_where[] = 'a.access IN (' . implode(',', $data) . ')';
	}

	/**
	 * @param $data
	 */
	protected function featured($data)
	{
		$this->booleanMatch('a.featured', $data);
	}

	/**
	 * @param $data
	 */
	protected function published($data)
	{
		$this->booleanMatch('a.published', $data);
	}

	/**
	 * @param $data
	 */
	protected function title($data)
	{
		$this->textMatch('a.title', $data);
	}

	/**
	 * @param $data
	 */
	protected function alias($data)
	{
		$this->textMatch('a.alias', $data);
	}

	/**
	 * @param $data
	 */
	protected function rating($data)
	{
		$this->numberMatch('ROUND(v.rating_sum / v.rating_count, 0)', $data);
	}

	/**
	 * @param $data
	 */
	protected function hits($data)
	{
		$this->numberMatch('a.hits', $data);
	}

	/**
	 * @param $data
	 */
	protected function language($data)
	{
		$wheres = array();
		foreach ($data as $match) {
			$wheres[] = 'a.language = ' . $this->db->quote($this->db->escape($match, true));
		}
		$this->filter_where[] = '(' . implode(' OR ', $wheres) . ')';
	}

	/**
	 * @param $data
	 */
	protected function createdDate($data)
	{
		$this->dateMatch('a.created', $data);
	}

	/**
	 * @param $data
	 */
	protected function modifiedDate($data)
	{
		$this->dateMatch('a.modified', $data);
	}

	/**
	 * @param $data
	 */
	protected function articletext($data)
	{
		$wheres = array();
		foreach ($data as $match) {
			$match = trim($match);
			if (!empty($match)) {
				$wheres[] = 'a.introtext like ' . $this->db->quote('%' . $this->db->escape($match, true) . '%');
			}
		}
		if (!empty($wheres)) {
			$this->filter_where[] = '(' . implode(' OR ', $wheres) . ')';
		}
	}

	/**
	 * @param $data
	 */
	protected function tag($data)
	{
		$wheres = array();
		foreach ($data as $match) {
			$match = trim($match);
			if (!empty($match)) {
				$wheres[] = 'CONCAT_WS(",", t.name) like ' . $this->db->quote('%' . $this->db->escape($match, true) . '%');
			}
		}
		if (!empty($wheres)) {
			$this->filter_where[] = '(' . implode(' OR ', $wheres) . ')';
		}
	}

	/**
	 * @param $data
	 */
	protected function searchablefield($data)
	{
		$wheres = array();
		foreach ($data as $match) {
			$match = trim($match);
			if (!empty($match)) {
				$wheres[] = 'a.extra_fields_search like ' . $this->db->quote('%' . $this->db->escape($match, true) . '%');
			}
		}
		if (!empty($wheres)) {
			$this->filter_where[] = '(' . implode(' OR ', $wheres) . ')';
		}
	}

	/**
	 * @param $data
	 */
	protected function sort_title($data)
	{
		$this->normalSortBy('a.title', $data);
	}

	/**
	 * @param $data
	 */
	protected function sort_alias($data)
	{
		$this->normalSortBy('a.alias', $data);
	}

	/**
	 * @param $data
	 */
	protected function sort_category($data)
	{
		$this->normalSortBy('category_title', $data);
	}

	/**
	 * @param $data
	 */
	protected function sort_createddate($data)
	{
		$this->normalSortBy('a.created', $data);
	}

	/**
	 * @param $data
	 */
	protected function sort_modifieddate($data)
	{
		$this->normalSortBy('a.modified', $data);
	}

	/**
	 * @param $data
	 */
	protected function sort_modifiedby($data)
	{
		$this->normalSortBy('last_modified_by', $data);
	}

	/**
	 * @param $data
	 */
	protected function sort_author($data)
	{
		$this->normalSortBy('author_name', $data);
	}

	/**
	 * @param $data
	 */
	protected function sort_rating($data)
	{
		$this->normalSortBy('rating', $data);
	}

	/**
	 * @param $data
	 */
	protected function sort_hits($data)
	{
		$this->normalSortBy('a.hits', $data);
	}
}
