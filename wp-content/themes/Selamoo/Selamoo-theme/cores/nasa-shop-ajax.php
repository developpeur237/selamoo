<?php
if(!function_exists('digi_init_map_shortcode')) :
    function digi_init_map_shortcode() {
        if (class_exists('WPBMap')) {
            WPBMap::addAllMappedShortcodes();
        }
    }
endif;

add_action('wp_head', 'digi_register_AjaxUrl', 0, 0);
if(!function_exists('digi_register_AjaxUrl')) :
    function digi_register_AjaxUrl() {
        echo '<script type="text/javascript">var ajaxurl="' . esc_url(admin_url('admin-ajax.php')) . '";</script>';
    }
endif;

/**
 * Schedule time for variation
 */
add_action('wp_ajax_nasa_get_deal_variation', 'digi_get_deal_variation_single_page');
add_action('wp_ajax_nopriv_nasa_get_deal_variation', 'digi_get_deal_variation_single_page');
if(!function_exists('digi_get_deal_variation_single_page')) :
    function digi_get_deal_variation_single_page() {
        if(isset($_REQUEST["pid"])) {
            $productId = $_REQUEST["pid"];
            $timeNow = time();
            $time_from = get_post_meta($productId, '_sale_price_dates_from', true);
            $time_to = get_post_meta($productId, '_sale_price_dates_to', true);
            $time_sale = ((int) $time_to < $timeNow || (int) $time_from > $timeNow) ?
                false : (int) $time_to;
            
            echo digi_time_sale($time_sale);
        }
        
        die;
    }
endif;

// **********************************************************************//
//	Update wishlist - AJAX
// **********************************************************************//
add_action('wp_ajax_nasa_update_wishlist', 'digi_update_wishlist');
add_action('wp_ajax_nopriv_nasa_update_wishlist', 'digi_update_wishlist');
if(!function_exists('digi_update_wishlist')) :
    function digi_update_wishlist(){
        $json = array(
            'list' => '',
            'count' => 0
        );
        
        $json['list'] = digi_mini_wishlist_sidebar(true);
        $json['status_add'] = 'true';
        $json['count'] = function_exists('yith_wcwl_count_products') ? yith_wcwl_count_products() : 0;
        
        if (NASA_WISHLIST_NEW_VER && isset($_REQUEST['added']) && $_REQUEST['added']) {
            $json['mess'] = '<div id="yith-wcwl-message">' . esc_html__('Product Added!', 'digi-theme') . '</div>';
        }

        die(json_encode($json));
    }
endif;

// **********************************************************************//
//	Add to wishlist - AJAX
// **********************************************************************//
add_action('wp_ajax_nasa_remove_from_wishlist', 'digi_remove_from_wishlist');
add_action('wp_ajax_nopriv_nasa_remove_from_wishlist', 'digi_remove_from_wishlist');
if(!function_exists('digi_remove_from_wishlist')) :
    function digi_remove_from_wishlist(){
        $json = array(
            'error' => '1',
            'list' => '',
            'count' => 0
        );

        if(!NASA_WISHLIST_ENABLE) {
            die(json_encode($json));
        }

        $detail = array();
        $detail['remove_from_wishlist'] = isset($_REQUEST['pid']) ? (int) $_REQUEST['pid'] : 0;
        $detail['wishlist_id'] = isset($_REQUEST['wishlist_id']) ? (int) $_REQUEST['wishlist_id'] : 0;
        $detail['pagination'] = isset($_REQUEST['pagination']) ? (int) $_REQUEST['pagination'] : 'no';
        $detail['per_page'] = isset($_REQUEST['per_page']) ? (int) $_REQUEST['per_page'] : 5;
        $detail['current_page'] = isset($_REQUEST['current_page']) ? (int) $_REQUEST['current_page'] : 1;
        $detail['user_id'] = is_user_logged_in() ? get_current_user_id() : false;
        $mess_success = '<div id="yith-wcwl-message">' . esc_html__('Product successfully removed!', 'digi-theme') . '</div>';
        
        if (!NASA_WISHLIST_NEW_VER) {
            $nasa_wishlist = new YITH_WCWL($detail);
            $json['error'] = digi_remove_wishlist_item($nasa_wishlist, true) ? '0' : '1';

            if($json['error'] == '0') {
                $json['list'] = digi_mini_wishlist_sidebar(true);
                $json['count'] = yith_wcwl_count_products();
                $json['mess'] = $mess_success;
            }
        } else {
            try{
                YITH_WCWL()->remove($detail);
                $json['list'] = digi_mini_wishlist_sidebar(true);
                $json['count'] = yith_wcwl_count_products();
                $json['mess'] = $mess_success;
                $json['error'] = '0';
            }
            catch(Exception $e){
                $json['mess'] = $e->getMessage();
            }
        }

        die(json_encode($json));
    }
