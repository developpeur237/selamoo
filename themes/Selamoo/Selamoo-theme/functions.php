<?php
/*
 *
 * @package nasatheme - digi-theme
 */

/* Define DIR AND URI OF THEME */
define('DIGI_THEME_PATH', get_template_directory());
define('DIGI_CHILD_PATH', get_stylesheet_directory());
define('DIGI_THEME_URI', get_template_directory_uri());

/* Check if WooCommerce is active */
defined('NASA_WOO_ACTIVED') or define('NASA_WOO_ACTIVED', (bool) class_exists('WooCommerce'));

/* Check Yith WooCommerce Wishlist */
defined('NASA_WISHLIST_ENABLE') or define('NASA_WISHLIST_ENABLE', (bool) defined('YITH_WCWL'));

$wishlist_loop = NASA_WISHLIST_ENABLE ? true : false;
$wishlist_new = false;
if (NASA_WISHLIST_ENABLE && defined('YITH_WCWL_VERSION')) {
    if (version_compare(YITH_WCWL_VERSION, '3.0', ">=")) {
        $wishlist_loop = get_option('yith_wcwl_show_on_loop') !== 'yes' ? false : true;
        $wishlist_new = true;
    }
}
define('NASA_WISHLIST_NEW_VER', $wishlist_new);
define('NASA_WISHLIST_IN_LIST', $wishlist_loop);

/* Check if nasa-core is active */
defined('NASA_CORE_ACTIVED') or define('NASA_CORE_ACTIVED', false);
defined('NASA_CORE_IN_ADMIN') or define('NASA_CORE_IN_ADMIN', is_admin());

/* Detect Mobile or Tablet */
defined('NASA_IS_PHONE') or define('NASA_IS_PHONE', false);

/* user info */
defined('NASA_CORE_USER_LOGIGED') or define('NASA_CORE_USER_LOGIGED', is_user_logged_in());

/* bundle type product */
defined('NASA_COMBO_TYPE') or define('NASA_COMBO_TYPE', 'yith_bundle');

/* Nasa theme prefix use for nasa-core */
defined('NASA_THEME_PREFIX') or define('NASA_THEME_PREFIX', 'digi');

/**
 * Cache plugin support
 */
function digi_plugins_cache_support() {
    /**
     * Check WP Super Cache active
     */
    global $super_cache_enabled;
    $super_cache_enabled = isset($super_cache_enabled) ? $super_cache_enabled : false;
    
    $plugin_cache_support = (
        /**
         * Check W3 Total cache active
         */
        (defined('W3TC') && W3TC) ||
            
        /**
         * Check WP Fastest cache
         */
        class_exists('WpFastestCache') ||
            
        /**
         * Check WP Super Cache active
         */
        (defined('WP_CACHE') && WP_CACHE && $super_cache_enabled) ||
            
        /**
         * Check autoptimizeCache active
         */
        class_exists('autoptimizeCache') ||
            
        /**
         * Check WP_ROCKET active
         */
        (defined('WP_ROCKET_SLUG') && WP_ROCKET_SLUG) ||
        
        /**
         * Check SG_CachePress
         */
        class_exists('SG_CachePress') ||
        
        /**
         * Check LiteSpeed Cache
         */
        class_exists('LiteSpeed_Cache')
    );
    
    return apply_filters('digi_plugins_cache_support', $plugin_cache_support);
}

/* Global $content_width */
if (!isset($content_width)){
    $content_width = 1200; /* pixels */
}

/**
 *
 * nasa_upload_dir
 */
if (!isset($nasa_upload_dir)) {
    $nasa_upload_dir = wp_upload_dir();
}

