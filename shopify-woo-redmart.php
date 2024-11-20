 <?php 
    /* Template Name: Shopify Woo*/
    ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
    echo "<pre>";//$metafields = getMetaFields('7236416176322', $token = 'xxxxxx');print_r($metafields);exit();
    function getImages($productID, $token){
        $token = getShopifytoken();
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
    function getLocation($id, $token){
        $token = getShopifytoken();
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
    function getMetaFields($productID, $token){
        $token = getShopifytoken();
        $flag = false;
        //echo $apiUrl = 'https://zegerman.myshopify.com/admin/api/2024-10/products/'.$productID.'/metafields.json';
        $apiUrl = "https://zegerman.myshopify.com/admin/api/2024-10/graphql.json";
        $post = '{
"query": "query { product(id: \"gid://shopify/Product/'.$productID.'\") {metafields(first: 5) { edges { node { key value description } } } } }"
}';                
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
        //echo "<pre>"; print_r($datas);print_r($datas['data']['product']['metafields']['edges']);exit();
        return $datas['data']['product']['metafields']['edges'];
        //echo "Meta Field is checked to: ".$flag;
        //return $flag; 
    }
    function getProduct($id, $token){
        $token = getShopifytoken();
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
        $token = getShopifytoken();
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
   /*$payload = '{"admin_graphql_api_id":"gid:\/\/shopify\/Product\/9685105738045","body_html":"<p>New testing.....<\/p>","created_at":"2024-10-03T15:26:14+08:00","handle":"test-product","id":9685105738045,"product_type":"Baby Care","published_at":null,"template_suffix":"","title":"Test Product 1-1","updated_at":"2024-11-07T11:45:10+08:00","vendor":"Autan","status":"draft","published_scope":"web","tags":"","variants":[{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/49448078311741","barcode":null,"compare_at_price":null,"created_at":"2024-10-03T15:26:15+08:00","id":49448078311741,"inventory_policy":"deny","position":2,"price":"9.90","product_id":9685105738045,"sku":"testproduct","taxable":true,"title":"Default Title","updated_at":"2024-11-07T11:45:10+08:00","option1":"Default Title","option2":null,"option3":null,"image_id":null,"inventory_item_id":51490254520637,"inventory_quantity":1,"old_inventory_quantity":1}],"options":[{"name":"Title","id":12112957440317,"product_id":9685105738045,"position":1,"values":["Default Title"]}],"images":[{"id":49548944671037,"product_id":9685105738045,"position":1,"created_at":"2024-10-03T16:22:10+08:00","updated_at":"2024-10-03T16:22:12+08:00","alt":null,"width":216,"height":144,"src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0580\/1449\/9010\/files\/regular_1095936191980050145_1845309323.jpg?v=1727943732","variant_ids":[],"admin_graphql_api_id":"gid:\/\/shopify\/ProductImage\/49548944671037"}],"image":{"id":49548944671037,"product_id":9685105738045,"position":1,"created_at":"2024-10-03T16:22:10+08:00","updated_at":"2024-10-03T16:22:12+08:00","alt":null,"width":216,"height":144,"src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0580\/1449\/9010\/files\/regular_1095936191980050145_1845309323.jpg?v=1727943732","variant_ids":[],"admin_graphql_api_id":"gid:\/\/shopify\/ProductImage\/49548944671037"},"media":[{"id":40907792908605,"product_id":9685105738045,"position":1,"created_at":"2024-10-03T16:22:10+08:00","updated_at":"2024-10-03T16:22:12+08:00","alt":null,"status":"READY","media_content_type":"IMAGE","preview_image":{"width":216,"height":144,"src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0580\/1449\/9010\/files\/regular_1095936191980050145_1845309323.jpg?v=1727943732","status":"READY"},"variant_ids":[],"admin_graphql_api_id":"gid:\/\/shopify\/MediaImage\/40907792908605"}],"variant_gids":[{"admin_graphql_api_id":"gid:\/\/shopify\/ProductVariant\/49448078311741","updated_at":"2024-11-07T03:45:10.000Z"}],"has_variants_that_requires_components":false,"category":{"admin_graphql_api_id":"gid:\/\/shopify\/TaxonomyCategory\/aa-2-14-3","name":"Hair Extensions","full_name":"Apparel & Accessories > Clothing Accessories > Hair Accessories > Hair Extensions"},"product_event_type":"update"}';
    //print_r(json_decode($payload, TRUE));
    //exit();
    /*$product_event_type = $_GET['product_event_type'] = "update";
    $payload = str_replace("'", "", $payload);
    $arr_m = json_decode($payload, TRUE);
    print_r($arr_m);
    getProduct($arr_m['id'], $token = 'xxxxxx');
    getLocation($arr_m['id'], $token = 'xxxxxx');
    exit();
    foreach($arr_m['variants'] as $variant){ 
        $arr_inventory_ids[] = $variant['inventory_item_id'];
    }
    echo $inventory_ids = implode(', ', $arr_inventory_ids);
    getInventoryInfo($inventory_ids, $token = 'xxxxxx');
    //pricode_update_product($arr_m);
    exit();*/
    if($payload!=""){
        $flag=$flag_redmart=false;
        $product_event_type = $_GET['product_event_type'] = "update";
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
        $rpc_threshold = 0;
        if($productID){
            echo "<br/>Checking Meta Field for WOO sync allowed. <br/>"; 
            $metafields = getMetaFields($productID, $token = 'xxxxxx');
			print_r($metafields);
            foreach($metafields as $metafield){
                if($metafield['node']['key'] == 'connect_woo'){
                    if($metafield['node']['value']){
                        $flag= true;
                    }else{
                        $flag = false;
                    }
                }
                if($metafield['node']['key'] == 'rpc_code'){
                    $flag_redmart= true;
                    $rpc_code = $metafield['node']['value'];
                }
                if($metafield['node']['key'] == 'rpc_threshold'){
                    if($metafield['node']['value'] !="" && $metafield['node']['value'] > 0){
                        $rpc_threshold = $metafield['node']['value'];
                    }else{
                        $rpc_threshold = 0;
                    }
                }
            }  
            $product_cat = getProduct($data_arr['id'], $token = 'xxxxxx');
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
                    if($productId!="" && $productId>0){ 
                        $product = wc_get_product( $productId );
                        if($product){
                            $product->update_meta_data('shopify', '');
                            $product->save(); 
                        }
                    }
                }  
            }
        }
		echo "<br/>wwwww".$flag_redmart;
            if($arr_m['variants'][0]['sku']){
				$inventory_quantity = $arr_m['variants'][0]['inventory_quantity'];
                echo $productId = wc_get_product_id_by_sku($arr_m['variants'][0]['sku']);
                if($productId!="" && $productId>0 && $rpc_code != ""){
					echo "<br/>Via Woo connection with redmart<br/>";
                    $product = wc_get_product( $productId );
                    $product->update_meta_data('rpc_code', "$rpc_code");
                    $rpc_pre_lot = $product->get_meta('rpc_pre_lot');
                    if($rpc_pre_lot ==""){
                        $redmart_inventory_array = getRedMartInventory($rpc_code);
                        $redmart_inventory_json = json_encode($redmart_inventory_array);
                        $product->update_meta_data('rpc_pre_lot', "$redmart_inventory_json");
                    }else{
                        $redmart_inventory_json = $rpc_pre_lot;
                    }
                    $rpc_pre_lot = $product->get_meta('rpc_pre_lot');
                    $product->update_meta_data('rpc_pre_lot', "$rpc_code");
                    $product->save();
                }else{
					echo "<br/>Direct connection with redmart<br/>";
					if($rpc_code != ""){
						update_option('rpc_code_'.$arr_m['variants'][0]['sku'], "$rpc_code");                    
						echo $redmart_inventory_json = get_option('rpc_pre_lot_'.$arr_m['variants'][0]['sku']);
						echo "<br/>333333";
						if($redmart_inventory_json ==""){  
							echo "<br/>First time connection";
							$redmart_inventory_array = getRedMartInventory($rpc_code);
							$quantityScheduledForPickup = $redmart_inventory_array['quantityScheduledForPickup'];						
							if($quantityScheduledForPickup>0){
								$to_update_shopify = -$quantityScheduledForPickup;
								$locationID = 63562907842;
								$data = getShopifyInventoryBySKU($arr_m['variants'][0]['sku']);
								$shopify_inventory_item_id = $data['shopify_inventory_item_id'];
								updateShopifyInventory($locationID, $shopify_inventory_item_id, $to_update_shopify);
								$inventory_quantity = $inventory_quantity - $quantityScheduledForPickup ;
							}
							if($inventory_quantity>=$rpc_threshold){
								$quantityToUpdate = $inventory_quantity-$rpc_threshold;
							}else{
								$quantityToUpdate=0;
							} 
							updateRedMartInventory($quantityToUpdate, $rpc_code);
							$redmart_inventory_array = getRedMartInventory($rpc_code);
							$redmart_inventory_json = json_encode($redmart_inventory_array);
							update_option('rpc_pre_lot_'.$arr_m['variants'][0]['sku'], $redmart_inventory_json);
						}else{
							if($rpc_threshold>0){}else{$rpc_threshold=0;}
							$inventory_diff = 0;
							// get recent redmart inventory to check any new/cancel order while this webhook is executed
							$redmart_inventory_array = getRedMartInventory($rpc_code);
							$quantityAvailableForSale = $redmart_inventory_array['quantityAvailableForSale'];
							if($quantityAvailableForSale>0){
								$inventory_diff = $quantityAvailableForSale - $inventory_quantity;
							}
							if($inventory_diff!=0){
								// if inventory difference is found first update shopify then apply change from shopify to redmart
								updateShopifyInventory($locationID, $shopify_inventory_item_id, $inventory_diff);
								$inventory_quantity = $inventory_quantity+$inventory_diff;
							}
							
							$redmart_inventory_json = json_decode($redmart_inventory_json, true);
							$quantityToUpdate = $inventory_quantity + $redmart_inventory_json['quantityScheduledForPickup']-$rpc_threshold;
							updateRedMartInventory($quantityToUpdate, $rpc_code);	
							
							$redmart_inventory_array = getRedMartInventory($rpc_code);
							$redmart_inventory_json = json_encode($redmart_inventory_array);
							update_option('rpc_pre_lot_'.$arr_m['variants'][0]['sku'], $redmart_inventory_json);
						}
					}else{
                        $rpc_code_old = get_option('rpc_code_'.$arr_m['variants'][0]['sku']);
                    	$redmart_inventory_array = getRedMartInventory($rpc_code_old);
						$redmart_inventory_json = json_encode($redmart_inventory_array);
						$quantityScheduledForPickup = $redmart_inventory_array['quantityScheduledForPickup'];						
						if($quantityScheduledForPickup>0){
							$to_update_shopify = $quantityScheduledForPickup;
						}						
						$locationID = 63562907842;
						$data = getShopifyInventoryBySKU($arr_m['variants'][0]['sku']);
						$shopify_inventory_item_id = $data['shopify_inventory_item_id'];
						updateShopifyInventory($locationID, $shopify_inventory_item_id, $to_update_shopify);
						update_option('rpc_pre_lot_'.$arr_m['variants'][0]['sku'], "");
						update_option('rpc_code_'.$arr_m['variants'][0]['sku'], ""); 
					}
                }
            }
            echo "<pre>";
            $redmart_inventory_json = json_decode($redmart_inventory_json, true);
			echo "<br/>";
			print_r($redmart_inventory_json);            
            echo "<br/>RedMart Meta new inventory is  and shopify inventory_quantity is ".$inventory_quantity;
            exit(); 
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
                echo $data_product['variants'][0]['sku'];
                echo $productId = wc_get_product_id_by_sku($data_product['variants'][0]['sku']);
                $product = wc_get_product( $productId );                
                if($product){
                    $product_parent=$product->get_parent_id();
                    if($product_parent==0){
                        $variation = $product;
                        $variant = $data_product['variants'][0];
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
                        if($variant['compare_at_price'] == "" || $variant['compare_at_price']==0)                               {
                            $variation->set_regular_price($variant['price']);
                        }  
                        $variation->set_attributes($attrb);
                        $variation->set_parent_id($product_parent);
                        $variation->save();
                        $product = wc_get_product($product_parent);
                        $product->save();
                    }else{
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
                        $images_urls = getImages($data_product['id'], $token = 'xxxxxx'); 
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
                    }
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
                $images_urls = getImages($data_product['id'], $token = 'xxxxxx'); 
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
