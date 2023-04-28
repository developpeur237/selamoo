<?php
// **********************************************************************//
// ! Tiny account
// **********************************************************************//
if (!function_exists('digi_tiny_account')) {

    function digi_tiny_account($icon = false, $user = false, $redirect = false) {
        $login_url = '#';
        $register_url = '#';
        $profile_url = '#';
        
        if (NASA_WOO_ACTIVED) { /* Active woocommerce */
            $myaccount_page_id = get_option('woocommerce_myaccount_page_id');
            if ($myaccount_page_id) {
                $login_url = get_permalink($myaccount_page_id);
                $register_url = $login_url;
                $profile_url = $login_url;
            }
        } else {
            $login_url = wp_login_url();
            $register_url = wp_registration_url();
            $profile_url = admin_url('profile.php');
        }

        $result = '<ul class="nasa-menus-account">';
        if (!NASA_CORE_USER_LOGIGED && !$user) {
            global $nasa_opt;
            $login_ajax = (!isset($nasa_opt['login_ajax']) || $nasa_opt['login_ajax'] == 1) ? '1' : '0';
            $span = $icon ? '<span class="pe7-icon pe-7s-user"></span>' : '';
            $login_title = esc_html__('Register or sign in', 'digi-theme');
            $result .= '<li class="menu-item color"><a class="nasa-login-register-ajax" data-enable="' . $login_ajax . '" href="' . esc_url($login_url) . '" title="' . $login_title . '">' . $span . '<span class="nasa-login-title">' . $login_title . '</span></a></li>';
        } else {
            if(!$redirect) {
                global $wp;
                $redirect = home_url(add_query_arg(array(), $wp->request));
            }
            $logout_url = wp_logout_url($redirect);
        
            $span1 = $icon ? '<span class="pe7-icon pe-7s-user"></span>' : '';
            $span2 = $icon ? '<span class="pe7-icon pe-7s-back"></span>' : '';
            $acc_title = esc_html__('My Account', 'digi-theme');
            $logout_title = esc_html__('Logout', 'digi-theme');
            $result .= 
                '<li class="menu-item"><a href="' . esc_url($profile_url) . '" title="' . $acc_title . '">' . $span1 . $acc_title . '</a></li>' .
                '<li class="menu-item"><a class="nav-top-link" href="' . esc_url($logout_url) . '" title="' . $logout_title . '">' . $span2 . $logout_title . '</a></li>';
        }
        
        return $result . '</ul>';
    }

}

