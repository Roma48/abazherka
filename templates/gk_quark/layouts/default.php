<?php

/**
 *
 * Default view
 *
 * @version             1.0.0
 * @package             Gavern Framework
 * @copyright			Copyright (C) 2010 - 2011 GavickPro. All rights reserved.
 *               
 */
 
// No direct access.
defined('_JEXEC') or die;
//
$app = JFactory::getApplication();
$menu = $app->getMenu();
$user = JFactory::getUser();
// getting User ID
$userID = $user->get('id');
// getting params
$option = JRequest::getCmd('option', '');
$view = JRequest::getCmd('view', '');
$item_id = JRequest::getCmd('itemId', '');
$error_item_id = $this->API->get('error_page_menu_item_id', '99999');
// defines if com_users
define('GK_COM_USERS', $option == 'com_users' && ($view == 'login' || $view == 'registration'));
// other variables
$btn_login_text = ($userID == 0) ? JText::_('TPL_GK_LANG_LOGIN') : JText::_('TPL_GK_LANG_LOGOUT');
// make sure that the modal will be loaded
JHTML::_('behavior.modal');
// check for the one page layout
$one_page_item = false;
$category_page = false;
$tag_style_page = false;
$com_k2_page = false;
$com_content_page = false;

if(JRequest::getCmd('option') == 'com_k2') {
	$com_k2_page = true;
}

if(JRequest::getCmd('option') == 'com_content') {
	$com_content_page = true;
}

if(
	JRequest::getCmd('option') == 'com_search' ||
	JRequest::getCmd('option') == 'com_finder'
) {
	$one_page_item = true;
}

if(
	JRequest::getCmd('option') == 'com_k2' && 
	JRequest::getCmd('view') == 'item'
) {
	$one_page_item = true;
}

if(
	JRequest::getCmd('option') == 'com_content' && 
	JRequest::getCmd('view') == 'article'
) {
	$one_page_item = true;
}

if(
	JRequest::getCmd('option') == 'com_k2' && 
	JRequest::getCmd('view') == 'itemlist'
) {
	$category_page = true;
}

if(
	JRequest::getCmd('option') == 'com_content' && 
	JRequest::getCmd('view') == 'category' &&
	(
		JRequest::getCmd('layout') == 'blog' ||
		JRequest::getCmd('layout') == 'gk_quarkrestaurant'
	)
) {
	$category_page = true;
}

if(
	JRequest::getCmd('option') == 'com_content' && 
	JRequest::getCmd('view') == 'featured'
) {
	$category_page = true;
}

if(
	JRequest::getCmd('option') == 'com_k2' && 
	JRequest::getCmd('view') == 'itemlist' && 
	(
		JRequest::getCmd('layout') == 'tag' || 
		in_array(
			JRequest::getCmd('task'), 
			array('tag', 'date', 'search', 'latest')
		)
	)
) {
	$tag_style_page = true;
}

// Set the page suffix
$dark_bg = ($one_page_item || $category_page) && !$tag_style_page && !$item_id == $error_item_id ? ' dark-bg' : '';

if(
	$this->API->get('template_style') === 'ecommerce.main.less' && 
	(
		JRequest::getCmd('option') == 'com_search' ||
		JRequest::getCmd('option') == 'com_finder'
	)
) {
	$dark_bg = '';
}

$error_page = $item_id == $error_item_id ? ' error-page' : '';
$page_suffix_output = $this->page_suffix;
//echo "<pre>";
//var_dump($page_suffix_output);
//echo "</pre>";
//exit;
if(stripos($page_suffix_output, 'frontpage') !== FALSE) {
	$dark_bg = ''; 
}

$page_suffix_table = explode(' ', $page_suffix_output);
$tpl_page_suffix = $page_suffix_output . $dark_bg . $error_page != '' ? ' class="' . $page_suffix_output . $dark_bg . $error_page . '" ' : '';

