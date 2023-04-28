<?php
if(!function_exists('digi_get_style_primary_color')) :
function digi_get_style_primary_color($color_primary = '', $return = true) {
    if(trim($color_primary) == '') {
        return '';
    }
    if($return) {
        ob_start();
    }
?><style type="text/css" rel="stylesheet">
/* Start override primary color =========================================== */
.primary-color,
body a:hover,
body a:active,
body p a,
body p a:hover,
body p a:active,
body p a:focus,
body p a:visited,
.add-to-cart-grid .cart-icon strong,
.navigation-paging a,
.navigation-image a,
#header-outer-wrap .mobile-menu a,
.logo a,
li.mini-cart .cart-icon strong,
#header-outer-wrap .mobile-menu a.mobile-menu a,
.checkout-group h3,
.order-review h3,
#yith-searchsubmit .icon-search,
.mini-cart-item .cart_list_product_price,
.remove:hover i,
.support-icon,
.entry-meta a,
#order_review_heading,
.checkout-group h3,
.shop_table.cart td.product-name a,
a.shipping-calculator-button,
.widget_layered_nav li a:hover,
.widget_layered_nav_filters li a:hover,
.product_list_widget .text-info span,
.copyright-footer span,
#menu-shop-by-category li.active.menu-parent-item .nav-top-link::after,
.product_list_widget .product-title:hover,
.item-product-widget .product-meta .product-title a:hover,
.bread.nasa-breadcrumb-has-bg .row .breadcrumb-row a:hover,
.bread.nasa-breadcrumb-has-bg .columns .breadcrumb-row a:hover,
.group-blogs .blog_info .post-date span,
.header-type-1 ul.header-nav li a.nav-top-link:hover,
.widget_layered_nav li:hover a,
.widget_layered_nav_filters li:hover a,
.remove .icon-close:hover,
.absolute-footer .left .copyright-footer span,
.service-block .box .title .icon,
.service-block.style-3 .box .service-icon,
.contact-information .contact-text strong,
.nav-wrapper .header-nav li.root-item:hover > a,
.nav-wrapper .header-nav li.root-item a:hover,
.group-blogs .blog_info .read_more a:hover,
.mini-cart .nav-dropdown .cart_list .mini-cart-item .mini-cart-info .cart_list_product_quantity,
#top-bar .top-bar-nav li.color a,
.mini-cart.mini-cart-type-full .cart-link .cart-icon:hover:before,
.mini-cart-type-simple .cart-link .cart-icon:hover:before,
.absolute-footer li a:hover,
.nasa-recent-posts li .post-date,
.nasa-recent-posts .read-more a,
.team-member .member-details h3,
.shop_table .remove-product .icon-close:hover,
.absolute-footer ul.menu li a:hover,
.vertical-menu h4.section-title:before,
.nasa-instagram .username-text span,
.bread .breadcrumb-row h3 a:hover,
.bread .breadcrumb-row h3 .current,
.nasa-pagination.style-1 .page-number li span.current,
.nasa-pagination.style-1 .page-number li a.current,
.nasa-pagination.style-1 .page-number li a.nasa-current,
.nasa-pagination.style-1 .page-number li a:hover,
#vertical-menu-wrapper li.root-item:hover > a,
.widget.woocommerce li.cat-item a.nasa-active,
.widget.widget_categories li a.nasa-active,
.widget.widget_archive li a.nasa-active,
.nasa-filter-by-cat.nasa-active,
.nasa-table-search-wrapper tr td .nasa-root-cat-topbar-warp .nasa-root-cat li:hover a,
.nasa-table-search-wrapper tr td .nasa-root-cat-topbar-warp .nasa-root-cat li:hover a:before,
.nasa-table-search-wrapper tr td .nasa-root-cat-topbar-warp .nasa-root-cat li a.nasa-active,
.nasa-table-search-wrapper tr td .nasa-root-cat-topbar-warp .nasa-root-cat li a.nasa-active:before,
.widget .nasa-tag-cloud-ul li a:hover,
.widget .nasa-tag-cloud-ul li a.nasa-active:hover,
.product-info .stock.in-stock,
.category-page .sort-bar .select-wrapper select,
.category-page .sort-bar .select-wrapper select option,
#nasa-footer .nasa-contact-footer-custom h5,
#nasa-footer .nasa-contact-footer-custom h5 i,
.group-blogs .nasa-blog-info-slider .nasa-post-date,
li.menu-item.nasa-megamenu > .nav-dropdown > .div-sub > ul > li.menu-item a:hover,
.nasa-tag-cloud a.nasa-active:hover,
.html-text i,
ul.header-nav li.active a.nav-top-link,
ul li .nav-dropdown > ul > li:hover > a,
ul li .nav-dropdown > ul > li:hover > a:before,
ul li .nav-dropdown > ul > li .nav-column-links > ul > li a:hover,
ul li .nav-dropdown > ul > li .nav-column-links > ul > li:hover > a:before,
.topbar-menu-container ul li a:hover,
.header-account ul li a:hover,
.header-icons > li a:hover i,
.nasa-title span.nasa-first-word,
.nasa-tabs-content .nasa-tabs li.active a h5,
.nasa-tabs-content .nasa-tabs li:hover a h5,
.woocommerce-tabs .nasa-tabs li.active a h5,
.woocommerce-tabs .nasa-tabs li:hover a h5,
.nasa-tabs-content.nasa-slide-style .nasa-tabs li.active a h5,
.woocommerce-tabs.nasa-slide-style .nasa-tabs li.active a h5,
.nasa-sc-pdeal.nasa-sc-pdeal-block .nasa-sc-p-img .images-popups-gallery a.product-image .nasa-product-label-stock .label-stock,
.nasa-sc-pdeal.nasa-sc-pdeal-block .nasa-sc-p-info .nasa-sc-p-title h3 a:hover,
#nasa-footer .nasa-footer-contact .wpcf7-form label span.your-email:after,
#nasa-footer .widget_nav_menu ul li a:hover,
#nasa-footer .nasa-footer-bottom .widget_nav_menu ul li a:hover,
#nasa-footer .nasa-nav-sc-menu ul li a:hover,
#nasa-footer .nasa-footer-bottom .nasa-nav-sc-menu ul li a:hover,
.owl-carousel .owl-nav div:hover,
.item-product-widget.nasa-list-type-2 .product-meta .product-interactions .btn-wishlist .btn-link:hover .wishlist-icon,
.item-product-widget.nasa-list-type-2 .product-meta .product-interactions .btn-wishlist .btn-link:hover .quick-view-icon,
.item-product-widget.nasa-list-type-2 .product-meta .product-interactions .quick-view .btn-link:hover .wishlist-icon,
.item-product-widget.nasa-list-type-2 .product-meta .product-interactions .quick-view .btn-link:hover .quick-view-icon,
.item-product-widget.nasa-list-type-2 .product-meta .product-title a:hover,
#nasa-wishlist-sidebar .wishlist_sidebar .wishlist_table tbody tr .product-wishlist-info .info-wishlist .nasa-wishlist-title a:hover,
#nasa-wishlist-sidebar .wishlist_sidebar .wishlist_table tbody tr .product-remove a:hover i,
#cart-sidebar .cart_sidebar .cart_list .mini-cart-item .mini-cart-info a:hover,
#cart-sidebar .cart_sidebar .cart_list .mini-cart-item .item-in-cart:hover i,
.mini-cart.mini-cart-type-simple .cart-link .cart-icon:hover:before,
.product-interactions .btn-link:hover .wishlist-icon .pe-icon,
.product-interactions .btn-link:hover .wishlist-icon .nasa-icon,
.product-interactions .btn-link:hover .quick-view-icon .pe-icon,
.product-interactions .btn-link:hover .quick-view-icon .nasa-icon,
.product-interactions .btn-link:hover .compare-icon .pe-icon,
.product-interactions .btn-link:hover .compare-icon .nasa-icon,
.product-interactions .btn-link:hover .gift-icon .pe-icon,
.product-interactions .btn-link:hover .gift-icon .nasa-icon,
.product-interactions .btn-link:hover .add-to-cart-grid .pe-icon,
.product-interactions .btn-link:hover .add-to-cart-grid .nasa-icon,
.nasa-table-compare tr.stock td span,
.nasa-wrap-table-compare .nasa-table-compare tr.stock td span,
.item-product-widget.nasa-list-type-extra .product-meta .product-interactions .btn-wishlist-main-list:hover .pe-icon,
.item-product-widget.nasa-list-type-extra .product-meta .product-interactions .btn-wishlist-main-list:hover .nasa-icon,
.item-product-widget.nasa-list-type-main .product-interactions .btn-wishlist:hover .pe-icon,
.item-product-widget.nasa-list-type-main .product-interactions .btn-wishlist:hover .nasa-icon,
.nasa-login-register-warper #nasa-login-register-form .nasa-switch-form a,
.vertical-menu-container #vertical-menu-wrapper li.root-item:hover > a,
.vertical-menu-container .vertical-menu-wrapper li.root-item:hover > a,
.vertical-menu-container #vertical-menu-wrapper li.root-item:hover > a > i,
.vertical-menu-container .vertical-menu-wrapper li.root-item:hover > a > i,
#cart-sidebar .wishlist_sidebar .wishlist_table tbody tr .product-wishlist-info .info-wishlist .add-to-cart-wishlist .button-in-wishlist:hover,
#nasa-wishlist-sidebar .wishlist_sidebar .wishlist_table tbody tr .product-wishlist-info .info-wishlist .add-to-cart-wishlist .button-in-wishlist:hover,
.vertical-menu:hover h5.section-title span,
.vertical-menu:hover h5.section-title:before,
.topbar-menu-container .header-icons > li > a:hover .wishlist-number .nasa-text,
.topbar-menu-container .header-icons > li > a:hover .compare-number .nasa-text,
.header-type-1 #top-bar .topbar-menu-container ul li a:hover .wishlist-number .nasa-text,
.header-type-1 #top-bar .topbar-menu-container ul li a:hover .compare-number .nasa-text,
.header-type-1 #top-bar .header-icons > li a:hover .nasa-sl
{
    color: <?php echo esc_attr($color_primary); ?>;
}
.blog_shortcode_item .blog_shortcode_text h3 a:hover,
.main-navigation li.menu-item a:hover,
.widget-area ul li a:hover,
h1.entry-title a:hover,
.comments-area a,
.progress-bar .bar-meter .bar-number,
.product-item .info .name a:hover,
.wishlist_table td.product-name a:hover,
.product_list_widget .text-info a:hover,
.product-list .info .name:hover,
.product-info .compare:hover,
.product-info .compare:hover:before,
.product-info .yith-wcwl-add-to-wishlist:hover:before,
.product-info .yith-wcwl-add-to-wishlist:hover a,
.product-info .yith-wcwl-add-to-wishlist:hover .feedback,
li.menu-item.nasa-megamenu > .nav-dropdown > .div-sub > ul > li.menu-item a:hover:before,
#nasa-footer .widget_tag_cloud .nasa-tag-cloud a:hover,
#nasa-footer .widget_tag_cloud .nasa-tag-cloud a.nasa-active,
#nasa-footer .widget_tag_cloud .nasa-tag-products-cloud a:hover,
#nasa-footer .widget_tag_cloud .nasa-tag-products-cloud a.nasa-active,
ul.main-navigation li .nav-dropdown > ul > li .nav-column-links > ul > li a:hover,
.nasa-table-search-wrapper tr td .nasa-root-cat-topbar-warp .nasa-root-cat li:hover a,
rev-btn.Digi-Button
{
    color: <?php echo esc_attr($color_primary); ?> !important;
}
/* BACKGROUND */
.tabbed-content.pos_pills ul.tabs li.active a,
li.featured-item.style_2:hover a,
.nasa_hotspot,
.label-new.menu-item a:after,
.text-box-primary,
.navigation-paging a:hover,
.navigation-image a:hover,
.next-prev-nav .prod-dropdown > a:hover,
.widget_product_tag_cloud a:hover,
.nasa-tag-cloud a.nasa-active,
.custom-cart-count,
a.button.trans-button:hover,
.please-wait i,
li.mini-cart .cart-icon .cart-count,
.product-img .product-bg,
#submit:hover,
button:hover,
#submit:hover,
button:hover,
input[type="submit"]:hover,
.post-item:hover .post-date,
.blog_shortcode_item:hover .post-date,
.group-slider .sliderNav a:hover,
.support-icon.square-round:hover,
.entry-header .post-date-wrapper,
.entry-header .post-date-wrapper:hover,
.comment-inner .reply a:hover,
.social-icons .icon.icon_email:hover,
.widget_collapscat h3,
ul.header-nav li a.nav-top-link::before,
.sliderNav a span:hover,
.shop-by-category h3.section-title,
ul.header-nav li a.nav-top-link::before,
.custom-footer-1 .nasa-hr,
.products.list .product-interactions .yith-wcwl-add-button:hover,
ul.header-nav li a.nav-top-link::before,
.widget_collapscat h2,
.shop-by-category h2.widgettitle,
.rev_slider_wrapper .type-label-2,
.nasa-hr.primary-color,
.products.list .product-interactions .yith-wcwl-add-button:hover,
.pagination-centered .page-numbers a:hover,
.pagination-centered .page-numbers span.current,
.cart-wishlist .mini-cart .cart-link .cart-icon .products-number,
.load-more::before,
.products-arrow .next-prev-buttons .icon-next-prev:hover,
.widget_price_filter .ui-slider .ui-slider-handle:after,
#under-top-bar .header-icons li .products-number .nasa-sl,
#under-top-bar .header-icons li .wishlist-number .nasa-sl,
#under-top-bar .header-icons li .compare-number .nasa-sl,
.nasa-tabs-content .nasa-tabs li.active .nasa-hr,
.nasa-tabs-content .nasa-tabs li:hover .nasa-hr,
.woocommerce-tabs .nasa-tabs li.active .nasa-hr,
.woocommerce-tabs .nasa-tabs li:hover .nasa-hr,
.category-page .filter-tabs li.active i,
.category-page .filter-tabs li:hover i,
.collapses.active .collapses-title a:before,
.title-block .heading-title span:after,
.mini-cart.mini-cart-type-full .cart-link .products-number .nasa-sl,
.mini-cart.mini-cart-type-simple .cart-link .products-number .nasa-sl,
.header-icons > li .products-number .nasa-sl,
.header-icons > li .wishlist-number .nasa-sl,
.header-icons > li .compare-number .nasa-sl,
.search-dropdown .nasa-show-search-form .search-wrapper form .nasa-icon-submit-page,
.nasa-search-space .nasa-show-search-form .search-wrapper form .nasa-icon-submit-page,
.nasa-login-register-warper #nasa-login-register-form .login-register-close a:hover i:before,
.products-group.nasa-combo-slider .product-item.grid .nasa-product-bundle-btns .quick-view:hover,
#nasa-footer .social-icons li:hover span,
#nasa-footer .nasa-service-footer,
body .nasa-follow .icon:hover,
.header-type-1 .nasa-header-icons-type-1 .header-icons > li.nasa-icon-mini-cart a .icon-nasa-cart-3,
.header-type-1 .nasa-header-icons-type-1 .header-icons > li.nasa-icon-mini-cart a:hover .icon-nasa-cart-3,
.header-type-1 .nasa-header-icons-type-1 .header-icons > li.nasa-icon-mini-cart a .icon-nasa-cart-3:hover:before,
.search-dropdown.nasa-search-style-3 .nasa-show-search-form .search-wrapper form .nasa-icon-submit-page:before,
.nasa-search-space.nasa-search-style-3 .nasa-show-search-form .search-wrapper form .nasa-icon-submit-page:before,
.product_list_widget .product-interactions .quick-view:hover,
#cart-sidebar.style-1 a.nasa-sidebar-return-shop:hover,
#nasa-wishlist-sidebar.style-1 a.nasa-sidebar-return-shop:hover,
#cart-sidebar .cart_sidebar .btn-mini-cart a.btn-viewcart,
#cart-sidebar .cart_sidebar .btn-mini-cart a.btn-viewcart:hover,
.nasa-gift-featured-wrap .nasa-gift-featured-event:hover,
#nasa-popup .wpcf7 input[type="button"],
#nasa-popup .wpcf7 input[type="submit"],
#nasa-popup .wpcf7 input[type="button"]:hover,
#nasa-popup .wpcf7 input[type="submit"]:hover,
.nasa-products-special-deal .product-special-deals .product-deal-special-progress .deal-progress .deal-progress-bar,
.badge .badge-inner,
.badge-inner,
body .easypin-marker > .nasa-marker-icon-wrap .nasa-marker-icon-bg,
body .easypin-marker > .nasa-marker-icon-wrap .nasa-action-effect,
.nasa-slider-deal-vertical-extra-switcher .badge-inner.sale-label .inner-text
{
    background-color: <?php echo esc_attr($color_primary); ?>;
}
.button.trans-button.primary,
button.primary-color,
#nasa-popup .ninja-forms-form-wrap .ninja-forms-form .ninja-forms-all-fields-wrap .field-wrap .button,
.newsletter-button-wrap .newsletter-button
{
    background-color: <?php echo esc_attr($color_primary); ?> !important;
}
.search-dropdown .nasa-show-search-form .search-wrapper form select[name="product_cat"],
.nasa-search-space .nasa-show-search-form .search-wrapper form select[name="product_cat"]
{
    background-image: linear-gradient(45deg, transparent 50%, <?php echo esc_attr($color_primary); ?> 50%), linear-gradient(135deg, <?php echo esc_attr($color_primary); ?> 50%, transparent 50%);
}
.search-dropdown .nasa-show-search-form .search-wrapper form select[name="product_cat"]:focus,
.nasa-search-space .nasa-show-search-form .search-wrapper form select[name="product_cat"]:focus
{
    background-image: linear-gradient(45deg, <?php echo esc_attr($color_primary); ?> 50%, transparent 50%), linear-gradient(135deg, transparent 50%, <?php echo esc_attr($color_primary); ?> 50%);
}
/* BORDER COLOR */
.text-bordered-primary,
.add-to-cart-grid .cart-icon-handle,
.add-to-cart-grid.please-wait .cart-icon strong,
.navigation-paging a,
.navigation-image a,
.post.sticky,
.next-prev-nav .prod-dropdown > a:hover,
.iosSlider .sliderNav a:hover span,
.woocommerce-checkout form.login,
li.mini-cart .cart-icon strong,
li.mini-cart .cart-icon .cart-icon-handle,
.post-date,
.main-navigation .nav-dropdown ul,
.remove:hover i,
.support-icon.square-round:hover,
.widget_price_filter .ui-slider .ui-slider-handle,
h3.section-title span,
.social-icons .icon.icon_email:hover,
.button.trans-button.primary,
.seam_icon .seam,
.border_outner,	
.pagination-centered .page-numbers a:hover,
.pagination-centered .page-numbers span.current,
.owl-carousel .owl-nav div:hover,
.products.list .product-interactions .yith-wcwl-wishlistexistsbrowse a,
li.menu-item.nasa-megamenu > .nav-dropdown > .div-sub > ul > li.menu-item.megatop > hr.hr-nasa-megamenu,
.owl-carousel .owl-dots .owl-dot.active,
.owl-carousel .owl-dots .owl-dot.active:hover,
.category-page .filter-tabs li.active i,
.category-page .filter-tabs li:hover i,
.products-arrow .next-prev-buttons .icon-next-prev:hover,
.search-dropdown .nasa-show-search-form .search-wrapper form .nasa-icon-submit-page,
.nasa-search-space .nasa-show-search-form .search-wrapper form .nasa-icon-submit-page,
.item-product-widget.nasa-list-type-2:hover,
.products-group.nasa-combo-slider .product-item.grid .nasa-product-bundle-btns .quick-view:hover,
.nasa-table-compare tr.stock td span,
.nasa-tabs-content.nasa-slide-style .nasa-tabs li.nasa-slide-tab,
.woocommerce-tabs.nasa-slide-style .nasa-tabs li.nasa-slide-tab,
.nasa-wrap-table-compare .nasa-table-compare tr.stock td span,
.nasa-table-search-wrapper tr td .nasa-root-cat-topbar-warp .nasa-root-cat li:hover:before,
.vertical-menu-container #vertical-menu-wrapper li.root-item:hover > a:before,
.vertical-menu-container .vertical-menu-wrapper li.root-item:hover > a:before,
.product_list_widget .product-interactions .quick-view:hover,
#cart-sidebar.style-1 a.nasa-sidebar-return-shop:hover,
#nasa-wishlist-sidebar.style-1 a.nasa-sidebar-return-shop:hover,
#cart-sidebar .cart_sidebar .btn-mini-cart a.btn-viewcart,
.nasa-gift-featured-wrap .nasa-gift-featured-event:hover,
#nasa-wishlist-sidebar .wishlist_sidebar .wishlist_table tbody tr .product-wishlist-info .info-wishlist .add-to-cart-wishlist .button-in-wishlist:hover .add_to_cart_text,
body .easypin-marker > .nasa-marker-icon-wrap .nasa-marker-icon-bg,
body .easypin-marker > .nasa-marker-icon-wrap .nasa-marker-icon
{
    border-color: <?php echo esc_attr($color_primary); ?>;
}
.promo .sliderNav span:hover,
.remove .icon-close:hover
{
    border-color: <?php echo esc_attr($color_primary); ?> !important;
}
.tabbed-content ul.tabs li a:hover:after,
.tabbed-content ul.tabs li.active a:after
{
    border-top-color: <?php echo esc_attr($color_primary); ?>;
}
.collapsing.categories.list li:hover,
.please-wait,
#menu-shop-by-category li.active
{
    border-left-color: <?php echo esc_attr($color_primary); ?> !important;
}
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
.add_to_cart:hover {
    background-color: <?php echo esc_attr($color_primary); ?>;
    border-color: <?php echo esc_attr($color_primary); ?>;
    color: #FFF;
}

/* For mobile */
@media only screen and (max-width: 59.07692em) {
    html .product-item .info .product-summary {
        background-color: <?php echo esc_attr($color_primary); ?>;
    }
}
/* End Primary color =========================================== */
</style>
<?php
    if($return) {
        $css = ob_get_clean();
        $css = strip_tags($css);
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(': ', ':', $css);
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        return $css;
    }
}
endif;

