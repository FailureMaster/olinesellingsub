<?php
/**
* Plugin Name: AppMySite
* Plugin URI: https://www.appmysite.com
* Description: This plugin enables WordPress & WooCommerce users to sync their websites with native iOS and Android apps, created on <a href="https://www.appmysite.com/"><strong>www.appmysite.com</strong></a>
* Version: 2.6.0
* Author: AppMySite
* Text Domain: appmysite
* Author URI: https://appmysite.com
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
**/

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die( 'No script kiddies please!');
	}

	// This will show warning to all where wordpress version is below minimum requirement.
	global $wp_version;
	if ( $wp_version <= 4.9 ) {		
		function wo_incompatibility_with_wp_version() {			 
			?>
			<div class="notice notice-error">
				<p><?php _e( 'AppMySite requires that WordPress 4.9 or greater be used. Update to the latest WordPress version.', 'appmysite' ); ?>
					<a href="<?php echo admin_url( 'update-core.php' ); ?>"><?php _e( 'Update Now', 'appmysite' ); ?></a></p>
			</div>
			<?php  
		}

		add_action( 'admin_notices', 'wo_incompatibility_with_wp_version' );		
	}
	
	add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v3', '/ams-menu', array(
			'methods' => 'GET',
			'callback' => 'ams_get_menu_items',  // array($this,'custom_wc_order_statuses')
		) );
	} );

	add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v3', '/ams-menu-names', array(
			'methods' => 'GET',
			'callback' => 'ams_get_menu_names',  // array($this,'custom_wc_order_statuses')
		) );
	} );
	
	add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v3', '/ams-login', array(
			'methods' => 'POST',
			'callback' => 'ams_login',
		) );
	} );	
		
	add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v3', '/ams-verify-user', array(
			'methods' => 'POST',
			'callback' => 'ams_verify_user',
		) );
	} );
	
	add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v3', '/ams-profile-meta', array(
			'methods' => 'GET',
			'callback' => 'ams_get_profile_meta',
		) );
	} );
	
	add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v3', '/ams-order-payment-url', array(
			'methods' => 'POST',
			'callback' => 'ams_get_order_payment_url',
		) );
	} );	

	add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v3', '/ams-order-total', array(
			'methods' => 'POST',
			'callback' => 'ams_get_order_total',
		) );
	} );
	
	add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v3', '/ams-send-password-reset-link', array(
			'methods' => 'POST',
			'callback' => 'ams_send_password_reset_link',
		) );
	} );

	add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v3', '/ams-applicable-shipping-method', array(
			'methods' => 'POST',
			'callback' => 'ams_applicable_shipping_method',
		) );
	} );
	
	add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v3', '/ams-product-search', array(
			'methods' => 'GET',
			'callback' => 'ams_product_search',
		) );
	} );
	
	add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v3', '/ams-verify-cart-items', array(
			'methods' => 'POST',
			'callback' => 'ams_verify_cart_items',
		) );
	} );

	add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v3', '/ams-categories', array(
			'methods' => 'GET',
			'callback' => 'ams_categories',
		) );
	} );

	add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v3', '/ams-post-categories', array(
			'methods' => 'GET',
			'callback' => 'ams_post_categories',
		) );
	} );	
	add_filter( 'user_has_cap', 'ams_allow_payment_without_login', 10, 3 );   // Allow customer to pay the order without login.

####################################################

	add_filter( 'woocommerce_get_settings_products', 'add_subtab_settings', 10, 2 );
	function add_subtab_settings( $settings ) {
    $current_section = get_option( 'woocommerce_default_catalog_orderby' );
	
		if ( isset($current_section) ) {
			$settings[] = array(
										'name'     => __( 'AMS WC Default Catalog Orderby Settings', 'woocommerce' ),
										'id'       => 'woocommerce_default_catalog_orderby',
										'label'     => 'Woocommerce Default Catalog Orderby',
										'type'     => 'select',
										'desc' => __( 'This setting determines the sorting order of products in the catalog.', 'woocommerce' ),
										'desc_tip' =>  true,
										'options'  => array(
														  'price'        => __( 'Sort by price (asc)', 'woocommerce' ),
														  'date'       => __( 'Sort by most recent', 'woocommerce' ),
														  'rating'  => __( 'Average rating', 'woocommerce' ),
														  'popularity' => __( 'Popularity (sales)', 'woocommerce' ),
														  'menu_order' => __( 'Default sorting (custom ordering + name)', 'woocommerce' ),
														  'price-desc' => __( 'Sort by price (desc)', 'woocommerce' )
														),
										'default' =>'',
										'value'  => get_option('woocommerce_default_catalog_orderby'),

									);		
			return $settings;
		} else {
			return $settings; // If not, return the standard settings
		}	
	}
################### For Blogs ######################

	register_rest_field( 
    'post', // Where to add the field (Here, blog posts. Could be an array)
    'featured_image_src', // Name of new field (You can call this anything)
    array(
        'get_callback'    => 'ams_get_image_src',
        'update_callback' => null,
        'schema'          => null,
         )
    );
    register_rest_field( 
        'post',
        'blog_images',
        array(
            'get_callback'    => 'ams_get_images_urls',
            'update_callback' => null,
            'schema'          => null,
        )
    );

