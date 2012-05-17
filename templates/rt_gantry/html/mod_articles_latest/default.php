<?php
/**
 * @package   Template Overrides - RocketTheme
 * @version   3.2.19 April 2, 2012
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Gantry Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */

// no direct access
defined('_JEXEC') or die;
?>
<ul class="latestnews">
<?php foreach ($list as $item) :  ?>
	<li>
		<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
	</li>
<?php endforeach; ?>
</ul>