endif;

if(!function_exists('digi_remove_wishlist_item')) :
    function digi_remove_wishlist_item($nasa_wishlist, $remove_force = false) {
        if(get_option('yith_wcwl_remove_after_add_to_cart') == 'yes' || $remove_force) {
            if(!$nasa_wishlist->details['user_id']){
                $wishlist = yith_getcookie('yith_wcwl_products');
                foreach( $wishlist as $key => $item ){
                    if($item['prod_id'] == $nasa_wishlist->details['remove_from_wishlist']){
                        unset($wishlist[$key]);
                    }
                }
                yith_setcookie('yith_wcwl_products', $wishlist);

                return true;
            }

            return $nasa_wishlist->remove();
        }

        return true;
    }
endif;

// **********************************************************************//
//	Mini cart - AJAX: Remove product from cart
// **********************************************************************//
add_action('wp_ajax_nasa_cart_remove_item' , 'digi_cart_remove_item');
add_action('wp_ajax_nopriv_nasa_cart_remove_item', 'digi_cart_remove_item');
if(!function_exists('digi_cart_remove_item')) :
    function digi_cart_remove_item() {
        global $woocommerce;
        $data = array('success' => false);
        if(isset($_REQUEST['item_key']) && ($item_key = $_REQUEST['item_key'])){
            if ($removed = $woocommerce->cart->remove_cart_item($item_key)){
                // Return fragments
                ob_start();
                woocommerce_mini_cart();
                $mini_cart = ob_get_clean();

                // Fragments and mini cart are returned
                $cart_session = $woocommerce->cart->get_cart_for_session();
                $data = array(
                    'success' => true,
                    'showing' => $woocommerce->cart->cart_contents_count,
                    'fragments' => apply_filters(
                        'woocommerce_add_to_cart_fragments',
                        array(
                            'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
                        )
                    ),
                    'cart_hash' => apply_filters(
                        'woocommerce_add_to_cart_hash',
                        $cart_session ? md5(json_encode($cart_session)) : '',
                        $cart_session
                    )
                );
            }
        }

        exit(json_encode($data));
    }
endif;

