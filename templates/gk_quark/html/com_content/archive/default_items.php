<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_content' . DS . 'archive' . DS . 'default_items.php';

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
$params = &$this->params;

?>

<?php foreach ($this->items as $i => $item) : ?>
<article class="item-view" itemscope itemtype="http://schema.org/Article">	
	<div class="header">
		<h2 class="item-title">
			<?php if ($params->get('link_titles')): ?>
			<a class="inverse" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language)); ?>">
				<?php echo $this->escape($item->title); ?>
			</a>
			<?php else: ?>
				<?php echo $this->escape($item->title); ?>
			<?php endif; ?>
		</h2>
		
		<?php if (
			($params->get('show_author')) or 
			($params->get('show_parent_category')) or 
			($params->get('show_category')) or 
			($params->get('show_create_date')) or
			($params->get('show_modify_date')) or 
			($params->get('show_publish_date'))  or 
			($params->get('show_hits')) or 
			($params->get('show_create_date')) 
		) : ?>
			<ul class="item-info">
				<?php if ($params->get('show_create_date')) : ?>
				<li><time datetime="<?php echo JHtml::_('date', $item->created, DATE_W3C); ?>" itemprop="dateCreated">
					<?php echo JHTML::_('date', $item->created, 'd.m.Y'); ?></time></li>
				<?php elseif ($params->get('show_publish_date')) : ?>
				<li><time datetime="<?php echo JHtml::_('date', $this->item->publish_up, JText::_(DATE_W3C)); ?>" itemprop="datePublished"><?php echo JHtml::_('date', $this->item->publish_up, JText::_('d.m.Y')); ?></time></li>
				<?php endif; ?>
				<?php if ($params->get('show_parent_category')) : ?>
				<li class="parent-category-name" itemprop="genre">
					<?php	$title = $this->escape($item->parent_title);
							$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($item->parent_slug)).'">'.$title.'</a>';?>
					<?php if ($params->get('link_parent_category') && $item->parent_slug) : ?>
						<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
						<?php else : ?>
						<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
					<?php endif; ?>
				</li>
				<?php endif; ?>
		
				<?php if ($params->get('show_category')) : ?>
				<?php	$title = $this->escape($item->category_title);
						$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug)) . '">' . $title . '</a>'; ?>
				<li class="category-name" itemprop="genre"><?php if ($params->get('link_category') && $item->catslug) : ?><?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?><?php else : ?><?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?><?php endif; ?></li>
				<?php endif; ?>
		
				<?php if ($params->get('show_modify_date')) : ?>
				<li class="modified"><time datetime="<?php echo JHtml::_('date', $item->modified, DATE_W3C); ?>" itemprop="dateModified"><?php echo JHtml::_('date', $item->modified, JText::_('l, j F Y')); ?></time></li>
				<?php endif; ?>
		
				<?php if ($params->get('show_author') && !empty($item->author )) : ?>
				<li class="createdby" itemprop="author" itemscope itemtype="http://schema.org/Person">
				<?php $author =  $item->author; ?>
				<?php $author = ($item->created_by_alias ? $item->created_by_alias : $author);?>
				<?php $author = '<span itemprop="name">' . $author . '</span>'; ?>
					<?php if (!empty($item->contactid ) &&  $params->get('link_author') == true):?>
						<?php 	echo JText::sprintf('COM_CONTENT_WRITTEN_BY' ,
						 JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$item->contactid), $author, array('itemprop' => 'url'))); ?>
					<?php else :?>
						<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
					<?php endif; ?>
				</li>
				<?php endif; ?>
		
				<?php if ($params->get('show_hits')) : ?>
				<li class="hits"><?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $item->hits); ?></li>
				<?php endif; ?>
			</ul>
			<?php endif; ?>
	</div>

	<?php if ($params->get('show_intro')) :?>
	<div class="item-body<?php if (!$params->get('show_publish_date')) : ?> nodate<?php endif; ?>" itemprop="articleBody">
		<?php echo JString::substr(JHtml::_('string.truncate', $item->introtext, $params->get('introtext_limit')), 0, -3); ?>
	</div>
	<?php endif; ?>
</article>
<?php endforeach; ?>

<?php echo str_replace('</ul>', '<li class="counter">'.$this->pagination->getPagesCounter().'</li>', $this->pagination->getPagesLinks()); ?>
<?php endif; ?>