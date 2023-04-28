<?php
// Custom image size
if(!has_image_size('nasa-list-thumb')) :
    add_image_size('nasa-list-thumb', 280, 280, true);
endif;

if(!has_image_size('nasa-category-thumb')) :
    add_image_size('nasa-category-thumb', 480, 900, true);
endif;

add_image_size('nasa-category-vertical', 280, 150, true);

add_image_size('nasa-medium', 300, 300, true);
add_image_size('nasa-large', 600, 600, true);

// Remove SRCSET imgs
add_action('init', 'nasa_remove_srcset_img');
function nasa_remove_srcset_img() {
    add_filter('wp_calculate_image_srcset', '__return_false');
}