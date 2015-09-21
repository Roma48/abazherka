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
$override .=  'com_k2' . DS . 'templates' . DS . 'default' . DS . 'tag.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>
<div id="k2Container" class="blog-page tag-page <?php if($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">
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
	
	<div class="gkPage">
		<div id="gk-content-wrapper">
			<?php if(count($this->items)): ?>
				<div class="item-list">
					<?php foreach($this->items as $item): ?>			
					<article class="item-view">				    
					    <?php if($item->params->get('tagItemImage',1) && !empty($item->imageGeneric)): ?>
					    <a href="<?php echo $item->link; ?>" title="<?php if(!empty($item->image_caption)) echo K2HelperUtilities::cleanHtml($item->image_caption); else echo K2HelperUtilities::cleanHtml($item->title); ?>" class="cat-item-image">
					    	<img src="<?php echo $item->imageGeneric; ?>" alt="<?php if(!empty($item->image_caption)) echo K2HelperUtilities::cleanHtml($item->image_caption); else echo K2HelperUtilities::cleanHtml($item->title); ?>" style="width:<?php echo $item->params->get('itemImageGeneric'); ?>px; height:auto;" />
					    </a>
					    <?php endif; ?>
					    
					    <div>
						    <?php if($item->params->get('tagItemTitle')): ?>
					    	<h2 class="item-title">
					            <?php if ($item->params->get('tagItemTitleLinked')): ?>
					            <a href="<?php echo $item->link; ?>" class="inverse"><?php echo $item->title; ?></a>
					            <?php else: ?>
					            <?php echo $item->title; ?>
					            <?php endif; ?>
					    	</h2>
						    <?php endif; ?>
						    
						    <div class="item-info">
						    	<?php if($item->params->get('tagItemDateCreated',1)): ?>
						    	<!-- Date created -->
						    	<span class="tag-item-date-created">
						    		<time datetime="<?php echo JHtml::_('date', $item->created, JText::_(DATE_W3C)); ?>"> <?php echo JHTML::_('date', $item->created, JText::_('l, j F Y')); ?> </time>
						    	</span>
						    	<?php endif; ?>
						    	
						    	<?php if($item->params->get('tagItemCategory')): ?>
						    	<!-- Item category name -->
						    	<span class="tag-item-category">
						    		<span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span>
						    		<a href="<?php echo $item->category->link; ?>" class="inverse"><?php echo $item->category->name; ?></a>
						    	</span>
						    	<?php endif; ?>
						    </div>
						 </div>
					</article>
					<?php endforeach; ?>
				</div>
				
				<?php if($this->params->get('tagFeedIcon',1)): ?>
				<a class="k2FeedIcon" href="<?php echo $this->feed; ?>"><?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?></a>
				<?php endif; ?>
				
				<?php if(count($this->pagination->getPagesLinks())): ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		
		<?php 
		 	$document = JFactory::getDocument();
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