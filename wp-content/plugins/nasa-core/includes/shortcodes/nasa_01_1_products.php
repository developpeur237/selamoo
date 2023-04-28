<?php
add_shortcode('nasa_products', 'nasa_sc_products');
function nasa_sc_products($atts, $content = null) {
    global $woocommerce, $nasa_opt;
    
    if (!$woocommerce) {
        return $content;
    }
    
    $dfAttr = array(
        'number' => '8',
        'icon' => '',
        'cat' => '',
        'type' => 'recent_product',
        'style' => 'grid',
        'style_row' => 'simple',
        'title_shortcode' => '',
        'pos_nav' => 'left',
        'title_align' => 'left',
        'shop_url' => 0,
        'arrows' => 1,
        'dots' => 'false',
        'auto_slide' => 'false',
        'rating_status' => '0',
        'flag_showmore' => '0',
        'columns_number' => '4',
        'columns_number_small' => '1',
        'columns_number_tablet' => '2',
        'is_ajax' => 'yes',
        'min_height' => 'auto',
        'el_class' => ''
    );
    extract(shortcode_atts($dfAttr, $atts));
    
    // Optimized speed
    if (isset($nasa_opt['enable_optimized_speed']) && $nasa_opt['enable_optimized_speed'] == 1) {
        $atts['is_ajax'] = !isset($atts['is_ajax']) ? $is_ajax : $atts['is_ajax'];
        if (isset($atts['is_ajax']) && $atts['is_ajax'] == 'yes' &&
            (!isset($_REQUEST['nasa_load_ajax']) || $_REQUEST['nasa_load_ajax'] != '1')) {
            
            return nasa_shortcode_text('nasa_products', $atts);
        }

        // Load ajax
        elseif($atts['is_ajax'] == 'yes' && $_REQUEST['nasa_load_ajax'] == '1') {
            extract(shortcode_atts($dfAttr, nasa_shortcode_vars($atts)));
        }
    }
    
    if ($type == '') {
        return $content;
    }
    $file = NASA_CORE_PRODUCT_LAYOUTS . 'nasa_products/' . $style . '.php';
    if (is_file($file)) :
        $is_deals = $type == 'deals' ? 'true' : 'false';
        $loop = nasa_woocommerce_query($type, $number, $cat);
        if ($_total = $loop->post_count) :
            ob_start();
            ?>
            <div class="products woocommerce<?php echo ($el_class != '') ? ' ' . esc_attr($el_class) : ''; ?>">
                <div class="inner-content">
                    <?php include $file; ?>
                </div>
            </div>
            <?php
            $content = ob_get_clean();
            wp_reset_postdata();
        endif;
    endif;
    
    return $content;
}