##########################################################################################################
    register_rest_field( 
        'product',
        'ams_default_variation_id',
        array(
            'get_callback'    => 'ams_get_default_variant',
            'update_callback' => null,
            'schema'          => null,
        )
    );
	function ams_get_default_variant( $object, $field_name, $request ) {
	  
	  $product = wc_get_product($object['id']);
		if( $product->is_type('variable') ){
			$default_attributes = $product->get_default_attributes();
			if(!empty($default_attributes)){
				foreach($product->get_available_variations() as $variation_values ){
					foreach($variation_values['attributes'] as $key => $attribute_value ){
						$attribute_name = str_replace( 'attribute_', '', $key );
						$default_value = $product->get_variation_default_attribute($attribute_name);
						if( $default_value == $attribute_value ){
							$is_default_variation = true;
						} else {
							$is_default_variation = false;
							break;
						}
					}
					if( $is_default_variation ){
						$variation_id = $variation_values['variation_id'];
						break;
					}
				}
				return $variation_id;
			}else{return 0;}
		}else{
			return 0;
		}	  

	}
##########################################################################################################
    register_rest_field( 
        'product',
        'ams_product_discount_percentage',
        array(
            'get_callback'    => 'ams_get_product_discount_percentage',
            'update_callback' => null,
            'schema'          => null,
        )
    );
	function ams_get_product_discount_percentage( $object, $field_name, $request ) {
	  
	    $product = wc_get_product($object['id']);
			if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')){ 
			$regular_price = (float) $product->get_regular_price(); // Regular price
			$sale_price = (float) $product->get_price();
			$saving_price = wc_price( $regular_price - $sale_price );
			$precision = 2;
			$discount_percentage = round( 100 - ( $sale_price / $regular_price * 100 ), 2 );
			return $discount_percentage;
	    }else{
			return 0.00;
		}	  

	}	
##########################################################################################################

	function prepare_product_images($response, $post, $request) {
		global $_wp_additional_image_sizes;

		if (empty($response->data)) {
			return $response;
		}

		foreach ($response->data['images'] as $key => $image) {
				$image_info = wp_get_attachment_image_src($image['id'], 'thumbnail');
				$response->data['images'][$key]['thumbnail'] = $image_info[0]; 
				$image_info = wp_get_attachment_image_src($image['id'], 'medium');
				$response->data['images'][$key]['medium'] = $image_info[0];     
		}
		return $response;

	}
	add_filter("woocommerce_rest_prepare_product_object", "prepare_product_images", 10, 3);

##########################################################################################################

	add_filter( 'woocommerce_rest_prepare_shop_order_object', 'ams_rest_apply_coupon', 10, 3 );
	function ams_rest_apply_coupon( $response, $object, $request ) {		
				//this section is to apply coupon					
				foreach ( $request->get_param( 'coupon_lines' ) as $item ) {
					if ( is_array( $item ) ) {
						if ( isset( $item['id'] ) ) {
							if ( !isset( $item['code'] ) ) {
								throw new WC_REST_Exception( 'woocommerce_rest_invalid_coupon', __( 'Coupon code is required.', 'woocommerce' ), 400 );
							}
							$order = wc_get_order( $response->data['id'] );
							$results = $order->apply_coupon( wc_clean( $item['code'] ) );
							
							if ( is_wp_error( $results ) ) {
								throw new WC_REST_Exception( 'woocommerce_rest_' . $results->get_error_code(), $results->get_error_message(), 400 );
							}
							return $response;
						}
					}
				}
				//this section is to add extra field into order api
				$ams_order_checkout_url = ( $value = esc_url( $object->get_checkout_payment_url()) ) ? $value : ''; 
				$response->data['order_checkout_payment_url'] = html_entity_decode($ams_order_checkout_url);	
				
				foreach($response->data['line_items'] as $key => $lineItem) {					
					$product_id = $lineItem['product_id'];
					$medium_url = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'thumbnail' );					
					$response->data['line_items'][$key]['ams_order_thumbnail'] = $medium_url[0]; 						
				} 
				
			return $response;
		
	}
##########################################################################################################
	
	function ams_get_image_src( $object, $field_name, $request ) {
	  $feat_img_array = wp_get_attachment_image_src(
		$object['featured_media'], // Image attachment ID
		'large',  // Size.  Ex. "thumbnail", "large", "full", etc..
		false // Whether the image should be treated as an icon.
	  );
	  return $feat_img_array[0];
	}
##########################################################################################################	
	function ams_get_images_urls( $object, $field_name, $request ) {
		$medium = wp_get_attachment_image_src( get_post_thumbnail_id( $object['id'] ), 'medium' );
		$medium_url = $medium['0'];

		$large = wp_get_attachment_image_src( get_post_thumbnail_id( $object['id'] ), 'large' );
		$large_url = $large['0'];

		return array(
			'medium' => $medium_url,
			'large'  => $large_url,
		);
	}
##########################################################################################################	
	function ams_get_menu_names() {
					
		$nav_menu_locations = get_theme_mod('nav_menu_locations');	
		
		return(rest_ensure_response($nav_menu_locations));
		
	}
