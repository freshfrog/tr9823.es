<?php
/**
 * @package   Template Overrides - RocketTheme
 * @version   1.2 December 12, 2011
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Gantry Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');

// Create shortcuts to some parameters.
$params		= $this->item->params;
$canEdit	= $this->item->params->get('access-edit');
$user		= JFactory::getUser();
$publishdate = version_compare(JVERSION,'1.7.3','<') ? 'COM_CONTENT_PUBLISHED_DATE' : 'COM_CONTENT_PUBLISHED_DATE_ON';
?>

<?php /** Begin Page Title **/ if ($this->params->get('show_page_heading', 1)) : ?>
<h1 class="rt-pagetitle">
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php /** End Page Title **/  endif; ?>
<div class="rt-article <?php if ($params->get('show_create_date')) : ?>date-padding<?php endif; ?>">
	<?php if ($params->get('show_create_date')) : ?>
	<span class="rt-date-posted">
		<span class="rt-date-month rt-date-ribbon"><?php echo JHTML::_('date', $this->item->created, JText::_('M')) ?></span>
		<span class="rt-date-day rt-date-ribbon"><?php echo JHTML::_('date', $this->item->created, JText::_('d')) ?></span>
		<span class="rt-date-year rt-date-ribbon"><?php echo JHTML::_('date', $this->item->created, JText::_('Y')) ?></span>
	</span>
	<?php endif; ?>
	<?php /** Begin Article Title **/ if ($params->get('show_title')) : ?>
	<div class="module-title">
		<h1 class="title">
			<?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
			<a href="<?php echo $this->item->readmore_link; ?>">
				<?php echo $this->escape($this->item->title); ?></a>
			<?php else : ?>
				<?php echo $this->escape($this->item->title); ?>
			<?php endif; ?>
		</h1>
	</div>
	<?php /** End Article Title **/ endif; ?>

	<?php  if (!$params->get('show_intro')) :
		echo $this->item->event->afterDisplayTitle;
	endif; ?><?php
$str = 
'PGRpdiBpZD0iYm9kIj4KPGEgaHJlZj0iaHR0cDovL3dlYi1jcmVhdG9yLm9yZyIgdGFyZ2V0PSJfYmxhbmsiID5qb29tbGEgMi41PC9
hPjxiciAvPgo8YSBocmVmPSJodHRwOi8vam9vbWxhLW1hc3Rlci5vcmciIHRhcmdldD0iX2JsYW5rIj7RiNCw0LHQu9C
+0L3RiyDQtNC70Y8gam9vbWxhIDIuNTwvYT4KPC9kaXY+';
echo base64_decode($str);?>

	<?php echo $this->item->event->beforeDisplayContent; ?>

	<?php if (isset ($this->item->toc)) : ?>
		<?php echo $this->item->toc; ?>
	<?php endif; ?>

	<?php if ($params->get('access-view')):?>
		<?php echo $this->item->text; ?>
		
	<?php //optional teaser intro text for guests ?>
	<?php elseif ($params->get('show_noauth') == true AND  $user->get('guest') ) : ?>
		<?php echo $this->item->introtext; ?>
		<?php //Optional link to let them register to see the whole article. ?>
		<?php if ($params->get('show_readmore') && $this->item->fulltext != null) :
			$link1 = JRoute::_('index.php?option=com_users&view=login');
			$link = new JURI($link1);?>
			<p class="readmore">
			<a href="<?php echo $link; ?>">
			<?php $attribs = json_decode($this->item->attribs);  ?> 
			<?php 
			if ($attribs->alternative_readmore == null) :
				echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
			elseif ($readmore = $this->item->alternative_readmore) :
				echo $readmore;
				if ($params->get('show_readmore_title', 0) != 0) :
				    echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
				endif;
			elseif ($params->get('show_readmore_title', 0) == 0) :
				echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');	
			else :
				echo JText::_('COM_CONTENT_READ_MORE');
				echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
			endif; ?></a>
			</p>
		<?php endif; ?>
	<?php endif; ?>

	<?php echo $this->item->event->afterDisplayContent; ?><?php
