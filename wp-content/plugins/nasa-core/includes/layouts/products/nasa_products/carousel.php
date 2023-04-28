<?php
$id_sc = rand(0, 9999999);

$_delay = 0;
$_delay_item = (isset($nasa_opt['delay_overlay']) && (int) $nasa_opt['delay_overlay']) ? (int) $nasa_opt['delay_overlay'] : 100;
$data_margin = isset($data_margin) ? (int) $data_margin : 10;
$height_auto = !isset($height_auto) ? 'true' : $height_auto;
$auto_slide = isset($auto_slide) ? $auto_slide : 'false';
$style_row = (!isset($style_row) || $style_row == 'simple') ? 'simple' : 'double';
$is_deals = $type == 'deals' ? true : false;
$shop_url = isset($shop_url) ? $shop_url : false;
$arrows = isset($arrows) ? $arrows : 0;
$dots = isset($dots) ? $dots : 'false';

$term = (int) $cat ? get_term_by('id', (int) $cat, 'product_cat') : null;
$link_shortcode = null;
$parent_term = null;
$parent_term_link = '#';
if($shop_url == 1) {
    if($term) {
        $parent_term = $term->parent ? get_term_by("id", $term->parent, "product_cat") : $parent_term;
        $parent_term_link = $parent_term ? get_term_link($parent_term, 'product_cat') : $parent_term_link;
        $link_shortcode = get_term_link($term, 'product_cat');
    } else {
        $permalinks = get_option('woocommerce_permalinks');
        $shop_page_id = wc_get_page_id('shop');
        $shop_page = get_post($shop_page_id);

        $shop_page_url = get_permalink($shop_page_id);
        $shop_page_title = get_the_title($shop_page_id);
        // If permalinks contain the shop page in the URI prepend the breadcrumb with shop
        if ($shop_page_id > 0 && strstr($permalinks['product_base'], '/' . $shop_page->post_name) && get_option('page_on_front') !== $shop_page_id) {
            $link_shortcode = get_permalink($shop_page);
        }
    }
}

