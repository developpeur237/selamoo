<?php
add_shortcode("nasa_product_categories", "nasa_sc_product_categories");
function nasa_sc_product_categories($atts, $content = null) {
    global $woocommerce, $nasa_opt;
    
    if (!$woocommerce) {
        return $content;
    }
    
    $dfAttr = array(
        'number' => '5',
        'title' => '',
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => 1,
        'parent' => '0',
        'infinitive' => 'false',
        'disp_type' => 'Horizontal3',
        'columns_number' => '4',
        'columns_number_small' => '2',
        'columns_number_tablet' => '4',
        'number_vertical' => '2',
        'auto_slide' => 'true',
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
            
            return nasa_shortcode_text('nasa_product_categories', $atts);
        }

        // Load ajax
        elseif($atts['is_ajax'] == 'yes' && $_REQUEST['nasa_load_ajax'] == '1') {
            extract(shortcode_atts($dfAttr, nasa_shortcode_vars($atts)));
        }
    }

    $_delay_item = (isset($nasa_opt['delay_overlay']) && (int) $nasa_opt['delay_overlay']) ? (int) $nasa_opt['delay_overlay'] : 100;
    $delay_animation_product = $_delay_item;
    $el_class = trim($el_class) != '' ? ' ' . esc_attr($el_class) : '';
    $ids = (isset($atts['ids']) && $atts['ids'] != '') ? 
        array_map('trim', explode(',', $atts['ids'])) : array();
    $hide_empty = (bool) $hide_empty ? 1 : 0;
    $auto_slide = $auto_slide == 'true' ? 'true' : 'false';

    $args = array(
        'taxonomy' => 'product_cat',
        'orderby' => $orderby,
        'order' => $order,
        'hide_empty' => $hide_empty,
        'include' => $ids,
        'pad_counts' => true
    );
    
    if($parent != 'false') {
        $args['parent'] = 0;
    }
    
    if(version_compare($woocommerce->version, '3.3.0', ">=") && (!isset($nasa_opt['show_uncategorized']) || !$nasa_opt['show_uncategorized'])) {
        $args['exclude'] = get_option('default_product_cat');
    }

    $product_categories = get_terms(apply_filters('woocommerce_product_attribute_terms', $args));
    $product_categories = (int) $number ? array_slice($product_categories, 0, (int) $number) : $product_categories;

    ob_start();
    if (function_exists('wc_print_notices') && $product_categories) :
        if ($title): ?>
            <div class="row<?php echo $el_class; ?>">
                <div class="large-12 columns">
                    <h3 class="section-title"><span><?php echo esc_attr($title); ?></span></h3>
                    <div class="nasa-hr full"></div>
                </div>
            </div>
        <?php endif; ?>

        <?php switch ($disp_type) {
            case 'Vertical':
                ?>
                <div class="vertical-slider nasa-category-slider-vertical nasa-mgr-y-20 wow fadeInUp<?php echo $el_class; ?>" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr($delay_animation_product); ?>ms">
                    <div
                        class="nasa-vertical-slider"
                        data-autoplay="<?php echo esc_attr($auto_slide); ?>"
                        data-show="<?php echo esc_attr($number_vertical); ?>">
                        <?php
                        foreach ($product_categories as $category) :
                            include NASA_CORE_PRODUCT_LAYOUTS . 'nasa_product_categories/content-product_cat_vertical.php';
                        endforeach;
                        ?>
                    </div> 
                </div>
                <?php
                break;
            case 'Horizontal3':
                $data_margin = '0';
                $class_hozi = 'nasa-category-horizontal-3';
                $disable_nav = 'false';
                $file = '3';
                ?>
                <div class="group-slider category-slider nasa-category-slider-horizontal-3 nasa-category-slider-horizontal<?php echo $el_class; ?>">
                    <div
                        class="nasa-slider products-group owl-carousel <?php echo $class_hozi; ?>"
                        data-autoplay="<?php echo $auto_slide; ?>"
                        data-loop="<?php echo $auto_slide; ?>"
                        data-disable-nav="<?php echo $disable_nav; ?>"
                        data-columns="<?php echo esc_attr($columns_number); ?>"
                        data-columns-small="<?php echo esc_attr($columns_number_small); ?>"
                        data-columns-tablet="<?php echo esc_attr($columns_number_tablet); ?>"
                        data-margin="<?php echo $data_margin; ?>">
                        <?php
                        foreach ($product_categories as $category) :
                            if(is_file(NASA_CORE_PRODUCT_LAYOUTS . 'nasa_product_categories/content-product_cat_horizontal_' . $file . '.php')) :
                                include NASA_CORE_PRODUCT_LAYOUTS . 'nasa_product_categories/content-product_cat_horizontal_' . $file . '.php';
                                $delay_animation_product += $_delay_item;
                            endif;
                        endforeach;
                        ?>
                    </div> 
                </div>
                <?php
                break;
            case 'Horizontal1':
            case 'Horizontal2':
            default:
                $data_margin = $disp_type == 'Horizontal2' ? '20' : '10';
                $class_hozi = $disp_type == 'Horizontal2' ? 'nasa-category-horizontal-2' : 'nasa-category-horizontal-1';
                $disable_nav = $disp_type == 'Horizontal2' ? 'false' : 'true';
                $file = $disp_type == 'Horizontal2' ? '2' : '1';
                ?>
                <div class="group-slider category-slider nasa-category-slider-horizontal<?php echo $el_class; ?>">
                    <div
                        class="nasa-slider products-group owl-carousel <?php echo $class_hozi; ?>"
                        data-autoplay="<?php echo $auto_slide; ?>"
                        data-loop="<?php echo $auto_slide; ?>"
                        data-disable-nav="<?php echo $disable_nav; ?>"
                        data-columns="<?php echo esc_attr($columns_number); ?>"
                        data-columns-small="<?php echo esc_attr($columns_number_small); ?>"
                        data-columns-tablet="<?php echo esc_attr($columns_number_tablet); ?>"
                        data-margin="<?php echo $data_margin; ?>">
                        <?php
                        foreach ($product_categories as $category) :
                            if(is_file(NASA_CORE_PRODUCT_LAYOUTS . 'nasa_product_categories/content-product_cat_horizontal_' . $file . '.php')) :
                                include NASA_CORE_PRODUCT_LAYOUTS . 'nasa_product_categories/content-product_cat_horizontal_' . $file . '.php';
                                $delay_animation_product += $_delay_item;
                            endif;
                        endforeach;
                        ?>
                    </div> 
                </div>
                <?php
                break;
        }
        woocommerce_reset_loop();
    endif;

    $content = ob_get_clean();
    
    return $content;
}

