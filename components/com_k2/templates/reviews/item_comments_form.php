<?php
/**
 * @version		$Id: item_comments_form.php 1492 2012-02-22 17:40:09Z joomlaworks@gmail.com $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div class="module-title">
    <h3 class="title nomargintop" style="4font-size: 190%;"><?php echo JText::_('Deje su comentario') ?></h3>
</div>

<?php if($this->params->get('commentsFormNotes')): ?>
<p class="itemCommentsFormNotes">
	<?php if($this->params->get('commentsFormNotesText')): ?>
	<?php echo nl2br($this->params->get('commentsFormNotesText')); ?>
	<?php else: ?>
	<?php echo JText::_('Los campos con * son obligatorios. Código html no es permitido y será moderado') ?>
	<?php endif; ?>
</p>
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" id="comment-form" class="form-validate">
	<label class="formComment" for="commentText"><?php echo JText::_('Mensaje'); ?> *</label>
	<textarea rows="50" style="width:625px; background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #CCC !important" cols="10" class="inputbox" onblur="if(this.value=='') this.value='<?php echo JText::_('Escriba su mensaje aquí ...'); ?>';" onfocus="if(this.value=='<?php echo JText::_('Escriba su mensaje aquí ...'); ?>') this.value='';" name="commentText" id="commentText"><?php echo JText::_('escriba su mensaje aquí ...'); ?></textarea>

	<label class="formName" for="userName"><?php echo JText::_('Nombre'); ?> *</label>
	<input class="inputbox" style="border: 1px solid #CCC !important" type="text" name="userName" id="userName" value="<?php echo JText::_('escriba su nombre ...'); ?>" onblur="if(this.value=='') this.value='<?php echo JText::_('escriba su nombre ...'); ?>';" onfocus="if(this.value=='<?php echo JText::_('escriba su nombre ...'); ?>') this.value='';" />

	<label class="formEmail" for="commentEmail"><?php echo JText::_('Correo eletrónico'); ?> *</label>
	<input class="inputbox" style="border: 1px solid #CCC !important" type="text" name="commentEmail" id="commentEmail" value="<?php echo JText::_('escriba su dirección de correo electrónico ...'); ?>" onblur="if(this.value=='') this.value='<?php echo JText::_('escriba su dirección de correo electrónico ...'); ?>';" onfocus="if(this.value=='<?php echo JText::_('escriba su dirección de correo electrónico ...'); ?>') this.value='';" />

	<label class="formUrl" for="commentURL"><?php echo JText::_('Sitio web'); ?></label>
	<input class="inputbox" style="border: 1px solid #CCC !important" type="text" name="commentURL" id="commentURL" value="<?php echo JText::_('escriba su sitio web ...'); ?>"  onblur="if(this.value=='') this.value='<?php echo JText::_('escriba su sitio web ...'); ?>';" onfocus="if(this.value=='<?php echo JText::_('escriba su sitio web ...'); ?>') this.value='';" />

	<?php if($this->params->get('recaptcha') && $this->user->guest): ?>
	<label class="formRecaptcha"><?php echo JText::_('K2_ENTER_THE_TWO_WORDS_YOU_SEE_BELOW'); ?></label>
	<div id="recaptcha"></div>
	<?php endif; ?>

	<div id="rt-popuplogin" style="display: block">
	<div class="readon">
	<input type="submit" class="button" style="background-image: url('/templates/rt_enigma/images/style5/readon-l.png')" id="submitCommentButton" value="<?php echo JText::_('Enviar comentario'); ?>" />
	</div>	
	</div>	

	<span id="formLog"></span>
	<input type="hidden" name="option" value="com_k2" />
	<input type="hidden" name="view" value="item" />
	<input type="hidden" name="task" value="comment" />
	<input type="hidden" name="itemID" value="<?php echo JRequest::getInt('id'); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
