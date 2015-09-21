<?php

// no direct access
defined('_JEXEC') or die;

// Template override
jimport('joomla.filesystem.file');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$override = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $templateParams->get('custom_override', '-1') . DS . 'html' . DS;
$override .=  'com_weblinks' . DS . 'form' . DS . 'edit.php';

if(
	$templateParams->get('custom_override', '-1') !== '-1' && 
	JFile::exists($override) &&
	__FILE__ !== $override
) :
	include_once($override);
else :
?>

<?php

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

// Create shortcut to parameters.
$params = $this->state->get('params');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'weblink.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task);
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<section class="edit<?php echo $this->pageclass_sfx; ?>">
          <?php if ($this->params->get('show_page_heading', 1)) : ?>
          <header>
                    <h1 class="header"><span><?php echo $this->escape($this->params->get('page_heading')); ?></span></h1>
          </header>
          <?php endif; ?>
          <form action="<?php echo JRoute::_('index.php?option=com_weblinks&view=form&w_id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
                    <fieldset>
                              <legend><?php echo JText::_('COM_WEBLINKS_LINK'); ?></legend>
                              <div class="formelm"> <?php echo $this->form->getLabel('title'); ?> <?php echo $this->form->getInput('title'); ?> </div>
                              <div class="formelm"> <?php echo $this->form->getLabel('alias'); ?> <?php echo $this->form->getInput('alias'); ?> </div>
                              <div class="formelm"> <?php echo $this->form->getLabel('catid'); ?> <?php echo $this->form->getInput('catid'); ?> </div>
                              <div class="formelm"> <?php echo $this->form->getLabel('url'); ?> <?php echo $this->form->getInput('url'); ?> </div>
                              <?php if ($this->user->authorise('core.edit.state', 'com_weblinks.weblink')): ?>
                              <div class="formelm"> <?php echo $this->form->getLabel('state'); ?> <?php echo $this->form->getInput('state'); ?> </div>
                              <?php endif; ?>
                              <div class="formelm"> <?php echo $this->form->getLabel('language'); ?> <?php echo $this->form->getInput('language'); ?> </div>
                              <div class="formelm-buttons">
                                        <button type="button" onclick="Joomla.submitbutton('weblink.save')"> <?php echo JText::_('JSAVE') ?> </button>
                                        <button type="button" onclick="Joomla.submitbutton('weblink.cancel')"> <?php echo JText::_('JCANCEL') ?> </button>
                              </div>
                              <div> <?php echo $this->form->getLabel('description'); ?> <?php echo $this->form->getInput('description'); ?> </div>
                    </fieldset>
                    <input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
                    <input type="hidden" name="task" value="" />
                    <?php echo JHtml::_( 'form.token' ); ?>
          </form>
</section>
<?php endif; ?>