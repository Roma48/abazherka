<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_content' . DS . 'featured' . DS . 'default.php';

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
$document = JFactory::getDocument();
$templateParams = JFactory::getApplication()->getTemplate(true)->params;

?>

<div id="comContentContainer" class="blog-page <?php echo $this->pageclass_sfx;?>" itemscope itemtype="http://schema.org/Blog">
	<?php if (
		!$document->countModules('header') && 
		(
			$this->params->get('show_page_heading') != 0 ||
			$templateParams->get('display_mouse_icon', '1') != '0'
		)
	) : ?>
	<div class="header no-image">
		<?php if ($this->params->get('show_page_heading') != 0) : ?>
		<div class="item-title-wrap">
			<h1 class="item-title" data-sr="enter bottom and move 50px"><?php echo $this->params->get('page_heading'); ?></h1>
		</div>
		<?php endif; ?>
		
		<?php if($templateParams->get('display_mouse_icon', '1') != '0') : ?>
		<span class="mouse-icon"><span><span></span></span></span> 
		<?php endif; ?>
	</div>	
	<?php endif; ?>
	
	<?php 
	 	$renderer = $document->loadRenderer('modules');
	 	
	 	if($document->countModules('breadcrumb')) {
			echo '<div id="gkBreadcrumb">';
			echo '<div class="gkPage">';
			echo $renderer->render('breadcrumb', array('style' => 'none'), null); 
			echo '</div>';
			echo '</div>';
		}
	?>
	
	<div class="gkPage">
		<div id="gk-content-wrapper">
			<?php 
				$items = array();
				
				if (!empty($this->lead_items)) $items = array_merge($items, $this->lead_items);
				if (!empty($this->intro_items)) $items = array_merge($items, $this->intro_items);
			
				if(count($items)): 
			?>
		    <div class="item-list">
		    	<?php foreach($items as $item): ?>
					<?php
						$this->item = $item;
						echo $this->loadTemplate('item');
					?>
		         <?php endforeach; ?>
		    </div>
		    
		    <?php if (
		    	($this->params->def('show_pagination', 1) == 1  || $this->params->get('show_pagination') == 2) && 
		    	$this->pagination->get('pages.total') > 1
		    ) : ?>
		    	<?php echo $this->pagination->getPagesLinks(); ?>
		    <?php endif; ?>
		    
		    <?php endif; ?>
		</div>
		
		<?php 
		 	$renderer = $document->loadRenderer('modules');
		 	
		 	if($document->countModules('sidebar')) {
		 		echo '<aside id="gkSidebar">';
		 		echo '<div>';
		 		echo $renderer->render('sidebar', array('style' => 'gk_style'), null); 
		 		echo '</div>';
		 		echo '</aside>';
		 	}
		?>
    </div>
</div>
<?php endif; ?>