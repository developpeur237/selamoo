<?php
if (!function_exists('digi_blog_heading')) {
    add_action('init', 'digi_blog_heading');
    function digi_blog_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if(empty($of_options)) {
            $of_options = array();
        }
        
        $of_options[] = array(
            "name" => esc_html__("Blog", 'digi-theme'),
            "target" => 'blog',
            "type" => "heading"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Single Blog layout", 'digi-theme'),
            "desc" => esc_html__("Change Single blog layout", 'digi-theme'),
            "id" => "single_blog_layout",
            "std" => "left",
            "type" => "select",
            "options" => array(
                "left" => esc_html__("Left sidebar", 'digi-theme'),
                "right" => esc_html__("Right sidebar", 'digi-theme'),
                "no" => esc_html__("No sidebar (Centered)", 'digi-theme')
            )
        );

        $of_options[] = array(
            "name" => esc_html__("Blog layout", 'digi-theme'),
            "desc" => esc_html__("Change blog layout", 'digi-theme'),
            "id" => "blog_layout",
            "std" => "left",
            "type" => "select",
            "options" => array(
                "left" => esc_html__("Left sidebar", 'digi-theme'),
                "right" => esc_html__("Right sidebar", 'digi-theme'),
                "no" => esc_html__("No sidebar (Centered)", 'digi-theme')
            )
        );

        $of_options[] = array(
            "name" => esc_html__("Blog style", 'digi-theme'),
            "desc" => esc_html__("Change blog style", 'digi-theme'),
            "id" => "blog_type",
            "std" => "blog-standard",
            "type" => "select",
            "options" => array(
                "blog-standard" => esc_html__("Standard", 'digi-theme'),
                "blog-list" => esc_html__("List style", 'digi-theme')
            )
        );

        $of_options[] = array(
            "name" => esc_html__("Config for Standard Blog style", 'digi-theme'),
            "std" => "<h4>" . esc_html__("Config for Standard Blog style", 'digi-theme') . "</h4>",
            "type" => "info"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Parallax effect", 'digi-theme'),
            "id" => "blog_parallax",
            "desc" => esc_html__("Enable parallax effect on featured images", 'digi-theme'),
            "std" => 1,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Show author info", 'digi-theme'),
            "id" => "show_author_info",
            "desc" => esc_html__("Show author info", 'digi-theme'),
            "std" => 1,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Show datetime info", 'digi-theme'),
            "id" => "show_date_info",
            "desc" => esc_html__("Show datetime info", 'digi-theme'),
            "std" => 1,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Show Categories info", 'digi-theme'),
            "id" => "show_cat_info",
            "desc" => esc_html__("Show Categories info", 'digi-theme'),
            "std" => 1,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Show Tags info", 'digi-theme'),
            "id" => "show_tag_info",
            "desc" => esc_html__("Show Tags info", 'digi-theme'),
            "std" => 1,
            "type" => "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Show Readmore blog", 'digi-theme'),
            "id" => "show_readmore_blog",
            "desc" => esc_html__("Show Readmore blog", 'digi-theme'),
            "std" => 1,
            "type" => "checkbox"
        );
    }
}
