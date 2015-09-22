<?php

defined('JPATH_PLATFORM') or die;

jimport('joomla.filesystem.folder');
JFormHelper::loadFieldClass('list');

/**
 * Supports an HTML select list of folder
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldGkFolderList extends JFormFieldList {
	protected $type = 'GkFolderList';
	protected $filter;
	protected $exclude;
	protected $hideNone = false;
	protected $directory;

	public function __get($name) {
		switch ($name) {
			case 'filter':
			case 'exclude':
			case 'hideNone':
			case 'directory':
				return $this->$name;
		}

		return parent::__get($name);
	}

	public function __set($name, $value) {
		switch ($name) {
			case 'filter':
			case 'directory':
			case 'exclude':
				$this->$name = (string) $value;
				break;

			case 'hideNone':
				$value = (string) $value;
				$this->$name = ($value === 'true' || $value === $name || $value === '1');
				break;

			default:
				parent::__set($name, $value);
		}
	}

	public function setup(SimpleXMLElement $element, $value, $group = null) {
		$return = parent::setup($element, $value, $group);

		if ($return) {
			$this->filter  = (string) $this->element['filter'];
			$this->exclude = (string) $this->element['exclude'];

			$hideNone       = (string) $this->element['hide_none'];
			$this->hideNone = ($hideNone == 'true' || $hideNone == 'hideNone' || $hideNone == '1');
			
			// Get the path in which to search for file options.
			$this->directory = (string) $this->element['directory'];
		}

		return $return;
	}

	protected function getOptions() {
		$options = array();

		$path = $this->directory;

		if (!is_dir($path)) {
			$path = JPATH_ROOT . '/' . $path;
		}

		// Prepend some default options based on field attributes.
		if (!$this->hideNone) {
			$options[] = JHtml::_('select.option', '-1', JText::alt('JOPTION_DO_NOT_USE', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
		}

		if(is_dir($path)) {
			// Get a list of folders in the search path with the given filter.
			$folders = JFolder::folders($path, $this->filter);
	
			// Build the options list from the list of folders.
			if (is_array($folders)) {
				foreach ($folders as $folder) {
					// Check to see if the file is in the exclude mask.
					if ($this->exclude) {
						if (preg_match(chr(1) . $this->exclude . chr(1), $folder)) {
							continue;
						}
					}
	
					$options[] = JHtml::_('select.option', $folder, $folder);
				}
			}
	
			// Merge any additional options in the XML definition.
			$options = array_merge(parent::getOptions(), $options);
	
			return $options;
		} else {
			return $options;
		}
	}
}
