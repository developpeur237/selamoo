<?php
/* Remove Tabs - Accordions elements */
add_action('vc_build_admin_page', 'nasa_vc_remove_elements_default', 11);
function nasa_vc_remove_elements_default() {
    // remove params tabs
    vc_remove_param('vc_tta_tabs', 'shape');
    vc_remove_param('vc_tta_tabs', 'style');
    vc_remove_param('vc_tta_tabs', 'color');
    vc_remove_param('vc_tta_tabs', 'autoplay');
    vc_remove_param('vc_tta_tabs', 'active_section');
    vc_remove_param('vc_tta_tabs', 'no_fill_content_area');
    vc_remove_param('vc_tta_tabs', 'spacing');
    vc_remove_param('vc_tta_tabs', 'gap');
    vc_remove_param('vc_tta_tabs', 'tab_position');
    vc_remove_param('vc_tta_tabs', 'pagination_style');
    vc_remove_param('vc_tta_tabs', 'pagination_color');
    vc_remove_param('vc_tta_tabs', 'el_id');

    // remove params accordions
    vc_remove_param('vc_tta_accordion', 'style');
    vc_remove_param('vc_tta_accordion', 'shape');
    vc_remove_param('vc_tta_accordion', 'color');
    vc_remove_param('vc_tta_accordion', 'c_align');
    vc_remove_param('vc_tta_accordion', 'no_fill');
    vc_remove_param('vc_tta_accordion', 'spacing');
    vc_remove_param('vc_tta_accordion', 'gap');
    vc_remove_param('vc_tta_accordion', 'autoplay');
    vc_remove_param('vc_tta_accordion', 'c_position');
    vc_remove_param('vc_tta_accordion', 'collapsible_all');
    vc_remove_param('vc_tta_accordion', 'c_icon');
    vc_remove_param('vc_tta_accordion', 'active_section');
    vc_remove_param('vc_tta_accordion', 'el_id');

    // remove params section
    //vc_remove_param('vc_tta_section', 'add_icon');
    //vc_remove_param('vc_tta_section', 'i_position');
    //vc_remove_param('vc_tta_section', 'i_type');
    //vc_remove_param('vc_tta_section', 'i_icon_fontawesome');
    //vc_remove_param('vc_tta_section', 'i_icon_openiconic');
    //vc_remove_param('vc_tta_section', 'i_icon_typicons');
    //vc_remove_param('vc_tta_section', 'i_icon_entypo');
    //vc_remove_param('vc_tta_section', 'i_icon_linecons');
    //vc_remove_param('vc_tta_section', 'i_icon_monosocial');
    
    global $vc_params_list;
    $vc_params_list[] = 'icon';

    vc_remove_element("vc_tabs");
    vc_remove_element("vc_accordion");
    vc_remove_element("vc_carousel");
    vc_remove_element("vc_images_carousel");
    vc_remove_element("vc_tour");
    vc_remove_element("vc_cta");
    vc_remove_element("vc_tta_tour");
    vc_remove_element("vc_tta_pageable");
    vc_remove_element("vc_cta_button");
    vc_remove_element("vc_cta_button2");
    vc_remove_element("vc_button");
    vc_remove_element("vc_button2");
    vc_remove_element("vc_wp_search");
    vc_remove_element("vc_wp_meta");
    vc_remove_element("vc_wp_recentcomments");
    vc_remove_element("vc_wp_calendar");
    vc_remove_element("vc_wp_posts");
    vc_remove_element("vc_wp_links");
    vc_remove_element("vc_wp_archives");
    vc_remove_element("vc_wp_rss");

    vc_remove_param("vc_row", "columns_placement");
    vc_remove_param("vc_row", "full_width");
    vc_remove_param("vc_row", "parallax_speed_bg");
    vc_remove_param("vc_row", "parallax_speed_video");
    vc_remove_param("vc_row", "full_height");
    vc_remove_param("vc_row", "gap");
    vc_remove_param("vc_row", "equal_height");
    vc_remove_param("vc_row", "content_placement");
    vc_remove_param("vc_row", "video_bg");
    vc_remove_param("vc_row", "video_bg_parallax");
    vc_remove_param("vc_row", "video_bg_url");
    vc_remove_param('vc_row', 'el_id');
    
    vc_remove_param("vc_column", "video_bg");
    vc_remove_param("vc_column", "video_bg_url");
    vc_remove_param("vc_column", "video_bg_parallax");
    vc_remove_param("vc_column", "parallax");
    vc_remove_param("vc_column", "parallax_image");
    vc_remove_param("vc_column", "parallax_speed_video");
    vc_remove_param("vc_column", "parallax_speed_bg");
    vc_remove_param('vc_column', 'el_id');
}

// Hook for admin editor
add_action('vc_build_admin_page', 'nasa_vc_remove_woocommerce', 11);