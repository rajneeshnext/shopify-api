<?php 
/* Template Name: Shopify Woo Redmart */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
echo "<pre>";

function getImages($productID, $token){
    $flag = false;
    $apiUrl = 'https://zegerman.myshopify.com/admin/api/2024-07/products/'.$productID.'/images.json';
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
    //print_r($datas['images']);
    return $datas['images'];
}

function getMetaFields($productID, $token){
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
    //print_r($datas['metafields'] );
    foreach($datas['metafields'] as $metafield){
        if($metafield['key'] == 'connect_woo'){
            if($metafield['value']){
                $flag= true;
            }else{
                $flag = false;
            }
        }
    }   
    echo "Meta Field is checked to: ".$flag;
    return $flag; 
}

$payload = @file_get_contents('php://input');
//$payload = '{"admin_graphql_api_id":"gid:\/\/shopify\/Product\/9200697475389","body_html":"","created_at":"2024-03-30T08:29:30+08:00","handle":"paper-bag-otc","id":9200697475389,"product_type":"","published_at":"2024-03-30T08:30:02+08:00","template_suffix":null,"title":"1 Paper Bag 20c 111","updated_at":"2024-09-17T16:29:47+08:00","vendor":"ZeGerman","status":"active","published_scope":"global","tags":"","variants":[{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/48177660887357","barcode":null,"compare_at_price":null,"created_at":"2024-03-30T08:29:30+08:00","id":48177660887357,"inventory_policy":"deny","position":1,"price":"0.20","product_id":9200697475389,"sku":"paperbag","taxable":true,"title":"Default Title","updated_at":"2024-09-14T18:01:55+08:00","option1":"Default Title","option2":null,"option3":null,"image_id":null,"inventory_item_id":50218072047933,"inventory_quantity":120,"old_inventory_quantity":120}],"options":[{"name":"Title","id":11549769367869,"product_id":9200697475389,"position":1,"values":["Default Title"]}],"images":[{"id":45771220713789,"product_id":9200697475389,"position":1,"created_at":"2024-05-18T13:05:43+08:00","updated_at":"2024-05-18T13:05:45+08:00","alt":null,"width":1207,"height":1207,"src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0580\/1449\/9010\/files\/B60FC8F5-E405-444F-8809-255597A5165E.jpg?v=1716008745","variant_ids":[],"admin_graphql_api_id":"gid:\/\/shopify\/ProductImage\/45771220713789"}],"image":{"id":45771220713789,"product_id":9200697475389,"position":1,"created_at":"2024-05-18T13:05:43+08:00","updated_at":"2024-05-18T13:05:45+08:00","alt":null,"width":1207,"height":1207,"src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0580\/1449\/9010\/files\/B60FC8F5-E405-444F-8809-255597A5165E.jpg?v=1716008745","variant_ids":[],"admin_graphql_api_id":"gid:\/\/shopify\/ProductImage\/45771220713789"},"media":[{"id":38277692621117,"product_id":9200697475389,"position":1,"created_at":"2024-05-18T13:05:43+08:00","updated_at":"2024-05-18T13:05:45+08:00","alt":null,"status":"READY","media_content_type":"IMAGE","preview_image":{"width":1207,"height":1207,"src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0580\/1449\/9010\/files\/B60FC8F5-E405-444F-8809-255597A5165E.jpg?v=1716008745","status":"READY"},"variant_ids":[],"admin_graphql_api_id":"gid:\/\/shopify\/MediaImage\/38277692621117"}],"variant_gids":[{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/48177660887357","updated_at":"2024-09-14T10:01:55.000Z"}]}';
if($payload!=""){
    $flag=false;
    $product_event_type = $_GET['product_event_type'];
    $payload = str_replace("'", "", $payload);
    $arr_m = json_decode($payload, TRUE);
    $arr_t['product_event_type'] = $product_event_type;
    $data_arr = array_merge($arr_m,$arr_t);
    //print_r($data_arr);//exit();
    $json = json_encode($data_arr);
    $fh = fopen(dirname(__FILE__).'/webhook_response.txt','w');
    fwrite($fh, $json);
    fclose($fh);

    $productID = $data_arr['id'];
    if($productID){
        echo "<br/>Checking Meta Field for WOO sync allowed. <br/>";       
        $flag = getMetaFields($productID, $token = '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');
    }
    if($flag){
        echo "<br/>Meta Field is true and event is ".$product_event_type;
        if($product_event_type == "update"){
            pricode_update_product($data_arr);
        }elseif($product_event_type == "delete"){
            pricode_delete_product($data_arr);
        }elseif($product_event_type == "add"){
            pricode_create_product($data_arr);
        }
    }  
}

