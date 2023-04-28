<?php
add_shortcode("nasa_pin_products_banner", "nasa_sc_pin_products_banner");
function nasa_sc_pin_products_banner($atts, $content = null) {
    global $woocommerce, $nasa_opt;
    
    if (!$woocommerce) {
        return $content;
    }
    
    $dfAttr = array(
        'pin_id' => '',
        'marker_style' => 'price',
        'show_img' => 'no',
        'show_price' => 'no',
        'pin_effect' => 'yes',
        'bg_icon' => '',
        'txt_color' => '',
        'border_icon' => '',
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
            
            return nasa_shortcode_text('nasa_pin_products_banner', $atts);
        }

        // Load ajax
        elseif($atts['is_ajax'] == 'yes' && $_REQUEST['nasa_load_ajax'] == '1') {
            extract(shortcode_atts($dfAttr, nasa_shortcode_vars($atts)));
        }
    }

    if(!(int) $pin_id) {
        return $content;
    }
    
    $pin = get_post((int) $pin_id);
    
    if(!$pin || $pin->post_status !== 'publish') {
        return $content;
    }
    
    $content = '';
    // Get current image.
    $attachment_id = get_post_meta($pin->ID, 'nasa_pin_pb_image_url', true);
    if ($attachment_id) {
        // Get image source.
        $image_src = wp_get_attachment_url($attachment_id);
        $pin_rand_id = 'nasa_pin_pb_' . rand(0, 99999);
        $data = array(
            $pin_rand_id => array()
        );
        $_width = get_post_meta($pin->ID, 'nasa_pin_pb_image_width', true);
        $_height = get_post_meta($pin->ID, 'nasa_pin_pb_image_height', true);
        $_options = get_post_meta($pin->ID, 'nasa_pin_pb_options', true);

        $_optionsArr = json_decode($_options);

        if(!isset($marker_style) || !in_array($marker_style, array('price', 'plus'))) {
            $marker_style = 'price';
        }

        $popover = '';
        $icon = '';
        $style = 'width:35px;height:35px;';
        $icon_style = '';
        if($bg_icon != '' || $txt_color != '' || $border_icon != '') {
            $icon_style .= ' style="';
            $icon_style .= $bg_icon != '' ? 'background-color:' . $bg_icon . ';' : '';
            $icon_style .= $txt_color != '' ? 'color:' . $txt_color . ';' : '';
            $icon_style .= $border_icon != '' ? 'border-color:' . $border_icon . ';' : '';
            $icon_style .= '" ';
        }

        $effect_style = $bg_icon != '' ? ' style="background-color:' . $bg_icon . ';"' : '';

        switch ($marker_style) {
            case 'plus':
                $icon = '<i class="nasa-marker-icon fa fa-plus"' . $icon_style . '></i>';
                $popover = ' popove-plus-wrap';
                break;

            case 'price':
            default:
                $style = 'min-width:45px;height:45px;';
                break;
        }

        $k = 0;
        $price_html = array();
        if(is_array($_optionsArr) && !empty($_optionsArr)) {
            foreach ($_optionsArr as $option) {
                $product_id = $option->product_id;
                $product = wc_get_product($product_id);
                if(!isset($option->coords) || !$product || $product->get_status() !== 'publish') {
                    continue;
                }

                if($marker_style == 'price') {
                    if($product->get_type() == 'variable') {
                        $price_sale = $product->get_variation_sale_price();
                        $price = !$price_sale ? $product->get_variation_regular_price() : $price_sale;
                    } else {
                        $price_sale = $product->get_sale_price();
                        $price = !$price_sale ? $product->get_regular_price() : $price_sale;
                    }

                    $icon = '<span class="nasa-marker-icon-bg"' . $icon_style . '>' . wc_price($price) . '</span>';
                }

                $data[$pin_rand_id][$k] = array(
                    'marker_pin' => $icon,
                    'id_product' => $product_id,
                    'title_product' => $product->get_name(),
                    'link_product' => esc_url($product->get_permalink()),
                    'img_product' => $product->get_image('shop_catalog'),
                    'coords' => $option->coords
                );

                if(!isset($price_html[$product_id])) {
                    $price_html[$product_id] = $product->get_price_html();
                }

                $k++;
            }
        }

        $canvas = array(
            'src' => $image_src,
            'width' => $_width,
            'height' => $_height
        );

        $data[$pin_rand_id]['canvas'] = $canvas;

        $data_pin = wp_json_encode($data);

        if($pin_effect == 'default') {
            $effect_class = isset($nasa_opt['effect_pin_product_banner']) && $nasa_opt['effect_pin_product_banner'] ? ' nasa-has-effect' : '';
        } else {
            $effect_class = $pin_effect == 'yes' ? ' nasa-has-effect' : '';
        }

        $effect_class .= $el_class != '' ? ' ' . $el_class : '';

        $content .= '<div class="nasa-inner-wrap nasa-pin-banner-wrap' . $effect_class . '" data-pin="' . esc_attr($data_pin) . '">';
        if(!empty($price_html)) {
            foreach ($price_html as $k => $price_product) {
                $content .= '<div class="hidden-tag nasa-price-pin-' . $k . '">' . $price_product . '</div>';
            }
        }

        $content .= '<span class="nasa-wrap-relative-image">' .
            '<img class="nasa_pin_pb_image" src="' . esc_url($image_src) . '" data-easypin_id="' . $pin_rand_id . '" alt="' . esc_attr($pin->post_title) . '" />' .
        '</span>';
        $content .= '<div style="display:none;" class="nasa-easypin-tpl">';
        $content .= 
        '<div class="nasa-popover-clone">' .
            '<div class="exPopoverContainer' . $popover . '">' .
                '<div class="popBg borderRadius"></div>' .
                '<div class="popBody">' .
                    '<div class="nasa-product-pin">' .
                        '<div class="nasa-product-pin-wrap">' .
                            '<div class="row">' .
                                '<div class="large-12 columns">' .
                                    '<a title="{[title_product]}" href="{[link_product]}">' .
                                        ($show_img === 'yes' ? '<div class="image-wrap">{[img_product]}</div>' : '') .
                                        '<div class="title-wrap">' .
                                            '<h5>{[title_product]}</h5>' .
                                        '</div>' .
                                    '</a>' .
                                    ($show_price === 'yes' ? '<div class="price nasa-price-pin" data-product_id="{[id_product]}"></div>' : '') .
                                '</div>' .
                            '</div>' .
                        '</div>' .
                    '</div>' .
                '</div>' .
            '</div>' .
        '</div>' .
        '<div class="nasa-marker-clone">' .
            '<div style="' . $style . '">' .
                '<span class="nasa-marker-icon-wrap">{[marker_pin]}<span class="nasa-action-effect"' . $effect_style . '></span></span>' .
            '</div>' .
        '</div>'; 
        $content .= '</div>';
        $content .= '</div>';
    }
    
    return $content;
}

