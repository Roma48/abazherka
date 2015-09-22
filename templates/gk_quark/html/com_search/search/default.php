<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_search' . DS . 'search' . DS . 'default.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<div class="search-page search<?php echo $this->pageclass_sfx; ?>">
	<form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_search');?>" method="post">
		<div class="header<?php if($templateParams->get('search_page_header_image') == '') : ?> no-image<?php endif; ?>">
			<?php if($templateParams->get('search_page_header_image') != '') : ?>
			<img src="<?php echo $templateParams->get('search_page_header_image'); ?>" alt="" />
			<?php endif; ?>
			
			<div class="header-content gkPage">
				<?php if ($this->params->get('show_page_heading', 1)) : ?>
				<header>
					<h1>
				      <?php if ($this->escape($this->params->get('page_heading'))) :?>
				      <?php echo $this->escape($this->params->get('page_heading')); ?>
				      <?php else : ?>
				      <?php echo $this->escape($this->params->get('page_title')); ?>
				      <?php endif; ?>
					</h1>
				</header>
				<?php endif; ?>
				<?php
					$lang = JFactory::getLanguage();
					$upper_limit = $lang->getUpperLimitSearchWord();
				?>
				<fieldset class="word">
					<input type="text" name="searchword" id="search-searchword" size="30" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>" class="inputbox" />
					<button name="Search" onclick="this.form.submit()" class="btn-border"><?php echo JText::_('COM_SEARCH_SEARCH');?></button>
					<input type="hidden" name="task" value="search" />
				</fieldset>
			
				<div class="searchintro<?php echo $this->params->get('pageclass_sfx'); ?>">
					<?php if (!empty($this->searchword)):?>
					<p><?php echo JText::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', $this->total);?></p>
					<?php endif;?>
				</div>
			</div>
		</div>
		
		<?php 
		 	$document = JFactory::getDocument();
		 	$renderer = $document->loadRenderer('modules');
		 	
		 	if($document->countModules('breadcrumb')) {
				echo '<div id="gkBreadcrumb">';
				echo '<div class="gkPage">';
				echo $renderer->render('breadcrumb', array('style' => 'none'), null); 
				echo '</div>';
				echo '</div>';
			}
		?>
		
		<div class="gk-search-results gkPage">
			<fieldset class="phrases">
				<legend><?php echo JText::_('COM_SEARCH_FOR');?></legend>
				<div class="phrases-box">
					<?php echo $this->lists['searchphrase']; ?>
				</div>
				<div class="ordering-box">
					<label for="ordering" class="ordering">
						<?php echo JText::_('COM_SEARCH_ORDERING');?>
						<?php echo $this->lists['ordering'];?>
					</label>
				</div>
			</fieldset>
		
			<?php if ($this->params->get('search_areas', 1)) : ?>
			<fieldset class="only">
				<legend><?php echo JText::_('COM_SEARCH_SEARCH_ONLY');?></legend>
				<?php foreach ($this->searchareas['search'] as $val => $txt) :
					$checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="checked"' : '';
				?>
					<label for="area-<?php echo $val;?>">
						<input type="checkbox" name="areas[]" value="<?php echo $val;?>" id="area-<?php echo $val;?>" <?php echo $checked;?> />
						<?php echo JText::_($txt); ?>
					</label>
				<?php endforeach; ?>
			</fieldset>
			<?php endif; ?>
		
			<?php if ($this->total > 0) : ?>
			<div class="form-limit">
				<label for="limit">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
			<?php endif; ?>
			
			<?php if ($this->error==null && count($this->results) > 0) :
				echo $this->loadTemplate('results');
			else :
				echo $this->loadTemplate('error');
			endif; ?>
		</div>
	</form>
</div>
<?php endif; ?>