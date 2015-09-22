<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'mod_login' . DS . 'default.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>
<?php JHtml::_('behavior.keepalive'); ?>
<div class="login">
	<h3><?php echo JText::_('TPL_GK_LANG_LOGIN_POPUP'); ?></h3>

	<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >
          <fieldset class="userdata">
                    <p class="login-fields">
                              <label><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label>
                              <input id="modlgn-username" type="text" name="username" class="inputbox"  size="24" />
                    </p>
                    <p class="login-fields">
                    		  <label><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
                              <input id="modlgn-passwd" type="password" name="password" class="inputbox" size="24" />
                    </p>
                    <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                    <div id="form-login-remember">
                              <input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
                              <label for="modlgn-remember"><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label>
                    </div>
                    <?php endif; ?>
                    <button class="button"><?php echo JText::_('JLOGIN') ?></button>
                    <ul>
                              <li> <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>" class="inverse"> <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a> </li>
                              <li> <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>" class="inverse"> <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a> </li>
                              <?php
                              	$usersConfig = JComponentHelper::getParams('com_users');
                              	if ($usersConfig->get('allowUserRegistration')) : 
                              ?>
                              <li class="last"> <a class="btn-border" href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>"> <?php echo JText::_('TPL_GK_LANG_REGISTER_POPUP'); ?></a> </li>
                              <?php endif; ?>
                    </ul>
                    <input type="hidden" name="option" value="com_users" />
                    <input type="hidden" name="task" value="user.login" />
                    <input type="hidden" name="return" value="<?php echo $return; ?>" />
                    <?php echo JHtml::_('form.token'); ?>
                    <gavern:fblogin> <span id="fb-auth"><small>fb icon</small><?php echo JText::_('TPL_GK_LANG_FB_LOGIN_TEXT'); ?></span> </gavern:fblogin>
          </fieldset>
          <div class="posttext"> <?php echo $params->get('posttext'); ?> </div>
	</form>
</div>
<?php endif; ?>