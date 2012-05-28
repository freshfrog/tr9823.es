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
?>

<div class="breadcrumbs" >
<?php if ($params->get('showHere', 1))
	{
		echo '<span class="showHere">' .JText::_('MOD_BREADCRUMBS_HERE').'</span>';
	}
?>
<?php for ($i = 0; $i < $count; $i ++) :

	// If not the last item in the breadcrumbs add the separator
	if ($i < $count -1) {
		if (!empty($list[$i]->link)) {
			echo '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="floatleft" style="margin-right: 5px">';
			echo '<a href="'.$list[$i]->link.'" class="pathway" itemprop="url"><span itemprop="title">'.$list[$i]->name.'</span></a>';
			echo '</div>';
		} else {
		    //echo '<span class="no-link">' . $list[$i]->name . '</span>';
		}
		if( $i < $count -2 && !empty($list[$i]->link) ){
			echo ' <span class="floatleft" style="font-size: 10px; margin-right: 5px"> '.$separator.' </span>';
		}
	}  else if ($params->get('showLast', 1)) { // when $i == $count -1 and 'showLast' is true
		if($i > 0){
			echo ' <span class="floatleft" style="font-size: 10px; margin-right: 5px"> '.$separator.' </span>';
		}
		echo '<span class="no-link">' . $list[$i]->name . '</span>';
	}
endfor; ?>
	<div class="clear: both"></div>
</div>
