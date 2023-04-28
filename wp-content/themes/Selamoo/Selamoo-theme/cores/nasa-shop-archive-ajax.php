<?php
/* ======================================================================= */
/* Load products page - AJAX - filter ==================================== */
/* ======================================================================= */
add_action('wp_ajax_nasa_products_page' , 'digi_products_page');
add_action('wp_ajax_nopriv_nasa_products_page', 'digi_products_page');
if(!function_exists('digi_products_page')) :
    function digi_products_page() {
        global $nasa_opt, $wp_query, $woocommerce;

        if(!$woocommerce){
            die(esc_html__('Please, install plugin WooCommerce !', 'digi-theme'));
        }

        $json = array();
        $paged = (int) $_REQUEST['paged'];
        $type_taxonomy = (isset($_REQUEST['taxonomy']) && trim($_REQUEST['taxonomy']) != '') ? $_REQUEST['taxonomy'] : 'product_cat';
        $term_id = (int)$_REQUEST['catId'];

        $_GET['orderby'] = isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : null;
        $args = $woocommerce->query->get_catalog_ordering_args();
        if(!$args){
            $args['orderby']  = 'menu_order title';
            $args['order']    = 'ASC';
        }
        $args['post_type']      = 'product';
        $args['posts_per_page'] = (isset($nasa_opt['products_pr_page']) && (int) $nasa_opt['products_pr_page']) ? (int) $nasa_opt['products_pr_page'] : get_option('posts_per_page');
        $args['post_status']    = 'publish';
        $args['paged']          = !$paged ? '1' : $paged;
        $args['tax_query']      = array('relation' => 'AND');
        $args['meta_query']     = array();
        $args['meta_query'][]   = $woocommerce->query->visibility_meta_query();
        $hasResults = true; // Flag has Post ?

        /*
         * Filter by Category
         */
        if($term_id > 0){
            $args['tax_query'][] = array(
                'taxonomy'  => $type_taxonomy,
                'field'     => 'id', 
                'terms'     => array($term_id)
            );
        }

        $product_visibility_terms = wc_get_product_visibility_term_ids();
        $arr_not_in = array($product_visibility_terms['exclude-from-catalog']);

        /*
         * Search product
         */
        if ($_REQUEST['hasSearch'] == 1 && isset($_REQUEST['s'])) {
            $args['post__in'] = array();
            $posts = get_posts(
                array(
                    'post_type' => 'product',
                    'numberposts' => -1,
                    'post_status' => 'publish',
                    'fields' => 'ids',
                    'no_found_rows' => true,
                    's' => $_REQUEST['s']
                )
            );

            if (!is_wp_error($posts) && count($posts)) {
                foreach ($posts as $v) {
                    if (!in_array($v, $args['post__in'])) {
                        $args['post__in'][] = $v;
                    }
                }
            }

            $arr_not_in = array($product_visibility_terms['exclude-from-search']);
            $hasResults = empty($args['post__in']) ? false : true;
        }

        if ($hasResults) {
            /*
             * Filter by variations
             */
            if(!empty($_REQUEST['variations'])){

                foreach ($_REQUEST['variations'] as $v){
                    $filter_name = 'filter_' . sanitize_title($v['taxonomy']);
                    $args['tax_query'][] = array(
                        'taxonomy' => 'pa_' . $v['taxonomy'],
                        'field'    => 'slug',
                        'terms'    => $v['slug'],
                        'operator' => strtolower($v['type']) === 'or' ? 'IN' : 'AND',
                        'include_children' => false,
                    );
                    $_REQUEST[$filter_name] = $v['slug'];
                    $_GET['query_type_' . $v['taxonomy']] = strtolower($v['type']) === 'or' ? 'or' : 'and';
                }
            }

            // Hide out of stock products.
            if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
                $arr_not_in[] = $product_visibility_terms['outofstock'];
            }

            if (!empty($arr_not_in)) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => $arr_not_in,
                    'operator' => 'NOT IN'
                );
            }

            /*
             * Filter by price
             */
            if(isset($_REQUEST['hasPrice']) && $_REQUEST['hasPrice'] && (isset($_REQUEST['min_price']) || isset($_REQUEST['max_price']))){
                $min = isset($_REQUEST['min_price']) ? floatval($_REQUEST['min_price']) : 0;
                $max = isset($_REQUEST['max_price']) ? floatval($_REQUEST['max_price']) : 9999999999;
                $meta_price = wc_get_min_max_price_meta_query(array(
                    'min_price' => $min,
                    'max_price' => $max
                ));
                $meta_price['price_filter'] = true;
                $args['meta_query'][] = $meta_price;
            }

            $GLOBALS['wp_query'] = new WP_Query($args);
        }

        $nasa_sidebar = isset($_REQUEST['nasa_sidebar']) ? $_REQUEST['nasa_sidebar'] : 'top';

        /*
         * List products
         */
        ob_start();
        woocommerce_product_loop_start();
        do_action('nasa_get_content_products', $nasa_sidebar);
        woocommerce_product_loop_end();
        wp_reset_postdata();
        $json['content'] = ob_get_clean();

        /*
         * Init shortcode
         */
        digi_init_map_shortcode();

        /*
         * Categories top sidebar
         */
        ob_start();
        $parentId = $type_taxonomy == 'product_cat' ? $term_id : 0;
        $intance = isset($_REQUEST['nasa_instance_widget']) ? $_REQUEST['nasa_instance_widget'] : 0;
        do_action('nasa_child_cat', $parentId, $intance);
        $json['cat_top_sidebar'] = ob_get_clean();

        if($_REQUEST['top'] == '1'){
            $json['cat_header'] = digi_get_cat_header((int) $_REQUEST['catId']);
            $json['recommend_products'] = '';
            if(defined('NASA_CORE_ACTIVED') && $type_taxonomy == 'product_cat') {
                ob_start();
                do_action('nasa_recommend_product', (int) $_REQUEST['catId']);
                $json['recommend_products'] = ob_get_clean();
            }
        }

        /*
         * Get description term
         */
        $json['description'] = digi_term_description($term_id, $type_taxonomy);

        /*
         * Refresh Select order
         */
        if ($wp_query->post_count <= 1 && $wp_query->max_num_pages <= 1) {
            $json['select_order'] = '';
        } else {
            ob_start();
            woocommerce_catalog_ordering();
            $json['select_order'] = ob_get_clean();
        }

        /*
         * Showing info
         */
        if (isset($_REQUEST['showing_info_top']) && $_REQUEST['showing_info_top'] == '1') {
            ob_start();
            do_action('digi_shop_category_count');
            $json['showing_info_top'] = ob_get_clean();
        }

        /*
         * Refresh Pagination
         */
        ob_start();
        do_action('woocommerce_after_shop_loop');
        
        $args_pagination = array(
            'total' => $wp_query->max_num_pages,
            'current' => $args['paged']
        );
        wc_get_template('loop/pagination.php', $args_pagination);
        $json['pagination'] = ob_get_clean();

        /*
         * Refresh Breadcrumb
         */
        ob_start();
        digi_get_breadcrumb(true);
        $json['breadcrumb'] = ob_get_clean();

        /*
         * Refress price slide
         */
        $argsPrice = !isset($_REQUEST['argsPrice']) || $_REQUEST['argsPrice'] === 'nochange' ? 'nochange' : $_REQUEST['argsPrice'];
        $intancePrice = !isset($_REQUEST['intancePrice']) || $_REQUEST['intancePrice'] === 'nochange' ? 'nochange' : $_REQUEST['intancePrice'];
        if($argsPrice == 'nochange' || $intancePrice == 'nochange') {
            $json['price'] = 'nochange';
        } elseif(isset($argsPrice['widget_id'])) {
            $json['price'] = 'change';
            $json['price_id'] = $argsPrice['widget_id'] . '-ajax-wrap';
            $json['price_content'] = digi_get_content_widget_price($argsPrice, $intancePrice, false);
        }

        /*
         * Refress variation widgets
         */
        $variations = array();
        if(!empty($_REQUEST['attr_variation'])) {
            foreach($_REQUEST['attr_variation'] as $var) {
                if(isset($var['args']) && isset($var['instance'])) {
                    $variations[$var['args']['widget_id'] . '-ajax-wrap'] = digi_get_content_widget_variation($var['args'], $var['instance'], $args);
                }
            }
        }

        $json['variations'] = $variations;

        die(json_encode($json));
    }
