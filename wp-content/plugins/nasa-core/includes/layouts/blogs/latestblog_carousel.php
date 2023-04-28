<?php
$_delay = 0;
$_delay_item = (isset($nasa_opt['delay_overlay']) && (int) $nasa_opt['delay_overlay']) ? (int) $nasa_opt['delay_overlay'] : 100;
$id_sc = rand(0, 999999);
$auto_slide = isset($auto_slide) ? $auto_slide : 'false';
$dots = isset($dots) ? $dots : 'false';
$arrows = isset($arrows) ? $arrows : 0;
?>

<div class="nasa-relative nasa-slide-style-blogs">
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
    
    <div class="row group-slider nasa-blog-wrap-all-items">
        <div
            id="nasa-slider-<?php echo esc_attr($id_sc); ?>"
            class="group-blogs nasa-blog-carousel nasa-slider owl-carousel"
            data-columns="<?php echo esc_attr($columns_number); ?>"
            data-columns-small="<?php echo esc_attr($columns_number_small); ?>"
            data-columns-tablet="<?php echo esc_attr($columns_number_tablet); ?>"
            data-autoplay="<?php echo esc_attr($auto_slide); ?>"
            data-loop="<?php echo $auto_slide == 'true' ? 'true' : 'false'; ?>"
            data-dot="<?php echo $dots == 'true' ? 'true' : 'false'; ?>"
            data-disable-nav="true">
            <?php
            while ($recentPosts->have_posts()) :
                $recentPosts->the_post();
                $title = get_the_title();
                $link = get_the_permalink();
                $author = get_the_author();
                $author_id = get_the_author_meta('ID');
                $link_author = get_author_posts_url($author_id);
                $postId = get_the_ID();
                $day = get_the_time('d', $postId);
                $month = get_the_time('m', $postId);
                $year = get_the_time('Y', $postId);
                $link_date = get_day_link($year, $month, $day);
                $date_post = get_the_time('d F', $postId);
                ?>
                <div class="blog_item wow fadeInUp" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr($_delay); ?>ms">
                    <div class="large-12 columns">
                        <div class="nasa-content-group">
                            <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>">
                                <div class="entry-blog">
                                    <div class="blog-image img_left">
                                        <div class="blog-image-attachment" style="overflow:hidden;">
                                            <?php
                                            if (has_post_thumbnail()):
                                                the_post_thumbnail('nasa-list-thumb', array(
                                                    'alt' => trim(strip_tags(get_the_title()))
                                                ));
                                            else:
                                                echo '<img src="' . NASA_CORE_PLUGIN_URL . 'assets/images/placeholder.png" alt="' . esc_attr($title) . '" />';
                                            endif;
                                            ?>
                                            <div class="image-overlay"></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="nasa-blog-info-slider">
                                <?php if($date_author == 'top') : ?>
                                    <a href="<?php echo esc_url($link_date); ?>" title="<?php echo esc_html__('Posts in ', 'nasa-core') . esc_attr($date_post); ?>" class="nasa-post-date-author-link">
                                        <span class="nasa-post-date-author">
                                            <i class="pe-7s-timer"></i>
                                            <?php echo $date_post; ?>
                                        </span>
                                    </a>
                                    
                                    <a href="<?php echo esc_url($link_author); ?>" title="<?php echo esc_html__('Posted By ', 'nasa-core') . esc_attr($author); ?>" class="nasa-post-date-author-link">
                                        <span class="nasa-post-date-author nasa-post-author">
                                            <i class="pe-7s-user"></i>
                                            <?php echo $author; ?>
                                        </span>
                                    </a>
                                <?php endif; ?>

                                <div class="blog_title">
                                    <h5>
                                        <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>">
                                            <?php echo $title; ?>
                                        </a>
                                    </h5>
                                </div>
                                
                                <?php if($des_enable == 'yes') : ?>
                                    <div class="nasa-info-short">
                                        <?php the_excerpt(); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($date_author == 'bot') : ?>
                                    <a href="<?php echo esc_url($link_date); ?>" title="<?php echo esc_html__('Posts in ', 'nasa-core') . esc_attr($date_post); ?>" class="nasa-post-date-author-link">
                                        <span class="nasa-post-date-author bottom">
                                            <i class="pe-7s-timer"></i>
                                            <?php echo $date_post; ?>
                                        </span>
                                    </a>
                                    
                                    <a href="<?php echo esc_url($link_author); ?>" title="<?php echo esc_html__('Posted By ', 'nasa-core') . esc_attr($author); ?>" class="nasa-post-date-author-link">
                                        <span class="nasa-post-date-author nasa-post-author bottom">
                                            <i class="pe-7s-user"></i>
                                            <?php echo $author; ?>
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $_delay += $_delay_item; ?>
            <?php endwhile; ?>
        </div> 
    </div>
    
</div>