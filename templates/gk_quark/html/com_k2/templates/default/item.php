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
// Code used to generate the page elements
$params = $this->item->params;
$k2ContainerClasses = (($this->item->featured) ? ' itemIsFeatured' : '') . ($params->get('pageclass_sfx')) ? ' '.$params->get('pageclass_sfx') : ''; 

$cur_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$cur_url = preg_replace('@%[0-9A-Fa-f]{1,2}@mi', '', htmlspecialchars($cur_url, ENT_QUOTES, 'UTF-8'));

$document = JFactory::getDocument();
$templateSettings = JFactory::getApplication()->getTemplate(true)->params;
$renderer = $document->loadRenderer('modules');

?>
<?php if(JRequest::getInt('print')==1): ?>

<a class="itemPrintThisPage" rel="nofollow" href="#" onclick="window.print(); return false;"> <?php echo JText::_('K2_PRINT_THIS_PAGE'); ?> </a>
<?php endif; ?>
<article id="k2Container" class="itemView single-page <?php echo $k2ContainerClasses; ?>"> <?php echo $this->item->event->BeforeDisplay; ?> <?php echo $this->item->event->K2BeforeDisplay; ?>
     <?php if(
     	!$document->countModules('header') && 
     	(
     		!empty($this->item->image) ||
     		$params->get('itemTitle') ||
     		$this->item->params->get('itemDateCreated')
     	)
     ): ?>
     <div class="header <?php if(!($params->get('itemImage') && !empty($this->item->image))): ?> no-image<?php endif; ?>">
          <?php if(!empty($this->item->image)) : ?>
          <img src="<?php echo $this->item->image; ?>" alt="<?php echo K2HelperUtilities::cleanHtml($this->item->title); ?>" />
          <?php endif; ?>
          <?php if($params->get('itemTitle')): ?>
          <div class="item-title-wrap">
               <h1 class="item-title" data-sr="enter bottom and move 50px">
               	<?php echo $this->item->title; ?>
               	<?php if($params->get('itemFeaturedNotice') && $this->item->featured): ?>
               	<sup><i class="gkicon-star"></i></sup>
               	<?php endif; ?>
               </h1>
               
               <?php if($this->item->params->get('itemDateCreated')): ?>
               <span class="item-category" data-sr="enter bottom and move 50px and wait .2s">
               		<time datetime="<?php echo JHtml::_('date', $this->item->created, JText::_(DATE_W3C)); ?>"> <?php echo JHTML::_('date', $this->item->created, 'l, j F Y'); ?> </time>
               </span>
               <?php endif; ?>
          </div>
          <?php endif; ?>
        
          <?php if(
               	($params->get('itemImageMainCaption') && !empty($this->item->image_caption)) ||
               	($params->get('itemImageMainCredits') && !empty($this->item->image_credits))
               ): ?>
          <div class="item-image-info">
               <?php if($params->get('itemImageMainCaption') && !empty($this->item->image_caption)): ?>
               <span class="item-image-caption"><?php echo $this->item->image_caption; ?></span>
               <?php endif; ?>
               <?php if($params->get('itemImageMainCredits') && !empty($this->item->image_credits)): ?>
               <span class="item-image-credits"><?php echo $this->item->image_credits; ?></span>
               <?php endif; ?>
          </div>
          <?php endif; ?>
          
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
		          <?php if(
		                    	$params->get('itemCategory') ||
		                    	$params->get('itemAuthor') ||
								$params->get('itemHits') ||
		                    	$params->get('itemFontResizer') ||
		                    	$params->get('itemPrintButton') ||
		                    	$params->get('itemEmailButton') ||
		                    	$params->get('itemSocialButton') ||
		                    	($params->get('itemImageGalleryAnchor') && !empty($this->item->gallery)) ||
		                    	$this->item->params->get('itemDateCreated') ||
		                    	(
		                    		$params->get('itemCommentsAnchor') && 
		                    		$params->get('itemComments') && 
		                    		( 
		                    			(
		                    				$params->get('comments') == '2' && 
		                    				!$this->user->guest
		                    			) || 
		                    			$params->get('comments') == '1'
		                    		)
		                    	)
		                    ): ?>
		          <div class="item-info">
		               <ul>
		                    <?php if($params->get('itemCategory')): ?>
		                    <li> 
		                    	<a href="<?php echo $this->item->category->link; ?>" class="inverse"><?php echo $this->item->category->name; ?></a> 
		                    </li>
		                    <?php endif; ?>
		                    
		                    <?php if($params->get('itemAuthor')): ?>
		                    <li class="itemAuthor">
		                         <?php if($this->item->author->avatar !== '') : ?>
		                         <?php echo JText::_('TPL_GK_LANG_POSTED_BY'); ?>
		                         <?php if(empty($this->item->created_by_alias)): ?>
		                         <a rel="author" href="<?php echo $this->item->author->link; ?>" class="inverse">
		                              <?php endif; ?>
		                              <?php echo $this->item->author->name; ?>
		                              <?php if(empty($this->item->created_by_alias)): ?>
		                         </a>
		                         <?php endif; ?>
		                         <?php endif; ?>
		                    </li>
		                    <?php endif; ?>
		                    <?php if($params->get('itemCommentsAnchor') && $params->get('itemComments') && ( ($params->get('comments') == '2' && !$this->user->guest) || ($params->get('comments') == '1'))): ?>
		                    <li>
		                         <?php if(!empty($this->item->event->K2CommentsCounter)): ?>
		                         <?php echo $this->item->event->K2CommentsCounter; ?>
		                         <?php else: ?>
		                         <?php if($this->item->numOfComments > 0): ?>
		                         <a class="itemCommentsLink k2Anchor inverse" href="<?php echo $this->item->link; ?>#itemCommentsAnchor"><span><?php echo $this->item->numOfComments; ?></span> <?php echo ($this->item->numOfComments>1) ? JText::_('K2_COMMENTS') : JText::_('K2_COMMENT'); ?> </a>
		                         <?php else: ?>
		                         <a class="itemCommentsLink k2Anchor inverse" href="<?php echo $this->item->link; ?>#itemCommentsAnchor"> <?php echo JText::_('K2_BE_THE_FIRST_TO_COMMENT'); ?></a>
		                         <?php endif; ?>
		                         <?php endif; ?>
		                    </li>
		                    <?php endif; ?>
		                    <?php if($params->get('itemHits')): ?>
		                    <li><?php echo JText::_('K2_READ'); ?> <?php echo $this->item->hits; ?> <?php echo JText::_('K2_TIMES'); ?></li>
		                    <?php endif; ?>
		                    <?php if($params->get('itemFontResizer')): ?>
		                    <li class="itemResizer"> <span><?php echo JText::_('K2_FONT_SIZE'); ?></span> <a href="#" id="fontDecrease"><?php echo JText::_('K2_DECREASE_FONT_SIZE'); ?></a> <a href="#" id="fontIncrease"><?php echo JText::_('K2_INCREASE_FONT_SIZE'); ?></a></li>
		                    <?php endif; ?>
		                    <?php if($params->get('itemPrintButton') && !JRequest::getInt('print')): ?>
		                    <li class="itemPrint"> <a rel="nofollow" href="<?php echo $this->item->printLink; ?>" class="inverse" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;"><?php echo JText::_('K2_PRINT'); ?></a> </li>
		                    <?php endif; ?>
		                    <?php if($params->get('itemEmailButton') && !JRequest::getInt('print')): ?>
		                    <li class="itemEmail"> <a rel="nofollow" class="inverse" href="<?php echo $this->item->emailLink; ?>" onclick="window.open(this.href,'emailWindow','width=400,height=350,location=no,menubar=no,resizable=no,scrollbars=no'); return false;"><?php echo JText::_('K2_EMAIL'); ?></a> </li>
		                    <?php endif; ?>
		                    <?php if($params->get('itemSocialButton') && !is_null($params->get('socialButtonCode', NULL))): ?>
		                    <li class="itemSocial"><?php echo $params->get('socialButtonCode'); ?></li>
		                    <?php endif; ?>
		                    <?php if($params->get('itemImageGalleryAnchor') && !empty($this->item->gallery)): ?>
		                    <li class="itemGallery"> <a class="k2Anchor inverse" href="<?php echo $this->item->link; ?>#itemImageGalleryAnchor"><?php echo JText::_('K2_IMAGE_GALLERY'); ?></a> </li>
		                    <?php endif; ?>
		               </ul>
		          </div>
		          <?php endif; ?>
		          <?php echo $this->item->event->AfterDisplayTitle; ?> <?php echo $this->item->event->K2AfterDisplayTitle; ?>
		          <div class="item-body"> <?php echo $this->item->event->BeforeDisplayContent; ?> <?php echo $this->item->event->K2BeforeDisplayContent; ?>
		               <?php if(!empty($this->item->fulltext)): ?>
		               <?php if($params->get('itemIntroText')): ?>
		               <div class="itemIntroText"> <?php echo $this->item->introtext; ?> </div>
		               <?php endif; ?>
		               <?php endif; ?>
		               <?php if($params->get('itemFullText')): ?>
		               <div class="itemFullText"> <?php echo (!empty($this->item->fulltext)) ? $this->item->fulltext : $this->item->introtext; ?> </div>
		               <?php endif; ?>
		               <?php if($params->get('itemVideo') && !empty($this->item->video)): ?>
		               <div class="itemVideoBlock" id="itemVideoAnchor">
		                    <?php if($this->item->videoType=='embedded'): ?>
		                    <div class="itemVideoEmbedded"> <?php echo $this->item->video; ?> </div>
		                    <?php else: ?>
		                    <span class="itemVideo"><?php echo $this->item->video; ?></span>
		                    <?php endif; ?>
		                    <?php if($params->get('itemVideoCaption') && !empty($this->item->video_caption)): ?>
		                    <span class="itemVideoCaption"><?php echo $this->item->video_caption; ?></span>
		                    <?php endif; ?>
		                    <?php if($params->get('itemVideoCredits') && !empty($this->item->video_credits)): ?>
		                    <span class="itemVideoCredits"><?php echo $this->item->video_credits; ?></span>
		                    <?php endif; ?>
		               </div>
		               <?php endif; ?>
		               <?php if($params->get('itemImageGallery') && !empty($this->item->gallery)): ?>
		               <div class="itemImageGallery" id="itemImageGalleryAnchor">
		                    <h3><?php echo JText::_('K2_IMAGE_GALLERY'); ?></h3>
		                    <?php echo $this->item->gallery; ?> </div>
		               <?php endif; ?>
		          </div>
		          <?php if($params->get('itemAttachments') && count($this->item->attachments)): ?>
		          <div class="itemAttachmentsBlock">
		               <h3><?php echo JText::_('K2_DOWNLOAD_ATTACHMENTS'); ?></h3>
		               <ul class="itemAttachments">
		                    <?php foreach ($this->item->attachments as $attachment): ?>
		                    <li><i class="fa fa-download"></i> <a title="<?php echo K2HelperUtilities::cleanHtml($attachment->titleAttribute); ?>" href="<?php echo $attachment->link; ?>"><?php echo $attachment->title; ?></a>
		                         <?php if($params->get('itemAttachmentsCounter')): ?>
		                         <span>(<?php echo $attachment->hits; ?> <?php echo ($attachment->hits==1) ? JText::_('K2_DOWNLOAD') : JText::_('K2_DOWNLOADS'); ?>)</span>
		                         <?php endif; ?>
		                    </li>
		                    <?php endforeach; ?>
		               </ul>
		          </div>
		          <?php endif; ?>
		          
		          <?php if($params->get('itemTags') && count($this->item->tags)): ?>
		          <ul class="itemTags">
		               <?php foreach ($this->item->tags as $tag): ?>
		               <li> <a href="<?php echo $tag->link; ?>"><?php echo $tag->name; ?></a> </li>
		               <?php endforeach; ?>
		          </ul>
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
		          <?php endif; ?>
		          
		          <?php if($params->get('itemExtraFields') && count($this->item->extra_fields)): ?>
		          <div class="itemExtraFields">
		               <h3><?php echo JText::_('K2_ADDITIONAL_INFO'); ?></h3>
		               <dl>
		                    <?php foreach ($this->item->extra_fields as $key=>$extraField): ?>
		                    <?php if($extraField->value != ''): ?>
		                    <dt><?php echo $extraField->name; ?>:</dt>
		                    <dd><?php echo $extraField->value; ?></dd>
		                    <?php endif; ?>
		                    <?php endforeach; ?>
		               </dl>
		          </div>
		          <?php endif; ?>
		          
		          <?php if(
			        	($params->get('itemDateModified') && intval($this->item->modified)!=0) || 
			        	isset($this->item->editLink)
			        ): ?>
		          <div class="itemBottom gk-clearfix">
		               <?php if($params->get('itemDateModified') && intval($this->item->modified) != 0 && $this->item->created != $this->item->modified): ?>
		               <small class="itemDateModified"> <?php echo JText::_('K2_LAST_MODIFIED_ON') . JHTML::_('date', $this->item->modified, JText::_('K2_DATE_FORMAT_LC2')); ?> </small>
		               <?php endif; ?>
		               
		               <?php if(isset($this->item->editLink)): ?>
		               <a class="itemEditLink modal" rel="{handler:'iframe',size:{x:990,y:550}}" href="<?php echo $this->item->editLink; ?>"><?php echo JText::_('K2_EDIT_ITEM'); ?></a>
		               <?php endif; ?>
		          </div>
		          <?php endif; ?>
		          
		          <?php if($params->get('itemRating')): ?>
		          <div class="itemRatingBlock">
		               <div class="itemRatingForm">
		                    <ul class="itemRatingList">
		                         <li class="itemCurrentRating" id="itemCurrentRating<?php echo $this->item->id; ?>" style="width:<?php echo $this->item->votingPercentage; ?>%;"></li>
		                         <li> <a href="#" rel="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_1_STAR_OUT_OF_5'); ?>" class="one-star">1</a> </li>
		                         <li> <a href="#" rel="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_2_STARS_OUT_OF_5'); ?>" class="two-stars">2</a> </li>
		                         <li> <a href="#" rel="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_3_STARS_OUT_OF_5'); ?>" class="three-stars">3</a> </li>
		                         <li> <a href="#" rel="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_4_STARS_OUT_OF_5'); ?>" class="four-stars">4</a> </li>
		                         <li> <a href="#" rel="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_5_STARS_OUT_OF_5'); ?>" class="five-stars">5</a> </li>
		                    </ul>
		                    <div id="itemRatingLog<?php echo $this->item->id; ?>" class="itemRatingLog"> <?php echo $this->item->numOfvotes; ?> </div>
		               </div>
		          </div>
		          <?php endif; ?>
		          
		          <?php echo $this->item->event->AfterDisplayContent; ?> <?php echo $this->item->event->K2AfterDisplayContent; ?>
		          <?php if($params->get('itemRelated') && isset($this->relatedItems)): ?>
		          <div class="itemAuthorContent">
		               <h3><?php echo JText::_("K2_RELATED_ITEMS_BY_TAG"); ?></h3>
		               <ul data-cols="<?php echo count($this->relatedItems); ?>">
		                    <?php foreach($this->relatedItems as $key=>$item): ?>
		                    <li class="<?php echo ($key%2) ? "odd" : "even"; ?>">
		                         <?php if($this->item->params->get('itemRelatedImageSize')): ?>
		                         <a class="itemRelTitle" href="<?php echo $item->link ?>"><img style="width:<?php echo $item->imageWidth; ?>px;height:auto;" class="itemRelImg" src="<?php echo $item->image; ?>" alt="<?php K2HelperUtilities::cleanHtml($item->title); ?>" /></a>
		                         <?php endif; ?>
		                         <a class="inverse" href="<?php echo $item->link ?>"><?php echo $item->title; ?></a> </li>
		                    <?php endforeach; ?>
		               </ul>
		          </div>
		          <?php endif; ?>
		          <?php if($params->get('itemAuthorLatest') && empty($this->item->created_by_alias) && isset($this->authorLatestItems)): ?>
		          <div class="itemAuthorContent itemAuthorLinks">
		               <?php if($params->get('itemAuthorLatest') && empty($this->item->created_by_alias) && isset($this->authorLatestItems)): ?>
		               <h3><?php echo JText::_('K2_LATEST_FROM'); ?> <?php echo $this->item->author->name; ?></h3>
		               <ul>
		                    <?php foreach($this->authorLatestItems as $key=>$item): ?>
		                    <li class="<?php echo ($key%2) ? "odd" : "even"; ?>"> <a href="<?php echo $item->link ?>"><?php echo $item->title; ?></a> </li>
		                    <?php endforeach; ?>
		               </ul>
		               <?php endif; ?>
		          </div>
		          <?php endif; ?>
		          <?php if($params->get('itemNavigation') && !JRequest::getCmd('print') && (isset($this->item->nextLink) || isset($this->item->previousLink))): ?>
		          <div class="itemNavigation gk-page">
		               <?php if(isset($this->item->previousLink)): ?>
		               <a class="itemPrevious inverse" href="<?php echo $this->item->previousLink; ?>">
		                    <?php 
		                          		$prev_title = explode('--', $this->item->previousTitle);
		                          		$prev_title = $prev_title[0];
		                          		echo $prev_title; 
		                          	?>
		               </a>
		               <?php endif; ?>
		               <?php if(isset($this->item->nextLink)): ?>
		               <a class="itemNext inverse" href="<?php echo $this->item->nextLink; ?>">
		                    <?php 
		                          			$next_title = explode('--', $this->item->nextTitle);
		                          			$next_title = $next_title[0];
		                          			echo $next_title;
		                          		?>
		               </a>
		               <?php endif; ?>
		          </div>
		          <?php endif; ?>
		          <?php echo $this->item->event->AfterDisplay; ?> <?php echo $this->item->event->K2AfterDisplay; ?> </div>
		     <?php if($params->get('itemComments') && ( ($params->get('comments') == '2' && !$this->user->guest) || ($params->get('comments') == '1'))):?>
		     <?php echo $this->item->event->K2CommentsBlock; ?>
		     <?php endif;?>
		     <?php if($params->get('itemComments') && !JRequest::getInt('print') && ($params->get('comments') == '1' || ($params->get('comments') == '2')) && empty($this->item->event->K2CommentsBlock)):?>
		     <div class="itemComments" id="itemCommentsAnchor">
		          <?php if($params->get('commentsFormPosition')=='above' && $params->get('itemComments') && !JRequest::getInt('print') && ($params->get('comments') == '1' || ($params->get('comments') == '2' && K2HelperPermissions::canAddComment($this->item->catid)))): ?>
		          <h3> <?php echo JText::_('K2_LEAVE_A_COMMENT') ?> </h3>
		          <div class="itemCommentsForm"> <?php echo $this->loadTemplate('comments_form'); ?> </div>
		          <?php endif; ?>
		          <?php if($this->item->numOfComments>0 && $params->get('itemComments') && !JRequest::getInt('print') && ($params->get('comments') == '1' || ($params->get('comments') == '2'))): ?>
		          <h3> <?php echo $this->item->numOfComments; ?> <?php echo ($this->item->numOfComments>1) ? JText::_('K2_COMMENTS') : JText::_('K2_COMMENT'); ?> </h3>
		          <ul class="itemCommentsList">
		               <?php foreach ($this->item->comments as $key=>$comment): ?>
		               <li class="<?php echo ($key%2) ? "odd" : "even"; echo (!$this->item->created_by_alias && $comment->userID==$this->item->created_by) ? " authorResponse" : ""; echo($comment->published) ? '':' unpublishedComment'; ?>">
		                    <?php if($comment->userImage):?>
		                    <img src="<?php echo $comment->userImage; ?>" alt="<?php echo JFilterOutput::cleanText($comment->userName); ?>" width="<?php echo $params->get('commenterImgWidth'); ?>" />
		                    <?php endif; ?>
		                    <div> <span>
		                         <?php if(!empty($comment->userLink)): ?>
		                         <a href="<?php echo JFilterOutput::cleanText($comment->userLink); ?>" title="<?php echo JFilterOutput::cleanText($comment->userName); ?>" target="_blank" rel="nofollow" class="inverse"> <?php echo $comment->userName; ?> </a>
		                         <?php else: ?>
		                         <?php echo $comment->userName; ?>
		                         <?php endif; ?>
		                         </span> <span> <?php echo JHTML::_('date', $comment->commentDate, JText::_('DATE_FORMAT_LC2')); ?> </span> <span> <a class="commentLink" href="<?php echo $this->item->link; ?>#comment<?php echo $comment->id; ?>" name="comment<?php echo $comment->id; ?>" id="comment<?php echo $comment->id; ?>"> <?php echo JText::_('K2_COMMENT_LINK'); ?> </a> </span>
		                         <?php if($this->inlineCommentsModeration || ($comment->published && ($this->params->get('commentsReporting')=='1' || ($this->params->get('commentsReporting')=='2' && !$this->user->guest)))): ?>
		                         <span class="commentToolbar">
		                         <?php if($this->inlineCommentsModeration): ?>
		                         <?php if(!$comment->published): ?>
		                         <a class="commentApproveLink" href="<?php echo JRoute::_('index.php?option=com_k2&view=comments&task=publish&commentID='.$comment->id.'&format=raw')?>"><?php echo JText::_('K2_APPROVE')?></a>
		                         <?php endif;?>
		                         <a class="commentRemoveLink" href="<?php echo JRoute::_('index.php?option=com_k2&view=comments&task=remove&commentID='.$comment->id.'&format=raw')?>"><?php echo JText::_('K2_REMOVE')?></a>
		                         <?php endif;?>
		                         <?php if($comment->published && ($this->params->get('commentsReporting')=='1' || ($this->params->get('commentsReporting')=='2' && !$this->user->guest))): ?>
		                         <a class="commentReportLink modal" rel="{handler:'iframe',size:{x:640,y:480}}" href="<?php echo JRoute::_('index.php?option=com_k2&view=comments&task=report&commentID='.$comment->id)?>"><?php echo JText::_('K2_REPORT')?></a>
		                         <?php endif; ?>
		                         </span>
		                         <?php endif; ?>
		                         <p>
		                              <?php echo $comment->commentText; ?>
		                         </p>
		                    </div>
		               </li>
		               <?php endforeach; ?>
		          </ul>
		          <div> <?php echo $this->pagination->getPagesLinks(); ?> </div>
		          <?php endif; ?>
		          <?php if($params->get('commentsFormPosition')=='below' && $params->get('itemComments') && !JRequest::getInt('print') && ($params->get('comments') == '1' || ($params->get('comments') == '2' && K2HelperPermissions::canAddComment($this->item->catid)))): ?>
		          <h3> <?php echo JText::_('K2_LEAVE_A_COMMENT') ?> </h3>
		          <div class="itemCommentsForm"> <?php echo $this->loadTemplate('comments_form'); ?> </div>
		          <?php endif; ?>
		          <?php $user = JFactory::getUser(); if ($params->get('comments') == '2' && $user->guest):?>
		          <div> <?php echo JText::_('K2_LOGIN_TO_POST_COMMENTS');?> </div>
		          <?php endif; ?>
		     </div>
		     <?php endif; ?>
		     
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