<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$el_class = (trim($el_class) != '') ? ' '.$el_class : '';
$output = $this->getTemplateVariable('title');
$output .= '<div class="nasa-accordions-content' . $el_class . '">';
    $output .= $prepareContent;
$output .= '</div>';
echo $output;
