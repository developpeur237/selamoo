<?php
if (!function_exists('digi_product_detail_heading')) {
    add_action('init', 'digi_product_detail_heading');
    function digi_product_detail_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if(empty($of_options)) {
            $of_options = array();
        }
        
        $of_options[] = array(
            "name" => esc_html__("Product Detail", 'digi-theme'),
            "target" => 'product-detail',
            "type" => "heading",
        );

        $of_options[] = array(
            "name" => esc_html__("Product Sidebar", 'digi-theme'),
            "id" => "product_sidebar",
            "std" => "left",
            "type" => "select",
            "options" => array(
                "left" => esc_html__("Left Sidebar", 'digi-theme'),
                "right" => esc_html__("Right Sidebar", 'digi-theme'),
                "no" => esc_html__("No sidebar", 'digi-theme')
            )
        );
        
        $of_options[] = array(
            "name" => esc_html__("Enable Hover product Zoom", 'digi-theme'),
            "id" => "product-zoom",
            "desc" => esc_html__("Enable product hover zoom on product detail page", 'digi-theme'),
            "std" => 1,
            "type" => "checkbox"
        );

        $of_options[] = array(
            "name" => esc_html__("Additional Global tab/section title", 'digi-theme'),
            "id" => "tab_title",
            "std" => "Add Tags",
            "type" => "text"
        );

        $of_options[] = array(
            "name" => esc_html__("Additional Global tab/section content", 'digi-theme'),
            "id" => "tab_content",
            "std" => "Custom Tab Content here. <br />Tail sed sausage magna quis commodo snasae. Aliquip strip steak esse ex in ham hock fugiat in. Labore velit pork belly eiusmod ut shank doner capicola consectetur landjaeger fugiat excepteur short loin. Pork belly laboris mollit in leberkas qui. Pariatur snasae aliqua pork chop venison veniam. Venison sed cow short loin bresaola shoulder cupidatat capicola drumstick dolore magna shankle.",
            "type" => "textarea"
        );

        $of_options[] = array(
            "name" => esc_html__("Enable Technical Specifications", 'digi-theme'),
            "id" => "enable_specifications",
            "desc" => esc_html__("Enable Technical Specifications", 'digi-theme'),
            "std" => "1",
            "type" => "checkbox"
        );

        $of_options[] = array(
            "name" => esc_html__("Show the Specifications in the Desciption tab", 'digi-theme'),
            "id" => "merge_specifi_to_desc",
            "desc" => esc_html__("Show the Specifications in the Desciption tab", 'digi-theme'),
            "std" => "1",
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Number for relate products", 'digi-theme'),
            "id" => "release_product_number",
            "std" => "12",
            "type" => "text"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Columns Relate | Upsell products", 'digi-theme'),
            "id" => "relate_columns_desk",
            "std" => "5-cols",
            "type" => "select",
            "options" => array(
                "3-cols" => esc_html__("3 columns", 'digi-theme'),
                "4-cols" => esc_html__("4 columns", 'digi-theme'),
                "5-cols" => esc_html__("5 columns", 'digi-theme')
            )
        );
        
        $of_options[] = array(
            "name" => esc_html__("Columns Relate | Upsell products for mobile", 'digi-theme'),
            "id" => "relate_columns_small",
            "std" => "1-col",
            "type" => "select",
            "options" => array(
                "1-cols" => esc_html__("1 column", 'digi-theme'),
                "2-cols" => esc_html__("2 columns", 'digi-theme')
            )
        );
        
        $of_options[] = array(
            "name" => esc_html__("Columns Relate | Upsell products for Taplet", 'digi-theme'),
            "id" => "relate_columns_tablet",
            "std" => "3-cols",
            "type" => "select",
            "options" => array(
                "1-col" => esc_html__("1 column", 'digi-theme'),
                "2-cols" => esc_html__("2 columns", 'digi-theme'),
                "3-cols" => esc_html__("3 columns", 'digi-theme')
            )
        );
        
        // Enable AJAX add to cart buttons on Detail OR Quickview
        $of_options[] = array(
            "name" => esc_html__("Enable AJAX add to cart buttons on Detail And Quickview", 'digi-theme'),
            "id" => "enable_ajax_addtocart",
            "desc" => esc_html__("Enable AJAX add to cart buttons on Detail And Quickview", 'digi-theme'),
            "std" => "1",
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Focus main image", 'digi-theme'),
            "id" => "enable_focus_main_image",
            "desc" => esc_html__("Scroll to main image when active variable product", 'digi-theme'),
            "std" => "0",
            "type" => "checkbox"
        );
    }
}
