<?php
if (!function_exists('digi_promotion_news_heading')) {
    add_action('init', 'digi_promotion_news_heading');
    function digi_promotion_news_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if(empty($of_options)) {
            $of_options = array();
        }
        
        $of_options[] = array(
            "name" => esc_html__("Promotion news", 'digi-theme'),
            "target" => "promotion-news",
            "type" => "heading"
        );

        $of_options[] = array(
            "name" => esc_html__("Enable Top bar Promotion news", 'digi-theme'),
            "desc" => esc_html__("Checked is Enable", 'digi-theme'),
            "id" => "enable_post_top",
            "std" => 0,
            "type" => "checkbox"
        );

        $of_options[] = array(
            "name" => esc_html__("Type display selected", 'digi-theme'),
            "desc" => esc_html__('Type display "My content custom" or "List posts"', 'digi-theme'),
            "id" => "type_display",
            "std" => 'custom',
            "type" => "select",
            "options" => array(
                'custom' => esc_html__('My content custom', 'digi-theme'),
                'list-posts' => esc_html__('List posts', 'digi-theme')
            ),
            'class' => 'type_promotion'
        );

        $of_options[] = array(
            "name" => esc_html__("My content custom", 'digi-theme'),
            "desc" => '<a href="javascript:void(0);" class="reset_content_custom"><b>Default value</b></a> for My content custom.<br /><a href="javascript:void(0);" class="restore_content_custom"><b>Restore text</b></a> for My content custom.<br />',
            "id" => "content_custom",
            "std" => '',
            'type' => 'textarea',
            'class' => 'hidden-tag nasa-custom_content'
        );

        $of_options[] = array(
            "name" => esc_html__("Category post", 'digi-theme'),
            "desc" => esc_html__("Post in category selected", 'digi-theme'),
            "id" => "category_post",
            "std" => '',
            "type" => "select",
            "options" => digi_get_cats_array(),
            'class' => 'hidden-tag nasa-list_post'
        );

        $of_options[] = array(
            "name" => esc_html__("Limit posts", 'digi-theme'),
            "desc" => esc_html__("Number posts display", 'digi-theme'),
            "id" => "number_post",
            "std" => 4,
            "type" => "text",
            'class' => 'hidden-tag nasa-list_post'
        );

        $of_options[] = array(
            "name" => esc_html__("Slide display", 'digi-theme'),
            "desc" => esc_html__("Number posts display in slide", 'digi-theme'),
            "id" => "number_post_slide",
            "std" => 1,
            "type" => "text",
            'class' => 'hidden-tag nasa-list_post'
        );

        $of_options[] = array(
            "name" => esc_html__("Display full width", 'digi-theme'),
            "desc" => esc_html__("Display full width", 'digi-theme'),
            "id" => "enable_fullwidth",
            "std" => 1,
            "type" => "checkbox"
        );

        $of_options[] = array(
            "name" => esc_html__("Text promotion color", 'digi-theme'),
            "desc" => esc_html__("Text promotion color", 'digi-theme'),
            "id" => "t_promotion_color",
            "std" => "#333",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Background", 'digi-theme'),
            "desc" => esc_html__("Background", 'digi-theme'),
            "id" => "background_area",
            "std" => DIGI_ADMIN_DIR_URI . 'assets/images/promo_bg.jpg',
            "type" => "media"
        );
    }
}
