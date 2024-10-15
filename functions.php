<?php
/**
 * DrFuri Core functions and definitions
 *
 * @package Martfury
 */
include dirname(__FILE__) . '/php/LazopSdk.php'; 
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since  1.0
 *
 * @return void
 */
function martfury_setup() {
	// Sets the content width in pixels, based on the theme's design and stylesheet.
	$GLOBALS['content_width'] = apply_filters( 'martfury_content_width', 840 );

	// Make theme available for translation.
	load_theme_textdomain( 'martfury', get_template_directory() . '/lang' );

	// Theme supports
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-formats', array( 'audio', 'gallery', 'video', 'quote', 'link' ) );
	add_theme_support(
		'html5', array(
			'comment-list',
			'search-form',
			'comment-form',
			'gallery',
		)
	);

	if ( class_exists( 'WooCommerce' ) ) {
		add_theme_support( 'woocommerce', array(
			'wishlist' => array(
				'single_button_position' => 'theme',
				'loop_button_position'   => 'theme',
				'button_type'            => 'theme',
			),
		) );
	}

	if ( martfury_fonts_url() ) {
		add_editor_style( array( 'css/editor-style.css', martfury_fonts_url() ) );
	} else {
		add_editor_style( 'css/editor-style.css' );
	}

	// Load regular editor styles into the new block-based editor.
	add_theme_support( 'editor-styles' );

	// Load default block styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	add_theme_support( 'align-wide' );

	add_theme_support( 'align-full' );

	// Register theme nav menu
	$nav_menu = array(
		'primary'         => esc_html__( 'Primary Menu', 'martfury' ),
		'shop_department' => esc_html__( 'Shop By Department Menu', 'martfury' ),
		'mobile'          => esc_html__( 'Mobile Header Menu', 'martfury' ),
		'category_mobile' => esc_html__( 'Mobile Category Menu', 'martfury' ),
		'user_logged'     => esc_html__( 'User Logged Menu', 'martfury' ),
	);
	if ( martfury_has_vendor() ) {
		$nav_menu['vendor_logged'] = esc_html__( 'Vendor Logged Menu', 'martfury' );
	}
	register_nav_menus( $nav_menu );

	add_image_size( 'martfury-blog-grid', 380, 300, true );
	add_image_size( 'martfury-blog-list', 790, 510, true );
	add_image_size( 'martfury-blog-masonry', 370, 588, false );

	global $martfury_woocommerce;
	$martfury_woocommerce = new Martfury_WooCommerce;

	global $martfury_mobile;
	$martfury_mobile = new Martfury_Mobile;

	\Martfury\Modules::instance();

}

add_action( 'after_setup_theme', 'martfury_setup', 100 );

/**
 * Register widgetized area and update sidebar with default widgets.
 *
 * @since 1.0
 *
 * @return void
 */
