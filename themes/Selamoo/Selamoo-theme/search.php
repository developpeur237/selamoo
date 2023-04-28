<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package nasatheme
 */
if (!isset($nasa_opt['blog_layout'])) :
    $nasa_opt['blog_layout'] = '';
endif;

$hasSidebar = true;
$left = true;
switch ($nasa_opt['blog_layout']):
    case 'right-sidebar':
        $left = false;
        $attr = 'large-9 left columns';
        break;
    case 'no-sidebar':
        $hasSidebar = false;
        $left = false;
        $attr = 'large-10 columns large-offset-1';
        break;
    default:
        $attr = 'large-9 right columns';
        break;
endswitch;

get_header();
digi_get_breadcrumb();
?>

<div class="container-wrap page-<?php echo ($nasa_opt['blog_layout']) ? esc_attr($nasa_opt['blog_layout']) : 'right-sidebar'; ?>">

    <?php if ($hasSidebar): ?>
        <div class="div-toggle-sidebar center"><a class="toggle-sidebar" href="javascript:void(0);"><i class="icon-menu"></i> <?php esc_html_e('Paneau lateral', 'digi-theme'); ?></a></div>
    <?php endif; ?>

    <div class="row">
        <div id="content" class="<?php echo esc_attr($attr); ?>" role="main">
            <div class="page-inner">
                <?php if (have_posts()) : ?>
                    <header class="page-header">
                        <h1 class="page-title"><?php printf(esc_html__('Resultat de recherche pour: %s', 'digi-theme'), '<span>' . get_search_query() . '</span>'); ?></h1>
                    </header>

                    <?php
                    while (have_posts()) : the_post();
                        get_template_part('content', get_post_format());
                    endwhile;

                    digi_content_nav('nav-below');
                else :
                    get_template_part('no-results', 'search');
                endif;
                ?>
            </div>
        </div>

        <?php if ($nasa_opt['blog_layout'] == 'left-sidebar' || $nasa_opt['blog_layout'] == 'right-sidebar'): ?>
            <div class="large-3 columns <?php echo ($left) ? 'left' : 'right'; ?> col-sidebar">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>

    </div>	
</div>

<?php
get_footer();