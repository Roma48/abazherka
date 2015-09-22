<?php
/**
 * @version		$Id: authors.php 1812 2013-01-14 18:45:06Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'mod_k2_tools' . DS . 'authors.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<div id="k2ModuleBox<?php echo $module->id; ?>" class="k2AuthorsListBlock<?php if($params->get('moduleclass_sfx')) echo ' '.$params->get('moduleclass_sfx'); ?>">
  <ul>
    <?php foreach ($authors as $author): ?>
    <li>
      <?php if ($params->get('authorAvatar')): ?>
      <a class="k2Avatar abAuthorAvatar" rel="author" href="<?php echo $author->link; ?>" title="<?php echo K2HelperUtilities::cleanHtml($author->name); ?>">
      	<img src="<?php echo $author->avatar; ?>" alt="<?php echo K2HelperUtilities::cleanHtml($author->name); ?>" style="width:<?php echo $avatarWidth; ?>px;height:auto;" />
      </a>
      <?php endif; ?>

      <a class="abAuthorName" rel="author" href="<?php echo $author->link; ?>">
      	<strong><?php echo $author->name; ?>

      	<?php if ($params->get('authorItemsCounter')): ?>
      	<span>(<?php echo $author->items; ?>)</span>
      	<?php endif; ?></strong>
      </a>

      <?php if ($params->get('authorLatestItem')): ?>
      <a class="abAuthorLatestItem" href="<?php echo $author->latest->link; ?>" title="<?php echo K2HelperUtilities::cleanHtml($author->latest->title); ?>">
      	<?php echo $author->latest->title; ?>
	      <span class="abAuthorCommentsCount">
	      	(<?php echo $author->latest->numOfComments; ?> <?php if($author->latest->numOfComments=='1') echo JText::_('K2_MODK2TOOLS_COMMENT'); else echo JText::_('K2_MODK2TOOLS_COMMENTS'); ?>)
	      </span>
      </a>
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>