function martfury_register_sidebar() {
	// Register primary sidebar
	$sidebars = array(
		'blog-sidebar'    => esc_html__( 'Blog Sidebar', 'martfury' ),
		'topbar-left'     => esc_html__( 'Topbar Left', 'martfury' ),
		'topbar-right'    => esc_html__( 'Topbar Right', 'martfury' ),
		'topbar-mobile'   => esc_html__( 'Topbar on Mobile', 'martfury' ),
		'header-bar'      => esc_html__( 'Header Bar', 'martfury' ),
		'post-sidebar'    => esc_html__( 'Single Post Sidebar', 'martfury' ),
		'page-sidebar'    => esc_html__( 'Page Sidebar', 'martfury' ),
		'catalog-sidebar' => esc_html__( 'Catalog Sidebar', 'martfury' ),
		'product-sidebar' => esc_html__( 'Single Product Sidebar', 'martfury' ),
		'footer-links'    => esc_html__( 'Footer Links', 'martfury' ),
	);

	if ( class_exists( 'WC_Vendors' ) || class_exists( 'MVX' ) ) {
		$sidebars['vendor_sidebar'] = esc_html( 'Vendor Sidebar', 'martfury' );
	}

	// Register footer sidebars
	for ( $i = 1; $i <= 6; $i ++ ) {
		$sidebars["footer-sidebar-$i"] = esc_html__( 'Footer', 'martfury' ) . " $i";
	}

	$custom_sidebar = martfury_get_option( 'custom_product_cat_sidebars' );
	if ( $custom_sidebar ) {
		foreach ( $custom_sidebar as $sidebar ) {
			if ( ! isset( $sidebar['title'] ) || empty( $sidebar['title'] ) ) {
				continue;
			}
			$title                                = $sidebar['title'];
			$sidebars[ sanitize_title( $title ) ] = $title;
		}
	}

	// Register sidebars
	foreach ( $sidebars as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

}

add_action( 'widgets_init', 'martfury_register_sidebar' );

/**
 * Load theme
 */

// customizer hooks

require get_template_directory() . '/inc/mobile/theme-options.php';
require get_template_directory() . '/inc/vendors/theme-options.php';
require get_template_directory() . '/inc/backend/customizer.php';

// layout
require get_template_directory() . '/inc/functions/layout.php';

require get_template_directory() . '/inc/functions/entry.php';


// Woocommerce
require get_template_directory() . '/inc/frontend/woocommerce.php';

require get_template_directory() . '/inc/modules/modules.php';

if( function_exists( 'wcboost_wishlist' ) ) {
	require get_template_directory() . '/inc/frontend/wcboost-wishlist.php';
}

if( function_exists( 'wcboost_products_compare' ) ) {
	require get_template_directory() . '/inc/frontend/wcboost-products-compare.php';
}

// Vendor
require get_template_directory() . '/inc/vendors/vendors.php';

// Mobile
require get_template_directory() . '/inc/libs/mobile_detect.php';
require get_template_directory() . '/inc/mobile/layout.php';

require get_template_directory() . '/inc/functions/media.php';

require get_template_directory() . '/inc/functions/header.php';

if ( is_admin() ) {
	require get_template_directory() . '/inc/libs/class-tgm-plugin-activation.php';
	require get_template_directory() . '/inc/backend/plugins.php';
	require get_template_directory() . '/inc/backend/meta-boxes.php';
	require get_template_directory() . '/inc/backend/product-cat.php';
	require get_template_directory() . '/inc/backend/product-meta-box-data.php';
	require get_template_directory() . '/inc/mega-menu/class-mega-menu.php';
	require get_template_directory() . '/inc/backend/editor.php';

} else {
	// Frontend functions and shortcodes
	require get_template_directory() . '/inc/functions/nav.php';
	require get_template_directory() . '/inc/functions/breadcrumbs.php';
	require get_template_directory() . '/inc/mega-menu/class-mega-menu-walker.php';
	require get_template_directory() . '/inc/mega-menu/class-mobile-walker.php';
	require get_template_directory() . '/inc/functions/comments.php';
	require get_template_directory() . '/inc/functions/footer.php';

	// Frontend hooks
	require get_template_directory() . '/inc/frontend/layout.php';
	require get_template_directory() . '/inc/frontend/nav.php';
	require get_template_directory() . '/inc/frontend/entry.php';
	require get_template_directory() . '/inc/frontend/footer.php';
}

require get_template_directory() . '/inc/frontend/header.php';

/**
 * WPML compatible
 */
if ( defined( 'ICL_SITEPRESS_VERSION' ) && ! ICL_PLUGIN_INACTIVE ) {
	require get_template_directory() . '/inc/wpml.php';
}
function create_order($token, $query, $order_id){
    //echo  $query;
    //$apiUrl = 'https://zegerman.myshopify.com/admin/api/2024-07/orders.json';    
    $apiUrl = 'https://zegerman.myshopify.com/admin/api/unstable/orders.json';    
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "$apiUrl",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_POSTFIELDS => $query,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
        "X-Shopify-Access-Token:  $token",
        "accept: application/json",
        "Content-Type: application/json"
      ),
     ));
    
    $response = curl_exec($curl);
    $datas = json_decode($response, true);  
	//print_r($datas);
    //echo $datas['order']['id'];    
    return $datas['order']['id'];
}
function void_cancel_order($orderID, $token){
    cancel_order($orderID, $token);
    $apiUrl = 'https://zegerman.myshopify.com/admin/api/2024-07/orders/'.$orderID.'/transactions.json';
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "$apiUrl",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "X-Shopify-Access-Token:  $token",
        "Content-Type: application/json"
      ),
     ));
    
    $response = curl_exec($curl);
    $datas = json_decode($response, true);
    foreach($datas['transactions'] as $data){
        if($data['kind'] == "capture"){
            $parent_id = $data['id'];
            $apiUrl = 'https://zegerman.myshopify.com/admin/api/2024-07/orders/'.$orderID.'/transactions.json';
            $query = '{
              "transaction": {
                "kind": "void",
                "parent_id": '.$parent_id.'
              }}';
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "$apiUrl",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POSTFIELDS => $query,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "X-Shopify-Access-Token:  $token",
                "Content-Type: application/json"
              ),
             ));            
            $response = curl_exec($curl);
            $datas = json_decode($response, true);
        }
    }   
}

