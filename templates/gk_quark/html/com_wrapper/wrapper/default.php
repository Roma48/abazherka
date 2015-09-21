<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_wrapper' . DS . 'wrapper' . DS . 'default.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>
<script type="text/javascript">
function iFrameHeight() {
	var h = 0;
	if (!document.all) {
		h = document.getElementById('blockrandom').contentDocument.height;
		document.getElementById('blockrandom').style.height = h + 60 + 'px';
	} else if (document.all) {
		h = document.frames('blockrandom').document.body.scrollHeight;
		document.all.blockrandom.style.height = h + 20 + 'px';
	}
}
</script>

<div class="contentpane <?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<header class="component-header">
		<h1>
			<?php if ($this->escape($this->params->get('page_heading'))) :?>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
			<?php else : ?>
			<?php echo $this->escape($this->params->get('page_title')); ?>
			<?php endif; ?>
		</h1>
	</header>
    <?php endif; ?>
    
    <iframe <?php echo $this->wrapper->load; ?>
    	id="blockrandom"
    	name="iframe"
    	src="<?php echo $this->escape($this->wrapper->url); ?>"
    	width="<?php echo $this->escape($this->params->get('width')); ?>"
    	height="<?php echo $this->escape($this->params->get('height')); ?>"
    	scrolling="<?php echo $this->escape($this->params->get('scrolling')); ?>"
    	frameborder="<?php echo $this->escape($this->params->get('frameborder', 1)); ?>"
    	class="wrapper<?php echo $this->pageclass_sfx; ?>">
    	<?php echo JText::_('COM_WRAPPER_NO_IFRAMES'); ?>
    </iframe>
</div>
<?php endif; ?>