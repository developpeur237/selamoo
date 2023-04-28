<?php
/**
 * Carousel slide for gift products
 */
$id_sc = rand(0, 9999999);
$_delay = 0;
$_delay_item = (isset($nasa_opt['delay_overlay']) && (int) $nasa_opt['delay_overlay']) ? (int) $nasa_opt['delay_overlay'] : 100;
$columns_title = isset($_REQUEST['title_columns']) && (int) $_REQUEST['title_columns'] <= 4 ? (int) $_REQUEST['title_columns'] : 2;
$coulums_slide = 12 - $columns_title;

?>
<div class="large-<?php echo esc_attr($columns_title); ?> columns">
    <div class="nasa-slide-left-info-wrap">
        <h4 class="nasa-combo-gift"><?php echo esc_html__('Promotion Gifts for', 'digi-theme'); ?></h4>
        <h3><?php echo $product->get_name(); ?><span class="nasa-count-items">(<?php echo count($combo) . ' ' . esc_html__('Items', 'digi-theme'); ?>)</span></h3>
        <div class="nasa-nav-carousel-wrap" data-id="#nasa-slider-<?php echo esc_attr($id_sc); ?>">
            <div class="nasa-nav-carousel-prev nasa-nav-carousel-div">
                <a class="nasa-nav-icon-slider" href="javascript:void(0);" data-do="prev">
                    <span class="pe-7s-angle-left"></span>
                </a>
            </div>
            <div class="nasa-nav-carousel-next nasa-nav-carousel-div">
                <a class="nasa-nav-icon-slider" href="javascript:void(0);" data-do="next">
                    <span class="pe-7s-angle-right"></span>
                </a>
            </div>
        </div>
        
        <?php if(!isset($nasa_viewmore) || $nasa_viewmore == true) : ?>
            <a class="nasa-view-more-slider" href="<?php echo esc_url(get_permalink($product->get_id())); ?>" title="<?php echo esc_html__('View more', 'digi-theme'); ?>"><?php echo esc_html__('View more', 'digi-theme'); ?></a>
        <?php endif; ?>
    </div>
</div>

<div class="large-<?php echo esc_attr($coulums_slide); ?> columns">
    <div class="row group-slider">
        <div id="nasa-slider-<?php echo esc_attr($id_sc); ?>" class="slider products-group nasa-combo-slider owl-carousel" data-margin="10px" data-columns="4" data-columns-small="1" data-columns-tablet="2" data-padding="65px" data-disable-nav="true">
            <?php
            foreach ($combo as $bundle_item) :
                $GLOBALS['product'] = $bundle_item->product;
                $post_object = get_post($bundle_item->product->get_id());
                setup_postdata($GLOBALS['post'] = & $post_object);
                wc_get_template('content-product.php', array(
                    '_delay' => $_delay,
                    'wrapper' => 'div',
                    'bundle' => true
                ));
                
                $_delay += $_delay_item;
            endforeach;
            ?>
        </div>
    </div>
</div>