##########################################################################################################
	function ams_get_menu_items(WP_REST_Request $request) {
		
		$menu_name = "primary-menu"; //primary-menu //top

		if(isset($request['menu_name'])){
		 $menu_name = $request["menu_name"];	
		}							
		$nav_menu_locations = get_theme_mod('nav_menu_locations');		
		$menu = wp_get_nav_menu_object($nav_menu_locations[$menu_name]);//get the menu object   // primary-menu		
		$nav_menu_items = wp_get_nav_menu_items($menu->slug);
		return(rest_ensure_response($nav_menu_items));
		
	}
##########################################################################################################	
	function ams_categories() {

		$orderby = 'name';
		$order = 'asc';
		$hide_empty = true ;
		$cat_args = array(
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty,
		);
		 
		$product_categories = array_values(get_terms( 'product_cat', $cat_args ));	
		$array_product_categories_items = json_decode(json_encode($product_categories), true);
		if(empty($array_product_categories_items)){return rest_ensure_response($array_product_categories_items);}
		$category_tree = ams_build_category_tree($array_product_categories_items, 'parent', 'term_id');			
		return rest_ensure_response($category_tree);
		
	}
	
	function ams_post_categories() {

		$orderby = 'name';
		$order = 'asc';
		$hide_empty = true ;
		$cat_args = array(
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty,
		);
		 
		$product_categories = array_values(get_terms( 'category', $cat_args ));	
		$array_product_categories_items = json_decode(json_encode($product_categories), true);
		if(empty($array_product_categories_items)){return rest_ensure_response($array_product_categories_items);}
		$category_tree = ams_build_category_tree($array_product_categories_items, 'parent', 'term_id');			
		return rest_ensure_response($category_tree);
		
	}	
	
##########################################################################################################	
	function ams_verify_cart_items(WP_REST_Request $request) {
		
		$params = $request->get_params();	
		$validate = ams_basic_validate($params,['line_items']);
		if($validate!=true){return $validate;}
				
		$line_items = $params['line_items'];
		//print_r($line_items);die;
		$result = [];
		foreach($line_items as $key=>$value){
			if(array_key_exists("variation_id",$value)){
								
                $variation  = wc_get_product( $value['variation_id'] );
				
				if($variation){
					$result[$key]['product_id'] = $variation->get_id();
					$result[$key]['variation_id'] = $value['variation_id'];
					$result[$key]['type'] = $variation->get_type();
					$result[$key]['status'] = $variation->get_status();
					$result[$key]['price'] = $variation->get_price();
					$result[$key]['regular_price'] = $variation->get_regular_price();
					$result[$key]['sale_price'] = $variation->get_sale_price();								
					$result[$key]['manage_stock'] = $variation->get_manage_stock();
					$result[$key]['stock_quantity'] = $variation->get_stock_quantity();
						if ( $result[$key]['stock_quantity'] == null ) {
							$result[$key]['stock_quantity'] = '';
						}
					$result[$key]['stock_status'] = $variation->get_stock_status();
					$result[$key]['on_sale'] = $variation->is_on_sale();
						if ( 1 == $result[$key]['on_sale'] ) {
							
							$result[$key]['on_sale'] = true; //echo('is 1'); die;
						}else{  $result[$key]['on_sale'] = false; }//echo(false); die;}					
				}
			}
			else{
			//get product details
				$product = wc_get_product($value['product_id']);
				if($product){
					$result[$key]['product_id'] = $product->get_id();
					$result[$key]['type'] = $product->get_type();
					$result[$key]['status'] = $product->get_status();
					$result[$key]['get_price'] = $product->get_price();
					$result[$key]['get_regular_price'] = $product->get_regular_price();
					$result[$key]['get_sale_price'] = $product->get_sale_price();								
					$result[$key]['manage_stock'] = $product->get_manage_stock();
					$result[$key]['stock_quantity'] = $product->get_stock_quantity();
						if ( $result[$key]['stock_quantity'] == null ) {
							$result[$key]['stock_quantity'] = '';
						}
					$result[$key]['stock_status'] = $product->get_stock_status();
					$result[$key]['on_sale'] = $product->is_on_sale();	
						if ( 1 == $result[$key]['on_sale'] ) {
							$result[$key]['on_sale'] = true;
						}else{$result[$key]['on_sale'] = false;}					
				}				
			}			
		}
		
		return rest_ensure_response(['line_items'=>$result]);						
	}
