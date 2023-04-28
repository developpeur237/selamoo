<?php

/**
 * Include and setup custom metaboxes and fields.
 *
 * @category nasa-core
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */
add_filter('cmb_meta_boxes', 'nasa_metaboxes');

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function nasa_metaboxes(array $meta_boxes) {
    // Start with an underscore to hide fields from custom fields list
    $prefix = '_nasa_';
    
    /* Get Footer style */
    $footers_option = nasa_get_footers_options();

    $meta_boxes['nasa_metabox'] = array(
        'id' => 'nasa_metabox',
        'title' => esc_html__('Options Page', 'nasa-core'),
        'pages' => array('page'), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'name' => esc_html__('Header Type', 'nasa-core'),
                'desc' => esc_html__('Description (optional)', 'nasa-core'),
                'id' => $prefix . 'custom_header',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__('Default', 'nasa-core'),
                    '1' => esc_html__('Header Type 1', 'nasa-core'),
                    '2' => esc_html__('Header Type 2', 'nasa-core')
                ),
                'default' => '',
                'class' => 'nasa-select-header-type-page'
            ),
            
            array(
                'name' => esc_html__('Main menu Style', 'nasa-core'),
                'desc' => esc_html__('Style of Main menu', 'nasa-core'),
                'id' => $prefix . 'main_menu_style',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__('Default', 'nasa-core'),
                    '1' => esc_html__('No border', 'nasa-core'),
                    '2' => esc_html__('Has border', 'nasa-core')
                ),
                'default' => ''
            ),
            
            array(
                "name" => esc_html__("Title vertical menu", 'nasa-core'),
                "id" => $prefix . "title_ver_menu",
                "default" => '',
                "type" => "text"
            ),
            array(
                "name" => esc_html__("Vertical menu", 'nasa-core'),
                "id" => $prefix . "vertical_menu_selected",
                "default" => "",
                "type" => "select",
                "options" => nasa_meta_getListMenus()
            ),
            array(
                "name" => esc_html__("Level 2 allways show", 'nasa-core'),
                'desc' => esc_html__('Yes, please', 'nasa-core'),
                "id" => $prefix . "vertical_menu_allways_show",
                "default" => '0',
                "type" => "checkbox"
            ),
            
            array(
                "name" => esc_html__("Search Style", 'nasa-core'),
                "desc" => esc_html__("Select search style", 'nasa-core'),
                "id" => $prefix . "search_style",
                "type" => "select",
                "options" => array(
                    '' => esc_html__('Default', 'nasa-core'),
                    '1' => esc_html__('Style 1', 'nasa-core'),
                    '2' => esc_html__('Style 2', 'nasa-core'),
                    '3' => esc_html__('Style 3', 'nasa-core')
                ),
                "default" => ""
            ),
            array(
                'name' => esc_html__('Show Breadcrumb', 'nasa-core'),
                'desc' => esc_html__('Yes, please', 'nasa-core'),
                'id' => $prefix . 'show_breadcrumb',
                'default' => '0',
                'type' => 'checkbox',
                'class' => 'nasa-breadcrumb-flag'
            ),
            array(
                'name' => esc_html__('Breadcrumb Type', 'nasa-core'),
                'desc' => esc_html__('Type override breadcrumb', 'nasa-core'),
                'id' => $prefix . 'type_breadcrumb',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__('Default', 'nasa-core'),
                    '1' => esc_html__('Has breadcrumb background', 'nasa-core')
                ),
                'default' => '',
                'class' => 'hidden-tag nasa-breadcrumb-type'
            ),
            array(
                'name' => esc_html__('Override background for breadcrumb', 'nasa-core'),
                'desc' => esc_html__('Background for breadcrumb', 'nasa-core'),
                'id' => $prefix . 'bg_breadcrumb',
                'allow' => false,
                'type' => 'file',
                'class' => 'hidden-tag nasa-breadcrumb-bg'
            ),
            array(
                'name' => esc_html__('Breadcrumb background color', 'nasa-core'),
                'desc' => esc_html__('Breadcrumb background color', 'nasa-core'),
                'id' => $prefix . 'bg_color_breadcrumb',
                'type' => 'text',
                'default' => '',
                'class' => 'hidden-tag nasa-breadcrumb-bg-color'
            ),
            array(
                'name' => esc_html__('Height breadcrumb', 'nasa-core'),
                'desc' => esc_html__('Height (Pixel)', 'nasa-core'),
                'id' => $prefix . 'height_breadcrumb',
                'type' => 'text',
                'default' => '150',
                'class' => 'hidden-tag nasa-breadcrumb-height'
            ),
            array(
                'name' => esc_html__('Breadcrumb text color', 'nasa-core'),
                'desc' => esc_html__('Text color', 'nasa-core'),
                'id' => $prefix . 'color_breadcrumb',
                'type' => 'text',
                'default' => '#FFF',
                'class' => 'hidden-tag nasa-breadcrumb-color'
            ),
            array(
                'name' => esc_html__('Override Logo', 'nasa-core'),
                'desc' => esc_html__('Upload an image for override default logo.', 'nasa-core'),
                'id' => $prefix . 'custom_logo',
                'allow' => false,
                'type' => 'file',
            ),
            array(
                'name' => esc_html__('Override Retina Logo', 'nasa-core'),
                'desc' => esc_html__('Upload an image for override default retina logo.', 'nasa-core'),
                'id' => $prefix . 'custom_logo_retina',
                'allow' => false,
                'type' => 'file',
            ),
            array(
                'name' => esc_html__('Override Primary color.', 'nasa-core'),
                'desc' => esc_html__('Yes, please', 'nasa-core'),
                'id' => $prefix . 'pri_color_flag',
                'default' => '0',
                'type' => 'checkbox',
                'class' => 'nasa-override-pri-color-flag'
            ),
            array(
                'name' => esc_html__('Primary color', 'nasa-core'),
                'desc' => esc_html__('Primary color', 'nasa-core'),
                'id' => $prefix . 'pri_color',
                'type' => 'text',
                'default' => '#229fff',
                'class' => 'hidden-tag nasa-option-color nasa-override-pri-color'
            ),
            
            array(
                'name' => esc_html__('Header Background', 'nasa-core'),
                'desc' => esc_html__('Header Background', 'nasa-core'),
                'id' => $prefix . 'bg_color_header',
                'type' => 'text',
                'default' => '',
                'class' => 'nasa-option-color'
            ),
            
            array(
                'name' => esc_html__('Header Text color', 'nasa-core'),
                'desc' => esc_html__('Override Text color items in header', 'nasa-core'),
                'id' => $prefix . 'text_color_header',
                'type' => 'text',
                'default' => '',
                'class' => 'nasa-option-color'
            ),
            
            array(
                'name' => esc_html__('Header Text color hover', 'nasa-core'),
                'desc' => esc_html__('Override Text color hover items in header', 'nasa-core'),
                'id' => $prefix . 'text_color_hover_header',
                'type' => 'text',
                'default' => '',
                'class' => 'nasa-option-color'
            ),
            
            array(
                'name' => esc_html__('Top bar Background', 'nasa-core'),
                'desc' => esc_html__('Top bar Background', 'nasa-core'),
                'id' => $prefix . 'bg_color_topbar',
                'type' => 'text',
                'default' => '',
                'class' => 'nasa-option-color'
            ),
            
            array(
                'name' => esc_html__('Top bar Text color', 'nasa-core'),
                'desc' => esc_html__('Override Text color items in Top bar', 'nasa-core'),
                'id' => $prefix . 'text_color_topbar',
                'type' => 'text',
                'default' => '',
                'class' => 'nasa-option-color'
            ),
            
            array(
                'name' => esc_html__('Top bar Text color hover', 'nasa-core'),
                'desc' => esc_html__('Override Text color hover items in Top bar', 'nasa-core'),
                'id' => $prefix . 'text_color_hover_topbar',
                'type' => 'text',
                'default' => '',
                'class' => 'nasa-option-color'
            ),
            
            array(
                'name' => esc_html__('Main menu Background', 'nasa-core'),
                'desc' => esc_html__('Override background color for Main menu', 'nasa-core'),
                'id' => $prefix . 'bg_color_main_menu',
                'type' => 'text',
                'default' => '',
                'class' => 'nasa-option-color'
            ),
            
            array(
                'name' => esc_html__('Main menu Text color', 'nasa-core'),
                'desc' => esc_html__('Override text color for Main menu', 'nasa-core'),
                'id' => $prefix . 'text_color_main_menu',
                'type' => 'text',
                'default' => '',
                'class' => 'nasa-option-color'
            ),
            
            array(
                'name' => esc_html__('Main menu Text color hover', 'nasa-core'),
                'desc' => esc_html__('Override text color hover for Main menu', 'nasa-core'),
                'id' => $prefix . 'text_color_hover_main_menu',
                'type' => 'text',
                'default' => '',
                'class' => 'nasa-option-color'
            ),
            
            array(
                'name' => esc_html__('Footer Type', 'nasa-core'),
                'desc' => esc_html__('Description (optional)', 'nasa-core'),
                'id' => $prefix . 'custom_footer',
                'type' => 'select',
                'options' => $footers_option,
                'default' => ''
            )
        )
    );

    return $meta_boxes;
}

add_action('init', 'nasa_init_cmb_meta_boxes');

/**
 * Initialize the metabox class.
 */
function nasa_init_cmb_meta_boxes() {
    if (!class_exists('cmb_Meta_Box')){
        require_once NASA_CORE_PLUGIN_PATH . 'admin/metabox/init.php';
    }
}