// **********************************************************************//
//	single add to cart - AJAX
// **********************************************************************//
add_action('wp_ajax_nasa_single_add_to_cart' , 'digi_single_add_to_cart');
add_action('wp_ajax_nopriv_nasa_single_add_to_cart', 'digi_single_add_to_cart');
if(!function_exists('digi_single_add_to_cart')) :
    function digi_single_add_to_cart() {
        global $woocommerce;

        if(!$woocommerce || !isset($_REQUEST['product_id']) || (int)$_REQUEST['product_id'] <= 0){
            echo json_encode(array(
                'error' => true,
                'message' => esc_html__('Sorry, Product is not existing.', 'digi-theme')
            ));
            die();
        }

        $error = false;
        $product_id        = apply_filters('woocommerce_add_to_cart_product_id', absint($_REQUEST['product_id']));
        $quantity          = empty($_REQUEST['quantity']) ? 1 : wc_stock_amount($_REQUEST['quantity']);
        $type = (!isset($_REQUEST['product_type']) || !in_array($_REQUEST['product_type'], array('simple', 'variation', 'variable', NASA_COMBO_TYPE))) ? 'simple' : $_REQUEST['product_type'];

        $variation = isset($_REQUEST['variation']) ? $_REQUEST['variation'] : array();
        $validate_attr = true;
        if($type == 'variation') {
            $variation_id = $product_id;
            $product_id = wp_get_post_parent_id($product_id);
            $type = 'variable';
        } else {
            $variation_id = (int) $_REQUEST['variation_id'];
        }

        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        $product_status    = get_post_status($product_id);
        $product = wc_get_product((int) $product_id);
        $product_type = false;
        if(!$product) {
            $error = true;
        } else {
            $product_type = $product->get_type();
            if(((!$variation || !$variation_id) && $product_type == 'variable') || $type != $product_type){
                $error = true;
            }
            elseif($product_type == NASA_COMBO_TYPE && function_exists('YITH_WCPB_Frontend')) {
                YITH_WCPB_Frontend();
            }
            
            if(!$error && $product_type == 'variable') {
                $validate_attr = digi_validate_variation($product, $variation_id, $variation, $quantity);
            }
        }

        if (!$error && $validate_attr && $passed_validation && $woocommerce->cart->add_to_cart($product_id, $quantity, $variation_id, $variation) && 'publish' === $product_status) {

            do_action('woocommerce_ajax_added_to_cart', $product_id);
            if (get_option('woocommerce_cart_redirect_after_add') == 'yes') {
                wc_add_to_cart_message($product_id);
            }

            // Return fragments
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();

            // Fragments and mini cart are returned
            $cart_session = $woocommerce->cart->get_cart_for_session();
            $data = array(
                'fragments' => apply_filters(
                    'woocommerce_add_to_cart_fragments',
                    array(
                        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
                    )
                ),
                'cart_hash' => apply_filters(
                    'woocommerce_add_to_cart_hash',
                    $cart_session ? md5(json_encode($cart_session)) : '',
                    $cart_session
                )
            );

            // Remove wishlist
            if (NASA_WISHLIST_ENABLE && $product_type && $product_type != 'external' && get_option('yith_wcwl_remove_after_add_to_cart') == 'yes') {
                $nasa_logined_id = get_current_user_id();    
                $detail = isset($_REQUEST['data_wislist']) ? $_REQUEST['data_wislist'] : array();
                if (!empty($detail) && isset($detail['from_wishlist']) && $detail['from_wishlist'] == '1') {
                    $detail['remove_from_wishlist'] = $product_id;
                    $detail['user_id'] = $nasa_logined_id;

                    $data['wishlist'] = '';
                    $data['wishlistcount'] = 0;

                    /**
                     * WCWL 2.x or Lower
                     */
                    if (!NASA_WISHLIST_NEW_VER) {
                        if ($nasa_logined_id) {
                            $nasa_wishlist = new YITH_WCWL($detail);
                            if (digi_remove_wishlist_item($nasa_wishlist)) {
                                $data['wishlist'] = digi_mini_wishlist_sidebar(true);
                                $data['wishlistcount'] = yith_wcwl_count_products();
                            }
                        }
                    }

                    /**
                     * WCWL 3x or Higher
                     */
                    else {
                        try {
                            YITH_WCWL()->remove($detail);
                            $data['wishlist'] = digi_mini_wishlist_sidebar(true);
                            $data['wishlistcount'] = yith_wcwl_count_products();
                        }
                        catch (Exception $e){
                            $data['message'] = $e->getMessage();
                        }
                    }
                }
            }

            wp_send_json($data);
        } else {
            // If there was an error adding to the cart, redirect to the product page to show any errors
            $data = array(
                'error' => true,
                'message' => esc_html__('Sorry, Maybe product empty in stock.', 'digi-theme'),
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
            );
            echo json_encode($data);
        }
        die();
    }
