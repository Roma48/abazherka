<?php

/**
 * @package		K2
 * @author		GavickPro http://gavick.com
 */

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_k2' . DS . 'templates' . DS . 'default' . DS . 'item_comments_form.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<form action="<?php echo JURI::root(true); ?>/index.php" method="post" id="comment-form" class="form-validate">
     <?php if($this->params->get('commentsFormNotes')): ?>
     <p class="itemCommentsFormNotes">
          <?php if($this->params->get('commentsFormNotesText')): ?>
          <?php echo nl2br($this->params->get('commentsFormNotesText')); ?>
          <?php else: ?>
          <?php echo JText::_('K2_COMMENT_FORM_NOTES') ?>
          <?php endif; ?>
     </p>
     <?php endif; ?>
     <input class="inputbox" type="text" name="userName" id="userName" value="<?php echo JText::_('TPL_GK_LANG_COMMENT_FORM_NAME'); ?>" onblur="if(this.value=='') this.value='<?php echo JText::_('TPL_GK_LANG_COMMENT_FORM_NAME'); ?>';" onfocus="if(this.value=='<?php echo JText::_('TPL_GK_LANG_COMMENT_FORM_NAME'); ?>') this.value='';" />
     <input class="inputbox" type="text" name="commentEmail" id="commentEmail" value="<?php echo JText::_('TPL_GK_LANG_COMMENT_FORM_EMAIL'); ?>" onblur="if(this.value=='') this.value='<?php echo JText::_('TPL_GK_LANG_COMMENT_FORM_EMAIL'); ?>';" onfocus="if(this.value=='<?php echo JText::_('TPL_GK_LANG_COMMENT_FORM_EMAIL'); ?>') this.value='';" />
     <input class="inputbox" type="text" name="commentURL" id="commentURL" value="<?php echo JText::_('TPL_GK_LANG_COMMENT_FORM_WWW'); ?>"  onblur="if(this.value=='') this.value='<?php echo JText::_('TPL_GK_LANG_COMMENT_FORM_WWW'); ?>';" onfocus="if(this.value=='<?php echo JText::_('TPL_GK_LANG_COMMENT_FORM_WWW'); ?>') this.value='';" />
     <textarea rows="20" cols="10" class="inputbox" onblur="if(this.value=='') this.value='<?php echo JText::_('TPL_GK_LANG_COMMENT_FORM_MESSAGE'); ?>';" onfocus="if(this.value=='<?php echo JText::_('TPL_GK_LANG_COMMENT_FORM_MESSAGE'); ?>') this.value='';" name="commentText" id="commentText"><?php echo JText::_('TPL_GK_LANG_COMMENT_FORM_MESSAGE'); ?></textarea>
     <?php if($this->params->get('recaptcha') && ($this->user->guest || $this->params->get('recaptchaForRegistered', 1))): ?>
     <label class="formRecaptcha"><?php echo JText::_('K2_ENTER_THE_TWO_WORDS_YOU_SEE_BELOW'); ?></label>
     <div id="recaptcha"></div>
     <?php endif; ?>
     <input type="submit" id="submitCommentButton" value="<?php echo JText::_( 'K2_SUBMIT_COMMENT' ); ?>" />
     <span id="formLog"></span>
     <input type="hidden" name="option" value="com_k2" />
     <input type="hidden" name="view" value="item" />
     <input type="hidden" name="task" value="comment" />
     <input type="hidden" name="itemID" value="<?php echo JRequest::getInt('id'); ?>" />
     <?php echo JHTML::_('form.token'); ?>
</form>
<?php endif; ?>