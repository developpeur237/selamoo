<?php

$output = $font_color = $el_class = $width = $offset = '';
extract(shortcode_atts(array(
    'font_color' => '',
    'el_class' => '',
    'width' => '1/1',
    'width_side' => '',
    'css' => '',
    'offset' => '',
    'nasa_effect' => 'none',
    'nasa_duration' => '1000',
    'nasa_delay' => '200',
    'footer_css' => ''
), $atts));
$footer_class = '';
if ($footer_css != '') {
    $footer_class = ' ' . vc_shortcode_custom_css_class($footer_css, ' ');
    $output .= '<style type="text/css">' . $footer_css . '</style>';
}
$el_atts = $el_effect = '';
if ($nasa_effect != 'none') {
    $el_atts = ' data-wow-duration="' . $nasa_duration . 'ms" data-wow-delay="' . $nasa_delay . 'ms"';
    $el_effect = ' wow ' . $nasa_effect;
}
$el_class = $this->getExtraClass($el_class);
$width = wpb_translateColumnWidthToSpan($width);
$width = vc_column_offset_class_merge($offset, $width);
$el_class .= ' wpb_column vc_column_container';
$style = $this->buildStyle($font_color);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $width . $el_class . vc_shortcode_custom_css_class($css, ' '), $this->settings['base'], $atts);

/**
 * Add class Full width to side
 */
$css_class .= $width_side != '' ? ' nasa-full-to-' . esc_attr($width_side) : $width_side;

$output .= "\n\t" . '<div class="' . $css_class . $footer_class . '"' . $style . '>';
$output .= "\n\t\t" . '<div class="wpb_wrapper' . $el_effect . '"' . $el_atts . '>';
$output .= "\n\t\t\t" . wpb_js_remove_wpautop($content);
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment('.wpb_wrapper');
$output .= "\n\t" . '</div> ' . $this->endBlockComment($el_class) . "\n";

echo $output;