endif;

if(!function_exists('digi_validate_variation')) :
    function digi_validate_variation($product, $variation_id, $variation, $quantity) {
        if (empty($variation_id) || empty($product)) {
            return false;
        }
        
        $missing_attributes = array();
        $variations         = array();
        $attributes         = $product->get_attributes();
        $variation_data     = wc_get_product_variation_attributes($variation_id);
        
        foreach ($attributes as $attribute) {
            if (!$attribute['is_variation']) {
                continue;
            }

            $taxonomy = 'attribute_' . sanitize_title($attribute['name']);

            if (isset($variation[$taxonomy])) {
                // Get value from post data
                if ($attribute['is_taxonomy']) {
                    // Don't use wc_clean as it destroys sanitized characters
                    $value = sanitize_title(stripslashes($variation[$taxonomy]));
                } else {
                    $value = wc_clean(stripslashes($variation[$taxonomy]));
                }

                // Get valid value from variation
                $valid_value = isset($variation_data[$taxonomy]) ? $variation_data[$taxonomy] : '';

                // Allow if valid or show error.
                if ($valid_value === $value || ('' === $valid_value && in_array($value, $attribute->get_slugs()))) {
                    $variations[$taxonomy] = $value;
                } else {
                    return false;
                }
            } else {
                $missing_attributes[] = wc_attribute_label($attribute['name']);
            }
        }
        if (!empty($missing_attributes)) {
            return false;
        }
        
        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product->get_id(), $quantity, $variation_id, $variations);
        
        return $passed_validation;
    }
endif;

// Ajax search
add_action('wp_ajax_nopriv_live_search_products', 'digi_live_search_products');
add_action('wp_ajax_live_search_products', 'digi_live_search_products');
if(!function_exists('digi_live_search_products')) :
    function digi_live_search_products() {
        global $nasa_opt, $woocommerce;

        $results = array();
        if (!$woocommerce || !isset($_GET['s']) || trim($_GET['s']) == '') {
            die(json_encode($results));
        }
        
        $data_store = WC_Data_Store::load('product');
        $post_id_in = $data_store->search_products(wc_clean($_GET['s']), '', true, true);
        if (empty($post_id_in)) {
            die(json_encode($results));
        }

        $query_args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => (isset($nasa_opt['limit_results_search']) && (int) $nasa_opt['limit_results_search'] > 0) ? (int) $nasa_opt['limit_results_search'] : 5,
            'no_found_rows' => true
        );

        $query_args['meta_query'] = array();
        $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
        $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();

        $query_args['post__in'] = array_merge($post_id_in, array(0));
        $query_args['tax_query'] = array('relation' => 'AND');
        $product_visibility_terms = wc_get_product_visibility_term_ids();
        $arr_not_in = array($product_visibility_terms['exclude-from-catalog']);

        // Hide out of stock products.
        if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
            $arr_not_in[] = $product_visibility_terms['outofstock'];
        }

        if (!empty($arr_not_in)) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field' => 'term_taxonomy_id',
                'terms' => $arr_not_in,
                'operator' => 'NOT IN',
            );
        }

        $search_query = new WP_Query($query_args);
        if ($the_posts = $search_query->get_posts()) {
            foreach ($the_posts as $the_post) {
                $title = get_the_title($the_post->ID);
                if (has_post_thumbnail($the_post->ID)) {
                    $post_thumbnail_ID = get_post_thumbnail_id($the_post->ID);
                    $post_thumbnail_src = wp_get_attachment_image_src($post_thumbnail_ID, 'thumbnail');
                } else {
                    $size = wc_get_image_size('thumbnail');
                    $post_thumbnail_src = array(
                        wc_placeholder_img_src(),
                        esc_attr($size['width']),
                        esc_attr($size['height'])
                    );
                }

                if ($product = wc_get_product($the_post->ID)) {
                    $results[] = array(
                        'title' => html_entity_decode($title, ENT_QUOTES, 'UTF-8'),
                        'tokens' => explode(' ', $title),
                        'url' => get_permalink($the_post->ID),
                        'image' => $post_thumbnail_src[0],
                        'price' => $product->get_price_html()
                    );
                }
            }
        }
        wp_reset_postdata();

        die(json_encode($results));
    }
