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

if ($content_position == 'right') $content_right = ' content-left';
else $content_right = ' content-right';

$show_titles = $params->get('show_article_title', 1);
$link_titles = $params->get('link_titles', 0);

$count = 0;
?>

<script type="text/javascript">
<?php foreach ($list as $item): ?>
    RokStoriesImage['rokstories-<?php echo $module->id; ?>'].push('<?php echo $item->image; ?>');
	<?php if ($params->get('link_images', 0) == 1): ?>
	RokStoriesLinks['rokstories-<?php echo $module->id; ?>'].push('<?php echo $item->link; ?>');
	<?php endif; ?>
<?php endforeach; ?>
</script>
<div id="rokstories-<?php echo $module->id; ?>" class="rokstories-<?php echo $layout; ?><?php echo $content_right; ?> rt-gallery-container"  <?php echo $style; ?>>
	<div class="rt-gallery-overlay">
		<div class="rt-gallery-overlay2"></div>
		<div class="rt-gallery-overlay3"></div>
		<div class="rt-gallery-overlay4"></div>
	</div>
	<div class="rt-gallery">
		<ul class="rt-gallery-items">
			<?php foreach ($list as $item): ?>
				<?php 
					$count++;
					if ($count == 1) $active = ' class="active"';
					else $active = ''; 
				?>
				<li<?php echo $active;?>>
					<img src="<?php echo $item->image; ?>" alt="<?php echo $item->link ?>" />
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<?php if ($count > 0): ?>
	<div class="rt-gallery-controls-container"><div class="rt-gallery-controls"><div class="rt-gallery-controls2"><div class="rt-gallery-controls3">
		<?php if ($show_titles): ?>
		<div class="rt-gallery-title">
			<?php
				$count = 0;
				foreach($list as $item){
					$cls = ' class="layout7-title layout7-title-'.($count + 1).'"';
					
					if ($link_titles) echo "<a href='".$item->link."'".$cls."><span>".$item->title."</span></a>";
					else echo "<span".$cls.">".$item->title."</span>";
					$count++;
				}
			?>	
		</div>
		<?php endif; ?>
		<ul>
			<li class="arrow previous"></li>
			<?php for ($i = 1; $i <= $count; $i++): ?>
				<?php 
					if ($i == 1) $active = ' class="active"';
					else $active = ''; 
				?>
				<li<?php echo $active;?>></li>
			<?php endfor;?>
			<li class="arrow next"></li>
		</ul>
	</div></div></div></div>
	<?php endif; ?>	
</div>