endif;

/* Pagination product pages */
if(!function_exists('digi_get_pagination_ajax')) :
    function digi_get_pagination_ajax(
        $total = 1,
        $current = 1,
        $type = 'list',
        $prev_text = 'PREV', 
        $next_text = 'NEXT',
        $end_size = 3, 
        $mid_size = 3,
        $prev_next = true,
        $show_all = false
    ) {

        if ($total < 2) {
            return;
        }

        if ($end_size < 1) {
            $end_size = 1;
        }

        if ($mid_size < 0) {
            $mid_size = 2;
        }

        $r = '';
        $page_links = array();

        // PREV Button
        if ($prev_next && $current && 1 < $current){
            $page_links[] = '<a class="nasa-prev prev page-numbers" data-page="' . ((int)$current - 1) . '" href="javascript:void(0);">' . $prev_text . '</a>';
        }

        // PAGE Button
        $moreStart = false;
        $moreEnd = false;
        for ($n = 1; $n <= $total; $n++){
            $page = number_format_i18n($n);
            
            if ($n == $current){
                $page_links[] = '<a class="nasa-current current page-numbers" data-page="' . $page . '" href="javascript:void(0);">' . $page . '</a>';
            }
            
            else {
                if ($show_all || ($current && $n >= $current - $mid_size && $n <= $current + $mid_size)) {
                    $page_links[] = '<a class="nasa-page page-numbers" data-page="' . $page . '" href="javascript:void(0);">' . $page . "</a>";
                }
                
                elseif ($n == 1 || $n == $total) {
                    $page_links[] = '<a class="nasa-page page-numbers" data-page="' . $page . '" href="javascript:void(0);">' . $page . "</a>";
                }
                
                elseif (!$moreStart && $n <= $end_size + 1) {
                    $moreStart = true;
                    $page_links[] = '<span class="nasa-page-more">' . esc_html__('...', 'digi-theme') . '</span>';
                }
                
                elseif (!$moreEnd && $n > $total - $end_size - 1) {
                    $moreEnd = true;
                    $page_links[] = '<span class="nasa-page-more">' . esc_html__('...', 'digi-theme') . '</span>';
                }
            }
            
        }

        // NEXT Button
        if ($prev_next && $current && ($current < $total || -1 == $total)){
            $page_links[] = '<a class="nasa-next next page-numbers" data-page="' . ((int)$current + 1)  . '" href="javascript:void(0);">' . $next_text . '</a>';
        }

        // DATA Return
        switch ($type) {
            case 'array' :
                return $page_links;

            case 'list' :
                $r .= '<ul class="page-numbers nasa-pagination-ajax"><li>';
                $r .= implode('</li><li>', $page_links);
                $r .= '</li></ul>';
                break;

            default :
                $r = implode('', $page_links);
                break;
        }

        return $r;
    }
