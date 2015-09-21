<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_finder
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_finder' . DS . 'search' . DS . 'default.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>
<?php
JHtml::_('behavior.core');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::stylesheet('com_finder/finder.css', false, true, false);

if ($this->params->get('show_advanced', 1) || $this->params->get('show_autosuggest', 1))
{
	JHtml::_('jquery.framework');
	$script = "
jQuery(function() {";
	if ($this->params->get('show_advanced', 1))
	{
		/*
		* This segment of code disables select boxes that have no value when the
		* form is submitted so that the URL doesn't get blown up with null values.
		*/
		$script .= "
	jQuery('#finder-search').on('submit', function(e){
		e.stopPropagation();
		// Disable select boxes with no value selected.
		jQuery('#advancedSearch').find('select').each(function(index, el) {
			var el = jQuery(el);
			if(!el.val()){
				el.attr('disabled', 'disabled');
			}
		});
	});";
	}
	/*
	* This segment of code sets up the autocompleter.
	*/
	if ($this->params->get('show_autosuggest', 1))
	{
		JHtml::_('script', 'media/jui/js/jquery.autocomplete.min.js', false, false, false, false, true);
		$script .= "
	var suggest = jQuery('#q').autocomplete({
		serviceUrl: '" . JRoute::_('index.php?option=com_finder&task=suggestions.suggest&format=json&tmpl=component', false) . "',
		paramName: 'q',
		minChars: 1,
		maxHeight: 400,
		width: 300,
		zIndex: 9999,
		deferRequestBy: 500
	});
	
	var wrap = jQuery('#advanced-search');
	wrap.css('display', 'block');
	wrap.hide();
	var wrap_state = false;
	
	jQuery('#advanced-search-toggle').click(function() {
		if(wrap_state) {
			wrap.hide();
			wrap_state = false;
		} else {
			wrap.show();
			wrap_state = true;
		}
	});
	";
	}
	$script .= "
});";
	JFactory::getDocument()->addScriptDeclaration($script);
}

?>

<div class="search-page finder<?php echo $this->pageclass_sfx; ?>">
	<form id="finder-search" action="<?php echo JRoute::_($this->query->toURI()); ?>" method="get">
		<div class="header<?php if($templateParams->get('search_page_header_image') == '') : ?> no-image<?php endif; ?>">
			<?php if($templateParams->get('search_page_header_image') != '') : ?>
			<img src="<?php echo $templateParams->get('search_page_header_image'); ?>" alt="" />
			<?php endif; ?>
			
			<div class="header-content gkPage">
				<?php if ($this->params->get('show_page_heading', 1)) : ?>
				<header>
					<h1>
						<?php if ($this->escape($this->params->get('page_heading'))) : ?>
							<?php echo $this->escape($this->params->get('page_heading')); ?>
						<?php else : ?>
							<?php echo $this->escape($this->params->get('page_title')); ?>
						<?php endif; ?>
					</h1>
				</header>
				<?php endif; ?>

				<?php if ($this->params->get('show_search_form', 1)): ?>
				<div id="search-form">
					<?php echo $this->getFields(); ?>
				
					<?php
					/*
					 * DISABLED UNTIL WEIRD VALUES CAN BE TRACKED DOWN.
					 */
					if (false && $this->state->get('list.ordering') !== 'relevance_dsc'): ?>
						<input type="hidden" name="o" value="<?php echo $this->escape($this->state->get('list.ordering')); ?>" />
					<?php endif; ?>
				
					<fieldset class="word">
						<input type="text" name="q" id="q" size="30" value="<?php echo $this->escape($this->query->input); ?>" class="inputbox" />
						<?php if ($this->escape($this->query->input) != '' || $this->params->get('allow_empty_search')):?>
							<button name="Search" type="submit" class="btn-border"><?php echo JText::_('JSEARCH_FILTER_SUBMIT');?></button>
						<?php else: ?>
							<button name="Search" type="" class="btn-border"><?php echo JText::_('JSEARCH_FILTER_SUBMIT');?></button>
						<?php endif; ?>
					</fieldset>
				</div>
			</div>
		</div>
		<?php endif; ?>
		
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
			<?php if ($this->params->get('show_advanced', 1)): ?>
			<a id="advanced-search-toggle"><?php echo JText::_('COM_FINDER_ADVANCED_SEARCH_TOGGLE'); ?></a>
	
			<div id="advanced-search" style="display: none;">
				<?php if ($this->params->get('show_advanced_tips', 1)): ?>
					<div class="advanced-search-tip">
						<?php echo JText::_('COM_FINDER_ADVANCED_TIPS'); ?>
					</div>
				<?php endif; ?>
				<div id="finder-filter-window">
					<?php echo JHtml::_('filter.select', $this->query, $this->params); ?>
				</div>
			</div>
			<?php endif; ?>
			
			<?php
			// Load the search results layout if we are performing a search.
			if ($this->query->search === true):
			?>
			
			<div id="search-results">		
				<?php echo $this->loadTemplate('results'); ?>
			</div>
			<?php endif; ?>
		</div>
	</form>
</div>
<?php endif; ?>