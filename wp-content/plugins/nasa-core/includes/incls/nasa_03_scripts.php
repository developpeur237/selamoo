<?php
/* Register ajax url core */
add_action('wp_head', 'nasa_register_ajaxurl', 11);
function nasa_register_ajaxurl() {
    echo '<script type="text/javascript">var ajaxurl_core="' . esc_js(admin_url('admin-ajax.php')) . '";</script>';
}

// Script nasa-core
add_action('wp_enqueue_scripts', 'nasa_core_scripts_libs', 11);
function nasa_core_scripts_libs() {
    /**
     * Magnigic popup
     */
    if(!wp_script_is('jquery-magnific-popup')) {
        wp_enqueue_script('jquery-magnific-popup', NASA_CORE_PLUGIN_URL . 'assets/js/min/jquery.magnific-popup.min.js', array('jquery'), null, true);
    }
    
    /**
     * Countdown
     */
    if(!wp_script_is('countdown')) {
        wp_enqueue_script('countdown', NASA_CORE_PLUGIN_URL . 'assets/js/min/countdown.min.js', array('jquery'), null, true);
        wp_localize_script(
            'countdown', 'nasa_countdown_l10n',
            array(
                'days'      => esc_html__('Days', 'nasa-core'),
                'months'    => esc_html__('Months', 'nasa-core'),
                'weeks'     => esc_html__('Weeks', 'nasa-core'),
                'years'     => esc_html__('Years', 'nasa-core'),
                'hours'     => esc_html__('Hours', 'nasa-core'),
                'minutes'   => esc_html__('Mins', 'nasa-core'),
                'seconds'   => esc_html__('Secs', 'nasa-core'),
                'day'       => esc_html__('Day', 'nasa-core'),
                'month'     => esc_html__('Month', 'nasa-core'),
                'week'      => esc_html__('Week', 'nasa-core'),
                'year'      => esc_html__('Year', 'nasa-core'),
                'hour'      => esc_html__('Hour', 'nasa-core'),
                'minute'    => esc_html__('Min', 'nasa-core'),
                'second'    => esc_html__('Sec', 'nasa-core')
            )
        );
    }
    
    /**
     * Owl-Carousel
     */
    if(!wp_script_is('owl-carousel')) {
        wp_enqueue_script('owl-carousel', NASA_CORE_PLUGIN_URL . 'assets/js/min/owl.carousel.min.js', array('jquery'), null, true);
    }
    
    /**
     * Slick slider
     */
    if(!wp_script_is('jquery-slick')) {
        wp_enqueue_script('jquery-slick', NASA_CORE_PLUGIN_URL . 'assets/js/min/jquey.slick.min.js', array('jquery'), null, true);
    }
    
    /**
     * Pin products banner
     */
    wp_enqueue_script('nasa_pin_pb_easing', NASA_CORE_PLUGIN_URL . 'assets/js/min/jquery.easing.min.js', array('jquery'), null, true);
    wp_enqueue_script('nasa_pin_pb_easypin', NASA_CORE_PLUGIN_URL . 'assets/js/min/jquery.easypin.min.js', array('jquery'), null, true);
}
    
// Script nasa-core
add_action('wp_enqueue_scripts', 'nasa_core_scripts_ready', 999);
function nasa_core_scripts_ready() {
    wp_enqueue_script('nasa-core-functions-js', NASA_CORE_PLUGIN_URL . 'assets/js/min/nasa.functions.min.js', array('jquery'), null, true);
    wp_enqueue_script('nasa-core-js', NASA_CORE_PLUGIN_URL . 'assets/js/min/nasa.script.min.js', array('jquery'), null, true);
    
    /**
     * Define ajax options
     */
    if (!defined('NASA_AJAX_OPTIONS') && class_exists('WooCommerce')) {
        define('NASA_AJAX_OPTIONS', true);
        
        $ajax_params_options = array(
            'ajax_url'    => WC()->ajax_url(),
            'wc_ajax_url' => WC_AJAX::get_endpoint('%%endpoint%%'),
        );
        
        $ajax_params = 'var nasa_ajax_params=' . json_encode($ajax_params_options) . ';';
        wp_add_inline_script('nasa-core-functions-js', $ajax_params, 'before');
    }
}

