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
        return $datas['metafields'];
        //echo "Meta Field is checked to: ".$flag;
        //return $flag; 
    }
    function getLocation($id, $token){
        $flag = false;
        echo $apiUrl = "https://zegerman.myshopify.com/admin/api/2024-07/locations.json";
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
        //print_r($datas);
        return $flag; 
    }
    function getProduct($id, $token){
        $flag = false;
        //our-development-store.myshopify.com/admin/api/2024-10/graphql.json
		$apiUrl = "https://zegerman.myshopify.com/admin/api/2024-10/graphql.json";
        //$apiUrl = "https://zegerman.myshopify.com/admin/api/2024-04/products/$id.json";
        $post = '{"query": "query { node(id: \"gid://shopify/Product/'.$id.'\") { id ... on Product { title category {name}} } }"}';
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
		$cat_arr = array();
		if(isset($datas['data']['node']['category'])){
			foreach($datas['data']['node']['category'] as $cat){
				$cat_arr[] = $cat;
			}
		}
        //print_r($cat_arr);
        return $cat_arr; 
    }
    function getInventoryInfo($ids, $token){
        $flag = false;
        $ids = str_replace(" ", "", $ids);
        $ids = str_replace(",", "%2C", $ids);
        $apiUrl = 'https://zegerman.myshopify.com/admin/api/2024-10/inventory_items.json?ids='.$ids;
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
        print_r($datas);
        return $flag; 
    }
   $payload = @file_get_contents('php://input');
   /*$payload = '{"admin_graphql_api_id":"gid:\/\/shopify\/Product\/9686175940925","body_html":"<p>Test 2<\/p>","created_at":"2024-10-04T13:30:00+08:00","handle":"test-product-2","id":9686175940925,"product_type":"Aufstrich","published_at":null,"template_suffix":"","title":"Test Product 2-1","updated_at":"2024-10-04T13:42:48+08:00","vendor":"ZeGerman","status":"draft","published_scope":"web","tags":"Lebensmittel, spo-default","variants":[{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/49451334304061","barcode":null,"compare_at_price":null,"created_at":"2024-10-04T13:30:01+08:00","id":49451334304061,"inventory_policy":"deny","position":2,"price":"3.20","product_id":9686175940925,"sku":"test2","taxable":true,"title":"Default Title","updated_at":"2024-10-04T13:33:08+08:00","option1":"Default Title","option2":null,"option3":null,"image_id":null,"inventory_item_id":51493526765885,"inventory_quantity":0,"old_inventory_quantity":0}],"options":[{"name":"Title","id":12114323865917,"product_id":9686175940925,"position":1,"values":["Default Title"]}],"images":[],"image":null,"media":[],"variant_gids":[{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/49451334304061","updated_at":"2024-10-04T05:33:08.000Z"}],"product_event_type":"update"}';
    $product_event_type = $_GET['product_event_type'];
    $payload = str_replace("'", "", $payload);
    $arr_m = json_decode($payload, TRUE);
    print_r($arr_m);
    getProduct($arr_m['id'], $token = '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');
    getLocation($arr_m['id'], $token = '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');
    exit();
    foreach($arr_m['variants'] as $variant){ 
        $arr_inventory_ids[] = $variant['inventory_item_id'];
    }
    echo $inventory_ids = implode(', ', $arr_inventory_ids);
    getInventoryInfo($inventory_ids, $token = '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');
    //pricode_update_product($arr_m);
    exit();*/
    if($payload!=""){
        $flag=$flag_redmart=false;
        $product_event_type = $_GET['product_event_type'];
        $payload = str_replace("'", "", $payload);
        $arr_m = json_decode($payload, TRUE);
        $arr_t['product_event_type'] = $product_event_type;
        $data_arr = array_merge($arr_m,$arr_t);
        //echo "<pre>";print_r($data_arr);echo "</pre>";exit();
        $json = json_encode($data_arr);
        $fh = fopen(dirname(__FILE__).'/webhook_response.txt','w');
        fwrite($fh, $json);
        fclose($fh);
        $productID = $data_arr['id'];
        $product_cat = array();
        if($productID){
            echo "<br/>Checking Meta Field for WOO sync allowed. <br/>"; 
			$metafields = getMetaFields($productID, $token = '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');
            foreach($metafields as $metafield){
                if($metafield['key'] == 'connect_woo'){
                    if($metafield['value']){
                        $flag= true;
                    }else{
                        $flag = false;
                    }
                }
                if($metafield['key'] == 'connect_redmart'){
                    if($metafield['value']){
                        $flag_redmart= true;
                    }else{
                        $flag_redmart = false;
                    }
                }
            }  
            $product_cat = getProduct($data_arr['id'], $token = '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');
        }
        if($flag){
            echo "<br/>Woo Meta Field is true and event is ".$product_event_type;
            if($product_event_type == "update"){
                pricode_update_product($data_arr, $product_cat);
            }elseif($product_event_type == "delete"){
                pricode_delete_product($data_arr);
            }elseif($product_event_type == "add"){
                pricode_create_product($data_arr, $product_cat);
            }
        }else{
            if($product_event_type == "update"){
                 $data_product = $data_arr;
                 if($data_product['variants'][0]['sku']){
                    $productId = wc_get_product_id_by_sku($data_product['variants'][0]['sku']);
                    $product = wc_get_product( $productId );
                    if($product){
                        $product->update_meta_data('shopify', '');
                        $product->save(); 
                    }
                }  
            }
        }
        if($flag_redmart){
            echo "<br/>RedMart Meta Field is true and event is ".$product_event_type;
            if($product_event_type == "update" || $product_event_type == "add"){
                foreach($data_arr['variants'] as $variant){ 
                    if($variant['inventory_quantity']==0){
                        $quantity_to_update = 0;
                    }else{
                        $quantity_to_update = $variant['inventory_quantity'];
                    }            
                    updateRedMartInventoryStart($variant['sku'], $quantity_to_update);
                }
            }   
        }
    }    

    function pricode_update_product($data_product, $product_cat){ 
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
                    $product->update_meta_data('shopify', 'Connected');
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
                                $variation->set_manage_stock(true);
								if($variant['inventory_quantity']==0){
									$variation->set_stock_quantity(0);
									$variation->set_stock_status('outofstock');
								}else{
									$variation->set_stock_quantity($variant['inventory_quantity']);
									$variation->set_stock_status('instock');
								}
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
                    pricode_create_product($data_product, $product_cat);
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
                    $product->update_meta_data('shopify', 'Connected');
					$product->set_manage_stock(true);
                    if($data_product['variants'][0]['inventory_quantity']==0){
						$product->set_stock_quantity(0);
						$product->set_stock_status('outofstock');
					}else{
						$product->set_stock_quantity($data_product['variants'][0]['inventory_quantity']);
						$product->set_stock_status('instock');
					}
					
                    $product->save();
                    if(isset($product_cat) && !empty($product_cat))
                    wp_set_object_terms($product->get_id(), $product_cat, 'product_cat');
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
                    pricode_create_product($data_product, $product_cat);
                }
            }else{
                echo "SKU does not exist";
            }
        }  
    }

    function pricode_create_product($data_product, $product_cat){ 
        if($data_product['id']){
            $desc = $data_product['body_html'];
            $title = $data_product['title'];    
            if(count($data_product['variants'])>1){
                echo "<br/>Adding variant product more then one variant<br/>";
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
                    $product->update_meta_data('shopify', 'Connected');
                    $product->save();
                    if(isset($product_cat) && !empty($product_cat))
                    wp_set_object_terms($product->get_id(), $product_cat, 'product_cat');
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
                    echo "<br/>Adding simple product only one variant<br/>";
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
                    $product->set_manage_stock(true);
					if($data_product['variants'][0]['inventory_quantity']==0){
						$product->set_stock_quantity(0);
						$product->set_stock_status('outofstock');
					}else{
						$product->set_stock_quantity($data_product['variants'][0]['inventory_quantity']);
						$product->set_stock_status('instock');
					}
                    $product->update_meta_data('shopify', 'Connected');
                    $product->save();
                    if(isset($product_cat) && !empty($product_cat))
                    wp_set_object_terms($product->get_id(), $product_cat, 'product_cat');
                    if(isset($data_product['vendor']) && $data_product['vendor']!="")
                    wp_set_object_terms($product->get_id(), array($data_product['vendor']), 'product_brand');
                    if($data_product['tags'] !=""){
                        $tags = explode(',', $data_product['tags']);
                        wp_set_object_terms($product->get_id(), $tags, 'product_tag');
                    }  
                }else{
                     echo "<br/>SKU does not exist";
                }
            }   
            if(isset($product)){     
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
                echo "<br/>Not Added";
                exit();
            }                   
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
		$variation->set_manage_stock(true);
        if( ! empty($data->inventory_quantity) && $data->inventory_quantity>0){
            $variation->set_stock_quantity( $data->inventory_quantity );
            $variation->set_stock_status('instock');
        } else {
			$variation->set_stock_quantity(0);
			$variation->set_stock_status('outofstock');
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
