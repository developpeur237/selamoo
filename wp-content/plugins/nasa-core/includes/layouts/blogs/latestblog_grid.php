<?php
$_delay = 0;
$_delay_item = (isset($nasa_opt['delay_overlay']) && (int) $nasa_opt['delay_overlay']) ? (int) $nasa_opt['delay_overlay'] : 100;
?>
<div class="row group-blogs nasa-blog-wrap-all-items">
    <div class="large-12 columns">
        <div class="blog-grid blog-grid-style">
            <ul class="small-block-grid-<?php echo esc_attr($columns_number_small); ?> medium-block-grid-<?php echo esc_attr($columns_number_tablet); ?> large-block-grid-<?php echo esc_attr($columns_number); ?> grid" data-product-per-row="<?php echo esc_attr($columns_number); ?>">
                <?php
                $k = 0;
                $count = wp_count_posts()->publish;
                if ($count > 0) {
                    while ($recentPosts->have_posts()) {
                        echo '<li class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="' . esc_attr($_delay) . 'ms"><div class="nasa-item-blog-grid">';
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
                            <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>">
                                <div class="entry-blog">
                                    <div class="blog-image">
                                        <div class="blog-image-attachment" style="overflow:hidden;">
                                            <?php
                                            if (has_post_thumbnail()):
                                                the_post_thumbnail('nasa-list-thumb', array(
                                                    'alt' => esc_attr($title)
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
                            <div class="nasa-blog-info nasa-blog-img-top">
                                <div class="blog_title">
                                    <h5>
                                        <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>"><?php echo $title; ?></a>
                                    </h5>
                                </div>
                                
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
                                
                                <div class="clearfix"></div>
                                
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
                        <?php
                        echo '</div></li>';
                        $k++;
                        $_delay += $_delay_item;
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</div> 