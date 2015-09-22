<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_weblinks' . DS . 'categories' . DS . 'default.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>
<?php

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

?>

<section class="categories-list<?php echo $this->pageclass_sfx;?>">
          <?php if (
				$this->params->get('show_page_heading', 1) ||
				(
					$this->params->get('show_base_description')	&&
					(
						$this->params->get('categories_description') ||
						$this->parent->description
					)
				)
	) : ?>
          <header>
                    <?php if ($this->params->get('show_page_heading', 1)) : ?>
                    <h1 class="header"><span><?php echo $this->escape($this->params->get('page_heading')); ?></span></h1>
                    <?php endif; ?>
                    <?php if ($this->params->get('show_base_description')) : ?>
                    <?php if($this->params->get('categories_description')) : ?>
                    <?php echo JHtml::_('content.prepare', $this->params->get('categories_description'), '', 'com_weblinks.categories'); ?>
                    <?php else: ?>
                    <?php if ($this->parent->description) : ?>
                    <?php echo JHtml::_('content.prepare', $this->parent->description, '', 'com_weblinks.categories'); ?>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php endif; ?>
          </header>
          <?php endif; ?>
          <?php echo $this->loadTemplate('items'); ?> 
</section>
<?php endif; ?>