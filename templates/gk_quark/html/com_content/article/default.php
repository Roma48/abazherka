<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_content' . DS . 'article' . DS . 'default.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>
<?php 

JHtml::addIncludePath(JPATH_COMPONENT .'/helpers');
JLoader::register('TagsHelperRoute', JPATH_BASE . '/components/com_tags/helpers/route.php');

// Create shortcuts to some parameters.
$document = JFactory::getDocument();
$params = $this->item->params;
$images = json_decode($this->item->images);
$attribs = json_decode($this->item->attribs);

foreach($attribs as $key => $value) {
    if($value != null) {
    	$params->set($key, $value);
    }
}

$canEdit	= $this->item->params->get('access-edit');
$urls = json_decode($this->item->urls);
$user		= JFactory::getUser();

// URL for Social API
$cur_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$cur_url = preg_replace('@%[0-9A-Fa-f]{1,2}@mi', '', htmlspecialchars($cur_url, ENT_QUOTES, 'UTF-8'));

// OpenGraph support
$template_config = new JConfig();
$uri = JURI::getInstance();
$article_attribs = json_decode($this->item->attribs, true);

$pin_image = '';
$og_title = $this->escape($this->item->title);
$og_type = 'article';
$og_url = $cur_url;
if (version_compare( JVERSION, '1.8', 'ge' ) && isset($images->image_fulltext) and !empty($images->image_fulltext)) {     $og_image = $uri->root() . htmlspecialchars($images->image_fulltext);
     $pin_image = $uri->root() . htmlspecialchars($images->image_fulltext);
} else {
     $og_image = '';
     preg_match('/src="([^"]*)"/', $this->item->text, $matches);

     if(isset($matches[0])) {
     	$pin_image = $uri->root() . substr($matches[0], 5,-1);
     }
}


$og_site_name = $template_config->sitename;
$og_desc = '';

if(isset($article_attribs['og:title'])) {
     $og_title = ($article_attribs['og:title'] == '') ? $this->escape($this->item->title) : $this->escape($article_attribs['og:title']);
     $og_type = $this->escape($article_attribs['og:type']);
     $og_url = $cur_url;
     $og_image = ($article_attribs['og:image'] == '') ? $og_image : $uri->root() . $article_attribs['og:image'];
     $og_site_name = ($article_attribs['og:site_name'] == '') ? $template_config->sitename : $this->escape($article_attribs['og:site_name']);
     $og_desc = $this->escape($article_attribs['og:description']);
}

$doc = JFactory::getDocument();
$doc->setMetaData( 'og:title', $og_title );
$doc->setMetaData( 'og:type', $og_type );
$doc->setMetaData( 'og:url', $og_url );
$doc->setMetaData( 'og:image', $og_image );
$doc->setMetaData( 'og:site_name', $og_site_name );
$doc->setMetaData( 'og:description', $og_desc );

$useDefList = (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_parent_category'))
	or ($params->get('show_create_date')) or ($params->get('show_modify_date')) or ($params->get('show_publish_date'))
	or ($params->get('show_hits')));

$templateSettings = JFactory::getApplication()->getTemplate(true)->params;
$renderer = $document->loadRenderer('modules');

$no_image = false;

if(!(isset($images->image_fulltext) && !empty($images->image_fulltext))) {
	$no_image = true;
}

?>