// Init $nasa_opt
$GLOBALS['nasa_opt'] = digi_get_options();
function digi_get_options() {
    $options = get_theme_mods();
    
    if(!empty($options)) {
        foreach ($options as $key => $value) {
            if (is_string($value)) {
                $options[$key] = str_replace(
                    array(
                        '[site_url]', 
                        '[site_url_secure]',
                    ),
                    array(
                        site_url('', 'http'),
                        site_url('', 'https'),
                    ),
                    $value
                );
            }
        }
    }
    
    if(!defined('NASA_PLG_CACHE_ACTIVE') && digi_plugins_cache_support()) {
        define('NASA_PLG_CACHE_ACTIVE', true);
    }
    
    if(defined('NASA_PLG_CACHE_ACTIVE') && NASA_PLG_CACHE_ACTIVE) {
        /**
         * Disable optimized speed
         */
        $options['enable_optimized_speed'] = '0';
    }
    
    return $options;
}

if(NASA_CORE_IN_ADMIN){
    /* wp-admin loading $nasa-opt =============== */
    require_once DIGI_THEME_PATH . '/admin/index.php';
}

add_action('after_setup_theme', 'digi_setup');
if (!function_exists('digi_setup')) :

    function digi_setup() {
        load_theme_textdomain('ADRIAN digi-theme', DIGI_THEME_PATH . '/languages');
        add_theme_support('woocommerce');
        add_theme_support('automatic-feed-links');

        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('custom-background');
        add_theme_support('custom-header');

        register_nav_menus(array(
            'primary' => esc_html__('Menu Principal', 'digi-theme'),
            'topbar-menu' => esc_html__('Menu de haut  - Only show level 1', 'digi-theme'),
            'vetical-menu' => esc_html__('Menu Vertical', 'digi-theme'),
        ));
        
        /**
         * Add custom images sizes
         */
        add_image_size('nasa-parallax-thumb', 870, 300, true);
        if(!has_image_size('nasa-list-thumb')) :
            add_image_size('nasa-list-thumb', 280, 280, true);
        endif;
        if(!has_image_size('nasa-category-thumb')) :
            add_image_size('nasa-category-thumb', 480, 900, true);
        endif;

        require_once DIGI_THEME_PATH . '/cores/nasa-custom-wc-ajax.php';
        require_once DIGI_THEME_PATH . '/cores/nasa-dynamic-style.php';
        require_once DIGI_THEME_PATH . '/cores/nasa-widget-functions.php';
        require_once DIGI_THEME_PATH . '/cores/nasa-theme-options.php';
        require_once DIGI_THEME_PATH . '/cores/nasa-theme-functions.php';
        require_once DIGI_THEME_PATH . '/cores/nasa-woo-functions.php';
        require_once DIGI_THEME_PATH . '/cores/nasa-shop-ajax.php';
        require_once DIGI_THEME_PATH . '/cores/nasa-shop-archive-ajax.php';
        require_once DIGI_THEME_PATH . '/cores/nasa-yith-wcwl-ext.php';
    }

endif;

/**
 * Enqueue scripts and styles
 */
/* add_action('wp_enqueue_scripts', 'digi_dequeue_wc_fragments', 100);
function digi_dequeue_wc_fragments() {
    wp_dequeue_script('wc-cart-fragments');
} */

