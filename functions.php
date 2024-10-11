<?php
/**
 * DrFuri Core functions and definitions
 *
 * @package Martfury
 */
include dirname(__FILE__) . '/php/LazopSdk.php'; 
function create_order($token, $query, $order_id){
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
	//void_cancel_order($orderID, $token); // Shopify Order
	$order = wc_get_order( $order_id );
	foreach ($order->get_items() as $item_id => $item) {
		//checkItem Connected with RedMart & Shopify
		$woo_item_quantity = $order->get_item_meta($item_id, '_qty', true);
		$productID=1047771;
		$quantityToUpdate = calculateRedMartQuantityForWooProduct($productID, -$woo_item_quantity);
		updateRedMartInventory($quantityToUpdate, $productID); // Update RedMart Inventory
	}	
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
		$productID=1047771;
		$quantityToUpdate = calculateRedMartQuantityForWooProduct($productID, $woo_item_quantity);
		echo "Quantity to update for RedMart: ".$quantityToUpdate;
		updateRedMartInventory($quantityToUpdate, $productID); // Update RedMart Inventory
		
		$product_name = $item['name'];
	    $item_total = $order->get_item_meta($item_id, '_line_total', true);
		$order_shopify['line_items'][] = array('title' => "$product_name", 'price' => "$item_total", 'quantity' => "$item_quantity");
	}
	return;
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
function calculateRedMartQuantityForWooProduct($productID, $woo_item_quantity){
	$current_redmart_quantity = getRedMartInventory($productID);	
	if($current_redmart_quantity>=$woo_item_quantity){
		$quantityToUpdate = $current_redmart_quantity - $woo_item_quantity;
	}else{
		$quantityToUpdate = 0;
	}
	return $quantityToUpdate;
}
function updateRedMartInventoryStart($productID, $woo_item_quantity){
	$woo_item_quantity = $item_quantity = $order->get_item_meta($item_id, '_qty', true);
    $productID=1047771;
    $quantityToUpdate = calculateRedMartQuantityForWooProduct($productID, $woo_item_quantity);
    //echo "Quantity to update for RedMart: ".$quantityToUpdate;
    updateRedMartInventory($quantityToUpdate, $productID);
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

add_filter( 'cron_schedules', 'add_update_redmart_order_every_hour' );
function add_update_redmart_order_every_hour( $schedules ) {
    $schedules['every_hour'] = array(
            'interval'  => 3600,
            'display'   => __( 'Every hr', 'textdomain' )
    );
    return $schedules;
}

// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'add_update_redmart_order_every_hour' ) ) {
    wp_schedule_event( time(), 'every_hour', 'add_update_redmart_order_every_hour' );
}

// Hook into that action that'll fire every three minutes
add_action( 'add_update_redmart_order_every_hour', 'add_update_redmart_order_every_hour_api' );
function add_update_redmart_order_every_hour_api() {
    $access_token = getToken();
	exit();
    getRedMartOrder();
}
//Column Added to Woocomerce OrderList to display Synced products
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
