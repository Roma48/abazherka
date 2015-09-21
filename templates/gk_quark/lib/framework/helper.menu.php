<?php

//
// Functions for menu
//

class GKTemplateMenu {
    //
    private $parent;
    //
    function __construct($parent) {
    	$this->parent = $parent;
    }	
	// function to get menu type
	public function getMenuType($type = false, $mobile = false) {
	    if(!$mobile) {
	    	if(!$type) {
	    		$file = dirname(__file__) . DS . '..' . DS . 'menu' . DS . 'GKMenu.php';
	    		$menuclass = 'GKMenu';
	    	} else {
	    		$file = dirname(__file__) . DS . '..' . DS . 'menu' . DS . 'GKAside.php';
	    		$menuclass = 'GKAsideMenu';
	    	}
	    	if (!is_file($file)) return null;
	    	require_once ($file);
	    	$this->parent->config->set('generateSubmenu', false);
	    
	    	$gkmenu = new $menuclass($this->parent->APITPL->params);
	    	$gkmenu->_tmpl = $this->parent->API;
	    
	    	return $gkmenu;
	    } else {
	    	$file = dirname(__file__) . DS . '..' . DS . 'menu' . DS . 'GKHandheld.php';
	    	if (!is_file($file)) return null;
	    	require_once ($file);
	    	$menuclass = 'GKHandheld';
	    	$this->parent->config->set('generateSubmenu', false);
	    	
	    	$gkmenu = new $menuclass($this->parent->APITPL->params);
	    	$gkmenu->_tmpl = $this->parent->API;
	    
	    	return $gkmenu;
	    }
	}
}

// EOF