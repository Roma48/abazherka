<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Eugen Stranz, Max Galt
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8610 2014-12-02 18:53:19Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/* Let's see if we found the product */
if (empty($this->product)) {
	echo vmText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
	echo '<br /><br />  ' . $this->continue_link_html;
	return;
}

echo shopFunctionsF::renderVmSubLayout('askrecomjs',array('product'=>$this->product));

if(vRequest::getInt('print',false)){ ?>
<body onload="javascript:print();">
<?php } ?>
<div class="productdetails-view productdetails">
     <?php if (VmConfig::get('product_navigation', 1)) : ?>
     <div class="product-neighbours">
          <?php
			    if (!empty($this->product->neighbours ['previous'][0])) {
				$prev_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['previous'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE);
					echo JHtml::_('link', $prev_link, '&laquo; ' . $this->product->neighbours['previous'][0]
					['product_name'], array('rel'=>'prev', 'class' => 'previous-page','data-dynamic-update' => '1'));
			    }
			    if (!empty($this->product->neighbours ['next'][0])) {
				$next_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['next'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE);
					echo JHtml::_('link', $next_link, $this->product->neighbours['next'][0]['product_name'] . ' &raquo;', array('rel'=>'next','class' => 'next-page','data-dynamic-update' => '1'));
			    }
		    ?>
     </div>
     <?php // Back To Category Button
        if ($this->product->virtuemart_category_id) {
        	$catURL =  JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$this->product->virtuemart_category_id, FALSE);
        	$categoryName = $this->product->category_name ;
        } else {
        	$catURL =  JRoute::_('index.php?option=com_virtuemart');
        	$categoryName = vmText::_('COM_VIRTUEMART_SHOP_HOME') ;
        }
        ?>
     <div class="back-to-category"> <a href="<?php echo $catURL ?>" class="product-details" title="<?php echo $categoryName ?>"><?php echo vmText::sprintf('COM_VIRTUEMART_CATEGORY_BACK_TO',$categoryName) ?></a> </div>
     <?php endif; // Product Navigation END ?>
     <div class="productDetails">
          <h1><?php echo $this->product->product_name ?></h1>
          <?php echo $this->product->event->afterDisplayTitle ?>
          <div class="product-additional-info">
               <?php
             	    // Rating
             	    echo shopFunctionsF::renderVmSubLayout('rating',array('showRating'=>$this->showRating,'product'=>$this->product));
             	    // Manufacturer of the Product
             	    if (VmConfig::get('show_manufacturers', 1) && !empty($this->product->virtuemart_manufacturer_id)) {
             	        echo $this->loadTemplate('manufacturer');
             	    }
              ?>
               <?php if (VmConfig::get('show_emailfriend') || VmConfig::get('show_printicon') || VmConfig::get('pdf_icon')) : ?>
               <?php
             		    $link = 'index.php?tmpl=component&option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id;
             	
             			echo $this->linkIcon($link . '&format=pdf', 'COM_VIRTUEMART_PDF', 'pdf_button', 'pdf_icon', false);
             			echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon',false,true,false,'class="printModal"');
             			$MailLink = 'index.php?option=com_virtuemart&view=productdetails&task=recommend&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component';
             		    echo $this->linkIcon($MailLink, 'COM_VIRTUEMART_EMAIL', 'emailButton', 'show_emailfriend', false,true,false,'class="recommened-to-friend"');
             	    ?>
               <?php endif; // PDF - Print - Email Icon END ?>
               <?php echo $this->edit_link; ?> </div>
          <div class="main-image">
               <?php if($this->product->product_special == 1) : ?>
               <span class="gk-image-badge-yellow"><?php echo JText::_('TPL_GK_LANG_VM3_FEATURED'); ?></span>
               <?php endif; ?>
               
               <?php 
				echo $this->loadTemplate('images');
				
				$count_images = count ($this->product->images);
				if ($count_images > 1) {
					echo $this->loadTemplate('images_additional');
				}
				?>
          </div>
          <?php if (!empty($this->product->product_s_desc)) : ?>
          <div>
               <div class="product-description">
                    <?php
					// Product Description
					if (!empty($this->product->product_s_desc)) :
			   ?>
                    <p>
                         <?php echo nl2br($this->product->product_s_desc); ?>
                    </p>
                    <?php
		    		endif; // Product Description END
		
			$product = $this->product;
			$position = 'normal';
			$class = 'product-fields';
			
			if (!empty($product->customfieldsSorted[$position])) {
				?>
                    <?php foreach ($product->customfieldsSorted[$position] as $field) {
						if ( $field->is_hidden ) //OSP http://forum.virtuemart.net/index.php?topic=99320.0
						continue;
						?>
                    <dl class="product-field product-field-type-<?php echo $field->field_type ?>">
                         <?php if ($field->custom_title != $custom_title and $field->show_title) : ?>
                         <dt><?php echo vmText::_ ($field->custom_title) ?></dt>
                         <?php endif; ?>
                         <?php if (!empty($field->display)) : ?>
                         <dd class="product-field-display"><?php echo $field->display ?></dd>
                         <?php endif; ?>
                         <?php if (!empty($field->custom_desc)) : ?>
                         <dd class="product-field-desc"><?php echo vmText::_($field->custom_desc) ?></dd>
                         <?php endif; ?>
                    </dl>
                    <?php
						$custom_title = $field->custom_title;
					} ?>
                    <?php
			}
			?>
                    <?php  
				// Ask a question about this product
				if (VmConfig::get('ask_question', 0) == 1) :
					$askquestion_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component', FALSE);
					?>
                    <p>
                         <a class="ask-a-question" href="<?php echo $askquestion_url ?>" rel="nofollow" ><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></a>
                    </p>
                    <?php endif; ?>
               </div>
               <?php echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'ontop')); ?>