?>
<!DOCTYPE html>
<html lang="<?php echo $this->APITPL->language; ?>">
<head>
	<?php $this->layout->addTouchIcon(); ?>
	<?php if(
			$this->browser->get('browser') == 'ie6' || 
			$this->browser->get('browser') == 'ie7' || 
			$this->browser->get('browser') == 'ie8' || 
			$this->browser->get('browser') == 'ie9'
		) : ?>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
	
	<?php endif; ?>
    <?php if($this->API->get('rwd', 1)) : ?>
    	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
	<?php else : ?>
		<meta name="viewport" content="width=<?php echo $this->API->get('template_width', 1020)+80; ?>">
	<?php endif; ?>
    <jdoc:include type="head" />
    <?php $this->layout->loadBlock('head'); ?>
	<?php $this->layout->loadBlock('cookielaw'); ?>
	
	<?php if($this->API->get('page_preloader', '1') === '1') : ?>
	<style>
	#gk-page-preloader { background: #fff url('<?php echo $this->API->URLtemplate(); ?>/images/preloaders/<?php echo $this->API->get('page_preloader_img', 'default.gif'); ?>') no-repeat center center; height: 100%; position: fixed; width: 100%; z-index: 10000000; }
	</style>
	<?php endif; ?>
</head>
<body<?php echo $tpl_page_suffix; ?><?php if($this->browser->get("tablet") == true) echo ' data-tablet="true"'; ?><?php if($this->browser->get("mobile") == true) echo ' data-mobile="true"'; ?><?php $this->layout->generateLayoutWidths(); ?> data-zoom-size="<?php echo $this->API->get('gk_zoom_size', '150'); ?>" data-parallax="<?php echo ($this->API->get('js_parallax', '1') == '1') ? 'true' : 'false'; ?>">	
	<?php
	     // put Google Analytics code
	     echo $this->social->googleAnalyticsParser();
	?>
	
	<?php if($this->API->get('page_preloader', '1') === '1') : ?>
	<div id="gk-page-preloader"></div>
	<?php endif; ?>
	
	
	<?php if ($this->browser->get('browser') == 'ie8' || $this->browser->get('browser') == 'ie7' || $this->browser->get('browser') == 'ie6') : ?>
	<!--[if lte IE 8]>
	<div id="ieToolbar"><div><?php echo JText::_('TPL_GK_LANG_IE_TOOLBAR'); ?></div></div>
	<![endif]-->
	<?php endif; ?>

	<div id="gkBg">	
		<?php if(in_array('frontpage', $page_suffix_table) !== FALSE) : ?>	
	    <?php if(count($app->getMessageQueue())) : ?>
	    <jdoc:include type="message" />
	    <?php endif; ?>
	    <?php endif; ?>
	    
	    <header id="gkHeader" class="gk-clearfix">		
			<div id="gkHeaderNav" class="gk-clearfix<?php if($this->API->get('menu_fixed', 0) == '1' && $this->API->get('menu_type', 'off-canvas') === 'classic') : ?> gk-fixed<?php endif; ?><?php if($this->API->modules('cart')) : ?> contains-cart<?php endif; ?>">
                 <?php if($this->API->get('menu_fixed', 0) == '1') : ?>
                 <div>
                 <?php endif; ?>
                 
                 <?php $this->layout->loadBlock('logo'); ?>
                 
                 <?php if($this->API->get('menu_type', 'off-canvas') === 'classic') : ?>
                 	<?php
                 		$this->mainmenu->loadMenu($this->API->get('menu_name','mainmenu')); 
                    	$this->mainmenu->genMenu($this->API->get('startlevel', 0), $this->API->get('endlevel',-1));
                 	?>
                 <?php endif; ?>
                 
                 <?php if($this->API->get('show_menu', 1)) : ?>
                 <div id="gkMobileMenu" class="gkPage<?php if($this->API->get('menu_type', 'off-canvas') === 'off-canvas') : ?> off-canvas<?php endif; ?>">
<!--                     <span id="gk-mobile-menu-text">--><?php //echo JText::_('TPL_GK_LANG_MENU'); ?><!--</span>-->
                     <i id="static-aside-menu-toggler"></i>
                 </div>
                 <?php endif; ?>
                 
                 <?php if($this->API->modules('cart')) : ?>
                     <div id="gk-cart-btn">
