<?php 

//
// Functions used in layouts
//

class GKTemplateLayout {
    //
    private $parent;
    // APIs from the parent to use in the loadBlocks functions
    public $API;
    public $cache;
    public $social;
    public $utilities;
    public $menu;
    // frontpage detection variables
    public $globalMenuActive = null;
    public $globalMenuLanguage = null;
    //
    
    function __construct($parent) {
    	$this->parent = $parent;
    	$this->API = $parent->API;
    	$this->cache = $parent->cache;
    	$this->social = $parent->social;
    	$this->utilities = $parent->utilities;
    	$this->menu = $parent->menu;
    }
	// function to load specified block
	public function loadBlock($path) {
	    jimport('joomla.filesystem.file');
	    
	    if(JFile::exists($this->API->URLtemplatepath() . DS . 'layouts' . DS . 'blocks' . DS . $path . '.php')) { 
	        include($this->API->URLtemplatepath() . DS . 'layouts' . DS . 'blocks' . DS . $path . '.php');
	    }
	}   
	// function to generate tablet and mobile width & base CSS urles
	public function generateLayoutWidths() {
		//
		$template_width = $this->API->get('template_width', 1230); // get the template width
		$tablet_width = $this->API->get('tablet_width', 1030); // get the tablet width
		$tablet_small_width = $this->API->get('tablet_small_width', 820); // get the small tablet width
		$mobile_width = $this->API->get('mobile_width', 580); // get the mobile width
		//
		$sidebar_width = $this->getSidebarWidthOverride(); // get the sidebar width
		$content_width = 100;
		$contentwrap_width = 100;
		$com_k2_page = false;
		$com_content_page = false;
		
		if(JRequest::getCmd('option') == 'com_k2') {
			$com_k2_page = true;
		}
		
		if(JRequest::getCmd('option') == 'com_content') {
			$com_content_page = true;
		}
		//
		if($this->API->modules('sidebar') && !$com_k2_page && !$com_content_page) {
			$content_width = 100 - $sidebar_width;
			// generate sidebar width
			$this->API->addCSSRule('#gkSidebar { width: '.$sidebar_width.'%; }' . "\n");
		} elseif($this->API->modules('sidebar') && ($com_k2_page || $com_content_page)) {
			// generate wrapper width
			$this->API->addCSSRule('#gk-content-wrapper { width: '.(100-$sidebar_width).'%; }' . "\n");
			// generate sidebar width
			$this->API->addCSSRule('#gkSidebar { width: '.$sidebar_width.'%; }' . "\n");
		}
		//
		$option_val = JRequest::getCmd('option');
		$view_val = JRequest::getCmd('view');
		// generate content width
        $this->API->addCSSRule('#gkContent { width: '.$content_width.'%; }' . "\n");
        // generate content wrap width
        $this->API->addCSSRule('#gkContentWrap { width: '.$contentwrap_width.'%; }' . "\n");
		// set the max width for the page
		$this->API->addCSSRule('.gkPage, #gkHeaderNav .gkPage, #gkMainbody .content { max-width: '.$template_width.'px; }' . "\n");
		// generate the data attributes
		echo ' data-tablet-width="'.($tablet_width).'" data-mobile-width="'.($mobile_width).'"';
	}
    // function to generate blocks paddings
    public function generateLayout() {
		$template_width = $this->API->get('template_width', 1230); // get the template width
		$tablet_width = $this->API->get('tablet_width', 1030); // get the tablet width
		$tablet_small_width = $this->API->get('tablet_small_width', 820); // get the small tablet width
		$mobile_width = $this->API->get('mobile_width', 580); // get the mobile width
		
		$override_suffix = '';
		
		if($this->API->get('custom_override', '-1') != '-1') {
			$override_suffix = '.' . $this->API->get('custom_override', '-1');
		}
		
		if($this->API->get('rwd', 1)) {
			// set media query for small desktops
			echo '<link rel="stylesheet" href="'.($this->API->URLtemplate()).'/css/small.desktop'.$override_suffix.'.css" media="(max-width: 1920px)" />' . "\n";	
			// set media query for the tablet.css
			echo '<link rel="stylesheet" href="'.($this->API->URLtemplate()).'/css/tablet'.$override_suffix.'.css" media="(max-width: '.$tablet_width.'px)" />' . "\n";
			// set media query for the small tablets
			echo '<link rel="stylesheet" href="'.($this->API->URLtemplate()).'/css/small.tablet'.$override_suffix.'.css" media="(max-width: '.$tablet_small_width.'px)" />' . "\n";	
			// set media query for the mobile.css
			echo '<link rel="stylesheet" href="'.($this->API->URLtemplate()).'/css/mobile'.$override_suffix.'.css" media="(max-width: '.$mobile_width.'px)" />' . "\n";
	       	// CSS to avoid problems with the K2/com_content columns on the smaller screens
	    	$this->API->addCSSRule('@media screen and (max-width: '.($tablet_width * 0.75).'px) {
	    	#k2Container .itemsContainer { width: 100%!important; } 
	    	.cols-2 .column-1,
	    	.cols-2 .column-2,
	    	.cols-3 .column-1,
	    	.cols-3 .column-2,
	    	.cols-3 .column-3,
	    	.demo-typo-col2,
	    	.demo-typo-col3,
	    	.demo-typo-col4 {width: 100%; }
	    	}');
	    }
    	// set CSS code for the messages
    	//$this->API->addCSSRule('#system-message-container { margin: 0 -'.$body_padding.'px; }');
    	
    	// CSS for the grid elements
	    $featured_image_height = $this->API->get('featured_image_height', 820);
	    $featured_image_height_full_hd = $this->API->get('featured_image_height_fullhd', 640);
	    $featured_image_height_desktop = $this->API->get('featured_image_height_desktop', 540);
	    $featured_image_height_tablet = $this->API->get('featued_image_height_tablet', 480);
	    $featured_image_height_small_tablet = $this->API->get('featured_image_height_small_tablet', 320);
	    $featured_image_height_mobile = $this->API->get('featured_image_height_mobile', 240);
	    
	    $this->API->addCSSRule('@media screen and (min-width: 1920px) {
	    	#gkHeaderMod,
	    	.blog-page > .header,
	    	.single-page > .header,
	    	.search-page .header { height: '.$featured_image_height.'px; }
	    }' . "\n");
	    
	    $this->API->addCSSRule('@media screen and (max-width: 1920px) {
	    	#gkHeaderMod,
	    	.blog-page > .header,
	    	.single-page > .header,
	    	.search-page .header { height: '.$featured_image_height_full_hd.'px; }
	    }' . "\n");
	    
	    $this->API->addCSSRule('@media screen and (max-width: '. ($template_width + 400) .'px) {
	    	#gkHeaderMod,
	    	.blog-page > .header,
	    	.single-page > .header,
	    	.search-page .header { height: '.$featured_image_height_desktop.'px; }
	    }' . "\n");
	    
	    $this->API->addCSSRule('@media screen and (max-width: '. $tablet_width .'px) {
	    	#gkHeaderMod,
	    	.blog-page > .header,
	    	.single-page > .header,
	    	.search-page .header { height: '.$featured_image_height_tablet.'px; }
	    }' . "\n");
	    
	    $this->API->addCSSRule('@media screen and (max-width: '. $tablet_small_width .'px) {
	    	#gkHeaderMod,
	    	.blog-page > .header,
	    	.single-page > .header,
	    	.search-page .header { height: '.$featured_image_height_small_tablet.'px; }
	    }' . "\n");
	    
	    $this->API->addCSSRule('@media screen and (max-width: '. $mobile_width .'px) {
	    	#gkHeaderMod,
	    	.blog-page > .header,
	    	.single-page > .header,
	    	.search-page .header { height: '.$featured_image_height_mobile.'px; }
	    }' . "\n");
	    
	    // CSS for the frontpage
	    $frontpage_image_height = $this->API->get('frontpage_image_height', 880);
	    $frontpage_image_height_full_hd = $this->API->get('frontpage_image_height_fullhd', 800);
	    $frontpage_image_height_desktop = $this->API->get('frontpage_image_height_desktop', 640);
	    $frontpage_image_height_tablet = $this->API->get('frontpage_image_height_tablet', 500);
	    $frontpage_image_height_small_tablet = $this->API->get('frontpage_image_height_small_tablet', 320);
	    $frontpage_image_height_mobile = $this->API->get('frontpage_image_height_mobile', 240);
	    
	    $this->API->addCSSRule('@media screen and (min-width: 1920px) {
	    	.frontpage #gkHeaderMod,
	    	.frontpage .blog-page > .header,
	    	.frontpage .single-page > .header,
	    	.frontpage .search-page .header { height: '.$frontpage_image_height.'px; }
	    }' . "\n");
	    
	    $this->API->addCSSRule('@media screen and (max-width: 1920px) {
	    	.frontpage #gkHeaderMod,
	    	.frontpage .blog-page > .header,
	    	.frontpage .single-page > .header,
	    	.frontpage .search-page .header { height: '.$frontpage_image_height_full_hd.'px; }
	    }' . "\n");
	    
	    $this->API->addCSSRule('@media screen and (max-width: '. ($template_width + 400) .'px) {
	    	.frontpage #gkHeaderMod,
	    	.frontpage .blog-page > .header,
	    	.frontpage .single-page > .header,
	    	.frontpage .search-page .header { height: '.$frontpage_image_height_desktop.'px; }
	    }' . "\n");
	    
	    $this->API->addCSSRule('@media screen and (max-width: '. $tablet_width .'px) {
	    	.frontpage #gkHeaderMod,
	    	.frontpage .blog-page > .header,
	    	.frontpage .single-page > .header,
	    	.frontpage .search-page .header { height: '.$frontpage_image_height_tablet.'px; }
	    }' . "\n");
	    
	    $this->API->addCSSRule('@media screen and (max-width: '. $tablet_small_width .'px) {
	    	.frontpage #gkHeaderMod,
	    	.frontpage .blog-page > .header,
	    	.frontpage .single-page > .header,
	    	.frontpage .search-page .header { height: '.$frontpage_image_height_small_tablet.'px; }
	    }' . "\n");
	    
	    $this->API->addCSSRule('@media screen and (max-width: '. $mobile_width .'px) {
	    	.frontpage #gkHeaderMod,
	    	.frontpage .blog-page > .header,
	    	.frontpage .single-page > .header,
	    	.frontpage .search-page .header { height: '.$frontpage_image_height_mobile.'px; }
	    }' . "\n");
    	
    	if($this->API->get("css_override", '0')) {
    		echo '<link rel="stylesheet" href="'.($this->API->URLtemplate()).'/css/override'.$override_suffix.'.css" />' . "\n";
    	}
    }
    