// Cancel Order
function cancel_order($orderID, $token){
    $apiUrl = 'https://zegerman.myshopify.com/admin/api/2024-07/orders/'.$orderID.'/cancel.json';
    $query = '';
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "$apiUrl",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    //CURLOPT_POSTFIELDS => $query,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
        "X-Shopify-Access-Token:  $token",
        "Content-Type: application/json"
      ),
     ));
    
    $response = curl_exec($curl);
    $datas = json_decode($response, true);
}
add_action( 'woocommerce_order_status_cancelled', 'cancel_shopify_order', 
21, 1 );
function cancel_shopify_order($order_id) {
	$token = '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';
	$orderID = get_post_meta( $order_id, 'shopify_order_id', true ); 
	if($orderID!="" && $orderID>0){
		void_cancel_order($orderID, $token);
	}
	$order = wc_get_order( $order_id );
	foreach ($order->get_items() as $item_id => $item) {
		//checkItem Connected with RedMart & Shopify
		$woo_item_quantity = $order->get_item_meta($item_id, '_qty', true);
		$this_product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();  
		$product = wc_get_product($this_product_id);
		if($product){
			$rpc_code = $product->get_meta('rpc_code');
			if($rpc_code!="" && $rpc_code>0){
				$quantityToUpdate = calculateRedMartQuantityForWooProduct($rpc_code, $woo_item_quantity);
				//echo "Quantity to update for RedMart: ".$quantityToUpdate;
				updateRedMartInventory($quantityToUpdate, $rpc_code);
			}
		}
	}	
	//exit();
}
add_action( 'woocommerce_order_status_processing', 'processing_shopify_order' );

