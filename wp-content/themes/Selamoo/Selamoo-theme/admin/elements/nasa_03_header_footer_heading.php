<?php
if (!function_exists('digi_header_footer_heading')) {
    add_action('init', 'digi_header_footer_heading');
    function digi_header_footer_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if(empty($of_options)) {
            $of_options = array();
        }
        
        $of_options[] = array(
            "name" => esc_html__("Header and Footer", 'digi-theme'),
            "target" => 'header-footer',
            "type" => "heading"
        );

        $of_options[] = array(
            "name" => esc_html__("Header Option", 'digi-theme'),
            "std" => "<h4>" . esc_html__("Header Option", 'digi-theme') . "</h4>",
            "type" => "info"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Header type", 'digi-theme'),
            "desc" => esc_html__("Select header type", 'digi-theme'),
            "id" => "header-type",
            "std" => "1",
            "type" => "images",
            "options" => array(
                '1' => DIGI_ADMIN_DIR_URI . 'assets/images/header-1.gif',
                '2' => DIGI_ADMIN_DIR_URI . 'assets/images/header-2.gif'
            )
        );

        $of_options[] = array(
            "name" => esc_html__("Title vertical menu", 'digi-theme'),
            "id" => "title_ver_menu",
            "std" => 'BROWSER',
            "type" => "text"
        );

        $menus = wp_get_nav_menus(array('orderby' => 'name'));
        $option_menu = array('' => esc_html__('Select menu', 'digi-theme'));
        if (!empty($menus)) {
            foreach ($menus as $menu_option) {
                $option_menu[$menu_option->term_id] = $menu_option->name;
            }
        }
        
        $of_options[] = array(
            "name" => esc_html__("Select vertical menu", 'digi-theme'),
            "id" => "vertical_menu_selected",
            "std" => "",
            "type" => "select",
            'override_numberic' => true,
            "options" => $option_menu
        );

        $block_type = get_posts(array('posts_per_page' => -1, 'post_type' => 'nasa_block'));
        $header_blocks = array('default' => esc_html__('Select the Static Block', 'digi-theme'));
        if (!empty($block_type)) {
            foreach ($block_type as $key => $value) {
                $header_blocks[$value->ID] = $value->post_title;
            }
        }
        $of_options[] = array(
            "name" => esc_html__("Block Header", 'digi-theme'),
            "id" => "header-block",
            "type" => "select",
            'override_numberic' => true,
            "options" => $header_blocks
        );

        $of_options[] = array(
            "name" => esc_html__("Sticky", 'digi-theme'),
            "id" => "fixed_nav",
            "desc" => esc_html__("Enable sticky", 'digi-theme'),
            "std" => 1,
            "type" => "checkbox"
        );

        /* $of_options[] = array(
            "name" => esc_html__("Show Top Bar", 'digi-theme'),
            "desc" => esc_html__("Show Top Bar", 'digi-theme'),
            "id" => "topbar_show",
            "std" => 1,
            "type" => "checkbox"
        );*/

        $of_options[] = array(
            "name" 		=> esc_html__("Enable Switch Languages", 'digi-theme'),
            "desc" 		=> esc_html__("Enable Switch Languages", 'digi-theme'),
            "id" 		=> "switch_lang",
            "std" 		=> 0,
            "type" 		=> "checkbox"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Show Topbar left", 'digi-theme'),
            "desc" => esc_html__("Show Topbar left", 'digi-theme'),
            "id" => "topbar_left_show",
            "std" => 1,
            "type" => "checkbox"
        );

        $of_options[] = array(
            "name" => esc_html__("Top bar left content", 'digi-theme'),
            "desc" => '<a href="javascript:void(0);" class="reset_topbar_left"><b>Default value</b></a> for left top bar.<br /><a href="javascript:void(0);" class="restore_topbar_left"><b>Restore text</b></a> for top bar left.<br />',
            "id" => "topbar_left",
            "std" => '',
            "type" => "textarea"
        );

        $of_options[] = array(
            "name" => esc_html__("Header Elements", 'digi-theme'),
            "std" => "<h4>" . esc_html__("Header Elements", 'digi-theme') . "</h4>",
            "type" => "info"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Background color Header", 'digi-theme'),
            "desc" => esc_html__("Background Color header.", 'digi-theme'),
            "id" => "bg_color_header",
            "std" => "",
            "type" => "color"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Text color Header", 'digi-theme'),
            "desc" => esc_html__("Text Color header.", 'digi-theme'),
            "id" => "text_color_header",
            "std" => "",
            "type" => "color"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Text color hover for items Header", 'digi-theme'),
            "desc" => esc_html__("Text color hover for items header.", 'digi-theme'),
            "id" => "text_color_hover_header",
            "std" => "",
            "type" => "color"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Topbar Background", 'digi-theme'),
            "desc" => esc_html__("Topbar Background.", 'digi-theme'),
            "id" => "bg_color_topbar",
            "std" => "",
            "type" => "color"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Topbar Text color", 'digi-theme'),
            "desc" => esc_html__("Topbar Text color.", 'digi-theme'),
            "id" => "text_color_topbar",
            "std" => "",
            "type" => "color"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Topbar Text color hover", 'digi-theme'),
            "desc" => esc_html__("Text color hover for items Topbar.", 'digi-theme'),
            "id" => "text_color_hover_topbar",
            "std" => "",
            "type" => "color"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Search Style", 'digi-theme'),
            "desc" => esc_html__("Select search style", 'digi-theme'),
            "id" => "search-style",
            "std" => "1",
            "type" => "images",
            "options" => array(
                '1' => DIGI_ADMIN_DIR_URI . 'assets/images/search-style-1.jpg',
                '2' => DIGI_ADMIN_DIR_URI . 'assets/images/search-style-2.jpg',
                '3' => DIGI_ADMIN_DIR_URI . 'assets/images/search-style-3.jpg'
            )
        );
        
        $of_options[] = array(
            "name" => esc_html__("Main menu Style", 'digi-theme'),
            "desc" => esc_html__("Select Main menu Style", 'digi-theme'),
            "id" => "main_menu_style",
            "std" => "1",
            "type" => "images",
            "options" => array(
                '1' => DIGI_ADMIN_DIR_URI . 'assets/images/menu-style-1.jpg',
                '2' => DIGI_ADMIN_DIR_URI . 'assets/images/menu-style-2.jpg'
            )
        );
        
        $of_options[] = array(
            "name" => esc_html__("Main menu Background", 'digi-theme'),
            "desc" => esc_html__("Change Background Color of Main menu.", 'digi-theme'),
            "id" => "bg_color_main_menu",
            "std" => "",
            "type" => "color"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Main menu Text Color", 'digi-theme'),
            "desc" => esc_html__("Change Text Color of Main menu.", 'digi-theme'),
            "id" => "text_color_main_menu",
            "std" => "",
            "type" => "color"
        );
        
        $of_options[] = array(
            "name" => esc_html__("Main menu Text Color hover", 'digi-theme'),
            "desc" => esc_html__("Change Text Color hover of Main menu.", 'digi-theme'),
            "id" => "text_color_hover_main_menu",
            "std" => "",
            "type" => "color"
        );

        $of_options[] = array(
            "name" => esc_html__("Footer Option", 'digi-theme'),
            "std" => "<h4>" . esc_html__("Footer Option", 'digi-theme') . "</h4>",
            "type" => "info"
        );

        $footers_type = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => 'footer'
        ));
        $footers_option = array();
        $footers_option['default'] = esc_html__('Select the Footer type', 'digi-theme');
        if (!empty($footers_type)) {
            foreach ($footers_type as $key => $value) {
                $footers_option[$value->ID] = $value->post_title;
            }
        }
        $of_options[] = array(
            "name" => esc_html__("Footer type", 'digi-theme'),
            "desc" => esc_html__("Select footer type", 'digi-theme'),
            "id" => "footer-type",
            "type" => "select",
            'override_numberic' => true,
            "options" => $footers_option
        );
    }
}
