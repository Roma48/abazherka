<?php

/**
 * @package		K2
 * @author		GavickPro http://gavick.com
 */

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_k2' . DS . 'templates' . DS . 'default' . DS . 'category.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :


$document = JFactory::getDocument();

?>

<div id="k2Container" class="blog-page <?php if($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">
	<?php if (
		!$document->countModules('header') && 
		(
			$this->params->get('catTitle') ||
			($this->params->get('catImage') && $this->category->image) ||
			$templateParams->get('display_mouse_icon', '1') != '0'
		)
	) : ?>
	<div class="header<?php if(!$this->params->get('catImage') && !empty($this->category->image)): ?> no-image<?php endif; ?>">
		<?php if($this->params->get('catImage') && $this->category->image): ?>
		<img alt="<?php echo K2HelperUtilities::cleanHtml($this->category->name); ?>" src="<?php echo $this->category->image; ?>" />
		<?php endif; ?>

		<?php if($this->params->get('catTitle')): ?>
		<div class="item-title-wrap">
			<h1 class="item-title" data-sr="enter bottom and move 50px"><?php echo $this->category->name; ?></h1>
			<?php if($this->params->get('catDescription')): ?>
			<p class="item-desc" data-sr="enter bottom and move 50px and wait .2s"><?php echo strip_tags($this->category->description, '<br>'); ?></p>
			<?php endif; ?>	
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
				
				if(isset($this->leading)) $items = array_merge($items, $this->leading);	
				if(isset($this->primary)) $items = array_merge($items, $this->primary);			
				if(isset($this->secondary)) $items = array_merge($items, $this->secondary);	
			
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
		    
		    <?php if(count($this->pagination->getPagesLinks())): ?>
			    <?php if($this->params->get('catPagination')): ?>
			    	<?php echo $this->pagination->getPagesLinks(); ?>
			    <?php endif; ?>
		    <?php endif; ?>
		    
		    <?php if($this->params->get('catFeedIcon')): ?>
<!--		    <div class="k2FeedIcon">-->
<!--		    	<a href="--><?php //echo $this->feed; ?><!--" title="--><?php //echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?><!--">-->
<!--		    		<span>--><?php //echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?><!--</span>-->
<!--		    	</a>-->
<!--		    	<div class="clr"></div>-->
<!--		    </div>-->
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