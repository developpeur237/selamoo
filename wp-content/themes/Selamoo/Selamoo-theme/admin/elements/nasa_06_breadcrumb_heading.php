<?php
if (!function_exists('digi_breadcrumb_heading')) {
    add_action('init', 'digi_breadcrumb_heading');
    function digi_breadcrumb_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if(empty($of_options)) {
            $of_options = array();
        }
        
        $of_options[] = array(
            "name" => esc_html__("Breadcrumb", 'digi-theme'),
            "target" => 'breadcumb',
            "type" => "heading"
        );

        $of_options[] = array(
            "name" => esc_html__("Show breadcrumb", 'digi-theme'),
            "desc" => esc_html__("Show breadcrumb", 'digi-theme'),
            "id" => "breadcrumb_show",
            "std" => 1,
            "type" => "checkbox",
            'class' => 'nasa-breadcrumb-flag-option'
        );

        $of_options[] = array(
            "name" => esc_html__("Breadcrumb type", 'digi-theme'),
            "desc" => esc_html__("Choose breadcrumb type.", 'digi-theme'),
            "id" => "breadcrumb_type",
            "std" => "Default",
            "type" => "select",
            "options" => array(
                "default" => esc_html__("Without Background", 'digi-theme'),
                "has-background" => esc_html__("With Background", 'digi-theme')
            ),
            'class' => 'hidden-tag nasa-breadcrumb-type-option'
        );

        $of_options[] = array(
            "name" => esc_html__("Breadcrumb background image.", 'digi-theme'),
            "desc" => esc_html__("Breadcrumb background image.", 'digi-theme'),
            "id" => "breadcrumb_bg",
            "std" => DIGI_ADMIN_DIR_URI . 'assets/images/breadcrumb-bg.jpg',
            "type" => "media",
            'class' => 'hidden-tag nasa-breadcrumb-bg-option'
        );

        $of_options[] = array(
            "name" => esc_html__("Breadcrumb background color.", 'digi-theme'),
            "desc" => esc_html__("Breadcrumb background color.", 'digi-theme'),
            "id" => "breadcrumb_bg_color",
            "std" => "",
            "type" => "color",
            'class' => 'hidden-tag nasa-breadcrumb-color-option'
        );
        
        $of_options[] = array(
            "name" => esc_html__("Breadcrumb background parallax", 'digi-theme'),
            "desc" => esc_html__("Enable breadcrumb background parallax", 'digi-theme'),
            "id" => "breadcrumb_bg_lax",
            "std" => 0,
            "type" => "checkbox",
            'class' => 'hidden-tag nasa-breadcrumb-bg-lax'
        );

        $of_options[] = array(
            "name" => esc_html__("Height breadcrumb", 'digi-theme'),
            "desc" => esc_html__("Height breadcrumb. (px)", 'digi-theme'),
            "id" => "breadcrumb_height",
            "std" => "150",
            "type" => "text",
            'class' => 'hidden-tag nasa-breadcrumb-height-option'
        );

        $of_options[] = array(
            "name" => esc_html__("Breadcrumb Text Color", 'digi-theme'),
            "desc" => esc_html__("Change Breadcrumb Text Color", 'digi-theme'),
            "id" => "breadcrumb_color",
            "std" => "#fff",
            "type" => "color",
            'class' => 'hidden-tag nasa-breadcrumb-text-option'
        );
    }
}
