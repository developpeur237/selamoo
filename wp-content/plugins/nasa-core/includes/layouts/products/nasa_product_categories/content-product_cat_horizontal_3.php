<div class="product-category wow fadeInUp" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr($delay_animation_product); ?>ms">
    <a class="nasa-cat-link" href="<?php echo get_term_link($category->slug, 'product_cat'); ?>" title="<?php echo esc_attr($category->name); ?>">
        <div class="nasa-cat-thumb">
            <?php nasa_category_thumbnail($category, 'nasa-list-thumb'); ?>
        </div>
        <div class="header-title">
            <h3><?php echo $category->name; ?></h3>
            <?php echo apply_filters('woocommerce_subcategory_count_html', ' <span class="count">' . $category->count . ' ' . esc_html__('items', 'nasa-core') . '</span>', $category); ?>
        </div>
        <div class="inner-wrap hover-overlay"></div>
        <?php do_action('woocommerce_after_subcategory_title', $category); ?>
    </a>
</div>