// **********************************************************************//
// Mini cart icon *******************************************************//
// **********************************************************************//
if (!function_exists('digi_mini_cart')) {

    function digi_mini_cart($show = true) {
        global $woocommerce, $nasa_opt, $nasa_mini_cart;
        
        if (!$woocommerce || (isset($nasa_opt['disable-cart']) && $nasa_opt['disable-cart'])) {
            return;
        }
        
        if (!$nasa_mini_cart) {
            if(!defined('YITH_WCPB')) {
                $total = $woocommerce->cart->get_cart_subtotal();
            }
            // Yith plugin Product Bundle
            else {
                $total = 0;
                $cart_items = $woocommerce->cart->get_cart();
                foreach ($cart_items as $cart_item_key => $cart_item) {
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                    if ($_product->exists() && $cart_item['quantity'] > 0) {
                        $price = apply_filters('woocommerce_cart_item_subtotal', $woocommerce->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                        $total += $price ? $_product->get_price() * $cart_item['quantity'] : 0;
                    }
                }
                $total = wc_price($total);
            }
            
            $sl = $show ? '' : ' hidden-tag';
            
            $hasEmpty = $woocommerce->cart->cart_contents_count == 0 ? ' nasa-product-empty' : '';
            $GLOBALS['nasa_mini_cart'] = 
            '<div class="mini-cart cart-inner mini-cart-type-full inline-block">' .
                '<a href="javascript:void(0);" class="cart-link">' .
                    '<div>' .
                        '<span class="nasa-icon cart-icon icon icon-nasa-cart-3"></span>' .
                        '<span class="products-number' . $hasEmpty . $sl . '">' .
                            '<span class="nasa-sl">' .
                                $woocommerce->cart->cart_contents_count .
                            '</span>' .
                            '<span class="hidden-tag nasa-sl-label last">' . esc_html__('Items', 'digi-theme') . '</span>' .
                        '</span>' .
                        '<span class="cart-count' . $sl . '">' .
                            '<span class="total-price">' .
                                $total .
                            '</span>' .
                        '</span>' .
                    '</div>' .
                '</a>' .
            '</div>';
        }
        
        return $nasa_mini_cart ? apply_filters('nasa_mini_cart', $nasa_mini_cart) : '';
    }

}

// *************************************************************************//
// ! Add to cart dropdown - Refresh mini cart content. Input from header type
// *************************************************************************//
add_filter('woocommerce_add_to_cart_fragments', 'digi_add_to_cart_refresh');
if (!function_exists('digi_add_to_cart_refresh')) :
    function digi_add_to_cart_refresh($fragments) {

        $fragments['.cart-inner'] = digi_mini_cart();
        $fragments['div.widget_shopping_cart_content'] = digi_mini_cart_sidebar(true);

        return $fragments;
    }
endif;

// **********************************************************************//
// ! Mini cart sidebar
// **********************************************************************//
if (!function_exists('digi_mini_cart_sidebar')) :

    function digi_mini_cart_sidebar($str = false) {
        global $woocommerce, $nasa_opt;
        
        $empty = '<p class="empty"><i class="nasa-empty-icon icon-nasa-cart-2"></i>' . esc_html__('No products in the cart.', 'digi-theme') . '<a href="javascript:void(0);" class="button nasa-sidebar-return-shop">' . esc_html__('RETURN TO SHOP', 'digi-theme') . '</a></p>';
        
        if (!$woocommerce || (isset($nasa_opt['disable-cart']) && $nasa_opt['disable-cart'])){
            return $empty;
        }
        
        ob_start();
        $file = DIGI_CHILD_PATH . '/includes/nasa-sidebar-cart.php';
        require is_file($file) ? $file : DIGI_THEME_PATH . '/includes/nasa-sidebar-cart.php';
        $content = ob_get_clean();
        if ($str) {
            return $content;
        }
        echo '<div class="empty hidden-tag">' . $empty . '</div>';
        echo $content;
    }

endif;

// **********************************************************************//
// ! Mini wishlist sidebar
// **********************************************************************//
if (!function_exists('digi_mini_wishlist_sidebar')) {

    function digi_mini_wishlist_sidebar($str = false) {
        global $woocommerce, $nasa_opt;
        if (!$woocommerce){
            return '';
        }
        
        ob_start();
        $file = DIGI_CHILD_PATH . '/includes/nasa-sidebar-wishlist.php';
        require is_file($file) ? $file : DIGI_THEME_PATH . '/includes/nasa-sidebar-wishlist.php';
        $content = ob_get_clean();
        if ($str) {
            return $content;
        }
        echo $content;
    }

}

if(!function_exists('digi_add_to_cart_in_wishlist')) :
    function digi_add_to_cart_in_wishlist() {
        global $product, $nasa_opt, $head_type;

        if (isset($nasa_opt['disable-cart']) && $nasa_opt['disable-cart']) {
            return '';
        }

        if(!$head_type) {
            $head_type = $nasa_opt['header-type'];
            global $wp_query;
            $custom_header = get_post_meta($wp_query->get_queried_object_id(), '_nasa_custom_header', true);
            if (!empty($custom_header)) {
                $head_type = (int) $custom_header;
            }

            $GLOBALS['head_type'] = $head_type;
        }

        $title = $product->add_to_cart_text();
        $product_type = $product->get_type();
        $enable_button_ajax = false;
        if($product->is_in_stock() && $product->is_purchasable()) {
            if($product_type == 'simple' || ($product_type == NASA_COMBO_TYPE && $product->all_items_in_stock())) {
                $url = 'javascript:void(0);';
                $enable_button_ajax = true;
            } else {
                $url = esc_url($product->add_to_cart_url());
            }
        }
        else {
            return '';
        }

        $result = apply_filters(
            'woocommerce_loop_add_to_cart_link',
            sprintf(
                '<a href="%s" rel="nofollow" data-quantity="1" data-product_id="%s" data-product_sku="%s" class="button-in-wishlist small btn-from-wishlist %s product_type_%s add-to-cart-grid" data-type="%s" data-head_type="%s" title="%s">' .
                    '<span class="cart-icon nasa-icon icon-nasa-cart-3"></span>' .
                    '<span class="add_to_cart_text">%s</span>' .
                    '<span class="cart-icon-handle"></span>' .
                '</a>',
                $url, //link
                esc_attr($product->get_id()), //product id
                esc_attr($product->get_sku()), //product sku
                $enable_button_ajax ? 'nasa_add_to_cart_from_wishlist' : '', //class name
                esc_attr($product_type), esc_attr($product_type), //product type
                esc_html($head_type), $title, $title
            ),
            $product
        );
        
        return $result;
    }
endif;

// **********************************************************************//
// ! Add to cart button
// **********************************************************************//
if (!function_exists('digi_add_to_cart_btn')):
    function digi_add_to_cart_btn($type = 'small', $echo = true, $tiptop = true, $customClass = '') {
        global $product, $nasa_opt, $head_type;

        if (isset($nasa_opt['disable-cart']) && $nasa_opt['disable-cart']) {
            return '';
        }

        if(!$head_type) {
            $head_type = $nasa_opt['header-type'];
            global $wp_query;
            $custom_header = get_post_meta($wp_query->get_queried_object_id(), '_nasa_custom_header', true);
            if (!empty($custom_header)) {
                $head_type = (int) $custom_header;
            }

            $GLOBALS['head_type'] = $head_type;
        }

        $productId = $product->get_id();
        $product_type = $product->get_type();
        $productVariable = null;
        $class_btn = $data_type = '';
        if($product->is_purchasable() && $product->is_in_stock()) {
            if($product_type == 'simple') {
                $class_btn .= 'add_to_cart_button ajax_add_to_cart';
            } elseif ($product_type == NASA_COMBO_TYPE) {
                $class_btn .= 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ? 'add_to_cart_button nasa_bundle_add_to_cart' : 'add_to_cart_button';
                $data_type = ' data-type="' . $product_type . '" ';
            }
            elseif ($product_type == 'variation') {
                $product_type = 'variable';
                $parent_id = wp_get_post_parent_id($productId);
                $productVariable = wc_get_product($parent_id);
            }
        }
        $class_btn .= $customClass != '' ? ' ' . $customClass : $customClass;
        $result = '';
        $title = esc_html(!$productVariable ? $product->add_to_cart_text() : $productVariable->add_to_cart_text()); //add to cart text;
        switch ($type) {
            case 'large':
                $result .= apply_filters(
                    'woocommerce_loop_add_to_cart_link',
                    sprintf(
                        '<div class="add-to-cart-btn">' .
                            '<a href="%s" rel="nofollow" data-product_id="%s" class="%s button small product_type_%s add-to-cart-grid" ' . $data_type . ' data-head_type="%s" title="%s">' .
                                '<span class="add_to_cart_text">%s</span>' .
                                '<span class="cart-icon-handle"></span>' .
                            '</a>' .
                        '</div>',
                        esc_url($product->add_to_cart_url()), //link
                        esc_attr($productId), //product id
                        esc_attr($class_btn), //class name
                        esc_attr($product_type), //product type
                        esc_html($head_type), $title, $title
                    ),
                    $product
                );
                break;
            case 'small':
            default:
                $result .= apply_filters(
                    'woocommerce_loop_add_to_cart_link',
                    sprintf(
                        '<div class="add-to-cart-btn">' .
                            '<div class="btn-link">' .
                                '<a href="%s" rel="nofollow" data-quantity="1" data-product_id="%s" data-product_sku="%s" class="%s product_type_%s add-to-cart-grid button small" ' . $data_type . ' data-head_type="%s" title="%s">' .
                                    '<span class="cart-icon pe-icon pe-7s-cart"></span>' .
                                    '<span class="add_to_cart_text">%s</span>' .
                                    '<span class="cart-icon-handle"></span>' .
                                '</a>' .
                            '</div>' .
                        '</div>',
                        esc_url($product->add_to_cart_url()), //link
                        esc_attr($productId), //product id
                        esc_attr($product->get_sku()), //product sku
                        esc_attr($class_btn), //class name
                        esc_attr($product_type), //product type
                        esc_html($head_type), $title, $title
                    ),
                    $product
                );
                break;
        }

        if (!$echo) {
            return $result;
        }
        echo $result;
    }
endif;

// Product group button
if (!function_exists('digi_product_group_button')):
    function digi_product_group_button($combo_show_type = 'popup') {
        ob_start();
        $file = DIGI_CHILD_PATH . '/includes/nasa-product-buttons.php';
        include is_file($file) ? $file : DIGI_THEME_PATH . '/includes/nasa-product-buttons.php';

        return ob_get_clean();
    }
endif;

// **********************************************************************//
// ! Wishlist link
// **********************************************************************//
if (!function_exists('digi_tini_wishlist')):
    function digi_tini_wishlist($icon = false) {
        if (!NASA_WOO_ACTIVED || !NASA_WISHLIST_ENABLE) {
            return;
        }

        $tini_wishlist = '';
        $wishlist_page_id = get_option('yith_wcwl_wishlist_page_id');
        if (function_exists('icl_object_id')) {
            $wishlist_page_id = icl_object_id($wishlist_page_id, 'page', true);
        }
        $wishlist_page = get_permalink($wishlist_page_id);

        $span = $icon ? '<span class="icon-nasa-like"></span>' : '';
        $tini_wishlist .= '<a href="' . esc_url($wishlist_page) . '" title="' . esc_html__('Wishlist', 'digi-theme') . '">' . $span . esc_html__('Wishlist', 'digi-theme') . '</a>';

        return $tini_wishlist;
    }
endif;

// **********************************************************************//
// ! Wishlist link
// **********************************************************************//
if (!function_exists('digi_icon_wishlist')):
    function digi_icon_wishlist() {
        if (!NASA_WOO_ACTIVED || !NASA_WISHLIST_ENABLE) {
            return;
        }

        global $nasa_icon_wishlist;
        if(!$nasa_icon_wishlist) {
            $show = defined('NASA_PLG_CACHE_ACTIVE') && NASA_PLG_CACHE_ACTIVE ? false : true;
            $count = digi_get_count_wishlist($show);
            
            $GLOBALS['nasa_icon_wishlist'] = 
            '<a class="wishlist-link" href="javascript:void(0);" title="' . esc_html__('Wishlist', 'digi-theme') . '">' .
                '<i class="nasa-icon icon-nasa-like"></i>' .
                $count .
            '</a>';
        }

        return $nasa_icon_wishlist ? $nasa_icon_wishlist : '';
    }
endif;

if (!function_exists('digi_get_count_wishlist')):
    function digi_get_count_wishlist($show = true) {
        if (!NASA_WOO_ACTIVED || !NASA_WISHLIST_ENABLE) {
            return '';
        }
        
        $count = yith_wcwl_count_products();
        $hasEmpty = (int) $count == 0 ? ' nasa-product-empty' : '';
        $sl = $show ? '' : ' hidden-tag';
        
        return '<span class="nasa-wishlist-count wishlist-number' . $hasEmpty . '">' .
                    '<span class="nasa-text hidden-tag">' . esc_html__('Wishlist', 'digi-theme') . '</span>' .
                    '<span class="nasa-sl' . $sl . '">' . (int) $count . '</span>' .
                '</span>';
    }
endif;

// **********************************************************************//
// ! Compare link
// **********************************************************************//
if (!function_exists('digi_icon_compare')):

    function digi_icon_compare() {
        if (!NASA_WOO_ACTIVED || !defined('YITH_WOOCOMPARE')) {
            return;
        }

        global $nasa_icon_compare, $nasa_opt;
        if(!$nasa_icon_compare) {
            global $yith_woocompare;
            
            if(!isset($nasa_opt['nasa-product-compare']) || $nasa_opt['nasa-product-compare']) {
                $view_href = isset($nasa_opt['nasa-page-view-compage']) && (int) $nasa_opt['nasa-page-view-compage'] ? get_permalink((int) $nasa_opt['nasa-page-view-compage']) : home_url('/');
                $class = 'nasa-show-compare';
            } else {
                $view_href = add_query_arg(array('iframe' => 'true'), $yith_woocompare->obj->view_table_url());
                $class = 'compare';
            }
            
            $show = defined('NASA_PLG_CACHE_ACTIVE') && NASA_PLG_CACHE_ACTIVE ? false : true;
            $count = digi_get_count_compare($show);
            
            $GLOBALS['nasa_icon_compare'] = 
            '<span class="yith-woocompare-widget">' .
                '<a href="' . esc_url($view_href) . '" title="' . esc_html__('Compare', 'digi-theme') . '" class="' . esc_attr($class) . '">' .
                    '<i class="nasa-icon icon-nasa-refresh"></i>' .
                    $count .
                '</a>' .
            '</span>';
        }
        
        return $nasa_icon_compare ? $nasa_icon_compare : '';
    }

endif;

if (!function_exists('digi_get_count_compare')):
    function digi_get_count_compare($show = true) {
        if (!NASA_WOO_ACTIVED || !defined('YITH_WOOCOMPARE')) {
            return '';
        }
        
        global $yith_woocompare;
        
        $count = count($yith_woocompare->obj->products_list);
        $hasEmpty = (int) $count == 0 ? ' nasa-product-empty' : '';
        
        $sl = $show ? '' : ' hidden-tag';
        
        return '<span class="nasa-compare-count compare-number' . $hasEmpty . '">' .
                    '<span class="nasa-text hidden-tag">' . esc_html__('Compare ', 'digi-theme') . ' </span>' .
                    '<span class="nasa-sl' . $sl . '">' . (int) $count . '</span>' .
                '</span>';
    }
endif;

if (!function_exists('digi_get_cat_header')):

    function digi_get_cat_header($catId = null) {
        global $nasa_opt;
        if (isset($nasa_opt['enable_cat_header']) && $nasa_opt['enable_cat_header'] != '1') {
            return '';
        }

        $content = '<div class="cat-header nasa-cat-header">';
        $do_content = '';
        
        if ((int) $catId > 0) {
            $shortcode = function_exists('get_term_meta') ? get_term_meta($catId, 'cat_header', false) : get_woocommerce_term_meta($catId, 'cat_header', false);
            $do_content = isset($shortcode[0]) ? do_shortcode($shortcode[0]) : '';
        }

        if (trim($do_content) === '') {
            if (isset($nasa_opt['cat_header']) && $nasa_opt['cat_header'] != '') {
                $do_content = do_shortcode($nasa_opt['cat_header']);
            }
        }

        if (trim($do_content) === '') {
            return '';
        }

        $content .= $do_content . '</div>';

        return $content;
    }

endif;

if (!function_exists('digi_get_product_meta_value')):

    function digi_get_product_meta_value($post_id, $field_id = null) {
        $meta_value = get_post_meta($post_id, 'wc_productdata_options', true);
        if (isset($meta_value[0]) && $field_id) {
            return isset($meta_value[0][$field_id]) ? $meta_value[0][$field_id] : '';
        }

        return isset($meta_value[0]) ? $meta_value[0] : $meta_value;
    }

endif;

add_action('nasa_search_by_cat', 'digi_search_by_cat', 10, 1);
if (!function_exists('digi_search_by_cat')):
    function digi_search_by_cat($echo = true) {
        global $nasa_opt;
        
        $select = '';
        if(NASA_WOO_ACTIVED && (!isset($nasa_opt['search_by_cat']) || $nasa_opt['search_by_cat'] == 1)){
            $select .= '<select name="product_cat">';
            $select .= '<option value="">' . esc_html__('Categories', 'digi-theme') . '</option>';
            
            $slug = get_query_var('product_cat');
            $nasa_catActive = $slug ? $slug : '';
            $args = array(
                'taxonomy' => 'product_cat',
                'parent' => 0,
                'hide_empty' => false,
                'orderby' => 'name'
            );
            
            if(version_compare(wc()->version, '3.3.0', ">=") && (!isset($nasa_opt['show_uncategorized']) || !$nasa_opt['show_uncategorized'])) {
                $args['exclude'] = get_option('default_product_cat');
            }
            
            $nasa_terms = get_terms($args);
            
            if($nasa_terms) {
                foreach ($nasa_terms as $v) {
                    $select .= '<option data-term_id="' . esc_attr($v->term_id) . '" value="' . esc_attr($v->slug) . '"' . (($nasa_catActive == $v->slug) ? ' selected' : '') . '>' . esc_attr($v->name) . '</option>';
                    digi_get_child($v, $select, $nasa_catActive);
                }
            }
            
            $select .= '</select>';
        }
        
        if(!$echo){
            return $select;
        }
        
        echo $select;
    }

endif;

if (!function_exists('digi_get_child')):
    function digi_get_child($obj, &$select, $nasa_catActive, $pad = '') {
        $childs = get_terms(array(
            'taxonomy' => 'product_cat',
            'parent' => $obj->term_id,
            'hide_empty' => false,
            'orderby' => 'name'
        ));

        if(!empty($childs)){
            $pad .= '&nbsp;&nbsp;&nbsp;';
            foreach ($childs as $v){
                $select .= '<option data-term_id="' . esc_attr($v->term_id) . '" value="' . esc_attr($v->slug) . '"' . (($nasa_catActive == $v->slug) ? ' selected' : '') . '>' . $pad . esc_attr($v->name) . '</option>';
                digi_get_child($v, $select, $nasa_catActive, $pad);
            }
        }
    }
endif;

// Nasa root categories in Shop Top bar
// <a href="Category link" data-id="cat_id" class="nasa-filter-by-cat">Category name</a>
add_action('nasa_root_cats', 'digi_get_root_categories');
if (!function_exists('digi_get_root_categories')):
    
    function digi_get_root_categories() {
        global $nasa_opt;
        
        $content = '';
        
        if(isset($nasa_opt['top_filter_rootcat']) && !$nasa_opt['top_filter_rootcat']) {
            echo $content;
            return;
        }
        
        if (!is_post_type_archive('product') && !is_tax(get_object_taxonomies('product'))) {
            echo $content;
            return;
        }
        
        if(NASA_WOO_ACTIVED && (!isset($nasa_opt['category_sidebar']) || $nasa_opt['category_sidebar'] == 'top')){
            $nasa_terms = get_terms(array(
                'taxonomy' => 'product_cat',
                'parent' => 0,
                'hide_empty' => false,
                'orderby' => 'name'
            ));
            
            if($nasa_terms) {
                $slug = get_query_var('product_cat');
                $nasa_catActive = $slug ? $slug : '';
                $content .= '<div class="nasa-transparent-topbar"></div>';
                $content .= '<div class="nasa-root-cat-topbar-warp hidden-tag"><ul class="nasa-root-cat product-categories">';
                $content .= '<li class="nasa_odd"><span class="nasa-root-cat-header">' . esc_html__('CATEGORIES', 'digi-theme'). '</span></li>';
                $li_class = 'nasa_even';
                foreach ($nasa_terms as $v) {
                    $class_active = $nasa_catActive == $v->slug ? ' nasa-active' : '';
                    $content .= '<li class="cat-item cat-item-' . esc_attr($v->term_id) . ' cat-item-accessories root-item ' . $li_class . '">';
                    $content .= '<a href="' . esc_url(get_term_link($v)) . '" data-id="' . esc_attr($v->term_id) . '" class="nasa-filter-by-cat' . $class_active . '" title="' . esc_attr($v->name) . '" data-taxonomy="product_cat">' . esc_attr($v->name) . '</a>';
                    $content .= '</li>';
                    $li_class = $li_class == 'nasa_even' ? 'nasa_odd' : 'nasa_even';
                }
                
                $content .= '</ul></div>';
            }
        }
        
        $icon = $content != '' ? '<div class="nasa-icon-cat-topbar"><a href="javascript:void(0);"><i class="pe-7s-menu"></i><span class="inline-block">' . esc_html__('BROWSE', 'digi-theme') . '</span></a></div>' : '';
        $content = $icon . $content;
        
        echo $content;
    }

endif;

// Nasa childs category in Shop Top bar
add_action('nasa_child_cat', 'digi_get_childs_category', 10, 2);
if (!function_exists('digi_get_childs_category')):
    
    function digi_get_childs_category($term = null, $instance = array()) {
        $content = '';
        
        if(NASA_WOO_ACTIVED){
            global $wp_query, $nasa_opt;
            
            $term = $term == null ? $wp_query->get_queried_object() : $term;
            $parent_id = is_numeric($term) ? $term : (isset($term->term_id) ? $term->term_id : 0);
            
            $args = array(
                'taxonomy' => 'product_cat',
                'parent' => $parent_id,
                'hierarchical' => true,
                'hide_empty' => false,
                'orderby' => 'name'
            );
            
            if(version_compare(wc()->version, '3.3.0', ">=") && (!isset($nasa_opt['show_uncategorized']) || !$nasa_opt['show_uncategorized'])) {
                $args['exclude'] = get_option('default_product_cat');
            }
            
            $nasa_terms = get_terms($args);
            if (!$nasa_terms) {
                $term_root = get_ancestors($parent_id, 'product_cat');
                $term_parent = isset($term_root[0]) ? $term_root[0] : 0;
                $args['parent'] = $term_parent;
                $nasa_terms = get_terms($args);
            }
            
            if($nasa_terms) {
                $show = isset($instance['show_items']) ? (int) $instance['show_items'] : 0;
                $content .= '<ul class="nasa-children-cat product-categories nasa-product-child-cat-top-sidebar">';
                $items = 0;
                foreach ($nasa_terms as $v) {
                    $class_active = $parent_id == $v->term_id ? ' nasa-active' : '';
                    $class_li = ($show && $items >= $show) ? ' nasa-show-less' : '';
                    
                    $icon = '';
                    if (isset($instance['cat_' . $v->slug]) && trim($instance['cat_' . $v->slug]) != '') {
                        $icon = '<i class="' . $instance['cat_' . $v->slug] . '"></i>';
                        $icon .= '&nbsp;&nbsp;';
                    }
                    
                    $content .= '<li class="cat-item cat-item-' . esc_attr($v->term_id) . ' cat-item-accessories root-item' . $class_li . '">';
                    $content .= '<a href="' . esc_url(get_term_link($v)) . '" data-id="' . esc_attr($v->term_id) . '" class="nasa-filter-by-cat' . $class_active . '" title="' . esc_attr($v->name) . '" data-taxonomy="product_cat">';
                    $content .= '<div class="nasa-cat-warp">';
                    $content .= '<h5 class="nasa-cat-title">';
                    $content .= $icon . esc_attr($v->name);
                    $content .= '</h5>';
                    $content .= '</div>';
                    $content .= '</a>';
                    $content .= '</li>';
                    $items++;
                }
                
                if ($show && ($items > $show)) {
                    $content .= '<li class="nasa_show_manual"><a data-show="1" class="nasa-show" href="javascript:void(0);">' . esc_html__('+ Show more', 'digi-theme') . '</a><a data-show="0" class="nasa-hidden" href="javascript:void(0);">' . esc_html__('- Show less', 'digi-theme') . '</a></li>';
                }
                
                $content .= '</ul>';
            }
        }
        
        echo $content;
    }

endif;

function digi_category_thumbnail($category, $type = 'shop_thumbnail') {
    $small_thumbnail_size = apply_filters('single_product_small_thumbnail_size', $type);
    $thumbnail_id = function_exists('get_term_meta') ? get_term_meta($category->term_id, 'thumbnail_id', true) : get_woocommerce_term_meta($category->term_id, 'thumbnail_id', true);

    if ($thumbnail_id) {
        $image = wp_get_attachment_image_src($thumbnail_id, $small_thumbnail_size);
        $image = $image[0];
    } else {
        $image = wc_placeholder_img_src();
    }

    if ($image) {
        $image = str_replace(' ', '%20', $image);
        return '<img src="' . esc_url($image) . '" alt="' . esc_attr($category->name) . '" />';
    }
    
    return '';
}

// Login Or Register Form
add_action('nasa_login_register_form', 'digi_login_register_form', 10, 1);
if(!function_exists('digi_login_register_form')) :
    function digi_login_register_form($prefix = false) {
        global $woocommerce, $nasa_opt;
        if(!$woocommerce) {
            return;
        }
        
        include DIGI_THEME_PATH . '/includes/nasa-login-register-form.php';
    }
endif;

// Get term description
if(!function_exists('digi_term_description')) :
    function digi_term_description($term_id, $type_taxonomy) {
        if(!NASA_WOO_ACTIVED) {
            return '';
        }
        
        if((int) $term_id < 1) {
            $shop_page = get_post(wc_get_page_id('shop'));
            $desc = $shop_page ? wc_format_content($shop_page->post_content) : '';
        } else {
            $term = get_term($term_id, $type_taxonomy);
            $desc = isset($term->description) ? $term->description : '';
        }
        
        return trim($desc) != '' ? '<div class="page-description">' . do_shortcode($desc) . '</div>' : '';
    }
endif;

// get value custom field nasa-core
if(!function_exists('digi_get_custom_field_value')) :
function digi_get_custom_field_value($post_id, $field_id) {
    $meta_value = get_post_meta($post_id, 'wc_productdata_options', true);
    if ($meta_value) {
        $meta_value = $meta_value[0];
    }

    return isset($meta_value[$field_id]) ? $meta_value[$field_id] : '';
}
endif;

// Add action archive-product get content product.
add_action('nasa_get_content_products', 'digi_get_content_products', 10, 1);
if(!function_exists('digi_get_content_products')) :
    function digi_get_content_products($nasa_sidebar = 'top') {
        global $nasa_opt, $wp_query;

        $file = DIGI_CHILD_PATH . '/includes/nasa-get-content-products.php';
        require is_file($file) ? $file : DIGI_THEME_PATH . '/includes/nasa-get-content-products.php';
    }
endif;

// Number post_per_page shop/archive_product
add_filter('loop_shop_per_page', 'digi_loop_shop_per_page', 20);
if(!function_exists('digi_loop_shop_per_page')) :
    function digi_loop_shop_per_page($post_per_page) {
        global $nasa_opt;
        $post_per_page = (isset($nasa_opt['products_pr_page']) && (int) $nasa_opt['products_pr_page']) ? (int) $nasa_opt['products_pr_page'] : get_option('posts_per_page');
        
        return $post_per_page;
    }
endif;

// Number relate products
add_filter('woocommerce_output_related_products_args', 'digi_output_related_products_args');
if(!function_exists('digi_output_related_products_args')) :
    function digi_output_related_products_args($args) {
        global $nasa_opt;
        $args['posts_per_page'] = (isset($nasa_opt['release_product_number']) && (int) $nasa_opt['release_product_number']) ? (int) $nasa_opt['release_product_number'] : 12;
        return $args;
    }
endif;

// Compare list in bot site
add_action('nasa_show_mini_compare', 'digi_show_mini_compare');
if(!function_exists('digi_show_mini_compare')) :
    function digi_show_mini_compare() {
        global $nasa_opt, $yith_woocompare;
        
        if(isset($nasa_opt['nasa-product-compare']) && !$nasa_opt['nasa-product-compare']) {
            echo '';
            return;
        }
        
        $nasa_compare = isset($yith_woocompare->obj) ? $yith_woocompare->obj : $yith_woocompare;
        if(!$nasa_compare) {
            echo '';
            return;
        }
        
        if(!isset($nasa_opt['nasa-page-view-compage']) || !(int) $nasa_opt['nasa-page-view-compage']) {
            $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'page-view-compare.php'
            ));
            
            if($pages) {
                foreach ($pages as $page) {
                    $nasa_opt['nasa-page-view-compage'] = (int) $page->ID;
                    break;
                }
            }
        }
        
        $view_href = isset($nasa_opt['nasa-page-view-compage']) && (int) $nasa_opt['nasa-page-view-compage'] ? get_permalink((int) $nasa_opt['nasa-page-view-compage']) : home_url('/');
        
        $nasa_compare_list = $nasa_compare->get_products_list();
        $max_compare = isset($nasa_opt['max_compare']) ? (int) $nasa_opt['max_compare'] : 4;
        
        $file = DIGI_CHILD_PATH . '/includes/nasa-mini-compare.php';
        require is_file($file) ? $file : DIGI_THEME_PATH . '/includes/nasa-mini-compare.php';
    }
