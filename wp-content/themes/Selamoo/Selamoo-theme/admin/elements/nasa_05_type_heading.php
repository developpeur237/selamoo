<?php
if (!function_exists('digi_type_heading')) {
    add_action('init', 'digi_type_heading');
    function digi_type_heading() {
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        // Set the Options Array
        global $of_options;
        if(empty($of_options)) {
            $of_options = array();
        }
        
        $google_fonts = digi_get_fonts();
        
        $of_options[] = array(
            "name" => esc_html__("Fonts", 'digi-theme'),
            "target" => 'fonts',
            "type" => "heading"
        );

        $of_options[] = array(
            "name" => esc_html__("Heading fonts (H1, H2)", 'digi-theme'),
            "desc" => esc_html__("Select heading fonts.", 'digi-theme'),
            "id" => "type_headings",
            "std" => "Rubik",
            "type" => "select_google_font",
            "preview" => array(
                "text" => '<strong>NasaTheme</strong><br /><span style="font-size:60%!important">UPPERCASE TEXT</span>',
                "size" => "30px"
            ),
            "options" => $google_fonts
        );

        $of_options[] = array(
            "name" => esc_html__("Text fonts (paragraphs, buttons, sub-navigations)", 'digi-theme'),
            "desc" => esc_html__("Select heading fonts", 'digi-theme'),
            "id" => "type_texts",
            "std" => "Rubik",
            "type" => "select_google_font",
            "preview" => array(
                "text" => " Here is a sample text displayed by selamoo to preview the font type appearence, just like if it is used, it will appear in the public site.", //this is the text from preview box
                "size" => "14px"
            ),
            "options" => $google_fonts
        );

        $of_options[] = array(
            "name" => esc_html__("Main navigation", 'digi-theme'),
            "desc" => esc_html__("Select navigation fonts", 'digi-theme'),
            "id" => "type_nav",
            "std" => "Rubik",
            "type" => "select_google_font",
            "preview" => array(
                "text" => "<span style='font-size:45%'>THIS IS THE TEXT.</span>",
                "size" => "30px"
            ),
            "options" => $google_fonts
        );

        $of_options[] = array(
            "name" => esc_html__("Alterntative font (.alt-font)", 'digi-theme'),
            "desc" => esc_html__("Select alternative font", 'digi-theme'),
            "id" => "type_alt",
            "std" => "Rubik",
            "type" => "select_google_font",
            "preview" => array(
                "text" => "This is the text.",
                "size" => "30px"
            ),
            "options" => $google_fonts
        );

        $of_options[] = array(
            "name" => esc_html__("Banner font", 'digi-theme'),
            "desc" => esc_html__("Select banners font", 'digi-theme'),
            "id" => "type_banner",
            "std" => "Rubik",
            "type" => "select_google_font",
            "preview" => array(
                "text" => "This is the text.",
                "size" => "30px"
            ),
            "options" => $google_fonts
        );

        $of_options[] = array(
            "name" => esc_html__("Character Sub-sets", 'digi-theme'),
            "desc" => esc_html__("Choose the character sets you want.", 'digi-theme'),
            "id" => "type_subset",
            "std" => array("latin"),
            "type" => "multicheck",
            "options" => array(
                "latin" => "Latin",
                "cyrillic-ext" => esc_html__("Cyrillic Extended", 'digi-theme'),
                "greek-ext" => esc_html__("Greek Extended", 'digi-theme'),
                "greek" => esc_html__("Greek", 'digi-theme'),
                "vietnamese" => esc_html__("Vietnamese", 'digi-theme'),
                "latin-ext" => esc_html__("Latin Extended", 'digi-theme'),
                "cyrillic" => esc_html__("Cyrillic", 'digi-theme')
            )
        );
    }
}