endif;

add_action('wp_head', 'digi_search_live_options', 0, 0);
if(!function_exists('digi_search_live_options')) :
    function digi_search_live_options() {
        global $nasa_opt;

        if ($enable = isset($nasa_opt['enable_live_search']) ? $nasa_opt['enable_live_search'] : true) {
            wp_enqueue_script('nasa-typeahead-js', DIGI_THEME_URI . '/assets/js/min/typeahead.bundle.min.js', array('jquery'), '', true);
            wp_enqueue_script('nasa-handlebars', DIGI_THEME_URI . '/assets/js/min/handlebars.min.js', array('nasa-typeahead-js'), '', true);
        }

        $search_options = array(
            'limit_results' => isset($nasa_opt['limit_results_search']) ? (int) $nasa_opt['limit_results_search'] : 5,
            'live_search_template' => '<div class="item-search"><a href="{{url}}" class="nasa-link-item-search" title="{{title}}"><img src="{{image}}" class="nasa-item-image-search" height="60" width="60" /><div class="nasa-item-title-search"><p>{{title}}</p></div></a></div>',
            'enable_live_search' => $enable
        );

        echo '<script type="text/javascript">var search_options=';
        echo $enable ? json_encode($search_options) : '"0"';
        echo ';</script>';
    }
endif;

// Login Ajax
add_action('wp_ajax_nopriv_nasa_process_login', 'digi_process_login');
add_action('wp_ajax_nasa_process_login', 'digi_process_login');
if(!function_exists('digi_process_login')) :
    function digi_process_login() {
        $mess = array('error' => '1', 'mess' => esc_html__('Error.', 'digi-theme'), '_wpnonce' => '0');
        !empty($_REQUEST['data']) or die(json_encode($mess));
        
        $input = array();
        foreach ($_REQUEST['data'] as $values) {
            if(isset($values['name']) && isset($values['value'])) {
                $input[$values['name']] = $values['value'];
            }
        }

        if(isset($input['woocommerce-login-nonce'])) {
            $nonce_value = $input['woocommerce-login-nonce'];
        } else {
            $nonce_value = isset($input['_wpnonce']) ? $input['_wpnonce'] : '';
        }

        // Check _wpnonce
        if(!wp_verify_nonce($nonce_value, 'woocommerce-login')) {
            $mess['_wpnonce'] = 'error';
            die(json_encode($mess));
        }

        if (!empty($_REQUEST['login'])) {
            $creds    = array();
            $username = trim($input['nasa_username']);

            $validation_error = new WP_Error();
            $validation_error = apply_filters('woocommerce_process_login_errors', $validation_error, $input['nasa_username'], $input['nasa_username']);

            // Login error
            if ($validation_error->get_error_code()) {
                $mess['mess'] = '<strong>' . esc_html__('Error', 'digi-theme') . ':</strong> ' . $validation_error->get_error_message();

                die(json_encode($mess));
            }

            // Require username
            if (empty($username)) {
                $mess['mess'] = '<strong>' . esc_html__('Error', 'digi-theme') . ':</strong> ' . esc_html__('Username is required.', 'digi-theme');

                die(json_encode($mess));
            }

            // Require Password
            if (empty($input['nasa_password'])) {
                $mess['mess'] = '<strong>' . esc_html__('Error', 'digi-theme') . ':</strong> ' . esc_html__('Password is required.', 'digi-theme');

                die(json_encode($mess));
            }

            if (is_email($username) && apply_filters('woocommerce_get_username_from_email', true)) {
                $user = get_user_by('email', $username);

                if (!isset($user->user_login)) {
                    // Email error
                    $mess['mess'] = '<strong>' . esc_html__('Error', 'digi-theme') . ':</strong> ' . esc_html__('A user could not be found with this email address.', 'digi-theme');

                    die(json_encode($mess));
                }

                $creds['user_login'] = $user->user_login;
            } else {
                $creds['user_login'] = $username;
            }

            $creds['user_password'] = $input['nasa_password'];
            $creds['remember'] = isset($input['nasa_rememberme']);
            $secure_cookie = is_ssl() ? true : false;
            $user = wp_signon(apply_filters('woocommerce_login_credentials', $creds), $secure_cookie);

            if (is_wp_error($user)) {
                // Other Error
                $message = $user->get_error_message();
                $mess['mess'] = str_replace(
                    '<strong>' . esc_html($creds['user_login']) . '</strong>',
                    '<strong>' . esc_html($username) . '</strong>',
                    $message
                );

                die(json_encode($mess));
            } else {
                // Login success
                $mess['error'] = '0';
                if (! empty($input['nasa_redirect'])) {
                    $redirect = $input['nasa_redirect'];
                } elseif (wp_get_referer()) {
                    $redirect = wp_get_referer();
                } else {
                    $redirect = wc_get_page_permalink('myaccount');
                }

                $mess['mess'] = esc_html__('Login success.', 'digi-theme');
                $mess['redirect'] = $redirect;
            }
        }

        die(json_encode($mess));
    }
