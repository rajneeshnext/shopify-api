<?php 
/* Template Name: Shopify Woo*/
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
echo $payload = '{"admin_graphql_api_id":"gid:\/\/shopify\/Product\/9200697475389","body_html":"<p>1 Paper Bag 20c<\/p>","created_at":"2024-03-30T08:29:30+08:00","handle":"paper-bag-otc","id":9200697475389,"product_type":"","published_at":null,"template_suffix":null,"title":"1 Paper Bag 20c","updated_at":"2024-09-23T14:21:59+08:00","vendor":"ZeGerman","status":"draft","published_scope":"web","tags":"","variants":[{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/49405923393853","barcode":null,"compare_at_price":null,"created_at":"2024-09-23T14:19:16+08:00","id":49405923393853,"inventory_policy":"deny","position":2,"price":"0.20","product_id":9200697475389,"sku":"rrr1111","taxable":true,"title":"Medium \/ White","updated_at":"2024-09-23T14:19:16+08:00","option1":"Medium","option2":"White","option3":null,"image_id":null,"inventory_item_id":51447987077437,"inventory_quantity":0,"old_inventory_quantity":0},{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/49405923426621","barcode":null,"compare_at_price":null,"created_at":"2024-09-23T14:19:16+08:00","id":49405923426621,"inventory_policy":"deny","position":3,"price":"0.20","product_id":9200697475389,"sku":"rrrw121","taxable":true,"title":"Medium \/ Red","updated_at":"2024-09-23T14:19:16+08:00","option1":"Medium","option2":"Red","option3":null,"image_id":null,"inventory_item_id":51447987110205,"inventory_quantity":0,"old_inventory_quantity":0},{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/49405923459389","barcode":null,"compare_at_price":null,"created_at":"2024-09-23T14:19:16+08:00","id":49405923459389,"inventory_policy":"deny","position":4,"price":"0.20","product_id":9200697475389,"sku":"rrr1231","taxable":true,"title":"Small \/ White","updated_at":"2024-09-23T14:19:16+08:00","option1":"Small","option2":"White","option3":null,"image_id":null,"inventory_item_id":51447987142973,"inventory_quantity":0,"old_inventory_quantity":0},{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/49405923492157","barcode":null,"compare_at_price":null,"created_at":"2024-09-23T14:19:16+08:00","id":49405923492157,"inventory_policy":"deny","position":5,"price":"0.20","product_id":9200697475389,"sku":"rr4321","taxable":true,"title":"Small \/ Red","updated_at":"2024-09-23T14:19:16+08:00","option1":"Small","option2":"Red","option3":null,"image_id":null,"inventory_item_id":51447987175741,"inventory_quantity":0,"old_inventory_quantity":0}],"options":[{"name":"Size","id":12097023934781,"product_id":9200697475389,"position":1,"values":["Medium","Small"]},{"name":"Color","id":12097023967549,"product_id":9200697475389,"position":2,"values":["White","Red"]}],"images":[{"id":45771220713789,"product_id":9200697475389,"position":1,"created_at":"2024-05-18T13:05:43+08:00","updated_at":"2024-05-18T13:05:45+08:00","alt":null,"width":1207,"height":1207,"src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0580\/1449\/9010\/files\/B60FC8F5-E405-444F-8809-255597A5165E.jpg?v=1716008745","variant_ids":[],"admin_graphql_api_id":"gid:\/\/shopify\/ProductImage\/45771220713789"}],"image":{"id":45771220713789,"product_id":9200697475389,"position":1,"created_at":"2024-05-18T13:05:43+08:00","updated_at":"2024-05-18T13:05:45+08:00","alt":null,"width":1207,"height":1207,"src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0580\/1449\/9010\/files\/B60FC8F5-E405-444F-8809-255597A5165E.jpg?v=1716008745","variant_ids":[],"admin_graphql_api_id":"gid:\/\/shopify\/ProductImage\/45771220713789"},"media":[{"id":38277692621117,"product_id":9200697475389,"position":1,"created_at":"2024-05-18T13:05:43+08:00","updated_at":"2024-05-18T13:05:45+08:00","alt":null,"status":"READY","media_content_type":"IMAGE","preview_image":{"width":1207,"height":1207,"src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0580\/1449\/9010\/files\/B60FC8F5-E405-444F-8809-255597A5165E.jpg?v=1716008745","status":"READY"},"variant_ids":[],"admin_graphql_api_id":"gid:\/\/shopify\/MediaImage\/38277692621117"}],"variant_gids":[{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/49405923393853","updated_at":"2024-09-23T06:19:16.000Z"},{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/49405923426621","updated_at":"2024-09-23T06:19:16.000Z"},{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/49405923459389","updated_at":"2024-09-23T06:19:16.000Z"},{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/49405923492157","updated_at":"2024-09-23T06:19:16.000Z"}],"product_event_type":"update"}';
$product_event_type = $_GET['product_event_type'];
$payload = str_replace("'", "", $payload);
$arr_m = json_decode($payload, TRUE);
print_r($arr_m);exit();
pricode_update_product($arr_m);
exit();
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
    if(count($data_product['variants'])>1){
        $desc = $data_product['body_html'];
        $title = $data_product['title'];
        if($data_product['variants'][0]['sku']){
            $productId = wc_get_product_id_by_sku($data_product['variants'][0]['sku']);
            $product_id = wp_get_post_parent_id($productId);
            $product = wc_get_product( $product_id );       
            if($product){
                $product->set_short_description("$desc");
                $product->set_name($title);
                $atts = [];
                foreach($data_product['options'] as $option){                
                    $atts[] = pricode_create_attributes($option['name'], $option['values']);                
                }
                $product->set_attributes( $atts );
                $product->save(); 

                foreach($data_product['variants'] as $variant){ 
                    $data = new stdClass();
                    $variantId = wc_get_product_id_by_sku( $variant['sku'] );
                    $variation = wc_get_product($variantId);
                    if($variation){     
                            if(isset($variant['sku']) && $variant['sku']!=""){
                                $variation->set_sku($variant['sku']);
                            }else{
                                $variation->set_sku("");
                            }    
                            if(isset($variant['barcode']) && $variant['barcode']!=""){
                                $variation->set_global_unique_id($variant['barcode']);
                            }else{
                                $variation->set_global_unique_id("");
                            } 
                            // Get the variation attaribute "size" value 
                            $variation->set_stock_quantity($variant['inventory_quantity']);
                            $variation->set_regular_price($variant['compare_at_price']);
                            $variation->set_sale_price($variant['price']);
                            if($variant['compare_at_price'] == "" || $variant['compare_at_price']==0){
                                $variation->set_regular_price($variant['price']);
                            }       
                            $i=0;
                            $attrb = [];                    
                            foreach($data_product['options'] as $option){
                                $i++;
                                $attrb[$option['name']] = $variant['option'.$i];
                            } 
                            echo "Dd";
                            $variation->set_attributes($attrb);
                            $variation->set_parent_id($product_id);
                            $variation->save();
                            $product = wc_get_product($product_id);
                            $product->save();
                    }else{
                        if(isset($variant['sku']) && $variant['sku']!=""){
                            $data->sku = $variant['sku'];
                        }else{
                            $data->sku = "";
                        }    
                        if(isset($variant['barcode']) && $variant['barcode']!=""){
                            $data->global_unique_id = $variant['barcode'];
                        }else{
                            $data->global_unique_id = "";
                        } 
                        $data->sale_price = $variant['price'];
                        $data->regular_price = $variant['compare_at_price'];
                        $data->inventory_quantity = $variant['inventory_quantity']; 
                        $i=0; 
                        $attrb = [];
                        foreach($data_product['options'] as $option){
                            $i++;
                            $attrb[$option['name']] = $variant['option'.$i];
                        } 
                        pricode_create_variations( $product->get_id(), $attrb, $data );
                    }
                }
            }else{
                pricode_create_product($data_product);
            }
            
        }else{
            echo "SKU does not exist";
        }        
    }else{
        if($data_product['variants'][0]['sku']){
            $productId = wc_get_product_id_by_sku($data_product['variants'][0]['sku']);
            $product = wc_get_product( $productId );
            if($product){
                $desc = $data_product['body_html'];
                $title = $data_product['title'];
                $product->set_short_description("$desc");
                $product->set_name($title);
                if(isset($data_product['variants'][0]['sku']) && $data_product['variants'][0]['sku']!=""){
                    $product->set_sku($data_product['variants'][0]['sku']);
                }else{
                    $product->set_sku("");
                }    
                if(isset($data_product['variants'][0]['barcode']) && $data_product['variants'][0]['barcode']!=""){
                    $product->set_global_unique_id($data_product['variants'][0]['barcode']);
                }else{
                    $product->set_global_unique_id("");
                } 
                $product->set_price($data_product['variants'][0]['price']);            
                $product->set_regular_price($data_product['variants'][0]['compare_at_price']);
                $product->set_sale_price($data_product['variants'][0]['price']);
                if($data_product['variants'][0]['compare_at_price'] == "" || $data_product['variants'][0]['compare_at_price']==0){
                    $product->set_regular_price($data_product['variants'][0]['price']);
                }  
                $product->set_stock_status($data_product['variants'][0]['inventory_quantity']);
                $product->save(); 

                if(isset($data_product['product_type']) && $data_product['product_type']!="")
                wp_set_object_terms($product->get_id(), array($data_product['product_type']), 'product_cat');
                if(isset($data_product['vendor']) && $data_product['vendor']!="")
                wp_set_object_terms($product->get_id(), array($data_product['vendor']), 'product_brand');
                if($data_product['tags'] !=""){
                    $tags = explode(',', $data_product['tags']);
                    wp_set_object_terms($product->get_id(), $tags, 'product_tag');
                }  
                delete_all_images_for_product($product->get_id()); 
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
                echo "<br/>Product updated";
                return $product;
            }else{
                pricode_create_product($data_product);
            }
        }else{
            echo "SKU does not exist";
        }
    }  
}

