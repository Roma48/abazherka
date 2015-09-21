<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_users' . DS . 'registration' . DS . 'default.php';

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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidator');
?>

<div class="registration<?php echo $this->pageclass_sfx?>">
     <?php if ($this->params->get('show_page_heading')) : ?>
     <header class="component-header">
          <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
     </header>
     <?php endif; ?>
     <form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
          <?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one.?>
          <?php $fields = $this->form->getFieldset($fieldset->name);?>
          <?php if (count($fields)):?>
          <fieldset>
               <?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
               <?php endif;?>
               <?php foreach($fields as $field):// Iterate through the fields in the set and display them.?>
               <?php if ($field->hidden):// If the field is hidden, just display the input.?>
               <?php echo $field->input;?>
               <?php else:?>
               <p class="login-fields">
                    <?php echo $field->label; ?>
                    <?php if (!$field->required && $field->type != 'Spacer'): ?>
                    <span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL');?></span>
                    <?php endif; ?>
                    <?php echo $field->input;?>
               </p>
               <?php endif;?>
               <?php endforeach;?>
          </fieldset>
          <?php endif;?>
          <?php endforeach;?>
          <div>
               <button type="submit" class="validate"><?php echo JText::_('JREGISTER');?></button>
               <a class="btn-border" href="<?php echo JRoute::_('');?>" title="<?php echo JText::_('JCANCEL');?>"><?php echo JText::_('JCANCEL');?></a>
               <input type="hidden" name="option" value="com_users" />
               <input type="hidden" name="task" value="registration.register" />
               <?php echo JHtml::_('form.token');?> </div>
     </form>
</div>
<?php endif; ?>