endif;

// Register Ajax
add_action('wp_ajax_nopriv_nasa_process_register', 'digi_process_register');
add_action('wp_ajax_nasa_process_register', 'digi_process_register');
if(!function_exists('digi_process_register')) :
    function digi_process_register() {
        !empty($_REQUEST['data']) or die;
        $mess = array('error' => '1', 'mess' => esc_html__('Error.', 'digi-theme'), '_wpnonce' => '0');
        $input = array();
        foreach ($_REQUEST['data'] as $values) {
            if(isset($values['name']) && isset($values['value'])) {
                $input[$values['name']] = $values['value'];
            }
        }

        if(isset($input['woocommerce-register-nonce'])) {
            $nonce_value = $input['woocommerce-register-nonce'];
        } else {
            $nonce_value = isset($input['_wpnonce']) ? $input['_wpnonce'] : '';
        }
        
        // Check _wpnonce
        if(!wp_verify_nonce($nonce_value, 'woocommerce-register')) {
            $mess['_wpnonce'] = 'error';
            die(json_encode($mess));
        }

        if (! empty($_REQUEST['register'])) {
            $username = 'no' === get_option('woocommerce_registration_generate_username') ? $input['nasa_username'] : '';
            $password = 'no' === get_option('woocommerce_registration_generate_password') ? $input['nasa_password'] : '';
            $email    = $input['nasa_email'];

            $validation_error = new WP_Error();
            $validation_error = apply_filters('woocommerce_process_registration_errors', $validation_error, $username, $password, $email);

            if ($validation_error->get_error_code()) {
                $mess['mess'] = $validation_error->get_error_message();
                die(json_encode($mess));
            }

            $new_customer = wc_create_new_customer(sanitize_email($email), wc_clean($username), $password);

            if (is_wp_error($new_customer)) {
                $mess['mess'] = $new_customer->get_error_message();
                die(json_encode($mess));
            }

            if (apply_filters('woocommerce_registration_auth_new_customer', true, $new_customer)) {
                wc_set_customer_auth_cookie($new_customer);
            }

            // Register success.
            $mess['error'] = '0';
            $mess['mess'] = esc_html__('Register success.', 'digi-theme');
            $mess['redirect'] = apply_filters('woocommerce_registration_redirect', wp_get_referer() ? wp_get_referer() : wc_get_page_permalink('myaccount'));
        }

        die(json_encode($mess));
    }
endif;