<!--               <h1>--><?php //echo $this->product->virtuemart_category_id; ?><!--</h1>-->

               <div class="vm-product-details-container">
                    <div class="spacer-buy-area">
                         <?php
							$product = $this->product;

							$addtoCartButton = '';
							if(!VmConfig::get('use_as_catalog', 0)){
								if($product->addToCartButton){
									$addtoCartButton = $product->addToCartButton;
								} else {
									$addtoCartButton = shopFunctionsF::getAddToCartButton ($product->orderable);
								}

							}
							$position = 'addtocart';
							if (isset($product->step_order_level))
								$step=$product->step_order_level;
							else
								$step=1;
							if($step==0)
								$step=1;
							$alert=JText::sprintf ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED', $step);

							$init = 1;
							if(isset($product->init)){
								$init = $product->init;
							}

							if(!empty($product->min_order_level) and $init<$product->min_order_level){
								$init = $product->min_order_level;
							}

							$step=1;
							if (!empty($product->step_order_level)){
								$step=$product->step_order_level;
								if(empty($product->min_order_level) and !isset($product->init)){
									$init = $step;
								}
							}

							$maxOrder= '';
							if (!empty($product->max_order_level)){
								$maxOrder = ' max="'.$product->max_order_level.'" ';
							}
						?>
                         <div class="addtocart-area">
                              <form method="post" class="product js-recalculate<?php if(empty($this->product->customfieldsSorted['addtocart'])) : ?> narrow-form<?php endif; ?>" action="<?php echo JRoute::_('index.php?option=com_virtuemart',false); ?>">
                                   <?php
                                   		echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$product,'position'=>'addtocart'));
                                   ?>
                                   <div class="product-price" id="productPrice<?php echo $this->product->virtuemart_product_id ?>">
                                        <?php
                           				if ($this->product->prices['salesPrice']<=0 and VmConfig::get ('askprice', 1) and isset($this->product->images[0]) and !$this->product->images[0]->file_is_downloadable) {
                           					$askquestion_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component', FALSE);
                           					?>
<!--                                        <a class="ask-a-question bold" href="--><?php //echo $askquestion_url ?><!--" rel="nofollow" >--><?php //echo vmText::_ ('COM_VIRTUEMART_PRODUCT_ASKPRICE') ?><!--</a>-->
                                        <?php
                           				} else {
//                           					echo $this->currency->createPriceDiv ('salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $this->product->prices);
                                             echo $this->currency->createPriceDiv ('salesPrice', 'ЦІНА: ', $this->product->prices);
//                           					if ($this->product->prices['discountedPriceWithoutTax'] != $this->product->prices['priceWithoutTax']) {
//                           						echo $this->currency->createPriceDiv ('discountedPriceWithoutTax', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX', $this->product->prices);
//                           					} else {
//                           						echo $this->currency->createPriceDiv ('priceWithoutTax', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX', $this->product->prices);
//                           					}

                           					echo $this->currency->createPriceDiv ('basePrice', 'COM_VIRTUEMART_PRODUCT_BASEPRICE', $this->product->prices);
                           					echo $this->currency->createPriceDiv ('basePriceVariant', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_VARIANT', $this->product->prices);
                           					echo $this->currency->createPriceDiv ('variantModification', 'COM_VIRTUEMART_PRODUCT_VARIANT_MOD', $this->product->prices);

                           					if (
                           						round($this->product->prices['basePriceWithTax'], $this->currency->_priceConfig['salesPrice'][1]) != $this->product->prices['salesPrice'] &&
                           						$this->product->prices['basePriceWithTax'] != 0
                           					) {
                           						echo '<span class="price-crossed" >' . $this->currency->createPriceDiv ('basePriceWithTax', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_WITHTAX', $this->product->prices) . "</span>";
                           					}

                           					if (round($this->product->prices['salesPriceWithDiscount'],$this->currency->_priceConfig['salesPrice'][1]) != $this->product->prices['salesPrice']) {
                           						echo $this->currency->createPriceDiv ('salesPriceWithDiscount', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITH_DISCOUNT', $this->product->prices);
                           					}

                           					if($this->product->prices['discountAmount'] != 0) {
                           						echo $this->currency->createPriceDiv ('discountAmount', 'COM_VIRTUEMART_PRODUCT_DISCOUNT_AMOUNT', $this->product->prices);
                           					}

//                           					if($this->product->prices['taxAmount'] != 0) {
//                           						echo $this->currency->createPriceDiv ('taxAmount', 'COM_VIRTUEMART_PRODUCT_TAX_AMOUNT', $this->product->prices);
//                           					}

//                           					$unitPriceDescription = vmText::sprintf ('COM_VIRTUEMART_PRODUCT_UNITPRICE', vmText::_('COM_VIRTUEMART_UNIT_SYMBOL_'.$this->product->product_unit));
//                           					echo $this->currency->createPriceDiv ('unitPrice', $unitPriceDescription, $this->product->prices);
                           				}
                           			?>
                                        <?php

               							if (is_array($this->productDisplayShipments)) {
               							    foreach ($this->productDisplayShipments as $productDisplayShipment) {
               									echo '<div class="gk-product-shipment">' . strip_tags($productDisplayShipment) . '</div>';
               							    }
               							}

               							if (is_array($this->productDisplayPayments)) {
               							    foreach ($this->productDisplayPayments as $productDisplayPayment) {
               									echo '<div class="gk-product-payment">' . strip_tags($productDisplayPayment) . '</div>';
               							    }
               							}

               							//In case you are not happy using everywhere the same price display fromat, just create your own layout
               							//in override /html/fields and use as first parameter the name of your file
               						?>
                                   </div>
                                   <?php if (!VmConfig::get('use_as_catalog', 0)) : ?>
                                   <div class="addtocart-bar" style="display: none !important;">
                                        <span class="addtocart-button"> <?php echo $addtoCartButton ?> </span>
                                        <noscript>
                                        <input type="hidden" name="task" value="add"/>
                                        </noscript>

                                        <?php
                                        	$templateSettings = JFactory::getApplication()->getTemplate(true)->params;
                                        	if($templateSettings->get('vm3_show_quantity', '0') == '1' ) :
                                        ?>
                                        <script type="text/javascript">
                                        function check(obj) {
                                        	// use the modulus operator '%' to see if there is a remainder
                                        	remainder=obj.value % <?php echo $step?>;
                                        	quantity=obj.value;
                                        	if (remainder  != 0) {
                                        		alert('<?php echo $alert?>!');
                                        		obj.value = quantity-remainder;
                                        		return false;
                                        	}

                                        	return true;
                                        }
                                        </script>
                                        <?php
                                        // Display the quantity box
                                        $stockhandle = VmConfig::get ('stockhandle', 'none');
                                        if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) { ?>
                                                    <a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="notify"><?php echo vmText::_ ('COM_VIRTUEMART_CART_NOTIFY') ?></a>
                                                    <?php
                                        } else {
                                        	$tmpPrice = (float) $product->prices['costPrice'];
                                        	if (!( VmConfig::get('askprice', true) and empty($tmpPrice) ) ) { ?>
                                                    <?php if ($product->orderable) : ?>
                                                    <label for="quantity<?php echo $product->virtuemart_product_id; ?>" class="quantity_box"><?php echo vmText::_ ('COM_VIRTUEMART_CART_QUANTITY'); ?>: </label>
                                                    <span class="quantity-box">
                                                    <input type="text" class="quantity-input js-recalculate" name="quantity[]"
                                        				   onblur="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
                                        				   onclick="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
                                        				   onchange="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
                                        				   onsubmit="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
                                        				   value="<?php echo $init; ?>" init="<?php echo $init; ?>" step="<?php echo $step; ?>" <?php echo $maxOrder; ?> />
                                                    </span> <span class="quantity-controls js-recalculate">
                                                    <input type="button" value="+" class="quantity-controls quantity-plus"/>
                                                    <input type="button" value="-" class="quantity-controls quantity-minus"/>
                                                    </span>
                                        <?php endif; ?>
                                        <?php
                                        	}
                                        } ?>
                                        <?php endif; ?>
                                   </div>
                                   <?php endif; ?>
                                   <input name="quantity" type="hidden" value="<?php echo $step ?>" />
                                   <input type="hidden" name="option" value="com_virtuemart"/>
                                   <input type="hidden" name="view" value="cart"/>
                                   <input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>"/>
                                   <input type="hidden" class="pname" value="<?php echo $product->product_name ?>"/>
                                   <?php
						$itemId=vRequest::getInt('Itemid',false);
						if($itemId){
							echo '<input type="hidden" name="Itemid" value="'.$itemId.'"/>';
						} ?>
                              </form>
                         </div>
                    </div>
                    <?php echo shopFunctionsF::renderVmSubLayout('stockhandle',array('product'=>$this->product)); ?>
                    <?php if ( VmConfig::get ('display_stock', 1) || $this->product->product_box) : ?>
                    <ul class="productDetailInfo" style="display: none !important;">
                         <?php if ( VmConfig::get ('display_stock', 1)) : ?>
                         <li> <b><?php echo JText::_('COM_VIRTUEMART_STOCK_LEVEL_DISPLAY_TITLE_TIP'); ?>:</b> <?php echo $this->product->product_in_stock; ?> </li>
                         <?php endif; ?>
                         <?php if ($this->product->product_box) : ?>
                         <li> <b><?php echo JText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX'); ?></b> <?php echo $this->product->product_box; ?> </li>
                         <?php endif; ?>
                    </ul>
                    <?php endif; ?>
               </div>
          </div>
          <?php endif; ?>

     </div>
     <p class="enquire-form"><a class="modal modal_link cboxElement" href="/component/rsform/form/3-product?ml=1&amp;iframe=1" data-modal-iframe="true" data-modal-inner-width="500" data-modal-inner-height="500" data-modal-class-name="no_title">ORDER</a></p>
     <?php echo $this->product->event->beforeDisplayContent; ?>
     <?php if(!empty($this->product->product_desc) || ($this->allowRating || $this->allowReview || $this->showRating || $this->showReview)) : ?>
     <ul id="product-tabs">  
          <li 
          	data-toggle="product-description" 
          	class="active"
          >
          	<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_DESC_TITLE') ?>
          </li>
          
          <?php if($this->allowRating || $this->allowReview || $this->showRating || $this->showReview) : ?>
          <li
	          data-toggle="customer-reviews"
          >
          	<?php echo vmText::_ ('COM_VIRTUEMART_REVIEWS') ?>
          </li>
          <?php endif; ?>
     </ul>
     <?php endif; ?>
     <?php if(!empty($this->product->product_desc) || ($this->allowRating || $this->allowReview || $this->showRating || $this->showReview)) : ?>
     <div id="product-tabs-content">
     <?php endif; ?>
          
          <?php if(!empty($this->product->product_desc)) : ?>
          <div class="product-description gk-product-tab"><?php echo $this->product->product_desc; ?></div>
          <?php else : ?>
          <div class="product-description gk-product-tab"><span class="step"><?php echo JText::_('TPL_GK_LANG_VM3_NO_DESC'); ?></span></div>
          <?php endif; ?>
          
          <?php if($this->allowRating || $this->allowReview || $this->showRating || $this->showReview) : ?>
          <div class="customer-reviews gk-product-tab"> <?php echo $this->loadTemplate('reviews'); ?> </div>
          <?php endif; ?>
          
     <?php if(!empty($this->product->product_desc) && ($this->allowRating || $this->allowReview || $this->showRating || $this->showReview)) : ?>
     </div>
     <?php endif; ?>
     
     <?php
		    // RELATED PRODUCTS
		    $product = $this->product;
		    $position = 'related_products';
		    $customTitle = true;
		    $class = 'product-related-products';
		    
		    if (!empty($product->customfieldsSorted[$position])) :
		    ?>
     <div class="<?php echo $class?>">
          <?php
		    	if($customTitle and isset($product->customfieldsSorted[$position][0])) :
		    		$field = $product->customfieldsSorted[$position][0]; ?>
          <h4><?php echo vmText::_ ($field->custom_title) ?></h4>
          <?php endif; ?>
          <?php foreach ($product->customfieldsSorted[$position] as $field) : ?>
          <?php if ( $field->is_hidden ) continue; //OSP http://forum.virtuemart.net/index.php?topic=99320.0 ?>
          <div class="product-field product-field-type-<?php echo $field->field_type ?>">
               <?php if (!empty($field->display)) : ?>
               <span class="product-field-display"><?php echo $field->display ?></span>
               <?php endif; ?>
          </div>
          <?php $custom_title = $field->custom_title; ?>
          <?php endforeach ?>
     </div>
     <?php endif ?>
     <?php
		    // RELATED CATEGORIES
		    $product = $this->product;
		    $position = 'related_categories';
		    $customTitle = true;
		    $class = 'product-related-categories';
		    
		    if (!empty($product->customfieldsSorted[$position])) :
			?>
     <div class="<?php echo $class?>">
          <?php
				if($customTitle and isset($product->customfieldsSorted[$position][0])) :
					$field = $product->customfieldsSorted[$position][0]; ?>
          <h4><?php echo vmText::_ ($field->custom_title) ?></h4>
          <?php endif; ?>
          <?php foreach ($product->customfieldsSorted[$position] as $field) : ?>
          <?php if ( $field->is_hidden ) continue; //OSP http://forum.virtuemart.net/index.php?topic=99320.0 ?>
          <div class="product-field product-field-type-<?php echo $field->field_type ?>">
               <?php if (!empty($field->display)) : ?>
               <span class="product-field-display"><?php echo $field->display ?></span>
               <?php endif; ?>
          </div>
          <?php $custom_title = $field->custom_title; ?>
          <?php endforeach ?>
     </div>
     <?php endif ?>
     <?php echo $this->product->event->afterDisplayContent; ?>
     <?php // Show child categories
    if (VmConfig::get('showCategory', 1)) {
		echo $this->loadTemplate('showcategory');
    }?>
     <?php
$j = 'jQuery(document).ready(function($) {
	Virtuemart.product(jQuery("form.product"));

	$("form.js-recalculate").each(function(){
		if ($(this).find(".product-fields").length && !$(this).find(".no-vm-bind").length) {
			var id= $(this).find(\'input[name="virtuemart_product_id[]"]\').val();
			Virtuemart.setproducttype($(this),id);

		}
	});
});';
//vmJsApi::addJScript('recalcReady',$j);

/** GALT
	 * Notice for Template Developers!
	 * Templates must set a Virtuemart.container variable as it takes part in
	 * dynamic content update.
	 * This variable points to a topmost element that holds other content.
	 */
$j = "Virtuemart.container = jQuery('.productdetails-view');
Virtuemart.containerSelector = '.productdetails-view';";

vmJsApi::addJScript('ajaxContent',$j);

$j = "jQuery(document).ready(function() {
    var tabs = jQuery('#product-tabs');
    // if tabs exists
    if(tabs.length && tabs.find('li').length > 1) {
        // initialization
        tabs.find('li').first().addClass('active');
        var contents = jQuery('#product-tabs-content');
        contents.children('div').css('display', 'none');
        contents.children('div').first().addClass('active');
        // add events to the tabs
        tabs.find('li').each(function(i, tab) {
        	var tab = jQuery(tab);
            tab.click(function() {
                var toggle = tab.attr('data-toggle');
                contents.children('div').removeClass('active');
                jQuery(contents.children().get(i)).addClass('active');
                tabs.find('li').removeClass('active');
                tab.addClass('active');                
            });
        });
    }
});";

vmJsApi::addJScript('gk-vm-addons',$j);

echo vmJsApi::writeJS();
?>
</div>
<!-- .productDetails -->
<?php

// EOF