/**
 * CSS override color for main menu
 */
if(!function_exists('digi_get_style_main_menu_color')) :
function digi_get_style_main_menu_color($bg_color = '', $text_color = '', $text_color_hover = '', $return = true) {
    if($bg_color == '' && $text_color == '' && $text_color_hover == '') {
        return '';
    }
    
    if($return) {
        ob_start();
    }
?><style type="text/css" rel="stylesheet">
/* Start override main menu color =========================================== */
<?php if ($bg_color != '') : ?>
body .wide-nav.nasa-bg-wrap
{
    background-color: <?php echo esc_attr($bg_color); ?>;
}
<?php
endif;

if ($text_color != '') : ?>
body .nav-wrapper .header-nav .root-item > a,
body .nasa-bg-wrap .nasa-vertical-header h5.section-title span,
body .nasa-bg-wrap .nasa-vertical-header h5.section-title:before
{
    color: <?php echo esc_attr($text_color); ?>;
}
<?php
endif;

if ($text_color_hover != '') : ?>
body .nav-wrapper .header-nav li.root-item > a:hover,
body .nav-wrapper .header-nav li.root-item:hover > a,
body .nasa-bg-wrap .nasa-vertical-header:hover h5.section-title span,
body .nasa-bg-wrap .nasa-vertical-header:hover h5.section-title:before
{
    color: <?php echo esc_attr($text_color_hover); ?>;
}
<?php
endif;
?>
/* End =========================================== */
</style>
<?php
    if($return) {
        $css = ob_get_clean();
        $css = strip_tags($css);
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(': ', ':', $css);
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        return $css;
    }
}
endif;

