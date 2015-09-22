<?php

/**
 *
 * Error view
 *
 * @version             1.0.0
 * @package             Gavern Framework
 * @copyright			Copyright (C) 2010 - 2011 GavickPro. All rights reserved.
 *              
 */

// No direct access.
defined('_JEXEC') or die;

if (!isset($this->error)) {
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false;
}

$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$uri = JUri::getInstance();

header('Location: '.$uri->base().'index.php?error='.$this->error->getCode().'&itemId=' . $templateParams->get('error_page_menu_item_id', '99999'));

exit();