add_action('wp_enqueue_scripts', 'digi_scripts', 998);
function digi_scripts() {
    global $nasa_opt;
    // Main Css
    wp_enqueue_style('digi-style', get_stylesheet_uri());
    
    wp_enqueue_script('jquery-cookie', DIGI_THEME_URI . '/assets/js/min/jquery.cookie.min.js', array('jquery'), null, true);
    wp_enqueue_script('modernizer', DIGI_THEME_URI . '/assets/js/min/modernizr.min.js', array('jquery'), null, true);
    wp_enqueue_script('jquery-JRespond', DIGI_THEME_URI . '/assets/js/min/jquery.jRespond.min.js', array('jquery'), null, true);
    wp_enqueue_script('jquery-waypoints', DIGI_THEME_URI . '/assets/js/min/jquey.waypoints.js', array('jquery'), null, true);
    wp_enqueue_script('jquery-tipr', DIGI_THEME_URI . '/assets/js/min/jquery.tipr.min.js', array('jquery'), null, true);
    wp_enqueue_script('jquery-variations', DIGI_THEME_URI . '/assets/js/min/jquery.variations.min.js', array('jquery'), null, true);
    
    if(class_exists('WC_AJAX')) {
        $params_variations = array(
            'wc_ajax_url' => WC_AJAX::get_endpoint('%%endpoint%%'),
            'i18n_no_matching_variations_text' => esc_attr__('Desolé, Aucun produit ne correspond a votre selection. Merci de choisir une autre selection.', 'digi-theme'),
            'i18n_make_a_selection_text' => esc_attr__('Veillez selectionner au moins 1 produit avant d&rsquo;ajouter ces produit a votre panier.', 'digi-theme'),
            'i18n_unavailable_text' => esc_attr__('Desolé, Ce produit est indisponible. Merci de choisir une autre selection.', 'digi-theme')
        );
        wp_add_inline_script('jquery-variations', 'var nasa_params_variations=' . json_encode($params_variations) . '; var _quicked_gallery = true;', 'before');
    }
    
    /**
     * magnific popup
     */
    if(!wp_script_is('jquery-magnific-popup')) {
        wp_enqueue_script('jquery-magnific-popup', DIGI_THEME_URI . '/assets/js/min/jquery.magnific-popup.js', array('jquery'), null, true);
    }
    
    /**
     * owl carousel slider
     */
    if(!wp_script_is('owl-carousel')) {
        wp_enqueue_script('owl-carousel', DIGI_THEME_URI . '/assets/js/min/owl.carousel.min.js', array('jquery'), null, true);
    }
    
    /**
     * Slick slider
     */
    if(!wp_script_is('jquery-slick')) {
        wp_enqueue_script('jquery-slick', DIGI_THEME_URI . '/assets/js/min/jquey.slick.min.js', array('jquery'), null, true);
    }
    
    /**
     * Parallax
     */
    wp_enqueue_script('jquery-stellar', DIGI_THEME_URI . '/assets/js/min/jquery.stellar.min.js', array('jquery'), null, true);
    
    /**
     * Countdown js
     */
    if(!wp_script_is('countdown')) {
        wp_enqueue_script('countdown', DIGI_THEME_URI . '/assets/js/min/countdown.min.js', array('jquery'), null, true);
        wp_localize_script(
            'digi-countdown', 'nasa_countdown_l10n',
            array(
                'days'      => esc_html__('Jours', 'digi-theme'),
                'months'    => esc_html__('Mois', 'digi-theme'),
                'weeks'     => esc_html__('Semaines', 'digi-theme'),
                'years'     => esc_html__('Année', 'digi-theme'),
                'hours'     => esc_html__('Heures', 'digi-theme'),
                'minutes'   => esc_html__('Mins', 'digi-theme'),
                'seconds'   => esc_html__('Secs', 'digi-theme'),
                'day'       => esc_html__('Jour', 'digi-theme'),
                'month'     => esc_html__('Mois', 'digi-theme'),
                'week'      => esc_html__('Semaine', 'digi-theme'),
                'year'      => esc_html__('Année', 'digi-theme'),
                'hour'      => esc_html__('Heure', 'digi-theme'),
                'minute'    => esc_html__('Min', 'digi-theme'),
                'second'    => esc_html__('Sec', 'digi-theme')
            )
        );
    }
    
    /**
     * Easy zoom js
     */
    wp_enqueue_script('jquery-easyzoom', DIGI_THEME_URI . '/assets/js/min/jquery.easyzoom.min.js', array('jquery'), null, true);
    
    /**
     * Wow js
     */
    if(!isset($nasa_opt['disable_wow']) || !$nasa_opt['disable_wow']) {
        wp_enqueue_script('wow', DIGI_THEME_URI . '/assets/js/min/wow.min.js', array('jquery'), false, true);
    }
    
    /**
     * Theme js
     */
    wp_enqueue_script('digi-functions-js', DIGI_THEME_URI . '/assets/js/min/functions.min.js', array('jquery'), false, true);
    wp_enqueue_script('digi-js', DIGI_THEME_URI . '/assets/js/min/main.min.js', array('jquery'), false, true);
    
    /**
     * Define ajax options
     */
    if (!defined('NASA_AJAX_OPTIONS') && NASA_WOO_ACTIVED) {
        define('NASA_AJAX_OPTIONS', true);
        
        $ajax_params_options = array(
            'ajax_url'    => WC()->ajax_url(),
            'wc_ajax_url' => WC_AJAX::get_endpoint('%%endpoint%%'),
        );
        
        $ajax_params = 'var nasa_ajax_params=' . json_encode($ajax_params_options) . ';';
        wp_add_inline_script('digi-functions-js', $ajax_params, 'before');
    }

    /**
     * Ignore css
     */
    if (!NASA_CORE_IN_ADMIN) {
        wp_deregister_style('woocommerce-layout');
        wp_deregister_style('woocommerce-smallscreen');
        wp_deregister_style('woocommerce-general');
    }
    
    /**
     * Dequeue contact-form-7 css
     */
    if(function_exists('wpcf7_style_is') && wpcf7_style_is()) {
        wp_dequeue_style('contact-form-7');
    }
    
    /**
     * Dequeue YITH WooCommerce Product Compare colorbox css
     */
    if(class_exists('YITH_Woocompare_Frontend') && (!isset($nasa_opt['nasa-product-compare']) || $nasa_opt['nasa-product-compare'])) {
        wp_dequeue_style('jquery-colorbox');
        wp_dequeue_script('jquery-colorbox');
    }
    
    /**
     * Dequeue YITH WooCommerce Product Wishlist css
     */
    if(NASA_WISHLIST_ENABLE) {
        wp_deregister_style('jquery-selectBox');
        wp_deregister_style('yith-wcwl-font-awesome');
        wp_deregister_style('yith-wcwl-font-awesome-ie7');
        wp_deregister_style('yith-wcwl-main');
    }
    
    /**
     * Dequeue YITH WooCommerce Product Bundles css
     */
    if(defined('YITH_WCPB')) {
        wp_deregister_style('yith_wcpb_bundle_frontend_style');
    }
    
    /**
     * Add css comment reply
     */
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('init', 'digi_post_type_support');
function digi_post_type_support() {
    add_post_type_support('page', 'excerpt');
}

// Default sidebars
add_action('widgets_init', 'digi_widgets_sidebars_init');
function digi_widgets_sidebars_init() {
    register_sidebar(array(
        'name' => esc_html__('Blog Sidebar', 'digi-theme'),
        'id' => 'blog-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
    ));
    
    register_sidebar(array(
        'name' => esc_html__('Shop Sidebar', 'digi-theme'),
        'id' => 'shop-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Product Sidebar', 'digi-theme'),
        'id' => 'product-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
    ));
    
    register_sidebar(array(
        'name' => esc_html__('Custom Sidebar', 'digi-theme'),
        'id' => 'nasa-custom-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
    ));
}

require_once DIGI_THEME_PATH . '/includes/nasa-google-fonts.php';
require_once DIGI_THEME_PATH . '/includes/nasa-ext-wc-query.php';

// Includes Woocommerce widgets custom
require_once DIGI_THEME_PATH . '/widgets/wg-nasa-product-categories.php';
require_once DIGI_THEME_PATH . '/widgets/wg-nasa-product-brands.php';
require_once DIGI_THEME_PATH . '/widgets/wg-nasa-product-filter-price.php';
require_once DIGI_THEME_PATH . '/widgets/wg-nasa-product-filter-variations.php';
require_once DIGI_THEME_PATH . '/widgets/wg-nasa-tag-cloud.php';