/**
 * CSS override color for header
 */
if(!function_exists('digi_get_style_header_color')) :
function digi_get_style_header_color($bg_color = '', $text_color = '', $text_color_hover = '', $return = true) {
    if($bg_color == '' && $text_color == '' && $text_color_hover == '') {
        return '';
    }
    
    if($return) {
        ob_start();
    }
?><style type="text/css" rel="stylesheet">
/* Start override header color =========================================== */
<?php if ($bg_color != '') : ?>
body #masthead
{
    background-color: <?php echo esc_attr($bg_color); ?>;
}
<?php
endif;

if ($text_color != '') : ?>
body #masthead .header-icons > li a,
body #masthead .header-icons > li .mini-cart.mini-cart-type-full .cart-link .cart-count
{
    color: <?php echo esc_attr($text_color); ?>;
}
body #masthead .header-icons > li .mini-cart.mini-cart-type-full .cart-link .cart-count .amount,
body .header-type-1 #masthead .nasa-header-icons-type-1 .header-icons > li .mini-cart.mini-cart-type-full .cart-link .products-number span
{
    color: <?php echo esc_attr($text_color); ?> !important;
}
<?php
endif;

if ($text_color_hover != '') : ?>
body #masthead .header-icons > li a:hover i,
body #masthead .mini-cart.mini-cart-type-full .cart-link .cart-icon:hover:before
{
    color: <?php echo esc_attr($text_color_hover); ?>;
}
<?php
endif;
?>
/* End =========================================== */
</style>
<?php
    if($return) {
        $css = ob_get_clean();
        $css = strip_tags($css);
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(': ', ':', $css);
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        return $css;
    }
}
endif;

