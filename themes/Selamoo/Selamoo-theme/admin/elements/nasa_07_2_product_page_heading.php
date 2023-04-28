<?php
if (!function_exists('digi_product_page_heading')) {
    add_action('init', 'digi_product_page_heading');
    function digi_product_page_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if(empty($of_options)) {
            $of_options = array();
        }
        
        $of_options[] = array(
            "name" => esc_html__("Product Category Page", 'digi-theme'),
            "target" => 'product-page',
            "type" => "heading",
        );

        $of_options[] = array(
            "name" => esc_html__("Category page", 'digi-theme'),
            "std" => "<h4>" . esc_html__("Category page (Shop page)", 'digi-theme') . "</h4>",
            "type" => "info"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Enable filter by Root Categories (BROWSER)", 'digi-theme'),
            "id" => "top_filter_rootcat",
            "desc" => esc_html__("Yes, please.", 'digi-theme'),
            "std" => "1",
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Category sidebar", 'digi-theme'),
            "desc" => esc_html__("Select if you want a sidebar on product categories.", 'digi-theme'),
            "id" => "category_sidebar",
            "std" => "top",
            "type" => "select",
            "options" => array(
                "top" => esc_html__("Top Sidebar", 'digi-theme'),
                "left" => esc_html__("Left Sidebar", 'digi-theme'),
                "right" => esc_html__("Right Sidebar", 'digi-theme'),
                "no" => esc_html__("No Sidebar", 'digi-theme')
            )
        );
        
        $of_options[] = array(
            "name" => esc_html__("Top sidebar default show", 'digi-theme'),
            "id" => "top_sidebar_df",
            "desc" => esc_html__("Yes, please.", 'digi-theme'),
            "std" => "1",
            "type" => "checkbox"
        );

        $of_options[] = array(
            "name" => esc_html__("Products Per Row", 'digi-theme'),
            "desc" => esc_html__("Change products number display per row for the Shop page", 'digi-theme'),
            "id" => "products_per_row",
            "std" => "4-cols",
            "type" => "select",
            "options" => array(
                "3-cols" => esc_html__("3 column", 'digi-theme'),
                "4-cols" => esc_html__("4 column", 'digi-theme'),
                "5-cols" => esc_html__("5 column", 'digi-theme'),
            )
        );
        
        $of_options[] = array(
            "name" => esc_html__("Products Per Row for Mobile", 'digi-theme'),
            "id" => "products_per_row_small",
            "std" => "1-col",
            "type" => "select",
            "options" => array(
                "1-cols" => esc_html__("1 column", 'digi-theme'),
                "2-cols" => esc_html__("2 columns", 'digi-theme')
            )
        );
        
        $of_options[] = array(
            "name" => esc_html__("Products Per Row for Tablet", 'digi-theme'),
            "id" => "products_per_row_tablet",
            "std" => "2-cols",
            "type" => "select",
            "options" => array(
                "1-col" => esc_html__("1 column", 'digi-theme'),
                "2-cols" => esc_html__("2 columns", 'digi-theme'),
                "3-cols" => esc_html__("3 columns", 'digi-theme')
            )
        );

        $of_options[] = array(
            "name" => esc_html__("Products per page", 'digi-theme'),
            "id" => "products_pr_page",
            "desc" => esc_html__("Change products per page.", 'digi-theme'),
            "std" => "15",
            "type" => "text"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Select type view", 'digi-theme'),
            "desc" => esc_html__("Select type view.", 'digi-theme'),
            "id" => "products_type_view",
            "std" => "grid",
            "type" => "select",
            "options" => array(
                "grid" => esc_html__("Grid view default", 'digi-theme'),
                "list" => esc_html__("List view default", 'digi-theme')
            )
        );
        
        $of_options[] = array(
            "name" => esc_html__("Enable change view as", 'digi-theme'),
            "id" => "enable_change_view",
            "desc" => esc_html__("Enable change view grid or list.", 'digi-theme'),
            "std" => "1",
            "type" => "checkbox"
        );

        $of_options[] = array(
            "name" => esc_html__("Pagination page style", 'digi-theme'),
            "id" => "pagination_style",
            "desc" => esc_html__("Select style for pagination", 'digi-theme'),
            "std" => 'style-2',
            "type" => "select",
            "options" => array(
                "style-2" => esc_html__("Simple", 'digi-theme'),
                "style-1" => esc_html__("Full", 'digi-theme'),
            )
        );

        $of_options[] = array(
            "name" => esc_html__("Top content Products page", 'digi-theme'),
            "std" => "<h4>" . esc_html__("Top content Products page", 'digi-theme') . "</h4>",
            "type" => "info"
        );

        $of_options[] = array(
            "name" => esc_html__("Enable Category top content", 'digi-theme'),
            "id" => "enable_cat_header",
            "desc" => esc_html__("Enable Category top content", 'digi-theme'),
            "std" => "1",
            "type" => "checkbox"
        );

        $of_options[] = array(
            "name" => esc_html__("Category top content", 'digi-theme'),
            "id" => "cat_header",
            "desc" => esc_html__("Input anything shortcode or text here. Recommend to use the Static Blocks to create banner or anything that you want to show the top content in the Shop page", 'digi-theme'),
            "std" => "",
            "type" => "textarea"
        );
        
    }
}
