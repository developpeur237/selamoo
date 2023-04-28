<?php
function digi_get_content_custom_css($nasa_opt = array()) {

ob_start(); ?><style type="text/css"><?php echo '@charset "UTF-8";' . "\n";

if (isset($nasa_opt['type_texts']) && $nasa_opt['type_texts'] != '') : ?>
p,
body,
#top-bar,
.nav-dropdown,
.top-bar-nav a.nav-top-link
{
    font-family: "<?php echo esc_attr($nasa_opt['type_texts']); ?>", helvetica, arial, sans-serif!important;
}
<?php
endif;

if (isset($nasa_opt['max_height_logo']) && (int) $nasa_opt['max_height_logo']) : ?>
#masthead .header-container .logo-wrapper .logo a img,
.fixed-header .logo-wrapper .logo a img
{
    max-height: <?php echo (int) $nasa_opt['max_height_logo'] . 'px'; ?>;
}
<?php
endif;

if (isset($nasa_opt['type_nav']) && $nasa_opt['type_nav'] != '') : ?>
.cart-count,
.mini-cart .nav-dropdown-inner,
.megatop > a,
.header-nav li.root-item > a,
.nasa-tabs .nasa-tab a,
#vertical-menu-wrapper li.root-item > a
{
    font-family: "<?php echo esc_attr($nasa_opt['type_nav']); ?>", helvetica, arial, sans-serif!important;
}
<?php
endif;

if (isset($nasa_opt['type_headings']) && $nasa_opt['type_headings'] != '') : ?>
.service-title,
.price .amount,
h1, h2, h3, h4, h5, h6
{
    font-family: "<?php echo esc_attr($nasa_opt['type_headings']); ?>", helvetica, arial, sans-serif!important;
}
<?php
endif;

if (isset($nasa_opt['type_alt']) && $nasa_opt['type_alt'] != '') : ?>
.anasa-font{
    font-family: "<?php echo esc_attr($nasa_opt['type_alt']); ?>", Georgia, serif!important;
}
<?php
endif;

if (isset($nasa_opt['type_banner']) && $nasa_opt['type_banner'] != '') : ?>
.banner .banner-content .banner-inner h1,
.banner .banner-content .banner-inner h2,
.banner .banner-content .banner-inner h3,
.banner .banner-content .banner-inner h4,
.banner .banner-content .banner-inner h5,
.banner .banner-content .banner-inner h6
{
    font-family: "<?php echo esc_attr($nasa_opt['type_banner']); ?>", helvetica, arial, sans-serif !important;
    letter-spacing: 0px;
    font-weight: normal !important;
}
<?php
endif;

if ($nasa_opt['site_layout'] == 'boxed') :
    $nasa_opt['site_bg_image'] = $nasa_opt['site_bg_image'] ? str_replace(
        array(
            '[site_url]', 
            '[site_url_secure]',
        ),
        array(
            site_url('', 'http'),
            site_url('', 'https'),
        ),
        $nasa_opt['site_bg_image']
    ) : false;
    ?> 
body.boxed,
body
{
    <?php if($nasa_opt['site_bg_color']) : ?>background-color: <?php echo esc_attr($nasa_opt['site_bg_color']); ?>;<?php endif; ?>
    <?php if($nasa_opt['site_bg_image']) : ?>background-image: url("<?php echo esc_url($nasa_opt['site_bg_image']); ?>");<?php endif; ?>
    background-attachment: fixed;
}
<?php
endif;

/* COLOR PRIMARY ================================================================ */
if (isset($nasa_opt['color_primary'])) :
    echo digi_get_style_primary_color($nasa_opt['color_primary']);
endif;

/* COLOR SECONDARY ============================================================== */
if (isset($nasa_opt['color_secondary']) && $nasa_opt['color_secondary'] != '') : ?>
a.secondary.trans-button,
li.menu-sale a
{
    color: <?php echo esc_attr($nasa_opt['color_secondary']); ?> !important;
}
.label-sale.menu-item a:after,
.mini-cart:hover .custom-cart-count,
.button.secondary,
.button.checkout,
#submit.secondary,
button.secondary,
.button.secondary,
input[type="submit"].secondary
{
    background-color: <?php echo esc_attr($nasa_opt['color_secondary']); ?>;
}
a.button.secondary,
.button.secondary
{
    border-color: <?php echo esc_attr($nasa_opt['color_secondary']); ?>;
}
a.secondary.trans-button:hover
{
    color: #FFF!important;
    background-color: <?php echo esc_attr($nasa_opt['color_secondary']); ?> !important;
}
<?php
endif;

/* COLOR SUCCESS ============================================================== */
if (isset($nasa_opt['color_success']) && $nasa_opt['color_success'] != '') : ?> 
.woocommerce-message
{
    color: <?php echo esc_attr($nasa_opt['color_success']); ?> !important;
}
.woocommerce-message:before,
.woocommerce-message:after,
#yith-wcwl-popup-message #yith-wcwl-message
{
    color: #FFF !important;
    background-color: <?php echo esc_attr($nasa_opt['color_success']); ?> !important;
}
.label-popular.menu-item a:after,
.tooltip-new.menu-item > a:after
{
    background-color: <?php echo esc_attr($nasa_opt['color_success']); ?>;
    border-color: <?php echo esc_attr($nasa_opt['color_success']); ?>;
}
.add-to-cart-grid.please-wait .cart-icon .cart-icon-handle,
.woocommerce-message,
.nasa-compare-list-bottom .nasa-compare-mess
{
    border-color: <?php echo esc_attr($nasa_opt['color_success']); ?>;
}
.tooltip-new.menu-item > a:before
{
    border-top-color: <?php echo esc_attr($nasa_opt['color_success']) ?> !important;
}
.out-of-stock-label
{
    border-right-color: <?php echo esc_attr($nasa_opt['color_success']); ?> !important;
    border-top-color: <?php echo esc_attr($nasa_opt['color_success']); ?> !important;
}
.added .pe-icon
{
    color: <?php echo esc_attr($nasa_opt['color_success']); ?> !important;
}
<?php
endif;

/* COLOR SALE ============================================================== */
if (isset($nasa_opt['color_sale_label']) && $nasa_opt['color_sale_label'] != '') : ?>
.badge .badge-inner
{
    background: <?php echo esc_attr($nasa_opt['color_sale_label']); ?>;
}
.badge .badge-inner:before
{
    border-color: transparent <?php echo esc_attr($nasa_opt['color_sale_label']) ?> transparent transparent;
}
.product-gallery .badge .badge-inner:after
{
    border-color: <?php echo esc_attr($nasa_opt['color_sale_label']); ?> transparent transparent transparent;
}
<?php
endif;

/* COLOR HOT ============================================================== */
if (isset($nasa_opt['color_hot_label']) && $nasa_opt['color_hot_label'] != '') : ?>
.badge .badge-inner.hot-label
{
    background: <?php echo esc_attr($nasa_opt['color_hot_label']); ?>;
}
.badge .badge-inner.hot-label:before
{
    border-color: transparent <?php echo esc_attr($nasa_opt['color_hot_label']); ?> transparent transparent;
}
.product-gallery .badge .badge-inner.hot-label:after
{
    border-color: <?php echo esc_attr($nasa_opt['color_hot_label']); ?> transparent transparent transparent;
}
<?php
endif;

/* COLOR PRICE ============================================================== */
if (isset($nasa_opt['color_price_label']) && $nasa_opt['color_price_label'] != '') : ?>
.product-price, 
.price.nasa-sc-p-price,
.price,
.product-item .info .price,
.countdown .countdown-row .countdown-amount,
.columns.nasa-column-custom-4 .nasa-sc-p-deal-countdown .countdown-row.countdown-show4 .countdown-section .countdown-amount,
.item-product-widget .product-meta .price
{
    color: <?php echo esc_attr($nasa_opt['color_price_label']); ?>;
}
.amount
{
    color: <?php echo esc_attr($nasa_opt['color_price_label']); ?> !important;
}
<?php
endif;

/* COLOR BUTTON ============================================================== */
if (isset($nasa_opt['color_button']) && $nasa_opt['color_button'] != '') : ?> 
form.cart .button,
.cart-inner .button.secondary,
.checkout-button,
input#place_order,
.btn-viewcart,
input#submit,
.add-to-cart-grid-style2,
.add_to_cart,
button,
.button
{
    background-color: <?php echo esc_attr($nasa_opt['color_button']); ?>!important;
}
<?php
endif;

/* COLOR HOVER ============================================================== */
if (isset($nasa_opt['color_hover']) && $nasa_opt['color_hover'] != '') : ?>
form.cart .button:hover,
a.primary.trans-button:hover,
.form-submit input:hover,
#payment .place-order input:hover,
input#submit:hover,
.add-to-cart-grid-style2:hover,
.add-to-cart-grid-style2.added,
.add-to-cart-grid-style2.loading,
.product-list .product-img .quick-view.fa-search:hover,
.footer-type-2 input.button,
button:hover,
.button:hover,
.widget.woocommerce li.nasa-li-filter-size.chosen,
.widget.woocommerce li.nasa-li-filter-size.nasa-chosen,
.widget.woocommerce li.nasa-li-filter-size:hover,
.widget.widget_categories li.nasa-li-filter-size.chosen,
.widget.widget_categories li.nasa-li-filter-size.nasa-chosen,
.widget.widget_categories li.nasa-li-filter-size:hover,
.widget.widget_archive li.nasa-li-filter-size.chosen,
.widget.widget_archive li.nasa-li-filter-size.nasa-chosen,
.widget.widget_archive li.nasa-li-filter-size:hover,
.cart-inner .button.secondary:hover,
.checkout-button:hover,
input#place_order:hover,
.btn-viewcart:hover,
input#submit:hover,
.add-to-cart-grid-style2:hover,
.add_to_cart:hover
{
    background-color: <?php echo esc_attr($nasa_opt['color_hover']); ?>!important;
}
.product_list_widget .product-interactions .add-to-cart-grid:hover,
.product_list_widget .product-interactions .quick-view:hover
{
    background-color: <?php echo esc_attr($nasa_opt['color_hover']); ?>;
    border-color: <?php echo esc_attr($nasa_opt['color_hover']); ?>;
}
<?php
endif;

/* COLOR BORDER BUTTON ============================================================== */
if (isset($nasa_opt['button_border_color']) && $nasa_opt['button_border_color'] != '') : ?>
#submit, 
button, 
.button, 
input[type="submit"],
.widget.woocommerce li.nasa-li-filter-size a,
.widget.widget_categories li.nasa-li-filter-size a,
.widget.widget_archive li.nasa-li-filter-size a
{
    border-color: <?php echo esc_attr($nasa_opt['button_border_color']); ?> !important;
}
.products.list .product-item .inner-wrap .product-summary .product-interactions > div
{
    border-color: <?php echo esc_attr($nasa_opt['button_border_color']); ?>;
}
<?php
endif;

if (isset($nasa_opt['button_border_color_hover']) && $nasa_opt['button_border_color_hover'] != '') : ?>
#submit:hover, 
button:hover, 
.button:hover, 
input[type="submit"]:hover,
.products.list .product-item .inner-wrap .product-summary .product-interactions > div:hover,
.widget.woocommerce li.nasa-li-filter-size.chosen a,
.widget.woocommerce li.nasa-li-filter-size.nasa-chosen a,
.widget.woocommerce li.nasa-li-filter-size:hover a,
.widget.widget_categories li.nasa-li-filter-size.chosen a,
.widget.widget_categories li.nasa-li-filter-size.nasa-chosen a,
.widget.widget_categories li.nasa-li-filter-size:hover a,
.widget.widget_archive li.nasa-li-filter-size.chosen a,
.widget.widget_archive li.nasa-li-filter-size.nasa-chosen a,
.widget.widget_archive li.nasa-li-filter-size:hover a
{
    border-color: <?php echo esc_attr($nasa_opt['button_border_color_hover']); ?> !important;
}
.products.list .product-item .inner-wrap .product-summary .product-interactions > div:hover
{
    border-color: <?php echo esc_attr($nasa_opt['button_border_color_hover']); ?>;
}
<?php
endif;

/* COLOR TEXT BUTTON ============================================================== */
if (isset($nasa_opt['button_text_color']) && $nasa_opt['button_text_color'] != '') : ?>
#submit, 
button, 
.button, 
input[type="submit"]
{
    color: <?php echo esc_attr($nasa_opt['button_text_color']); ?> !important;
}
<?php
endif;

/* COLOR HOVER TEXT BUTTON ======================================================= */
if (isset($nasa_opt['button_text_color_hover']) && $nasa_opt['button_text_color_hover'] != '') : ?>
#submit:hover, 
button:hover, 
.button:hover, 
input[type="submit"]:hover
{
    color: <?php echo esc_attr($nasa_opt['button_text_color_hover']); ?> !important;
}
.product_list_widget .product-interactions .quick-view:hover .nasa-icon-text,
.product_list_widget .product-interactions .quick-view:hover .pe-icon,
.product_list_widget .product-interactions .quick-view:hover .nasa-icon
{
    color: <?php echo esc_attr($nasa_opt['button_text_color_hover']); ?>;
}
<?php
endif;

if (isset($nasa_opt['button_radius']) && (int) $nasa_opt['button_radius']) : ?>
#submit, 
button, 
.button,
.products.list .product-item .inner-wrap .info .product-summary .product-interactions .add-to-cart-btn,
.products.list .product-interactions .btn-link .add-to-cart-grid,
.widget .tagcloud a,
.widget.woocommerce li.nasa-li-filter-color a span,
.widget.woocommerce li.nasa-li-filter-color a span,
.widget.widget_categories li.nasa-li-filter-color a span,
.widget.widget_categories li.nasa-li-filter-color a span,
.widget.widget_archive li.nasa-li-filter-color a span,
.widget.widget_archive li.nasa-li-filter-color a span,
.products.grid .product-item .inner-wrap.product-deals .info .nasa-deal-showmore a.button,
.products.grid .product-item .inner-wrap.product-deals .info .nasa-deal-showmore button,
.wishlist_table .add_to_cart,
.yith-wcwl-add-button > a.button.alt,
input[type="submit"]
{
    border-radius: <?php echo (int) $nasa_opt['button_radius']; ?>px;
}
<?php
endif;

if (isset($nasa_opt['button_border']) && (int) $nasa_opt['button_border']) : ?>
#submit, 
button, 
.button,
input[type="submit"]
{
    border-width: <?php echo (int) $nasa_opt['button_border']; ?>px;
}
<?php
endif;

if (isset($nasa_opt['input_radius']) && (int) $nasa_opt['input_radius']) : ?>
textarea,
input[type="text"],
input[type="password"],
input[type="date"], 
input[type="datetime"],
input[type="datetime-local"],
input[type="month"],
input[type="week"],
input[type="email"],
input[type="number"],
input[type="search"],
input[type="tel"],
input[type="time"],
input[type="url"],
#submit.disabled,
#submit[disabled],
button.disabled,
button[disabled],
.button.disabled,
.button[disabled],
input[type="submit"].disabled,
input[type="submit"][disabled],
.category-page .sort-bar .select-wrapper
{
    border-radius: <?php echo (int) $nasa_opt['input_radius']; ?>px;
    -webkit-border-radius: <?php echo (int) $nasa_opt['input_radius']; ?>px;
    -o-border-radius: <?php echo (int) $nasa_opt['input_radius']; ?>px;
}
<?php
endif;

/**
 * Color of header
 */
$bg_color = (isset($nasa_opt['bg_color_header']) && $nasa_opt['bg_color_header']) ? $nasa_opt['bg_color_header'] : '';
$text_color = (isset($nasa_opt['text_color_header']) && $nasa_opt['text_color_header']) ? $nasa_opt['text_color_header'] : '';
$text_color_hover = (isset($nasa_opt['text_color_hover_header']) && $nasa_opt['text_color_hover_header']) ? $nasa_opt['text_color_hover_header'] : '';

echo digi_get_style_header_color($bg_color, $text_color, $text_color_hover);

/**
 * Color of main menu
 */
$bg_color = (isset($nasa_opt['bg_color_main_menu']) && $nasa_opt['bg_color_main_menu']) ? $nasa_opt['bg_color_main_menu'] : '';
$text_color = (isset($nasa_opt['text_color_main_menu']) && $nasa_opt['text_color_main_menu']) ? $nasa_opt['text_color_main_menu'] : '';
$text_color_hover = (isset($nasa_opt['text_color_hover_main_menu']) && $nasa_opt['text_color_hover_main_menu']) ? $nasa_opt['text_color_hover_main_menu'] : '';

echo digi_get_style_main_menu_color($bg_color, $text_color, $text_color_hover);

/**
 * Color of Top bar
 */
if(!isset($nasa_opt['topbar_show']) || $nasa_opt['topbar_show']) {
    $bg_color = (isset($nasa_opt['bg_color_topbar']) && $nasa_opt['bg_color_topbar']) ? $nasa_opt['bg_color_topbar'] : '';
    $text_color = (isset($nasa_opt['text_color_topbar']) && $nasa_opt['text_color_topbar']) ? $nasa_opt['text_color_topbar'] : '';
    $text_color_hover = (isset($nasa_opt['text_color_hover_topbar']) && $nasa_opt['text_color_hover_topbar']) ? $nasa_opt['text_color_hover_topbar'] : '';

    echo digi_get_style_topbar_color($bg_color, $text_color, $text_color_hover);
}

/* Add custom here ================================== */
/* HERE ============================================= */
/* Add custom here ================================== */
if (is_admin_bar_showing()) : ?> 
.tipr_container_bottom
{
    display: none;
    position: absolute;
    margin-top: 13px;
    z-index: 1000;
}
.tipr_container_top
{
    display: none;
    position: absolute;
    margin-top: -70px;
    z-index: 1000;
}
<?php
endif;

if (!isset($nasa_opt['disable_wow']) || !$nasa_opt['disable_wow']) : ?>
.wow
{
    visibility: hidden;
}
<?php endif; ?></style><?php
    $css = ob_get_clean();
    $css = strip_tags($css);
    
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    $css = str_replace(': ', ':', $css);
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
    return $css;
    
    return $css;
}
