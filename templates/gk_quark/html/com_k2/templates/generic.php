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
$override .=  'com_k2' . DS . 'templates' . DS . 'generic.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<div id="k2Container" class="blog-page generic-page <?php if($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">
      <div class="gkPage">
      	<?php if(JRequest::getCmd('task')=='search' && $this->params->get('googleSearch')): ?>
      	<!-- Google Search container -->
      	<div id="<?php echo $this->params->get('googleSearchContainer'); ?>"></div>
      	<?php endif; ?>
      
      	<div id="gk-content-wrapper">
      		<?php if(count($this->items)): ?>
      			<div class="item-list">
      				<?php foreach($this->items as $item): ?>			
      				<article class="item-view">				    
      				    <?php if($item->params->get('genericItemTitle')): ?>
      			    	<h2 class="item-title">
      			            <?php if ($item->params->get('genericItemTitleLinked')): ?>
      			            <a href="<?php echo $item->link; ?>" class="inverse"><?php echo $item->title; ?></a>
      			            <?php else: ?>
      			            <?php echo $item->title; ?>
      			            <?php endif; ?>
      			    	</h2>
      				    <?php endif; ?>
      				    
      				    <div class="item-info">
      				    	<?php if($item->params->get('genericItemDateCreated',1)): ?>
      				    	<!-- Date created -->
      				    	<span class="generic-item-date-created">
      				    		<time datetime="<?php echo JHtml::_('date', $item->created, JText::_(DATE_W3C)); ?>"> <?php echo JHTML::_('date', $item->created, JText::_('l, j F Y')); ?> </time>
      				    	</span>
      				    	<?php endif; ?>
      				    	
      				    	<?php if($item->params->get('genericItemCategory')): ?>
      				    	<!-- Item category name -->
      				    	<span class="generic-item-category">
      				    		<span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span>
      				    		<a href="<?php echo $item->category->link; ?>" class="inverse"><?php echo $item->category->name; ?></a>
      				    	</span>
      				    	<?php endif; ?>
      				    </div>
      				</article>
      				<?php endforeach; ?>
      			</div>
      			
      			<?php if($this->params->get('genericFeedIcon',1)): ?>
      			<a class="k2FeedIcon" href="<?php echo $this->feed; ?>"><?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?></a>
      			<?php endif; ?>
      			
      			<?php if(count($this->pagination->getPagesLinks())): ?>
      			<?php echo $this->pagination->getPagesLinks(); ?>
      			<?php endif; ?>
      		<?php else: ?>
	      		<?php if(!$this->params->get('googleSearch')): ?>
	      		<!-- No results found -->
	      		<div id="genericItemListNothingFound">
	      		    <p><?php echo JText::_('K2_NO_RESULTS_FOUND'); ?></p>
	      		</div>
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