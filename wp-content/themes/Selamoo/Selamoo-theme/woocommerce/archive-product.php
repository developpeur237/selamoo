<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.4.0
 */
if (!defined('ABSPATH')){
    exit; // Exit if accessed directly
}
global $nasa_opt, $wp_query;

$typeView = !isset($nasa_opt['products_type_view']) ?
    'grid' : ($nasa_opt['products_type_view'] == 'list' ? 'list' : 'grid');

$nasa_opt['products_per_row'] = isset($nasa_opt['products_per_row']) && (int) $nasa_opt['products_per_row'] ?
    (int) $nasa_opt['products_per_row'] : 5;
$nasa_opt['products_per_row'] = $nasa_opt['products_per_row'] > 5 || $nasa_opt['products_per_row'] < 3 ? 5 : $nasa_opt['products_per_row'];
$nasa_change_view = !isset($nasa_opt['enable_change_view']) || $nasa_opt['enable_change_view'] ? true : false;
$typeShow = $typeView == 'grid' ? ($typeView . '-' . ((int) $nasa_opt['products_per_row'])) : 'list';
$typeShow = $nasa_change_view && isset($_COOKIE['gridcookie']) ? $_COOKIE['gridcookie'] : $typeShow;

$nasa_cat_obj = $wp_query->get_queried_object();
$nasa_term_id = 0;
$nasa_type_page = 'product_cat';
$nasa_href_page = '';
if (isset($nasa_cat_obj->term_id) && isset($nasa_cat_obj->taxonomy)) {
    $nasa_term_id = (int) $nasa_cat_obj->term_id;
    $nasa_type_page = $nasa_cat_obj->taxonomy;
    $nasa_href_page = esc_url(get_term_link((int) $nasa_cat_obj->term_id, $nasa_type_page));
}

$nasa_description = digi_term_description($nasa_term_id, $nasa_type_page);
$nasa_sidebar = isset($nasa_opt['category_sidebar']) ? $nasa_opt['category_sidebar'] : 'top';
$nasa_has_get_sidebar = false;

// Check $_GET['sidebar']
if (isset($_GET['sidebar'])):
    $nasa_has_get_sidebar = true;
    switch ($_GET['sidebar']) :
        case 'left' :
            $nasa_sidebar = 'left';
            break;
        
        case 'right' :
            $nasa_sidebar = 'right';
            break;
        
        case 'no' :
            $nasa_sidebar = 'no';
            break;
        
        case 'top' :
        default:
            $nasa_sidebar = 'top';
            break;
    endswitch;
endif;

$hasSidebar = true;
$topSidebar = false;
$attr = 'nasa-products-page-wrap ';
switch ($nasa_sidebar):
    case 'right':
        $attr .= 'large-9 columns left has-sidebar';
        break;

    case 'left':
        $attr .= 'large-9 columns right has-sidebar';
        break;

    case 'no':
        $hasSidebar = false;
        $attr .= 'large-12 columns no-sidebar';
        break;
    
    case 'top':
    default :
        $topSidebar = true;
        $attr .= 'large-12 columns no-sidebar top-sidebar';
        break;
endswitch;

$nasa_recom_pos = isset($nasa_opt['recommend_product_position']) ? $nasa_opt['recommend_product_position'] : 'top';
get_header('shop');
digi_get_breadcrumb();
?>

