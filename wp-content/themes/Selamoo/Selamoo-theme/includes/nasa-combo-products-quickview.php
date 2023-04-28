<?php
// Exit if accessed directly
if (!defined('ABSPATH')) :
    exit;
endif;

if (!$product->is_purchasable()) :
    return;
endif;

$bundled_items = $product->get_bundled_items();
if ($bundled_items) : ?>
    <hr class="nasa-single-hr">
    <h3 class="nasa-gift-label"><?php echo esc_html__('Prix promotionnel pour ce produit ', 'digi-theme'); ?><span class="nasa-gift-count">(<?php echo count($bundled_items) . ' ' . esc_html__('items', 'digi-theme'); ?>)</span></h3>
    <div id="nasa-slider-gifts-product-quickview" class="nasa-slider owl-carousel products-group" data-columns="3" data-columns-small="2" data-columns-tablet="2" data-margin="10px" data-disable-nav="true">
        <?php foreach ($bundled_items as $bundled_item) :
            $bundled_product = $bundled_item->get_product();
            $bundled_post = get_post(yit_get_base_product_id($bundled_product));
            $quantity = $bundled_item->get_quantity();
            ?>
            <div class="nasa-gift-product-quickview-item">
                <a href="<?php echo esc_url($bundled_product->get_permalink()); ?>" title="<?php echo esc_attr($bundled_product->get_title()); ?>">
                    <div class="nasa-bundled-item-image"><?php echo $bundled_product->get_image(); ?></div>
                    <h5><?php echo $quantity . ' x ' . $bundled_product->get_title(); ?></h5>
                </a>
                
                <?php
                if ($bundled_product->has_enough_stock($quantity) && $bundled_product->is_in_stock()) :
                    echo '<div class="nasa-label-stock nasa-item-instock">' . esc_html__('En stock', 'digi-theme') . '</div>';
                else :
                    echo '<div class="nasa-label-stock nasa-item-outofstock">' . esc_html__('Stock épuisé, 'digi-theme') . '</div>';
                endif;
                ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif;