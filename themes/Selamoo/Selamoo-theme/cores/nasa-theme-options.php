<?php
/**
 * Add custom meta to head tag
 */
if (!is_home()) :
    add_action('wp_head', 'digi_share_meta_head');
    if(!function_exists('digi_share_meta_head')):
        function digi_share_meta_head() {
            global $post;
            ?>
            <meta property="og:title" content="<?php the_title(); ?>" />
            <?php if (isset($post->ID)) : ?>
                <?php if (has_post_thumbnail($post->ID)) :
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
                    <meta property="og:image" content="<?php echo esc_url($image[0]); ?>" />
                <?php endif; ?>
            <?php endif; ?>
            <meta property="og:url" content="<?php the_permalink(); ?>" />
            <?php
        }
    endif;
endif;

// **********************************************************************// 
// ! Header main
// **********************************************************************//
add_action('nasa_get_header_theme', 'digi_get_header_theme');
if(!function_exists('digi_get_header_theme')) :
    function digi_get_header_theme() {
        global $woocommerce, $woo_options, $nasa_opt, $post, $wp_query;

        $file = DIGI_CHILD_PATH . '/headers/header-main.php';
        include_once is_file($file) ? $file : DIGI_THEME_PATH . '/headers/header-main.php';
    }
endif;

// **********************************************************************// 
// ! Header Type
// **********************************************************************//
add_filter('custom_header_filter', 'digi_get_header_type');
if(!function_exists('digi_get_header_type')) :
    function digi_get_header_type() {
        global $nasa_opt;

        return isset($nasa_opt['header-type']) ? $nasa_opt['header-type'] : '';
    }
endif;

// **********************************************************************// 
// ! Footer Main
// **********************************************************************//
add_action('nasa_get_footer_theme', 'digi_get_footer_theme');
if(!function_exists('digi_get_footer_theme')) :
    function digi_get_footer_theme() {
        global $nasa_opt;

        $file = DIGI_CHILD_PATH . '/footers/footer-main.php';
        include_once is_file($file) ? $file : DIGI_THEME_PATH . '/footers/footer-main.php';
    }
endif;

// **********************************************************************// 
// ! Footer Type
// **********************************************************************//
add_action('nasa_footer_layout_style', 'digi_footer_layout_style_function');
if(!function_exists('digi_footer_layout_style_function')) :
    function digi_footer_layout_style_function() {
        global $nasa_opt, $wp_query;
        $pageid = $wp_query->get_queried_object_id();
        $footer_id = (int) get_post_meta($pageid, '_nasa_custom_footer', true);
        if (!$footer_id && isset($nasa_opt['footer-type']) && $nasa_opt['footer-type'] != '') {
            $footer_id = (int) $nasa_opt['footer-type'];
        }

        if(!$footer_id) {
            $footers_type = get_posts(array(
                'posts_per_page' => 1,
                'post_type' => 'footer',
                'post_status' => 'publish'
            ));

            $footer = isset($footers_type[0]) ? $footers_type[0] : null;
            $footer_id = isset($footer->ID) ? (int) $footer->ID : null;
        }

        if (class_exists('SitePress') && function_exists('icl_object_id') && (int) $footer_id) {
            $footer_id = icl_object_id($footer_id, 'footer', true);
        }

        if ((int) $footer_id) {
            $shortcodes_custom_css = get_post_meta($footer_id, '_wpb_shortcodes_custom_css', true);
            if (!empty( $shortcodes_custom_css)) {
                $shortcodes_custom_css = strip_tags($shortcodes_custom_css);
                echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
                echo $shortcodes_custom_css;
                echo '</style>';
            }
            
            $post_obj = get_post($footer_id);
            if (isset($post_obj->post_content) && $post_obj->post_status == 'publish') {
                echo do_shortcode($post_obj->post_content);
                return;
            }
        }

        get_template_part('footers/default'); // Default footer
    }
endif;

// **********************************************************************// 
// ! Add Font Awesome, Font Pe7s, Font Elegant
// **********************************************************************//
add_action('wp_enqueue_scripts', 'digi_add_fonts_style');
if(!function_exists('digi_add_fonts_style')) :
    function digi_add_fonts_style() {
        /**
         * Add Font Awesome
         */
        wp_enqueue_style('digi-font-awesome-style', DIGI_THEME_URI . '/assets/font-awesome-4.7.0/css/font-awesome.min.css', array(), false, 'all');
        
        /**
         * Add Font Pe7s
         */
        wp_enqueue_style('digi-font-pe7s-style', DIGI_THEME_URI . '/assets/pe-icon-7-stroke/css/pe-icon-7-stroke.css', array(), false, 'all');
        
        /**
         * Add Font Elegant
         */
        wp_enqueue_style('digi-font-flaticon', DIGI_THEME_URI . '/assets/font-flaticon/flaticon.css', array(), false, 'all');
    }
endif;

// **********************************************************************// 
// ! Other functions
// **********************************************************************// 
add_filter('attachment_link', 'digi_enhanced_image_navigation', 10, 2);
if(!function_exists('digi_enhanced_image_navigation')) :
    function digi_enhanced_image_navigation($url, $id) {
        if (!is_attachment() && !wp_attachment_is_image($id)){
            return $url;
        }

        $image = get_post($id);
        $url .= (!empty($image->post_parent) && $image->post_parent != $id) ? '#main' : '';

        return $url;
    }
endif;

if(!function_exists('digi_short_excerpt')):
    function digi_short_excerpt($limit) {
        $excerpt = explode(' ', get_the_excerpt(), $limit);
        $count = count($excerpt);
        if ($count >= $limit) {
            array_pop($excerpt);
            $excerpt = implode(" ", $excerpt) . '...';
        } else {
            $excerpt = implode(" ", $excerpt);
        }
        $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
        return $excerpt;
    }
endif;

if(!function_exists('digi_content')):
    function digi_content($limit) {
        $content = explode(' ', get_the_content(), $limit);
        $count = count($content);
        if ($count >= $limit) {
            array_pop($content);
            $content = implode(" ", $content) . '...';
        } else {
            $content = implode(" ", $content);
        }
        $content = preg_replace('/\[.+\]/', '', $content);
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        return $content;
    }
endif;

if(!function_exists('digi_hex2rgba')):
    function digi_hex2rgba($color, $opacity = false) {
        $default = 'rgb(0,0,0)';
        if (empty($color)) {
            return $default;
        }
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        $rgb = array_map('hexdec', $hex);

        if ($opacity) {
            if (abs($opacity) > 1)
                $opacity = '1.0';
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        return $output;
    }
endif;
    
add_filter('sod_ajax_layered_nav_product_container', 'digi_nasa_product_container');
if(!function_exists('digi_nasa_product_container')):
    function digi_nasa_product_container($product_container) {
        return 'ul.products';
    }
endif;

// **********************************************************************// 
// ! Get header structure
// **********************************************************************//
add_action('nasa_header_structure', 'digi_get_header_structure');
if (!function_exists('digi_get_header_structure')):

    function digi_get_header_structure() {
        global $woocommerce, $woo_options, $nasa_opt, $post, $wp_query;

        $hstructure = isset($nasa_opt['header-type']) ? $nasa_opt['header-type'] : '1';
        if (isset($post->ID)) {
            $custom_header = get_post_meta($wp_query->get_queried_object_id(), '_nasa_custom_header', true);
            if (!empty($custom_header)) {
                $hstructure = (int) $custom_header;
            }
        }
        $menu_warp_class = array();

        $header_classes = get_post_meta($wp_query->get_queried_object_id(), '_nasa_header_transparent', true) ? ' header-transparent' : '';
        if (get_post_meta($wp_query->get_queried_object_id(), '_nasa_main_menu_transparent', true)) {
            $menu_warp_class[] = ' nasa-menu-transparent';
            $header_classes .= 'nasa-has-menu-transparent';
        } else {
            if (isset($nasa_opt['main_menu_transparent']) && $nasa_opt['main_menu_transparent']) {
                $menu_warp_class[] = ' nasa-menu-transparent';
                $header_classes .= ' nasa-has-menu-transparent';
            }
        }
        
        $menu_style = get_post_meta($wp_query->get_queried_object_id(), '_nasa_main_menu_style', true);
        $menu_style = in_array($menu_style, array('1', '2')) ? $menu_style : (isset($nasa_opt['main_menu_style']) ? $nasa_opt['main_menu_style'] : '1');
        $menu_warp_class[] = 'nasa-nav-style-' . (int) $menu_style;
        $data_padding_y = 20;
        $data_padding_x = $menu_style == '1' ? 15 : 35;
        
        $menu_warp_class = !empty($menu_warp_class) ? ' ' . implode(' ', $menu_warp_class) : '';
        
        $file = DIGI_CHILD_PATH . '/headers/header-structure-' . ((int) $hstructure) . '.php';
        if (is_file($file)) {
            include $file;
        } else {
            $file = DIGI_THEME_PATH . '/headers/header-structure-' . ((int) $hstructure) . '.php';
            include is_file($file) ? $file : DIGI_THEME_PATH . '/headers/header-structure-1.php';
        }
    }

endif;

// **********************************************************************// 
// ! Get header sticky
// **********************************************************************//
add_action('nasa_header_structure', 'digi_get_header_sticky', 9);
if (!function_exists('digi_get_header_sticky')):

    function digi_get_header_sticky() {
        global $nasa_opt;
        if (!isset($nasa_opt['fixed_nav']) || $nasa_opt['fixed_nav']) {
            $file = DIGI_CHILD_PATH . '/headers/sticky.php';
            include is_file($file) ? $file : DIGI_THEME_PATH . '/headers/sticky.php';
        }
    }

endif;

// **********************************************************************// 
// ! Mobile account menu
// **********************************************************************//
if (!function_exists('digi_mobile_account')) :

    function digi_mobile_account() {
        $file = DIGI_CHILD_PATH . '/includes/nasa-mobile-account.php';
        require is_file($file) ? $file : DIGI_THEME_PATH . '/includes/nasa-mobile-account.php';
    }

endif;

// **********************************************************************// 
// ! Header get link current page
// **********************************************************************//
if (!function_exists('digi_get_link_page')) :

    function digi_get_link_page() {
        global $wp;
        return home_url($wp->request);
    }

endif;

add_action('wp_footer', 'digi_run_static_content', 9);
if (!function_exists('digi_run_static_content')) :
    function digi_run_static_content() {
        do_action('nasa_static_content');
    }
endif;

// **********************************************************************// 
// digi_static_content_before
// **********************************************************************//
add_action('nasa_static_content', 'digi_static_content_before', 10);
if (!function_exists('digi_static_content_before')) :
    function digi_static_content_before() {
        echo '<a href="javascript:void(0);" id="nasa-back-to-top" data-wow="bounceIn" class="wow bounceIn hidden-tag"><span class="icon-angle-up"></span></a>';
        
        echo '<!-- Start static content -->' .
            '<div class="static-position">' .
                '<div class="black-window hidden-tag"></div>' .
                '<div class="white-window hidden-tag"></div>' .
                '<div class="transparent-window hidden-tag"></div>';
    }
endif;

// **********************************************************************// 
// digi_static_content_before
// **********************************************************************//
add_action('nasa_static_content', 'digi_static_content_after', 100);
if (!function_exists('digi_static_content_after')) :
    function digi_static_content_after() {
        echo '</div><!-- End static content -->';
    }
endif;

// **********************************************************************// 
// digi_static_for_mobile
// **********************************************************************//
add_action('nasa_static_content', 'digi_static_for_mobile', 12);
if (!function_exists('digi_static_for_mobile')) :

    function digi_static_for_mobile() { ?>
        <div class="warpper-mobile-search hidden-tag">
            <!-- for mobile -->
            <?php
            $search_form_file = DIGI_CHILD_PATH . '/includes/nasa-mobile-product-searchform.php';
            include is_file($search_form_file) ? $search_form_file : DIGI_THEME_PATH . '/includes/nasa-mobile-product-searchform.php'; ?>
        </div>

        <div id="heading-menu-mobile" class="hidden-tag">
            <i class="fa fa-bars"></i><?php esc_html_e('Navigation','digi-theme'); ?>
        </div>
        <div id="mobile-account" class="hidden-tag">
            <?php
            $mobile_acc_file = DIGI_CHILD_PATH . '/includes/nasa-mobile-account.php';
            include is_file($mobile_acc_file) ? $mobile_acc_file : DIGI_THEME_PATH . '/includes/nasa-mobile-account.php'; ?>
        </div>
        <?php
    }

endif;

// **********************************************************************// 
// digi_static_cart_sidebar
// **********************************************************************//
add_action('nasa_static_content', 'digi_static_cart_sidebar', 13);
if (!function_exists('digi_static_cart_sidebar')) :

    function digi_static_cart_sidebar() {
        global $nasa_opt;
        if (!NASA_WOO_ACTIVED || (isset($nasa_opt['disable-cart']) && $nasa_opt['disable-cart'])) {
            return;
        }
        
        $nasa_cart_style = isset($nasa_opt['style-cart']) ? esc_attr($nasa_opt['style-cart']) : 'style-1'; ?>
        <div id="cart-sidebar" class="nasa-static-sidebar hidden-tag <?php echo esc_attr($nasa_cart_style); ?>">
            <div class="nasa-cart-fog hidden-tag"></div>
            <div class="cart-close nasa-sidebar-close">
                <h3 class="nasa-tit-mycart nasa-sidebar-tit"><?php echo esc_html__('CART', 'digi-theme'); ?></h3>
                <a href="javascript:void(0);" title="<?php esc_html_e('Close', 'digi-theme'); ?>"><?php esc_html_e('Close','digi-theme'); ?></a>
                <hr />
            </div>

            <div class="widget_shopping_cart_content cart_sidebar"></div>
            
            <?php // echo digi_loader_html('nasa-cart-sidebar-content'); ?>
            <?php if(isset($_REQUEST['nasa_cart_sidebar']) && $_REQUEST['nasa_cart_sidebar'] == 1) : ?>
                <input type="hidden" name="nasa_cart_sidebar_show" value="1" />
            <?php endif; ?>
        </div>
        <?php
    }

endif;

// **********************************************************************// 
// digi_static_wishlist_sidebar
// **********************************************************************//
add_action('nasa_static_content', 'digi_static_wishlist_sidebar', 14);
if (!function_exists('digi_static_wishlist_sidebar')) :

    function digi_static_wishlist_sidebar() {
        if (!NASA_WOO_ACTIVED || !NASA_WISHLIST_ENABLE) {
            return;
        }
        
        global $nasa_opt;
        $nasa_wishlist_style = isset($nasa_opt['style-wishlist']) ? esc_attr($nasa_opt['style-wishlist']) : 'style-1'; ?>
        <div id="nasa-wishlist-sidebar" class="nasa-static-sidebar hidden-tag <?php echo esc_attr($nasa_wishlist_style); ?>">
            <div class="nasa-wishlist-fog hidden-tag"></div>
            <div class="wishlist-close nasa-sidebar-close">
                <h3 class="nasa-tit-wishlist nasa-sidebar-tit"><?php echo esc_html__('WISHLIST', 'digi-theme'); ?></h3>
                <a href="javascript:void(0);" title="<?php esc_html_e('Close', 'digi-theme'); ?>"><?php esc_html_e('Close', 'digi-theme'); ?></a>
                <hr />
            </div>
            
            <?php echo digi_loader_html('nasa-wishlist-sidebar-content'); ?>
        </div>
        <?php
    }

endif;

// **********************************************************************// 
// digi_static_wishlist_sidebar
// **********************************************************************//
add_action('nasa_static_content', 'digi_static_viewed_sidebar', 15);
if (!function_exists('digi_static_viewed_sidebar')) :

    function digi_static_viewed_sidebar() {
        global $nasa_opt;
        if (!defined('NASA_COOKIE_VIEWED') || !NASA_WOO_ACTIVED || (isset($nasa_opt['disable-viewed']) && $nasa_opt['disable-viewed'])) {
            return;
        } ?>
        
        <?php $nasa_viewed_icon = isset($nasa_opt['style-viewed-icon']) ? esc_attr($nasa_opt['style-viewed-icon']) : 'style-1'; ?>
        <a id="nasa-init-viewed" class="<?php echo esc_attr($nasa_viewed_icon); ?>" href="javascript:void(0);" title="<?php esc_html_e('Products viewed', 'digi-theme'); ?>">
            <i class="pe-icon pe-7s-clock"></i>
            <span class="nasa-init-viewed-text"><?php esc_html_e('Viewed','digi-theme'); ?></span>
        </a>
    
        <?php $nasa_viewed_style = isset($nasa_opt['style-viewed']) ? esc_attr($nasa_opt['style-viewed']) : 'style-1'; ?>
        <!-- viewed product -->
        <div id="nasa-viewed-sidebar" class="nasa-static-sidebar hidden-tag <?php echo esc_attr($nasa_viewed_style); ?>">
            <div class="viewed-close nasa-sidebar-close">
                <h3 class="nasa-tit-viewed nasa-sidebar-tit"><?php echo esc_html__("RECENTLY VIEWED", 'digi-theme'); ?></h3>
                <a href="javascript:void(0);" title="<?php esc_html_e('Close', 'digi-theme'); ?>"><?php esc_html_e('Close','digi-theme'); ?></a>
                <hr />
            </div>
            
            <?php echo digi_loader_html('nasa-viewed-sidebar-content'); ?>
        </div>
        <?php
    }

endif;

// **********************************************************************// 
// digi_static_login_register
// **********************************************************************//
add_action('nasa_static_content', 'digi_static_login_register', 16);
if (!function_exists('digi_static_login_register')) :

    function digi_static_login_register() {
        global $nasa_opt;
        
        if(!NASA_CORE_USER_LOGIGED && shortcode_exists('woocommerce_my_account') && (!isset($nasa_opt['login_ajax']) || $nasa_opt['login_ajax'] == 1)) : ?>
            <div class="nasa-login-register-warper hidden-tag">
                <div id="nasa-login-register-form">
                    <div class="nasa-form-logo-log nasa-no-fix-size-retina">
                        <?php echo digi_logo(); ?>
                    </div>

                    <div class="login-register-close">
                        <a href="javascript:void(0);" title="<?php esc_html_e('Close', 'digi-theme'); ?>"><i class="pe-7s-angle-up"></i></a>
                    </div>
                    <div class="nasa-message"></div>
                    <div class="nasa-form-content">
                        <?php echo digi_loader_html('nasa_customer_login'); ?>
                    </div>
                </div>
            </div>
        <?php
        endif;
    }

endif;

// **********************************************************************// 
// digi_static_compare_sidebar
// **********************************************************************//
add_action('nasa_static_content', 'digi_static_compare_sidebar', 17);
if (!function_exists('digi_static_compare_sidebar')) :

    function digi_static_compare_sidebar() { ?>
        <div class="nasa-compare-list-bottom">
            <div id="nasa-compare-sidebar-content" class="nasa-relative">
                <div class="nasa-loader">
                    <div class="nasa-line"></div>
                    <div class="nasa-line"></div>
                    <div class="nasa-line"></div>
                    <div class="nasa-line"></div>
                </div>
            </div>
            <?php // do_action('nasa_show_mini_compare'); // Show mini compare list ?>
            <p class="nasa-compare-mess nasa-compare-success hidden-tag"></p>
            <p class="nasa-compare-mess nasa-compare-exists hidden-tag"></p>
        </div>
        <?php
    }

endif;

// **********************************************************************// 
// digi_static_nav_extra
// **********************************************************************//
if (!function_exists('digi_get_static_nav_extra')) :

    function digi_get_static_nav_extra() {
        global $nasa_opt;
        
        if((isset($nasa_opt['disable_nav_extra']) && $nasa_opt['disable_nav_extra'] == 1) || !has_nav_menu('primary')) :
            return '';
        endif;
        
        $nasa_menu_extra_style = isset($nasa_opt['style-nav_extra-icon']) ? esc_attr($nasa_opt['style-nav_extra-icon']) : 'style-2';
        
        return
        '<div class="nasa-nav-extra-warp ' . esc_attr($nasa_menu_extra_style) . '">' .
            '<div class="desktop-menu-bar">' .
                '<div class="mini-icon-mobile">' .
                    '<a href="javascript:void(0);" class="nasa-mobile-menu_toggle bar-mobile_toggle">' .
                        '<span class="icon-menu"></span>' .
                    '</a>' .
                '</div>' .
            '</div>' .
        '</div>';
    }

endif;

// **********************************************************************// 
// digi_static_menu_vertical_mobile
// **********************************************************************//
add_action('nasa_static_content', 'digi_static_menu_vertical_mobile', 19);
if (!function_exists('digi_static_menu_vertical_mobile')) :

    function digi_static_menu_vertical_mobile() {
        global $nasa_opt;
        $class = isset($nasa_opt['mobile_menu_layout']) && $nasa_opt['mobile_menu_layout'] == 'light' ? "nasa-light" : "nasa-dark";
        
    ?>
        <div id="nasa-menu-sidebar-content" class="<?php echo $class; ?>">
            <div class="nasa-mobile-nav-wrap">
                <div id="mobile-navigation" class="nasa-loader">
                    <div class="nasa-line"></div>
                    <div class="nasa-line"></div>
                    <div class="nasa-line"></div>
                    <div class="nasa-line"></div>
                </div>
            </div>
            
            <?php echo digi_get_static_nav_extra(); ?>
        </div>
        <?php
    }

endif;

// **********************************************************************// 
// digi_static_config_info
// **********************************************************************//
add_action('nasa_static_content', 'digi_static_config_info', 20);
if (!function_exists('digi_static_config_info')) :

    function digi_static_config_info() {
        global $nasa_opt;
        
        if (!isset($nasa_opt['enable_fixed_add_to_cart']) || $nasa_opt['enable_fixed_add_to_cart']) {
            echo '<input type="hidden" name="nasa_fixed_single_add_to_cart" value="1" />';
        }
        ?>
        
        <input type="hidden" name="nasa_currency_pos" value="<?php echo get_option('woocommerce_currency_pos'); ?>" />
        <input type="hidden" name="nasa_logout_menu" value="<?php echo wp_logout_url(get_home_url()); ?>" />

        <!-- Enable countdown -->
        <input type="hidden" name="nasa-count-down-enable" value="1" />

        <!-- Enable WOW -->
        <input type="hidden" name="nasa-enable-wow" value="<?php echo (!isset($nasa_opt['disable_wow']) || !$nasa_opt['disable_wow']) ? '1' : '0'; ?>" />

        <!-- Enable Portfolio -->
        <input type="hidden" name="nasa-enable-portfolio" value="<?php echo (isset($nasa_opt['enable_portfolio']) && $nasa_opt['enable_portfolio'] == 1) ? '1' : '0'; ?>" />

        <!-- Enable gift effect -->
        <input type="hidden" name="nasa-enable-gift-effect" value="<?php echo (isset($nasa_opt['enable_gift_effect']) && $nasa_opt['enable_gift_effect'] == 1) ? '1' : '0'; ?>" />
        
        <!-- Enable focus main image -->
        <input type="hidden" name="nasa-enable-focus-main-image" value="<?php echo (isset($nasa_opt['enable_focus_main_image']) && $nasa_opt['enable_focus_main_image'] == 1) ? '1' : '0'; ?>" />
        
        <!-- Select option to quickview -->
        <input type="hidden" name="nasa-disable-quickview-ux" value="<?php echo (isset($nasa_opt['disable-quickview']) && $nasa_opt['disable-quickview'] == 1) ? '1' : '0'; ?>" />

        <!-- optimized type load -->
        <input type="hidden" name="nasa-optimized-type" value="<?php echo isset($nasa_opt['optimized_type']) ? $nasa_opt['optimized_type'] : 'sync'; ?>" />

        <p class="hidden-tag" id="nasa-empty-result-search"><?php esc_html_e('Sorry. No results match your search.', 'digi-theme'); ?></p>
        
        <?php
        $shop_url   = NASA_WOO_ACTIVED ? wc_get_page_permalink('shop') : '';
        $base_url   = home_url('/');
        $friendly   = preg_match('/\?post_type\=/', $shop_url) ? '0' : '1';
        if(preg_match('/\?page_id\=/', $shop_url)){
            $friendly = '0';
            $shop_url = $base_url . '?post_type=product';
        }
        
        echo '<input type="hidden" name="nasa-shop-page-url" value="' . esc_url($shop_url) . '" />';
        echo '<input type="hidden" name="nasa-base-url" value="' . esc_url($base_url) . '" />';
        echo '<input type="hidden" name="nasa-friendly-url" value="' . esc_attr($friendly) . '" />';
        
        if (defined('NASA_PLG_CACHE_ACTIVE') && NASA_PLG_CACHE_ACTIVE) :
            echo '<input type="hidden" name="nasa-caching-enable" value="1" />';
        endif;
        
        echo
        '<script type="text/template" id="tmpl-variation-template-nasa">
            <div class="woocommerce-variation-description">{{{data.variation.variation_description}}}</div>
            <div class="woocommerce-variation-price">{{{data.variation.price_html}}}</div>
            <div class="woocommerce-variation-availability">{{{data.variation.availability_html}}}</div>
        </script>
        <script type="text/template" id="tmpl-unavailable-variation-template-nasa">
            <p>' . esc_html__('Sorry, this product is unavailable. Please choose a different combination.', 'digi-theme') . '</p>
        </script>';
    }

endif;

/**
 * Global wishlist template
 */
add_action('nasa_static_content', 'digi_global_wishlist', 25);
if (!function_exists('digi_global_wishlist')):
    function digi_global_wishlist() {
        if (NASA_WISHLIST_ENABLE) {
            $file = DIGI_CHILD_PATH . '/includes/nasa-global-wishlist.php';
            include is_file($file) ? $file : DIGI_THEME_PATH . '/includes/nasa-global-wishlist.php';
        }
    }
endif;

if (!function_exists('digi_loader_html')) :
    function digi_loader_html($id_attr = null) {
        $id = $id_attr != null ? ' id="' . esc_attr($id_attr) . '"' : '';
        return 
            '<div' . $id . ' class="nasa-relative">' .
                '<div class="nasa-loader">' .
                    '<div class="nasa-line"></div>' .
                    '<div class="nasa-line"></div>' .
                    '<div class="nasa-line"></div>' .
                    '<div class="nasa-line"></div>' .
                '</div>' .
            '</div>';
    }
endif;