<div class="row fullwidth category-page">
    <?php do_action('woocommerce_before_main_content'); ?>
    
    <div class="large-12 columns">
        <div class="row filters-container nasa-filter-wrap">
            <div class="hide-for-small large-4 columns">
                <?php /* Sidebar TOP ICON SHOW | HIDE */
                if($topSidebar) :
                    $showFilter = !isset($nasa_opt['top_sidebar_df']) || $nasa_opt['top_sidebar_df'] ? '1' : '0';
                    ?>
                    <a class="nasa-togle-topbar" href="javascript:void(0);" data-filter="<?php echo $showFilter; ?>">
                        <span class="nasa-hide-filter<?php echo $showFilter == '0' ? ' hidden-tag' : ''; ?>"><?php echo esc_html__('Hide filter', 'digi-theme'); ?></span>
                        <span class="nasa-show-filter<?php echo $showFilter == '1' ? ' hidden-tag' : ''; ?>"><?php echo esc_html__('Show filter', 'digi-theme'); ?></span>
                    </a>
                <?php /* INFO Sidebar LEFT | RIGHT */
                else:
                    echo '<input type="hidden" name="nasa-pos-showing-info" value="1" />';
                    echo '<div class="showing_info_top">';
                    do_action('digi_shop_category_count');
                    echo '</div>';
                endif;
                ?>
            </div>
            <?php /* Change view ICONS */
            if ($nasa_change_view) : ?>
                <div class="large-4 text-center columns">
                    <ul class="filter-tabs">
                        <li class="sort-bar-text"><?php esc_html_e('View as: ', 'digi-theme'); ?></li>
                        <?php if($nasa_sidebar != 'left' && $nasa_sidebar != 'right') : ?>
                            <li class="nasa-change-layout productGrid grid-5<?php echo ($typeShow == 'grid-5') ? ' active' : ''; ?>" data-columns="5">
                                <i class="icon-nasa-5column"></i>
                            </li>
                        <?php endif; ?>
                        <li class="nasa-change-layout productGrid grid-4<?php echo (($typeShow == 'grid-4') || ($typeShow == 'grid-5' && ($nasa_sidebar == 'left' || $nasa_sidebar == 'right'))) ? ' active' : ''; ?>" data-columns="4">
                            <i class="icon-nasa-4column"></i>
                        </li>
                        <li class="nasa-change-layout productGrid grid-3<?php echo ($typeShow == 'grid-3') ? ' active' : ''; ?>" data-columns="3">
                            <i class="icon-nasa-3column"></i>
                        </li>
                        <li class="nasa-change-layout productList list<?php echo ($typeShow == 'list') ? ' active' : ''; ?>" data-columns="1">
                            <i class="icon-nasa-list"></i>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="large-4 columns right">
                <ul class="sort-bar">
                    <?php /* TOGLE Sidebar FOR MOBILE */
                    if ($hasSidebar): ?>
                        <li class="li-toggle-sidebar">
                            <a class="toggle-sidebar" href="javascript:void(0);">
                                <i class="icon-menu"></i> <?php esc_html_e('Sidebar', 'digi-theme'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="sort-bar-text nasa-order-label hidden-tag"><?php esc_html_e('Sort by: ', 'digi-theme'); ?></li>
                    <li class="nasa-filter-order filter-order"><?php do_action('woocommerce_before_shop_loop'); ?></li>
                </ul>
            </div>

            <?php /* Sidebar TOP */
            if($topSidebar) :
                do_action('nasa_top_sidebar_shop');
            endif; ?>
        </div>
    </div>
    
    <div class="<?php echo esc_attr($attr); ?>">
        <?php if(!isset($nasa_opt['disable_ajax_product_progress_bar']) || $nasa_opt['disable_ajax_product_progress_bar'] != 1) : ?>
            <div class="nasa-progress-bar-load-shop"><div class="nasa-progress-per"></div></div>
        <?php endif; ?>
        
        <?php if(!$topSidebar && $nasa_recom_pos !== 'bot') :?>
            <span id="position-nasa-recommend-product" class="hidden-tag"></span>
            <?php if(defined('NASA_CORE_ACTIVED') && NASA_CORE_ACTIVED) :
                do_action('nasa_recommend_product', $nasa_term_id);
            endif; ?>
        <?php endif; ?>

        <span id="position-nasa-cat-header" class="hidden-tag"></span>
        <?php echo digi_get_cat_header($nasa_term_id); ?>

        <div class="row">
            <?php
            /**
             * Hook: woocommerce_archive_description.
             *
             * @hooked woocommerce_taxonomy_archive_description - 10
             * @hooked woocommerce_product_archive_description - 10
             */
            do_action('woocommerce_archive_description');
            ?>
            <div class="large-12 columns nasa_shop_description text-justify<?php echo $nasa_description != '' ? ' margin-bottom-20' : ''; ?>">
                <?php echo $nasa_description; ?>
            </div>
        </div>
        
        <div class="nasa-archive-product-warp">
            <?php
            // Content products in shop
            if(version_compare(wc()->version, '3.3.0', "<")) :
                do_action('nasa_archive_get_sub_categories');
            endif;
            woocommerce_product_loop_start();
            do_action('nasa_get_content_products', $nasa_sidebar);
            woocommerce_product_loop_end();
            ?>
        </div>
        
        <div class="row nasa-paginations-warp filters-container-down">
            <?php
            // Pagination -->
            do_action('woocommerce_after_shop_loop');
            ?>
        </div>
        
        <?php if($nasa_recom_pos == 'bot') :?>
            <span id="position-nasa-recommend-product" class="hidden-tag"></span>
            <?php if(defined('NASA_CORE_ACTIVED') && NASA_CORE_ACTIVED) :
                do_action('nasa_recommend_product', $nasa_term_id);
            endif; ?>
        <?php endif; ?>
    </div>
    
    <?php /* Sidebar LEFT | RIGHT */
    if ($nasa_sidebar == 'right' || $nasa_sidebar == 'left') :
        do_action('nasa_sidebar_shop', $nasa_sidebar);
        echo '<input type="hidden" name="nasa-data-sidebar" value="' . $nasa_sidebar . '" />';
    endif;
    ?>
    
    <?php do_action('woocommerce_after_main_content'); ?>
</div>

<?php
$disable_ajax_product = false;
if(isset($nasa_opt['disable_ajax_product']) && $nasa_opt['disable_ajax_product']) :
    $disable_ajax_product = true;
elseif(get_option('woocommerce_shop_page_display', '') != '' || get_option('woocommerce_category_archive_display', '') != '') :
    $disable_ajax_product = true;
endif;

if(!$disable_ajax_product) : ?>
    <div class="nasa-has-filter-ajax hidden-tag">
        <div class="current-cat hidden-tag">
            <a data-id="<?php echo (int) $nasa_term_id; ?>" href="<?php echo esc_url($nasa_href_page); ?>" class="nasa-filter-by-cat" id="nasa-hidden-current-cat" data-taxonomy="<?php echo esc_attr($nasa_type_page); ?>" data-sidebar="<?php echo esc_attr($nasa_sidebar); ?>"></a>
        </div>
        <p><?php esc_html_e('No products were found matching your selection.', 'digi-theme'); ?></p>
        <?php if ($s = get_search_query()): ?>
            <input type="hidden" name="nasa_hasSearch" id="nasa_hasSearch" value="<?php echo esc_attr($s); ?>" />
        <?php endif; ?>
        <?php if($nasa_has_get_sidebar) : ?>
            <input type="hidden" name="nasa_getSidebar" id="nasa_getSidebar" value="<?php echo esc_attr($nasa_sidebar); ?>" />
        <?php endif; ?>
    </div>
<?php endif;

get_footer('shop');