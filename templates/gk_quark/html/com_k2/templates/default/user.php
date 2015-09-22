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
$override .=  'com_k2' . DS . 'templates' . DS . 'default' . DS . 'item.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<?php

// Get user stuff (do not change)
$user = JFactory::getUser();

?>
<div id="k2Container" class="blog-page user-page <?php if($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">   
     <?php if (
     	$this->params->get('userImage') || 
     	$this->params->get('userName') || 
     	$this->params->get('userDescription') || 
     	$this->params->get('userURL') || 
     	$this->params->get('userEmail')
     ): ?>
     <div class="header">
          <?php if($templateParams->get('k2_author_page_header_image') != '') : ?>
          <img src="<?php echo $templateParams->get('k2_author_page_header_image'); ?>" alt="" />
          <?php endif; ?>
          
          <div class="item-author-details">
               <?php if ($this->params->get('userImage') && !empty($this->user->avatar)): ?>
               <img src="<?php echo $this->user->avatar; ?>" alt="<?php echo $this->user->name; ?>" data-sr="enter bottom and move 50px and wait .3s" />
               <?php endif; ?>
               
               <?php if ($this->params->get('userName')): ?>
               <h1 class="item-title" data-sr="enter bottom and move 50px and wait .1s"><?php echo $this->user->name; ?></h1>
               <?php endif; ?>
               
               <?php if ($this->params->get('userDescription') && isset($this->user->profile->description)): ?>
               <p class="item-desc" data-sr="enter bottom and move 50px and wait .2s"><?php echo strip_tags($this->user->profile->description); ?></p>
               <?php endif; ?>
               <p class="item-desc" data-sr="enter bottom and move 50px and wait .2s">
                    <?php if ($this->params->get('userEmail')): ?>
                    <?php echo JText::_('K2_EMAIL'); ?>: <?php echo JHTML::_('Email.cloak', $this->user->email); ?>
                    <?php endif; ?>
                    <?php if ($this->params->get('userURL') && isset($this->user->profile->url) && trim($this->user->profile->url) != ''): ?>
                    <?php echo JText::_('K2_WEBSITE_URL'); ?>: <a href="<?php echo $this->user->profile->url; ?>" target="_blank" rel="me"><?php echo $this->user->profile->url; ?></a>
                    <?php endif; ?>
               </p>
          </div>
          
          <?php echo $this->user->event->K2UserDisplay; ?>
          
          <?php if($templateParams->get('display_mouse_icon', '1') != '0') : ?>
          <span class="mouse-icon"><span><span></span></span></span> 
          <?php endif; ?>
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
     
     <div class="gkPage">
     	<div id="gk-content-wrapper">
     		<?php if(count($this->items)): ?>
	     		<div class="item-list">
			          <?php foreach ($this->items as $item): ?>
			          <article class="item-view"> 
			          	<?php echo $item->event->BeforeDisplay; ?> 
			          	<?php echo $item->event->K2BeforeDisplay; ?>
						
						<?php if($item->params->get('userItemTitle')): ?>
						<h2 class="item-title">
					      <?php if ($item->params->get('userItemTitleLinked')): ?>
					      <a href="<?php echo $item->link; ?>" class="inverse"><?php echo $item->title; ?></a>
					      <?php else: ?>
					      <?php echo $item->title; ?>
					      <?php endif; ?>
						</h2>
						<?php endif; ?>
						    
						<div class="item-info">
							<?php if($item->params->get('userItemDateCreated',1)): ?>
							<!-- Date created -->
							<span class="user-item-date-created">
								<time datetime="<?php echo JHtml::_('date', $item->created, JText::_(DATE_W3C)); ?>"> <?php echo JHTML::_('date', $item->created, JText::_('l, j F Y')); ?> </time>
							</span>
							<?php endif; ?>
							
							<?php if($item->params->get('userItemCategory')): ?>
							<!-- Item category name -->
							<span class="user-item-category">
								<span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span>
								<a href="<?php echo $item->category->link; ?>" class="inverse"><?php echo $item->category->name; ?></a>
							</span>
							<?php endif; ?>
						</div>
						
			            <?php echo $item->event->AfterDisplay; ?> 
			            <?php echo $item->event->K2AfterDisplay; ?> 
			          </article>
			          <?php endforeach; ?>
	     		</div>
	     		
				<?php if($this->params->get('userFeedIcon',1)): ?>
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