/**
 * CSS override color for TOP BAR
 */
if(!function_exists('digi_get_style_topbar_color')) :
function digi_get_style_topbar_color($bg_color = '', $text_color = '', $text_color_hover = '', $return = true) {
    if($bg_color == '' && $text_color == '' && $text_color_hover == '') {
        return '';
    }
    
    if($return) {
        ob_start();
    }
?><style type="text/css" rel="stylesheet">
/* Start override topbar color =========================================== */
<?php if ($bg_color != '') : ?>
body #top-bar,
body .header-type-1 #top-bar
{
    background-color: <?php echo esc_attr($bg_color); ?>;
}
<?php
endif;

if ($text_color != '') : ?>
body #top-bar,
body #top-bar .topbar-menu-container ul li:after,
body #top-bar .topbar-menu-container ul li a,
body #top-bar .topbar-menu-container ul li a .pe7-icon,
body #top-bar .left-text,
body .header-type-1 #top-bar .left-text,
body .header-type-1 #top-bar,
body .header-type-1 #top-bar .topbar-menu-container ul li:after,
body .header-type-1 #top-bar .topbar-menu-container ul li a,
body .header-type-1 #top-bar .topbar-menu-container ul li a .pe7-icon,
body .header-type-1 #top-bar .left-text i,
body .header-type-1 #top-bar .header-icons > li a .compare-number .nasa-text,
body .header-type-1 #top-bar .header-icons > li a .wishlist-number .nasa-text,
body .header-type-1 #top-bar .header-icons > li a .nasa-icon,
body .header-type-1 #top-bar .header-icons > li a .compare-number .nasa-sl,
body .header-type-1 #top-bar .header-icons > li a .wishlist-number .nasa-sl
{
    color: <?php echo esc_attr($text_color); ?>;
}

body #top-bar .topbar-menu-container ul li:after,
body .header-type-1 #top-bar .topbar-menu-container ul li:after,
body .header-type-1 #top-bar .header-icons > li:last-child:after
{
    border-color: <?php echo esc_attr($text_color); ?>;
}
<?php
endif;

if ($text_color_hover != '') : ?>
body #top-bar .topbar-menu-container ul li a:hover,
body #top-bar .topbar-menu-container ul li a:hover .pe7-icon,
body .header-type-1 #top-bar .topbar-menu-container ul li a:hover i,
body .header-type-1 #top-bar .topbar-menu-container ul li a:hover .nasa-text,
body .header-type-1 #top-bar .topbar-menu-container ul li a:hover .compare-number .nasa-text,
body .header-type-1 #top-bar .topbar-menu-container ul li a:hover .wishlist-number .nasa-text,
body .header-type-1 #top-bar .topbar-menu-container ul li a:hover .nasa-sl,
body .header-type-1 #top-bar .topbar-menu-container ul li a:hover .nasa-sl:before,
body .header-type-1 #top-bar .topbar-menu-container ul li a:hover .nasa-sl:after
{
    color: <?php echo esc_attr($text_color_hover); ?>;
}
<?php
endif;
?>
/* End =========================================== */
</style>
<?php
    if($return) {
        $css = ob_get_clean();
        $css = strip_tags($css);
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(': ', ':', $css);
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        return $css;
    }
}
endif;

