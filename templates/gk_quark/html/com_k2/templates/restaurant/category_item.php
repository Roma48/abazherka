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
$override .=  'com_k2' . DS . 'templates' . DS . 'default' . DS . 'category_item.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<?php

// Define default image size (do not change)
K2HelperUtilities::setDefaultImage($this->item, 'itemlist', $this->params);

?>

<article class="item-view quark-restaurant-style"> 	
	<?php echo $this->item->event->BeforeDisplay; ?> 
	<?php echo $this->item->event->K2BeforeDisplay; ?>
	
	<?php if($this->item->params->get('catItemImage') && !empty($this->item->image)): ?>
	<a href="<?php echo $this->item->link; ?>" title="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" class="cat-item-image gk-border-anim" data-sr="enter bottom and move 0px over 0.01s">
		<img src="<?php echo $this->item->image; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" />
	</a>
	<?php endif; ?>
	
	<div>
		<header>
		    <?php if($this->item->params->get('catItemTitle')): ?>
	    	<h2 class="item-title">
	            <?php if ($this->item->params->get('catItemTitleLinked')): ?>
	            <a href="<?php echo $this->item->link; ?>" class="inverse"><?php echo $this->item->title; ?></a>
	            <?php else: ?>
	            <?php echo $this->item->title; ?>
	            <?php endif; ?>
	            
	            <?php if($this->item->featured): ?>
	            <sup><i class="gkicon-star"></i></sup>
	            <?php endif; ?>
	    	</h2>
	    	
	    	<?php echo $this->item->event->AfterDisplayTitle; ?> 
	    	<?php echo $this->item->event->K2AfterDisplayTitle; ?>
		    <?php endif; ?>
			    
			<?php if(
				$this->item->params->get('catItemAuthor') ||
				$this->item->params->get('catItemDateCreated')
			): ?>
		    <div class="item-info">
			    <ul>
				    <?php if($this->item->params->get('catItemDateCreated')): ?>
				    <li>
				    	<time datetime="<?php echo JHtml::_('date', $this->item->created, JText::_(DATE_W3C)); ?>"> <?php echo JHTML::_('date', $this->item->created, JText::_('l, j F Y')); ?> </time>
				    </li>
				    <?php endif; ?> 
				    
				    <?php if($this->item->params->get('catItemAuthor')): ?>
				    <li>
				        <?php echo JText::_('TPL_GK_LANG_POSTED_BY'); ?>
				        
				        <?php if(isset($this->item->author->link) && $this->item->author->link): ?>
				        <a rel="author" href="<?php echo $this->item->author->link; ?>" title="<?php echo $this->item->author->name; ?>" class="inverse">
				        	<?php echo $this->item->author->name; ?>
				        </a>
				        <?php else: ?>
				        	<?php echo $this->item->author->name; ?>
				        <?php endif; ?>
				    </li>
				    <?php endif; ?>  
			    </ul> 
		    </div>
		    <?php endif; ?>
	    </header>
		
		<?php if($this->item->params->get('catItemIntroText')): ?>
		<div class="cat-item-intro-text">
			<?php echo $this->item->introtext; ?>
		</div>
		<?php endif; ?>
		
		<?php if ($this->item->params->get('catItemReadMore')): ?>
		<a class="gk-read-more" href="<?php echo $this->item->link; ?>">
			<?php echo str_replace('...', '', JText::_('K2_READ_MORE')); ?>
		</a>
		<?php endif; ?>
	</div>
	
    <?php echo $this->item->event->AfterDisplay; ?> 
    <?php echo $this->item->event->K2AfterDisplay; ?>
</article>
<?php endif; ?>