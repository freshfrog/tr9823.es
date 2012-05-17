<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

/**
 * @var $item RokSprocket_Item
 */
?>

<li class="sprocket-features-index-<?php echo $index;?><?php echo (!($index - 1)) ? ' active' : '';?>" data-showcase-pane>
	<div class="sprocket-features-container">
		<?php
		if ($item->getPrimaryImage()) :
		?>
		<div class="sprocket-features-img-container">
			<?php if ($item->getPrimaryLink()) : ?>
				<a href="<?php echo $item->getPrimaryLink()->getUrl(); ?>"><img src="<?php echo $item->getPrimaryImage()->getSource(); ?>" alt="" /></a>
			<?php else: ?>
				<img src="<?php echo $item->getPrimaryImage()->getSource(); ?>" alt="" />
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<div class="sprocket-features-content">
			<?php if ($parameters->get('features_show_title') && $item->getTitle()) : ?>
				<h2 class="sprocket-features-title">
					<?php if ($item->getPrimaryLink()) : ?>
						<a href="<?php echo $item->getPrimaryLink()->getUrl(); ?>"><?php echo $item->getTitle(); ?></a>
					<?php else: ?>
						<?php echo $item->getTitle(); ?>
					<?php endif; ?>
				</h2>
			<?php endif; ?>
			<?php if ($parameters->get('features_show_article_text') && ($item->getText() || $item->getPrimaryLink())) : ?>
				<div class="sprocket-features-desc">
					<span>
						<?php echo $item->getText(); ?>
					</span>
					<?php if ($item->getPrimaryLink()) : ?>
					<a href="<?php echo $item->getPrimaryLink()->getUrl(); ?>" class="readon"><span><?php rc_e('READ_MORE'); ?></span></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</li>
