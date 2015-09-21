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
$override .=  'com_contact' . DS . 'contact' . DS . 'default.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<?php
$cparams = JComponentHelper::getParams ('com_media');
?>

<div class="contact <?php echo $this->pageclass_sfx?>" itemscope itemtype="http://schema.org/Person">
     <?php if ($this->params->get('show_page_heading', 1)) : ?>
     <header class="component-header">
          <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
     </header>
     <?php endif; ?>
     
     <div class="contact-form">
          <?php if ($this->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id)) : ?>
          <?php if ($this->params->get('presentation_style')!='plain'):?>
          <?php  echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_EMAIL_FORM'), 'display-form');  ?>
          <?php endif; ?>
          <?php echo $this->loadTemplate('form');  ?>
          <?php endif; ?>
          <?php if ($this->params->get('show_links')) : ?>
          <?php echo $this->loadTemplate('links'); ?>
          <?php endif; ?>
     </div>
     
     <div class="contact-details">
          <?php if ($this->contact->name && $this->params->get('show_name')) : ?>
          <h3><span class="contact-name" itemprop="name"><?php echo $this->contact->name; ?></span></h3>
          <?php endif; ?>

		  <?php if ($this->contact->image && $this->params->get('show_image')) : ?>
		  <div class="contact-image">
		       <?php echo JHtml::_('image', $this->contact->image, JText::_('COM_CONTACT_IMAGE_DETAILS'), array('align' => 'middle', 'itemprop' => 'image')); ?>
		  </div>
		  <?php endif; ?>
		  
		  <?php if($this->contact->misc != '') : ?>
		  <div class="contact-misc">
		  	  <?php echo $this->contact->misc; ?>
		  </div>
		  <?php endif; ?>

          <?php if ($this->contact->con_position && $this->params->get('show_position')) : ?>
          <p class="contact-position" itemprop="jobTitle"><?php echo $this->contact->con_position; ?></p>
          <?php endif; ?>
          
          <?php echo $this->loadTemplate('address'); ?>
          
          <?php if ($this->params->get('allow_vcard')) : ?>
          <?php echo JText::_('COM_CONTACT_DOWNLOAD_INFORMATION_AS');?>
          <a href="<?php echo JRoute::_('index.php?option=com_contact&amp;view=contact&amp;id='.$this->contact->id . '&amp;format=vcf'); ?>">
               <?php echo JText::_('COM_CONTACT_VCARD');?>
          </a>
          <?php endif; ?>
     </div>
     
     <?php if ($this->params->get('show_articles') && $this->contact->user_id && $this->contact->articles) : ?>
     	<?php echo '<h3>'. JText::_('JGLOBAL_ARTICLES').'</h3>'; ?>
     	<?php echo $this->loadTemplate('articles'); ?>
     <?php endif; ?>
     
     <?php if ($this->params->get('show_profile') && $this->contact->user_id && JPluginHelper::isEnabled('user', 'profile')) : ?>
	     <?php echo '<h3>'. JText::_('COM_CONTACT_PROFILE').'</h3>'; ?>
	     <?php echo $this->loadTemplate('profile'); ?>
     <?php endif; ?>
</div>
<?php endif; ?>