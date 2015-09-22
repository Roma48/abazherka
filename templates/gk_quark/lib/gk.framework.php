<?php

/**
 *
 * Main framework class
 *
 * @version             1.0.0
 * @package             Gavern Framework
 * @copyright			Copyright (C) 2010 - 2011 GavickPro. All rights reserved.
 *               
 */
 
// No direct access.
defined('_JEXEC') or die;

if(!defined('DS')){ 
	define('DS',DIRECTORY_SEPARATOR); 
}

require_once(dirname(__file__) . DS . 'framework' . DS . 'gk.parser.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'gk.browser.php');

require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.api.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.cache.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.layout.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.less.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.menu.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.social.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.utilities.php');

/*
* Main framework class
*/
class GKTemplate {
    // template name
    public $name = 'quark_j30';
    // access to the standard Joomla! template API
    public $API;
    // access to the helper classes
    public $cache;
    public $layout;
    public $less;
    public $social;
    public $utilities;
    public $menu;
    // detected browser:
    public $browser;
    // page config
    public $config;
    // module IDs
    public $module_ids;
    // page menu
    public $mainmenu;
    // aside menu
    public $asidemenu;
    // page suffix
    public $page_suffix;
    
    // constructor
    public function __construct($tpl, $embed_mode = false) {
        // load the mootools
        JHtml::_('behavior.framework', true);
		// put the template handler into API field
        $this->API = new GKTemplateAPI($tpl);
        $this->APITPL = $tpl;
        // get the helpers
        $this->cache = new GKTemplateCache($this);
        $this->less = new GKTemplateLESS($this, false);
        $this->social = new GKTemplateSocial($this);
        $this->utilities = new GKTemplateUtilities($this);
        $this->menu = new GKTemplateMenu($this);
        // create instance of GKBrowser class and detect
        $browser = new GKBrowser();
        $this->browser = $browser->result;
        // get the params
        $this->getParameters();
        // get the page suffix
        $this->getSuffix();
        // get the modules IDs overrides
        $this->module_ids = $this->config->get('moduleid_override');
        // get type and generate menu
        $this->mainmenu = $this->menu->getMenuType(false, false);
        $this->asidemenu = $this->menu->getMenuType(true, false);
        // load the layout helper
        $this->layout = new GKTemplateLayout($this);
        // get the layout
        if(!$embed_mode) {   
			$this->getLayout();
        }
        // parse FB and Twitter buttons
        $this->social->socialApiParser($embed_mode);
        // define an event for replacement
        $dispatcher = JDispatcher::getInstance();
 		// set a proper event for GKParserPlugin 
 		if($this->API->get('use_gk_cache', 0) == 0) {
 			$dispatcher->register('onAfterRender', 'GKParserPlugin');
 		} else {
 			$dispatcher->register('onBeforeCache', 'GKParserPlugin');
 		}
 		// remove JomSocial <html> addons ;)
 		GKParser::$customRules['/xmlns:fb="http:\/\/ogp\.me\/ns\/fb#"/mi'] = '';
 		// The scripts must be always loaded on the item editor
 		$item_editor = (JRequest::getCmd('option') == 'com_content' && JRequest::getCmd('view') == 'form' && JRequest::getCmd('layout') == 'edit') || JRequest::getCmd('option') == 'com_config';
 		$com_finder = JRequest::getCmd('option') == 'com_finder' && JRequest::getCmd('view') == 'search';
 		$user = JFactory::getUser();
 		
 		// Rules to remove predefined JS files
 		if(
 			(($this->API->get('mootools_core', '0') == '0' && $user->id == 0) ||
 			($this->API->get('mootools_core_logged_in', '1') == '0' && $user->id != 0)) &&
 			!$item_editor && !$com_finder
 		) {
 			GKParser::$customRules['/<script src="(.*?)media\/system\/js\/mootools-core.js" type="text\/javascript"><\/script>/mi'] = '';
 		}
 		
 		if(JRequest::getCmd('option') == 'com_config') {
 			$doc = JFactory::getDocument();
 			$doc->addStyleSheet(JURI::base() . '/media/jui/css/bootstrap.min.css');
 			$doc->addStyleSheet(JURI::base() . '/media/jui/css/bootstrap-responsive.min.css');
 			$doc->addStyleSheet(JURI::base() . '/media/jui/css/bootstrap-extended.css');
 			$doc->addStyleSheet(JURI::base() . '/media/jui/css/icomoon.css');
 			$doc->addStyleSheet(JURI::base() . '/media/jui/css/chosen.css');
 			$doc->addStyleSheet(JURI::base() . '/media/media/css/mediamanager.css');
 		}
 		
 		if(
 			(($this->API->get('mootools_more', '0') == '0' && $user->id == 0) || 
 			($this->API->get('mootools_more_logged_in', '1') == '0' && $user->id != 0)) && 
 			!$item_editor && !$com_finder
 		) {
 			GKParser::$customRules['/<script src="(.*?)media\/system\/js\/mootools-more.js" type="text\/javascript"><\/script>/mi'] = '';
 		}
 		
 		if(
 			(($this->API->get('jquery_noconflict', '0') == '0' && $user->id == 0) || 
 			($this->API->get('jquery_noconflict_logged_in', '1') == '0' && $user->id != 0)) && 
 			!$item_editor && !$com_finder
 		) {
 			GKParser::$customRules['/<script src="(.*?)media\/jui\/js\/jquery-noconflict.js" type="text\/javascript"><\/script>/mi'] = '';
 		}
 		
 		if(
 			(($this->API->get('bootstrap_js', '0') == '0' && $user->id == 0) || 
 			($this->API->get('bootstrap_js_logged_in', '1') == '0' && $user->id != 0)) && 
 			!$item_editor
 		) {
 			GKParser::$customRules['/<script src="(.*?)media\/jui\/js\/bootstrap.min.js" type="text\/javascript"><\/script>/mi'] = '';
 		}
 		
 		if(
 			(($this->API->get('modal_js', '0') == '0' && $user->id == 0) || 
 			($this->API->get('modal_js_logged_in', '0') == '0' && $user->id != 0)) && 
 			!$item_editor
 		) {
 			GKParser::$customRules['/<script src="(.*?)media\/system\/js\/modal.js" type="text\/javascript"><\/script>/mi'] = '';
 			GKParser::$customRules['/<link rel="stylesheet" href="(.*?)media\/system\/css\/modal.css" type="text\/css" \/>/mi'] = '';
 			GKParser::$customRules['/jQuery\(function\(\$\) \{.*?\$\(\'.hasTip\'\).*?JTooltips.*?\}\);.*?\}\);/mis'] = '';
 			GKParser::$customRules['/jQuery\(function\(\$\) \{.*?SqueezeBox\.initialize\(\{\}\);.*?\}\);.*?\}\);/mis'] = '';
 			GKParser::$customRules['/jQuery\(\'\.hasTooltip\'\)\.tooltip\(\{\"html\"\: true\,\"container\"\: \"body\"\}\)\;/mis'] = '';
 		}	
 	}
    
    // get the template parameters in PHP form
    public function getParameters() {
        // create config object
        $this->config = new JObject();
        // set layout override param
        $this->config->set('layout_override', $this->utilities->overrideArrayParse($this->API->get('layout_override', '')));
        $this->config->set('suffix_override', $this->utilities->overrideArrayParse($this->API->get('suffix_override', '')));
        $this->config->set('sidebar_width_override', $this->utilities->overrideArrayParse($this->API->get('sidebar_width_for_pages', '')));
    	$this->config->set('inset_width_override', $this->utilities->overrideArrayParse($this->API->get('inset_width_for_pages', '')));
    	$this->config->set('page_title_override', $this->utilities->overrideArrayParse($this->API->get('page_title_override', '')));
    	$this->config->set('moduleid_override', $this->utilities->overrideArrayParse($this->API->get('moduleid_override', '')));
	}
   
    // function to get layout for specified mode
    public function getLayout() {
		$layoutpath = $this->API->URLtemplatepath() . DS . 'layouts' . DS . 'default.php';
		
		if (is_file($layoutpath)) {
			include ($layoutpath);	
		} else {
			echo 'Default layout doesn\'t exist!';
		}
    }

	// function to get page suffix
	public function getSuffix() {
	    // check the override
	    $is_overrided = $this->getSuffixOverride();
	    // if current page is overrided
	    if ($is_overrided !== false) {
	        $this->page_suffix = $is_overrided;
	    } else { 
	    	$this->page_suffix = '';
	    }
	}

	// function to get layout override
	public function getSuffixOverride() {
	    // get current ItemID
	    $ItemID = JRequest::getInt('Itemid');
	    // get current option value
	    $option = JRequest::getCmd('option');
	    // override array
	    $suffix_overrides = $this->config->get('suffix_override');
	    // check the config
	    if (isset($suffix_overrides[$ItemID])) {
	        return $suffix_overrides[$ItemID];
	    } else {
	        if (isset($suffix_overrides[$option])) {
	            return $suffix_overrides[$option];
	        } else {
	            return false;
	        }
	    }
	}
}

if(!function_exists('GKParserPlugin')){
	function GKParserPlugin(){
		$parser = new GKParser();
	}
}