<article id="comContentContainer" class="itemView single-page <?php echo $this->pageclass_sfx?>" itemscope itemtype="http://schema.org/Article">
	<meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? JFactory::getConfig()->get('language') : $this->item->language; ?>" />

	<?php if (
		!$document->countModules('header') && 
		(
			!$no_image ||
			$params->get('show_title') ||
			$params->get('show_publish_date') ||
			$params->get('show_create_date')
		)
	) : ?>
	<div class="header<?php if($no_image) : ?> no-image<?php endif; ?>">
		<?php if (isset($images->image_fulltext) && !empty($images->image_fulltext)) : ?>
		<img src="<?php echo $images->image_fulltext; ?>" alt="" />
		<?php endif; ?>   
		
		<?php if (
			$params->get('show_title') ||
			$params->get('show_publish_date') ||
			$params->get('show_create_date')
		) : ?>
		<div class="item-title-wrap">
			<?php if ($params->get('show_title')) : ?>
			<h1 class="item-title" itemprop="name" data-sr="enter bottom and move 50px"><?php echo $this->escape($this->item->title); ?></h1>
			<?php endif; ?>
			
			<?php if($params->get('show_publish_date')) : ?>
			<span class="item-category" data-sr="enter bottom and move 50px and wait .2s">
				<time datetime="<?php echo JHtml::_('date', $this->item->publish_up, JText::_(DATE_W3C)); ?>" itemprop="datePublished">
					<?php echo JHTML::_('date', $this->item->publish_up, 'l, j F Y'); ?>
				</time>
			</span>
			<?php elseif($params->get('show_create_date')) : ?>
			<span class="item-category" data-sr="enter bottom and move 50px and wait .2s">
				<time datetime="<?php echo JHtml::_('date', $this->item->created, JText::_(DATE_W3C)); ?>" itemprop="dateCreated">
					<?php echo JHTML::_('date', $this->item->created, 'l, j F Y'); ?>
				</time>
			</span>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		
		<?php if($templateSettings->get('display_mouse_icon', '1') != '0') : ?>
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
		  	<?php 
		  		if($document->countModules('mainbody_top')) {
		  			echo '<section id="gkMainbodyTop">';
		  			echo '<div>';
		  			echo $renderer->render('mainbody_top', array('style' => 'gk_style'), null); 
		  			echo '</div>';
		  			echo '</section>';
		  		} 
		  	?>
		  	
		  	<div class="item-content">
		  		<div class="item-info">			
		  			<ul>
			  			<?php if($params->get('show_category')) : ?>
			  			<li itemprop="genre">
			  				<?php 
			  					$title = $this->escape($this->item->category_title); 
			  					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)).'" class="inverse">'.$title.'</a>';
			  				?>
			  				
			  				<?php if ($params->get('link_category') and $this->item->catslug) : ?>
			  					<?php echo $url; ?>
			  				<?php else : ?>
			  					<?php echo $title; ?>
			  				<?php endif; ?>
			  			</li>
			  			<?php endif; ?>
			  			
			  			<?php if ($params->get('show_parent_category') && $this->item->parent_slug != '1:root') : ?>
						<li class="parent-category-name" itemprop="genre">
							<?php	
								$title = $this->escape($this->item->parent_title);
								$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'" class="inverse">'.$title.'</a>';
							?>
							<?php if ($params->get('link_parent_category') and $this->item->parent_slug) : ?>
							<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
							<?php else : ?>
							<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
							<?php endif; ?>
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
			  				
						<?php if ($params->get('show_hits')) : ?>
						<li class="itemHits">
							<meta itemprop="interactionCount" content="UserPageVisits:<?php echo $item->hits; ?>" />
							<?php echo JText::sprintf('TPL_GK_LANG_HITS', $this->item->hits); ?>
						</li>
						<?php endif; ?>
						
						<?php if (!$this->print) : ?>
							<?php if ($params->get('show_print_icon')) : ?>
							<li><?php echo preg_replace(array('@<img.*?alt="(.*?)".*?\/>@mis', '@href=@'), array('$1', 'class="inverse" href='), JHtml::_('icon.print_popup',  $this->item, $params)); ?></li>
							<?php endif; ?>
							
							<?php if ($params->get('show_email_icon')) : ?>
							<li><?php echo preg_replace(array('@<img.*?alt="(.*?)".*?\/>@mis', '@href=@'), array('$1', 'class="inverse" href='),JHtml::_('icon.email',  $this->item, $params)); ?></li>
							<?php endif; ?>
						
							<?php if ($canEdit) : ?>
							<li><?php echo preg_replace(array('@<img.*?alt="(.*?)".*?\/>@mis', '@href=@'), array('$1', 'class="inverse" href='),JHtml::_('icon.edit', $this->item, $params)); ?></li>
							<?php endif; ?>
						<?php else : ?>
						<li><?php echo preg_replace(array('@<img.*?alt="(.*?)".*?\/>@mis', '@href=@'), array('$1', 'class="inverse" href='),JHtml::_('icon.print_screen',  $this->item, $params)); ?></li>
			  			<?php endif; ?>
		  			</ul>
		  		</div><!-- .itemInfo -->
		
		  		<div class="item-body" itemprop="articleBody">
		            <?php  if (!$params->get('show_intro')) : ?>
		            	<?php echo $this->item->event->afterDisplayTitle; ?>
		            <?php endif; ?>
		            
		            <?php echo $this->item->event->beforeDisplayContent; ?>
		        	
		        	<?php if (isset ($this->item->toc)) : ?>
		        		<?php echo $this->item->toc; ?>
		        	<?php endif; ?>
		            
		            <?php if (isset($urls) AND ((!empty($urls->urls_position) AND ($urls->urls_position=='0')) OR  ($params->get('urls_position')=='0' AND empty($urls->urls_position) )) OR (empty($urls->urls_position) AND (!$params->get('urls_position')))): ?>
		            	<?php echo $this->loadTemplate('links'); ?>
		            <?php endif; ?>
		            
		            <?php if ($params->get('access-view')):?>
			            <?php if (!empty($this->item->pagination) AND $this->item->pagination AND !$this->item->paginationposition AND !$this->item->paginationrelative) : ?>
				  			<?php echo $this->item->pagination; ?>
				  		<?php endif; ?>
			  		            
			  		    <?php echo $this->item->text; ?>
			            
			            <?php if (isset($urls) AND ((!empty($urls->urls_position)  AND ($urls->urls_position=='1')) OR ( $params->get('urls_position')=='1') )): ?>
			            	<?php echo $this->loadTemplate('links'); ?>
			            <?php endif; ?>
			  		            
			            <?php if (!empty($this->item->pagination) AND $this->item->pagination AND $this->item->paginationposition AND!$this->item->paginationrelative): ?>
			            	<?php echo $this->item->pagination; ?>
			            <?php endif; ?>
		            <?php elseif ($params->get('show_noauth') == true and  $user->get('guest') ) : ?>
		  		        <?php echo $this->item->introtext; ?>
		  		        
		  		        <?php if ($params->get('show_readmore') && $this->item->fulltext != null) : ?>
			  				<?php
			  					$link1 = JRoute::_('index.php?option=com_users&view=login');
			  					$link = new JURI($link1);
			  				?>
		  		            <p class="readmore"> 
		  		            	<a href="<?php echo $link; ?>">
		  		                	<?php $attribs = json_decode($this->item->attribs);  ?>
		  		                    <?php
						  				if ($attribs->alternative_readmore == null) :
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
						  				endif; 
						  			?>
		  		                 </a> 
		  		            </p>
		  		            <?php endif; ?>
		  		       <?php endif; ?>
		  		  </div><!-- .item-body -->
		  		  
		  		  <?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
		  		  <div class="itemTags"> 
		  		   <ul>
		  		   	<?php foreach ($this->item->tags->itemTags as $tag) : ?>
		  		    <li>
		  		       <a href="<?php echo JRoute::_(TagsHelperRoute::getTagRoute($tag->tag_id . ':' . $tag->alias)) ?>"><?php echo $tag->title; ?></a>
		  		    </li>
		  		   	<?php endforeach; ?>
		  		   </ul>
		  		  </div>
		  		  <?php endif; ?>	
		  		  
		  		  <?php if(
		  		  	$templateSettings->get('social_api_type', '2') == '2' && 
		  		  	(
		  		  		$templateSettings->get('popup_fb', '1') == '1' ||
		  		  		$templateSettings->get('popup_twitter', '1') == '1' ||
		  		  		$templateSettings->get('popup_gplus', '1') == '1' ||
		  		  		$templateSettings->get('popup_pinterest', '1') == '1' ||
		  		  		$templateSettings->get('popup_linkedin', '0') == '1' ||
		  		  		$templateSettings->get('popup_vk', '0') == '1'
		  		  	)
		  		  ) : ?>
		  		  <gavern:social>
		  		  <span class="gk-social-icons">
		  		  	<i class="fa fa-share-alt"></i>
		  		  	
		  		  	<span>
		  		  		<?php if($templateSettings->get('popup_fb', '1') == '1') : ?>
		  		  		<a href="https://www.facebook.com/sharer.php?u=<?php echo $cur_url; ?>" target="_blank"><i class="fa fa-facebook"></i> Facebook</a>
		  		  		<?php endif; ?>
		  		  		
		  		  		<?php if($templateSettings->get('popup_twitter', '1') == '1') : ?>
		  		  		<a href="http://twitter.com/intent/tweet?source=sharethiscom&amp;url=<?php echo $cur_url; ?>" target="_blank"><i class="fa fa-twitter"></i> Twitter</a>
		  		  		<?php endif; ?>
		  		  		
		  		  		<?php if($templateSettings->get('popup_gplus', '1') == '1') : ?>
		  		  		<a href="https://plus.google.com/share?url=<?php echo $cur_url; ?>" target="_blank"><i class="fa fa-google-plus"></i> Google+</a>
		  		  		<?php endif; ?>
		  		  		
		  		  		<?php if($templateSettings->get('popup_pinterest', '1') == '1') : ?>
		  		  		<a href="javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','//assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());"><i class="fa fa-pinterest-p"></i> Pinterest</a>
		  		  		<?php endif; ?>
		  		  		
		  		  		<?php if($templateSettings->get('popup_linkedin', '0') == '1') : ?>
		  		  		<a href="https://www.linkedin.com/cws/share?url=<?php echo $cur_url; ?>"><i class="fa fa-linkedin"></i> LinkedIn</a>
		  		  		<?php endif; ?>
		  		  		
		  		  		<?php if($templateSettings->get('popup_vk', '0') == '1') : ?>
		  		  		<a href="http://vkontakte.ru/share.php?url=<?php echo $cur_url; ?>"><i class="fa fa-vk"></i> VK</a>
		  		  		<?php endif; ?>
		  		  	</span>
		  		  </span>
		  		  </gavern:social>
		  		  <?php endif; ?>
		  		  
		  		  <?php if ($params->get('show_modify_date')) : ?>
		  		  <div class="itemBottom gk-clearfix">
		  		 		<small class="itemDateModified">
		  		 			<?php echo JText::_('TPL_GK_LANG_MODIFIED_AT'); ?>
		  		 			<?php echo JHtml::_('date', $this->item->modified, 'D j M Y'); ?>
		  		 		</small>
		  		  </div> 
		  		  <?php endif; ?>
		  		  
		  		  <?php if($templateSettings->get('social_api_type', '2') == '1') : ?>
		  		  <gavern:social><div id="gkSocialAPI" class="itemSocialSharing"></gavern:social>
		  		     <gavern:social><div><g:plusone GK_GOOGLE_PLUS_SETTINGS data-href="<?php echo $cur_url; ?>"></g:plusone></div></gavern:social>
		  		     <gavern:social><div><g:plus action="share" GK_GOOGLE_PLUS_SHARE_SETTINGS href="<?php echo $cur_url; ?>"></g:plus></div></gavern:social>
		  		     <gavern:social><div><fb:like href="<?php echo $cur_url; ?>" GK_FB_LIKE_SETTINGS></fb:like></div></gavern:social>
		  		     <gavern:social><div><a href="http://twitter.com/share" class="twitter-share-button" data-text="<?php echo $this->item->title; ?>" data-url="<?php $cur_url; ?>"  gk_tweet_btn_settings>Tweet</a></div></gavern:social>
		  		     <gavern:social><div><a href="http://pinterest.com/pin/create/button/?url=<?php echo $cur_url; ?>&amp;media=<?php echo $pin_image; ?>&amp;description=<?php echo str_replace(" ", "%20", $this->item->title); ?>" class="pin-it-button" count-layout="GK_PINTEREST_SETTINGS"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="<?php echo JText::_('TPL_GK_LANG_PINIT_TITLE'); ?>" /></a></div></gavern:social>
		  		   <gavern:social></div></gavern:social>
		  		  <?php endif; ?>
		  		  
		  		  <?php if (
		  		  				!empty($this->item->pagination) AND 
		  		  				$this->item->pagination AND 
		  		  				$this->item->paginationposition AND 
		  		  				$this->item->paginationrelative
		  		  ): ?>
		  		 	 <?php echo $this->item->pagination;?>
		  		  <?php endif; ?>          
		  		  
		  		  <?php echo $this->item->event->afterDisplayContent; ?>
		  	 </div><!-- .item-content -->
		  	 
		  	 <?php 
	  	 		if($document->countModules('mainbody_bottom')) {
	  	 			echo '<section id="gkMainbodyBottom">';
	  	 			echo '<div>';
	  	 			echo $renderer->render('mainbody_bottom', array('style' => 'gk_style'), null); 
	  	 			echo '</div>';
	  	 			echo '</section>';
	  	 		} 
		  	 ?>	
		</div>	
		
		<?php 
		 	if($document->countModules('sidebar')) {
		 		echo '<aside id="gkSidebar">';
		 		echo '<div>';
		 		echo $renderer->render('sidebar', array('style' => 'gk_style'), null); 
		 		echo '</div>';
		 		echo '</aside>';
		 	}
		?> 
	</div>
</article>
<?php endif; ?>