function pricode_update_product($data_product){ 
    //echo "<pre>";
    //print_r($data_product);
    $productId = wc_get_product_id_by_sku( $data_product['id'] );
    $product = wc_get_product( $productId );

    if($product){
        $desc = $data_product['body_html'];
        $title = $data_product['title'];
        //$product = new WC_Product_Variable();
        $product->set_description("$desc");
        $product->set_name($title);
        //$product->set_sku($data_product['id']);
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
            $variantId = wc_get_product_id_by_sku( $variant['id'] );
            $variation = wc_get_product($variantId);
            if($variation){                    
                    // Get the variation attaribute "size" value 
                    $variation->set_stock_quantity($variant['inventory_quantity']);
                    $variation->set_regular_price($variant['compare_at_price']);
                    $variation->set_sale_price($variant['price']);
                    if($variant['compare_at_price'] == "" || $variant['compare_at_price']==0){
                        $variation->set_regular_price($variant['price']);
                    }       
                    $variation->save();
            }else{
                $data->sku = $variant['id'];
                $data->sale_price = $variant['price'];
                $data->regular_price = $variant['compare_at_price'];
                $data->inventory_quantity = $variant['inventory_quantity'];     
                pricode_create_variations( $product->get_id(), ['size' => $variant['option1']], $data );
            }
        }

        delete_all_images_for_product($productId); 
        $images_urls = getImages($data_product['id'], $token = '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'); 
        foreach($images_urls as $image){
            echo "<br/>Adding Image: ".$image['src']."<br/>";
            echo $image_url = trim(strtok($image['src'], '?'));
            $upload_dir = wp_upload_dir();
            $image_data = file_get_contents($image_url);
            $filename = basename($image_url);
            if(wp_mkdir_p($upload_dir['path']))
                $file = $upload_dir['path'] . '/' . $filename;
            else
                $file = $upload_dir['basedir'] . '/' . $filename;
            file_put_contents($file, $image_data);

            $wp_filetype = wp_check_filetype($filename, null );
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment( $attachment, $file, $product->get_id() );
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            set_post_thumbnail( $product->get_id(), $attach_id );
        }           
        echo "<br/>Product Added";
        return $product;
    }else{
        pricode_create_product($data_arr);
    }    
}

function pricode_create_product($data_product){ 
    //echo "<pre>";
    //print_r($data_product);
    if($data_product['id']){
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

        $images_urls = getImages($data_product['id'], $token = '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'); 
        foreach($images_urls as $image){
            echo "<br/>Adding Image: ".$image['src']."<br/>";
            echo $image_url = trim(strtok($image['src'], '?'));
            $upload_dir = wp_upload_dir();
            $image_data = file_get_contents($image_url);
            $filename = basename($image_url);
            if(wp_mkdir_p($upload_dir['path']))
                $file = $upload_dir['path'] . '/' . $filename;
            else
                $file = $upload_dir['basedir'] . '/' . $filename;
            file_put_contents($file, $image_data);

            $wp_filetype = wp_check_filetype($filename, null );
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment( $attachment, $file, $product->get_id() );
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            set_post_thumbnail( $product->get_id(), $attach_id );
        }           
        echo "Product Added";
        return $product;
    }else{
        echo "Product not found";
        return false;
    }
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
    if($data->regular_price == "" || $data->regular_price==0){
        $variation->set_regular_price($data->sale_price);
    }
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
function delete_all_images_for_product($product_id) {
    // Check if the product exists
    $product = wc_get_product($product_id);
    if (!$product) {
        return; // Exit if the product does not exist
    }

    // Delete the featured image (thumbnail)
    $attachment_id = get_post_thumbnail_id($product_id);
    if ($attachment_id) {
        // Delete the attachment (image)
        wp_delete_attachment($attachment_id, true);
        
        // Remove the product thumbnail metadata
        delete_post_meta($product_id, '_thumbnail_id');
    }

    // Delete gallery images
    $gallery_image_ids = $product->get_gallery_image_ids();
    if (!empty($gallery_image_ids)) {
        foreach ($gallery_image_ids as $gallery_image_id) {
            // Delete each gallery image
            wp_delete_attachment($gallery_image_id, true);
        }

        // Remove the gallery metadata
        delete_post_meta($product_id, '_product_image_gallery');
    }
}
//Adding product
//$product = pricode_create_product($data_product);
//$product = pricode_delete_product($data_product);
//$product = pricode_update_product($data_product);
?>