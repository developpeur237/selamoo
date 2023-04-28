<?php
if (!function_exists('digi_product_compare_heading')) {
    add_action('init', 'digi_product_compare_heading');
    function digi_product_compare_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if(empty($of_options)) {
            $of_options = array();
        }
        
        $of_options[] = array(
            "name" => esc_html__("Products Compare ", 'digi-theme'),
            "target" => 'product-compare',
            "type" => "heading",
        );
        
        global $yith_woocompare;
        if($yith_woocompare) {
            $of_options[] = array(
                "name" => esc_html__("Enable Nasa compare products Extends Yith Plugin Compare", 'digi-theme'),
                "id" => "nasa-product-compare",
                "desc" => esc_html__("Enable Nasa compare products", 'digi-theme'),
                "std" => 1,
                "type" => "checkbox"
            );
            
            $of_options[] = array(
                "name" => esc_html__("Page view compare products", 'digi-theme'),
                "desc" => esc_html__("Select page view compare products.", 'digi-theme'),
                "id" => "nasa-page-view-compage",
                "type" => "select",
                "options" => get_pages_temp_compare()
            );

            $of_options[] = array(
                "name" => esc_html__("Max products compare", 'digi-theme'),
                "desc" => esc_html__("Change max number display compare products", 'digi-theme'),
                "id" => "max_compare",
                "std" => "4",
                "type" => "select",
                "options" => array("2" => "2", "3" => "3", "4" => "4")
            );
        } else {
            $of_options[] = array(
                "name" => esc_html__("Install Yith Plugin Compare, Please", 'digi-theme'),
                "std" => '<h4 style="color: red">' . esc_html__("Please, Install Yith Plugin Compare!", 'digi-theme') . "</h4>",
                "type" => "info"
            );
        }
    }
}
