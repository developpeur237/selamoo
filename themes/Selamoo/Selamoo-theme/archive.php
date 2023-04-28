<?php
/**
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package nasatheme
 * @Developper edition: Revised by Adrian Deltoro (Deltoro Corp)
 */
global $nasa_opt;

$nasa_sidebar = isset($nasa_opt['blog_layout']) ? $nasa_opt['blog_layout'] : '';

// Check $_GET['sidebar']
if (isset($_GET['sidebar'])):
    switch ($_GET['sidebar']) :
        case 'right' :
            $nasa_sidebar = 'right';
            break;
        
        case 'no' :
            $nasa_sidebar = 'no';
            break;
        
        case 'left' :
        default:
            $nasa_sidebar = 'left';
            break;
    endswitch;
endif;

$hasSidebar = true;
$left = true;
switch ($nasa_sidebar):
    case 'right':
        $left = false;
        $attr = 'large-9 left columns';
        break;
    
    case 'no':
        $hasSidebar = false;
        $left = false;
        $attr = 'large-12 columns';
        break;
    
    case 'left':
    default:
        $attr = 'large-9 right columns';
        break;
endswitch;

get_header();
digi_get_breadcrumb();
?>

<div class="container-wrap page-<?php echo ($nasa_sidebar) ? esc_attr($nasa_sidebar) : 'left'; ?>-sidebar">

    <?php if ($hasSidebar): ?>
        <div class="div-toggle-sidebar center"><a class="toggle-sidebar" href="javascript:void(0);"><i class="icon-menu"></i> <?php esc_html_e('Sidebar', 'digi-theme'); ?></a></div>
    <?php endif; ?>

    <div class="row">
        <div id="content" class="<?php echo esc_attr($attr); ?>" role="main">
            <?php if (have_posts()) : ?>
                <header class="page-header">
                    <h1 class="page-title">
                        <?php
                        if (is_category()) :
                            printf(esc_html__('Category Archives: %s', 'digi-theme'), '<span>' . single_cat_title('', false) . '</span>');

                        elseif (is_tag()) :
                            printf(esc_html__('Archives de Tag: %s', 'digi-theme'), '<span>' . single_tag_title('', false) . '</span>');

                        elseif (is_author()) : the_post();
                            printf(esc_html__('Archives D&rsquo;auteur: %s', 'digi-theme'), '<span class="vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" title="' . esc_attr(get_the_author()) . '" rel="me">' . get_the_author() . '</a></span>');
                            rewind_posts();

                        elseif (is_day()) :
                            printf(esc_html__('Archives Journaliere: %s', 'digi-theme'), '<span>' . get_the_date() . '</span>');

                        elseif (is_month()) :
                            printf(esc_html__('Archives Mensuel: %s', 'digi-theme'), '<span>' . get_the_date('F Y') . '</span>');

                        elseif (is_year()) :
                            printf(esc_html__('Archives Annuel: %s', 'digi-theme'), '<span>' . get_the_date('Y') . '</span>');

                        elseif (is_tax('post_format', 'post-format-aside')) :
                            esc_html_e('Asides', 'digi-theme');

                        elseif (is_tax('post_format', 'post-format-image')) :
                            esc_html_e('Images', 'digi-theme');

                        elseif (is_tax('post_format', 'post-format-video')) :
                            esc_html_e('Videos', 'digi-theme');

                        elseif (is_tax('post_format', 'post-format-quote')) :
                            esc_html_e('Quotes', 'digi-theme');

                        elseif (is_tax('post_format', 'post-format-link')) :
                            esc_html_e('Links', 'digi-theme');

                        else :
                            esc_html_e('', 'digi-theme');

                        endif;
                        ?>
                    </h1>
                    <?php
                    if (is_category()) :
                        $category_description = category_description();
                        if (!empty($category_description)) :
                            echo apply_filters('category_archive_meta', '<div class="taxonomy-description">' . $category_description . '</div>');
                        endif;

                    elseif (is_tag()) :
                        $tag_description = tag_description();
                        if (!empty($tag_description)) :
                            echo apply_filters('tag_archive_meta', '<div class="taxonomy-description">' . $tag_description . '</div>');
                        endif;

                    endif;
                    ?>
                </header>

                <div class="page-inner">
                    <?php
                    while (have_posts()) :
                        the_post();
                        get_template_part('Contenu', get_post_format());
                    endwhile;
                    ?>
                    <div class="large-12 columns navigation-container">
                        <?php digi_content_nav('nav-below'); ?>
                    </div>
                </div>
            <?php else : ?>
                <?php get_template_part('Sans resultat', 'archive'); ?>
            <?php endif; ?>
        </div>

        <?php if ($nasa_sidebar != 'no') : ?>
            <div class="large-3 columns <?php echo ($left) ? 'left' : 'right'; ?> col-sidebar">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php
get_footer();