// Upsell ajax in grid
add_action('wp_ajax_nasa_upsell_products', 'digi_upsell_products');
add_action('wp_ajax_nopriv_nasa_upsell_products', 'digi_upsell_products');
if(!function_exists('digi_upsell_products')) :
    function digi_upsell_products(){
        $output = array();

        global $woocommerce, $nasa_opt;

        if(!$woocommerce || !isset($_REQUEST['id']) || !(int) $_REQUEST['id']){
            die(json_encode($output));
        }

        $product = new WC_Product((int) $_REQUEST['id']);
        if (!$upsells = $product->get_upsell_ids()) {
            die(json_encode($output));
        }

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'paged' => 1,
            'meta_query' => array($woocommerce->query->stock_status_meta_query()),
            'post__in' => $upsells,
            'post__not_in' => array($product->get_id())
        );
        $loop = new WP_Query($args);

        ob_start();
        if ($loop->found_posts):
            $_delay = $count = 0;
            $_delay_item = (isset($nasa_opt['delay_overlay']) && (int) $nasa_opt['delay_overlay']) ? (int) $nasa_opt['delay_overlay'] : 100;

            $file = DIGI_CHILD_PATH . '/includes/nasa-upsell-product.php';
            $file = is_file($file) ? $file : DIGI_THEME_PATH . '/includes/nasa-upsell-product.php';

            echo '<div class="group-slider"><div class="slider products-group nasa-upsell-slider owl-carousel">';
            while($loop->have_posts()):
                $loop->the_post();
                global $product;
                include $file;
                $_delay += $_delay_item;
            endwhile;
            echo '</div></div>';
        endif;

        wp_reset_postdata();
        $output['content'] = ob_get_clean();

        echo json_encode($output);
        die();
    }
endif;

// Combo product ajax in grid
add_action('wp_ajax_nasa_combo_products', 'digi_combo_products');
add_action('wp_ajax_nopriv_nasa_combo_products', 'digi_combo_products');
if(!function_exists('digi_combo_products')) :
    function digi_combo_products(){
        $output = array();

        if(!defined('YITH_WCPB')) {
            die(json_encode($output));
        }

        global $woocommerce, $nasa_opt;

        if(!$woocommerce || !isset($_REQUEST['id']) || !(int) $_REQUEST['id']){
            die(json_encode($output));
        }

        $product = wc_get_product((int) $_REQUEST['id']);
        if ($product->get_type() != NASA_COMBO_TYPE || !$combo = $product->get_bundled_items()) {
            die(json_encode($output));
        }

        $file = DIGI_CHILD_PATH . '/includes/nasa-combo-products.php';
        $file = is_file($file) ? $file : DIGI_THEME_PATH . '/includes/nasa-combo-products.php';
        ob_start();
        include $file;
        $output['content'] = ob_get_clean();

        echo json_encode($output);
        die();
    }
endif;

// Add compare product
add_action('wp_ajax_nasa_add_compare_product', 'digi_add_compare_product');
add_action('wp_ajax_nopriv_nasa_add_compare_product', 'digi_add_compare_product');
if(!function_exists('digi_add_compare_product')) :
    function digi_add_compare_product(){
        $result = array(
            'result_compare' => 'error',
            'mess_compare' => esc_html__('Error !', 'digi-theme'),
            'mini_compare' => 'no-change',
            'count_compare' => 0
        );
        if (!isset($_REQUEST['pid']) || !(int) $_REQUEST['pid']) {
            die(json_encode($result));
        }

        global $nasa_opt, $yith_woocompare;
        $nasa_compare = isset($yith_woocompare->obj) ? $yith_woocompare->obj : $yith_woocompare;
        if(!$nasa_compare) {
            die(json_encode($result));
        }

        $max_compare = isset($nasa_opt['max_compare']) ? (int) $nasa_opt['max_compare'] : 4;
        if(!in_array((int) $_REQUEST['pid'], $nasa_compare->products_list)) {
            if(count($nasa_compare->products_list) >= $max_compare) {
                while (count($nasa_compare->products_list) >= $max_compare) {
                    array_shift($nasa_compare->products_list);
                }
            }

            $nasa_compare->add_product_to_compare((int) $_REQUEST['pid']);
            $result['mess_compare'] = esc_html__('Product added to compare !', 'digi-theme');
            
            ob_start();
            do_action('nasa_show_mini_compare');
            $result['mini_compare'] = ob_get_clean();

            if (isset($_REQUEST['compare_table']) && $_REQUEST['compare_table']) {
                $result['result_table'] = digi_products_compare_content();
            }
        } else {
            $result['mess_compare'] = esc_html__('Product already exists in Compare list !', 'digi-theme');
        }

        $result['count_compare'] = count($nasa_compare->products_list);
        $result['result_compare'] = 'success';

        die(json_encode($result));
    }
