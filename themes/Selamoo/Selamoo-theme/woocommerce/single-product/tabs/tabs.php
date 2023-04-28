<?php
/**
 * Single Product tabs / and sections
 *
 * @author 	WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.8.0
 */
if (!defined('ABSPATH')) :
    exit; // Exit if accessed directly
endif;

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters('woocommerce_product_tabs', array());

if (!empty($tabs)) :
    global $nasa_opt, $product;

    $specifications = (!isset($nasa_opt['enable_specifications']) || $nasa_opt['enable_specifications'] == '1') ?
        digi_get_product_meta_value($product->get_id(), 'nasa_specifications') : '';
    $specifi_desc = (!isset($nasa_opt['merge_specifi_to_desc']) || $nasa_opt['merge_specifi_to_desc'] == '1') ? true : false;

    $comboContent = digi_combo_tab(false);
?>
    <div class="nasa-tabs-content woocommerce-tabs text-left nasa-slide-style">
        <ul class="nasa-tabs">
            <li class="nasa-slide-tab"></li>
            <?php if($comboContent) : ?>
            <li class="nasa_combo_tab nasa-tab active first">
                <a href="javascript:void(0);" data-id="#nasa-tab-combo-gift">
                    <h5><?php echo esc_html__('Promotion Gifts', 'digi-theme'); ?></h5>
                    <span class="nasa-hr small"></span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (!empty($tabs)) :
                $k_title = $comboContent ? 1 : 0;
                foreach ($tabs as $key => $tab) :
                    ?>
                    <li class="<?php echo esc_attr($key); ?>_tab nasa-tab<?php echo $k_title == 0 ? ' active first' : ''; ?>">
                        <a href="javascript:void(0);" data-id="#nasa-tab-<?php echo esc_attr($key); ?>">
                            <h5><?php echo apply_filters('woocommerce_product_' . $key . '_tab_title', $tab['title'], $key); ?></h5>
                            <span class="nasa-hr small"></span>
                        </a>
                    </li>
                    <li class="separator">|</li>
                    <?php if ($key == 'description' && (trim($specifications) != '' && !$specifi_desc)) : ?>
                        <li class="specifications_tab nasa-tab">
                            <a href="javascript:void(0);" data-id="#nasa-tab-specifications">
                                <h5><?php echo esc_html__('Specifications', 'digi-theme'); ?></h5>
                                <span class="nasa-hr small"></span>
                            </a>
                        </li>
                        <li class="separator">|</li>
                        <?php
                    endif;
                    $k_title++;
                endforeach;
            endif;
            if (isset($nasa_opt['tab_title']) && $nasa_opt['tab_title']) : ?> 
                <li class="additional-tab nasa-tab<?php echo $k_title == 0 ? ' active first' : ''; ?>">
                    <a href="javascript:void(0);" data-id="#nasa-tab-additional">
                        <h5><?php echo esc_attr($nasa_opt['tab_title']) ?></h5>
                        <span class="nasa-hr small"></span>
                    </a>
                </li>
                <li class="separator">|</li>
            <?php endif; ?>
        </ul>
        <div class="nasa-panels">
            <?php if($comboContent) : ?>
                <div class="nasa-panel entry-content nasa-content-combo-gift active" id="nasa-tab-combo-gift">
                    <div class="row nasa-combo-row no-border"><?php echo $comboContent; ?></div>
                </div>
            <?php endif; ?>
            <?php
            if (!empty($tabs)) :
                $k_tab = $comboContent ? 1 : 0;
                foreach ($tabs as $key => $tab) :
                    ?>
                    <div class="nasa-panel entry-content<?php echo ($k_tab == 0) ? ' active' : ''; ?>" id="nasa-tab-<?php echo esc_attr($key); ?>">
                        <?php if ($key == 'description' && $specifi_desc): ?>
                            <div class="nasa-panel-block">
                                <?php call_user_func($tab['callback'], $key, $tab); ?>
                            </div>
                            <?php if (trim($specifications) != '') : ?>
                                <div class="nasa-panel-block nasa-content-specifications">
                                    <?php echo $specifications; ?>
                                </div>
                            <?php endif; ?>
                        <?php
                        else:
                            call_user_func($tab['callback'], $key, $tab);
                        endif;
                        ?>
                    </div>
                    <?php 
                    if ($key == 'description' && (trim($specifications) != '') && !$specifi_desc) : ?>
                        <div class="nasa-panel entry-content nasa-content-specifications" id="nasa-tab-specifications">
                            <p><?php echo $specifications; ?></p>
                        </div>
                    <?php
                    endif;
                    $k_tab++;
                endforeach;
            endif;
            if ($nasa_opt['tab_title']) : ?>
                <div class="nasa-panel entry-content<?php echo ($k_tab == 0) ? ' active' : ''; ?>" id="nasa-tab-additional">
                    <?php echo do_shortcode($nasa_opt['tab_content']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
endif;
