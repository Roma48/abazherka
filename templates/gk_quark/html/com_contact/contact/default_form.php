<?php

 /**
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_contact' . DS . 'contact' . DS . 'default_form.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<?php
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.tooltip');
?>

<?php if (isset($this->error)) : ?>
<div class="contact-error"> <?php echo $this->error; ?> </div>
<?php endif; ?>

<form id="contact-form" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate">
  <fieldset>     
       <p class="contact-name"><?php echo $this->form->getLabel('contact_name'); ?> <?php echo $this->form->getInput('contact_name'); ?> </p>
       <p class="contact-email"><?php echo $this->form->getLabel('contact_email'); ?> <?php echo $this->form->getInput('contact_email'); ?></p>
       <p><?php echo $this->form->getLabel('contact_subject'); ?> <?php echo $this->form->getInput('contact_subject'); ?></p>
       <?php //Dynamically load any additional fields from plugins. ?>
       <?php foreach ($this->form->getFieldsets() as $fieldset): ?>
       <?php if ($fieldset->name != 'contact'):?>
       <?php $fields = $this->form->getFieldset($fieldset->name);?>
       <?php foreach($fields as $field): ?>
       <?php if ($field->hidden): ?>
       <?php echo $field->input;?>
       <?php else:?>
       <p> <?php echo $field->label; ?>
            <?php if (!$field->required && $field->type != "Spacer"): ?>
            <span class="optional"><?php echo JText::_('COM_CONTACT_OPTIONAL');?></span>
            <?php endif; ?>
            <?php echo $field->input;?></p>
       <?php endif;?>
       <?php endforeach;?>
       <?php endif ?>
       <?php endforeach;?>
       <p><?php echo $this->form->getLabel('contact_message'); ?> <?php echo $this->form->getInput('contact_message'); ?></p>
       <p>
            <?php 	if ($this->params->get('show_email_copy')){ ?>
            <?php echo $this->form->getLabel('contact_email_copy'); ?> <?php echo $this->form->getInput('contact_email_copy'); ?>
            <?php 	} ?>
       </p>
       <p>
            <button class="button validate" type="submit"><?php echo JText::_('COM_CONTACT_CONTACT_SEND'); ?></button>
            <input type="hidden" name="option" value="com_contact" />
            <input type="hidden" name="task" value="contact.submit" />
            <input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
            <input type="hidden" name="id" value="<?php echo $this->contact->slug; ?>" />
            <?php echo JHtml::_( 'form.token' ); ?> </p>
  </fieldset>
</form>
<?php endif; ?>