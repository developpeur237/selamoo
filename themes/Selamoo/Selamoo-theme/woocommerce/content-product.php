<?php
/**
 *
 * The template for displaying product content within loops
 *
 * 
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.6.0
 */
global $product, $post, $nasa_opt;
if (empty($product) || !$product->is_visible()) :
    return;
endif;

$nasa_bundle = isset($bundle) && $bundle ? true : false;
$group_buttons = '';
if(!$nasa_bundle) :
    // $combo_show_type = isset($combo_show_type) && $combo_show_type == 'popup' ? 'popup' : 'rowdown';
    $combo_show_type = 'popup';
    $group_buttons = digi_product_group_button($combo_show_type);
endif;
$productId = $product->get_id();

/*
 * $time_sale = get_post_meta($productId, '_sale_price_dates_to', true);
 */
$time_sale = false;
if($product->is_on_sale()) {
    $timeNow = time();
    $time_from = get_post_meta($productId, '_sale_price_dates_from', true);
    $time_to = get_post_meta($productId, '_sale_price_dates_to', true);
    $time_sale = ((int) $time_to < $timeNow || (int) $time_from > $timeNow) ? false : (int) $time_to;
}

$main_img = $product->get_image('shop_catalog');

/**
 *  $attachment_ids = !NASA_IS_PHONE ? $product->get_gallery_image_ids() : false;
 */
$attachment_ids = $product->get_gallery_image_ids();

$_wrapper = (isset($wrapper) && $wrapper == 'li') ? 'li' : 'div';

$nasa_title = get_the_title($post); // Title
$nasa_link = get_permalink($post); // permalink

$product_category = function_exists('wc_get_product_category_list') ? wc_get_product_category_list($product->get_id(), ', ') : $product->get_categories(', '); // Categories list

$class_wrap = 'wow fadeInUp product-item archive-product-item grid';
$class_wrap .= isset($nasa_opt['animated_products']) ? ' ' . $nasa_opt['animated_products'] : '';

$stock_status = $product->get_stock_status();
$class_wrap .= $stock_status == "outofstock" ? ' out-of-stock' : '';
$stock_label = $stock_status == 'outofstock' ? esc_html__('Out of stock', 'digi-theme') : esc_html__('In stock', 'digi-theme');

echo '<' . $_wrapper . ' class="' . esc_attr($class_wrap) . '" data-wow-duration="1s" data-wow-delay="' . esc_attr($_delay) . 'ms" data-wow="fadeInUp">';

