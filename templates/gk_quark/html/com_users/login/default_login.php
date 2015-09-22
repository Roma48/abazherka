<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_users' . DS . 'login' . DS . 'default_login.php';

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
?>

<div class="login <?php echo $this->pageclass_sfx?>">
     <?php if ($this->params->get('show_page_heading')) : ?>
     <header class="component-header">
          <h1><?php echo $this->escape($this->params->get('page_heading')); ?></span></h1>
     </header>
     <?php endif; ?>
     <gavern:fblogin>
     <span id="fb-auth"><small>fb icon</small><?php echo JText::_('TPL_GK_LANG_FB_LOGIN_TEXT'); ?></span>
     <gavern:fblogin>
     <?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
     <div class="login-description">
          <?php endif ; ?>
          <?php if($this->params->get('logindescription_show') == 1) : ?>
          <?php echo $this->params->get('login_description'); ?>
          <?php endif; ?>
          <?php if (($this->params->get('login_image')!='')) :?>
          <img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT')?>"/>
          <?php endif; ?>
          <?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
     </div>
     <?php endif ; ?>
     <form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post">
          <fieldset>
               <?php foreach ($this->form->getFieldset('credentials') as $field): ?>
               <?php if (!$field->hidden): ?>
               <p class="login-fields">
                    <?php echo $field->label; ?> <?php echo $field->input; ?>
               </p>
               <?php endif; ?>
               <?php endforeach; ?>
               <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
               <p class="login-fields">
                    <label id="remember-lbl" for="remember">
                         <input id="remember" type="checkbox" name="remember" class="inputbox" value="yes"  alt="<?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>" />
                         <?php echo JText::_('JGLOBAL_REMEMBER_ME') ?></label>
               </p>
               <?php endif; ?>
               <button type="submit" class="button"><?php echo JText::_('JLOGIN'); ?></button>
               <input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />
               <?php echo JHtml::_('form.token'); ?>
               <ul>
                    <li> <a class="inverse" href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"> <?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a> </li>
                    <li> <a class="inverse" href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>"> <?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a> </li>
                    <?php
			$usersConfig = JComponentHelper::getParams('com_users');
			if ($usersConfig->get('allowUserRegistration')) : ?>
                    <li class="last"> <a class="btn-border" href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>"> <?php echo JText::_('COM_USERS_LOGIN_REGISTER'); ?></a> </li>
                    <?php endif; ?>
               </ul>
          </fieldset>
     </form>
</div>
<?php endif; ?>