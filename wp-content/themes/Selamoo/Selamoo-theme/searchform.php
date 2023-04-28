<?php
/**
 * The template for displaying search forms in nasatheme
 *
 * @package nasatheme
 */

$search_param = array(
    'name'  => 'post_type',
    'value' => 'product'
);

$_id = rand();
ob_start();
do_action('nasa_root_cats');
$nasa_cat_top = ob_get_clean();
?>

<div class="search-wrapper nasa-ajaxsearchform-container <?php echo esc_attr($_id); ?>_container">
    <table class="nasa-table-search-wrapper">
        <tr>
            <td class="icon-td-warp<?php echo ($nasa_cat_top != '') ? ' nasa-has-cat-topbar' : '' ; ?>">
                <div class="nasa-filter-cat-topbar"><?php echo $nasa_cat_top; ?></div>
            </td>
            <td>
                <div class="nasa-search-form-warp">
                    <form method="get" class="nasa-ajaxsearchform" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="search-control-group control-group">
                            <label class="sr-only screen-reader-text">
                                <?php esc_html_e('Rechercher ici', 'digi-theme'); ?>
                            </label>
                            <input id="nasa-input-<?php echo esc_attr($_id); ?>" type="text" class="search-field search-input live-search-input" value="<?php echo get_search_query(); ?>" name="s" placeholder="<?php esc_html_e("Je cherche le produit ...", 'digi-theme'); ?>" />
                            <?php do_action('nasa_search_by_cat', true); ?>
                            <span class="nasa-icon-submit-page"><input type="submit" name="page" value="<?php esc_html_e('Rechercher', 'digi-theme'); ?>" /></span>
                            <input type="hidden" class="search-param" name="<?php echo esc_attr($search_param['name']); ?>" value="<?php echo esc_attr($search_param['value']); ?>" />
                        </div>
                    </form>
                </div>
            </td>
        </tr>
    </table>
</div>