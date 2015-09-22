<?php

/**
 *
 * Framework module styles
 *
 * @version             1.0.0
 * @package             GK Framework
 * @copyright			Copyright (C) 2010 - 2011 GavickPro. All rights reserved.
 * @license                
 */
 
// no direct access
defined('_JEXEC') or die;

$gkModulesCounter = array();

/**
 * gk_style
 */
 
function modChrome_gk_style($module, $params, $attribs) {	
	global $gkModulesCounter;
	
	if (!empty ($module->content)) {		
		$modnum_class = '';
		
		if(isset($attribs['name']) && isset($gkModulesCounter[$attribs['name']])) {
			$gkModulesCounter[$attribs['name']]++;
		} elseif(isset($attribs['name'])) {
			$gkModulesCounter[$attribs['name']] = 1;
		}
		
		/**
		 *
		 *	We will get following classes:
		 *
		 *	gkmod-1 - for 1 module
		 *	gkmod-2 - for 2 modules
		 *	gkmod-3 - for 3 modules
		 *	gkmod-4 - for 4 modules
		 *	gkmod-more - for more than 4 modules
		 *
		 *	gkmod-last-1 - for more than 4 modules and 1 module at the end
		 *	gkmod-last-2 - for more than 4 modules and 2 module at the end
		 *	gkmod-last-3 - for more than 4 modules and 3 module at the end
		 *
		 **/
		$num = 1; 
		$cols = 6;
		
		if(isset($attribs['modcol'])) {
			$cols = $attribs['modcol'];
		} 
		 
		if(isset($attribs['modnum'])) {
			$num = $attribs['modnum'];
			
			if($num > $cols) {
				$num = $num % $cols;
				
				if($num == 0) {
					$modnum_class = ' gkmod-' . $cols;
				} else {
					$modnum_class = ' gkmod-more gkmod-last-' . $num;
				}
			} else {
				$modnum_class = ' gkmod-' . $num;
			}
		}
		
		$margin_class = '';
		$overflow_class = '';		
		$clear_mode = false;
		
		if(stripos($params->get('moduleclass_sfx'), 'clear') !== FALSE) {
			$clear_mode = true;
		}
		
		$header_type = '3';
		
		echo '<div class="box ' . $params->get('moduleclass_sfx') . $modnum_class . $overflow_class . $margin_class . '"' . (($params->get('backgroundimage')) ? ' style="background-image:url('. $params->get('backgroundimage') . ');" ' : '') . '>';
		
		$gkPage = false;
		
		if(
			isset($attribs['name']) &&
			(
				$attribs['name'] == 'top1' ||
				$attribs['name'] == 'top2' ||
				$attribs['name'] == 'bottom1' ||
				$attribs['name'] == 'bottom2' ||
				$attribs['name'] == 'bottom3' ||
				$attribs['name'] == 'bottom4' ||
				$attribs['name'] == 'bottom5' ||
				$attribs['name'] == 'bottom6' ||
				$attribs['name'] == 'bottom7' ||
				$attribs['name'] == 'bottom8' ||
				$attribs['name'] == 'bottom9' ||
				$attribs['name'] == 'bottom10'   
			) &&
			$attribs['modnum'] == 1
		) {
			$gkPage = true;
		}
		
		if(stripos($params->get('moduleclass_sfx'), 'box-wide') !== FALSE) {
			$gkPage = false;
		}
		
		if($clear_mode == false) echo '<div class="box-wrap">';
		
		if($module->showtitle) {	
			$title = str_replace('[br]', '<br />', $module->title);
			if($params->get('module_link_switch')) {
				$title = preg_replace('/__(.*?)__/i', '</a><small>${1}</small>', $title);
				
				if(stripos($title, '</span>') === FALSE) {
					$title .= '</a>';
				}
			} else {
				$title = preg_replace('/__(.*?)__/i', '</span><small>${1}</small>', $title);
				
				if(stripos($title, '</span>') === FALSE) {
					$title .= '</span>';
				}
			}

			if($params->get('module_link_switch')) {
				echo '<h'.$header_type.' class="header'.($gkPage ? ' gkPage' : '').'"><a href="'. $params->get('module_link') .'">'. $title .'</h'.$header_type.'>';
			} else {
				echo '<h'.$header_type.' class="header'.($gkPage ? ' gkPage' : '').'"><span>'. $title .'</h'.$header_type.'>';
			}
		}
	
		if($clear_mode == false) echo '<div class="content'.($gkPage ? ' gkPage' : '').'">';
		
		echo $module->content;
		
		if($clear_mode == false) echo '</div>';
		
		if($clear_mode == false) echo '</div>';
		
		echo '</div>';
 	}
}

// EOF