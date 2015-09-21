<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_contact' . DS . 'featured' . DS . 'default.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<?php

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

?>

<div class="blog-featured<?php echo $this->pageclass_sfx;?>">
          <?php if ($this->params->get('show_page_heading')!=0 ): ?>
          <header class="component-header">
              <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
          </header>
          <?php endif; ?>
          <?php echo $this->loadTemplate('items'); ?>
          <?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->get('pages.total') > 1)) : ?>
          <?php echo str_replace('</ul>', '<li class="counter">'.$this->pagination->getPagesCounter().'</li>', $this->pagination->getPagesLinks()); ?>
          <?php endif; ?>
</div>
<?php endif; ?>