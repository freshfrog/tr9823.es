<?php
/**
 * @package        Joomla.Administrator
 * @subpackage     com_modules
 * @copyright      Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
$fieldSet      = $that->form->getFieldset('roksprocket');
$hidden_fields = '';
?>

<div class="panel-left">
	<div class="panel-left-wrapper">
    	<?php  echo RokCommon_Composite::get('roksprocket.module.edit')->load('edit_articles.php', array('that'=>$that)); ?>
	</div>
</div>
<div class="panel-right">
	<ul>
	    <?php
	    	foreach ($fieldSet as $field) :

            foreach(array('group_open','group_bit', 'group_close','group_class') as $group){
                ${$group} = $that->form->getFieldAttribute($field->fieldname, $group, false, 'params');
            }
	    ?>
	    <?php if (!$field->hidden) : ?>
	        <?php
	        	if ($group_open) echo "<li".($group_class ? " class=\"".$group_class."\"" : "").">".$field->label.$field->input;
	        	else if ($group_bit) echo "<div class=\"group-bit\"><div class=\"group-label\">".$field->label."</div><div class=\"group-field\">".$field->input."</div></div>";
	    		else if ($group_close) echo "<div class=\"group-bit\"><div class=\"group-label\">".$field->label."</div><div class=\"group-field\">".$field->input."</div></div></li>";
	        	else echo "<li>".$field->label.$field->input."</li>";
	    	?>

	    <?php else : $hidden_fields .= $field->input; ?>
	    <?php endif; ?>
	    <?php endforeach; ?>
	</ul>
	<?php echo $hidden_fields; ?>
</div>
<div class="clr"></div>
