<?php
defined('ABSPATH') or die(); // Exit if accessed directly

if(class_exists('WC_AJAX')) :
    class DIGI_WC_AJAX extends WC_AJAX {

        /**
         * Hook in ajax handlers.
         */
        public static function nasa_init() {
            add_action('init', array(__CLASS__, 'define_ajax'), 0);
            add_action('template_redirect', array(__CLASS__, 'do_wc_ajax'), 0);
            self::nasa_add_ajax_events();
        }

        /**
         * Hook in methods - uses WordPress ajax handlers (admin-ajax).
         */
        public static function nasa_add_ajax_events() {
            /**
             * Register ajax event
             */
            $events = array(
                'nasa_static_content'               => true,
                'nasa_quick_view'                   => true
            );
            $ajax_events = apply_filters('nasa_regiter_ajax_events', $events);

            foreach ($ajax_events as $ajax_event => $nopriv) {
                add_action('wp_ajax_woocommerce_' . $ajax_event, array(__CLASS__, $ajax_event));

                if ($nopriv) {
                    add_action('wp_ajax_nopriv_woocommerce_' . $ajax_event, array(__CLASS__, $ajax_event));

                    // WC AJAX can be used for frontend ajax requests.
                    add_action('wc_ajax_' . $ajax_event, array(__CLASS__, $ajax_event));
                }
            }
        }

        /**
         * Static content
         * 
         * @global type $nasa_opt
         */
        public static function nasa_static_content() {
            global $nasa_opt;

            $content = array();
            // $content['#nasa-cart-sidebar-content'] = digi_mini_cart_sidebar(true);
            $content['#nasa-wishlist-sidebar-content'] = digi_mini_wishlist_sidebar(true);

            ob_start();
            do_action('nasa_show_mini_compare');
            $content['#nasa-compare-sidebar-content'] = ob_get_clean();

            if(defined('NASA_PLG_CACHE_ACTIVE') && NASA_PLG_CACHE_ACTIVE) {
                $content['.nasa-compare-count.compare-number'] = digi_get_count_compare();
                $content['.nasa-wishlist-count.wishlist-number'] = digi_get_count_wishlist();
                // $content['.cart-inner'] = digi_mini_cart();
                if (NASA_CORE_USER_LOGIGED) {
                    $content['.nasa-menus-account'] = digi_tiny_account(true);
                }
            }

            // Load compare product
            if(isset($_REQUEST['compare']) && $_REQUEST['compare']) {
                $content['#nasa-view-compare-product'] = digi_products_compare_content();
            }

            /**
             * Get viewed product
             */
            if(defined('NASA_COOKIE_VIEWED') && shortcode_exists('nasa_products_viewed') && (!isset($nasa_opt['disable-viewed']) || !$nasa_opt['disable-viewed'])) {
                $content['#nasa-viewed-sidebar-content'] = do_shortcode('[nasa_products_viewed is_ajax="no" columns_number="1" columns_small="1" columns_number_tablet="1" default_rand="false" display_type="sidebar"]');
            }

            /**
             * Login form ajax
             */
            if(!NASA_CORE_USER_LOGIGED && shortcode_exists('woocommerce_my_account') && (!isset($nasa_opt['login_ajax']) || $nasa_opt['login_ajax'] == 1)) {
                ob_start();
                do_action('nasa_login_register_form', true);
                $content['#nasa_customer_login'] = ob_get_clean();
            }

            wp_send_json($content);
        }

        /**
         * Get a refreshed cart fragment, including the mini cart HTML.
         */
        public static function nasa_quick_view() {
            $result = array('mess_unavailable' => esc_html__('Sorry, this product is unavailable.', 'digi-theme'), 'content' => '');
        
            if(isset($_REQUEST["product"])) {
                $prod_id = $_REQUEST["product"];
                $GLOBALS['post'] = get_post($prod_id);
                $GLOBALS['product'] = wc_get_product($prod_id);
                $product_lightbox = $GLOBALS['product'];
                if($product_lightbox) {
                    $product_type = $product_lightbox->get_type();

                    if($product_type == 'variation') {
                        $variation_data = wc_get_product_variation_attributes($prod_id);
                        $prod_id = wp_get_post_parent_id($prod_id);
                        $GLOBALS['post'] = get_post($prod_id);
                        $GLOBALS['product'] = wc_get_product($prod_id);
                        if(!empty($variation_data)) {
                            foreach ($variation_data as $key => $value) {
                                if($value != '') {
                                    $_REQUEST[$key] = $value;
                                }
                            }
                        }
                    } elseif ($product_type == 'grouped') {
                        $GLOBALS['product_lightbox'] = $product_lightbox;
                    }
                    ob_start();
                    wc_get_template('content-single-product-lightbox.php');
                    $result['content'] = ob_get_clean();
                }
            }

            wp_send_json($result);
        }
    }

    /**
     * Init WC AJAX
     */
    if(isset($_REQUEST['wc-ajax'])) {
        add_action('init', 'digi_init_wc_ajax');
        if(!function_exists('digi_init_wc_ajax')) :
            function digi_init_wc_ajax() {
                DIGI_WC_AJAX::nasa_init();
            }
        endif;
    }

endif;
