<?php
/**
 * Single Product Thumbnails
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.1
 */
if (!defined('ABSPATH')) :
    exit; // Exit if accessed directly
endif;

global $product;

$productId = $product->get_id();
$postThumbId = get_post_thumbnail_id();
$attachment_ids = $product->get_gallery_image_ids();
$has_video = true;
$has_thumbnail = has_post_thumbnail();

if (($has_thumbnail && ($has_video || $attachment_ids)) || ($has_video && $attachment_ids)) : ?>
    <div id="product-pager" class="product-thumbnails images-popups-gallery owl-carousel">
        <?php
        $loop = 0;
        $columns = apply_filters('woocommerce_product_thumbnails_columns', 3);

        $data_rel = '';
        $image_title = esc_attr(get_the_title($postThumbId));
        $image_link = wp_get_attachment_url($postThumbId);
        $image = get_the_post_thumbnail($productId, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'), array('title' => $image_title));
        
        $image_thumb = wp_get_attachment_image_src($postThumbId, 'thumbnail');
        $thumb_src = isset($image_thumb['0']) ? $image_thumb['0'] : wc_placeholder_img_src();

        if ($has_thumbnail) :
            echo sprintf('<a href="%s" title="%s" class="active-thumbnail" data-thumb_org="%s" %s>%s</a>', $image_link, $image_title, esc_attr($thumb_src), $data_rel, $image);
        else :
            $noimage = wc_placeholder_img_src();
            echo sprintf('<a href="%s" class="active-thumbnail" data-thumb_org="%s"><img src="%s" /></a>', $noimage, esc_attr($noimage), $noimage);
        endif;

        if(!empty($attachment_ids)) :
            foreach ($attachment_ids as $attachment_id) :
                $classes = array('zoom');

                if ($loop == 0 || $loop % $columns == 0) :
                    $classes[] = 'first';
                endif;

                if (( $loop + 1 ) % $columns == 0) :
                    $classes[] = 'last';
                endif;

                if (!$image_link = wp_get_attachment_url($attachment_id)) :
                    continue;
                endif;

                $image = wp_get_attachment_image($attachment_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'));
                $image_class = esc_attr(implode(' ', $classes));
                $image_title = esc_attr(get_the_title($attachment_id));

                echo apply_filters('woocommerce_single_product_image_thumbnail_html', sprintf('%s', $image), $attachment_id, $productId, $image_class);

                $loop++;
            endforeach;
        endif;
        ?>
    </div>
    <?php
endif;