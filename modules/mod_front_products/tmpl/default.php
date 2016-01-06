<?php

defined('_JEXEC') or die;

?>

<style>
    .box.quark-product-showcase .addtocart-button {
        font-weight: 100;
        line-height: 46px;
    }

    #gkBottom2 .box.quark-product-showcase {
        padding: 0;
    }

    .clear-both:nth-child(odd) {
        clear: both;
    }
</style>

<div class="box quark-product-showcase gkmod-1"><div class="box-wrap"><div class="content gkPage">	<div class="nspMain big-spaces quark-product-showcase activated" id="nsp-nsp-877" data-config="{
				'animation_speed': 400,
				'animation_interval': 5000,
				'animation_function': 'Fx.Transitions.Expo.easeIn',
				'news_column': 2,
				'news_rows': 3,
				'links_columns_amount': 1,
				'links_amount': 3
			}">
                <div class="" style="width:100%;">
                    <div class="nspArtScroll1">
                        <div class="nspArtScroll2 nspPages1">
                            <div class="nspArtPage active nspCol1">

                                <?php foreach ( $products as $product) :
                                    $url = JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
                                        $product->virtuemart_category_id);
                                    if (isset($product->images) && !empty($product->images)) {
                                        $img = $product->images[0]->getUrl();
                                    } else {
                                        $img = '';
                                    }


                                ?>

                                <div class="clear-both nspArt nspCol2 <?php if($product->product_special) echo "nspFeatured"  ?>" style="padding:65px;">
                                    <div class="center tcenter">
                                        <a href="<?php echo $url; ?>" class="nspImageWrapper tcenter" style="margin:0 0 20px 0;" target="_self">
                                            <img class="nspImage" src="<?php echo $img; ?>" alt="<?php echo $product->product_name; ?>" style=" width : auto; max-height:320px; margin: 0 auto">
                                        </a>
                                    </div>
                                    <h4 class="nspHeader tcenter fnone has-image"><a href="<?php echo $url; ?>" title="<?php echo $product->product_name; ?>" target="_self"><?php echo $product->product_name; ?></a></h4>
                                    <div class="nspInfo nspInfo1 tcenter fnone">
                                        <div><span><strong></strong></span><div class="addtocart-area">
                                                <a href="<?php echo $url; ?>" class="addtocart-button"><?php echo JText::_("MOD_FRONT_PRODUCTS_LINK_TEXT"); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<!--<div style="width: 100%; margin-bottom: 20px">-->

<!--    <p class="text-map-top bold popup" style="text-decoration: underline; cursor: pointer"><a class="modal modal_link cboxElement" href="/index.php/rsform?ml=1&amp;iframe=1" data-modal-iframe="true" data-modal-inner-width="500" data-modal-inner-height="470" data-modal-class-name="no_title">Enquire here</a></p>-->

<!--</div>-->
