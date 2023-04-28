<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */
if (!defined('ABSPATH')) :
    exit; // Exit if accessed directly
endif;

global $product;
?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
    <p class="price large"><?php echo $product->get_price_html(); ?></p>
    <meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
</div>