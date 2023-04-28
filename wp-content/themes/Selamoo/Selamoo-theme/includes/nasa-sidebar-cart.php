<?php if (!isset($woocommerce) || !$woocommerce) :
    esc_html_e('Install WooCommerce plugin and active, Please!', 'digi-theme');
else :
    $cart_items = $woocommerce->cart->get_cart();
    ?>
    <div class="widget_shopping_cart_content cart_sidebar">
        <?php if (sizeof($cart_items) > 0) : ?>
            <div class="cart_list">
                <?php
                $total = 0;
                $show_gift = (isset($nasa_opt['show_gift_minicart']) && $nasa_opt['show_gift_minicart'] == 1) ? true : false;
                
                foreach ($cart_items as $cart_item_key => $cart_item) :
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                    if ($_product->exists() && $cart_item['quantity'] > 0) :
                        $priceItem = $_product->get_price();
                        if($priceItem == 0 && !$show_gift) {
                            continue;
                        }
                        
                        $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                        
                        $link_product = get_permalink((int) $product_id);
                        $name_product = $_product->get_title();
                        ?>
                        <div class="row mini-cart-item collapse<?php echo ($priceItem == 0) ? ' nasa-gift-item-in-cart' : ''; ?>" id="item-<?php echo (int) $product_id; ?>">
                            <div class="small-3 large-3 columns">
                                <?php echo '<a class="cart_list_product_img" href="' . esc_url($link_product) . '" title="' . esc_attr($name_product) . '">' . str_replace(array('http:', 'https:'), '', $_product->get_image()) . '</a>'; ?>
                            </div>
                            <div class="<?php echo ($priceItem == 0) ? 'small-9 large-9' : 'small-7 large-7'; ?> columns">
                                <div class="mini-cart-info">
                                    <?php
                                    echo '<a class="cart_list_product_title" href="' . esc_url($link_product) . '" title="' . esc_attr($name_product) . '">' . apply_filters('woocommerce_cart_widget_product_title', $name_product, $_product) . '</a>';
                                    // Meta data
                                    echo function_exists('wc_get_formatted_cart_item_data') ? wc_get_formatted_cart_item_data($cart_item) : $woocommerce->cart->get_item_data($cart_item);
                                    echo apply_filters('woocommerce_widget_cart_item_quantity', '<div class="cart_list_product_quantity">' . sprintf('%s &times; %s', $cart_item['quantity'], ($product_price ? $product_price : wc_price(0))) . '</div>', $cart_item, $cart_item_key);
                                    ?>
                                </div>
                            </div>
                            <?php if($priceItem != 0) : ?>
                                <div class="small-2 large-2 columns text-right">
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="javascript:void(0);" data-key="%s" data-id="%s" class="remove item-in-cart" title="%s"><i class="pe-7s-close"></i></a>', // pe-7s-trash
                                            $cart_item_key,
                                            $product_id,
                                            esc_html__('Remove this item', 'digi-theme')
                                        ),
                                        $cart_item_key
                                    );
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>                                     
                <?php endforeach; ?>
            </div>

            <div class="minicart_total_checkout">
                <?php
                /**
                 * Woocommerce_widget_shopping_cart_total hook.
                 */
                do_action('woocommerce_widget_shopping_cart_total');
                ?>
            </div>
            
            <div class="btn-mini-cart inline-lists text-center">
                <div class="row collapse">
                    <div class="small-12 large-12 columns">
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="button btn-viewcart"><?php esc_html_e('View Cart', 'digi-theme'); ?></a>
                    </div>
                    <?php if (sizeof($woocommerce->cart->cart_contents) > 0): ?>
                        <div class="small-12 large-12 columns">
                            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="button btn-checkout" title="<?php esc_html_e('Checkout', 'digi-theme'); ?>"><?php esc_html_e('Checkout', 'digi-theme'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php
    else:
        echo $empty;
    endif;
    ?>
    </div>
<?php
endif;
