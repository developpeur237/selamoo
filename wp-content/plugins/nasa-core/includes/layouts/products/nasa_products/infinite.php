<?php
$infinite_id = rand(1, 999999);
$is_deals = $type == 'deals' ? true : false;
$columns_number = (int) $columns_number < 2 || (int) $columns_number > 5 ? 5 : (int) $columns_number;
?>
<div class="row">
    <div class="large-12 columns">
        <div 
            class="products grid products-infinite products-group shortcode_<?php echo $infinite_id; ?>"
            data-next-page="2"
            data-product-type="<?php echo $type; ?>"
            data-post-per-page="<?php echo $number; ?>"
            data-post-per-row="<?php echo $columns_number; ?>"
            data-is-deals="<?php echo $is_deals; ?>"
            data-max-pages="<?php echo $loop->max_num_pages; ?>"
            data-cat="<?php echo esc_attr($cat); ?>">
            <?php include NASA_CORE_PRODUCT_LAYOUTS . 'globals/row_layout.php'; ?>
        </div>
    </div>
    
    <div class="large-12 columns text-center margin-top-70">
        <?php if ($loop->max_num_pages > 1) : ?>
            <div class="load-more-btn load-more" data-infinite="<?php echo $infinite_id; ?>" data-nodata="<?php esc_html_e('ALL PRODUCTS LOADED', 'nasa-core'); ?>">
                <div class="button load-more-content">
                    <span class="load-more-icon icon-nasa-refresh"></span>
                    <span class="load-more-text"><?php esc_html_e('View more', 'nasa-core'); ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>