<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author 	WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.1
 */
if (!defined('ABSPATH')):
    exit;
endif;

global $nasa_opt, $nasa_cat_loop_delay;
$nasa_cat_loop_delay = !$nasa_cat_loop_delay ? 0 : $nasa_cat_loop_delay;
?>
<div class="product-category wow fadeInUp large-3 medium-4 small-6 columns margin-bottom-20" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr($nasa_cat_loop_delay); ?>ms">
    <?php do_action('woocommerce_before_subcategory', $category); ?>
    <div class="inner-wrap hover-overlay">
        <a href="<?php echo get_term_link($category->slug, 'product_cat'); ?>" class="nasa-filter-by-cat" data-id="<?php echo esc_attr($category->term_id); ?>" data-taxonomy="product_cat">
            <?php do_action('woocommerce_before_subcategory_title', $category); ?>
            <div class="header-title text-center">
                <?php
                if ($category->count > 0) :
                    echo '<h4>' . $category->name . '</h4>';
                    echo apply_filters('woocommerce_subcategory_count_html', ' <span class="count">' . $category->count . ' ' . esc_html__('items', 'digi-theme') . '</span>', $category);
                endif;
                ?>
            </div>
            <?php do_action('woocommerce_after_subcategory_title', $category); ?>
        </a>
    </div>
    <?php do_action('woocommerce_after_subcategory', $category); ?>
</div>

<?php
$_delay_item = (isset($nasa_opt['delay_overlay']) && (int) $nasa_opt['delay_overlay']) ? (int) $nasa_opt['delay_overlay'] : 100;
$GLOBALS['nasa_cat_loop_delay'] += $_delay_item;