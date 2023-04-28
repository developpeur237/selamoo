<?php

if (isset($nasa_opt['type_headings'])) {
    $customfont = '';
    $default_fonts = array(
        'arial',
        'verdana',
        'trebuchet',
        'georgia',
        'times',
        'tahoma',
        'helvetica'
    );

    $googlefonts = array(
        $nasa_opt['type_headings'],
        $nasa_opt['type_texts'],
        $nasa_opt['type_nav'],
        $nasa_opt['type_alt']
    );

    $customfontset = '';

    if (isset($nasa_opt['type_subset'])) {
        $subsets = array('latin');
        if (isset($nasa_opt['type_subset']['latin']) && $nasa_opt['type_subset']['latin']) {
            array_push($subsets, 'latin');
        }
        if (isset($nasa_opt['type_subset']['cyrillic-ext']) && $nasa_opt['type_subset']['cyrillic-ext']) {
            array_push($subsets, 'cyrillic-ext');
        }
        if (isset($nasa_opt['type_subset']['greek-ext']) && $nasa_opt['type_subset']['greek-ext']) {
            array_push($subsets, 'greek-ext');
        }
        if (isset($nasa_opt['type_subset']['greek']) && $nasa_opt['type_subset']['greek']) {
            array_push($subsets, 'greek');
        }
        if (isset($nasa_opt['type_subset']['vietnamese']) && $nasa_opt['type_subset']['vietnamese']) {
            array_push($subsets, 'vietnamese');
        }
        if (isset($nasa_opt['type_subset']['cyrillic']) && $nasa_opt['type_subset']['cyrillic']) {
            array_push($subsets, 'cyrillic');
        }
        foreach ($subsets as $fontset) {
            $customfontset = $fontset . ',' . $customfontset;
        }
        $customfontset = '&subset=' . substr_replace($customfontset, "", -1);
    }

    foreach ($googlefonts as $googlefont) {
        if (!in_array($googlefont, $default_fonts)) {
            $customfont = str_replace(' ', '+', $googlefont) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,900,900italic|' . $customfont;
        }
    }

    if ($customfont != "") {
        add_action('wp_enqueue_scripts', 'digi_google_fonts');
        function digi_google_fonts() {
            global $customfont, $customfontset;
            wp_enqueue_style('nasa-googlefonts', "//fonts.googleapis.com/css?family=" . substr_replace($customfont, "", -1) . $customfontset);
        }
    }
}