function processing_shopify_order($order_id) {
    $token = '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';
    $order_shopify = array();
	$order = wc_get_order( $order_id );
	$billingEmail = $order->billing_email;
	$products = $order->get_items();
	foreach ($order->get_items() as $item_id => $item) {
		$woo_item_quantity = $item_quantity = $order->get_item_meta($item_id, '_qty', true);
		$this_product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();  
		$product = wc_get_product($this_product_id);
		if($product){
			$rpc_code = $product->get_meta('rpc_code');
			if($rpc_code!="" && $rpc_code>0){
				$quantityToUpdate = calculateRedMartQuantityForWooProduct($rpc_code, $woo_item_quantity);
				//echo "Quantity to update for RedMart: ".$quantityToUpdate;
				updateRedMartInventory($quantityToUpdate, $rpc_code);
			}
		}
		$product_name = $item['name'];
	    $item_total = $order->get_item_meta($item_id, '_line_total', true);
		$order_shopify['line_items'][] = array('title' => "$product_name", 'price' => "$item_total", 'quantity' => "$item_quantity");
	}
	$order_shopify['source_identifier'] = "$order_id";
	$order_shopify['source_name'] = "Woo";
	$order_shopify['source_url'] = home_url()."/wp-admin/admin.php?page=wc-orders&action=edit&id=$order_id";
	$order_shopify['tags'] = "store-pickup,woo-$order_id";
	$order_shopify['test'] = 1;
	$order_shopify['financial_status'] = "paid";
	$order_shopify['customer'] = array('first_name' => "$order->billing_first_name", 'last_name' => "$order->billing_last_name", 'email' => "$order->billing_email");
	$order_shopify['billing_address'] = array('first_name' => "$order->billing_first_name", 'last_name' => "$order->billing_last_name", 'address1' => "$order->billing_address_1", 'phone' => "$order->billing_phone", 'city' => "$order->billing_city", 'country' => "$order->billing_country", 'zip' => "$order->billing_postcode");
	$order_shopify['shipping_address'] = array('first_name' => "$order->shipping_first_name", 'last_name' => "$order->shipping_last_name", 'address1' => "$order->shipping_address_1", 'city' => "$order->shipping_city", 'country' => "$order->shipping_country", 'zip' => "$order->shipping_postcode");
	$order_shopify['transactions'][] = array('test' => 1, 'kind' => "capture", 'status' => "success", 'amount' => $order->get_total());
	$tax_price = $order->get_total_tax();
	$order_shopify['tax_lines'][] = array('title' => 'woo-standard', 'price' => "$tax_price", 'title' => "Standard");
	$shipping_price = $order->get_shipping_total() + $order->get_shipping_tax();
	$order_shopify['shipping_lines'][] = array('woo-standard' => 'woo-standard', 'price' => "$shipping_price", 'title' => "Standard");
	$order_shopify_full['order'] = $order_shopify;
	$datas = json_encode($order_shopify_full);
	$orderID = create_order($token, $datas, $order_id);
	update_post_meta( $order_id, 'shopify_order_id', esc_attr($orderID));
}
function getToken(){
    $access_token = "";
    $fh = fopen(dirname(__FILE__).'/redmart-access-token.txt','r');
    while ($line = fgets($fh)) {
      $data_r = $line;
      $data = json_decode($data_r, TRUE);
      $access_token = $data['access_token'];
    }
    fclose($fh);
    return $access_token;
}
function getRedMartAppKey(){
    $app_key = 130647;
    return $app_key;
}
function getRedMartAppSecret(){
    $app_secret = "eUHNQ4Z9lZs2KkF5104IgiuyH3jv7SEi";
    return $app_secret;
}
function updateRedMartInventoryStart($productID, $woo_item_quantity){
    $quantityToUpdate = calculateRedMartQuantityForWooProduct($productID, $woo_item_quantity);
    echo "$productID, Quantity to update for RedMart: ".$quantityToUpdate."<br/>";
    updateRedMartInventory($quantityToUpdate, $productID);
}
function calculateRedMartQuantityForWooProduct($productID, $woo_item_quantity){
	$current_redmart_quantity = getRedMartInventory($productID);	
	if($current_redmart_quantity>=$woo_item_quantity){
		$quantityToUpdate = $current_redmart_quantity - $woo_item_quantity;
	}else{
		$quantityToUpdate = 0;
	}
	return $quantityToUpdate;
}
function updateRedMartInventory($quantityToUpdate, $productID=1047771){
    $url = "https://api.lazada.sg/rest";
    $access_token = getToken();
    $app_key = getRedMartAppKey();
    $app_secret = getRedMartAppSecret();
    $url = "https://api.lazada.sg/rest";
    $c = new LazopClient($url, $app_key, $app_secret);
    $request = new LazopRequest('/rss/stockLot/update');
    $request->addApiParam('storeId','50525');
    $request->addApiParam('pickupLocationId','50532');
    $request->addApiParam('productId', $productID);
    $request->addApiParam('stockLotId','0');
    $request->addApiParam('stockLotUpdateDTO',"{\"quantityAtPickupLocation\":\"$quantityToUpdate\"}");
    $orders = $c->execute($request, $access_token);
    $product = json_decode($orders, TRUE);
    if(isset($product["result"])){
    }else if(isset($product["code"]) && $product["code"]!=0){
        if($product["code"] == "IllegalAccessToken"){
            refreshToken("https://auth.lazada.com/rest", $app_key, $app_secret);
        }
    }
}    
function getRedMartInventory($productID=1047771){
    $url = "https://api.lazada.sg/rest";
    $access_token = getToken();
    $app_key = getRedMartAppKey();
    $app_secret = getRedMartAppSecret();
    $url = "https://api.lazada.sg/rest";
    $c = new LazopClient($url, $app_key, $app_secret);
    $request = new LazopRequest('/rss/stockLots/get','GET');
    $request->addApiParam('storeId','50525');
    $request->addApiParam('pickupLocationId','50532');
    $request->addApiParam('productId', $productID);
    $orders = $c->execute($request, $access_token);
    $product = json_decode($orders, TRUE);    
	if(isset($product["result"]) && isset($product["result"]["data"][0]['quantityAtPickupLocation'])){
    	return $product["result"]["data"][0]['quantityAtPickupLocation'];
    }else if(isset($product["code"]) && $product["code"]!=0){
        if($product["code"] == "IllegalAccessToken"){
            refreshToken("https://auth.lazada.com/rest", $app_key, $app_secret);
        }
    }
}  
function getRedMartOrder(){
    //echo $access_token."===".$app_key."===".$app_secret;
    $access_token = getToken();
    $app_key = getRedMartAppKey();
    $app_secret = getRedMartAppSecret();
    $url = "https://api.lazada.sg/rest";
    $dt   = new DateTime();
    $to_stamp = $dt->getTimestamp()*1000;
    $dt   = new DateTime('yesterday');
    $from_stamp = $dt->getTimestamp()*1000;    
    echo "===".date('Y-m-d H:i:s', $to_stamp/1000)."===".date('Y-m-d H:i:s', $from_stamp/1000);
    echo "<br/>";
    //exit();
    $c = new LazopClient($url, $app_key, $app_secret);
    $request = new LazopRequest('/rss/pickup-jobs/get');
    $request->addApiParam('storeId','50525');
    $request->addApiParam('from',"$from_stamp");
    $request->addApiParam('till',"$to_stamp");
    $request->addApiParam('statuses','\"pickedup\"');
    $orders = $c->execute($request, $access_token);
    $orders = json_decode($orders, TRUE);
    
    if(isset($orders["result"])){
		//echo "<pre>";
		//print_r($orders);
        $order_shopify = array();
        $token = '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';
        foreach($orders["result"]["data"] as $order){
			$orderID = get_option($order['id']);
			echo $order['id']."====".$orderID;
            if($orderID==""){echo "Not exist <br/>";}else{echo "Exists <br/>";continue;}
			//exit();
            $pickedAt = $order['pickedAt']/1000;
            $order_shopify['source_identifier'] = $order['id'];
            $order_shopify['source_name'] = "RedMart";
            $order_shopify['tags'] = "store-pickup,redmart-".$order['id'];
            $order_shopify['test'] = 1;
            $order_shopify['financial_status'] = "paid";
            //$order_shopify['transactions'][] = array('test' => 1, 'kind' => "capture", 'status' => "success");
            foreach($order['items'] as $item){
                $product_name = $item['name'];
                $item_quantity = $item['qty'];
                $order_shopify['line_items'][] = array('title' => "$product_name", 'quantity' => "$item_quantity", 'price' => 0.00);
            }
            $order_shopify_full['order'] = $order_shopify;
            $datas = json_encode($order_shopify_full);
        	$orderID = create_order($token, $datas, $order['id']);
        	update_option( $order['id'] , $orderID );
        }
    }else if(isset($orders["code"]) && $orders["code"]!=0){
        if($orders["code"] == "IllegalAccessToken"){
            refreshToken("https://auth.lazada.com/rest", $app_key, $app_secret);
        }
    }
    
}
function refreshToken($url, $appkey, $appSecret){
    $access_token = "";
    $fh = fopen(dirname(__FILE__).'/redmart-access-token.txt','r');
    while ($line = fgets($fh)) {
      $data_r = $line;
      $data = json_decode($data_r, TRUE);
      $refresh_token = $data['refresh_token'];
      if($refresh_token!=""){
        $c = new LazopClient($url,$appkey,$appSecret);
        $request = new LazopRequest('/auth/token/refresh');
        $request->addApiParam('refresh_token',"$refresh_token");
        $json = $c->execute($request);
        $fh = fopen(dirname(__FILE__).'/redmart-access-token.txt','w');
        fwrite($fh, $json);
        fclose($fh);
      }
    }
    fclose($fh);
    return $access_token;
}


