<?php
if (!function_exists('digi_logo_icon_heading')) {
    add_action('init', 'digi_logo_icon_heading');
    function digi_logo_icon_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if(empty($of_options)) {
            $of_options = array();
        }
        
        $of_options[] = array(
            "name" => esc_html__("Logo and icons", 'digi-theme'),
            "target" => 'logo-icons',
            "type" => "heading"
        );

        $of_options[] = array(
            "name" => esc_html__("Logo", 'digi-theme'),
            "desc" => esc_html__("Upload logo here.", 'digi-theme'),
            "id" => "site_logo",
            "std" => DIGI_THEME_URI . "/assets/images/logo.png",
            "type" => "media"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Retina Logo", 'digi-theme'),
            "desc" => esc_html__("Upload retina logo.", 'digi-theme'),
            "id" => "site_logo_retina",
            "std" => DIGI_THEME_URI . "/assets/images/logo_retina.png",
            "type" => "media"
        );

        $of_options[] = array(
            "name" => esc_html__("Max height logo", 'digi-theme'),
            "desc" => esc_html__("Max height logo", 'digi-theme'),
            "id" => "max_height_logo",
            "std" => "95px",
            "type" => "text"
        );

        $of_options[] = array(
            "name" => esc_html__("Favicon", 'digi-theme'),
            "desc" => esc_html__("Add your custom Favicon image. 16x16px .ico or .png file required.", 'digi-theme'),
            "id" => "site_favicon",
            "std" => "",
            "type" => "media"
        );
    }
}