do_action('woocommerce_before_shop_loop_item'); ?>
<div class="inner-wrap<?php echo $time_sale ? ' product-deals' : ''; ?><?php echo (isset($title_top) && $title_top) ? ' nasa-title-top' : ' nasa-title-bottom'; ?>">
    <div class="product-outner">
        <div class="product-inner">
            <?php if (isset($title_top) && $title_top): ?>
                <div class="name">
                    <?php do_action('woocommerce_before_shop_loop_item_title'); ?>
                    <?php do_action('woocommerce_shop_loop_item_title'); ?>
                    <a href="<?php echo esc_url($nasa_link); ?>" title="<?php echo esc_attr($nasa_title); ?>">
                        <?php echo $nasa_title; ?>
                    </a>
                </div>
            <?php endif; ?>
            <div class="product-img<?php echo (isset($nasa_opt['product-hover-overlay']) && $nasa_opt['product-hover-overlay']) ? ' hover-overlay' : ''; ?>">
                
                <a href="<?php echo esc_url($nasa_link); ?>" title="<?php echo esc_attr($nasa_title); ?>">
                    <div class="main-img"><?php echo $main_img; ?></div>
                    <?php if ($attachment_ids) :
                        $loop = 0;
                        foreach ($attachment_ids as $attachment_id) :
                            $image_link = wp_get_attachment_url($attachment_id);
                            if (!$image_link):
                                continue;
                            endif;
                            $loop++;
                            printf('<div class="back-img back">%s</div>', wp_get_attachment_image($attachment_id, 'shop_catalog'));
                            if ($loop == 1):
                                break;
                            endif;
                        endforeach;
                    else : ?>
                        <div class="back-img"><?php echo $main_img; ?></div>
                    <?php endif; ?>
                </a>

                <?php if ($stock_status == "outofstock"): ?>
                    <div class="badge">
                        <div class="badge-inner out-of-stock-label">
                            <div class="inner-text"><?php esc_html_e('Sold out', 'digi-theme'); ?></div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php 
                wc_get_template('loop/sale-flash.php');
                /*
                 * Nasa Gift icon
                 */
                do_action('nasa_gift_featured');
                ?>
            </div>

            <?php if(!$nasa_bundle) : ?>
                <div class="nasa-product-list hidden-tag">
                    <?php do_action('nasa_price_list_loop'); ?>
                    <p class="nasa-list-stock-status hidden-tag <?php echo esc_attr($stock_status); ?>">
                        <?php echo esc_html__('AVAILABILITY: ', 'digi-theme') . '<span>' . $stock_label . '</span>'; ?>
                    </p>
                    <!-- Product interactions button button for list -->
                    <div class="nasa-group-btn-in-list">
                        <?php echo $group_buttons; ?>
                    </div>
                    <!-- End Product interactions button-->
                </div>
            <?php endif; ?>

            <div class="info">
                <?php if(!$nasa_bundle) : ?>
                    <div class="nasa-list-category hidden-tag">
                        <?php echo $product_category; ?>
                    </div>
                    <div class="name hidden-tag nasa-name">
                        <?php do_action('woocommerce_before_shop_loop_item_title'); ?>
                        <?php do_action('woocommerce_shop_loop_item_title'); ?>
                        <a href="<?php echo esc_url($nasa_link); ?>" title="<?php echo esc_attr($nasa_title); ?>">
                            <?php echo $nasa_title; ?>
                        </a>
                    </div>
                    <div class="info_main">
                        <?php do_action('woocommerce_after_shop_loop_item_title'); ?>
                        <hr class="nasa-list-hr hidden-tag">
                        <div class="product-des">
                            <?php echo apply_filters('woocommerce_short_description', $post->post_excerpt); ?>
                        </div>
                    </div>
                    <?php if (!isset($title_top) || !$title_top): ?>
                        <div class="name">
                            <?php do_action('woocommerce_before_shop_loop_item_title'); ?>
                            <?php do_action('woocommerce_shop_loop_item_title'); ?>
                            <a href="<?php echo esc_url($nasa_link); ?>" title="<?php echo esc_attr($nasa_title); ?>">
                                <?php echo $nasa_title; ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="nasa-product-grid">
                        <!-- Product interactions button for grid -->
                        <?php echo $group_buttons; ?>
                        <!-- End Product interactions button-->
                    </div>
                <?php
                /*
                 *  Bundle product
                 */
                else: ?>
                    <div class="name nasa-name">
                        <?php do_action('woocommerce_before_shop_loop_item_title'); ?>
                        <?php do_action('woocommerce_shop_loop_item_title'); ?>
                        <a href="<?php echo esc_url($nasa_link); ?>" title="<?php echo esc_attr($nasa_title); ?>">
                            <?php echo $nasa_title; ?>
                        </a>
                        <?php do_action('woocommerce_after_shop_loop_item_title'); ?>
                        <div class="nasa-product-bundle-btns">
                            <!-- Product interactions button for grid -->
                            <?php echo digi_quickview_in_list(); ?>
                            <!-- End Product interactions button-->
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($time_sale): ?>
                <div class="nasa-sc-pdeal-countdown">
                    <?php echo digi_time_sale($time_sale); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
do_action('woocommerce_after_shop_loop_item');
echo '</' . $_wrapper . '>';
