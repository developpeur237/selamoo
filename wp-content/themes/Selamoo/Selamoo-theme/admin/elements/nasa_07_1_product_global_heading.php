<?php
if (!function_exists('digi_product_global_heading')) {
    add_action('init', 'digi_product_global_heading');
    function digi_product_global_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if(empty($of_options)) {
            $of_options = array();
        }
        
        $of_options[] = array(
            "name" => esc_html__("Products Global option", 'digi-theme'),
            "target" => 'product-global',
            "type" => "heading",
        );
        
        $of_options[] = array(
            "name" => esc_html__("Hover product effect", 'digi-theme'),
            "desc" => esc_html__("Select if you want change hover product image.", 'digi-theme'),
            "id" => "animated_products",
            "std" => "hover-fade",
            "type" => "select",
            "options" => array(
                "hover-fade" => esc_html__("Fade", 'digi-theme'),
                "hover-flip" => esc_html__("Flip Horizontal", 'digi-theme'),
                "hover-bottom-to-top" => esc_html__("Bottom to top", 'digi-theme'),
                "" => esc_html__("No effect", 'digi-theme')
            )
        );

        $of_options[] = array(
            "name" => esc_html__("Enable Hover overlay", 'digi-theme'),
            "id" => "product-hover-overlay",
            "desc" => esc_html__("Enable product hover black overlay on product grid page", 'digi-theme'),
            "std" => 1,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Hide quickview icon in loop products", 'digi-theme'),
            "id" => "disable-quickview",
            "desc" => esc_html__("Hide quickview icon in loop products.", 'digi-theme'),
            "std" => "0",
            "type" => "checkbox"
        );

        $of_options[] = array(
            "name" => esc_html__("Disable add to cart", 'digi-theme'),
            "id" => "disable-cart",
            "desc" => esc_html__("Disable add to cart button in your site.", 'digi-theme'),
            "std" => "0",
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Show gifts in mini cart", 'digi-theme'),
            "id" => "show_gift_minicart",
            "desc" => esc_html__("Show gifts in mini cart.", 'digi-theme'),
            "std" => 0,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Style cart sidebar", 'digi-theme'),
            "desc" => esc_html__("Style cart sidebar.", 'digi-theme'),
            "id" => "style-cart",
            "std" => "style-1",
            "type" => "select",
            "options" => array(
                'style-1' => esc_html__('Light', 'digi-theme'),
                'style-2' => esc_html__('Dark', 'digi-theme')
            )
        );
        
        $of_options[] = array(
            "name" => esc_html__("Style wishlist sidebar", 'digi-theme'),
            "desc" => esc_html__("Style wishlist sidebar.", 'digi-theme'),
            "id" => "style-wishlist",
            "std" => "style-1",
            "type" => "select",
            "options" => array(
                'style-1' => esc_html__('Light', 'digi-theme'),
                'style-2' => esc_html__('Dark', 'digi-theme')
            )
        );
        
        // Enable Gift in grid
        $of_options[] = array(
            "name" => esc_html__("Enable Promotion Gifts featured icon", 'digi-theme'),
            "desc" => esc_html__("Enable Promotion Gifts featured icon products", 'digi-theme'),
            "id" => "enable_gift_featured",
            "std" => 1,
            "type" => "checkbox"
        );
        
        // Enable effect Gift featured
        $of_options[] = array(
            "name" => esc_html__("Enable Promotion Gifts effect featured icon", 'digi-theme'),
            "desc" => esc_html__("Enable Promotion Gifts effect featured icon.", 'digi-theme'),
            "id" => "enable_gift_effect",
            "std" => 0,
            "type" => "checkbox"
        );

        // Options live search products
        $of_options[] = array(
            "name" => esc_html__("Enable live search products", 'digi-theme'),
            "desc" => esc_html__("Enable live search ajax", 'digi-theme'),
            "id" => "enable_live_search",
            "std" => 1,
            "type" => "checkbox"
        );
        // End Options live search products
        
        // limit_results_search
        $of_options[] = array(
            "name" => esc_html__("Results Ajax search products limit", 'digi-theme'),
            "id" => "limit_results_search",
            "desc" => esc_html__("Input number limit products ajax search result.", 'digi-theme'),
            "std" => "5",
            "type" => "text"
        );
        
        // Options live search products
        $of_options[] = array(
            "name" => esc_html__("Enable search products by Category", 'digi-theme'),
            "desc" => esc_html__("Enable search products by Category", 'digi-theme'),
            "id" => "search_by_cat",
            "std" => 1,
            "type" => "checkbox"
        );
        // End Options live search products
        
        $of_options[] = array(
            "name" => esc_html__("Show Uncategorized", 'digi-theme'),
            "id" => "show_uncategorized",
            "desc" => esc_html__("Show Uncategorized.", 'digi-theme'),
            "std" => 0,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Disable ajax Shop", 'digi-theme'),
            "id" => "disable_ajax_product",
            "desc" => esc_html__("Disable ajax archive product", 'digi-theme'),
            "std" => 0,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Disable ajax Shop Progress bar loading", 'digi-theme'),
            "id" => "disable_ajax_product_progress_bar",
            "desc" => esc_html__("Disable ajax Shop Progress bar loading.", 'digi-theme'),
            "std" => 0,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Disable Viewed products", 'digi-theme'),
            "id" => "disable-viewed",
            "desc" => esc_html__("Disable Viewed products", 'digi-theme'),
            "std" => 0,
            "type" => "checkbox"
        );
        
        // limit_product_viewed
        $of_options[] = array(
            "name" => esc_html__("Viewed products limit", 'digi-theme'),
            "id" => "limit_product_viewed",
            "desc" => esc_html__("Input number limit product viewed.", 'digi-theme'),
            "std" => "12",
            "type" => "text"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Style viewed icon", 'digi-theme'),
            "desc" => esc_html__("Style viewed icon.", 'digi-theme'),
            "id" => "style-viewed-icon",
            "std" => "style-1",
            "type" => "select",
            "options" => array(
                'style-1' => esc_html__('Light', 'digi-theme'),
                'style-2' => esc_html__('Dark', 'digi-theme')
            )
        );
        
        $of_options[] = array(
            "name" => esc_html__("Style viewed sidebar", 'digi-theme'),
            "desc" => esc_html__("Style viewed sidebar.", 'digi-theme'),
            "id" => "style-viewed",
            "std" => "style-1",
            "type" => "select",
            "options" => array(
                'style-1' => esc_html__('Light', 'digi-theme'),
                'style-2' => esc_html__('Dark', 'digi-theme')
            )
        );
    }
}
