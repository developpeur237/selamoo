<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
global $product;

if (!$product->is_visible()):
    return;
endif;

$productId = $product->get_id();
$attachment_ids = $product->get_gallery_image_ids();
$products_cats = function_exists('wc_get_product_category_list') ?
    wc_get_product_category_list($productId, '|') : $product->get_categories('|');

$stock_status = get_post_meta($productId, '_stock_status', true) == 'outofstock';
?>

<li class="product-list grid4<?php echo $stock_status == "1" ? ' out-of-stock' : ''; ?>">
    <?php do_action('woocommerce_before_shop_loop_item'); ?>
    <div class="row">
        <div class="large-5 small-5 columns">
            <a href="<?php the_permalink(); ?>">
                <div class="product-img">
                    <div class="image-overlay"></div>
                    <div class="quick-view fa fa-search tip-top" data-prod="<?php echo (int) $productId; ?>" data-tip="<?php esc_html_e('Quick View', 'digi-theme'); ?>"></div>
                    <div class="main-img"><?php echo $product->get_image('shop_catalog'); ?></div>
                    <?php
                    if ($attachment_ids) :
                        $loop = 0;
                        foreach ($attachment_ids as $attachment_id) :
                            $image_link = wp_get_attachment_url($attachment_id);
                            if (!$image_link) :
                                continue;
                            endif;
                            $loop++;
                            if ($loop == 1) :
                                break;
                            endif;
                        endforeach;
                    endif;
                    
                    if ($stock_status == "1") : ?>
                        <div class="badge">
                            <div class="badge-inner out-of-stock-label">
                                <div class="inner-text"><?php esc_html_e('Sold out', 'digi-theme'); ?></div>      
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </a>
        </div>

        <div class="large-7 small-7 columns">
            <div class="info">
                <?php $product_cats = strip_tags(wc_get_product_category_list($productId, '|', '', '')); ?>

                <a href="<?php the_permalink(); ?>"><p class="name"><?php the_title(); ?></p></a>
                <?php do_action('woocommerce_after_shop_loop_item_title');
                if (NASA_WISHLIST_ENABLE) :
                    $link = array(
                        'url' => '',
                        'label' => '',
                        'class' => ''
                    );

                    $handler = apply_filters('woocommerce_add_to_cart_handler', $product->get_type(), $product);

                    switch ($handler) {
                        case "variable" :
                            $link['url'] = apply_filters('variable_add_to_cart_url', get_permalink($productId));
                            $link['label'] = apply_filters('variable_add_to_cart_text', esc_html__('Select options', 'digi-theme'));
                            break;
                        case "grouped" :
                            $link['url'] = apply_filters('grouped_add_to_cart_url', get_permalink($productId));
                            $link['label'] = apply_filters('grouped_add_to_cart_text', esc_html__('View options', 'digi-theme'));
                            break;
                        case "external" :
                            $link['url'] = apply_filters('external_add_to_cart_url', get_permalink($productId));
                            $link['label'] = apply_filters('external_add_to_cart_text', esc_html__('Read More', 'digi-theme'));
                            break;
                        default :
                            if ($product->is_purchasable()) {
                                $link['url'] = apply_filters('add_to_cart_url', esc_url($product->add_to_cart_url()));
                                $link['label'] = apply_filters('add_to_cart_text', esc_html__('Add to cart', 'digi-theme'));
                                $link['class'] = apply_filters('add_to_cart_class', 'add_to_cart_button');
                            } else {
                                $link['url'] = apply_filters('not_purchasable_url', get_permalink($productId));
                                $link['label'] = apply_filters('not_purchasable_text', esc_html__('Read More', 'digi-theme'));
                            }
                            break;
                    }
                    echo apply_filters('woocommerce_loop_add_to_cart_link', sprintf('<div data-href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s product_type_%s add-to-cart-grid-style2" data-tip="%s">
                        <div class="cart-icon">
                        <strong>ADD TO CART</strong>
                        <span class="cart-icon-handle"></span>

                </div></div>', esc_url($link['url']), esc_attr($productId), esc_attr($product->get_sku()), esc_attr($link['class']), esc_attr($product->get_type()), esc_html($link['label'])), $product, $link);
                    
                endif; ?>
            </div>
        </div>
    </div>
    <?php wc_get_template('loop/sale-flash.php'); ?>
</li>
