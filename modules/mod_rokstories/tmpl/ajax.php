<?php 
/**
 * RokStories Module
 *
 * @package RocketTheme
 * @subpackage rokstories.tmpl
 * @version   1.9 March 20, 2012
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

	// no direct access
	defined('_JEXEC') or die('Restricted access');
	jimport( 'joomla.user.user' );
	
	$install_sql = dirname(__FILE__).DS."../install.rokstories.sql";
	$images_path = JURI::Root(true)."/modules/mod_rokstories/images/sample/";
	$mainframe = JFactory::getApplication();
	$dbprefix = $mainframe->getCfg('dbprefix');
	
	if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) die("You aren't allowed to import data from outside the Admin CP.");

	$user =& JFactory::getUser();
	
	if (!array_key_exists('Super Users', $user->groups)) die("You must be logged into your site (front-end) as Administrator or Super-Administrator, in order to import data.");

	$db =& JFactory::getDBO();
	
	// Check for existance
	if (JRequest::getVar("duplicate") != 'true') {
		$query = "SELECT ".$dbprefix."categories.title FROM ".$dbprefix."categories WHERE ".$dbprefix."categories.title = 'RokStories Samples' LIMIT 1";
		$db->setQuery($query);
		if (!$db->query()) {
			die($db->getErrorMsg());
		} else {
			if ($db->getNumRows() == 1) die('please.confirm');
		}
	}
	
	// RokStories Category
	$query = "INSERT INTO `".$dbprefix."categories` (`id`, `parent_id`, `level`, `path`, `extension`, `title`, `alias`, `note`, `description`, `published`, `checked_out`, `checked_out_time`, `access`, `params`, `metadesc`, `metakey`, `metadata`, `created_user_id`, `created_time`, `modified_user_id`, `modified_time`, `hits`, `language`) VALUES (0, 1, 1, 'rokstories-samples', 'com_content', 'RokStories Samples', 'rokstories-samples', '', '', 1, 0, '0000-00-00 00:00:00', 1, '{\"target\":\"\",\"image\":\"\"}', '', '', '{\"author\":\"\",\"robots\":\"\"}', ".$user->id.", '', 0, '".date('Y-m-d H:i:s', time())."', 0, '*');";
	$db->setQuery($query);
	if (!$db->query()) {
		die($db->getErrorMsg());
	} else {
		$category_id = $db->insertid();
	}
		
	// RokStories Content
	$query = "INSERT INTO `".$dbprefix."content` (`title`, `alias`, `title_alias`, `introtext`, `fulltext`, `state`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`, `metadata`, `language`) VALUES('Featured Article Headline', 'featured-article-headline', '', '<img src=\"".$images_path."/rokstories3.jpg\" alt=\"image\" />\r\n\r\n<p>Integer consequat iaculis sollicitudin. Donec faucibus urna mattis ipsum egestas ullamcorper. Nam semper lacinia blandit. Integer aliquet quam sit amet nibh posuere pharetra. Fusce fermentum, neque ut tincidunt suscipit, tortor mauris placerat augue, at ultricies tortor ante id est.</p>', '', 1, 0, $category_id, '".date('Y-m-d H:i:s', time())."', ".$user->id.", '', '".date('Y-m-d H:i:s', time())."', ".$user->id.", 0, '0000-00-00 00:00:00', '".date('Y-m-d H:i:s', time())."', '0000-00-00 00:00:00', '', '', '{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\"}', 2, 0, 3, '', '', 1, 0, '{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}', '*');";
	$db->setQuery($query);
	if (!$db->query()) {
		die($db->getErrorMsg());
	}
	
	$query = "INSERT INTO `".$dbprefix."content` (`title`, `alias`, `title_alias`, `introtext`, `fulltext`, `state`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`, `metadata`, `language`) VALUES('Another Featured Article', 'another-featured-article', '', '<img src=\"".$images_path."rokstories2.jpg\" alt=\"image\" />\r\n\r\n<p>Phasellus sit amet odio eros. Ut sagittis metus volutpat eros bibendum accumsan. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In scelerisque aliquam tincidunt. Duis quis dui ac augue hendrerit elementum. Phasellus risus mauris, volutpat eget molestie vel, rhoncus eu lorem. Morbi a nisi quam.</p>', '', 1, 0, $category_id, '".date('Y-m-d H:i:s', time())."', ".$user->id.", '', '".date('Y-m-d H:i:s', time())."', ".$user->id.", 0, '0000-00-00 00:00:00', '".date('Y-m-d H:i:s', time())."', '0000-00-00 00:00:00', '', '', '{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\"}', 2, 0, 2, '', '', 1, 0, '{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}', '*');";
	$db->setQuery($query);
	if (!$db->query()) {
		die($db->getErrorMsg());
	}
	
	$query = "INSERT INTO `".$dbprefix."content` (`title`, `alias`, `title_alias`, `introtext`, `fulltext`, `state`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`, `metadata`, `language`) VALUES('Important Featured Story', 'important-featured-story', '', '<img src=\"".$images_path."rokstories1.jpg\" alt=\"image\" />\r\n\r\n<p>Phasellus sit amet odio eros. Ut sagittis metus volutpat eros bibendum accumsan. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In scelerisque aliquam tincidunt. Duis quis dui ac augue hendrerit elementum. Phasellus risus mauris, volutpat eget molestie vel, rhoncus eu lorem. Morbi a nisi quam.</p>', '', 1, 0, $category_id, '".date('Y-m-d H:i:s', time())."', ".$user->id.", '', '".date('Y-m-d H:i:s', time())."', ".$user->id.", 0, '0000-00-00 00:00:00', '".date('Y-m-d H:i:s', time())."', '0000-00-00 00:00:00', '', '', '{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\"}', 2, 0, 1, '', '', 1, 0, '{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}', '*');";
	$db->setQuery($query);
	if (!$db->query()) {
		die($db->getErrorMsg());
	}
	
	die('success.'.$category_id);
?>