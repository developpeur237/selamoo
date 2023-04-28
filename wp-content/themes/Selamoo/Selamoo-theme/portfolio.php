<?php
/**
 * Template Name: Portfolio
 *
 */

if(!NASA_CORE_ACTIVED || !isset($nasa_opt['enable_portfolio']) || !$nasa_opt['enable_portfolio']) :
    require_once DIGI_THEME_PATH . '/404.php';
    exit(); // Exit if nasa-core has not actived OR disable Fortfolios
endif;

$nasa_columns = (isset($nasa_opt['portfolio_columns']) && (int) $nasa_opt['portfolio_columns']) ?
    (int) $nasa_opt['portfolio_columns'] : 3;

if (isset($_GET['columns'])):
    switch ($_GET['columns']) :
        case '2' :
        case '4' :
            $nasa_columns = (int) $_GET['columns'];
            break;
        case '3':
        default :
            $nasa_columns = 3;
            break;
    endswitch;
endif;

$cat = get_query_var('portfolio_category') ? get_queried_object_id() : 0;
$categories = get_terms('portfolio_category');
$catsCount = count($categories);

get_header();
digi_get_breadcrumb();
?>

<div class="row">
    <div class="content large-12 columns margin-top-20<?php /*margin-bottom-60*/?>">
        <div class="nasa-tabs-content">
            <?php if(!$cat):?>
            <ul class="nasa-tabs portfolio-tabs">
                <li class="description_tab nasa-tab first active">
                    <a href="javascript:void(0);" data-filter="*" class="btn big">
                        <h5><?php esc_html_e('Afficher tout', 'digi-theme'); ?></h5>
                        <span class="nasa-hr medium"></span>
                    </a>
                </li>
                <?php if($catsCount > 0):
                    echo '<li class="separator">|</li>';
                    foreach($categories as $category) :?>
                        <li class="description_tab nasa-tab">
                            <a href="javascript:void(0);" data-filter=".sort-<?php echo esc_attr($category->slug); ?>" class="btn big">
                                <h5><?php echo $category->name; ?></h5>
                                <span class="nasa-hr medium"></span>
                            </a>
                        </li>
                        <li class="separator">|</li>
                    <?php endforeach;?>
                <?php endif;?>
            </ul>
            <?php endif;?>

            <div class="row portfolio collapse portfolio-list" data-columns="<?php echo (int) $nasa_columns; ?>"></div>
            <div class="row">
                <div class="large-12 columns">
                    <div class="text-center load-more loadmore-portfolio" data-category="<?php echo (int) $cat; ?>">
                        <span><?php esc_html_e('Afficher d&rsquo;vantage','digi-theme'); ?></span>
                        <span class="load-more-icon fa fa-angle-double-down"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();