endif;

/* ============================================================================== */
/* ============================= Deprecated ===================================== */
/* ============================================================================== */
/*
 * Count by Variations
 * Deprecated
 */
function digi_count_by_variations($taxonomy, $args = array(), &$result = array(), $query_type = 'and'){
    $get_terms_args = array('taxonomy' => $taxonomy, 'hide_empty' => '1');
    $orderby = wc_attribute_orderby($taxonomy);

    switch ($orderby) {
        case 'name' :
            $get_terms_args['orderby']    = 'name';
            $get_terms_args['menu_order'] = false;
            break;
        case 'id' :
            $get_terms_args['orderby']    = 'id';
            $get_terms_args['order']      = 'ASC';
            $get_terms_args['menu_order'] = false;
            break;
        case 'menu_order' :
            $get_terms_args['menu_order'] = 'ASC';
            break;
    }
    unset($args['paged']);
    $args['posts_per_page'] = -1;
    $args['fields'] = 'ids';
    
    $attr = str_replace('pa_', '', $taxonomy);
    $result[$attr] = array();
    
    if (0 < count($terms = get_terms(apply_filters('woocommerce_product_attribute_terms', $get_terms_args)))) {
        global $woocommerce;
        $compare_ver = version_compare($woocommerce->version, '2.6.1', ">=");
        $term_counts = $compare_ver ? get_filtered_term_product_counts(wp_list_pluck($terms, 'term_id'), $taxonomy, $query_type, $args, true) : null;
        
        foreach ($terms as $k => $term) {
            if(!$compare_ver){
                $args['tax_query'][1] = array(
                    array(
                        'taxonomy' 	=> $taxonomy,
                        'terms' 	=> $term->term_id,
                        'field' 	=> 'term_id'
                    )
                );
                $result[$attr]['nasa_' . $attr . '_'.$term->term_id] = count(get_posts($args));
                unset($args['tax_query'][1]);
            }else{
                $result[$attr]['nasa_' . $attr . '_' . $term->term_id] = isset($term_counts[$term->term_id]) ? $term_counts[$term->term_id] : 0;
            }
        }
    }
}