endif;

// remove compare product
add_action('wp_ajax_nasa_remove_compare_product', 'digi_remove_compare_product');
add_action('wp_ajax_nopriv_nasa_remove_compare_product', 'digi_remove_compare_product');
if(!function_exists('digi_remove_compare_product')) :
    function digi_remove_compare_product(){
        $result = array(
            'result_compare' => 'error',
            'mess_compare' => esc_html__('Error !', 'digi-theme'),
            'mini_compare' => 'no-change',
            'count_compare' => 0
        );
        if (!isset($_REQUEST['pid']) || !(int) $_REQUEST['pid']) {
            die(json_encode($result));
        }

        global $yith_woocompare;
        $nasa_compare = isset($yith_woocompare->obj) ? $yith_woocompare->obj : $yith_woocompare;
        if(!$nasa_compare) {
            die(json_encode($result));
        }

        if(in_array((int) $_REQUEST['pid'], $nasa_compare->products_list)) {
            $nasa_compare->remove_product_from_compare((int) $_REQUEST['pid']);
            $result['mess_compare'] = esc_html__('Removed product from compare !', 'digi-theme');
            
            ob_start();
            do_action('nasa_show_mini_compare');
            $result['mini_compare'] = ob_get_clean();

            if (isset($_REQUEST['compare_table']) && $_REQUEST['compare_table']) {
                $result['result_table'] = digi_products_compare_content();
            }
        } else {
            $result['mess_compare'] = esc_html__('Product not already exists in Compare list !', 'digi-theme');
        }

        $result['count_compare'] = count($nasa_compare->products_list);
        $result['result_compare'] = 'success';

        die(json_encode($result));
    }
endif;

// remove All compare product
add_action('wp_ajax_nasa_removeAll_compare_product', 'digi_removeAll_compare_product');
add_action('wp_ajax_nopriv_nasa_removeAll_compare_product', 'digi_removeAll_compare_product');
if(!function_exists('digi_removeAll_compare_product')) :
    function digi_removeAll_compare_product(){
        $result = array(
            'result_compare' => 'error',
            'mess_compare' => esc_html__('Error !', 'digi-theme'),
            'mini_compare' => 'no-change',
            'count_compare' => 0
        );

        global $yith_woocompare;
        $nasa_compare = isset($yith_woocompare->obj) ? $yith_woocompare->obj : $yith_woocompare;
        if(!$nasa_compare) {
            die(json_encode($result));
        }

        if(!empty($nasa_compare->products_list)) {
            $nasa_compare->remove_product_from_compare('all');
            $result['mess_compare'] = esc_html__('Removed all products from compare !', 'digi-theme');
            ob_start();
            do_action('nasa_show_mini_compare');

            $result['mini_compare'] = ob_get_clean();
        } else {
            $result['mess_compare'] = esc_html__('Compare products were empty !', 'digi-theme');
        }

        $result['count_compare'] = count($nasa_compare->products_list);
        $result['result_compare'] = 'success';
        if (isset($_REQUEST['compare_table']) && $_REQUEST['compare_table']) {
            $result['result_table'] = digi_products_compare_content();
        }

        die(json_encode($result));
    }
endif;
