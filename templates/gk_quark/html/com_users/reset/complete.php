<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_users' . DS . 'reset' . DS . 'complete.php';

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
?>

<div class="reset<?php echo $this->pageclass_sfx?>">
     <?php if ($this->params->get('show_page_heading')) : ?>
     <header class="component-header">
          <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
     </header>
     <?php endif; ?>
     <form action="<?php echo JRoute::_('index.php?option=com_users&task=reset.complete'); ?>" method="post" class="form-validate">
          <?php foreach ($this->form->getFieldsets() as $fieldset): ?>
          <p>
               <?php echo JText::_($fieldset->label); ?>
          </p>
          <fieldset>
               <p class="login-fields">
                    <?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field): ?>
                    <?php echo $field->label; ?> <?php echo $field->input; ?>
                    <?php endforeach; ?>
               </p>
          </fieldset>
          <?php endforeach; ?>
          <div>
               <button type="submit" class="validate"><?php echo JText::_('JSUBMIT'); ?></button>
               <?php echo JHtml::_('form.token'); ?> </div>
     </form>
</div>
<?php endif; ?>