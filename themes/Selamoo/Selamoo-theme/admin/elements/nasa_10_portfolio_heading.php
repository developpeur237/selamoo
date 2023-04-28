<?php
if (!function_exists('digi_portfolio_heading')) {
    add_action('init', 'digi_portfolio_heading');
    function digi_portfolio_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if(empty($of_options)) {
            $of_options = array();
        }
        
        $of_options[] = array(
            "name" => esc_html__("Portfolio", 'digi-theme'),
            "target" => 'portfolio',
            "type" => "heading"
        );
        
        $of_options[] = array(
            "name" 		=> esc_html__("Enable Portfolio", 'digi-theme'),
            "desc" 		=> esc_html__("Enable Portfolio.", 'digi-theme'),
            "id" 		=> "enable_portfolio",
            "std" 		=> 0,
            "type" 		=> "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Recent Projects", 'digi-theme'),
            "desc" => esc_html__("Recent Projects", 'digi-theme'),
            "id" => "recent_projects",
            "std" => 1,
            "type" => "checkbox"
        );
        $of_options[] = array(
            "name" => esc_html__("Portfolio Comments", 'digi-theme'),
            "desc" => esc_html__("Portfolio Comments", 'digi-theme'),
            "id" => "portfolio_comments",
            "std" => 1,
            "type" => "checkbox"
        );
        $of_options[] = array(
            "name" => esc_html__("Portfolio Count", 'digi-theme'),
            "desc" => esc_html__("Portfolio Count", 'digi-theme'),
            "id" => "portfolio_count",
            "std" => 8,
            "type" => "text"
        );
        $of_options[] = array(
            "name" => esc_html__("Project Category", 'digi-theme'),
            "desc" => esc_html__("Display project category", 'digi-theme'),
            "id" => "project_byline",
            "std" => 1,
            "type" => "checkbox"
        );
        $of_options[] = array(
            "name" => esc_html__("Project Name", 'digi-theme'),
            "desc" => esc_html__("Project Name", 'digi-theme'),
            "id" => "project_name",
            "std" => 1,
            "type" => "checkbox"
        );

        $of_options[] = array(
            "name" => esc_html__("Portfolio Columns", 'digi-theme'),
            "desc" => esc_html__("Portfolio Columns", 'digi-theme'),
            "id" => "portfolio_columns",
            "std" => "3-cols",
            "type" => "select",
            "options" => array(
                "4-cols" => esc_html__("4 columns", 'digi-theme'),
                "3-cols" => esc_html__("3 columns", 'digi-theme'),
                "2-cols" => esc_html__("2 columns", 'digi-theme')
            )
        );

        $of_options[] = array(
            "name" => esc_html__("portfolio Lightbox", 'digi-theme'),
            "desc" => esc_html__("portfolio Lightbox", 'digi-theme'),
            "id" => "portfolio_lightbox",
            "std" => 1,
            "type" => "checkbox"
        );
    }
}
