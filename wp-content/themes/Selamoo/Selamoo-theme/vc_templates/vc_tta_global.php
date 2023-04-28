<?php

if (!defined('ABSPATH')) {
    die('-1');
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * @var $this WPBakeryShortCode_VC_Tta_Accordion|WPBakeryShortCode_VC_Tta_Tabs|WPBakeryShortCode_VC_Tta_Tour|WPBakeryShortCode_VC_Tta_Pageable
 */
$el_class = '';
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
$this->resetVariables($atts, $content);
extract($atts);

$this->setGlobalTtaInfo();
$prepareContent = $this->getTemplateVariable('content');
require DIGI_THEME_PATH . '/vc_templates/tta_global_layout/' . $this->layout . '_layout.php';
