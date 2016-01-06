<?php

defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/helper.php';


defined('DS') or define('DS', DIRECTORY_SEPARATOR);
if (!class_exists( 'VmConfig' )) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');

if (!class_exists('VmMediaHandler')) require(VMPATH_ADMIN.DS.'helpers'.DS.'mediahandler.php');
if (!class_exists('VmImage')) require(VMPATH_ADMIN.DS.'helpers'.DS.'image.php');
VmConfig::loadConfig();
VmConfig::loadJLang('mod_virtuemart_product', true);

$max_items = 		$params->get( 'max_items', 6 ); //maximum number of items to display
$layout = $params->get('layout','qk_quark');
$category_id = 		$params->get( 'virtuemart_category_id', 5 ); // Display products from this category only
$filter_category = 	(bool)$params->get( 'filter_category', 0 ); // Filter the category
$display_style = 	$params->get( 'display_style', "div" ); // Display Style
$products_per_row = $params->get( 'products_per_row', 1 ); // Display X products per Row
$show_price = 		(bool)$params->get( 'show_price', 0 ); // Display the Product Price?
$show_addtocart = 	(bool)$params->get( 'show_addtocart', 0 ); // Display the "Add-to-Cart" Link?
$headerText = 		$params->get( 'headerText', '' ); // Display a Header Text
$footerText = 		$params->get( 'footerText', ''); // Display a footerText
$Product_group = 	$params->get( 'product_group', ''); // Display a footerText

$mainframe = Jfactory::getApplication();
$virtuemart_currency_id = $mainframe->getUserStateFromRequest( "virtuemart_currency_id", 'virtuemart_currency_id',vRequest::getInt('virtuemart_currency_id',0) );


$productModel = VmModel::getModel('Product');


//$products = $productModel->getProductListing($Product_group, $max_items, $show_price, true, false,$filter_category, $category_id);
$products_all = $productModel->sortSearchListQuery(true, false, 'latest');
$products_all = $productModel->getProducts($products_all);
$productModel->addImages($products_all);

$products = array();

foreach ($products_all as $product){
    if ($product->virtuemart_category_id != 7 && count($products) < 6){
        $products[] = $product;
    }
}

require JModuleHelper::getLayoutPath('mod_front_products');