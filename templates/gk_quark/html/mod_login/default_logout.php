<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'mod_login' . DS . 'default_logout.php';

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
	<h3><?php echo JText::_('TPL_GK_LANG_MYACCOUNT'); ?></h3>
	<form action="<?php echo JRoute::_(JUri::getInstance()->toString(), true, $params->get('usesecure')); ?>" method="post" id="login-form">
          <div class="logout-button">
                <?php if ($params->get('greeting')) : ?>
                    <div class="login-greeting">
                              <?php if($params->get('name') == 0) : {
					echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('name')));
				} else : {
					echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username')));
				} endif; ?>
                    </div>
                    <?php endif; ?>
                    <input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGOUT'); ?>" />
          </div>
          
          <input type="hidden" name="option" value="com_users" />
          <input type="hidden" name="task" value="user.logout" />
          <input type="hidden" name="return" value="<?php echo $return; ?>" />
          <?php echo JHtml::_('form.token'); ?>
	</form>
</div>
<?php endif; ?>