// **********************************************************************// 
// ! Register New Element: Product Categories
// **********************************************************************//
add_action('init', 'nasa_register_productCategories');
function nasa_register_productCategories(){
    $products_categories_list_params = array(
        "name" => "Product categories",
        "base" => "nasa_product_categories",
        "icon" => "icon-wpb-nasatheme",
        'description' => esc_html__("Display categories as images slide.", 'nasa-core'),
        "category" => "Nasa Core",
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__('Title', 'nasa-core'),
                "param_name" => 'title'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Categories number for display', 'nasa-core'),
                "param_name" => 'number',
                "value" => '5'
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Display type', 'nasa-core'),
                "param_name" => 'disp_type',
                "value" => array(
                    "Horizontal 1"  => 'Horizontal1',
                    "Horizontal 2"  => 'Horizontal2',
                    "Horizontal 3"  => 'Horizontal3',
                    "Vertical"      => 'Vertical'
                ),
                "std" => 'Horizontal3'
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Only Show top level', 'nasa-core'),
                "param_name" => 'parent',
                "value" => array(
                    esc_html__('Yes, please', 'nasa-core') => 'true',
                    esc_html__('No, thank', 'nasa-core') => 'false'
                ),
                "std" => 'true',
                "description" => esc_html__("Only Show top level.", 'nasa-core')
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Hide empty categories', 'nasa-core'),
                "param_name" => 'hide_empty',
                "value" => array(
                    esc_html__('Yes, please', 'nasa-core') => '1',
                    esc_html__('No, thank', 'nasa-core') => '0'
                ),
                "std" => '1',
                "description" => esc_html__("Hide empty categories.", 'nasa-core')
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Items Columns', 'nasa-core'),
                "param_name" => 'columns_number',
                "value" => array(
                    "2" => '2',
                    "3" => '3',
                    "4" => '4',
                    "5" => '5',
                    "6" => '6',
                    "7" => '7',
                    "8" => '8',
                    "9" => '9',
                    "10" => '10'
                ),
                "std" => '4',
                "dependency" => array(
                    "element" => "disp_type",
                    "value" => array(
                        "Horizontal1",
                        "Horizontal2",
                        "Horizontal3"
                    )
                ),
                "description" => esc_html__("Only using for Display type is Horizontal.", 'nasa-core')
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Columns number small", 'nasa-core'),
                "param_name" => "columns_number_small",
                "value" => array(3, 2, 1),
                "std" => 2,
                "dependency" => array(
                    "element" => "disp_type",
                    "value" => array(
                        "Horizontal1",
                        "Horizontal2",
                        "Horizontal3"
                    )
                ),
                "description" => esc_html__("Only using for Display type is Horizontal.", 'nasa-core')
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Columns number tablet", 'nasa-core'),
                "param_name" => "columns_number_tablet",
                "value" => array(4, 3, 2, 1),
                "std" => 4,
                "dependency" => array(
                    "element" => "disp_type",
                    "value" => array(
                        "Horizontal1",
                        "Horizontal2",
                        "Horizontal3"
                    )
                ),
                "description" => esc_html__("Only using for Display type is Horizontal.", 'nasa-core')
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Items show vertical', 'nasa-core'),
                "param_name" => 'number_vertical',
                "value" => array(
                    "1" => '1',
                    "2" => '2',
                    "3" => '3',
                    "4" => '4',
                ),
                "dependency" => array(
                    "element" => "disp_type",
                    "value" => array(
                        "Vertical"
                    )
                ),
                "description" => esc_html__("Only using for Display type is Vertical.", 'nasa-core')
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Slide auto', 'nasa-core'),
                "param_name" => 'auto_slide',
                "value" => array(
                    esc_html__('Yes, please', 'nasa-core') => 'true',
                    esc_html__('No, thank', 'nasa-core') => 'false'
                ),
                "std" => 'true',
                "description" => esc_html__("Auto slider.", 'nasa-core')
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
                "heading" => esc_html__("Extra Class", 'nasa-core'),
                "param_name" => "el_class",
                "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nasa-core')
            )
        )
    );
    vc_map($products_categories_list_params);
}
