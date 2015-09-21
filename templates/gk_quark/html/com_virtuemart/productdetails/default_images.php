<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen

 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_images.php 8508 2014-10-22 18:57:14Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

vmJsApi::addJScript( 'fancybox/jquery.fancybox-1.3.4.pack', false);
vmJsApi::css('jquery.fancybox-1.3.4');
$document = JFactory::getDocument ();
$imageJS = '
	jQuery(document).ready(function() {
		Virtuemart.updateImageEventListeners()
	});
	Virtuemart.updateImageEventListeners = function() {
		jQuery(".main-image > a").fancybox({
			"titlePosition" 	: "inside",
			"transitionIn"	:	"elastic",
			"transitionOut"	:	"elastic"
		});
		
		jQuery(".vm-additional-images-area a.product-image").click(function(e) {
			e.preventDefault();
			var main_link = jQuery(".main-image").children("a");
			var main_img = main_link.find("img");
			var main_desc = jQuery(".main-image .vm-img-desc");
			var alt = jQuery(this).find("img").attr("alt");
			var src = jQuery(this).attr("href");
			
			main_img.attr("alt", alt);
			main_img.attr("src", src);
			main_link.attr("href", src);
			main_link.attr("title", alt);
			main_desc.html(alt);
		}); 
	}
';

vmJsApi::addJScript('imagepopup',$imageJS);

if (!empty($this->product->images)) {
	$image = $this->product->images[0];
	?>
	
		<?php echo $image->displayMediaFull("",true,"rel='vm-additional-images'"); ?>
	
	<?php
}
?>
