<?php

// No direct access.
defined('_JEXEC') or die;

if($this->API->get('webmaster_contact_type') != 'none') {
     // get the webmaster e-mail value
     $webmaster_contact = $this->API->get('webmaster_contact', '');
     if($this->API->get('webmaster_contact_type') == 'email') {
          // e-mail cloak
          $searchEmail = '([\w\.\-]+\@(?:[a-z0-9\.\-]+\.)+(?:[a-z0-9\-]{2,4}))';
          $searchText = '([\x20-\x7f][^<>]+)';
          $pattern = '~(?:<a [\w "\'=\@\.\-]*href\s*=\s*"mailto:' . $searchEmail . '"[\w "\'=\@\.\-]*)>' . $searchText . '</a>~i';   
          preg_match($pattern, '<a href="mailto:'.$webmaster_contact.'">'.JText::_('TPL_GK_LANG_CONTACT_WEBMASTER').'</a>', $regs, PREG_OFFSET_CAPTURE);
          $replacement = JHtml::_('email.cloak', $regs[1][0], 1, $regs[2][0], 0);
          $webmaster_contact_email = substr_replace($webmaster_contact, $replacement, $regs[0][1], strlen($regs[0][0]));
     }
}

$error_code = JRequest::getCmd('error', '');

if($error_code == '') {
	$error_code = '404';
}

$document = JFactory::getDocument();
$document->setTitle(JText::_('TPL_GK_LANG_ERROR_TITLE_PRE') . $error_code . JText::_('TPL_GK_LANG_ERROR_TITLE_POST'));

?>

<div class="gkPage error-page-container">
     <h1><?php echo $error_code; ?></h1>
     <?php if($error_code == '404') : ?>
     <h2><?php echo JText::_('TPL_GK_LANG_ERROR_TITLE_404'); ?></h2>
     <?php else : ?>
     <h2><?php echo JText::_('TPL_GK_LANG_ERROR_TITLE_PRE') . $error_code . JText::_('TPL_GK_LANG_ERROR_TITLE_POST'); ?></h2>
     <?php endif; ?>
     
</div>
<?php if($error_code == '404') : ?>
<script type="text/javascript">
var GOOG_FIXURL_LANG = '<?php echo JText::_('TPL_GK_LANG_ERROR_CODE'); ?>';
var GOOG_FIXURL_SITE = '<?php echo JURI::base(); ?>';
</script> 
<script type="text/javascript" src="https://linkhelp.clients.google.com/tbproxy/lh/wm/fixurl.js"></script>
<?php endif; ?>

<p class="error-links">
      <a href="<?php echo JURI::base(); ?>"><?php echo JText::_('TPL_GK_LANG_ERROR_TITLE_BACK_TO_HOMEPAGE'); ?></a>
      <?php if($this->API->get('webmaster_contact_type') == 'email') : ?>
      <?php echo $webmaster_contact_email; ?>
      <?php elseif($this->API->get('webmaster_contact_type') == 'url') : ?>
      <a href="<?php echo $webmaster_contact; ?>">
           <?php  echo JText::_('TPL_GK_LANG_CONTACT_WEBMASTER'); ?>
      </a>
      <?php endif; ?>
</p>