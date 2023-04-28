<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */
global $nasa_opt;

$typeView = !isset($nasa_opt['products_type_view']) ? 'grid' : $nasa_opt['products_type_view'];
$typeShow = $typeView == 'list' ? $typeView : 'grid';
$nasa_change_view = !isset($nasa_opt['enable_change_view']) || $nasa_opt['enable_change_view'] ? true : false;
if($nasa_change_view && isset($_COOKIE['gridcookie'])) {
    $typeShow = $_COOKIE['gridcookie'] == 'list' ? 'list' : 'grid';
}
?>

<div class="row">
    <div class="large-12 columns nasa-content-page-products">
        <div class="nasa-row-child-clear-none thumb products <?php echo esc_attr($typeShow);?>">