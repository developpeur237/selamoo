<?php
/**
 * Single Product Share
 *
 * @author 	WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.0
 */
if (!defined('ABSPATH')) :
    exit;
endif;
?>
<hr class="nasa-single-hr" />
<div class="nasa-single-share">
    <span class="nasa-single-share-text">
        <?php echo esc_html__('Share it: ', 'digi-theme'); ?>
    </span>
    <?php
    echo (shortcode_exists('nasa_share')) ? do_shortcode('[nasa_share]') : '';
    do_action('woocommerce_share');
    ?>
</div>