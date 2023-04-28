<?php
/**
 * @package nasatheme
 */

global $nasa_opt;

if (isset($_GET['list-style'])):
    $nasa_opt['blog_type'] = 'blog-list';
endif;

$allowed_html = array(
    'strong' => array()
);

$postId = get_the_ID(); 
$title = get_the_title();
$link = get_the_permalink();

$show_author_info = (!isset($nasa_opt['show_author_info']) || $nasa_opt['show_author_info']) ? true : false;
$show_date_info = (!isset($nasa_opt['show_date_info']) || $nasa_opt['show_date_info']) ? true : false;
$show_cat_info = (!isset($nasa_opt['show_cat_info']) || $nasa_opt['show_cat_info']) ? true : false;
$show_tag_info = (!isset($nasa_opt['show_tag_info']) || $nasa_opt['show_tag_info']) ? true : false;
$show_readmore = (!isset($nasa_opt['show_readmore_blog']) || $nasa_opt['show_readmore_blog']) ? true : false;

if ($show_author_info) {
    $author = get_the_author();
    $author_id = get_the_author_meta('ID');
    $link_author = get_author_posts_url($author_id);
}

if ($show_date_info) {
    $day = get_the_time('d', $postId);
    $month = get_the_time('m', $postId);
    $year = get_the_time('Y', $postId);
    $link_date = get_day_link($year, $month, $day);
    $date_post = get_the_time('F n, Y', $postId);
}

if (!isset($nasa_opt['blog_type']) || $nasa_opt['blog_type'] == 'blog-standard') :
    $nasa_parallax = isset($nasa_opt['blog_parallax']) && $nasa_opt['blog_parallax'] ? true : false;
    ?>
    <article id="post-<?php echo (int) $postId; ?>" <?php post_class(); ?>>
        <?php if (has_post_thumbnail()) : ?>
            <div class="entry-image nasa-blog-img">
                <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>">
                    <?php if ($nasa_parallax) : ?>
                        <div class="parallax_img" style="overflow:hidden">
                            <div class="parallax_img_inner" data-velocity="0.15">
                                <?php the_post_thumbnail('nasa-parallax-thumb'); ?>
                                <div class="image-overlay"></div>
                            </div>
                        </div>
                    <?php else : ?>
                        <?php the_post_thumbnail('nasa-parallax-thumb'); ?>
                        <div class="image-overlay"></div>
                    <?php endif; ?>
                </a>
            </div>
        <?php endif; ?>
        
        <header class="entry-header">
            <div class="row">
                
                <div class="large-12 columns">
                    <h3 class="entry-title">
                        <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>" rel="bookmark">
                            <?php echo $title; ?>
                        </a>
                    </h3>
                </div>
                
                <div class="large-12 columns text-left info-wrap">
                    
                    <?php if($show_author_info) : ?>
                        <a href="<?php echo esc_url($link_author); ?>" title="<?php echo esc_html__('Posted By ', 'digi-theme') . esc_attr($author); ?>">
                            <span class="meta-author inline-block">
                                <i class="pe-7s-user"></i> <?php esc_html_e('Publiée par ', 'digi-theme'); ?><?php echo $author; ?>
                            </span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if($show_date_info) : ?>
                        <a href="<?php echo esc_url($link_date); ?>" title="<?php echo esc_html__('Publiée dans ', 'digi-theme') . esc_attr($date_post); ?>">
                            <span class="post-date inline-block">
                                <i class="pe-7s-timer"></i>
                                <?php echo $date_post; ?>
                            </span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!post_password_required() && (comments_open() || '0' != get_comments_number())) : ?>
                        <span class="comments-link inline-block no-after">
                            <i class="pe-7s-comment"></i>
                            <?php comments_popup_link(esc_html__('Laisser un commentaire', 'digi-theme'), wp_kses(__('<strong>1</strong> Commentaire', 'digi-theme'), $allowed_html), wp_kses(__('<strong>%</strong> Commentiares', 'digi-theme'), $allowed_html)); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="large-12 columns">
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>
                </div>
                
                <?php if($show_readmore) : ?>
                    <div class="large-12 columns">
                        <div class="entry-readmore">
                            <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_html__('Lire plus', 'digi-theme'); ?>"><?php echo esc_html__('Lire plus', 'digi-theme'); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
                
            </div>
        </header>

        <?php
        $tags_list = $show_tag_info ? get_the_tag_list('', esc_html__(', ', 'digi-theme')) : false;
        if($show_cat_info || ($show_tag_info && $tags_list)) : ?>
            <footer class="entry-meta">
                <?php if ('post' == get_post_type()) : ?>
                    <?php if ($show_cat_info) : ?>
                        <?php $categories_list = get_the_category_list(esc_html__(', ', 'digi-theme')); ?>
                        <span class="cat-links">
                            <?php printf(esc_html__('Publiée dans %1$s', 'digi-theme'), $categories_list); ?>
                        </span>
                    <?php endif; ?>

                    <?php
                    if ($show_tag_info) :
                        if ($tags_list) :
                            ?>
                            <?php if ($show_cat_info) : ?>
                                <span class="sep"> | </span>
                            <?php endif; ?>
                            <span class="tags-links">
                                <?php printf(esc_html__('Taggé %1$s', 'digi-theme'), $tags_list); ?>
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </footer>
        <?php endif; ?>
    </article>

<?php elseif ($nasa_opt['blog_type'] == 'blog-list') : ?>
    <div class="blog-list-style">
        <article id="post-<?php (int) $postId; ?>" <?php post_class(); ?>>
            <div class="row">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="large-4 columns">
                        <div class="entry-image">
                            <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>">
                                <?php the_post_thumbnail('nasa-list-thumb'); ?>
                                <div class="image-overlay"></div>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="large-8 columns">
                    <div class="entry-content">
                        <h3 class="entry-title">
                            <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>" rel="bookmark">
                                <?php echo $title; ?>
                            </a>
                        </h3>
                        <?php the_excerpt(); ?>
                        <?php if ('post' == get_post_type()) : ?>
                            <div class="entry-meta">
                                <?php digi_posted_on(); ?>
                                <?php if (!post_password_required() && (comments_open() || '0' != get_comments_number())): ?>
                                    <span class="comments-link right">
                                        <?php comments_popup_link(esc_html__('Laisser un commentaire', 'digi-theme'), wp_kses(__('<strong>1</strong> Commentaire', 'digi-theme'), $allowed_html), wp_kses(__('<strong>%</strong> Commentaires', 'digi-theme'), $allowed_html)); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </article>
    </div>
    <?php
endif;