<!--                     	<i class="gkicon-cart"></i>-->
						 <jdoc:include type="modules" name="cart" style="none" />
                     </div>

                     <?php $this->layout->loadBlock('cart'); ?>
                 <?php endif; ?>
                 
                 <?php if($this->API->get('menu_fixed', 0) == '1') : ?>
                 </div>
                 <?php endif; ?>
	    	</div>

	    	<?php if($this->API->modules('header') && $item_id != $error_item_id) : ?>
	    	<div id="gkHeaderMod" class="gk-clearfix">
	    		<jdoc:include type="modules" name="header" style="none" />
			</div>
	    	<?php endif; ?>
	    </header>
	    
	    <?php if(in_array('frontpage', $page_suffix_table) === FALSE) : ?>	
	    <?php if(count($app->getMessageQueue())) : ?>
	    <jdoc:include type="message" />
	    <?php endif; ?>
	    <?php endif; ?>
	
		<?php if(
			$this->API->modules('breadcrumb') && 
			$item_id != $error_item_id && 
			(
				in_array('frontpage', $page_suffix_table) === TRUE || 
				(!$category_page && !$one_page_item)
			)
		) : ?>
		<div id="gkBreadcrumb">
			<div class="gkPage">
				<jdoc:include type="modules" name="breadcrumb" style="none" />
			</div>
		</div>
		<?php endif; ?>
	
		<div id="gkPageContent">
	    	<?php if($this->API->modules('top1') && $item_id != $error_item_id) : ?>
	    	<section id="gkTop1" class="gk-clearfix<?php if($this->API->modules('top1') > 1) : ?> gkPage gkCols3<?php endif; ?>">
	    		<div <?php if(isset($this->module_ids['top1'])) echo ' id="'.$this->module_ids['top1'].'"'; ?> class="gk-clearfix<?php if($this->API->modules('top1') > 1) : ?> gk-not-single-module<?php endif; ?>">
	    			<jdoc:include type="modules" name="top1" style="gk_style" modcol="3" modnum="<?php echo $this->API->modules('top1'); ?>" modcol="3" />
	    		</div>
	    	</section>
	    	<?php endif; ?>
	    	
	    	<?php if($this->API->modules('top2') && $item_id != $error_item_id) : ?>
	    	<section id="gkTop2" class="gk-clearfix<?php if($this->API->modules('top2') > 1) : ?> gkPage gkCols3<?php endif; ?>">
	    		<div <?php if(isset($this->module_ids['top2'])) echo ' id="'.$this->module_ids['top2'].'"'; ?> class="gk-clearfix<?php if($this->API->modules('top2') > 1) : ?> gk-not-single-module<?php endif; ?>">
	    			<jdoc:include type="modules" name="top2" style="gk_style" modcol="3" modnum="<?php echo $this->API->modules('top2'); ?>" modcol="3" />
	    		</div>
	    	</section>
	    	<?php endif; ?>
	    	
	    	<div<?php if(!$one_page_item && !$category_page && !(stripos($tpl_page_suffix, 'frontpage') !== FALSE)) : ?> class="gkPage"<?php endif; ?>> 	
	    		<div<?php if($this->API->modules('sidebar') && $item_id != $error_item_id) : ?> data-sidebar-pos="<?php echo $this->API->get('sidebar_position', 'right'); ?>"<?php endif; ?>>
			    	<div id="gkContent">					
						<?php if($this->API->modules('mainbody_top') && $item_id != $error_item_id && !$com_k2_page && !$com_content_page) : ?>
						<section id="gkMainbodyTop" class="gkPage">
							<div<?php if(isset($this->module_ids['mainbody_top'])) echo ' id="'.$this->module_ids['mainbody_top'].'"'; ?>>
								<jdoc:include type="modules" name="mainbody_top" style="gk_style" />
							</div>
						</section>
						<?php endif; ?>	
						
						<?php if($item_id != $error_item_id) : ?>
						<section id="gkMainbody">
							<?php if(isset($this->module_ids['mainbody'])) : ?>
							<div<?php if(isset($this->module_ids['mainbody'])) echo ' id="'.$this->module_ids['mainbody'].'"'; ?>>
							<?php endif; ?>
							
								<?php if(!$this->API->modules('mainbody')) : ?>
								<jdoc:include type="component" />
								<?php else : ?>
								<jdoc:include type="modules" name="mainbody" style="gk_style" />
								<?php endif; ?>
							
							<?php if(isset($this->module_ids['mainbody'])) : ?>
							</div>
							<?php endif; ?>
						</section>
						<?php else : ?>
						<section id="gkMainbody">
							<?php $this->layout->loadBlock('error_page'); ?>
						</section>
						<?php endif; ?>
						
						<?php if($this->API->modules('mainbody_bottom') && $item_id != $error_item_id && !$com_k2_page && !$com_content_page) : ?>
						<section id="gkMainbodyBottom" class="gkPage">
							<div<?php if(isset($this->module_ids['mainbody_bottom'])) echo ' id="'.$this->module_ids['mainbody_bottom'].'"'; ?>>
								<jdoc:include type="modules" name="mainbody_bottom" style="gk_style" />
							</div>
						</section>
						<?php endif; ?>
			    	</div>
			    	
			    	<?php if($this->API->modules('sidebar') && $item_id != $error_item_id && !$com_k2_page && !$com_content_page) : ?>
			    	<aside id="gkSidebar">
			    		<div>
			    			<jdoc:include type="modules" name="sidebar" style="gk_style" />
			    		</div>
			    	</aside>
			    	<?php endif; ?>
		    	</div>
			</div>
		</div>
		
		<?php if($this->API->modules('bottom1') && $item_id != $error_item_id) : ?>
		<section id="gkBottom1" class="gk-clearfix<?php if($this->API->modules('bottom1') > 1) : ?> gkPage gkCols3<?php endif; ?>">
			<div <?php if(isset($this->module_ids['bottom1'])) echo ' id="'.$this->module_ids['bottom1'].'"'; ?> class="gk-clearfix<?php if($this->API->modules('bottom1') > 1) : ?> gk-not-single-module<?php endif; ?>">
				<jdoc:include type="modules" name="bottom1" style="gk_style" modcol="3" modnum="<?php echo $this->API->modules('bottom1'); ?>" />
			</div>
		</section>
		<?php endif; ?>
	    
	    <?php if($this->API->modules('bottom2') && $item_id != $error_item_id) : ?>
	    <section id="gkBottom2" class="gk-clearfix<?php if($this->API->modules('bottom2') > 1) : ?> gkPage gkCols3<?php endif; ?>">
	    	<div <?php if(isset($this->module_ids['bottom2'])) echo ' id="'.$this->module_ids['bottom2'].'"'; ?> class="gk-clearfix<?php if($this->API->modules('bottom2') > 1) : ?> gk-not-single-module<?php endif; ?>">
	    		<jdoc:include type="modules" name="bottom2" style="gk_style" modcol="3" modnum="<?php echo $this->API->modules('bottom2'); ?>" />
	    	</div>
	    </section>
	    <?php endif; ?>
	    
	    <?php if($this->API->modules('bottom3') && $item_id != $error_item_id) : ?>
	    <section id="gkBottom3" class="gk-clearfix<?php if($this->API->modules('bottom3') > 1) : ?> gkPage gkCols3<?php endif; ?>">
	    	<div <?php if(isset($this->module_ids['bottom3'])) echo ' id="'.$this->module_ids['bottom3'].'"'; ?> class="gk-clearfix<?php if($this->API->modules('bottom3') > 1) : ?> gk-not-single-module<?php endif; ?>">
	    		<jdoc:include type="modules" name="bottom3" style="gk_style" modcol="3" modnum="<?php echo $this->API->modules('bottom3'); ?>" />
	    	</div>
	    </section>
	    <?php endif; ?>
	    
	    <?php if($this->API->modules('bottom4') && $item_id != $error_item_id) : ?>
	    <section id="gkBottom4" class="gk-clearfix<?php if($this->API->modules('bottom4') > 1) : ?> gkPage gkCols3<?php endif; ?>">
	    	<div <?php if(isset($this->module_ids['bottom4'])) echo ' id="'.$this->module_ids['bottom4'].'"'; ?> class="gk-clearfix<?php if($this->API->modules('bottom4') > 1) : ?> gk-not-single-module<?php endif; ?>">
	    		<jdoc:include type="modules" name="bottom4" style="gk_style" modcol="3" modnum="<?php echo $this->API->modules('bottom4'); ?>" />
	    	</div>
	    </section>
	    <?php endif; ?>
	    
	    <?php if($this->API->modules('bottom5') && $item_id != $error_item_id) : ?>
	    <section id="gkBottom5" class="gk-clearfix<?php if($this->API->modules('bottom5') > 1) : ?> gkPage gkCols3<?php endif; ?>">
	    	<div <?php if(isset($this->module_ids['bottom5'])) echo ' id="'.$this->module_ids['bottom5'].'"'; ?> class="gk-clearfix<?php if($this->API->modules('bottom5') > 1) : ?> gk-not-single-module<?php endif; ?>">
	    		<jdoc:include type="modules" name="bottom5" style="gk_style" modcol="3" modnum="<?php echo $this->API->modules('bottom5'); ?>" />
	    	</div>
	    </section>
	    <?php endif; ?>
	    
	    <?php if($this->API->modules('bottom6') && $item_id != $error_item_id) : ?>
	    <section id="gkBottom6" class="gk-clearfix<?php if($this->API->modules('bottom6') > 1) : ?> gkPage gkCols3<?php endif; ?>">
	    	<div <?php if(isset($this->module_ids['bottom6'])) echo ' id="'.$this->module_ids['bottom6'].'"'; ?> class="gk-clearfix<?php if($this->API->modules('bottom6') > 1) : ?> gk-not-single-module<?php endif; ?>">
	    		<jdoc:include type="modules" name="bottom6" style="gk_style" modcol="3" modnum="<?php echo $this->API->modules('bottom6'); ?>" />
	    	</div>
	    </section>
	    <?php endif; ?>
	    
	    <?php if($this->API->modules('bottom7') && $item_id != $error_item_id) : ?>
	    <section id="gkBottom7" class="gk-clearfix<?php if($this->API->modules('bottom7') > 1) : ?> gkPage gkCols3<?php endif; ?>">
	    	<div <?php if(isset($this->module_ids['bottom7'])) echo ' id="'.$this->module_ids['bottom7'].'"'; ?> class="gk-clearfix<?php if($this->API->modules('bottom7') > 1) : ?> gk-not-single-module<?php endif; ?>">
	    		<jdoc:include type="modules" name="bottom7" style="gk_style" modcol="3" modnum="<?php echo $this->API->modules('bottom7'); ?>" />
	    	</div>
	    </section>
	    <?php endif; ?>
	    
	    <?php if($this->API->modules('bottom8') && $item_id != $error_item_id) : ?>
	    <section id="gkBottom8" class="gk-clearfix<?php if($this->API->modules('bottom8') > 1) : ?> gkPage gkCols3<?php endif; ?>">
	    	<div <?php if(isset($this->module_ids['bottom8'])) echo ' id="'.$this->module_ids['bottom8'].'"'; ?> class="gk-clearfix<?php if($this->API->modules('bottom8') > 1) : ?> gk-not-single-module<?php endif; ?>">
	    		<jdoc:include type="modules" name="bottom8" style="gk_style" modcol="3" modnum="<?php echo $this->API->modules('bottom8'); ?>" />
	    	</div>
	    </section>
	    <?php endif; ?>
	    
	    <?php if($this->API->modules('bottom9') && $item_id != $error_item_id) : ?>
	    <section id="gkBottom9" class="gk-clearfix<?php if($this->API->modules('bottom9') > 1) : ?> gkPage gkCols3<?php endif; ?>">
	    	<div <?php if(isset($this->module_ids['bottom9'])) echo ' id="'.$this->module_ids['bottom9'].'"'; ?> class="gk-clearfix<?php if($this->API->modules('bottom9') > 1) : ?> gk-not-single-module<?php endif; ?>">
	    		<jdoc:include type="modules" name="bottom9" style="gk_style" modcol="3" modnum="<?php echo $this->API->modules('bottom9'); ?>" />
	    	</div>
	    </section>
	    <?php endif; ?>
	    
	    <?php if($this->API->modules('lang')) : ?>
	    <div id="gkLang">
	    	<div class="gkPage">
	         	<jdoc:include type="modules" name="lang" style="gk_style" />
	         </div>
	    </div>
	    <?php endif; ?>
	    
	    <?php $this->layout->loadBlock('footer'); ?>
    </div>
    
   	<?php $this->layout->loadBlock('social'); ?>
   	
   	<?php if($this->API->get('menu_type', 'off-canvas') === 'off-canvas') : ?>	
   	<i id="close-menu">&times;</i>
   	<nav id="aside-menu">
   		<div>
   			<?php if($this->API->modules('menu_top')) : ?>
   			<div id="gk-menu-top">
   			     <jdoc:include type="modules" name="menu_top" style="none" />
   			</div>
   			<?php endif; ?>
   			
   			<?php
   				$this->asidemenu->loadMenu($this->API->get('menu_name','mainmenu')); 
   		    	$this->asidemenu->genMenu($this->API->get('startlevel', 0), $this->API->get('endlevel',-1));
   			?>
   			
   			<?php if($this->API->modules('menu_bottom')) : ?>
			<div id="gk-menu-bottom">
			     <jdoc:include type="modules" name="menu_bottom" style="none" />
			</div>
			<?php endif; ?>
   		</div>
   	</nav>
   	<?php endif; ?>	
   		
    <?php if($this->API->modules('login')) : ?>
    <div id="gk-login-popup">
    	 <a href="#" id="gk-login-popup-close">&times;</a>
         <jdoc:include type="modules" name="login" style="none" />
    </div>
    <div id="gk-login-popup-overlay"></div>
    <?php endif; ?>
    
    <?php if($this->API->modules('newsletter') && !isset($_COOKIE['gk-newsletter-popup'])) : ?>
    <div 
    	id="gk-newsletter-popup" 
    	data-display="<?php echo $this->API->get('newsletter_display', 'after_time'); ?>" 
    	data-scroll="<?php echo $this->API->get('newsletter_scroll', '1000'); ?>" 
    	data-time="<?php echo $this->API->get('newsletter_time', '10'); ?>"
    	class="hidden-popup"
    >
    	 <a href="#" id="gk-newsletter-popup-close">&times;</a>
         <jdoc:include type="modules" name="newsletter" style="none" />
    </div>
    <?php endif; ?>
   		
   	<?php if($this->API->get('js_scrollreveal', '1') == '1') : ?> 
	<script>
		if(jQuery(window).outerWidth() > 600) {
			function startCounting(el, final) {
				var time = el.attr('data-time') || 1000;
				var increase = Math.floor(final / (time / 24));
				
				if(increase < 1) {
					increase = 1;
				}
				
				var anim = setInterval(function() {
					var prev = parseInt(el.text(), 10);
					
					if(prev + increase < final) {
						el.text(prev + increase);
					} else {
						el.text(final);
						clearInterval(anim);
					}
				}, 24);
			}
			
			if(jQuery('.transparent-tabs').length) {
				jQuery('.transparent-tabs').each(function(i, tabs) {
					jQuery(tabs).find('.gkTabsNav li').each(function(j, tab) {
						jQuery(tab).attr('data-sr', 'wait ' + (0.15 * j) + 's and enter bottom and scale up 30% and move 50px');
					});
				});
			}
			
			var config = {
				complete: function(el) {
					el = jQuery(el);
					
					if(el.attr('data-count')) {
						startCounting(el, el.attr('data-count'));
					}
					
					if(el.hasClass('gk-add-rotate-animation')) {
						el.addClass('gk-rotate-animation');
					}
					
					if(el.hasClass('bar')) {
						el.addClass('gk-loaded');
					}
					
					if(el.parent().hasClass('gkNspPM-NewsSlider')) {
						el.attr('style', '');
						el.parent().addClass('gk-run-animation');
					}
					
					if(el.hasClass('gk-border-anim')) {
						el.addClass('gk-anim-complete');
					}
				},
				mobile: true
			};
			window.sr = new scrollReveal(config);
		} else {
			jQuery('span[data-count]').each(function(i, el) {
				if(jQuery(el).attr('data-sr')) {
					jQuery(el).text(jQuery(el).attr('data-count'));
				}
			});
			
			jQuery('*[data-sr]').each(function(i, el) {
				el = jQuery(el);
				
				if(el.hasClass('gk-add-rotate-animation')) {
					el.addClass('gk-rotate-animation');
				}
				
				if(el.hasClass('bar')) {
					el.addClass('gk-loaded');
				}
				
				if(el.parent().hasClass('gkNspPM-NewsSlider')) {
					el.attr('style', '');
					el.parent().addClass('gk-run-animation');
				}
				
				if(el.hasClass('gk-border-anim')) {
					el.addClass('gk-anim-complete');
				}
			});
		}
	</script>
	<?php else : ?>
	<script>
		jQuery('span[data-count]').each(function(i, el) {
			if(jQuery(el).attr('data-sr')) {
				jQuery(el).text(jQuery(el).attr('data-count'));
			}
		});
		
		jQuery('*[data-sr]').each(function(i, el) {
			el = jQuery(el);
//			console.log(el);
			
			if(el.hasClass('gk-add-rotate-animation')) {
				el.addClass('gk-rotate-animation');
			}
			
			if(el.hasClass('bar')) {
				el.addClass('gk-loaded');
			}
			
			if(el.parent().hasClass('gkNspPM-NewsSlider')) {
				el.attr('style', '');
				el.parent().addClass('gk-run-animation');
			}
			
			if(el.hasClass('gk-border-anim')) {
				el.addClass('gk-anim-complete');
			}
		});
	</script>
	<?php endif; ?>
	
	<jdoc:include type="modules" name="debug" />
</body>
</html>
<?php 

// EOF