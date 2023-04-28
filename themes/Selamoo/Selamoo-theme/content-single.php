<?php
/**
 * @package nasatheme
 */
global $nasa_opt;
$nasa_parallax = isset($nasa_opt['blog_parallax']) && $nasa_opt['blog_parallax'] ? true : false;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="entry-image">
            <?php if ($nasa_parallax) : ?>
                <div class="parallax_img" style="overflow:hidden">
                    <div class="parallax_img_inner" data-velocity="0.15">
                        <?php the_post_thumbnail(); ?>
                        <div class="image-overlay"></div>
                    </div>
                </div>
            <?php else : ?>
                <?php the_post_thumbnail(); ?>
                <div class="image-overlay"></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <header class="entry-header text-center">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <div class="entry-meta">
            <?php digi_posted_on(); ?>
        </div>
    </header>

    <div class="entry-content">
        <?php
        the_content();
        wp_link_pages(array(
            'Avant' => '<div class="page-links">' . esc_html__('Pages:', 'selamoo-theme'),
            'Apres' => '</div>',
        ));
        ?>
    </div>

    <?php
    echo '<div class="blog-share text-center">';
    echo shortcode_exists('share') ? do_shortcode('[share]') : '';
    echo '</div>';
    ?>

    <footer class="entry-meta">
        <?php
        $category_list = get_the_category_list(esc_html__(', ', 'digi-theme'));
        $tag_list = get_the_tag_list('', esc_html__(', ', 'digi-theme'));
        $allowed_html = array(
            'a' => array('href' => array(), 'rel' => array(), 'title' => array())
        );

        if ('' != $tag_list) :
            $meta_text = esc_html__('Publiée dans %1$s et taggé %2$s.', 'digi-theme');
        else :
            $meta_text = wp_kses(__('Publiée il ya %1$s. Bookmark le <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'digi-theme'), $allowed_html);
        endif;

        printf($meta_text, $category_list, $tag_list, get_permalink(), the_title_attribute('echo=0'));
        ?>
    </footer>

</article>
