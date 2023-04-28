<?php
global $product;
$attachment_ids = $product->get_gallery_image_ids();
?>
<div class="row collapse">
    <div class="large-6 columns">
        <div class="product-img">
            <div class="owl-carousel main-image-slider">
                <?php if (has_post_thumbnail()) :
                    echo get_the_post_thumbnail($product->get_id(), apply_filters('single_product_large_thumbnail_size', 'shop_single'));
                else:
                    echo '<img src="' . wc_placeholder_img_src() . '" />';
                endif;
                if ($attachment_ids) :
                    $loop = 0;
                    $columns = apply_filters('woocommerce_product_thumbnails_columns', 3);
                    foreach ($attachment_ids as $attachment_id) :
                        $classes = array('zoom');
                        if ($loop == 0 || $loop % $columns == 0) :
                            $classes[] = 'first';
                        endif;

                        if (( $loop + 1 ) % $columns == 0) :
                            $classes[] = 'last';
                        endif;

                        $image_link = wp_get_attachment_url($attachment_id);

                        if (!$image_link) :
                            continue;
                        endif;

                        $image = wp_get_attachment_image($attachment_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'));
                        $image_class = esc_attr(implode(' ', $classes));
                        $image_title = esc_attr(get_the_title($attachment_id));

                        printf('%s', wp_get_attachment_image($attachment_id, apply_filters('single_product_large_thumbnail_size', 'shop_single')), wp_get_attachment_url($attachment_id));
                        $loop++;
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>
    
    <div class="large-6 columns">
        <div class="product-lightbox-inner product-info">
            <h1 itemprop="name" class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            <?php do_action('woocommerce_single_product_lightbox_summary'); ?>
        </div>
    </div>
    
    <?php do_action('woocommerce_single_product_lightbox_after'); ?>
</div>