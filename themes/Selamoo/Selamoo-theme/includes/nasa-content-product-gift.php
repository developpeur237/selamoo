<?php
/**
 *
 * The template for displaying product gifts content within loops
 */
$product_gift = $bundle_item->product;

if (!$product_gift->is_visible() && (!isset($_REQUEST['nasa_load_ajax']) || !$_REQUEST['nasa_load_ajax'])) :
    return;
endif;

$post_gift = get_post($product_gift->get_id());
$productId = $product_gift->get_id();

$time_sale = (isset($is_deals) && $is_deals) ? get_post_meta($productId, '_sale_price_dates_to', true) : false;
$main_img = $product_gift->get_image('shop_catalog');

/**
 * $attachment_ids = !NASA_IS_PHONE ? $product_gift->get_gallery_image_ids() : false;
 */
$attachment_ids = $product_gift->get_gallery_image_ids();

$nasa_title = get_the_title($post_gift); // Title
$nasa_link = get_permalink($post_gift); // permalink

$product_category = function_exists('wc_get_product_category_list') ? wc_get_product_category_list($productId, ', ') : $product_gift->get_categories(', '); // Categories list

$class_wrap = 'wow fadeInUp product-item grid';
$class_wrap .= isset($nasa_opt['animated_products']) ? ' ' . $nasa_opt['animated_products'] : '';

$stock_status = $product_gift->get_stock_status();
$class_wrap .= $stock_status == "1" ? ' out-of-stock' : '';

echo '<div class="' . esc_attr($class_wrap) . '" data-wow-duration="1s" data-wow-delay="' . esc_attr($_delay) . 'ms" data-wow="fadeInUp">';
?>

<div class="inner-wrap<?php echo $time_sale ? ' product-deals' : ''; ?> nasa-title-bottom">
    <div class="product-outner">
        <div class="product-inner">
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

                <?php if ($stock_status == "1"): ?>
                    <div class="badge">
                        <div class="badge-inner out-of-stock-label">
                            <div class="inner-text"><?php esc_html_e('Sold out', 'digi-theme'); ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="info">
                <div class="name nasa-name">
                    <a href="<?php echo esc_url($nasa_link); ?>" title="<?php echo esc_attr($nasa_title); ?>">
                        <?php echo $nasa_title; ?>
                    </a>
                    <?php do_action('woocommerce_after_shop_loop_item_title'); ?>
                    <div class="nasa-product-bundle-btns">
                        <div class="quick-view tip-top" data-prod="<?php echo (int) $productId; ?>" data-tip="<?php esc_html_e('Quick View', 'digi-theme'); ?>" data-head_type="<?php echo esc_attr($head_type); ?>" title="<?php esc_html_e('Quick View', 'digi-theme'); ?>" data-product_type="<?php echo esc_attr($product_gift->get_type()); ?>">
                            <div class="btn-link">
                                <div class="quick-view-icon">
                                    <span class="pe-icon pe-7s-look"></span>
                                    <span class="hidden-tag nasa-icon-text"><?php esc_html_e('Quick View', 'digi-theme'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
echo '</div>';