// **********************************************************************// 
// ! Dynamic - css
// **********************************************************************//
add_action('wp_enqueue_scripts', 'digi_add_dynamic_css', 999);
if (!function_exists('digi_add_dynamic_css')) :

    function digi_add_dynamic_css() {
        global $nasa_upload_dir;
        
        $upload_dir = !isset($nasa_upload_dir) ? wp_upload_dir() : $nasa_upload_dir;
        $dynamic_path = $upload_dir['basedir'] . '/nasa-dynamic';
        
        if (is_file($dynamic_path . '/dynamic.css')) {
            global $nasa_opt;
            $version = isset($nasa_opt['nasa_dynamic_t']) ? $nasa_opt['nasa_dynamic_t'] : null;
            
            // Dynamic Css
            wp_enqueue_style('digi-style-dynamic', $upload_dir['baseurl'] . '/nasa-dynamic/dynamic.css', array('digi-style'), $version, 'all');
        }
    }

endif;

// **********************************************************************// 
// ! Dynamic - Page override primary color - css
// **********************************************************************//
add_action('wp_enqueue_scripts', 'digi_page_override_style', 1000);
if(!function_exists('digi_page_override_style')) :
    function digi_page_override_style() {
        if(!wp_style_is('digi-style-dynamic')) {
            return;
        }
        
        global $wp_query, $nasa_opt;

        /**
         * color_primary
         */
        $flag_override_color = get_post_meta($wp_query->get_queried_object_id(), '_nasa_pri_color_flag', true);
        $color_primary_css = $page_css = '';
        if ($flag_override_color) :
            $color_primary = get_post_meta($wp_query->get_queried_object_id(), '_nasa_pri_color', true);
            $color_primary_css = $color_primary ? digi_get_style_primary_color($color_primary) : '';
        endif;
        
        /**
         * color for header
         */
        $bg_color = get_post_meta($wp_query->get_queried_object_id(), '_nasa_bg_color_header', true);
        $text_color = get_post_meta($wp_query->get_queried_object_id(), '_nasa_text_color_header', true);
        $text_color_hover = get_post_meta($wp_query->get_queried_object_id(), '_nasa_text_color_hover_header', true);
        $page_css .= digi_get_style_header_color($bg_color, $text_color, $text_color_hover);
        
        /**
         * color for top bar
         */
        if(!isset($nasa_opt['topbar_show']) || $nasa_opt['topbar_show']) {
            $bg_color = get_post_meta($wp_query->get_queried_object_id(), '_nasa_bg_color_topbar', true);
            $text_color = get_post_meta($wp_query->get_queried_object_id(), '_nasa_text_color_topbar', true);
            $text_color_hover = get_post_meta($wp_query->get_queried_object_id(), '_nasa_text_color_hover_topbar', true);
            $page_css .= digi_get_style_topbar_color($bg_color, $text_color, $text_color_hover);
        }
        
        /**
         * color for main menu
         */
        $bg_color = get_post_meta($wp_query->get_queried_object_id(), '_nasa_bg_color_main_menu', true);
        $text_color = get_post_meta($wp_query->get_queried_object_id(), '_nasa_text_color_main_menu', true);
        $text_color_hover = get_post_meta($wp_query->get_queried_object_id(), '_nasa_text_color_hover_main_menu', true);
        $page_css .= digi_get_style_main_menu_color($bg_color, $text_color, $text_color_hover);

        $dinamic_css = $color_primary_css . $page_css;
        if($dinamic_css != '') {
            wp_add_inline_style('digi-style-dynamic', $dinamic_css);
        }
    }
endif;