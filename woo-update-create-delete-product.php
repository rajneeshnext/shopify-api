<?php 
/* Template Name: Shopify Woo Redmart */
$payload = @file_get_contents('php://input');
if($payload!=""){
    $fh = fopen(dirname(__FILE__).'/webhook_response.txt','w');
    fwrite($fh, $payload);
    fclose($fh);
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$json = '{"admin_graphql_api_id":"gid:\/\/shopify\/Product\/788032119674292922","body_html":"An example T-Shirt","created_at":null,"handle":"example-t-shirt","id":788032119674292922,"product_type":"Shirts","published_at":"2024-09-14T13:48:12+08:00","template_suffix":null,"title":"Example T-Shirt","updated_at":"2024-09-14T13:48:12+08:00","vendor":"Acme","status":"active","published_scope":"web","tags":"example, mens, t-shirt","variants":[{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/642667041472713922","barcode":null,"compare_at_price":"24.99","created_at":"2024-09-12T13:48:12+08:00","id":6426670414727113922,"inventory_policy":"deny","position":1,"price":"18.99","product_id":788032119674292922,"sku":null,"taxable":true,"title":"Small","updated_at":"2024-09-13T13:48:12+08:00","option1":"Small","option2":null,"option3":null,"image_id":null,"inventory_item_id":null,"inventory_quantity":75,"old_inventory_quantity":75},{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/757650484644203962","barcode":null,"compare_at_price":"24.99","created_at":"2024-09-12T13:48:12+08:00","id":7576501484644203962,"inventory_policy":"deny","position":2,"price":"19.99","product_id":788032119674292922,"sku":null,"taxable":true,"title":"Medium","updated_at":"2024-09-14T13:48:12+08:00","option1":"Medium","option2":null,"option3":null,"image_id":null,"inventory_item_id":null,"inventory_quantity":50,"old_inventory_quantity":50}],"options":[],"images":[],"image":null,"media":[],"variant_gids":[{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/757650484644203962","updated_at":"2024-09-14T05:48:12.000Z"},{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/642667041472713922","updated_at":"2024-09-13T05:48:12.000Z"}],"has_variants_that_requires_components":false,"category":null}';


$data_product = json_decode($payload, true);
function pricode_create_product($data_product){	
	//echo "<pre>";
	//print_r($data_product);
	$desc = $data_product['body_html'];
	$title = $data_product['title'];
    $product = new WC_Product_Variable();
    $product->set_description("$desc");
    $product->set_name($title);
    $product->set_sku($data_product['id']);
    $product->set_price(1);
    $product->set_regular_price(1);
    $product->set_stock_status();


    foreach($data_product['variants'] as $variant){
		$size_array[]  = $variant['option1'];
	}
	$atts = [];
	if($size_array){
		$atts[] = pricode_create_attributes('size', $size_array);
		$product->set_attributes( $atts );
	}
	$product->save();
	wp_set_object_terms($product->get_id(), array($data_product['product_type']), 'product_cat');
	if($data_product['tags'] !=""){
		$tags = explode(',', $data_product['tags']);
		wp_set_object_terms($product->get_id(), $tags, 'product_tag');
	}
	
    
	foreach($data_product['variants'] as $variant){
		$data = new stdClass();
		$data->sku = $variant['id'];
		$data->sale_price = $variant['price'];
		$data->regular_price = $variant['compare_at_price'];
		$data->inventory_quantity = $variant['inventory_quantity'];		
		pricode_create_variations( $product->get_id(), ['size' => $variant['option1']], $data );
	}
	echo "Product Added";
    return $product;
}

/**
 * Create Product Attributes 
 * @param  string $name    Attribute name
 * @param  array $options Options values
 * @return Object          WC_Product_Attribute 
 */
function pricode_create_attributes( $name, $options ){
    $attribute = new WC_Product_Attribute();
    $attribute->set_id(0);
    $attribute->set_name($name);
    $attribute->set_options($options);
    $attribute->set_visible(true);
    $attribute->set_variation(true);
    return $attribute;
}

/**
 * [pricode_create_variations description]
 * @param  [type] $product_id [description]
 * @param  [type] $values     [description]
 * @return [type]             [description]
 */
function pricode_create_variations( $product_id, $values, $data ){

    $variation = new WC_Product_Variation();
    $variation->set_parent_id( $product_id );
    $variation->set_attributes($values);
    $variation->set_status('publish');
    $variation->set_sku($data->sku);
    $variation->set_sale_price($data->sale_price);
    $variation->set_regular_price($data->regular_price);
    if( ! empty($data->inventory_quantity) ){
        $variation->set_stock_quantity( $data->inventory_quantity );
        $variation->set_manage_stock(true);
        $variation->set_stock_status('');
    } else {
        $variation->set_manage_stock(false);
    }
    $variation->save();
    $product = wc_get_product($product_id);
    $product->save();

}
function pricode_delete_product($data_product){	
	$product_id = wc_get_product_id_by_sku( $data_product['id'] );
	wp_delete_post( $product_id );
	echo $product_id." Product deleted";
}	
//Adding product
$product = pricode_create_product($data_product);
//$product = pricode_delete_product($data_product);
?>