##########################################################################################################
	function ams_product_search(WP_REST_Request $request) {
		
		
	    $param = $request->get_params();	
		$category = $param['category']; 		
		$filters  = $param['filter']; 
		$per_page = $param['per_page']; 
		$page   = $param['page']; 
		$order    = $param['order']; 
		$orderby  = $param['orderby']; 
		$featured  = $param['featured']; 
		$on_sale  = $param['on_sale']; 
		$min_price  = $param['min_price'];
		$max_price  = $param['max_price'];	
		$stock_status  = $param['stock_status'];		
		$output = [];
		// Use default arguments.
		$args = [
		  'post_type'      => 'product',
		  'posts_per_page' => get_option( 'posts_per_page' ),
		  'post_status'    => 'publish',
		  'paged'          => 1,
		  
		];
		// Posts per page.
		if ( ! empty( $per_page ) ) {			
		  $args['posts_per_page'] = $per_page;
		}
		// Pagination, starts from 1.
		if ( ! empty( $page ) ) {
		  $args['paged'] = $page;
		}

		// Order condition. ASC/DESC.
		if ( ! empty( $order ) ) {
		  $args['order'] = $order;
		}
		// Order condition. ASC/DESC.		
		if ( ! empty( $orderby ) ) {
		  if ( $orderby == 'price' ) {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_price';
								
		  }elseif($orderby == 'popularity'){   //For Popularity case, the sort order will always be desc.
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = 'total_sales';

		  }else{
			$args['orderby'] = $orderby;
		  }
		}
		
		if ( ! empty( $featured ) ) {
		    if($featured==true){				
		  	$args['tax_query'][] = [
			  'taxonomy' => 'product_visibility',
			  'field'    => 'name',
			  'terms'    => 'featured',
			];}
		}
		
		if ( ! empty( $on_sale ) ) {
		    if($on_sale==true){	
	
				$args['meta_query'][] = [
					'key'           => '_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				];											
			}
		} 
		if ( ! empty( $stock_status ) ) {	
				$args['meta_query'][] = [
					'key'           => '_stock_status',
					'value'         => $stock_status,
				];											
		}				
		  if ( isset( $min_price ) || isset( $max_price ) ) {
			  
			$price_request = [];
			if ( isset( $min_price ) ) {
			  $price_request['min_price'] = $min_price;
			}

			if ( isset( $max_price ) ) {
			  $price_request['max_price'] = $max_price;
			}
			$args['meta_query'][] = wc_get_min_max_price_meta_query( $price_request );												
		  }


		if ( ! empty( $category ) || ! empty( $filters ) ) {
			
		  $args['tax_query']['relation'] = 'AND';
		  if ( ! empty( $category ) ) {
			$args['tax_query'][] = [
			  'taxonomy' => 'product_cat',
			  'field'    => 'id',
			  'terms'    => explode( ',', $category ),        //[ $category ],
			];
		  }
		  
		  if ( ! empty( $filters ) ) {
			foreach ( $filters as $filter_key => $filter_value ) {
			  if ( $filter_key === 'min_price' || $filter_key === 'max_price' ) {
				continue;
			  }
			  $args['tax_query'][] = [
				'taxonomy' => $filter_key,
				'field'    => 'term_id',
				'terms'    => explode( ',', $filter_value ),
			  ];
			}
		  }
		}
		
		$the_query = new \WP_Query( $args );

		if ( ! $the_query->have_posts() ) {			
		  return $output;
		}
		while ( $the_query->have_posts() ) {			
		$the_query->the_post();		  
        $product_ids[] = $the_query->post->ID; 
		$output[] = get_the_title();
		}
		wp_reset_postdata();

		$request = new WP_REST_Request( 'GET', '/wc/v3/products' );
		$parameters = [ 'include' => $product_ids];
		if(!empty($order)){$parameters += ["order" => $order]; }
		if(!empty($orderby)){$parameters += ["orderby" => $orderby]; }
		if(!empty($per_page)){$parameters += ["per_page" => $per_page]; } 		
		$request->set_query_params($parameters);
		$response = rest_do_request( $request );
		$server = rest_get_server();
		$data = $server->response_to_data( $response, false ); 	
		return rest_ensure_response($data);						
	}
		
##########################################################################################################	
	function ams_login(WP_REST_Request $request) { 

		$req = $request->get_json_params();
		
		$validate = ams_basic_validate($req,['username','password']);
		if($validate!=true){return $validate;}
									$wp_version = get_bloginfo('version');
									$user = wp_authenticate(sanitize_email($req["username"]),sanitize_text_field($req["password"]));  //  htmlspecialchars
								
									if(isset($user->errors)){										
										$error_message = strip_tags(ams_convert_error_to_string($user->errors));
										$error = new WP_Error(); $error->add('message', __($error_message.""));return $error;																																								
									}elseif(isset($user->data)){
										$user->data->user_pass= "";
										$user->data->wp_version= $wp_version;
										return rest_ensure_response($user->data);																			
									}else{
										$error = new WP_Error(); $error->add('message', __("Something went wrong. Please contact support."));return $error;									
		}
		$error = new WP_Error(); $error->add('message', __("Something went wrong. Please contact support")); return $error;										
	}

##########################################################################################################
	
	function ams_verify_user(WP_REST_Request $request) { 

		$req = $request->get_json_params();
		
		$validate = ams_basic_validate($req,['username']);
		if($validate!=true){return $validate;}

								    $user = get_user_by('email',$req["username"]) ; //| ID | slug | email | login.								   								
									if(isset($user->errors)){										
										$error_message = strip_tags(convertErrorToString($user->errors));										
										$error = new WP_Error(); $error->add('message', __($error_message.""));return $error;																																								
									}elseif(isset($user->data)){
										$user->data->user_pass= "";
										return rest_ensure_response($user->data);																			
									}else{
										return rest_ensure_response([]); 
									}
		$error = new WP_Error(); $error->add('message', __("Something went wrong. Please contact support.")); return $error;										
	}

##########################################################################################################
	function ams_get_profile_meta(WP_REST_Request $request) {
		
		if(isset($request['id'])){$user_id = sanitize_text_field( $request['id'] ); }		
		$validate = ams_basic_validate($req,['id']);
		if($validate!=true){return $validate;}			
		$user_meta_data = get_user_meta($user_id, 'wp_user_avatar', true); 
		$profile_image_full_path = wp_get_attachment_image_src($user_meta_data);				
		return rest_ensure_response(['wp_user_avatar'=>$profile_image_full_path]);						
	}

##########################################################################################################
	
##########################################################################################################
	function ams_allow_payment_without_login( $allcaps, $caps, $args ) {

		if ( !isset( $caps[0] ) || $caps[0] != 'pay_for_order' )
			return $allcaps;
		if ( !isset( $_GET['key'] ) )
			return $allcaps;
		$order = wc_get_order( $args[2] );
		if( !$order )
			return $allcaps; 
		$order_key = $order->get_order_key();
		$order_key_check = sanitize_text_field($_GET['key']);
		$allcaps['pay_for_order'] = ( $order_key == $order_key_check );
		return $allcaps;
	}	
##########################################################################################################	
	function ams_get_order_payment_url(WP_REST_Request $request) {

		$req = $request->get_json_params();		
		$validate = ams_basic_validate($req,['order_id']);
		if($validate!=true){return $validate;}		
		$order_id = sanitize_text_field($req["order_id"]);
		$order = wc_get_order($order_id);  // Returns WC_Product|null|false		
		if(!isset($order) || $order==false){$error = new WP_Error(); $error->add('message', __("The order ID appears to be invalid. Please try again."));return $error;}  // Verify Valid Order ID
		$pay_now_url = esc_url( $order->get_checkout_payment_url());
		return(rest_ensure_response(html_entity_decode($pay_now_url)));
	}

##########################################################################################################	
	function ams_send_password_reset_link(WP_REST_Request $request) {
			$req = $request->get_json_params();
			$validate = ams_basic_validate($req,['email']);
			if($validate!=true){return $validate;}			
			$email = sanitize_email($req['email']);			
			$user = get_user_by('email', $email);
				if(!$user){$error = new WP_Error(); $error->add('message', __("The email address appears to be incorrect. Please try again."));return $error;}				
			$firstname = $user->first_name;
			$email = $user->user_email;
			$adt_rp_key = get_password_reset_key( $user );
			$user_login = $user->user_login;
			$rp_link = '<a href="' . wp_lostpassword_url()."?key=$adt_rp_key&login=" . rawurlencode($user_login) . '">' . wp_lostpassword_url()."?key=$adt_rp_key&login=" . rawurlencode($user_login) . '</a>';

			if ($firstname == "") $firstname = "Customer";
			$message = "Hi ".$firstname.",<br>";
			$message .= "Looks like you�ve requested a new password. Click below to reset it. <br>";
			$message .= $rp_link.'<br>';
			$message .= "You can safely ignore this email if you do not wish to reset your password.<br><br><br>";
			$message .= "Thanks.<br>";
			
			   $subject = __("Reset Password on ".get_bloginfo( 'name'));
			   $headers = array();

			   add_filter( 'wp_mail_content_type', function( $content_type ) {return 'text/html';});
			   $headers[] = 'From: '.get_bloginfo('name').'<'.get_bloginfo('admin_email').'>'."\r\n";
			   wp_mail( $email, $subject, $message, $headers);
			   remove_filter( 'wp_mail_content_type', 'set_html_content_type' );			

			return(rest_ensure_response(['message'=>"Reset Password link sent successfully!"]));

	}
	
##########################################################################################################	
	function ams_applicable_shipping_method(WP_REST_Request $request) {
			$req = $request->get_json_params();			
			$validate = ams_basic_validate($req,['shipping','line_items']);
			if($validate!=true){return $validate;}			
		
		$product_id = sanitize_text_field( $req['product_id'] );
		$shipping = $req['shipping'];
		$line_items = $req['line_items'];
		$free_shipping_by_coupon = rest_sanitize_boolean( $req['free_shipping_by_coupon'] );
		$args = array(
			'include' => array_column( $line_items,'product_id' )
		);
		$products = wc_get_products( $args );
		$cartTotal=0; ####
		foreach($products as $key=>$product){		
		 $cartTotal += (wc_get_price_excluding_tax($product)*$line_items[$key]['quantity']); // price without VAT		
		}
		$stateCode=sanitize_text_field( $shipping['state'] );
		$postcode=sanitize_text_field( $shipping['postcode'] );
		$countryCode=sanitize_text_field( $shipping['country'] ); 
		$newShippingMethods=[];		
		$shipping_class_ids=array_column($products,'shipping_class_id');
		$product_qty=array_column($line_items,'quantity');
	
		################################GET REQUEST##########################################
		$request_a = new WP_REST_Request( 'GET', '/wc/v3/shipping/zones' );
		$response_a = rest_do_request($request_a);
		$server_a = rest_get_server();
		$data_a = $server_a->response_to_data( $response_a, false );
		$shippingZones = $data_a;		
		################################GET REQUEST##########################################
		
			$shippingZoneId=0;  // default shipping zone id (Locations not covered byother zones)
			unset($shippingZones[0]);  //remove default zone as it is not required to be matched 
			$shippingZones=array_values($shippingZones);  // serialize the array to use in loop

			foreach ($shippingZones as $key => $shippingZone) 
			{   
				//fetch location of the zone as the address needs to be matched with address
				######GET REQUEST##########################################
				$request_b = new WP_REST_Request( 'GET', "/wc/v3/shipping/zones/{$shippingZone['id']}/locations" );
				$response_b = rest_do_request($request_b);
				$server_b = rest_get_server();
				$data_b = $server_b->response_to_data( $response_b, false );				
				$shippingZones[$key]['locations'] = $data_b;				
				######GET REQUEST##########################################

				unset($shippingZones[$key]['_links']);   //remove extra data 
				
				if (!empty($shippingZones[$key]['locations']))  //if locations are associted with particular zone
				{
					
					foreach($shippingZones[$key]['locations'] as $locationkey => $locations) 
					{
						unset($shippingZones[$key]['locations'][$locationkey]['_links']);
						//remove extra data
						
						if ($shippingZones[$key]['locations'][$locationkey]['type']=='postcode') 
						{						
							//if country code and state code does not match but the zone defines post code, we need to match post code

							$haystack=$shippingZones[$key]['locations'][$locationkey]['code'];
							
							if (strpos($haystack,'...') !== false) // if zone defines range of zip i.e. 110011-110099
							{
								$zips=explode('...', $haystack);
								if($postcode>=$zips[0]&&$postcode<=$zips[1])
								{
									$shippingZoneId=$shippingZones[$key]['id'];									
								}
							}
							elseif (strpos($haystack,'*') !== false) { // if zone defines preg match of zip i.e. 1100*
								$haystack=str_replace('*', '', $haystack);
																
								if (preg_match("/^{$haystack}/", $postcode))  
								{
									$shippingZoneId=$shippingZones[$key]['id'];								    
								}								      
							}
							elseif(strtolower($haystack)==strtolower($postcode)) // if zone defines full zip i.e. 110091
							{
								$shippingZoneId=$shippingZones[$key]['id'];
							}
							else
							{
								//if post code not matched, let's continue the same process for other zones.								
							}					
						}			
						elseif (($shippingZones[$key]['locations'][$locationkey]['type']=='state') && ($shippingZones[$key]['locations'][$locationkey]['code']==$countryCode.':'.$stateCode)) 
						{	
							$shippingZoneId=$shippingZones[$key]['id'];
						}
						elseif (($shippingZones[$key]['locations'][$locationkey]['type']=='country') && ($shippingZones[$key]['locations'][$locationkey]['code']==$countryCode)) 							
						{							
							$shippingZoneId=$shippingZones[$key]['id'];
							
						}
						elseif (($shippingZones[$key]['locations'][$locationkey]['type']=='continent') && ($shippingZones[$key]['locations'][$locationkey]['code']==ams_get_country_continent($countryCode))) 							
						{							
							$shippingZoneId=$shippingZones[$key]['id'];
							
						}
						else
						{
							
						}	
					}
					if ($shippingZoneId!=0) {
						break;
					}					
				}
				else //if no location are associated with particular zone, possible with worldwide location
				{  					
					$shippingZoneId=$shippingZones[$key]['id'];					
					break;
				}
			}			
		
		################################REQUEST##########################################
		$request_c = new WP_REST_Request( 'GET', "/wc/v3/shipping/zones/{$shippingZoneId}/methods" );
		$response_c = rest_do_request($request_c);
		$server_c = rest_get_server();
		$methods = $server_c->response_to_data( $response_c, false );		
		################################REQUEST##########################################

		foreach ($methods as $methodKey => $method) 
		{
				if (isset($method['settings']['min_amount']))
				{
					if ($method['settings']['requires']['value']=='min_amount' && $cartTotal > $method['settings']['min_amount']['value'] ) 
					{
							$methods[$methodKey]['methodcost']=0;
							unset($methods[$methodKey]['_links']);
						
					}elseif(($method['settings']['requires']['value'] =='coupon') && ($free_shipping_by_coupon==true) ){
						
							$methods[$methodKey]['methodcost']=0;
							unset($methods[$methodKey]['_links']);
					
					}elseif(($method['settings']['requires']['value'] =='both') && ($free_shipping_by_coupon==true)&& ($cartTotal > $method['settings']['min_amount']['value']) ){
						
							$methods[$methodKey]['methodcost']=0;
							unset($methods[$methodKey]['_links']);
					
					}elseif(($method['settings']['requires']['value'] =='either') && ($free_shipping_by_coupon==true || $cartTotal > $method['settings']['min_amount']['value']) ){
						
							$methods[$methodKey]['methodcost']=0;
							unset($methods[$methodKey]['_links']);
					
					}elseif($method['settings']['requires']['value']==''){
						
							$methods[$methodKey]['methodcost']=0;
							unset($methods[$methodKey]['_links']);
					
					}
					else
					{
						unset($methods[$methodKey]); //remove methods those does not require amount to be applicable (in case of coupon or both coupon and amount)
					}					
				}
				elseif (isset($method['settings']['type'])) 
				{
						$methodCost=$method['settings']['cost']['value'];
						$methodCost= trim(trim($methodCost,"]"),"[");
						if(strpos($methodCost, 'fee percent')!==false)
						{
							$methodCost=str_replace('fee percent', 'fee_percent', $methodCost);							
							$methodCostArr=explode(" ", $methodCost);
							
							foreach ($methodCostArr as $methodCostkey => $methodCostArrVal) 
							{
								$methodCostArrVal=explode('="', trim($methodCostArrVal,'"'));
								switch ($methodCostArrVal[0]) {
									case 'fee_percent':
										$fee_percent=$methodCostArrVal[1];
										break;
									case 'min_fee':
										$min_fee=$methodCostArrVal[1];
										break;
									case 'max_fee':
										$max_fee=$methodCostArrVal[1];
										break;									
									default:										
										break;
								}
							}
							$methodNewCost=($cartTotal*$fee_percent)/100;
							
							if (isset($min_fee) && $min_fee>$methodNewCost) 
							{
								$methodNewCost=$min_fee;
							}
							if (isset($max_fee) && $methodNewCost>$max_fee) 
							{
								$methodNewCost=$max_fee;
							}
						}
						elseif(strpos($methodCost, 'qty')!==false)
						{
							$multiplier=(float)$methodCost;
							$qty= $product_qty[$shippinkey];
							$methodNewCost=($multiplier*$qty);
						}
						else
						{
							$methodNewCost=(float)$methodCost;
						}

						$methods[$methodKey]['settings']['cost']['value']=$methodNewCost;
											
					foreach ($shipping_class_ids as $shippinkey => $shipping_class) 
					{
						
						$shippingClassId=$shipping_class!=0?'class_cost_'.$shipping_class:'no_class_cost';
						$currCost=$method['settings'][$shippingClassId]['value'];
						$currCost= trim(trim($currCost,"]"),"[");
						$methodCost=$method['settings']['cost']['value'];
						
						if(strpos($currCost, 'fee percent')!==false)
						{
							$currCost=str_replace('fee percent', 'fee_percent', $currCost);
							$currCostArr=explode(" ", $currCost);

							foreach ($currCostArr as $currCostkey => $currCostArrVal) 
							{
								$currCostArrVal=explode('="', trim($currCostArrVal,'"'));
								switch ($currCostArrVal[0]) {
									case 'fee_percent':
										$fee_percent=$currCostArrVal[1];
										break;
									case 'min_fee':
										$min_fee=$currCostArrVal[1];
										break;
									case 'max_fee':
										$max_fee=$currCostArrVal[1];
										break;									
									default:										
										break;
								}
							}

							$methodNewCost=($cartTotal*$fee_percent)/100;
							
							if (isset($min_fee) && $min_fee>$methodNewCost) 
							{
								$methodNewCost=$min_fee;
							}

							if (isset($max_fee) && $methodNewCost>$max_fee) 
							{
								$methodNewCost=$max_fee;
							}

							$methods[$methodKey]['cost']=$methodNewCost;

						}
						elseif(strpos($currCost, 'qty')!==false)
						{
							$multiplier=(float)$currCost;
							$qty= $product_qty[$shippinkey];
							$methodNewCost=($multiplier*$qty);
							$methods[$methodKey]['cost']=$methodNewCost;
						}
						else
						{
							$methodNewCost=(float)$currCost;
						}
						
					}

					if ($method['settings']['type']['value']=='class') 
					{
						$methods[$methodKey]['methodcost']=array_sum($methods[$methodKey]['cost'])+$methodCost;
						unset($methods[$methodKey]['_links']);
					}
					elseif($method['settings']['type']['value']=='order') 
					{
						$methods[$methodKey]['methodcost']=max($methods[$methodKey]['cost'])+$methodCost;
						unset($methods[$methodKey]['_links']);

					}
					else
					{
						//
					}
				}
				else
				{
					$methods[$methodKey]['methodcost']=$methods[$methodKey]['settings']['cost']['value']?$methods[$methodKey]['settings']['cost']['value']:0;
					unset($methods[$methodKey]['_links']);
				}
			if($methods[$methodKey]['enabled']!=1)
			{
				unset($methods[$methodKey]);
			}
			else
			{
				$newShippingMethods[]=array(
										'id'=>(string)$methods[$methodKey]['id'],
										'title'=>$methods[$methodKey]['title'],
										'cost'=>floatval($methods[$methodKey]['methodcost'])
										);
			}				
		}
		$methods=array_values($methods);
		if (!$newShippingMethods) //if order could not be found
		{
			return(rest_ensure_response([]));
		}	
		return(rest_ensure_response($newShippingMethods));
		
	}
	


//###########################################################################################################//
//######################################## Other Helping Function ###########################################//	
//###########################################################################################################//

	function ams_build_category_tree($flat, $pidKey, $idKey = null)
	{
		$grouped = array();
		foreach ($flat as $sub){
			$grouped[$sub[$pidKey]][] = $sub;
		}
		$level=0;
		$fnBuilder = function($siblings,$level) use (&$fnBuilder, $grouped, $idKey) {
			foreach ($siblings as $k => $sibling) {
				if($sibling['slug']=="uncategorized"){unset($siblings[$k]); continue;} 
				$id = $sibling[$idKey];
				$sibling['description'] = "";
				$level++;
				if(isset($grouped[$id])) {
					$sibling['depth'] = $level;					
					$sibling['children'] = $fnBuilder($grouped[$id],$level);					
				}else{$sibling['depth'] = $level; $sibling['children'] = []; }
				$siblings[$k] = $sibling;
				$level--;
			}
			return $siblings;
		};

		if(isset($grouped[0])){
		$tree = $fnBuilder($grouped[0],$level);
		}
		if(!empty($tree)){
			return array_values($tree);
		}else{	//if no parent child relationship found.
			foreach($flat as $key=>$value){
				if($value['slug']=="uncategorized"){unset($flat[$key]); continue;}				
				$flat[$key]['children'] = [];
			}
			return array_values($flat);
		}
	}
	
	function ams_convert_error_to_string($er)
	{		
		 $string = " ";
		 foreach($er as $key=>$value){
			 
			$string = $string."".$key.":";
				foreach($value as $newkey=>$newvalue){
				
					$string = $string."".$newvalue." ";					
				}			 
		 }
		 $string = str_replace("Lost your password?","",$string);  // Remove this extra string from the error message.
		 $string = str_replace("Error:","",$string);
		 $string = str_replace("[message]","",$string);
		 return($string); 			
	}
	
	function ams_basic_validate($request,$keys)
	{  
		foreach($keys as $key=>$value){ 

			if(!isset($request[$value])){ 
				status_header(400);  echo (json_encode(['message' => 'There is a problem with your input!','error' => $value.": Field is required!"], JSON_UNESCAPED_UNICODE)); die();
			}
			if(empty($request[$value])){				
				status_header(400);  echo (json_encode(['message' => 'There is a problem with your input!','error' => $value.": Can not be empty!"], JSON_UNESCAPED_UNICODE)); die();
				}			
		}
		 return true; 
	}
	function ams_get_country_continent( $country ) {
		$data = array(
			'EU' => array( 'AD', 'AL', 'AT', 'AX', 'BA', 'BE', 'BG', 'BY', 'CH', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FO', 'FR', 'FX', 'GB', 'GG', 'GI', 'GR', 'HR', 'HU', 'IE', 'IM', 'IS', 'IT', 'JE', 'LI', 'LT', 'LU', 'LV', 'MC', 'MD', 'ME', 'MK', 'MT', 'NL', 'NO', 'PL', 'PT', 'RO', 'RS', 'RU', 'SE', 'SI', 'SJ', 'SK', 'SM', 'TR', 'UA', 'VA' ),
			'AS' => array( 'AE', 'AF', 'AM', 'AP', 'AZ', 'BD', 'BH', 'BN', 'BT', 'CC', 'CY', 'CN', 'CX', 'GE', 'HK', 'ID', 'IL', 'IN', 'IO', 'IQ', 'IR', 'YE', 'JO', 'JP', 'KG', 'KH', 'KP', 'KR', 'KW', 'KZ', 'LA', 'LB', 'LK', 'MY', 'MM', 'MN', 'MO', 'MV', 'NP', 'OM',	'PH', 'PK', 'PS', 'QA', 'SA', 'SG', 'SY', 'TH', 'TJ', 'TL', 'TM', 'TW', 'UZ', 'VN' ),
			'OC' => array( 'AS', 'AU', 'CK', 'FJ', 'FM', 'GU', 'KI', 'MH', 'MP', 'NC', 'NF', 'NR', 'NU', 'NZ', 'PF', 'PG', 'PN', 'PW', 'SB', 'TK', 'TO', 'TV', 'UM', 'VU', 'WF', 'WS' ),
			'NA' => array( 'CA', 'MX', 'US','AG', 'AI', 'AN', 'AW', 'BB', 'BL', 'BM', 'BS', 'BZ', 'CR', 'CU', 'DM', 'DO', 'GD', 'GL', 'GP', 'GT', 'HN', 'HT', 'JM', 'KY', 'KN', 'LC', 'MF', 'MQ', 'MS', 'NI', 'PA', 'PM', 'PR', 'SV', 'TC', 'TT', 'VC', 'VG', 'VI' ),
			'SA' => array( 'AR', 'BO', 'BR', 'CL', 'CO', 'EC', 'FK', 'GF', 'GY', 'GY', 'PE', 'PY', 'SR', 'UY', 'VE' ),
			'AF' => array( 'AO', 'BF', 'BI', 'BJ', 'BW', 'CD', 'CF', 'CG', 'CI', 'CM', 'CV', 'DJ', 'DZ', 'EG', 'EH', 'ER', 'ET', 'GA', 'GH', 'GM', 'GN', 'GQ', 'GW', 'YT', 'KE', 'KM', 'LY', 'LR', 'LS', 'MA', 'MG', 'ML', 'MR', 'MU', 'MW', 'MZ', 'NA', 'NE', 'NG', 'RE', 'RW', 'SC', 'SD', 'SH', 'SL', 'SN', 'SO', 'ST', 'SZ', 'TD', 'TG', 'TN', 'TZ', 'UG', 'ZA', 'ZM', 'ZW' ),
		);
		foreach ( $data as $continent => $countries ) {
			if ( in_array( $country, $countries ) ) {
				return $continent;
			}
		}
		return '';
	}	