// **********************************************************************// 
// ! Register New Element: Product Banner
// **********************************************************************//
add_action('init', 'nasa_register_products_banner');
function nasa_register_products_banner(){
    $products_banner_params = array(
        "name" => "Products banner",
        "base" => "nasa_pin_products_banner",
        "icon" => "icon-wpb-nasatheme",
        'description' => esc_html__("Display products pin banner.", 'nasa-core'),
        "category" => "Nasa Core",
        "params" => array(
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Select pin', 'nasa-core'),
                "param_name" => 'pin_id',
                "value" => nasa_get_pin_ids(),
                "std" => '',
                "admin_label" => true
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Marker style", 'nasa-core'),
                "param_name" => "marker_style",
                "value" => array(
                    esc_html__('Price icon', 'nasa-core') => 'price',
                    esc_html__('Plus icon', 'nasa-core') => 'plus'
                ),
                "std" => 'price',
                "admin_label" => true
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Show image", 'nasa-core'),
                "param_name" => "show_img",
                "value" => array(
                    esc_html__('No', 'nasa-core') => 'no',
                    esc_html__('Yes', 'nasa-core') => 'yes'
                ),
                "std" => 'no'
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Show price", 'nasa-core'),
                "param_name" => "show_price",
                "value" => array(
                    esc_html__('No', 'nasa-core') => 'no',
                    esc_html__('Yes', 'nasa-core') => 'yes'
                ),
                "std" => 'no'
            ),
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Effect icons", 'nasa-core'),
                "param_name" => "pin_effect",
                "value" => array(
                    esc_html__('Yes', 'nasa-core') => 'yes',
                    esc_html__('No', 'nasa-core') => 'no'
                ),
                "std" => 'yes'
            ),
            
            array(
                "type" => "colorpicker",
                "heading" => esc_html__("Background icon", 'nasa-core'),
                "param_name" => "bg_icon",
                "value" => "",
                "description" => esc_html__("Choose Background color.", 'nasa-core')
            ),
            
            array(
                "type" => "colorpicker",
                "heading" => esc_html__("Text color icon", 'nasa-core'),
                "param_name" => "txt_color",
                "value" => "",
                "description" => esc_html__("Choose text color.", 'nasa-core')
            ),
            
            array(
                "type" => "colorpicker",
                "heading" => esc_html__("Border color icon", 'nasa-core'),
                "param_name" => "border_icon",
                "value" => "",
                "description" => esc_html__("Choose border color.", 'nasa-core')
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
    vc_map($products_banner_params);
}

function nasa_get_pin_ids() {
    $pins = get_posts(array(
        'posts_per_page'    => -1,
        'post_status'       => 'publish',
        'post_type'         => 'nasa_pin_pb'
    ));
    
    $pin_pb = array(esc_html__('Select pin products banner', 'nasa-core') => '');
    if($pins) {
        foreach ($pins as $pin) {
            $pin_pb[$pin->post_title] = $pin->ID;
        }
    }
    
    return $pin_pb;
}