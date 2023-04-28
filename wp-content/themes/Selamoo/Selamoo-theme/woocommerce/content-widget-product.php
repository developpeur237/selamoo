<?php
/**
 *
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.5.5
 */
if (!defined('ABSPATH')) {
    exit;
}

global $product, $nasa_opt;

if (!is_a($product, 'WC_Product')) {
    return;
}

$productId = $product->get_id();
$link = $product->get_permalink();
$title = $product->get_name();
$average = $product->get_average_rating();
$rating_html = wc_get_rating_html($average);

$class = (!isset($is_animate) || $is_animate) ? ' wow fadeInUp ' . (isset($nasa_opt['animated_products']) ? $nasa_opt['animated_products'] : 'hover-fade') : '';
$list_type = isset($list_type) ? $list_type : '1';

$class_img = 'large-4 medium-6 small-4 columns images';
$class_info = 'large-8 medium-6 small-8 columns product-meta';
$nasa_quickview = $nasa_compare = $nasa_add_to_cart = false;
$nasa_wishlist = true;
switch ($list_type) :
    case '2':
        $nasa_add_to_cart = true;
        $class .= ' nasa-list-type-2';
        break;
    
    case 'list_main':
        $class .= ' nasa-list-type-main';
        $class_img = 'large-12 columns images';
        $class_info = 'large-12 columns images';
        break;
    
    case 'list_extra' :
        $class .= ' nasa-list-type-extra';
        break;
    
    case '1':
    default:
        $nasa_quickview = true;
        $class .= ' nasa-list-type-1';
        break;
endswitch;

if(!isset($delay)){
    global $delay;
    $_delay_item = (isset($nasa_opt['delay_overlay']) && (int) $nasa_opt['delay_overlay']) ? (int) $nasa_opt['delay_overlay'] : 100;
    $delay = !$delay ? 0 : $delay;
    $delay += $_delay_item;
}

$class_warp = isset($class_column) ? ' ' . $class_column : '';
$wapper = (isset($wapper) && $wapper == 'div') ? 'div' : 'li';
$start_wapper = ($wapper == 'div') ? '<div class="li_wapper' . $class_warp . '">' : '<li class="li_wapper' . $class_warp . '">';
$end_warp = '</' . $wapper . '>';

echo $start_wapper;
?>
<div class="row item-product-widget clearfix<?php echo esc_attr($class); ?>" data-wow-duration="1s" data-wow-delay="<?php echo (int)$delay; ?>ms">
    <div class="<?php echo esc_attr($class_img); ?>">
        <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>">
            <?php echo $product->get_image('shop_thumbnail'); ?>
            <div class="overlay"></div>
        </a>
    </div>
    <div class="<?php echo esc_attr($class_info); ?>">
        <div class="product-title separator">
            <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>">
                <?php echo esc_attr($title); ?>
            </a>
        </div>
        
        <?php echo $rating_html ? $rating_html : ''; ?>
        
        <div class="price"><?php echo $product->get_price_html(); ?></div>
        
        <?php /* Group btns */?>
        <?php echo $nasa_add_to_cart ? digi_add_to_cart_btn('small', true) : ''; ?>
        <?php /* End Group btns */?>
        
        <div class="product-interactions">
            <?php if ($nasa_quickview): ?>
                <div class="nasa-space"></div>
                <div class="quick-view" data-prod="<?php echo esc_attr($productId); ?>" title="<?php esc_html_e('Quick View', 'digi-theme'); ?>">
                    <div class="btn-link">
                        <div class="quick-view-icon">
                            <i class="pe-icon pe-7s-look"></i>
                            <span class="nasa-icon-text"><?php echo esc_html__('Quick view', 'digi-theme');?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if(NASA_WISHLIST_ENABLE && $nasa_wishlist) :?>
                <div class="<?php echo $list_type == 'list_extra' ? 'btn-wishlist btn-wishlist-main-list' : 'btn-wishlist'; ?>" data-prod="<?php echo esc_attr($productId); ?>">
                    <div class="btn-link">
                        <div class="wishlist-icon">
                            <i class="nasa-icon icon-nasa-like"></i>
                            <span class="nasa-icon-text not-added"><?php echo esc_html__('Wishlist', 'digi-theme');?></span>
                            <span class="nasa-icon-text hidden-tag has-added"><?php echo esc_html__('Added', 'digi-theme');?></span>
                        </div>
                    </div>
                </div>
                <div class="hidden-tag add-to-link"><?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?></div>
            <?php endif; ?>
        </div>
        
    </div>
</div>

<?php
echo $end_warp;
