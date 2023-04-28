<?php

add_action('init', 'nasa_custom_option_themes', 11);
function nasa_custom_option_themes() {
    global $of_options;
    if(empty($of_options)) {
        $of_options = array();
    }
    
    $of_options[] = array(
        "name" => esc_html__("Nasa-core Options", 'nasa-core'),
        "target" => 'nasa-option',
        "type" => "heading"
    );
    
    $of_options[] = array(
        "name" => esc_html__('Enable UX Variations', 'nasa-core'),
        "desc" => esc_html__('Enable UX Variations.', 'nasa-core'),
        "id" => "enable_nasa_variations_ux",
        "std" => 1,
        "type" => "checkbox"
    );
    
    // limit_show num of 1 variation
    $of_options[] = array(
        "name" => esc_html__('Limit in product grid', 'nasa-core'),
        "desc" => esc_html__('Limit show variations/1 attribute in product grid. Input 0 to show all', 'nasa-core'),
        "id" => "limit_nasa_variations_ux",
        "std" => "5",
        "type" => "text"
    );
    
    // Loading ux variations in loop by ajax
    $of_options[] = array(
        "name" => esc_html__('UX Variations Loop by Ajax', 'nasa-core'),
        "desc" => esc_html__('Loading UX Variations In Loop by Ajax.', 'nasa-core'),
        "id" => "load_variations_ux_ajax",
        "std" => 1,
        "type" => "checkbox"
    );
    
    $of_options[] = array(
        "name" => esc_html__('Enable UX Variations With Type Select', 'nasa-core'),
        "desc" => esc_html__('Enable UX Variations With Type Select In Loop Product.', 'nasa-core'),
        "id" => "enable_nasa_ux_select",
        "std" => 1,
        "type" => "checkbox"
    );
    
    $of_options[] = array(
        "name" => esc_html__('Enable Cache files', 'nasa-core'),
        "desc" => esc_html__('Cache files.', 'nasa-core'),
        "id" => "enable_nasa_cache",
        "std" => 1,
        "type" => "checkbox"
    );
    
    $of_options[] = array(
        "name" => esc_html__('Expire Time (seconds)', 'nasa-core'),
        "desc" => '<a href="javascript:void(0);" class="button-primary nasa-clear-variations-cache" data-ok="' . esc_html__('Clear Cache Success !', 'nasa-core') . '" data-miss="' . esc_html__('Cache Empty!', 'nasa-core') . '" data-fail="' . esc_html__('Error!', 'nasa-core') . '">' . esc_html__('Clear Cache', 'nasa-core') . '</a><span class="nasa-admin-loader hidden-tag"><img src="' . NASA_CORE_PLUGIN_URL . 'admin/assets/ajax-loader.gif" /></span>',
        "id" => "nasa_cache_expire",
        "std" => '3600',
        "type" => "text"
    );
    
    /*
     * Share and follow
     */
    $of_options[] = array(
        "name" => esc_html__("Nasa Options Share & Follow", 'nasa-core'),
        "std" => "<h4>" . esc_html__("Nasa Options Share & Follow", 'nasa-core') . "</h4>",
        "type" => "info"
    );
    
    $of_options[] = array(
        "name" => esc_html__("Share Icons", 'nasa-core'),
        "desc" => esc_html__("Select icons to be shown on share icons on product page, blog and [share] shortcode", 'nasa-core'),
        "id" => "social_icons",
        "std" => array(
            "facebook",
            "twitter",
            "email",
            "pinterest"
        ),
        "type" => "multicheck",
        "options" => array(
            "facebook" => esc_html__("Facebook", 'nasa-core'),
            "twitter" => esc_html__("Twitter", 'nasa-core'),
            "email" => esc_html__("Email", 'nasa-core'),
            "pinterest" => esc_html__("Pinterest", 'nasa-core')
        )
    );
    
    $of_options[] = array(
        "name" => esc_html__("Facebook URL", 'nasa-core'),
        "desc" => esc_html__("Input Facebook link follow here.", 'nasa-core'),
        "id" => "facebook_url_follow",
        "std" => "",
        "type" => "text"
    );
    
    $of_options[] = array(
        "name" => esc_html__("Twitter URL", 'nasa-core'),
        "desc" => esc_html__("Input Twitter link follow here.", 'nasa-core'),
        "id" => "twitter_url_follow",
        "std" => "",
        "type" => "text"
    );
    
    $of_options[] = array(
        "name" => esc_html__("Email URL", 'nasa-core'),
        "desc" => esc_html__("Input Email follow here.", 'nasa-core'),
        "id" => "email_url_follow",
        "std" => "",
        "type" => "text"
    );
    
    $of_options[] = array(
        "name" => esc_html__("Pinterest URL", 'nasa-core'),
        "desc" => esc_html__("Input pinterest URL follow here.", 'nasa-core'),
        "id" => "pinterest_url_follow",
        "std" => "",
        "type" => "text"
    );
    
    $of_options[] = array(
        "name" => esc_html__("Instagram URL", 'nasa-core'),
        "desc" => esc_html__("Input instagram URL follow here.", 'nasa-core'),
        "id" => "instagram_url_follow",
        "std" => "",
        "type" => "text"
    );
    
    $of_options[] = array(
        "name" => esc_html__("RSS URL", 'nasa-core'),
        "desc" => esc_html__("Input RSS URL follow here.", 'nasa-core'),
        "id" => "rss_url_follow",
        "std" => "",
        "type" => "text"
    );
    
    $of_options[] = array(
        "name" => esc_html__("Linkedin URL", 'nasa-core'),
        "desc" => esc_html__("Input Linkedin URL follow here.", 'nasa-core'),
        "id" => "linkedin_url_follow",
        "std" => "",
        "type" => "text"
    );
    
    $of_options[] = array(
        "name" => esc_html__("Youtube URL", 'nasa-core'),
        "desc" => esc_html__("Input Youtube URL follow here.", 'nasa-core'),
        "id" => "youtube_url_follow",
        "std" => "",
        "type" => "text"
    );
    
    $of_options[] = array(
        "name" => esc_html__("Flickr URL", 'nasa-core'),
        "desc" => esc_html__("Input Flickr URL follow here.", 'nasa-core'),
        "id" => "flickr_url_follow",
        "std" => "",
        "type" => "text"
    );
    
    $of_options[] = array(
        "name" => esc_html__("Telegram URL Follow", 'nasa-core'),
        "desc" => esc_html__("Input Telegram link follow here.", 'nasa-core'),
        "id" => "telegram_url_follow",
        "std" => "",
        "type" => "text"
    );
    
    $of_options[] = array(
        "name" => esc_html__("Whatsapp URL Follow", 'nasa-core'),
        "desc" => esc_html__("Input Whatsapp link follow here.", 'nasa-core'),
        "id" => "whatsapp_url_follow",
        "std" => "",
        "type" => "text"
    );
}
