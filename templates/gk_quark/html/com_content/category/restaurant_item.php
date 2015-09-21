<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_content' . DS . 'category' . DS . 'blog_item.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<?php

// Create a shortcut for params.
$params = &$this->item->params;
$images = json_decode($this->item->images);
$canEdit	= $this->item->params->get('access-edit');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');

$item_url = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language));

$bg = ' no-image'; 

if(isset($images->image_intro) && !empty($images->image_intro)) {
	$bg = ' lazy" data-original="' . JURI::root() . $images->image_intro . '';
} else if(isset($images->image_fulltext) && !empty($images->image_fulltext)) {
	$bg = ' lazy" data-original="' . JURI::root() . $images->image_fulltext . '';
}

?>

<article class="item-view quark-restaurant-style"> 		
	<?php $images = json_decode($this->item->images); ?>
	<?php if (isset($images->image_intro) && !empty($images->image_intro)) : ?>
	<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language)); ?>" class="cat-item-image" data-sr="enter bottom and move 100px">
		<img
		<?php if ($images->image_intro_caption):
			echo 'class="caption"' . ' title="' . htmlspecialchars($images->image_intro_caption) . '"';
		endif; ?>
		src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" itemprop="thumbnailUrl"/> 
	</a>
	<?php endif; ?>
	
	<div>
		<header>
		    <?php if ($params->get('show_title')) : ?>
		    <h2 class="item-title">
		    	<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
		    		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language)); ?>" class="inverse">
		    		<?php echo $this->escape($this->item->title); ?></a>
		    	<?php else : ?>
		    		<?php echo $this->escape($this->item->title); ?>
		    	<?php endif; ?>
		    </h2>
		    <?php endif; ?>	
		    
		    <?php if (!$params->get('show_intro')) : ?>
		    	<?php echo $this->item->event->afterDisplayTitle; ?>
		    <?php endif; ?>
	  
			<?php if(
				$params->get('show_publish_date') ||
				$params->get('show_create_date') ||
				($params->get('show_author') && !empty($this->item->author))
			): ?>
		    <div class="item-info">
			    <ul>
				    <?php if($params->get('show_publish_date')) : ?>
				    <li>
				    	<time datetime="<?php echo JHtml::_('date', $this->item->publish_up, JText::_(DATE_W3C)); ?>" itemprop="datePublished">
				    		<?php echo JHTML::_('date', $this->item->publish_up, 'l, j F Y'); ?>
				    	</time>
				    </li>
				    <?php elseif($params->get('show_create_date')) : ?>
				    <li>
				    	<time datetime="<?php echo JHtml::_('date', $this->item->created, JText::_(DATE_W3C)); ?>" itemprop="dateCreated">
				    		<?php echo JHTML::_('date', $this->item->created, 'l, j F Y'); ?>
				    	</time>
				    </li>
				    <?php endif; ?>
				    
				    <?php if($params->get('show_author') && !empty($this->item->author)): ?>
				    <li class="itemAuthor" itemprop="author">
				    	<?php 
				    		$author_obj = JFactory::getUser($this->item->created_by);
				    		$author_name = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author;
				    	?>
				    	
				    	<?php
				    		$cntlink = '';
				    		
				    		if (!empty($this->item->contactid) && $params->get('link_author') == true) {
				    			$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
				    			$menu = JFactory::getApplication()->getMenu();
				    			$item = $menu->getItems('link', $needle, true);
				    			$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
				    		}
				    	?>
				    	<?php if($cntlink != '') : ?><a href="<?php echo $cntlink; ?>" class="inverse"><?php endif; ?>
				    	<?php echo $author_name; ?>				
				    	<?php if($cntlink != '') : ?></a><?php endif; ?>
				    </li><!-- .itemAuthor -->
				    <?php endif; ?>  
			    </ul> 
		    </div>
		    <?php endif; ?>
	    </header>
		
		<?php echo $this->item->event->beforeDisplayContent; ?>
		<div class="cat-item-intro-text">
			<?php echo $this->item->introtext; ?>
		</div>
		
		<?php if ($params->get('show_readmore') && $this->item->readmore) :
			if ($params->get('access-view')) :
				$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language));
			else :
				$menu = JFactory::getApplication()->getMenu();
				$active = $menu->getActive();
				$itemId = $active->id;
				$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
				$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language));
				$link = new JUri($link1);
				$link->setVar('return', base64_encode($returnURL));
			endif; ?>	
		<a class="gk-read-more" href="<?php echo $link; ?>" itemprop="url">
			<?php if (!$params->get('access-view')) :
				echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
			elseif ($readmore = $this->item->alternative_readmore) :
				echo $readmore;
				if ($params->get('show_readmore_title', 0) != 0) :
					echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
				endif;
			elseif ($params->get('show_readmore_title', 0) == 0) :
				echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
			else :
				echo JText::_('COM_CONTENT_READ_MORE');
				echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
			endif; ?>
		</a>
		<?php endif; ?>
	</div>
	<?php echo $this->item->event->afterDisplayContent; ?>
</article>
<?php echo $this->item->event->afterDisplayContent; ?>
<?php endif; ?>