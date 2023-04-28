<?php
if (!function_exists('digi_general_heading')) {
    add_action('init', 'digi_general_heading');
    function digi_general_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if(empty($of_options)) {
            $of_options = array();
        }
        
        $of_options[] = array(
            "name" => esc_html__("General", 'digi-theme'),
            "target" => 'general',
            "type" => "heading"
        );

        if(get_option('nasatheme_imported') !== 'imported') {
            $of_options[] = array(
                "name" => esc_html__("Import Demo Content", 'digi-theme'),
                "desc" => esc_html__("Click for import. Please ensure our plugins are activated before content is imported.", 'digi-theme'),
                "id" => "demo_data",
                'href' => '#',
                "std" => "",
                "btntext" => esc_html__("Import Demo Content", 'digi-theme'),
                "type" => "button"
            );
        }
        else {
            $of_options[] = array(
                "name" => esc_html__("Demo data imported", 'digi-theme'),
                "std" => '<h3 style="background: #fff; margin: 0; padding: 5px 10px;">' . esc_html__("Demo data was imported. If you want import demo data again, You should need reset data of your site.", 'digi-theme') . "</h3>",
                "type" => "info"
            );
        }
        
        $of_options[] = array(
            "name" => esc_html__("Site Layout", 'digi-theme'),
            "desc" => esc_html__("Selects site layout.", 'digi-theme'),
            "id" => "site_layout",
            "std" => "wide",
            "type" => "select",
            "options" => array(
                "wide" => esc_html__("Wide", 'digi-theme'),
                "boxed" => esc_html__("Boxed", 'digi-theme')
            )
        );

        $of_options[] = array(
            "name" => esc_html__("Site Background Color", 'digi-theme'),
            "id" => "site_bg_color",
            "std" => "#eee",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Site Background Image", 'digi-theme'),
            "id" => "site_bg_image",
            "std" => DIGI_THEME_URI . "/assets/images/bkgd1.jpg",
            "type" => "media"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Login or register by Ajax form", 'digi-theme'),
            "desc" => esc_html__("Enable Login or register by Ajax form", 'digi-theme'),
            "id" => "login_ajax",
            "std" => 1,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Mobile Menu Layout", 'digi-theme'),
            "id" => "mobile_menu_layout",
            "std" => "",
            "type" => "select",
            "options" => array(
                "dark" => esc_html__("Dark", 'digi-theme'),
                "light" => esc_html__("Light", 'digi-theme')
            )
        );

        $of_options[] = array(
            "name" => esc_html__("Disable Transition Loading", 'digi-theme'),
            "desc" => esc_html__("Disable transition loading for all page", 'digi-theme'),
            "id" => "disable_wow",
            "std" => 0,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Delay overlay items", 'digi-theme'),
            "desc" => esc_html__("(ms) Delay overlay items.", 'digi-theme'),
            "id" => "delay_overlay",
            "std" => "100",
            "type" => "text"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Optimized speed", 'digi-theme'),
            "desc" => esc_html__("Enable Optimized speed", 'digi-theme'),
            "id" => "enable_optimized_speed",
            "std" => '0',
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Optimized type load", 'digi-theme'),
            "desc" => esc_html__("Optimized type load.", 'digi-theme'),
            "id" => "optimized_type",
            "std" => "sync",
            "type" => "select",
            "options" => array(
                "sync" => esc_html__("Synchronous load", 'digi-theme'),
                "async" => esc_html__("Asynchronous load", 'digi-theme')
            )
        );
        
        $of_options[] = array(
            "name" => esc_html__("Effect before load site", 'digi-theme'),
            "desc" => esc_html__("Enable Effect before load site", 'digi-theme'),
            "id" => "effect_before_load",
            "std" => 1,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Disable UX vertical menu.", 'digi-theme'),
            "id" => "disable_nav_extra",
            "desc" => esc_html__("Disable UX vertical menu.", 'digi-theme'),
            "std" => 0,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Style UX vertical menu icon", 'digi-theme'),
            "desc" => esc_html__("Style UX vertical menu icon.", 'digi-theme'),
            "id" => "style-nav_extra-icon",
            "std" => "style-2",
            "type" => "select",
            "options" => array(
                'style-1' => esc_html__('Light', 'digi-theme'),
                'style-2' => esc_html__('Dark', 'digi-theme')
            )
        );
    }
}
