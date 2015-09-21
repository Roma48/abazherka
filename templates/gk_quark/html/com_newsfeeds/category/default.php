<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_newsfeeds' . DS . 'category' . DS . 'default.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<div class="category<?php echo $this->pageclass_sfx;?>">
    <?php 
		if(
				$this->params->get('show_page_heading', 1) ||
				$this->params->get('show_category_title', 1) ||
				(
					(
						$this->params->get('show_description', 1) || 
						$this->params->def('show_description_image', 1)
					)
					&&
					(
						(
							$this->params->get('show_description_image') && 
							$this->category->getParams()->get('image')
						)
						||
						(
							$this->params->get('show_description') && 
							$this->category->description
						)
					)
				)
		) : 
	?>
	<header class="component-header">
        <?php if ($this->params->get('show_page_heading', 1)) : ?>
        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        <?php endif; ?>
        <?php if($this->params->get('show_category_title', 1)) : ?>
        <h2><?php echo JHtml::_('content.prepare', $this->category->title, '', 'com_newsfeeds.category'); ?></h2>
        <?php endif; ?>
        <?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
        <div>
	          <?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
	          <img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
	          <?php endif; ?>
	          <?php if ($this->params->get('show_description') && $this->category->description) : ?>
	          <?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_newsfeeds.category'); ?>
	          <?php endif; ?>
	          
	          
	          <?php if ($this->params->get('show_tags', 1) && !empty($this->category->tags->itemTags)) : ?>
	           		<span class="tags-label"><?php echo JText::sprintf('TPL_GK_LANG_TAGGED_UNDER'); ?></span>
	                <?php $this->category->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
	                <?php echo $this->category->tagLayout->render($this->category->tags->itemTags); ?>
	           <?php endif; ?>
        </div>
        <?php endif; ?>
	</header>
	<?php endif; ?>
	
	<?php echo $this->loadTemplate('items'); ?>
	<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
	<div class="children">
	        <h3><?php echo JText::_('JGLOBAL_SUBCATEGORIES') ; ?></h3>
	        <?php echo $this->loadTemplate('children'); ?> 
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>