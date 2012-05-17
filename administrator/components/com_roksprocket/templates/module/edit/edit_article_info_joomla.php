<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
?>

<h1 class="popover-title"><?php echo $article->title;?></h1>
<div class="article-info">
	<ul>
		<li>
			<span class="title"><?php rc_e('ROKSPROCKET_ID')?></span>
			<span class="content"><?php echo $article->id;?></span>
		</li>
		<li>
			<span class="title"><?php rc_e('ROKSPROCKET_STATUS')?></span>
			<span class="content"><?php
				switch($article->state) {
					case 1:
						$state = rc_e('PUBLISHED');
						$state .= ' <span class="green">&#9679;</span>';
						break;
					case 2:
						$state = rc_e('ARCHIVED');
						$state .= ' <span class="red">&#9679;</span>';
						break;
					case 0:
						$state = rc_e('UNPUBLISHED');
						$state .= ' <span class="red">&#9679;</span>';
						break;
					case -2: default:
						$state = rc_e('TRASHED');
						$state .= ' <span class="red">&#9679;</span>';
						break;
				}
				echo $state;
			?></span>
		</li>
		<li>
			<span class="title"><?php rc_e('ROKSPROCKET_TITLE')?></span>
			<span class="content"><?php echo $article->access_title;?></span>
		</li>
		<li>
			<span class="title"><?php rc_e('ROKSPROCKET_ALIAS')?></span>
			<span class="content"><?php echo $article->alias;?></span>
		</li>
		<li>
			<span class="title"><?php rc_e('ROKSPROCKET_CATEGORY')?></span>
			<span class="content"><?php echo $article->category_title;?></span>
		</li>
		<li>
			<span class="title"><?php rc_e('ROKSPROCKET_FEATURED')?></span>
			<span class="content"><?php if ($article->featured) {
				$yes = rc_e('ROKSPROCKET_YES');
				$yes .= ' <span class="green">&#9679;</span>';
				echo $yes;
			} else {
				$no = rc_e('ROKSPROCKET_NO');
				$no .= ' <span class="red">&#9679;</span>';
				echo $no;
			}?></span>
		</li>
		<li>
			<span class="title"><?php rc_e('ROKSPROCKET_LANGUAGE')?></span>
			<span class="content"><?php echo strlen($article->language_title) ? $article->language_title : 'All';?></span>
		</li>
		<li>
			<span class="title"><?php rc_e('ROKSPROCKET_CREATED_BY')?></span>
			<span class="content"><?php echo $article->author_name;?></span>
		</li>
		<li>
			<span class="title"><?php rc_e('ROKSPROCKET_CREATED_DATE')?></span>
			<span class="content"><?php echo $article->created;?></span>
		</li>
		<li>
			<span class="title"><?php rc_e('ROKSPROCKET_MODIFIED_DATE')?></span>
			<span class="content"><?php echo $article->modified;?></span>
		</li>
	</ul>
	<div class="statusbar">
		<a class="btn btn-primary" href="<?php echo $article->editUrl;?>" target="_blank">Edit</a>
	</div>
</div>
