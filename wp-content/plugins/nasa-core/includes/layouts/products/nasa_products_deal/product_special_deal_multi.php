<?php
$id_sc = rand(0, 999999);
$arrows = isset($arrows) ? $arrows : 0;
$auto_slide = isset($auto_slide) ? $auto_slide : 'true';
$main_pos = $position_nav == 'right' ? 'left' : 'right';
$thumb_pos = $position_nav == 'right' ? $position_nav : 'left';
$thumb_img = $position_nav == 'right' ? 'left' : 'right';

if (isset($title) && $title != ''): ?>
    <div class="row">
        <div class="large-12 columns">
            <div class="nasa-title nasa_type_2">
                <h3 class="nasa-title-heading">
                    <span><?php echo esc_attr($title); ?></span>
                </h3>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="nasa-main-special large-9 columns <?php echo esc_attr($main_pos); ?>">
        <?php if ($arrows == 1) : ?>
            <div class="nasa-nav-slick-wrap" data-id="#nasa-slider-slick-<?php echo esc_attr($id_sc); ?>">
                <div class="nasa-nav-slick-prev nasa-nav-slick-div">
                    <a class="nasa-nav-icon-slick" href="javascript:void(0);" data-do="prev">
                        <span class="pe-7s-angle-left"></span><?php echo esc_html__('Prev deal', 'nasa-core'); ?>
                    </a>
                </div>
                <div class="nasa-nav-slick-next nasa-nav-slick-div">
                    <a class="nasa-nav-icon-slick" href="javascript:void(0);" data-do="next">
                        <?php echo esc_html__('Next deal', 'nasa-core'); ?><span class="pe-7s-angle-right"></span>
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="row group-slider">
            <div class="large-12 columns">
                <div class="nasa-special-deal-style-multi-wrap">
                    <div
                        id="nasa-slider-slick-<?php echo esc_attr($id_sc); ?>"
                        class="slider products-group nasa-slider-deal-has-vertical products grid"
                        data-autoplay="<?php echo esc_attr($auto_slide); ?>"
                        data-speed="600"
                        data-delay="3000"
                        data-id="<?php echo esc_attr($id_sc); ?>">
                    <?php 
                    $vertical_product = array();
                    while ($specials->have_posts()) : $specials->the_post();
                        global $product;
                        $product_error = false;
                        $productId = $product->get_id();
                        $productType = $product->get_type();
                        $postId = $productType == 'variation' ? wp_get_post_parent_id($productId) : $productId;
                        $post = get_post($postId);
                        $vertical_product[] = array('product' => $product, 'post' => $post);
                        
                        /* Rating reviews */
                        $productRating = $productType == 'variation' ? wc_get_product($postId) : $product;
                        if(!$productRating) {
                            $product_error = true;
                            $average = $count = 0;
                        } else {
                            $average = $productRating->get_average_rating();
                            $count = $productRating->get_review_count();
                        }
                        
                        $rating_html = wc_get_rating_html($average, $count);

                        $stock_available = false;
                        if($statistic) :
                            $stock_sold = ($total_sales = get_post_meta($productId, 'total_sales', true)) ? round($total_sales) : 0;
                            $stock_available = ($stock = get_post_meta($productId, '_stock', true)) ? round($stock) : 0;
                            $percentage = $stock_available > 0 ? round($stock_sold/($stock_available + $stock_sold) * 100) : 0;
                        endif;

                        $time_sale = get_post_meta($productId, '_sale_price_dates_to', true);
                        $attachment_ids = $product->get_gallery_image_ids();
                        $product_link = $product_error ? '#' : get_the_permalink();
                        $product_name = get_the_title() . ($product_error ? esc_html__(' - Has been error. You need rebuilt this product.', 'nasa-core') : '');
                        ?>
                        <div class="nasa-special-deal-item nasa-special-deal-style-multi">
                            <div class="wow fadeInUp product-item<?php echo isset($nasa_opt['animated_products']) ? ' ' . esc_attr($nasa_opt['animated_products']) : ' hover-overlay'; ?>" data-wow-duration="1s" data-wow-delay="0ms">
                                <div class="inner-wrap product-special-deals">
                                    <div class="row">
                                        <div class="large-2 small-3 columns">
                                            <?php
                                            /*
                                             * Nasa Gift icon
                                             */
                                            do_action('nasa_gift_featured');
                                            ?>

                                            <div class="product-deal-special-countdown">
                                                <table>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?php echo nasa_time_sale($time_sale); ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="large-5 small-9 columns">
                                            <div class="product-img<?php echo (isset($nasa_opt['product-hover-overlay']) && $nasa_opt['product-hover-overlay']) ? ' hover-overlay' : ''; ?>">
                                                <a href="<?php echo esc_url($product_link); ?>" title="<?php echo esc_attr($product_name); ?>">
                                                    <div class="main-img">
                                                        <?php echo $product->get_image('shop_catalog'); ?>
                                                    </div>
                                                    <?php
                                                    if ($attachment_ids) :
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
                                                        <div class="back-img">
                                                            <?php echo $product->get_image('shop_catalog'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="large-5 columns">
                                            <div class="product-deal-special-wrap-info">
                                                <div class="product-deal-special-title">
                                                    <a href="<?php echo esc_url($product_link); ?>" title="<?php echo esc_attr($product_name); ?>">
                                                        <span><?php echo $product_name; ?></span>
                                                    </a>
                                                </div>
                                                
                                                <?php
                                                echo $rating_html ? $rating_html : '<div class="star-rating"></div>';
                                                ?>
                                                
                                                <div class="product-deal-special-price">
                                                    <span class="price"><?php echo $product->get_price_html(); ?></span>
                                                </div>

                                                <div class="nasa-product-deal-des">
                                                    <?php echo apply_filters('woocommerce_short_description', $post->post_excerpt); ?>
                                                </div>

                                                <?php if($stock_available) :?>
                                                    <div class="product-deal-special-progress">
                                                        <div class="deal-stock-label">
                                                            <span class="stock-available text-left"><?php echo esc_html__('Available:', 'nasa-core');?> <strong><?php echo esc_html($stock_available); ?></strong></span>
                                                            <span class="stock-sold text-right"><?php echo esc_html__('Already Sold:', 'nasa-core');?> <strong><?php echo esc_html($stock_sold); ?></strong></span>
                                                        </div>
                                                        <div class="deal-progress">
                                                            <span class="deal-progress-bar" style="<?php echo esc_attr('width:' . $percentage . '%'); ?>"><?php echo $percentage; ?></span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php
                                                /**
                                                 * Group buttons
                                                 */
                                                $buttons = '';
                                                $nasa_function = defined('NASA_THEME_PREFIX') && function_exists(NASA_THEME_PREFIX . '_product_group_button') ? NASA_THEME_PREFIX . '_product_group_button' : false;

                                                if($nasa_function) :
                                                    $buttons = $nasa_function('popup');
                                                ?>
                                                    <div class="product-deal-special-buttons">
                                                        <div class="nasa-product-grid">
                                                            <?php echo $buttons; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if (!$stock_available) : ?>
                                                    <div class="margin-bottom-40"> </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="large-3 columns hide-for-small <?php echo esc_attr($thumb_pos); ?>">
        <div class="nasa-slider-deal-vertical-extra-switcher nasa-slider-deal-vertical-extra-<?php echo esc_attr($id_sc); ?> wow fadeInUp<?php echo isset($nasa_opt['animated_products']) ? ' ' . esc_attr($nasa_opt['animated_products']) : ' hover-overlay'; ?>" data-wow-duration="1s" data-wow-delay="0ms" data-count="<?php echo count($vertical_product); ?>">
            <?php foreach ($vertical_product as $extra) :
                $product_thumb = $extra['product'];
                $post_thumb = $extra['post']
                ?>
                <div class="item-slick">
                    <div class="item-slick-outner">
                        <div class="item-slick-inner">
                            <div class="row">
                                <div class="large-7 columns nasa-slick-img <?php echo esc_attr($thumb_img); ?>">
                                    <?php echo $product_thumb->get_image('shop_catalog'); ?>
                                </div>
                                <div class="large-5 columns text-center">
                                    <?php
                                    // echo '<h3>' . $product_thumb->get_title() . '</h3>';
                                    $GLOBALS['product'] = $product_thumb;
                                    $GLOBALS['post'] = $post_thumb;
                                    
                                    if (function_exists('digi_add_custom_sale_flash')) :
                                        digi_add_custom_sale_flash();
                                    else :
                                        wc_get_template('loop/sale-flash.php');
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
