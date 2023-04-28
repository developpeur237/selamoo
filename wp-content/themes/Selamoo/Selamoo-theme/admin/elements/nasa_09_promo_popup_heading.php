<?php

if (!function_exists('digi_promo_popup_heading')) {
    add_action('init', 'digi_promo_popup_heading');

    function digi_promo_popup_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if (empty($of_options)) {
            $of_options = array();
        }

        $of_options[] = array(
            "name" => esc_html__("Promo Popup", 'digi-theme'),
            "target" => 'promo-popup',
            "type" => "heading"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Enable promo popup", 'digi-theme'),
            "desc" => esc_html__("Enable promo popup", 'digi-theme'),
            "id" => "promo_popup",
            "std" => 0,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Popup width", 'digi-theme'),
            "id" => "pp_width",
            "std" => "412",
            "type" => "text"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Popup height", 'digi-theme'),
            "id" => "pp_height",
            "std" => "412",
            "type" => "text"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Popup content", 'digi-theme'),
            "id" => "pp_content",
            "std" => '<h3>Adrian</h3><span>Sign up for our newsletter and get 20% off your order. Pretty sweet, we know.</span>',
            "type" => "textarea"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Select contact form", 'digi-theme'),
            "desc" => esc_html__("Select contact form", 'digi-theme'),
            "id" => "pp_contact_form",
            "type" => "select",
            'override_numberic' => true,
            "options" => get_contactForm7Items()
        );
        
        $of_options[] = array(
            "name" => esc_html__("Popup Background Color", 'digi-theme'),
            "desc" => esc_html__("Insert popup background color.", 'digi-theme'),
            "id" => "pp_background_color",
            "std" => "#fff",
            "type" => "color"
        );
        $of_options[] = array(
            "name" => esc_html__("Popup Background", 'digi-theme'),
            "desc" => esc_html__("Insert popup background.", 'digi-theme'),
            "id" => "pp_background_image",
            "std" => "",
            "type" => "media"
        );
    }

}


function get_contactForm7Items() {
    $items = array('default' => esc_html__('Select the Contact form', 'digi-theme'));
    
    if(class_exists('WPCF7_ContactForm')) {
        $contacts = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => WPCF7_ContactForm::post_type
        ));

        if (!empty($contacts)) {
            foreach ($contacts as $key => $value) {
                $items[$value->ID] = $value->post_title;
            }
        }
    }
    
    return $items;
}