    public function getSidebarWidthOverride() {
    	// get current ItemID
        $ItemID = JRequest::getInt('Itemid');
        // get current option value
        $option = JRequest::getCmd('option');
        // override array
        $sidebar_width_override = $this->parent->config->get('sidebar_width_override');
        // check the config
        if (isset($sidebar_width_override[$ItemID])) {
            return $sidebar_width_override[$ItemID];
        } else {
            return (isset($sidebar_width_override[$option])) ? $sidebar_width_override[$option] : $this->API->get('sidebar_width', 30);
        }   
    }
    
    // function to check if the page is frontpage
    function isFrontpage() {
        $app = JFactory::getApplication();
        $menu = $app->getMenu();
        $lang = JFactory::getLanguage();
        return ($menu->getActive() == $menu->getDefault($lang->getTag()));    
    }

	public function addTouchIcon() {
	     $touch_image = $this->API->get('touch_image', '');
	    
	     if($touch_image == '') {
	          $touch_image = $this->API->URLtemplate() . '/images/touch-device.png';
	     } else {
	          $touch_image = $this->API->URLbase() . $touch_image;
	     }
	     $doc = JFactory::getDocument();
	     $doc->addCustomTag('<link rel="apple-touch-icon" href="'.$touch_image.'">');
	     $doc->addCustomTag('<link rel="apple-touch-icon-precomposed" href="'.$touch_image.'">');
	}

	public function addTemplateFavicon() {
		$favicon_image = $this->API->get('favicon_image', '');
		
		if($favicon_image == '') {
			$favicon_image = $this->API->URLtemplate() . '/images/favicon.ico';
		} else {
			$favicon_image = $this->API->URLbase() . $favicon_image;
		}
		
		$this->API->addFavicon($favicon_image);
	}
	
	public function getTemplateStyle($type) {
		$template_style = $this->API->get("template_color", 1);
		
		if($this->API->get("stylearea", 1)) {
			if(isset($_COOKIE['gk_'.$this->parent->name.'_'.$type])) {
				$template_style = $_COOKIE['gk_'.$this->parent->name.'_'.$type];
			} else {
				$template_style = $this->API->get("template_color", 1);
			}
		}
		
		return $template_style;
	}
}

// EOF