endif;

/**
 * Default page compare
 */
if(!function_exists('digi_products_compare_content')) :
    function digi_products_compare_content() {
        global $nasa_opt, $yith_woocompare;
        
        if(isset($nasa_opt['nasa-product-compare']) && !$nasa_opt['nasa-product-compare']) {
            return '';
        }
        
        $nasa_compare = isset($yith_woocompare->obj) ? $yith_woocompare->obj : $yith_woocompare;
        if(!$nasa_compare) {
            return '';
        }
        
        ob_start();
        $file = DIGI_CHILD_PATH . '/includes/nasa-view-compare.php';
        require is_file($file) ? $file : DIGI_THEME_PATH . '/includes/nasa-view-compare.php';
        
        return ob_get_clean();
    }
endif;

/* ======================================================================= */
/* NEXT - PREV PRODUCTS */
/* ======================================================================= */
add_action('next_prev_product', 'digi_prev_product');
add_action('next_prev_product', 'digi_next_product');
/* NEXT / PREV NAV ON PRODUCT PAGES */
function digi_next_product() {
    $next_post = get_next_post(true, '', 'product_cat');
    if (is_a($next_post, 'WP_Post')) {
        $product_obj = new WC_Product($next_post->ID);
        $title = get_the_title($next_post->ID);
        $link = get_the_permalink($next_post->ID);
        ?>
        <div class="next-product next-prev-buttons">
            <a href="<?php echo esc_url($link); ?>" rel="next" class="icon-next-prev icon-angle-right next" title="<?php echo esc_attr($title); ?>"></a>
            <div class="dropdown-wrap">
                <a title="<?php echo esc_attr($title); ?>" href="<?php echo esc_url($link); ?>">
                    <?php echo get_the_post_thumbnail($next_post->ID, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail')); ?>
                    <div>
                        <span class="product-name"><?php echo $title; ?></span>
                        <span class="price"><?php echo $product_obj->get_price_html(); ?></span>
                    </div>
                </a>
            </div>
        </div>
        <?php
    }
}

function digi_prev_product() {
    $prev_post = get_previous_post(true, '', 'product_cat');
    if (is_a($prev_post, 'WP_Post')) {
        $product_obj = new WC_Product($prev_post->ID);
        $title = get_the_title($prev_post->ID);
        $link = get_the_permalink($prev_post->ID);
        ?>
        <div class="prev-product next-prev-buttons">
            <a href="<?php echo esc_url($link); ?>" rel="prev" class="icon-next-prev icon-angle-left prev" title="<?php echo esc_attr($title); ?>"></a>
            <div class="dropdown-wrap">
                <a title="<?php echo esc_attr($title); ?>" href="<?php echo esc_url($link); ?>">
                    <?php echo get_the_post_thumbnail($prev_post->ID, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail')); ?>
                    <div>
                        <span class="product-name"><?php echo $title; ?></span>
                        <span class="price"><?php echo $product_obj->get_price_html(); ?></span>
                    </div>
                </a>
            </div>
        </div>
        <?php
    }
}

/* ==========================================================================
 * ADD VIDEO PLAY BUTTON ON PRODUCT DETAIL PAGE
 * ======================================================================= */
add_action('product_video_btn', 'digi_product_video_btn_function', 1);
if (!function_exists('digi_product_video_btn_function')) {

    function digi_product_video_btn_function() {
        $id = get_the_ID();
        if ($video_link = digi_get_custom_field_value($id, '_product_video_link')) {
            ?>
            <a class="product-video-popup tip-top" data-tip="<?php esc_html_e('View video', 'digi-theme'); ?>" href="<?php echo esc_url($video_link); ?>"><span class="pe-7s-play"></span>
                <span class="nasa-play-video-text"><?php esc_html_e('Play Video', 'digi-theme'); ?></span>
            </a>
            <?php
            $height = '800';
            $width = '800';
            $iframe_scale = '100%';
            $custom_size = digi_get_custom_field_value($id, '_product_video_size');
            if ($custom_size) {
                $split = explode("x", $custom_size);
                $height = $split[0];
                $width = $split[1];
                $iframe_scale = ($width / $height * 100) . '%';
            }
            $style = '.has-product-video .mfp-iframe-holder .mfp-content{max-width: ' . $width . 'px;}';
            $style .= '.has-product-video .mfp-iframe-scaler{padding-top: ' . $iframe_scale . '}';
            wp_add_inline_style('product_detail_css_custom', $style);
        }
    }

}

/**
 * Remove wishlist button in detail product
 */
if (!function_exists('digi_remove_btn_wishlist_single_product')) :
    function digi_remove_btn_wishlist_single_product($hook) {
        $hook['add-to-cart'] = array('hook' => '', 'priority' => 0);
        
        return $hook;
    }
endif;

if (!function_exists('digi_remove_default_wishlist_button')) :
    function digi_remove_default_wishlist_button($positions) {
        $positions = array();
        
        return $positions;
    }
endif;

/*
 * digi add wishlist in list
 */
if(!function_exists('digi_add_wishlist_button')) :
    function digi_add_wishlist_button() {
        if (NASA_WISHLIST_ENABLE) {
            global $product, $yith_wcwl;
            if (!$yith_wcwl) {
                return;
            }
            $variation = false;
            $productId = $product->get_id();
            $productType = $product->get_type();
            $parent_id = $productId;
            if($productType == 'variation') {
                $variation_product = $product;
                $parent_id = wp_get_post_parent_id($productId);
                $parentProduct = wc_get_product($parent_id);
                $productType = $parentProduct->get_type();
                
                $GLOBALS['product'] = $parentProduct;
                $variation = true;
            }

            ?>
            <a href="javascript:void(0);" class="btn-wishlist tip-top" data-prod="<?php echo (int) $productId; ?>" data-prod_type="<?php echo esc_attr($productType); ?>" data-original-product-id="<?php echo (int) $parent_id; ?>" data-tip="<?php esc_attr_e('Add to Wishlist', 'digi-theme'); ?>" title="<?php esc_html_e('Wishlist', 'digi-theme'); ?>">
                <div class="btn-link">
                    <div class="wishlist-icon">
                        <span class="nasa-icon icon-nasa-like"></span>
                        <span class="hidden-tag nasa-icon-text no-added"><?php esc_html_e('Add to Wishlist', 'digi-theme'); ?></span>
                    </div>
                </div>
            </a>

            <?php

            if($variation) {
                $GLOBALS['product'] = $variation_product;
            }
        }
    }
endif;

/*
 * digi add wishlist in Detail
 */
if(!function_exists('digi_add_wishlist_in_detail')) :
    function digi_add_wishlist_in_detail() {
        digi_add_wishlist_button();
    }
endif;

/*
 * digi add wishlist in list
 */
if(!function_exists('digi_add_wishlist_in_list')) :
    function digi_add_wishlist_in_list() {
        if (NASA_WISHLIST_IN_LIST) {
            digi_add_wishlist_button();
        }
    }
endif;

/*
 * digi quickview in list
 */
if(!function_exists('digi_quickview_in_list')) :
    function digi_quickview_in_list() {
        global $product, $nasa_opt, $head_type;
        
        if(!$head_type) {
            $head_type = isset($nasa_opt['header-type']) ? $nasa_opt['header-type'] : 1;
            global $wp_query;
            $custom_header = get_post_meta($wp_query->get_queried_object_id(), '_nasa_custom_header', true);
            if (!empty($custom_header)) {
                $head_type = (int) $custom_header;
            }

            $GLOBALS['head_type'] = $head_type;
        }
        ?>
        <div class="quick-view tip-top" data-prod="<?php echo (int) $product->get_id(); ?>" data-tip="<?php esc_html_e('Quick View', 'digi-theme'); ?>" data-head_type="<?php echo esc_attr($head_type); ?>" title="<?php esc_html_e('Quick View', 'digi-theme'); ?>" data-product_type="<?php echo esc_attr($product->get_type()); ?>">
            <div class="btn-link">
                <div class="quick-view-icon">
                    <span class="pe-icon pe-7s-look"></span>
                    <span class="hidden-tag nasa-icon-text"><?php esc_html_e('Quick View', 'digi-theme'); ?></span>
                </div>
            </div>
        </div>
        <?php
    }
endif;

/*
 * digi add to cart in list
 */
if(!function_exists('digi_add_to_cart_in_list')) :
    function digi_add_to_cart_in_list() {
        digi_add_to_cart_btn('small');
    }
endif;

/*
 * digi gift icon in list
 */
if(!function_exists('digi_bundle_in_list')) :
    function digi_bundle_in_list($combo_show_type) {
        global $product;
        if(!defined('YITH_WCPB') || $product->get_type() != NASA_COMBO_TYPE) {
            return;
        }
        ?>
        <div class="btn-combo-link tip-top" data-prod="<?php echo (int) $product->get_id(); ?>" data-tip="<?php esc_html_e('Promotion Gifts', 'digi-theme'); ?>" title="<?php esc_html_e('Promotion Gifts', 'digi-theme'); ?>" data-show_type="<?php echo esc_attr($combo_show_type); ?>">
            <div class="btn-link">
                <div class="gift-icon">
                    <span class="pe-icon pe-7s-gift"></span>
                    <span class="hidden-tag nasa-icon-text"><?php esc_html_e('Promotion Gifts', 'digi-theme'); ?></span>
                </div>
            </div>
        </div>
        <?php
    }
endif;

/*
 * Nasa Gift icon Featured
 */
if(!function_exists('digi_gift_featured')) :
    function digi_gift_featured() {
        global $product, $nasa_opt;
        
        if(isset($nasa_opt['enable_gift_featured']) && !$nasa_opt['enable_gift_featured']) {
            return;
        }
        
        $product_type = $product->get_type();
        if(!defined('YITH_WCPB') || $product_type != NASA_COMBO_TYPE) {
            return;
        }
        
        $class_effect = isset($nasa_opt['enable_gift_effect']) && $nasa_opt['enable_gift_effect'] == 1 ? '' : ' nasa-transition';
        
        echo 
        '<div class="nasa-gift-featured-wrap">' .
            '<div class="nasa-gift-featured">' .
                '<div class="gift-icon">' .
                    '<a href="javascript:void(0);" class="nasa-gift-featured-event' . $class_effect . '" title="' . esc_html__('View the promotion gifts', 'digi-theme') . '">' .
                        '<span class="pe-icon pe-7s-gift"></span>' .
                        '<span class="hidden-tag nasa-icon-text">' . 
                            esc_html__('Promotion Gifts', 'digi-theme') . 
                        '</span>' .
                    '</a>' .
                '</div>' .
            '</div>' .
        '</div>';
    }
endif;

/*
 * digi add compare in list
 */
if(!function_exists('digi_add_compare_in_list')) :
    function digi_add_compare_in_list() {
        global $product, $nasa_opt;
        $productId = $product->get_id();
        
        $nasa_compare = (!isset($nasa_opt['nasa-product-compare']) || $nasa_opt['nasa-product-compare']) ? true : false;
        ?>
        <div class="btn-compare tip-top<?php echo $nasa_compare ? ' nasa-compare' : ''; ?>" data-prod="<?php echo (int) $productId; ?>" data-tip="<?php esc_html_e('Compare', 'digi-theme'); ?>" title="<?php esc_html_e('Compare', 'digi-theme'); ?>">
            <div class="btn-link">
                <div class="compare-icon">
                    <span class="nasa-icon icon-nasa-compare-2"></span>
                    <span class="hidden-tag nasa-icon-text"><?php esc_html_e('Compare', 'digi-theme'); ?></span>
                </div>
            </div>
        </div>
        
        <?php if(!$nasa_compare) : ?>
            <div class="add-to-link hidden-tag">
                <div class="woocommerce-compare-button">
                    <?php echo do_shortcode('[yith_compare_button]'); ?>
                </div>
            </div>
        <?php endif;
    }
endif;

/*
 * digi add compare in detail
 */
if(!function_exists('digi_add_compare_in_detail')) :
    function digi_add_compare_in_detail() {
        global $product, $nasa_opt;
        $productId = $product->get_id();
        
        $nasa_compare = (!isset($nasa_opt['nasa-product-compare']) || $nasa_opt['nasa-product-compare']) ? true : false;
        ?>
        <div class="product-interactions">
            <div class="btn-compare<?php echo $nasa_compare ? ' nasa-compare' : ''; ?>" data-prod="<?php echo (int) $productId; ?>" data-tip="<?php esc_html_e('Compare', 'digi-theme'); ?>" title="<?php esc_html_e('Compare', 'digi-theme'); ?>">
                <div class="btn-link">
                    <div class="compare-icon">
                        <span class="nasa-icon icon-nasa-compare-2"></span>
                        <span class="nasa-icon-text"><?php esc_html_e('Add to Compare', 'digi-theme'); ?></span>
                    </div>
                </div>
            </div>
        
            <?php if(!$nasa_compare) : ?>
                <div class="add-to-link hidden-tag">
                    <div class="woocommerce-compare-button">
                        <?php echo do_shortcode('[yith_compare_button]'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php
    }
endif;

add_action('woocommerce_single_product_summary', 'digi_single_availability', 15);
if(!function_exists('digi_single_availability')) :
    function digi_single_availability() {
        global $product;
        // Availability
        $availability = $product->get_availability();

        if ($availability['availability']) :
            echo apply_filters('woocommerce_stock_html', '<p class="nasa-stock stock ' . esc_attr($availability['class']) . '">' . wp_kses(__('<span>Availability:</span> ', 'digi-theme'), array('span' => array())) . esc_html($availability['availability']) . '</p>', $availability['availability']);
        endif;
    }
endif;

// custom fields product
if(!function_exists('digi_add_custom_field_detail_product')) :
    function digi_add_custom_field_detail_product() {
        global $product, $product_lightbox;
        if($product_lightbox) {
            $product = $product_lightbox;
        }
        
        $product_type = $product->get_type();
        if($product_type == 'external' || (!defined('YITH_WCPB') && $product_type == NASA_COMBO_TYPE)) {
            return;
        }
        
        global $nasa_opt, $head_type;

        if(!$head_type) {
            $head_type = $nasa_opt['header-type'];
            global $wp_query;
            $custom_header = get_post_meta($wp_query->get_queried_object_id(), '_nasa_custom_header', true);
            if (!empty($custom_header)) {
                $head_type = (int) $custom_header;
            }

            $GLOBALS['head_type'] = $head_type;
        }

        $nasa_btn_ajax_value = ('yes' === get_option('woocommerce_enable_ajax_add_to_cart') && (!isset($nasa_opt['enable_ajax_addtocart']) || $nasa_opt['enable_ajax_addtocart'] == '1')) ? '1' : '0';
        echo '<div class="nasa-custom-fields hidden-tag">';
        echo '<input type="hidden" name="nasa-enable-addtocart-ajax" value="' . $nasa_btn_ajax_value . '" />';
        echo '<input type="hidden" name="data-product_id" value="' . esc_attr($product->get_id()) . '" />';
        echo '<input type="hidden" name="data-type" value="' . esc_attr($product_type) . '" />';
        echo '<input type="hidden" name="data-head_type" value="' . esc_attr($head_type) . '" />';
        $nasa_has_wishlist = (isset($_REQUEST['nasa_wishlist']) && $_REQUEST['nasa_wishlist'] == '1') ? '1' : '0';
        echo '<input type="hidden" name="data-from_wishlist" value="' . esc_attr($nasa_has_wishlist) . '" />';
        echo '</div>';
    }
endif;

if(!function_exists('digi_single_hr')) :
    function digi_single_hr() {
        echo '<hr class="nasa-single-hr" />';
    }
endif;

if(!function_exists('digi_price_list_loop')) :
    function digi_price_list_loop() {
        wc_get_template('loop/price.php');
    }
endif;

/*
 * Remove action woocommerce
 */
add_action('init', 'digi_remove_action_woo');
if(!function_exists('digi_remove_action_woo')) :
    function digi_remove_action_woo() {
        if(!NASA_WOO_ACTIVED) {
            return;
        }
        
        global $nasa_opt, $yith_woocompare;
        
        /* UNREGISTRER DEFAULT WOOCOMMERCE HOOKS */
        remove_action('woocommerce_single_product_summary', 'woocommerce_breadcrumb', 20);
        
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
        
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
        
        remove_action('woocommerce_before_shop_loop', 'woocommerce_show_messages', 10);
        remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
        
        remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals');
        
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
        
        /**
         * Shop page
         */
        remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
        remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);

        if (isset($nasa_opt['disable-cart']) && $nasa_opt['disable-cart']) {
            remove_action('woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30);
            remove_action('woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30);
            remove_action('woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30);
        }
        
        // Remove compare default
        if($yith_woocompare) {
            $nasa_compare = isset($yith_woocompare->obj) ? $yith_woocompare->obj : $yith_woocompare;
            remove_action('woocommerce_after_shop_loop_item', array($nasa_compare, 'add_compare_link'), 20);
            remove_action('woocommerce_single_product_summary', array($nasa_compare, 'add_compare_link'), 35);
        }
        
        /**
         * For content-product
         */
        remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open');
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail');
        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash');
        remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title');

        if(version_compare(wc()->version, '3.3.0', "<")) {
            remove_action('woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10);
            remove_action('woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10);
            remove_action('woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10);
        }
        
        // For woo 3.3
        if(version_compare(wc()->version, '3.3.0', ">=")) {
            remove_filter('woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories');
        }
        
        /**
         * Remove wishlist btn in detail product
         */
        if (NASA_WISHLIST_ENABLE) {
            add_filter('yith_wcwl_positions', 'digi_remove_btn_wishlist_single_product');
        }
        
        /**
         * For New version Yith Wishlist 3.0 or Higher
         */
        if (NASA_WISHLIST_NEW_VER) {
            add_filter('yith_wcwl_loop_positions', 'digi_remove_default_wishlist_button');
        }
    }
endif;

/*
 * Add action woocommerce
 */
add_action('init', 'digi_add_action_woo');
if(!function_exists('digi_add_action_woo')) :
    function digi_add_action_woo() {
        if(!NASA_WOO_ACTIVED) {
            return;
        }
        
        global $nasa_opt, $yith_woocompare;
        
        add_action('digi_shop_category_count', 'woocommerce_result_count', 20);
        
        add_action('woocommerce_single_product_lightbox_summary', 'woocommerce_template_loop_rating', 10);
        add_action('woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_price', 15);
        add_action('woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_excerpt', 20);
        add_action('woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_meta', 30);
        if (!isset($nasa_opt['disable-cart']) || !$nasa_opt['disable-cart']) {
            add_action('woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_add_to_cart', 40);
        }
        add_action('woocommerce_single_product_lightbox_summary', 'digi_combo_in_quickview', 50);
        add_action('woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_sharing', 60);
        
        // Deal time for Quickview product
        if(!isset($nasa_opt['single-product-deal']) || $nasa_opt['single-product-deal']) {
            add_action('woocommerce_single_product_lightbox_summary', 'digi_deal_time_quickview', 35);
        }
        
        add_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 1);
        add_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
        add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 20);
        add_action('woocommerce_single_product_summary', 'digi_single_hr', 21);
        add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 25);
        add_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 35);
        
        // Deal time for Single product
        if(!isset($nasa_opt['single-product-deal']) || $nasa_opt['single-product-deal']) {
            add_action('woocommerce_single_product_summary', 'digi_deal_time_single', 29);
        }
        
        // add nasa Wishlist in detail product
        add_action('woocommerce_single_product_summary', 'digi_add_wishlist_in_detail', 31);
        
        // add nasa compare in detail product
        if ($yith_woocompare) {
            if (get_option('yith_woocompare_compare_button_in_product_page') == 'yes') {
                add_action('woocommerce_single_product_summary', 'digi_add_compare_in_detail', 32);
            }
            
            if (get_option('yith_woocompare_compare_button_in_products_list') == 'yes') {
                add_action('nasa_show_buttons_loop', 'digi_add_compare_in_list', 25);
            }
        }
        
        add_action('nasa_show_buttons_loop', 'digi_add_to_cart_in_list', 10);
        add_action('nasa_show_buttons_loop', 'digi_add_wishlist_in_list', 15);
        
        if (!isset($nasa_opt['disable-quickview']) || !$nasa_opt['disable-quickview']) {
            add_action('nasa_show_buttons_loop', 'digi_quickview_in_list', 20);
        }
        add_action('nasa_show_buttons_loop', 'digi_bundle_in_list', 30, 1);
        
        // Add nasa bundles form
        add_action('woocommerce_after_add_to_cart_button', 'digi_add_custom_field_detail_product', 25);
        
        add_action('nasa_price_list_loop', 'digi_price_list_loop');
        
        add_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals', 9);
        
        // nasa_top_sidebar_shop
        add_action('nasa_top_sidebar_shop', 'digi_top_sidebar_shop');
        add_action('nasa_sidebar_shop', 'digi_side_sidebar_shop', 10 , 1);
        
        add_filter('woocommerce_checkout_coupon_message', 'digi_wrap_coupon_toggle');
        
        // for woo 3.3
        if(version_compare(wc()->version, '3.3.0', ">=")) {
            add_filter('woocommerce_product_loop_start', 'digi_maybe_show_product_subcategories');
            
            if(!isset($nasa_opt['show_uncategorized']) || !$nasa_opt['show_uncategorized']) {
                add_filter('woocommerce_product_subcategories_args', 'digi_hide_uncategorized');
            }
        }
        
        /**
         * Gift featured
         */
        add_action('nasa_gift_featured', 'digi_gift_featured', 10);
        
        /**
         * sale flash
         */
        add_filter('woocommerce_sale_flash', 'digi_filter_sale_flash');
        add_action('nasa_gift_featured', 'digi_add_custom_sale_flash', 11);
        add_action('woocommerce_before_single_product_summary', 'digi_add_custom_sale_flash', 11);
        
        /**
         * Disable redirect Search one product to single product
         */
        add_filter('woocommerce_redirect_single_search_result', '__return_false');
    }
endif;

if(!function_exists('digi_wrap_coupon_toggle')) :
    function digi_wrap_coupon_toggle($content) {
        return '<div class="nasa-toggle-coupon-checkout text-right">' . $content . '</div>';
    }
endif;

if (!function_exists('digi_combo_tab')) :
    function digi_combo_tab($nasa_viewmore = true) {
        global $woocommerce, $nasa_opt, $head_type, $product;

        if (!$woocommerce || !$product || $product->get_type() != NASA_COMBO_TYPE || !$combo = $product->get_bundled_items()) {
            return false;
        }

        $file = DIGI_CHILD_PATH . '/includes/nasa-combo-products-in-detail.php';
        $file = is_file($file) ? $file : DIGI_THEME_PATH . '/includes/nasa-combo-products-in-detail.php';
        ob_start();
        include $file;

        return ob_get_clean();
    }
endif;

/**
 * nasa product budles in quickview
 */
if(!function_exists('digi_combo_in_quickview')) :
    function digi_combo_in_quickview() {
        global $woocommerce, $nasa_opt, $product;

        if (!$woocommerce || !$product || $product->get_type() != NASA_COMBO_TYPE || !$combo = $product->get_bundled_items()) {
            echo '';
        }
        else {
            $file = DIGI_CHILD_PATH . '/includes/nasa-combo-products-quickview.php';
            $file = is_file($file) ? $file : DIGI_THEME_PATH . '/includes/nasa-combo-products-quickview.php';

            include $file;
        }
    }
endif;

/**
 * nasa_top_sidebar_shop
 */
if(!function_exists('digi_top_sidebar_shop')) :
    function digi_top_sidebar_shop() {
        $sidebar_run = 'shop-sidebar';
        if(is_tax('product_cat')) {
            global $wp_query;
            $query_obj = $wp_query->get_queried_object();
            $sidebar_cats = get_option('nasa_sidebars_cats');
            if(isset($sidebar_cats[$query_obj->slug])) {
                $sidebar_run = $query_obj->slug;
            }
        }
        ?>
        <div class="large-12 columns left col-sidebar nasa-top-sidebar hidden-tag">
            <div class="row">
                <?php
                if (is_active_sidebar($sidebar_run)) :
                    dynamic_sidebar($sidebar_run);
                endif;
                ?>
            </div>
        </div>
    <?php
    }
endif;

/**
 * nasa_sidebar_shop
 */
if(!function_exists('digi_side_sidebar_shop')) :
    function digi_side_sidebar_shop($nasa_sidebar = 'left') {
        $sidebar_run = 'shop-sidebar';
        if(is_tax('product_cat')) {
            global $wp_query;
            $query_obj = $wp_query->get_queried_object();
            $sidebar_cats = get_option('nasa_sidebars_cats');
            if(isset($sidebar_cats[$query_obj->slug])) {
                $sidebar_run = $query_obj->slug;
            }
        }
        ?>
        <div class="large-3 <?php echo $nasa_sidebar != 'right' ? 'left' : 'right'; ?> columns col-sidebar">
            <?php
            if (is_active_sidebar($sidebar_run)) :
                dynamic_sidebar($sidebar_run);
            endif;
            ?>
        </div>
    <?php
    }
endif;


/**
 * Sale flash
 */
if(!function_exists('digi_filter_sale_flash')):
    function digi_filter_sale_flash() {
        return '';
    }
endif;

/**
 * Sale flash custom
 */
if(!function_exists('digi_add_custom_sale_flash')) :
    function digi_add_custom_sale_flash() {
        global $product;
        $product_type = $product->get_type();
        
        if ($nasa_bubble_hot = digi_get_custom_field_value($product->get_id(), '_bubble_hot')):
            ?>
            <div class="badge">
                <div class="badge-inner hot-label">
                    <div class="inner-text">
                        <?php echo $nasa_bubble_hot; ?>
                    </div>
                </div>
            </div>
            <?php
        endif;

        if ($product->is_on_sale()):
            if ($product_type == 'variable') :
                ?>
                <div class="badge">
                    <div class="badge-inner sale-label">
                        <div class="inner-text">
                            <span class="sale-label-text sale-variable"><?php echo esc_html__('SALE', 'digi-theme'); ?></span>
                        </div>
                    </div>
                </div>
            <?php else :
                $price = '';
                $maximumper = 0;
                $regular_price = $product->get_regular_price();
                $sales_price = $product->get_sale_price();
                $percentage = $regular_price ? round(((($regular_price - $sales_price) / $regular_price) * 100), 0) : 0;
                if ($percentage > $maximumper) :
                    $maximumper = $percentage;
                endif;
                ?>
                <div class="badge">
                    <div class="badge-inner sale-label">
                        <div class="inner-text">
                            <span class="sale-label-text"><?php echo esc_html__('SALE', 'digi-theme'); ?></span>
                            <?php echo '-' . $price . sprintf(esc_html__('%s', 'digi-theme'), $maximumper . '%'); ?>
                        </div>
                    </div>
                </div>
                <?php
            endif;
        endif;
    }
endif;

/**
 * nasa_archive_get_sub_categories
 */
add_action('nasa_archive_get_sub_categories', 'nasa_archive_get_sub_categories');
if(!function_exists('nasa_archive_get_sub_categories')) :
    function nasa_archive_get_sub_categories() {
        $GLOBALS['nasa_cat_loop_delay'] = 0;
        echo '<div class="nasa-archive-sub-categories-wrap">';
        woocommerce_product_subcategories(array(
            'before' => '<div class="row"><div class="large-12 columns"><h3>' . esc_html__('Subcategories: ', 'digi-theme') . '</h3></div></div><div class="row">',
            'after' => '</div><div class="row"><div class="large-12 columns margin-bottom-20 margin-top-20 text-center"><hr class="margin-left-20 margin-right-20" /></div></div>'
        ));
        echo '</div>';
    }
endif;

/**
 * Sub categories Shop page
 */
if(!function_exists('digi_maybe_show_product_subcategories') && NASA_WOO_ACTIVED && version_compare(wc()->version, '3.3.0', ">=")) :
    function digi_maybe_show_product_subcategories($loop_html) {
        $display_type = woocommerce_get_loop_display_mode();
        
        // If displaying categories, append to the loop.
        if ('subcategories' === $display_type || 'both' === $display_type) {
            $before = '<div class="row"><div class="large-12 columns"><h3>' . esc_html__('Subcategories: ', 'digi-theme') . '</h3></div></div><div class="row">';
            $after = '</div><div class="row"><div class="large-12 columns margin-bottom-20 margin-top-20 text-center"><hr class="margin-left-20 margin-right-20" /></div></div>';
            ob_start();
            woocommerce_output_product_categories(array(
                'parent_id' => is_product_category() ? get_queried_object_id() : 0,
            ));
            $loop_html .= $before . ob_get_clean() . $after;

            if ('subcategories' === $display_type) {
                wc_set_loop_prop('total', 0);

                // This removes pagination and products from display for themes not using wc_get_loop_prop in their product loops. @todo Remove in future major version.
                global $wp_query;

                if ($wp_query->is_main_query()) {
                    $wp_query->post_count    = 0;
                    $wp_query->max_num_pages = 0;
                }
            }
        }

        return $loop_html;
    }
endif;

if(!function_exists('digi_hide_uncategorized')) :
    function digi_hide_uncategorized($args) {
        $args['exclude'] = get_option('default_product_cat');
        return $args;
    }
endif;

/**
 * Deal time in Single product page
 */
if(!function_exists('digi_deal_time_single')) :
    function digi_deal_time_single() {
        global $product;
        
        if($product->get_stock_status() == 'outofstock') {
            return;
        }
        
        $product_type = $product->get_type();
        
        // For variation of Variation product
        if($product_type == 'variable') {
            echo '<div class="nasa-detail-product-deal-countdown nasa-product-variation-countdown"></div>';
            return;
        }
        
        $not = array('grouped', 'external');
        
        if(in_array($product_type, $not)) {
            return;
        }
        
        $productId = $product->get_id();

        $timeNow = time();
        $time_from = get_post_meta($productId, '_sale_price_dates_from', true);
        $time_to = get_post_meta($productId, '_sale_price_dates_to', true);
        $time_sale = ((int) $time_to < $timeNow || (int) $time_from > $timeNow) ? false : (int) $time_to;
        if (!$time_sale) {
            return;
        }
        
        echo '<div class="nasa-detail-product-deal-countdown">';
        echo digi_time_sale($time_sale);
        echo '</div>';
    }
endif;

/**
 * Deal time in Quick view product
 */
if(!function_exists('digi_deal_time_quickview')) :
    function digi_deal_time_quickview() {
        global $product;
        
        if($product->get_stock_status() == 'outofstock') {
            return;
        }
        
        $product_type = $product->get_type();
        
        // For variation of Variation product
        if($product_type == 'variable') {
            echo '<div class="nasa-quickview-product-deal-countdown nasa-product-variation-countdown"></div>';
            return;
        }
        
        $not = array('grouped', 'external');
        
        if(in_array($product_type, $not)) {
            return;
        }
        
        $productId = $product->get_id();

        $timeNow = time();
        $time_from = get_post_meta($productId, '_sale_price_dates_from', true);
        $time_to = get_post_meta($productId, '_sale_price_dates_to', true);
        $time_sale = ((int) $time_to < $timeNow || (int) $time_from > $timeNow) ? false : (int) $time_to;
        if (!$time_sale) {
            return;
        }
        
        echo '<div class="nasa-quickview-product-deal-countdown">';
        echo digi_time_sale($time_sale);
        echo '</div>';
    }
endif;
