<?php if($count = wp_count_posts()->publish):
    $_delay = 0;
    $_delay_item = (isset($nasa_opt['delay_overlay']) && (int) $nasa_opt['delay_overlay']) ? (int) $nasa_opt['delay_overlay'] : 100;
    ?>
    <div class="nasa-sc-blogs-list nasa-blog-wrap-all-items">
        <?php while($recentPosts->have_posts()) :
            $recentPosts->the_post();
            $id = get_the_ID();
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
            <div class="row nasa-sc-blogs-row wow fadeInUp" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr($_delay); ?>ms">
                <div class="large-2 small-5 columns">
                    <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>">
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
                    </a>
                </div>
                <div class="large-10 small-7 columns">
                    <div class="post-content">
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
                        
                        <h5><a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>"><?php echo $title;?></a></h5>
                        
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
                        
                        <?php if($des_enable == 'yes') : ?>
                            <div class="entry-blog">
                                <div class="clearfix"></div>
                                <div class="nasa-info-short">
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php $_delay += $_delay_item; ?>
        <?php endwhile; ?>
    </div>
<?php
endif;