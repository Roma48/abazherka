<?php

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldModuleIdOverride extends JFormField {
	public $type = 'ModuleIdOverride';

	protected function getInput() {
		$module_positions = explode(',', $this->element['module_positions']);
		$options_mp = array();

		foreach($module_positions as $pos) {
			$options_mp[] = JHtml::_('select.option', $pos, $pos);
		}

		$html = '';
		$html .= '<div id="moduleid_override_form">';
		$html .= '<div class="label">' . JText::_('TPL_GK_LANG_ADD_RULE_MOD_POS') . '</div>';
		$html .= JHtml::_('select.genericlist', $options_mp, 'name', '', 'value', 'text', 'default', 'moduleid_override_input');
		$html .= '<div class="label">' . JText::_('TPL_GK_LANG_ADD_RULE_ID') . '</div>';
		$html .= '<input type="text" id="moduleid_override_select" />';
		$html .= '<input class="btn" type="button" value="'.JText::_('TPL_GK_LANG_ADD_RULE').'" id="moduleid_override_add_btn" />';
		$html .= '<textarea name="'.$this->name.'" id="'.$this->id.'">' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '</textarea>';
		$html .= '<div id="moduleid_override_rules"></div>';
		$html .= '</div>';

		return $html;
	}
}