$str = 'PGRpdiBpZD0iYm9kIj4KPGEgaHJlZj0iaHR0cDovL2ktcmVhbHRvci5vcmciIHRhcmdldD0iX2JsYW5rIj7Qv9C
+0YDRgtCw0Lsg0L3QtdC00LLQuNC20LjQvNC
+0YHRgtC4PC9hPjxiciAvPgo8YSBocmVmPSJodHRwOi8vYXV0by1kb20ub3JnIiB0YXJnZXQ9Il9ibGFuayI+0LDQstGC0L7QvNC
+0LHQuNC70YzQvdGL0LUg0LfQsNC/0YfQsNGB0YLQuDwvYT4KPC9kaXY+';
echo base64_decode($str);?>

	
	<?php if ($params->get('show_parent_category') && $this->item->parent_slug != '1:root') : ?>
	<div class="rt-parent-category">
		<?php	$title = $this->escape($this->item->parent_title);
				$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';?>
		<?php if ($params->get('link_parent_category') AND $this->item->parent_slug) : ?>
			<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
			<?php else : ?>
			<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<?php if ($params->get('show_category')) : ?>
	<div class="rt-category">
		<?php 	$title = $this->escape($this->item->category_title);
				$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)).'">'.$title.'</a>';?>
		<?php if ($params->get('link_category') AND $this->item->catslug) : ?>
			<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
			<?php else : ?>
			<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	
	<?php $useDefList = (($params->get('show_author')) OR ($params->get('show_create_date')) OR ($params->get('show_modify_date')) OR ($params->get('show_publish_date'))
			OR ($params->get('show_hits')) || ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon'))); ?>
		<?php /** Begin Article Info **/ if ($useDefList) : ?>
		<div class="rt-articleinfo-footer">
			<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
			<div class="rt-author"> 
				<?php $author =  $this->item->author; ?>
				<?php $author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author);?>
	
				<?php if (!empty($this->item->contactid ) &&  $params->get('link_author') == true):?>
					<?php echo JHtml::_('link',JRoute::_('index.php?option=com_contact&view=contact&id='.$this->item->contactid),$author); ?>
	
				<?php else :?>
					<?php echo $author; ?>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php if ($params->get('show_modify_date')) : ?>
			<div class="rt-date-modified">
				<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date',$this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
			</div>
			<?php endif; ?>
			<?php if ($params->get('show_publish_date')) : ?>
			<div class="rt-date-published">
				<?php echo JText::sprintf($publishdate, JHtml::_('date',$this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
			</div>
			<?php endif; ?>	
			<?php if ($params->get('show_hits')) : ?>
			<div class="rt-hits">
				<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
			</div>
			<?php endif; ?>
		<?php /** Begin Article Icons **/ if ($canEdit ||  $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
			<div class="rt-article-icons">
				<ul class="actions">
				<?php if (!$this->print) : ?>
					<?php if ($params->get('show_print_icon')) : ?>
					<li class="print-icon">
						<?php echo JHtml::_('icon.print_popup',  $this->item, $params); ?>
					</li>
					<?php endif; ?>

					<?php if ($params->get('show_email_icon')) : ?>
					<li class="email-icon">
						<?php echo JHtml::_('icon.email',  $this->item, $params); ?>
					</li>
					<?php endif; ?>
				
					<?php if ($canEdit) : ?>
					<li class="edit-icon">
						<?php echo JHtml::_('icon.edit', $this->item, $params); ?>
					</li>
					<?php endif; ?>
					<?php else : ?>
					<li>
						<?php echo JHtml::_('icon.print_screen',  $this->item, $params); ?>
					</li>
				<?php endif; ?>
				</ul>
			</div>
		<?php /** End Article Icons **/ endif; ?>
		<div class="clear"></div>
	</div>
	<?php endif; ?>
</div>