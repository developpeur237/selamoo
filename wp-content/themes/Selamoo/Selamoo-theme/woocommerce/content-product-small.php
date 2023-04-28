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
?>         

<li class="product small">
    <a href="<?php the_permalink(); ?>" style="display:block" class="tip-top" data-tip="<?php the_title(); ?> / <?php echo strip_tags(wc_price($product->get_price())); ?>">
        <div class="product-img">
            <?php echo get_the_post_thumbnail($product->get_id(), apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail')); ?>
        </div>
    </a>
    <?php wc_get_template('loop/sale-flash.php'); ?>
</li>