/* Shop Ajax loadMore */
add_action('wp_ajax_nasa_more_product', 'nasa_loadmore_products');
add_action('wp_ajax_nopriv_nasa_more_product', 'nasa_loadmore_products');
function nasa_loadmore_products() {
    $type = $_REQUEST['type'];
    $post_per_page = $_REQUEST['post_per_page'];
    $page = $_REQUEST['page'];
    $cat = (isset($_REQUEST['cat']) && (int) $_REQUEST['cat']) ? (int) $_REQUEST['cat'] : null;
    $is_deals = $_REQUEST['is_deals'];
    $columns_number = $_REQUEST['columns_number'];

    $loop = nasa_woocommerce_query($type, $post_per_page, $cat, $page);
    if ($loop->found_posts):
        global $nasa_opt;
        include NASA_CORE_PRODUCT_LAYOUTS . 'globals/row_layout.php';
    endif;
    wp_reset_postdata();
    
    die();
}

/* Shortcode load Ajax All */
add_action('wp_ajax_nasa_load_ajax_all', 'nasa_load_sc_ajax_all');
add_action('wp_ajax_nopriv_nasa_load_ajax_all', 'nasa_load_sc_ajax_all');
function nasa_load_sc_ajax_all() {
    if (!isset($_REQUEST['shortcode']) || empty($_REQUEST['shortcode'])) {
        die('');
    }
    
    $result = array();
    foreach ($_REQUEST['shortcode'] as $key => $shortcode) {
        $result[$key] = do_shortcode($shortcode);
    }
    
    die(json_encode($result));
}

/* Shortcode load Ajax item */
add_action('wp_ajax_nasa_load_ajax_item', 'nasa_load_sc_ajax');
add_action('wp_ajax_nopriv_nasa_load_ajax_item', 'nasa_load_sc_ajax');
function nasa_load_sc_ajax() {
    if (!isset($_REQUEST['shortcode']) || empty($_REQUEST['shortcode']) || !isset($_REQUEST['shortcode_name']) || empty($_REQUEST['shortcode_name'])) {
        die();
    }
    
    $result = shortcode_exists($_REQUEST['shortcode_name']) ? do_shortcode($_REQUEST['shortcode']) : '';
    
    die($result);
}

/**
 * Get Total Price Accessories
 */
add_action('wp_ajax_nasa_refresh_accessories_price', 'nasa_refresh_accessories_price');
add_action('wp_ajax_nopriv_nasa_refresh_accessories_price', 'nasa_refresh_accessories_price');
function nasa_refresh_accessories_price() {
    $price = 0;
    if (isset($_REQUEST['total_price']) && $_REQUEST['total_price']) {
        $price = $_REQUEST['total_price'];
    }

    wp_send_json(array('total_price' => wc_price($price)));
}

/**
 * Add To Cart All Product + Accessories
 */
add_action('wp_ajax_nasa_add_to_cart_accessories', 'nasa_add_to_cart_accessories');
add_action('wp_ajax_nopriv_nasa_add_to_cart_accessories', 'nasa_add_to_cart_accessories');
function nasa_add_to_cart_accessories() {
    $error = array(
        'error' => true,
        'message' => '<p>' . esc_html__('Sorry, Maybe a product empty in stock.', 'nasa-core') . '</p>'
    );

    if (!isset($_REQUEST['product_ids']) || empty($_REQUEST['product_ids'])) {
        wp_send_json($error);

        return;
    }

    foreach ($_REQUEST['product_ids'] as $productId) {
        $product_id = (int) $productId;
        $product = wc_get_product($product_id);

        /**
         * Check Product
         */
        if (!$product) {
            wp_send_json($error);

            return;
        }

        $type = $product->get_type();

        /**
         * Check type
         */
        if (!in_array($type, array('simple', 'variation'))) {
            wp_send_json($error);

            return;
        }

        $quantity = 1;
        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        $product_status    = get_post_status($product_id);
        $variation_id      = 0;
        $variation         = array();

        /**
         * Check validate for variation product
         */
        if ('variation' === $type) {
            $variation_id = $product_id;
            $product_id   = $product->get_parent_id();
            $variation    = $product->get_variation_attributes();
        }

        /**
         * Add To Cart
         */
        if ($passed_validation && false !== WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status) {
            do_action('woocommerce_ajax_added_to_cart', $product_id);
        } else {
            $errors = wc_get_notices();
            if ($errors && !empty($errors['error'])) {
                $error['message'] = '';
                foreach ($errors['error'] as $notices) {
                    if (isset($notices['notice'])) {
                        $error['message'] .= '<p>' . $notices['notice'] . '</p>';
                    }
                }
            }
            wc_clear_notices();

            wp_send_json($error);

            return;
        }
    }

    if (class_exists('WC_AJAX')) {
        WC_AJAX::get_refreshed_fragments();
    }
}
