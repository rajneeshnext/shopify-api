<?php
/**
 * DrFuri Core functions and definitions
 *
 * @package Martfury
 */


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
    $apiUrl = 'https://zegerman.myshopify.com/admin/api/2024-07/orders.json';    
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
    echo $datas['order']['id'];    
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
//$orderID = 6074772619581;
void_cancel_order($orderID, $token);
}
add_action( 'woocommerce_order_status_processing', 'processing_shopify_order' );

function processing_shopify_order($order_id) {
    $token = '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';
    $order_shopify = array();
$order = wc_get_order( $order_id );
$billingEmail = $order->billing_email;
$products = $order->get_items();
foreach ($order->get_items() as $item_id => $item) {
$product_name = $item['name'];
    $item_quantity = $order->get_item_meta($item_id, '_qty', true);
    $item_total = $order->get_item_meta($item_id, '_line_total', true);
	$order_shopify['line_items'][] = array('title' => "$product_name", 'price' => "$item_total", 'quantity' => "$item_quantity");
}
$order_shopify['tags'] = "store-pickup,woo-$order_id";
$order_shopify['test'] = 1;
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