// **********************************************************************// 
// ! Register New Element: nasa products
// **********************************************************************//
add_action('init', 'nasa_register_product');
function nasa_register_product(){
    vc_map(array(
        "name" => esc_html__("Products", 'nasa-core'),
        "base" => "nasa_products",
        'icon' => 'icon-wpb-nasatheme',
        'description' => esc_html__("Display products as many format.", 'nasa-core'),
        "class" => "",
        "category" => 'Nasa Core',
        "params" => array(
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Type", 'nasa-core'),
                "param_name" => "type",
                "value" => array(
                    esc_html__('Recent Products', 'nasa-core') => 'recent_product',
                    esc_html__('Best Selling', 'nasa-core') => 'best_selling',
                    esc_html__('Featured Products', 'nasa-core') => 'featured_product',
                    esc_html__('Top Rate', 'nasa-core') => 'top_rate',
                    esc_html__('On Sale', 'nasa-core') => 'on_sale',
                    esc_html__('Recent Review', 'nasa-core') => 'recent_review',
                    esc_html__('Product Deals') => 'deals'
                ),
                'std' => 'recent_product',
                "admin_label" => true,
                "description" => esc_html__("Select type product to show.", 'nasa-core')
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Style", 'nasa-core'),
                "param_name" => "style",
                "value" => array(
                    esc_html__('Grid', 'nasa-core') => 'grid',
                    esc_html__('Carousel', 'nasa-core') => 'carousel',
                    esc_html__('Ajax Infinite', 'nasa-core') => 'infinite',
                    esc_html__('List', 'nasa-core') => 'list_1'
                ),
                'std' => 'grid',
                "admin_label" => true
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Number row of Slide', 'nasa-core'),
                "param_name" => 'style_row',
                "value" => array(
                    esc_html__('Simple row', 'nasa-core') => 'simple',
                    esc_html__('Double rows', 'nasa-core') => 'double'
                ),
                "std" => 'simple',
                "dependency" => array(
                    "element" => "style",
                    "value" => array(
                        "carousel"
                    )
                ),
                "description" => esc_html__("Only using for Style is Carousel.", 'nasa-core')
            ),
            
            array(
                "type" => "textfield",
                "heading" => esc_html__("Title", 'nasa-core'),
                "param_name" => "title_shortcode",
                "value" => '',
                "dependency" => array(
                    "element" => "style",
                    "value" => array(
                        "carousel"
                    )
                ),
                "description" => esc_html__("Only using for Style is Carousel.", 'nasa-core')
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Position Title | Navigation", 'nasa-core'),
                "param_name" => "pos_nav",
                "value" => array(
                    esc_html__('Side', 'nasa-core') => 'left',
                    esc_html__('Top', 'nasa-core') => 'top'
                ),
                "std" => 'left',
                "dependency" => array(
                    "element" => "style_row",
                    "value" => array(
                        "simple"
                    )
                ),
                "description" => esc_html__("Only using for Show type is Carousel.", 'nasa-core')
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Title align", 'nasa-core'),
                "param_name" => "title_align",
                "value" => array(
                    esc_html__('Left', 'nasa-core') => 'left',
                    esc_html__('Right', 'nasa-core') => 'right'
                ),
                "std" => 'left',
                "dependency" => array(
                    "element" => "pos_nav",
                    "value" => array(
                        "top"
                    )
                )
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Shop url', 'nasa-core'),
                "param_name" => 'shop_url',
                "value" => array(
                    esc_html__('Yes, please', 'nasa-core') => 1,
                    esc_html__('No, thank', 'nasa-core') => 0
                ),
                "std" => 0,
                "dependency" => array(
                    "element" => "style",
                    "value" => array(
                        "carousel"
                    )
                ),
                "description" => esc_html__("Only using for Style is Carousel.", 'nasa-core')
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Show arrows', 'nasa-core'),
                "param_name" => 'arrows',
                "value" => array(
                    esc_html__('Yes, please', 'nasa-core') => 1,
                    esc_html__('No, thank', 'nasa-core') => 0
                ),
                "std" => 1,
                "dependency" => array(
                    "element" => "style",
                    "value" => array(
                        "carousel"
                    )
                ),
                "description" => esc_html__("Only using for Show type is Carousel.", 'nasa-core')
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Show dots', 'nasa-core'),
                "param_name" => 'dots',
                "value" => array(
                    esc_html__('Yes, please', 'nasa-core') => 'true',
                    esc_html__('No, thank', 'nasa-core') => 'false'
                ),
                "std" => 'false',
                "dependency" => array(
                    "element" => "style",
                    "value" => array(
                        "carousel"
                    )
                ),
                "description" => esc_html__("Only using for Show type is Carousel.", 'nasa-core')
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Slide auto', 'nasa-core'),
                "param_name" => 'auto_slide',
                "value" => array(
                    esc_html__('Yes, please', 'nasa-core') => 'true',
                    esc_html__('No, thank', 'nasa-core') => 'false'
                ),
                "std" => 'false',
                "dependency" => array(
                    "element" => "style",
                    "value" => array(
                        "carousel"
                    )
                ),
                "description" => esc_html__("Only using for Style is Carousel.", 'nasa-core')
            ),
            
            array(
                "type" => "textfield",
                "heading" => esc_html__("Number of products to show", 'nasa-core'),
                "param_name" => "number",
                "value" => '8',
                "std" => '8',
                "admin_label" => true,
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Columns number", 'nasa-core'),
                "param_name" => "columns_number",
                "value" => array(5, 4, 3, 2, 1),
                "std" => 4,
                "admin_label" => true,
                "description" => esc_html__("Select columns count.", 'nasa-core')
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Columns number small", 'nasa-core'),
                "param_name" => "columns_number_small",
                "value" => array(3, 2, 1),
                "std" => 1,
                "admin_label" => true,
                "description" => esc_html__("Select columns count small display.", 'nasa-core')
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Columns number tablet", 'nasa-core'),
                "param_name" => "columns_number_tablet",
                "value" => array(4, 3, 2, 1),
                "std" => 2,
                "admin_label" => true,
                "description" => esc_html__("Select columns count in tablet.", 'nasa-core')
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Product Category", 'nasa-core'),
                "param_name" => "cat",
                "admin_label" => true,
                "value" => nasa_get_cat_product_array(),
                "description" => esc_html__("Input the category name here.", 'nasa-core')
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Optimized speed", 'nasa-core'),
                "param_name" => "is_ajax",
                "value" => array(
                    esc_html__('Yes', 'nasa-core') => 'yes',
                    esc_html__('No', 'nasa-core') => 'no'
                ),
                "std" => 'yes',
                "admin_label" => true
            ),
            
            array(
                "type" => "textfield",
                "heading" => esc_html__('Min height (px)', 'nasa-core'),
                "param_name" => "min_height",
                "std" => 'auto',
                "description" => esc_html__('Only use when Optimized speed "Yes"', 'nasa-core')
            ),
            
            array(
                "type" => "textfield",
                "heading" => esc_html__("Extra class name", 'nasa-core'),
                "param_name" => "el_class",
                "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'nasa-core')
            )
        )
    ));
}