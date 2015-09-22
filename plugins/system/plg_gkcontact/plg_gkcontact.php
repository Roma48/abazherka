<?php

/**
* GK Contact plugin
* @Copyright (C) 2013 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0 $
**/

defined( '_JEXEC' ) or die();
jimport( 'joomla.plugin.plugin' );

class plgSystemPlg_GKContact extends JPlugin {
	
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage('plg_system_gkcontact');
	}
	
	// Prepare the form
	function onAfterRender() {
		$app = JFactory::getApplication();
		
		// Load the language
		$this->loadLanguage('plg_system_gkcontact');
		
		// check if this is a front-end
		if ($app->getName() != 'site') {
			return true;
		}		
		// get the output buffer
		$buffer = JResponse::getBody();
		// prepare an output
		$output = '';
		
		if($this->params->get('use_recaptcha') == 1 && stripos($buffer, '{GKCONTACT}')) {
			$app = JFactory::getApplication();
			$lang   = $this->_getLanguage();
			$pubkey = $this->params->get('public_key', '');	
			$server = 'https://www.google.com/recaptcha/api';
	
			$output .= '<script src="'. $server . '/js/recaptcha_ajax.js"></script>';
			$output .= '<script>jQuery(document).ready(function() {Recaptcha.create("' . $pubkey . '", "dynamic_recaptcha_1", {theme: "clean",' . $lang . 'tabindex: 0});});</script>';
		}
		// output the main wrapper
		$output .= '<div class="gkContactForm">';			
		//
		// output the form
		//
		// get the current page URL
		$cur_url = ((!empty($_SERVER['HTTPS'])) && ($_SERVER['HTTPS']!='off')) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$cur_url = preg_replace('@%[0-9A-Fa-f]{1,2}@mi', '', htmlspecialchars($cur_url, ENT_QUOTES, 'UTF-8'));
		//
		$output .= '<form action="'.$cur_url.'" method="post">';
			if(
				$this->params->get('name_field') == 1 ||
				$this->params->get('email_field') == 1 ||
				$this->params->get('title_field') == 1
			) {
				$output .= '<p class="gkcontact-fields">';
			} 
			
			if($this->params->get('email_field') == 1) {
				$output .= '<input type="email" class="required" placeholder="'.JText::_('PLG_GKCONTACT_EMAIL_PLACEHOLDER').'" name="gkcontact-email" />';
			}
			
			if($this->params->get('name_field') == 1) {
				$output .= '<input type="text" class="required" placeholder="'.JText::_('PLG_GKCONTACT_NAME_PLACEHOLDER').'" name="gkcontact-name" />';
			}
			
			if($this->params->get('title_field') == 1) {
				$output .= '<input type="text" class="required" placeholder="'.JText::_('PLG_GKCONTACT_TITLE_PLACEHOLDER').'" name="gkcontact-title" />';
			}
			
			if(
				$this->params->get('name_field') == 1 ||
				$this->params->get('email_field') == 1 ||
				$this->params->get('title_field') == 1
			) {
				$output .= '</p>';
			} 
			
			$output .= '<p class="gkcontact-textarea"><textarea class="required" name="gkcontact-textarea" placeholder="'.JText::_('PLG_GKCONTACT_TEXT_PLACEHOLDER').'"></textarea></p>';
				
			if($this->params->get('use_recaptcha') == 1) {
				$output .= '<div id="dynamic_recaptcha_1"></div>';
			}	
			
			$output .= '<p><input type="submit" value="'.JText::_('PLG_GKCONTACT_SEND_BTN').'" class="submit button-border" /></p>';
			$output .= '<input type="hidden" value="'.$cur_url.'" name="return" />';
			$output .= JHtml::_( 'form.token' );
		$output .= '</form>';
		// close the main wrapper
		$output .= '</div>';
		// replace the {GKCONTACT} string with the generated output
		$buffer = str_replace('{GKCONTACT}', $output, $buffer);
		// save the changes in the buffer
		JResponse::setBody($buffer);
		
		return true;
	}
	// Prepare the form
	function onAfterInitialise() {	
		$post = JRequest::get('post');   
		
		if(isset($post['gkcontact-textarea'])) {   
			$app = JFactory::getApplication();
			// if reCaptcha is enabled
			if($this->params->get('use_recaptcha') == 1) {
				JPluginHelper::importPlugin('captcha');
				$dispatcher = JDispatcher::getInstance();
				$res = $dispatcher->trigger('onCheckAnswer', $post['recaptcha_response_field']);
				
				if(!$res[0]){
				    $app->redirect($post['return'],JText::_('PLG_GKCONTACT_RECAPTCHA_ERROR'),"error");
				}
			}
			// check the token
			JSession::checkToken() or die( 'Invalid Token' );
			// if the reCaptcha and token are correct - check the mail data:
			
			// get the mailing api
			$mailer = JFactory::getMailer();
			
			// set the sender
			$config = JFactory::getConfig();
			$sender = array( 
			    $config->getValue('config.mailfrom'),
			    $config->getValue('config.fromname') 
			);
			 
			$mailer->setSender($sender);
			
			// set the recipient
			if(trim($this->params->get('emails')) != '') {
				$mailer->addRecipient(explode(',', $this->params->get('emails')));
				
				// use XSS filters
				$filter = JFilterInput::getInstance(); 
				
				// NAME
				$name = '';
				
				if($this->params->get('name_field') == 1) {
					$name = $filter->clean($post['gkcontact-name']); 
				}
				// EMAIL
				$email = '';
				
				if($this->params->get('email_field') == 1) {
					$email = $filter->clean($post['gkcontact-email']); 
				}
				// TITLE
				$title = '';
				
				if($this->params->get('title_field') == 1) {
					$title = $filter->clean($post['gkcontact-title']);	
				} else {
					$title = JText::_('PLG_GKCONTACT_STANDARD_SUBJECT') . $config->getValue('config.sitename');
				}
				
				$text = trim($filter->clean($post['gkcontact-textarea']));
				
				if(
					$text != '' && 
					($this->params->get('name_field') == 0 || ($this->params->get('name_field') == 1 && $name != '')) &&
					($this->params->get('email_field') == 0 || ($this->params->get('email_field') == 1 && $email != '')) &&
					($this->params->get('title_field') == 0 || ($this->params->get('title_field') == 1 && $title != ''))
				) {
					// set the message body
					$body = "<html>";
					$body .= "<body>";
					$body .= "<h1 style=\"font-size: 24px; border-bottom: 4px solid #EEE; margin: 10px 0; padding: 10px 0; font-weight: normal; font-style: italic;\">".$title."</h1>";
			
					if($this->params->get('name_field') == 1) {
						$body .= "<div>";
						$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".JText::_('PLG_GKCONTACT_NAME_LABEL')."</h2>";
						$body .= "<p>".$name."</p>";
						$body .= "</div>";
					}
			
					if($this->params->get('email_field') == 1) {
						$body .= "<div>";
						$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".JText::_('PLG_GKCONTACT_EMAIL_LABEL')."</h2>";
						$body .= "<p>".$email."</p>";
						$body .= "</div>";
					}
			
					$body .= "<div>";
					$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".JText::_('PLG_GKCONTACT_TEXT_LABEL')."</h2>";
					$body .= $text;
					$body .= "</div>";
					$body .= "</body>";
					$body .= "</html>";
					
					$mailer->isHTML(true);
					$mailer->Encoding = 'base64';
					$mailer->setBody($body);
					
					if(trim($this->params->get('title')) == '') {
						$mailer->setSubject(JText::_('PLG_GKCONTACT_STANDARD_SUBJECT') . $config->getValue('config.sitename'));
					} else {
						$mailer->setSubject($this->params->get('title'));
					}
					// sending and redirecting
					$send = $mailer->Send();
					//
					if ( $send !== true ) {
						$app->redirect($post['return'], JText::_('PLG_GKCONTACT_MESSAGE_SENT_ERROR') . $send->message, "error");
					} else {
					    $app->redirect($post['return'], JText::_('PLG_GKCONTACT_MESSAGE_SENT_INFO'), "information");
					}
				} else {
					$app->redirect($post['return'], JText::_('PLG_GKCONTACT_MESSAGE_EMPTY_ERROR'), "error");
				}
			} else {
				 $app->redirect($post['return'], JText::_('PLG_GKCONTACT_NO_RECIPENT_INFO'), "error");
			}
		}
	}
	
	private function _getLanguage() {
		$language = JFactory::getLanguage();

		$tag = explode('-', $language->getTag());
		$tag = $tag[0];
		$available = array('en', 'pt', 'fr', 'de', 'nl', 'ru', 'es', 'tr');

		if (in_array($tag, $available))
		{
			return "lang : '" . $tag . "',";
		}

		// If nothing helps fall back to english
		return '';
	}
}

/* EOF */