<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$alignment = $alignment ? ' text-' . $alignment : '';
$el_class = (trim($el_class) != '') ? ' ' . $el_class : '';
$border_header = (isset($border_head) && $border_head == 1) ? ' nasa-has-border-bottom' : '';
$output = $this->getTemplateVariable('title');
$tabs_slide = (isset($tabs_display_type) && $tabs_display_type == 1) ? true : false;

if (WPBakeryShortCode_VC_Tta_Section::$section_info):
    $el_class .= $alignment;
    $el_class .= $tabs_slide ? ' nasa-slide-style' : ' nasa-classic-style';
    $output .= '<div class="nasa-tabs-content' . esc_attr($el_class) . '">';
    $output .= '<ul class="nasa-tabs' . $border_header . '">';
    $output .= $tabs_slide ? '<li class="nasa-slide-tab"></li>' : '';
    
    foreach (WPBakeryShortCode_VC_Tta_Section::$section_info as $k => $v):
        $title = esc_html($v['title']);
        $icon = '';
        $hr = (!$tabs_slide && isset($v['hr']) && $v['hr']) ? '<span class="nasa-hr lager"></span>' : '';
        if ($v['add_icon'] == 'true') {
            $icon = 'nasa-tab-icon ' . $v['i_icon_' . $v['i_type']];
            switch ($v['i_position']) {
                case 'right':
                    $title = $title . '<i class="' . $icon . '"></i>';
                    break;
                case 'left':
                default :
                    $title = '<i class="' . $icon . '"></i>' . $title;
                    break;
            }
        }
        
        $nasa_attr = ($k == 0) ? ' class="nasa-tab active first" data-show="1"' : ' class="nasa-tab" data-show="0"';
        $output .= '<li' . $nasa_attr . '>';
        $output .= '<a href="javascript:void(0);" data-id="#nasa-secion-' . esc_attr($v['tab_id']) . '"><h5>' . $title . '</h5>' . $hr . '</a></li>';
        $output .= !$tabs_slide ? '<li class="separator"> </li>' : ''; // separator
    endforeach;
    
    $output .= '</ul>';
    $output .= '<div class="nasa-panels">';
    $output .= $prepareContent; // Content
    $output .= '</div>';
    $output .= '</div>';
endif;

echo $output;
