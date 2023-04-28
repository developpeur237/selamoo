<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.6.0
 */
global $product, $nasa_opt;
$nasa_sidebar = isset($nasa_opt['product_sidebar']) ? $nasa_opt['product_sidebar'] : 'no';
$nasa_actsidebar = is_active_sidebar('product-sidebar');

// Check $_GET['sidebar']
if (isset($_GET['sidebar'])):
    switch ($_GET['sidebar']) :
        case 'right' :
            $nasa_sidebar = 'right';
            break;

        case 'left' :
            $nasa_sidebar = 'left';
            break;
        
        case 'no' :
        default:
            $nasa_sidebar = 'no';
            break;
    endswitch;
endif;

// Class
switch ($nasa_sidebar) :
    case 'right' :
        $main_class = 'large-9 columns left';
        $bar_class = 'large-3 columns col-sidebar product-sidebar-right right';
        break;

    case 'no' :
        $main_class = 'large-12 columns';
        $bar_class = '';
        break;

    default:
    case 'left' :
        $main_class = 'large-9 columns right';
        $bar_class = 'large-3 columns col-sidebar product-sidebar-left left';
        break;

endswitch;

do_action('woocommerce_before_single_product');
if (post_password_required()) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}
?>

<div itemscope itemtype="http://schema.org/Product" id="product-<?php echo (int) $product->get_id(); ?>" <?php post_class(); ?>>
    
    <?php if ($nasa_actsidebar && $nasa_sidebar != 'no') : ?>
        <div class="div-toggle-sidebar center">
            <a class="toggle-sidebar" href="javascript:void(0);"><i class="icon-menu"></i> <?php esc_html_e('Sidebar', 'digi-theme'); ?></a>
        </div>
    <?php endif; ?>
    
    <div class="row nasa-product-details-page">
        <div class="products-arrow">
            <?php do_action('next_prev_product'); ?>
        </div>

        <div class="<?php echo esc_attr($main_class); ?>">

            <div class="row">
                
                <div class="large-<?php echo $nasa_opt['product_sidebar'] != 'no' ? '8' : '6'; ?> small-12 columns product-gallery"> 
                    <?php do_action('woocommerce_before_single_product_summary'); ?>
                </div>
                
                <div class="large-<?php echo $nasa_opt['product_sidebar'] != 'no' ? '4' : '6'; ?> small-12 columns product-info left">
                    <?php do_action('woocommerce_single_product_summary'); ?>
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    <div class="product-details">
                        <div class="row">
                            <div class="large-12 columns">
                                <?php wc_get_template('single-product/tabs/tabs.php'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="related-product">
                <?php do_action('woocommerce_after_single_product_summary'); ?>
            </div>

        </div>

        <?php if ($nasa_actsidebar && $nasa_sidebar != 'no') : ?>
            <div class="<?php echo esc_attr($bar_class); ?>">     
                <div class="inner">
                    <?php dynamic_sidebar('product-sidebar'); ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php
do_action('woocommerce_after_single_product');
