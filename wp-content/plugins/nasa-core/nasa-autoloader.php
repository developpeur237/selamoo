<?php
// Auto load includes files function
function nasa_includes_files($files = array()) {
    if(!empty($files)) {
        foreach ($files as $file) {
            include_once $file;
        }
    }
}

/**
 * Abstract files
 */
nasa_includes_files(glob(NASA_CORE_PLUGIN_PATH . 'abstracts/nasa_*.php'));

// Back-end
if (NASA_CORE_IN_ADMIN) {
    nasa_includes_files(glob(NASA_CORE_PLUGIN_PATH . 'admin/incls/nasa_*.php'));
}

// Includes shortcode and custom
nasa_includes_files(glob(NASA_CORE_PLUGIN_PATH . 'includes/incls/nasa_*.php'));
// Include custom post-type
nasa_includes_files(glob(NASA_CORE_PLUGIN_PATH . 'post_type/incls/nasa_*.php'));