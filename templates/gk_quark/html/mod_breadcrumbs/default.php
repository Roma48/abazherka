<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'mod_breadcrumbs' . DS . 'default.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<?php 
	echo '<ul class="breadcrumbs">';
    for ($i = 0; $i < $count; $i ++) {
		// If not the last item in the breadcrumbs add the separator
		if ($i < $count -1) {
			if (!empty($list[$i]->link)) echo '<li><a href="'.$list[$i]->link.'" >'.$list[$i]->name.'</a></li>';
			else echo '<li class="pathway">' . $list[$i]->name . '</li>';
			if($i < $count -2) echo ' <li class="separator">/</li> ';
		} else if ($params->get('showLast', 1)) { // when $i == $count -1 and 'showLast' is true
			if($i > 0) echo ' <li class="separator">/</li> ';
			echo '<li>' . $list[$i]->name . '</li>';
		}
	} 
    echo '</ul>';
?>
<?php endif; ?>