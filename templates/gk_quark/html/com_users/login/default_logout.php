<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_users' . DS . 'login' . DS . 'default_logout.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<div class="logout<?php echo $this->pageclass_sfx?>">
	<?php if($this->params->get('show_page_heading') || (($this->params->get('logoutdescription_show') == 1 && str_replace(' ', '', $this->params->get('logout_description')) != '')|| $this->params->get('logout_image') != '')) : ?>
	<header class="component-header">
	        <?php if ($this->params->get('show_page_heading')) : ?>
	        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	        <?php endif; ?>
	        <?php if (($this->params->get('logoutdescription_show') == 1 && str_replace(' ', '', $this->params->get('logout_description')) != '')|| $this->params->get('logout_image') != '') : ?>
	        <div>
	                  <?php endif ; ?>
	                  <?php if (($this->params->get('logout_image')!='')) :?>
	                  <img src="<?php echo $this->escape($this->params->get('logout_image')); ?>" class="logout-image" alt="<?php echo JTEXT::_('COM_USER_LOGOUT_IMAGE_ALT')?>"/>
	                  <?php endif; ?>
	                  <?php if ($this->params->get('logoutdescription_show') == 1) : ?>
	                  <div><?php echo $this->params->get('logout_description'); ?></div>
	                  <?php endif; ?>
	                  <?php if (($this->params->get('logoutdescription_show') == 1 && str_replace(' ', '', $this->params->get('logout_description')) != '')|| $this->params->get('logout_image') != '') : ?>
	        </div>
	        <?php endif ; ?>
	</header>
	<?php endif; ?>
	
	<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
	  <div>
	      <button type="submit" class="button"><?php echo JText::_('JLOGOUT'); ?></button>
	      <input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('logout_redirect_url', $this->form->getValue('return'))); ?>" />
	      <?php echo JHtml::_('form.token'); ?>
	  </div>
	</form>
</div>
<?php endif; ?>