function pricode_create_product($data_product){ 
    if($data_product['id']){
        $desc = $data_product['body_html'];
        $title = $data_product['title'];         
        if(count($data_product['variants'])>1){
            if($data_product['variants'][0]['sku']){
                $product = new WC_Product_Variable();
                $product->set_short_description("$desc");
                $product->set_name($title);
                //$product->set_sku($data_product['id']);
                $product->set_price(1);
                $product->set_regular_price(1);
                $product->set_stock_status();
                $atts = [];
                foreach($data_product['options'] as $option){                
                    $atts[] = pricode_create_attributes($option['name'], $option['values']);                
                }
                $product->set_attributes( $atts );
                $product->save();
                if(isset($data_product['product_type']) && $data_product['product_type']!="")
                wp_set_object_terms($product->get_id(), array($data_product['product_type']), 'product_cat');
                if(isset($data_product['vendor']) && $data_product['vendor']!="")
                wp_set_object_terms($product->get_id(), array($data_product['vendor']), 'product_brand');
                if($data_product['tags'] !=""){
                    $tags = explode(',', $data_product['tags']);
                    wp_set_object_terms($product->get_id(), $tags, 'product_tag');
                }    
                foreach($data_product['variants'] as $variant){
                    $data = new stdClass();
                    if(isset($variant['sku']) && $variant['sku']!=""){
                        $data->sku = $variant['sku'];
                    }else{
                        $data->sku = $variant['id'];
                    }    
                    if(isset($variant['barcode']) && $variant['barcode']!=""){
                        $data->global_unique_id = $variant['barcode'];
                    }else{
                        $data->global_unique_id = "";
                    } 
                    $data->sale_price = $variant['price'];
                    $data->regular_price = $variant['compare_at_price'];
                    $data->inventory_quantity = $variant['inventory_quantity'];     
                    $i=0; 
                    $attrb = [];
                    foreach($data_product['options'] as $option){
                        $i++;
                        $attrb[sanitize_title($option['name'])] = $variant['option'.$i];
                    } 
                    pricode_create_variations( $product->get_id(), $attrb, $data );
                }
            }else{
                echo "SKU does not exist";
            }
        }else{
            if($data_product['variants'][0]['sku']){
                $product = new WC_Product();
                $product->set_short_description("$desc");
                $product->set_name($title);
                if(isset($data_product['variants'][0]['sku']) && $data_product['variants'][0]['sku']!=""){
                    $product->set_sku($data_product['variants'][0]['sku']);
                }else{
                    $product->set_sku($data_product['variants'][0]['id']);
                }    
                if(isset($data_product['variants'][0]['barcode']) && $data_product['variants'][0]['barcode']!=""){
                    $product->set_global_unique_id($data_product['variants'][0]['barcode']);
                }else{
                    $product->set_global_unique_id("");
                } 
                $product->set_price($data_product['variants'][0]['price']);            
                $product->set_regular_price($data_product['variants'][0]['compare_at_price']);
                $product->set_sale_price($data_product['variants'][0]['price']);
                if($data_product['variants'][0]['compare_at_price'] == "" || $data_product['variants'][0]['compare_at_price']==0){
                    $product->set_regular_price($data_product['variants'][0]['price']);
                }  
                $product->set_stock_status($data_product['variants'][0]['inventory_quantity']);
                $product->save();
                if(isset($data_product['product_type']) && $data_product['product_type']!="")
                wp_set_object_terms($product->get_id(), array($data_product['product_type']), 'product_cat');
                if(isset($data_product['vendor']) && $data_product['vendor']!="")
                wp_set_object_terms($product->get_id(), array($data_product['vendor']), 'product_brand');
                if($data_product['tags'] !=""){
                    $tags = explode(',', $data_product['tags']);
                    wp_set_object_terms($product->get_id(), $tags, 'product_tag');
                }  
            }
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
