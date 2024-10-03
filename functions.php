<?php
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
