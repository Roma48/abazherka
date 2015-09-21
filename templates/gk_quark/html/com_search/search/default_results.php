<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_search' . DS . 'search' . DS . 'default_results.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<div class="blog-page generic-page search-results<?php echo $this->pageclass_sfx; ?>">
	<div class="gkPage">
		<div class="item-list">  	
			<?php foreach($this->results as $result) : ?>
			<article class="item-view">
				<?php if ($result->href) :?>
				<h2 class="item-title">
					<a href="<?php echo JRoute::_($result->href); ?>"<?php if ($result->browsernav == 1) :?> target="_blank"<?php endif;?> class="inverse">
						<?php echo $this->escape($result->title);?>
					</a>
				</h2>
				<?php else:?>
				<h2 class="item-title">
					<?php echo $this->escape($result->title);?>
				</h2>
				<?php endif; ?>
				
				<?php if ($result->section || ($this->params->get('show_date') && $result->created)) : ?>
				<div class="item-info">
					<?php if ($result->section) : ?>
					<span class="item-category">
						<?php echo $this->escape($result->section); ?>
					</span>
					<?php endif; ?>
					
					<?php if ($this->params->get('show_date') && $result->created) : ?>
					<span class="item-date-created">
						<?php echo JText::sprintf('JGLOBAL_CREATED_DATE_ON', $result->created); ?>
					</span>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				
				<div class="item-body">
					<?php echo $result->text; ?>
				</div>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<?php echo str_replace('</ul>', '<li class="counter">'.$this->pagination->getPagesCounter().'</li>', $this->pagination->getPagesLinks()); ?>
<?php endif; ?>