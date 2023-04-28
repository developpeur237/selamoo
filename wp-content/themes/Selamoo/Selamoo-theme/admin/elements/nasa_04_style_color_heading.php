<?php
if (!function_exists('digi_style_color_heading')) {
    add_action('init', 'digi_style_color_heading');
    function digi_style_color_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if(empty($of_options)) {
            $of_options = array();
        }
        
        $of_options[] = array(
            "name" => esc_html__("Style and Colors", 'digi-theme'),
            "target" => 'style-color',
            "type" => "heading",
        );

        $of_options[] = array(
            "name" => esc_html__("Style and Colors Global Option", 'digi-theme'),
            "std" => "<h4>" . esc_html__("Style and Colors Global Option", 'digi-theme') . "</h4>",
            "type" => "info"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Primary Color", 'digi-theme'),
            "desc" => esc_html__("Change primary color. Used for primary buttons, link hover, background, etc.", 'digi-theme'),
            "id" => "color_primary",
            "std" => "#296dc1",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Secondary Color", 'digi-theme'),
            "desc" => esc_html__("Change secondary color. Used for sale bubble.", 'digi-theme'),
            "id" => "color_secondary",
            "std" => "#f46e6d",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Success Color", 'digi-theme'),
            "desc" => esc_html__("Change the success color. Used for global success messages.", 'digi-theme'),
            "id" => "color_success",
            "std" => "#5cb85c",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Hot label Color", 'digi-theme'),
            "desc" => esc_html__("Change the hot label color. Used for product hot.", 'digi-theme'),
            "id" => "color_hot_label",
            "std" => "#6db3f4",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Sale label Color", 'digi-theme'),
            "desc" => esc_html__("Change the sale label color. Used for product sale.", 'digi-theme'),
            "id" => "color_sale_label",
            "std" => "#229fff",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Price Color", 'digi-theme'),
            "desc" => esc_html__("Change the Price color. Used for product.", 'digi-theme'),
            "id" => "color_price_label",
            "std" => "#ff3333",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Buttons Style and Color", 'digi-theme'),
            "std" => "<h4>" . esc_html__("Buttons Style and Color", 'digi-theme') . "</h4>",
            "type" => "info"
        );

        $of_options[] = array(
            "name" => esc_html__("Buttons Background Color", 'digi-theme'),
            "desc" => esc_html__("Change background color for buttons.", 'digi-theme'),
            "id" => "color_button",
            "std" => "",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Buttons Background Color Hover", 'digi-theme'),
            "desc" => esc_html__("Change background color hover for buttons. Default is primary color", 'digi-theme'),
            "id" => "color_hover",
            "std" => "",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Buttons Border Color", 'digi-theme'),
            "desc" => esc_html__("Change border color for buttons.", 'digi-theme'),
            "id" => "button_border_color",
            "std" => "",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Buttons Border Color Hover", 'digi-theme'),
            "desc" => esc_html__("Change border color hover for buttons.", 'digi-theme'),
            "id" => "button_border_color_hover",
            "std" => "",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Buttons Text Color", 'digi-theme'),
            "desc" => esc_html__("Change text color for buttons. Default is primary color", 'digi-theme'),
            "id" => "button_text_color",
            "std" => "",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Buttons Text Color Hover", 'digi-theme'),
            "desc" => esc_html__("Change text color hover for buttons.", 'digi-theme'),
            "id" => "button_text_color_hover",
            "std" => "",
            "type" => "color"
        );
        $of_options[] = array(
            "name" => esc_html__("Buttons radius", 'digi-theme'),
            "desc" => esc_html__("Change Buttons Radius. (px)", 'digi-theme'),
            "id" => "button_radius",
            "std" => "5",
            "step" => "1",
            "max" => '100',
            "type" => "sliderui"
        );

        $of_options[] = array(
            "name" => esc_html__("Buttons border", 'digi-theme'),
            "desc" => esc_html__("Change Buttons Border. (px)", 'digi-theme'),
            "id" => "button_border",
            "std" => "1",
            "step" => "1",
            "max" => '5',
            "type" => "sliderui"
        );

        $of_options[] = array(
            "name" => esc_html__("Inputs radius", 'digi-theme'),
            "desc" => esc_html__("Change Radius Inputs. (px)", 'digi-theme'),
            "id" => "input_radius",
            "std" => "5",
            "step" => "1",
            "max" => "100",
            "type" => "sliderui"
        );
    }
}
