<?php
$output = $el_class = $bg_image = $bg_color = $bg_image_repeat = $font_color = $padding = $margin_bottom = '';
extract(shortcode_atts(array(
    'el_class'        => '',
    'bg_image'        => '',
    'bg_color'        => '',
    'bg_image_repeat' => '',
    'font_color'      => '',
    'padding'         => '',
    'margin_bottom'   => '',
    'fullwidth'       => '0',
    'parallax'        => '',
    'parallax_speed'  => '0.6',
    'parallax_image'  => '',
    'css'             => '',
    'rowsm'           => false,
    'footer_css'      => '',
    'disable_element' => ''
), $atts));
$footer_class='';
if($footer_css!=''){
    $footer_class = ' ' . vc_shortcode_custom_css_class($footer_css, ' ');
    echo '<style type="text/css">' . $footer_css . '</style>';
}
$_is_fullwidth = ($fullwidth == '1') ? true : false;
$row_sm = ($rowsm) ? ' row-sm' : '';

//$parallax_image1 = '';
$parallax_image = wp_get_attachment_image_src($parallax_image, 'full');
$parallax_bg = $parallax_image[0];
$is_parallax = ($parallax != '') ? ' data-stellar-background-ratio="' . $parallax_speed . '" style="background-image: url(' . $parallax_bg . ');"' : '';
$parallax = ($parallax != '') ? ' parallax' : '';

$el_class = $this->getExtraClass($el_class) . $footer_class;
$el_class .= $disable_element == 'yes' ? ' hidden-tag' : '';
$style = $this->buildStyle($bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);

$output = '';
if($this->settings('base') === 'vc_row'){
    $output .='<div class="section-element' . $el_class . $parallax . vc_shortcode_custom_css_class($css, ' ') . '" ' . $style . $is_parallax . '>';
    $output .= ($_is_fullwidth) ? '<div class="nasa-row fullwidth clearfix">' : '<div class="row' . $row_sm . '">';
    $output .= wpb_js_remove_wpautop($content);
    $output .= ($_is_fullwidth) ? '</div>' : '</div>' . $this->endBlockComment('row');
    $output .= '</div>';
}else{
    $output.='<div class="section-element' . $el_class . $parallax . vc_shortcode_custom_css_class($css, ' ') . '" ' . $style . '>';
    $output .= '<div class="row' . $row_sm . '">';
    $output .= wpb_js_remove_wpautop($content);
    $output .= '</div>' . $this->endBlockComment('row');
    $output .= '</div>';
}

echo $output;