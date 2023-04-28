<?php
/**
 * The main template file.
 *
 * @package nasatheme
 */

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
    
    <?php if($hasSidebar):?>
        <div class="div-toggle-sidebar center">
            <a class="toggle-sidebar" href="javascript:void(0);"><i class="icon-menu"></i> <?php esc_html_e('Sidebar', 'digi-theme');?></a>
        </div>
    <?php endif;?>
        
    <div class="row">
        <div id="content" class="<?php echo esc_attr($attr);?>" role="main">
            <div class="page-inner">
                <?php if (have_posts()) :
                    while (have_posts()) : the_post();
                        get_template_part('content', get_post_format());
                    endwhile;
                else :
                    get_template_part('no-results', 'index');
                endif; ?>

                <div class="large-12 columns navigation-container">
                    <?php digi_content_nav('nav-below'); ?>
                </div>
            </div>
        </div>

        <?php if($nasa_sidebar != 'no'):?>
            <div class="large-3 columns <?php echo ($left) ? 'left' : 'right'; ?> col-sidebar">
                <?php get_sidebar(); ?>
            </div>
        <?php endif;?>
    </div>
</div>

<?php
get_footer();
