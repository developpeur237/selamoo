<?php
/**
 * Pagination - Show numbered pagination for catalog pages.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.1
 */
if (!defined('ABSPATH')) :
    exit; // Exit if accessed directly
endif;

global $nasa_opt, $wp_query;

$total   = isset($total) ? $total : (function_exists('wc_get_loop_prop') ? wc_get_loop_prop('total_pages') : $wp_query->max_num_pages);
$current = isset($current) ? $current : (function_exists('wc_get_loop_prop') ? wc_get_loop_prop('current_page') : max(1, get_query_var('paged')));
$base    = isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));
$format  = isset($format) ? $format : '';

if ($total <= 1) :
	return;
endif;

/* <!-- PAGINATION --> */

$disable_ajax_product = false;
if(isset($nasa_opt['disable_ajax_product']) && $nasa_opt['disable_ajax_product']) :
    $disable_ajax_product = true;
elseif(get_option('woocommerce_shop_page_display', '') != '' || get_option('woocommerce_category_archive_display', '') != '') :
    $disable_ajax_product = true;
endif;

?>

<div class="large-12 columns">
    <?php
    if (isset($nasa_opt['pagination_style'])) :
        if ($nasa_opt['pagination_style'] == 'style-1') :
            ?>
            <div class="nasa-pagination clearfix style-1">
                <div class="page-sumary">
                    <ul>
                        <li><?php do_action('digi_shop_category_count'); ?></li>
                    </ul>
                </div>
                <div class="page-number">
                    <?php
                    echo (!$disable_ajax_product) ?
                        digi_get_pagination_ajax(
                            $total, // Total
                            $current, // Current
                            'list', // Type display
                            '<span class="pe7-icon pe-7s-angle-left"></span>', // Prev text
                            '<span class="pe7-icon pe-7s-angle-right"></span>', // Next text
                            1, // end_size
                            1  // mid_size
                        ) : paginate_links(apply_filters('woocommerce_pagination_args', array(
                            'base' => $base,
                            'format' => $format,
                            'current' => $current,
                            'total' => $total,
                            'prev_text' => '<span class="pe7-icon pe-7s-angle-left"></span>',
                            'next_text' => '<span class="pe7-icon pe-7s-angle-right"></span>',
                            'type' => 'list',
                            'end_size' => 1,
                            'mid_size' => 1
                        )));
                    ?>
                </div>
            </div>
        <?php elseif ($nasa_opt['pagination_style'] == 'style-2') : ?>
            <div class="nasa-pagination style-2">
                <div class="page-number">
                    <?php
                    echo (!$disable_ajax_product) ?
                        digi_get_pagination_ajax(
                            $total,
                            $current,
                            'list',
                            '<span class="fa fa-caret-left"></span>',
                            '<span class="fa fa-caret-right"></span>',
                            1, // end_size
                            1  // mid_size
                        ) : paginate_links(apply_filters('woocommerce_pagination_args', array(
                            'base' => $base,
                            'format' => $format,
                            'current' => $current,
                            'total' => $total,
                            'prev_text' => '<span class="fa fa-caret-left"></span>',
                            'next_text' => '<span class="fa fa-caret-right"></span>',
                            'type' => 'list',
                            'end_size' => 1,
                            'mid_size' => 1
                        )));
                    ?>
                </div>
                <hr />
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php /*!-- end PAGINATION -- */