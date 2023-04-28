<?php

if (!defined('ABSPATH')) {
    die('-1');
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * @var $this WPBakeryShortCode_VC_Tta_Section
 */
$class = '';
$style = ' style="display: none"';
if ((WPBakeryShortCode_VC_Tta_Section::$self_count == 0)) {
    $class = ' active first';
    $style = ' style="display: block"';
}
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
$class .= $atts['el_class'] != '' ? ' ' . $atts['el_class'] : '';

$this->resetVariables($atts, $content);
WPBakeryShortCode_VC_Tta_Section::$self_count ++;
WPBakeryShortCode_VC_Tta_Section::$section_info[] = $atts;
$isPageEditable = vc_is_page_editable();
$output = '';

$output .= '<div class="nasa-accordion-title">';
$output .= '<a class="nasa-accordion' . $class . '" data-id="' . esc_attr($this->getTemplateVariable('tab_id')) . '" href="javascript:void(0);">' . $this->getTemplateVariable('title') . '</a>';
$output .= '</div>';

$output .= '<div class="nasa-panel' . $class . '" id="nasa-secion-' . esc_attr($this->getTemplateVariable('tab_id')) . '"' . $style . '>';
$output .= $this->getTemplateVariable('content');
$output .= '</div>';

echo $output;
