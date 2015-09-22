<?php

//
// Bootstrap LESS parser
//

include_once('lessparser.php');

class GKTemplateLESS {
	function __construct($parent, $force='false') {
		if($parent->API->get('recompile_css', 0) == 1) {
			$tpl_path = $parent->API->URLtemplatepath();
			
			$override_suffix = '';
			
			if($parent->API->get('custom_override', '-1') != '-1') {
				$override_suffix = '.' . $parent->API->get('custom_override', '-1');
			}
			
			$style_suffix = '';
			
			if($parent->API->get('template_style', 'main.less') != 'main.less') {
				$style_suffix = '.' . str_replace('.main.less', '', $parent->API->get('template_style', 'main.less'));
			}
			
			$style_dir = str_replace('.', '', $style_suffix);
			
			if($style_dir != '') {
				$style_dir .= DS; 
			}
			
			$files = array(
				'main' 			=> array(
					'css' => 'template' . $style_suffix . $override_suffix, 
					'less' => $tpl_path . DS . 'less' . DS . $parent->API->get('template_style', 'main.less')
				),
				'small.desktop' => array(
					'css' => 'small.desktop' . $override_suffix, 
					'less' => $tpl_path . DS . 'less' . DS . $style_dir . 'small.desktop.less'
				),
				'tablet' 		=> array(
					'css' => 'tablet' . $override_suffix, 
					'less' => $tpl_path . DS . 'less' . DS . $style_dir . 'tablet.less'
				),
				'small.tablet' 	=> array(
					'css' => 'small.tablet' . $override_suffix, 
					'less' => $tpl_path . DS . 'less' . DS . $style_dir . 'small.tablet.less'
				),
				'mobile' 		=> array(
					'css' => 'mobile' . $override_suffix, 
					'less' => $tpl_path . DS . 'less' . DS . $style_dir . 'mobile.less'
				),
				'print' 		=> array(
					'css' => 'print' . $override_suffix, 
					'less' => $tpl_path . DS . 'less' . DS . 'print.less'
				),
				'mailto' 		=> array(
					'css' => 'mailto' . $override_suffix, 
					'less' => $tpl_path . DS . 'less' . DS . 'mailto.less'
				),
				'offline' 		=> array(
					'css' => 'offline' . $style_suffix . $override_suffix, 
					'less' => $tpl_path . DS . 'less' . DS . 'offline.less'
				),
				'override' 		=> array(
					'css' => 'override' . $override_suffix, 
					'less' => $tpl_path . DS . 'less' . DS . 'override.less'
				)
			);
			
			if($parent->API->get('custom_override', '-1') !== '-1') {
				$override_path = JPATH_SITE . DS . 'templates' . DS . 'gk_overrides' . DS . $parent->API->get('custom_override', '-1') . DS . 'less' . DS;
				
				foreach($files as $file_name => $file_data) {
					if(JFile::exists($override_path . $file_name . '.less')) {
						$files[$file_name]['less'] = $override_path . $file_name . '.less';
					}
				}
			}
			
			// remove old Template CSS files
			foreach($files as $file) {
				JFile::delete($tpl_path . DS . 'css' . DS . $file['css'] . '.css');	
			}

			// generate new Template CSS files
			try {
				// normal Template code
				lessc::ccompile(
							    	$files['main']['less'], 
							    	$tpl_path . DS . 'css' . DS . $files['main']['css'] . '.css'
							    );
							    
				lessc::ccompile(
							    	$files['small.desktop']['less'], 
							    	$tpl_path . DS . 'css' . DS . $files['small.desktop']['css'] . '.css'
							    );
							    
				lessc::ccompile(
							    	$files['tablet']['less'], 
							    	$tpl_path . DS . 'css' . DS . $files['tablet']['css'] . '.css'
							    );
							    
				lessc::ccompile(
							    	$files['small.tablet']['less'], 
							    	$tpl_path . DS . 'css' . DS . $files['small.tablet']['css'] . '.css'
							    );			    
							    
				lessc::ccompile(
							    	$files['mobile']['less'], 
							    	$tpl_path . DS . 'css' . DS . $files['mobile']['css'] . '.css'
							    );
				
				lessc::ccompile(
							    	$files['print']['less'], 
							    	$tpl_path . DS . 'css' . DS . $files['print']['css'] . '.css'
							    );
							    
				lessc::ccompile(
							    	$files['mailto']['less'], 
							    	$tpl_path . DS . 'css' . DS . $files['mailto']['css'] . '.css'
							    );
					
				// additional Template code				
				lessc::ccompile(
							    	$files['offline']['less'], 
							    	$tpl_path . DS . 'css' . DS . $files['offline']['css'] . '.css'
							    );
							    
				lessc::ccompile(
							    	$files['override']['less'], 
							    	$tpl_path . DS . 'css' . DS . $files['override']['css'] . '.css'
							    );
			    
			    return true;
			} catch (exception $ex) {
			    exit('LESS Parser fatal error:<br />'.$ex->getMessage());
			}
		}
	}
}
// EOF