add_action( 'manage_product_posts_custom_column', 'wpso23858236_product_column_shopify', 10, 2 );
function wpso23858236_product_column_shopify( $column, $postid ) {
    if ( $column == 'shopify' ) {
        $shopify = get_post_meta( $postid, 'shopify', true );
        if ( ! empty( $shopify ) && $shopify =="Connected") {
            echo '<mark class="instock">In-Sync</mark>';
        }else{echo '<mark class="outofstock">No-Sync</mark>';
        }
    }
}
add_filter( 'manage_edit-product_columns', 'show_product_order', 15);
function show_product_order($columns){
   unset($columns['product_tag']);
   $columns['shopify'] = __( 'Shopify'); 
   return $columns;
}
add_filter( 'manage_edit-product_sortable_columns', 'my_sortable_reference_column' );
function my_sortable_reference_column( $columns ) {
    $columns['shopify'] = 'shopify';
    return $columns;
}

function getLocations($token){
		$post = '{
		"query": "query { locations(first: 5) { edges { node { id name address { formatted } } } } }"
		}';
        $apiUrl = 'https://zegerman.myshopify.com/admin/api/2024-10/graphql.json';
        $curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "$apiUrl",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $post,
			CURLOPT_HTTPHEADER => array(
				"X-Shopify-Access-Token:  $token",
				"Content-Type: application/json"
			),
		));
        $response = curl_exec($curl);
        $datas = json_decode($response, true);
        //print_r($datas);
}
function updateShopifyInventory($locationID, $inventory_item_id, $quantity, $token){
        $flag = false;
		$post = '{"location_id":'.$locationID.',"inventory_item_id":'.$inventory_item_id.',"available_adjustment":'.$quantity.'}';
        $apiUrl = 'https://zegerman.myshopify.com/admin/api/2024-10/inventory_levels/adjust.json';
        $curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "$apiUrl",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $post,
			CURLOPT_HTTPHEADER => array(
				"X-Shopify-Access-Token:  $token",
				"Content-Type: application/json"
			),
		));
        $response = curl_exec($curl);
        $datas = json_decode($response, true);
        //print_r($datas);
}
function getShopifyMetaFields($productID, $token){
        $flag = false;
        $apiUrl = 'https://zegerman.myshopify.com/admin/api/2024-07/products/'.$productID.'/metafields.json';
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "$apiUrl",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "X-Shopify-Access-Token:  $token",
            "Content-Type: application/json"
          ),
         ));
        $response = curl_exec($curl);
        $datas = json_decode($response, true);
        return $datas['metafields'];
        //echo "Meta Field is checked to: ".$flag;
        //return $flag; 
}
function getShopifyInventoryBySKU($sku, $token){
	$apiUrl = "https://zegerman.myshopify.com/admin/api/2024-10/graphql.json";
	$post = '{"query": "query { productVariants(first: 3, query: \"sku:'.$sku.'\") { edges { node { id title price updatedAt inventoryQuantity product { id title } inventoryItem {id }} } } }"}';
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "$apiUrl",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $post,
		CURLOPT_HTTPHEADER => array(
			"X-Shopify-Access-Token:  $token",
			"Content-Type: application/json"
		),
	));
	
	$response = curl_exec($curl);
	$datas = json_decode($response, true);
	if(isset($datas['data']['productVariants']['edges'][0]['node']['inventoryQuantity'])){
		$data['shopify_inventory']=$inventoryQuantity = $datas['data']['productVariants']['edges'][0]['node']['inventoryQuantity'];
		$data['shopify_id']=basename($datas['data']['productVariants']['edges'][0]['node']['product']['id']);
		$data['shopify_variant_id']=basename($datas['data']['productVariants']['edges'][0]['node']['id']);
		$data['shopify_inventory_item_id']=basename($datas['data']['productVariants']['edges'][0]['node']['inventoryItem']['id']);
		return $data;
	}else{
		return "-1";
	}
}
function updateShopifyOrderRoutine(){
	$args = array(
		'post_type' => array('product_variation', 'product'),
	    'meta_query'      => array(
	        array(
	            'key'     => 'rpc_code',
	            'value'   => 0,
	            'compare' => '>='
	        ),
	    ),
	);
	// execute the main query
	$the_main_loop = new WP_Query($args);
	echo "Total product/variations connected: ";echo $count = $the_main_loop->found_posts;
	echo "<br/>";
	echo "<pre>";
	// go main query
	if($the_main_loop->have_posts()) { 
	    while($the_main_loop->have_posts()) { 
		    $the_main_loop->the_post(); 
		    echo $productId = $post_id = get_the_ID();
			echo "====";
			echo $sku = $product->get_sku();
			echo "====";
			echo $rpc_code = $product->get_meta('rpc_code');
			echo "====<br/>";
		    $date_synced = get_post_meta($productId, 'red_mart_shopify_date_synced', true);
			$date_now = date('Y-m-d H:i:s');
			$d1 = strtotime("$date_synced"); // first date
			$d2 = strtotime("$date_now"); // second date
			$interval = round(abs($d2 - $d1) / 60, 2); // get difference between two datesecho "<br/>";
			echo "==" . $interval;
			echo " min passed<br/>";
			if($interval >= 5 || $date_synced==""){
				update_post_meta($productId, 'red_mart_shopify_date_synced', date("Y-m-d H:i:s"));
				//Get Shopify Inventory - Threshold
				$token = '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';
				//getLocations($token);exit();
				$locationID = 63562907842;
				//$shopify_inventory_item_id=43685596102850;$quantity=-7;
				//updateShopifyInventory($locationID, $shopify_inventory_item_id, $quantity, $token);
				//exit();
				$data = getShopifyInventoryBySKU($sku, $token);
				print_r($data);
				/*Array
				(
				    [shopify_inventory] => 8
				    [shopify_id] => 7236416176322
				    [shopify_variant_id] => 41589570732226
				    [shopify_inventory_item_id] => 43685596102850
				)*/
				if($data['shopify_inventory']>=0){
					//Get RedMart Inventory
					$metas = getShopifyMetaFields($data['shopify_id'], $token);
					$rpc_threshold = 0;
					foreach($metas as $metafield){
		                if($metafield['key'] == 'rpc_threshold'){
		                    if($metafield['value'] !="" && $metafield['value'] > 0){
								$rpc_threshold = $metafield['value'];
		                    }
		                }
		            }  
					if($rpc_threshold == 0){
						$final_inventory = $data['shopify_inventory'];
					}
					else{
						$final_inventory = $data['shopify_inventory'] - $rpc_threshold;
					}

					echo "<br/>Final after threshold: ".$final_inventory;
					$redmart_inventory = getRedMartInventory($rpc_code);
					echo "<br/>RedMart Inventory: ".$redmart_inventory;
					if($redmart_inventory!=$final_inventory){
						//update shopify inventory
						echo $to_update_shopify = $redmart_inventory+$rpc_threshold-$data['shopify_inventory'];
						if(isset($data['shopify_inventory_item_id'])){
							$shopify_inventory_item_id = $data['shopify_inventory_item_id'];
							updateShopifyInventory($locationID, $shopify_inventory_item_id, $to_update_shopify, $token);
							echo "<br/>Done shopify inventory";
						}		
					}else{
						echo "<br/>Both inventory are same";
					}
				}
				echo "<br/>";
			}
	    } // endwhile
	    wp_reset_postdata(); // VERY VERY IMPORTANT
	}
}

// CRON fetch all connected the redmart product/variations in loop every 5 min for meta field rpc_code
// rpc_code value added via webhook from shopify
// Get Shopify inventory and inventory_item_id by SKU
// Get Shopify meta field to get rpc_threshold
// Update Inventory to shopify updateShopifyInventory
add_filter( 'cron_schedules', 'add_update_shopify_inventory_routine' );
function add_update_shopify_inventory_routine( $schedules ) {
    $schedules['every_five_minute'] = array(
            'interval'  => 300,
            'display'   => __( 'Every 5 min', 'textdomain' )
    );
    return $schedules;
}

// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'add_update_shopify_inventory_routine' ) ) {
    wp_schedule_event( time(), 'every_five_minute', 'add_update_shopify_inventory_routine' );
}
// Hook into that action that'll fire every three minutes
add_action( 'add_update_shopify_inventory_routine', 'add_update_shopify_inventory_routine_func' );
function add_update_shopify_inventory_routine_func() {
    $access_token = getToken();
    updateShopifyOrderRoutine();
}
