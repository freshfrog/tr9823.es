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

$layout = $params->get("layout_type", 'layout1');
$content_position = $params->get("content_position", 'right');
$height = $params->get('fixed_height', 0);
if ($height != 0 && $height != '0') $style = 'style="height: '.$height.'px;"';
else $style = "";
$image_pad = '';
$content_pad = '';
if ($content_position == 'right') $image_pad = ' feature-pad';
if ($content_position == 'left') $content_pad = ' feature-pad';
?>

<script type="text/javascript">
<?php foreach ($list as $item): ?>
    RokStoriesImage['rokstories-<?php echo $module->id; ?>'].push('<?php echo $item->image; ?>');
	<?php if ($params->get('link_images', 0) == 1): ?>
	RokStoriesLinks['rokstories-<?php echo $module->id; ?>'].push('<?php echo $item->link; ?>');
	<?php endif; ?>
<?php endforeach; ?>
</script>
<div id="rokstories-<?php echo $module->id; ?>" class="rokstories-<?php echo $layout; ?>"  <?php echo $style; ?>>
	<div class="feature-block">
		<div class="image-container<?php echo $image_pad; ?>" style="float: <?php echo $content_position; ?>">
			<div class="image-full"></div>
			<div class="image-small">
				<?php $i = 0; ?>
			    <?php foreach ($list as $item): ?>
				<div class="wrapper items-<?php echo count($list); ?><?php echo (!$i) ? ' first' : '';?><?php echo ($i == count($list) - 1) ? ' last' : ''; ?>"><div class="wrapper-padding">
			    		<div class="thumb">
							<img src="<?php echo $item->thumb; ?>" class="feature-sub" alt="image" width="<?php echo $item->thumbSizes['width']; ?>" height="<?php echo $item->thumbSizes['height']; ?>" />
						</div>
						<div class="description<?php echo $content_pad; ?>">
							<?php // title ?>
							<?php if ($params->get("show_article_title",1)==1): ?>
								<?php if ($params->get("link_titles", 0) == 1): ?>
									<a href="<?php echo $item->link; ?>" class="feature-link"><span class="feature-title"><?php echo $item->title; ?></span></a>
								<?php else: ?>
									<span class="feature-title"><?php echo $item->title; ?></span>					
								<?php endif; ?>
							<?php endif;?>
							
							<?php // content ?>
							<?php if ($params->get("show_article",1)==1): ?>
								<span class="feature-desc"><?php echo $item->introtext; ?></span>
							<?php endif; ?>
							
							<?php // read more ?>
							<?php if ($params->get("show_article_link",1)==1): ?>
								<?php if ($params->get('legacy_readmore',1)==1): ?>
									<div class="clr"></div><div class="readon-wrap1"><div class="readon1-l"></div><a href="<?php echo $item->link; ?>" class="readon-main"><span class="readon1-m"><span class="readon1-r"><?php echo JText::_('ROKSTORIES_READMORE'); ?></span></span></a></div><div class="clr"></div>
								<?php endif;?>
								<?php if ($params->get('legacy_readmore',1)==0): ?>
									<a href="<?php echo $item->link; ?>" class="readon"><span><?php echo JText::_('ROKSTORIES_READMORE'); ?></span></a>
								<?php endif; ?>
							<?php endif; ?>
						</div>
						<div class="active-arrow"></div>
				</div></div>
				<?php $i++; ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php if ($params->get("show_arrows",1)==1 && $params->get("arrows_placement", 'inside')=='outside' && $layout == 'layout2'): ?>
		<div class="feature-arrow-r"></div>
		<div class="feature-arrow-l"></div>
		<?php endif; ?>
	</div>
</div>