/*
 * get count product
 * Deprecated
 */
function get_filtered_term_product_counts($term_ids, $taxonomy, $query_type, $args = array(), $ajax = false) {
    global $wpdb;
    
    if(!$ajax){
        $meta_query = WC_Query::get_main_meta_query();
        $tax_query  = WC_Query::get_main_tax_query();
    } else {
        $meta_query = isset($args['meta_query']) ? $args['meta_query'] : array();
        $tax_query  = isset($args['tax_query']) ? $args['tax_query'] : array();
    }
    
    if ('or' === $query_type) {
        foreach ($tax_query as $key => $query) {
            if (isset($query['taxonomy']) && $taxonomy === $query['taxonomy']) {
                unset($tax_query[$key]);
            }
        }
    }
    
    $meta_query      = new WP_Meta_Query($meta_query);
    $tax_query       = new WP_Tax_Query($tax_query);
    $meta_query_sql  = $meta_query->get_sql('post', $wpdb->posts, 'ID');
    $tax_query_sql   = $tax_query->get_sql($wpdb->posts, 'ID');
    
    // Generate query
    $query           = array();
    $query['select'] = 'SELECT COUNT(DISTINCT ' . $wpdb->posts . '.ID) as term_count, terms.term_id as term_count_id';
    
    $query['from']   = 'FROM ' . $wpdb->posts;
    
    $query['join']   = 
        'INNER JOIN ' . $wpdb->term_relationships . ' AS term_relationships ON ' . $wpdb->posts . '.ID = term_relationships.object_id ' .
        'INNER JOIN ' . $wpdb->term_taxonomy . ' AS term_taxonomy USING(term_taxonomy_id) ' .
        'INNER JOIN ' . $wpdb->terms . ' AS terms USING(term_id) ' .
        $tax_query_sql['join'] . $meta_query_sql['join'];
    
    $query['where']   = 
        'WHERE ' . $wpdb->posts . '.post_type LIKE "product" ' .
        'AND ' . $wpdb->posts . '.post_status LIKE "publish" ' . 
        $tax_query_sql['where'] . $meta_query_sql['where'] . ' ' .
        'AND terms.term_id IN (' . implode(',', array_map('absint', $term_ids)) . ')';

    // For search case
    if(isset($_GET['s']) && $_GET['s']){
        $s = esc_sql(str_replace(array("\r", "\n"), '', stripslashes($_GET['s'])));
        
        $query['where'] .= ' AND (' . $wpdb->posts . '.post_title LIKE "%' . $s . '%" OR ' . $wpdb->posts . '.post_excerpt LIKE "%' . $s . '%" OR ' . $wpdb->posts . '.post_content LIKE "%' . $s . '%")';
    }

    $query['group_by'] = "GROUP BY terms.term_id";
    $queryString = implode(' ', apply_filters('woocommerce_get_filtered_term_product_counts_query', $query));
    $results = $wpdb->get_results($queryString);
    
    return wp_list_pluck($results, 'term_count', 'term_count_id');
}