if($style_row == 'simple') :
    $pos_nav = (!isset($pos_nav) || $pos_nav != 'top') ? 'left' : 'top';
    
    $catName = isset($term->name) ? ' ' . $term->name : '';
    if(!isset($title_shortcode) || trim($title_shortcode) == '') {
        switch ($type):
            case 'best_selling':
                $title_shortcode = esc_html__('Best Selling', 'nasa-core');
                break;
            case 'featured_product':
                $title_shortcode = esc_html__('Featured', 'nasa-core');
                break;
            case 'top_rate':
                $title_shortcode = esc_html__('Top Rate', 'nasa-core');
                break;
            case 'on_sale':
                $title_shortcode = esc_html__('On Sale', 'nasa-core');
                break;
            case 'recent_review':
                $title_shortcode = esc_html__('Recent Review', 'nasa-core');
                break;
            case 'deals':
                $title_shortcode = esc_html__('Deals', 'nasa-core');
                break;
            case 'recent_product':
            default:
                $title_shortcode = esc_html__('Recent', 'nasa-core');
                break;
        endswitch;

        $title_shortcode = $catName != '' ? $title_shortcode . ' ' . $catName : $title_shortcode;
    }
    
    if($pos_nav == 'left') : ?>
        <div class="row nasa-warp-slide-nav-side">
            <div class="large-2 columns">
                <div class="nasa-slide-left-info-wrap">
                    <?php if($parent_term) : ?>
                        <h4 class="nasa-shortcode-parent-term">
                            <a href="<?php echo esc_url($parent_term_link); ?>" title="<?php echo esc_attr($parent_term->name); ?>"><?php echo $parent_term->name; ?></a>
                        </h4>
                    <?php endif; ?>
                    <h3 class="nasa-shortcode-title-slider"><?php echo $title_shortcode; ?></h3>

                    <?php if($arrows == 1) : ?>
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
                    <?php endif; ?>

                    <?php if($link_shortcode) : ?>
                        <a href="<?php echo esc_url($link_shortcode); ?>" title="<?php echo esc_html__('View more of', 'nasa-core') . ($catName != '' ? ' ' . esc_attr($catName) : ''); ?>" class="nasa-view-more-slider">
                            <?php echo esc_html__('View more', 'nasa-core'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="large-10 columns">
                <div class="row group-slider">
                    <div
                        id="nasa-slider-<?php echo esc_attr($id_sc); ?>"
                        class="slider products-group nasa-slider owl-carousel products grid"
                        data-margin="<?php echo esc_attr($data_margin); ?>"
                        data-margin_small="5"
                        data-columns="<?php echo esc_attr($columns_number); ?>"
                        data-columns-small="<?php echo esc_attr($columns_number_small); ?>"
                        data-columns-tablet="<?php echo esc_attr($columns_number_tablet); ?>"
                        data-autoplay="<?php echo esc_attr($auto_slide); ?>"
                        data-loop="<?php echo $auto_slide == 'true' ? 'true' : 'false'; ?>"
                        data-padding="65px"
                        data-height-auto="<?php echo $height_auto; ?>"
                        data-dot="<?php echo esc_attr($dots); ?>"
                        data-disable-nav="true">
                        <?php
                        while ($loop->have_posts()) :
                            $loop->the_post();

                            wc_get_template('content-product.php', array(
                                'is_deals' => $is_deals,
                                '_delay' => $_delay,
                                '_delay_item' => $_delay_item,
                                'disable_drag' => true,
                                'combo_show_type' => 'popup'
                            ));
                            $_delay += $_delay_item;
                        endwhile;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else :
        if($title_shortcode != '') :
            $title_align = !isset($title_align) || $title_align != 'right' ? 'left' : 'right';
            ?>
            <div class="row nasa-warp-slide-nav-top<?php echo ' title-align-' . $title_align; ?>">
                <div class="large-12 columns">
                    <div class="nasa-title nasa_type_2">
                        <h3 class="nasa-title-heading">
                            <?php if($parent_term) : ?>
                                <span class="hidden-tag nasa-parent-cat">
                                    <a href="<?php echo esc_url($parent_term_link); ?>" title="<?php echo esc_attr($parent_term->name); ?>"><?php echo $parent_term->name; ?></a>
                                </span>
                            <?php endif; ?>
                            <span><?php echo esc_attr($title_shortcode); ?></span>
                        </h3>
                        <hr class="nasa-separator" />
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="nasa-relative nasa-slide-style-product-carousel nasa-warp-slide-nav-top<?php echo ' title-align-' . $title_align; ?>">

            <?php if($link_shortcode) : ?>
                <div class="nasa-sc-product-btn">
                    <a href="<?php echo esc_url($link_shortcode); ?>" title="<?php echo esc_html__('Shop all', 'nasa-core') . ($catName != '' ? ' ' . esc_attr($catName) : ''); ?>" class="nasa-view-more-slider">
                        <?php echo esc_html__('Shop all', 'nasa-core') . ($catName != '' ? ' ' . esc_attr($catName) : ''); ?>
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if($arrows == 1) : ?>
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
            <?php endif; ?>
            
            <div class="row group-slider">
                <div
                    id="nasa-slider-<?php echo esc_attr($id_sc); ?>"
                    class="slider products-group nasa-slider owl-carousel products grid"
                    data-margin="<?php echo esc_attr($data_margin); ?>"
                    data-margin_small="5"
                    data-columns="<?php echo esc_attr($columns_number); ?>"
                    data-columns-small="<?php echo esc_attr($columns_number_small); ?>"
                    data-columns-tablet="<?php echo esc_attr($columns_number_tablet); ?>"
                    data-autoplay="<?php echo esc_attr($auto_slide); ?>"
                    data-loop="<?php echo $auto_slide == 'true' ? 'true' : 'false'; ?>"
                    data-padding="65px"
                    data-height-auto="<?php echo $height_auto; ?>"
                    data-dot="<?php echo esc_attr($dots); ?>"
                    data-disable-nav="true">
                    <?php while ($loop->have_posts()) :
                        $loop->the_post();
                        wc_get_template('content-product.php', array(
                            'is_deals' => $is_deals,
                            '_delay' => $_delay,
                            '_delay_item' => $_delay_item,
                            'disable_drag' => true,
                            'combo_show_type' => 'popup'
                        ));
                        $_delay += $_delay_item;
                    endwhile; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php else :
    if($title_shortcode != '') :
        $title_align = !isset($title_align) || $title_align != 'right' ? 'left' : 'right';
        ?>
        <div class="row nasa-warp-slide-nav-top<?php echo ' title-align-' . $title_align; ?>">
            <div class="large-12 columns">
                <div class="nasa-title nasa_type_2">
                    <h3 class="nasa-title-heading">
                        <?php if($parent_term) : ?>
                            <span class="hidden-tag nasa-parent-cat">
                                <a href="<?php echo esc_url($parent_term_link); ?>" title="<?php echo esc_attr($parent_term->name); ?>"><?php echo $parent_term->name; ?></a>
                            </span>
                        <?php endif; ?>
                        <span><?php echo esc_attr($title_shortcode); ?></span>
                    </h3>
                    <hr class="nasa-separator" />
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="nasa-relative nasa-slide-style-product-carousel nasa-warp-slide-nav-top<?php echo ' title-align-' . $title_align; ?>">
        <?php if($link_shortcode) : ?>
            <div class="nasa-sc-product-btn">
                <a href="<?php echo esc_url($link_shortcode); ?>" title="<?php echo esc_html__('Shop all', 'nasa-core') . ($catName != '' ? ' ' . esc_attr($catName) : ''); ?>" class="nasa-view-more-slider">
                    <?php echo esc_html__('Shop all', 'nasa-core') . ($catName != '' ? ' ' . esc_attr($catName) : ''); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <?php if($arrows == 1) : ?>
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
        <?php endif; ?>
        
        <div class="row group-slider">
            <div
                id="nasa-slider-<?php echo esc_attr($id_sc); ?>"
                class="slider products-group nasa-slider owl-carousel products grid nasa-slide-double-row"
                data-margin="<?php echo esc_attr($data_margin); ?>"
                data-margin_small="5"
                data-columns="<?php echo esc_attr($columns_number); ?>"
                data-columns-small="<?php echo esc_attr($columns_number_small); ?>"
                data-columns-tablet="<?php echo esc_attr($columns_number_tablet); ?>"
                data-autoplay="<?php echo esc_attr($auto_slide); ?>"
                data-loop="<?php echo $auto_slide == 'true' ? 'true' : 'false'; ?>"
                data-padding="65px"
                data-height-auto="<?php echo $height_auto; ?>"
                data-dot="<?php echo esc_attr($dots); ?>"
                data-disable-nav="true">
                <?php
                $k = 0;
                echo '<div class="nasa-wrap-column">';
                while ($loop->have_posts()) :
                    $loop->the_post();
                    echo ($k && $k%2 == 0) ? '<div class="nasa-wrap-column">' : '';

                    wc_get_template('content-product.php', array(
                        'is_deals' => $is_deals,
                        '_delay' => $_delay,
                        '_delay_item' => $_delay_item,
                        'disable_drag' => true,
                        'combo_show_type' => 'popup'
                    ));

                    $_delay += ($k && $k%2 == 1) ? $_delay_item : 0;
                    echo ($k && $k%2 == 1) ? '</div>' : '';
                    $k++;
                endwhile;
                echo ($k && $k%2 == 1) ? '</div>' : '';
                ?>
            </div>
        </div>
    </div>
<?php
endif;
