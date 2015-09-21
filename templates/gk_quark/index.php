<?php

/**
 *
 * Main file
 *
 * @version             3.0.0
 * @package             Gavern Framework
 * @copyright			Copyright (C) 2010 - 2012 GavickPro. All rights reserved.
 *               
 */
 
// No direct access.
defined('_JEXEC') or die;

if(!defined('DS')){
   define('DS',DIRECTORY_SEPARATOR);
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// include framework classes and files
require_once('lib/gk.framework.php');
// run the framework
$tpl = new GKTemplate($this);

// EOF