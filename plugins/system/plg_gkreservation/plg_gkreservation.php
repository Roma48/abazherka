<?php

/**
* GK Reservation plugin
* @Copyright (C) 2014 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0 $
**/

defined( '_JEXEC' ) or die();
jimport( 'joomla.plugin.plugin' );

class plgSystemPlg_GKReservation extends JPlugin {
	
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage('plg_system_gkreservation');
	}
	
	// Prepare the form
	function onAfterRender() {
		$app = JFactory::getApplication();
		
		// Load the language
		$this->loadLanguage('plg_system_gkreservation');
		
		// check if this is a front-end
		if ($app->getName() != 'site') {
			return true;
		}		
		// get the output buffer
		$buffer = JResponse::getBody();
		// prepare an output
		$output = '';
		
		if($this->params->get('use_recaptcha') == 1 && stripos($buffer, '{GKRESERVATION}')) {
			$app = JFactory::getApplication();
			$lang   = $this->_getLanguage();
			$pubkey = $this->params->get('public_key', '');	
			$server = 'https://www.google.com/recaptcha/api';
	
			$output .= '<script src="'. $server . '/js/recaptcha_ajax.js"></script>';
			$output .= '<script>jQuery(document).ready(function() {Recaptcha.create("' . $pubkey . '", "dynamic_recaptcha_1", {theme: "clean",' . $lang . 'tabindex: 0});});</script>';
		}
		// output the main wrapper
		$output .= '<div class="gkReservationForm">';			
		//
		// output the form
		//
		// get the current page URL
		$cur_url = ((!empty($_SERVER['HTTPS'])) && ($_SERVER['HTTPS']!='off')) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$cur_url = preg_replace('@%[0-9A-Fa-f]{1,2}@mi', '', htmlspecialchars($cur_url, ENT_QUOTES, 'UTF-8'));
		//
		$output .= '<form action="'.$cur_url.'" method="post">';
			if(
				$this->params->get('date_field') == 1 ||
				$this->params->get('time_field') == 1 ||
				$this->params->get('size_field') == 1
			) {
				$output .= '<div class="gkreservation-party-details">';
			} 
			
			if($this->params->get('date_field') == 1) {
				$output .= '<div class="gkreservation-date"><input type="text" class="auto-kal required" placeholder="'.JText::_('PLG_GKRESERVATION_DATE_PLACEHOLDER').'" name="gkreservation-date" /></div>';
			}
			
			if($this->params->get('time_field') == 1) {
				$output .= '<div class="gkreservation-time"><input type="text" class="required" placeholder="'.JText::_('PLG_GKRESERVATION_TIME_PLACEHOLDER').'" name="gkreservation-time" /></div>';
			}
			
			if($this->params->get('size_field') == 1) {
				$output .= '<input type="text" class="required" placeholder="'.JText::_('PLG_GKRESERVATION_SIZE_PLACEHOLDER').'" name="gkreservation-size" />';
			}
			
			if(
				$this->params->get('date_field') == 1 ||
				$this->params->get('time_field') == 1 ||
				$this->params->get('size_field') == 1
			) {
				$output .= '</div>';
			} 
			

			$output .= '<div class="gkreservation-party-info">';
			
			if($this->params->get('name_field') == 1) {
				$output .= '<input type="text" class="required" placeholder="'.JText::_('PLG_GKRESERVATION_NAME_PLACEHOLDER').'" name="gkreservation-name" />';
			}
			
			if($this->params->get('email_field') == 1) {
				$output .= '<input type="email" class="required" placeholder="'.JText::_('PLG_GKRESERVATION_EMAIL_PLACEHOLDER').'" name="gkreservation-email" />';
			}
			
			if($this->params->get('phone_field') == 1) {
				$output .= '<input type="text" class="required" placeholder="'.JText::_('PLG_GKRESERVATION_PHONE_PLACEHOLDER').'" name="gkreservation-phone" />';
			}
			
			if($this->params->get('text_field') == 1) {
				$output .= '<textarea name="gkreservation-text" class="required" placeholder="'.JText::_('PLG_GKRESERVATION_TEXT_PLACEHOLDER').'"></textarea>';
			}
		
			if($this->params->get('additional_text') != '') {
				$output .= '<small>'.$this->params->get('additional_text').'</small>';
			}	
				
			if($this->params->get('use_recaptcha') == 1) {
				$output .= '<div id="dynamic_recaptcha_1"></div>';
			}
			
			$output .= '<p><input type="submit" value="'.JText::_('PLG_GKRESERVATION_SEND_BTN').'" class="submit button-border" /></p>';

			$output .= '</div>';
			
	
			$output .= '<input type="hidden" value="'.$cur_url.'" name="return" />';
			$output .= JHtml::_( 'form.token' );
		$output .= '</form>';
		// close the main wrapper
		$output .= '</div>';
		// replace the {GKRESERVATION} string with the generated output
		$buffer = str_replace('{GKRESERVATION}', $output, $buffer);
		// save the changes in the buffer
		JResponse::setBody($buffer);
		
		return true;
	}
	// Prepare the form
	function onAfterInitialise() {	
		$post = JRequest::get('post');   
		
		if(isset($post['gkreservation-text'])) {   
			$app = JFactory::getApplication();
			// if reCaptcha is enabled
			if($this->params->get('use_recaptcha') == 1) {
				JPluginHelper::importPlugin('captcha');
				$dispatcher = JDispatcher::getInstance();
				$res = $dispatcher->trigger('onCheckAnswer', $post['recaptcha_response_field']);
				
				if(!$res[0]){
				    $app->redirect($post['return'],JText::_('PLG_GKRESERVATION_RECAPTCHA_ERROR'),"error");
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
				
				// Fields
				$name = $this->params->get('name_field') == 1 ? $filter->clean($post['gkreservation-name']) : '';
				$email = $this->params->get('email_field') == 1 ? $filter->clean($post['gkreservation-email']) : '';
				$phone = $this->params->get('phone_field') == 1 ? $filter->clean($post['gkreservation-phone']) : '';	
				$date = $this->params->get('date_field') == 1 ? $filter->clean($post['gkreservation-date']) : '';
				$time = $this->params->get('time_field') == 1 ? $filter->clean($post['gkreservation-time']) : '';
				$size = $this->params->get('size_field') == 1 ? $filter->clean($post['gkreservation-size']) : '';
				$text = trim($filter->clean($post['gkreservation-text']));
				$title = JText::_('PLG_GKRESERVATION_STANDARD_SUBJECT') . $config->getValue('config.sitename');
				
				if(
					($this->params->get('name_field') == 0 || ($this->params->get('name_field') == 1 && $name != '')) &&
					($this->params->get('email_field') == 0 || ($this->params->get('email_field') == 1 && $email != '')) &&
					($this->params->get('phone_field') == 0 || ($this->params->get('phone_field') == 1 && $phone != '')) &&
					($this->params->get('date_field') == 0 || ($this->params->get('date_field') == 1 && $date != '')) &&
					($this->params->get('time_field') == 0 || ($this->params->get('time_field') == 1 && $time != '')) &&
					($this->params->get('size_field') == 0 || ($this->params->get('size_field') == 1 && $size != ''))
				) {
					// set the message body
					$body = "<html>";
					$body .= "<body>";
					$body .= "<h1 style=\"font-size: 24px; border-bottom: 4px solid #EEE; margin: 10px 0; padding: 10px 0; font-weight: normal; font-style: italic;\">".$title."</h1>";
			
					if($this->params->get('date_field') == 1) {
						$body .= "<div>";
						$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".JText::_('PLG_GKRESERVATION_DATE_LABEL')."</h2>";
						$body .= "<p>".$date."</p>";
						$body .= "</div>";
					}
					
					if($this->params->get('time_field') == 1) {
						$body .= "<div>";
						$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".JText::_('PLG_GKRESERVATION_TIME_LABEL')."</h2>";
						$body .= "<p>".$time."</p>";
						$body .= "</div>";
					}
					
					if($this->params->get('size_field') == 1) {
						$body .= "<div>";
						$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".JText::_('PLG_GKRESERVATION_SIZE_LABEL')."</h2>";
						$body .= "<p>".$size."</p>";
						$body .= "</div>";
					}
			
					if($this->params->get('name_field') == 1) {
						$body .= "<div>";
						$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".JText::_('PLG_GKRESERVATION_NAME_LABEL')."</h2>";
						$body .= "<p>".$name."</p>";
						$body .= "</div>";
					}
			
					if($this->params->get('email_field') == 1) {
						$body .= "<div>";
						$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".JText::_('PLG_GKRESERVATION_EMAIL_LABEL')."</h2>";
						$body .= "<p>".$email."</p>";
						$body .= "</div>";
					}
					
					if($this->params->get('phone_field') == 1) {
						$body .= "<div>";
						$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".JText::_('PLG_GKRESERVATION_PHONE_LABEL')."</h2>";
						$body .= "<p>".$phone."</p>";
						$body .= "</div>";
					}
			
					$body .= "<div>";
					$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".JText::_('PLG_GKRESERVATION_TEXT_LABEL')."</h2>";
					$body .= $text;
					$body .= "</div>";
					$body .= "</body>";
					$body .= "</html>";
					
					$mailer->isHTML(true);
					$mailer->Encoding = 'base64';
					$mailer->setBody($body);
					
					if(trim($this->params->get('title')) == '') {
						$mailer->setSubject(JText::_('PLG_GKRESERVATION_STANDARD_SUBJECT') . $config->getValue('config.sitename'));
					} else {
						$mailer->setSubject($this->params->get('title'));
					}
					// sending and redirecting
					$send = $mailer->Send();
					//
					if ( $send !== true ) {
						$app->redirect($post['return'], JText::_('PLG_GKRESERVATION_MESSAGE_SENT_ERROR') . $send->message, "error");
					} else {
					    $app->redirect($post['return'], JText::_('PLG_GKRESERVATION_MESSAGE_SENT_INFO'), "information");
					}
				} else {
					$app->redirect($post['return'], JText::_('PLG_GKRESERVATION_MESSAGE_EMPTY_ERROR'), "error");
				}
			} else {
				 $app->redirect($post['return'], JText::_('PLG_GKRESERVATION_NO_RECIPENT_INFO'), "error");
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