jQuery(window).trigger('resize').trigger('scroll');
// ---------------------------------------------- //
// Global Read-Only Variables (DO NOT CHANGE!)
// ---------------------------------------------- //
var doc = document.documentElement;
doc.setAttribute('data-useragent', navigator.userAgent);

var wow_enable = false,
    fullwidth = 1200,
    iOS = check_iOS(),
    _event = (iOS) ? 'click, mousemove' : 'click',
    globalTimeout = null,
    load_flag = false,
    page_load = 1,
    _sidebar_show = false,
    _single_variations = [],
    _lightbox_variations = [];

/* =========== Document ready ==================== */
jQuery(document).ready(function($){
"use strict";
$(window).stellar();

// Init Wow effect
if($('input[name="nasa-enable-wow"]').length === 1 && $('input[name="nasa-enable-wow"]').val() === '1') {
    wow_enable = true;
    $('body').addClass('nasa-enable-wow');
    new WOW({mobile: false}).init();
}

$('body #nasa-before-load').fadeOut(1000);

if(typeof nasa_ajax_setup === 'undefined'){
    $.ajaxSetup({
        data: {
            context: 'frontend'
        }
    });
}

/**
 * Load content static
 */
var _urlAjaxStaticContent = null;
if(
    typeof nasa_ajax_params !== 'undefined' &&
    typeof nasa_ajax_params.wc_ajax_url !== 'undefined'
) {
    _urlAjaxStaticContent = nasa_ajax_params.wc_ajax_url.toString().replace('%%endpoint%%', 'nasa_static_content');
}

if(_urlAjaxStaticContent) {
    var _data_static_content = {};
    if($('#nasa-view-compare-product').length === 1) {
        _data_static_content.compare = true;
    }
    $.ajax({
        // url : ajaxurl,
        url : _urlAjaxStaticContent,
        type: 'post',
        dataType: 'json',
        data: _data_static_content,
        beforeSend: function(){

        },
        success: function(result){
            if(typeof result !== 'undefined') {
                $.each(result, function (key, value) {
                    if($(key).length > 0) {
                        $(key).replaceWith(value);
                        
                        if (key === '#nasa-wishlist-sidebar-content') {
                            initWishlistIcons($);
                        }
                    }
                });
            }
        }
    });
}

if($('#wpadminbar').length > 0) {
    $("head").append('<style type="text/css" media="screen">#wpadminbar {position: fixed !important;}</style>');
    var height_adminbar = $('#wpadminbar').height();
    $('#cart-sidebar, #nasa-wishlist-sidebar, #nasa-viewed-sidebar, .col-sidebar').css({'top' : height_adminbar});
    $('.fixed-header-area').css({'margin-top' : height_adminbar});
    
    $(window).resize(function() {
        height_adminbar = $('#wpadminbar').height();
        $('#cart-sidebar, #nasa-wishlist-sidebar, #nasa-viewed-sidebar, .col-sidebar').css({'top' : height_adminbar});
        $('.fixed-header-area').css({'margin-top' : height_adminbar});
    });
}

// Fix vertical mega menu
if($('#nasa-menu-vertical-header .vertical-menu-wrapper').length > 0){
    $('#nasa-menu-vertical-header .vertical-menu-wrapper').attr('data-over', '0');

    var width_default = 200;
    var _h_vertical = $('#nasa-menu-vertical-header .vertical-menu-container').height();
    $('#nasa-menu-vertical-header .vertical-menu-wrapper .nasa-megamenu').find('>.nav-dropdown').each(function(){
        $(this).css({'width': 0});
        $(this).find('>.div-sub').css({'min-height': _h_vertical});
    });

    $('body').on('mousemove', '#nasa-menu-vertical-header .vertical-menu-wrapper .nasa-megamenu', function(){
        var _this = $(this);
        $('#nasa-menu-vertical-header .vertical-menu-wrapper .nasa-megamenu').removeClass('nasa-curent-hover');
        $(_this).addClass('nasa-curent-hover');

        var _w_mega, _w_mega_df, _w_ss;
        var total_w = $('#nasa-menu-vertical-header').parents('.row').width();
        
        $('#nasa-menu-vertical-header .vertical-menu-wrapper .nasa-megamenu').each(function(){
            var _this = $(this);
            
            var current_w = $(_this).outerWidth();
            _w_mega = _w_mega_df = total_w - current_w;
        
            if($(_this).hasClass('cols-5') || $(_this).hasClass('fullwidth')){
                _w_mega = _w_mega - 20;
            } else {
                if($(_this).hasClass('cols-2')){
                    _w_mega = _w_mega / 5 * 2 + 50;
                    _w_ss = width_default * 2;
                    _w_mega = (_w_ss > _w_mega && _w_ss < _w_mega_df) ? _w_ss : _w_mega;
                }
                else if($(_this).hasClass('cols-3')){
                    _w_mega = _w_mega / 5 * 3 + 50;
                    _w_ss = width_default * 3;
                    _w_mega = (_w_ss > _w_mega && _w_ss < _w_mega_df) ? _w_ss : _w_mega;
                }
                else if($(_this).hasClass('cols-4')){
                    _w_mega = _w_mega / 5 * 4 + 50;
                    _w_ss = width_default * 4;
                    _w_mega = (_w_ss > _w_mega && _w_ss < _w_mega_df) ? _w_ss : _w_mega;
                }
            }

            if($(_this).hasClass('nasa-curent-hover')){
                var _init = $('#nasa-menu-vertical-header .vertical-menu-wrapper').attr('data-over');
                if(_init === '0'){
                    $('#nasa-menu-vertical-header .vertical-menu-wrapper').attr('data-over', '1');
                    $(_this).find('>.nav-dropdown').css({'width': 0}).animate({'width': _w_mega}, 50);
                }else{
                    $(_this).find('>.nav-dropdown').css({'width': _w_mega});
                }
            } else {
                $(_this).find('>.nav-dropdown').css({'width': _w_mega});
            }
        });
    });

    $('body').on('mouseover', '#nasa-menu-vertical-header .vertical-menu-wrapper .menu-item-has-children.default-menu', function(){
        var _init = $('#nasa-menu-vertical-header .vertical-menu-wrapper').attr('data-over');
        if(_init === '0'){
            $('#nasa-menu-vertical-header .vertical-menu-wrapper').attr('data-over', '1');
            $(this).find('> .nav-dropdown > .div-sub > .sub-menu').css({'width': 0}).animate({'width': width_default}, 150);
        } else {
            $(this).find('> .nav-dropdown > .div-sub > .sub-menu').css({'width': width_default});
        }

        var _w_mega, _w_mega_df, _w_ss;
        var total_w = $('#nasa-menu-vertical-header').parents('.row').width();
        
        $('#nasa-menu-vertical-header .vertical-menu-wrapper .nasa-megamenu').each(function(){
            var _this = $(this);
            
            var current_w = $(_this).outerWidth();
            _w_mega = _w_mega_df = total_w - current_w;
            
            if($(_this).hasClass('cols-5') || $(_this).hasClass('fullwidth')){
                _w_mega = _w_mega - 20;
            } else {
                if($(_this).hasClass('cols-2')){
                    _w_mega = _w_mega / 5 * 2 + 50;
                    _w_ss = width_default * 2;
                    _w_mega = (_w_ss > _w_mega && _w_ss < _w_mega_df) ? _w_ss : _w_mega;
                }
                else if($(_this).hasClass('cols-3')){
                    _w_mega = _w_mega / 5 * 3 + 50;
                    _w_ss = width_default * 3;
                    _w_mega = (_w_ss > _w_mega && _w_ss < _w_mega_df) ? _w_ss : _w_mega;
                }
                else if($(_this).hasClass('cols-4')){
                    _w_mega = _w_mega / 5 * 4 + 50;
                    _w_ss = width_default * 4;
                    _w_mega = (_w_ss > _w_mega && _w_ss < _w_mega_df) ? _w_ss : _w_mega;
                }
            }
            $(_this).find('>.nav-dropdown').css({'width': _w_mega});
        });

    });
    
    $('body').on('mouseleave', '#nasa-menu-vertical-header .vertical-menu-wrapper', function(){
        $('#nasa-menu-vertical-header .vertical-menu-wrapper').attr('data-over', '0');
        $('#nasa-menu-vertical-header .vertical-menu-wrapper .nasa-megamenu > .nav-dropdown').css({'width': 0});
        $('#nasa-menu-vertical-header .vertical-menu-wrapper .menu-item-has-children.default-menu > .nav-dropdown > .div-sub > .sub-menu').css({'width': 0});
    });
}

/**
 * Init menu vertical for mobile
 */
initMainMenuVertical($);
if($('.nasa-mobile-menu_toggle').length > 0) {
    $('body').on('click', '.nasa-mobile-menu_toggle', function(){
        if($('#mobile-navigation').attr('data-show') !== '1') {
            $('#nasa-menu-sidebar-content').css({'z-index': 9999});
            $('.black-window').show().addClass('desk-window').addClass('nasa-transparent');
            $('#nasa-menu-sidebar-content').show().animate({left: 0}, 800);
            $('#mobile-navigation').attr('data-show', '1');
        } else {
            $('.black-window').click();
        }
    });
}

// Accordion menu
$('body').on('click', '.nasa-menu-accordion .li_accordion > a.accordion', function(e) {
    e.preventDefault();
    var ths = $(this).parent();
    var cha = $(ths).parent();
    if(!$(ths).hasClass('active')) {
        var c = $(cha).children('li.active');
        $(c).removeClass('active').children('.nav-dropdown-mobile').css({height:'auto'}).slideUp(300);
        $(ths).children('.nav-dropdown-mobile').slideDown(300).parent().addClass('active');
        $(c).find('> a.accordion > span').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
        $(this).find('span').removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
    } else {
        $(ths).find('>.nav-dropdown-mobile').slideUp(300).parent().removeClass('active');
        $(this).find('span').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
    }
    return false;
});

if($('.nasa-accordion .li_accordion > a.accordion').length > 0){
    $('body').on('click', '.nasa-accordion .li_accordion > a.accordion', function() {
        var _show = $(this).attr('data-class_show'); // 'pe-7s-plus'
        var _hide = $(this).attr('data-class_hide'); // 'pe-7s-less'
        var ths = $(this).parent();
        var cha = $(ths).parent();
        if(!$(ths).hasClass('active')) {
            $(cha).removeClass('current-cat-parent').removeClass('current-cat');
            var c = $(cha).children('li.active');
            $(c).removeClass('active').children('.children').slideUp(300);
            $(ths).addClass('active').children('.children').slideDown(300);
            $(c).find('>a.accordion>span').removeClass(_hide).addClass(_show);
            $(this).find('span').removeClass(_show).addClass(_hide);
        } else {
            $(ths).removeClass('active').children('.children').slideUp(300);
            $(this).find('span').removeClass(_hide).addClass(_show);
        }
        return false;
    });
}

$('body').on('click', '.product-summary .quick-view', function(){
    var product_item = $(this).parents('.product-item');
    if(!$(product_item).hasClass('nasa-quickview-special')) {
        var item = $(product_item).find('.inner-wrap');
        $(item).find('.product-inner').css({opacity: 0.3});
        $(item).find('.product-inner').after('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
    } else {
        $(product_item).append('<div class="nasa-loader" style="top:50%"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
    }
});

$('body').on('click', '.product_list_widget .quick-view', function(){
    $(this).parents('.item-product-widget').find('.images').append('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
});

/*
 * Quick view
 */
$('body').on('click', '.quick-view', function(e){
    var _urlAjax = null;
    if(
        typeof nasa_ajax_params !== 'undefined' &&
        typeof nasa_ajax_params.wc_ajax_url !== 'undefined'
    ) {
        _urlAjax = nasa_ajax_params.wc_ajax_url.toString().replace('%%endpoint%%', 'nasa_quick_view');
    }
    
    if(_urlAjax) {
        var _this = $(this);

        var _wrap = $(_this).parents('.product-item'),
            product_item = $(_wrap).find('.inner-wrap'),
            product_img = $(product_item).find('.product-img'),
            product_id = $(_this).attr('data-prod'),
            _head_type = $(_this).attr('data-head_type'),
            _wishlist = ($(_this).attr('data-from_wishlist') === '1') ? '1' : '0';

        if($(_wrap).length <= 0) {
            _wrap = $(_this).parents('.item-product-widget');
        }

        if($(_wrap).length <= 0) {
            _wrap = $(_this).parents('.wishlist-item-warper');
        }

        var data = {
            action: 'nasa_quickview',
            product: product_id,
            head_type: _head_type,
            nasa_wishlist: _wishlist
        };

        $.post(_urlAjax, data, function(response) {
            $.magnificPopup.open({
                mainClass: 'my-mfp-zoom-in',
                items: {
                    src: '<div class="product-lightbox">' + response.content + '</div>',
                    type: 'inline'
                },
                callbacks: {
                    afterClose: function() {
                        var buttons = $(_this).parents('.product-summary');
                        $(buttons).addClass('hidden-tag');
                        setTimeout(function(){
                            $(buttons).removeClass('hidden-tag');
                        }, 100);
                    }
                }
            });

            if($(_this).hasClass('nasa-view-from-wishlist')){
                $('.wishlist-item').animate({opacity: 1}, 500);
                $('.wishlist-close a').click();
            }

            $(_wrap).find('.nasa-loader, .please-wait, .color-overlay').remove();
            if($(product_img).length > 0 && $(product_item).length > 0) {
                $(product_img).removeAttr('style');
                $(product_item).find('.product-inner').animate({opacity: 1}, 500);
            }

            var formLightBox = $('.product-lightbox').find('.variations_form');
            if($(formLightBox).find('.single_variation_wrap').length === 1) {
                $(formLightBox).find('.single_variation_wrap').hide();
                $(formLightBox).wc_variation_form_lightbox(response.mess_unavailable);
                $(formLightBox).find('select').change();
                if($('input[name="nasa_attr_ux"]').length === 1 && $('input[name="nasa_attr_ux"]').val() === '1') {
                    $(formLightBox).nasa_attr_ux_variation_form();
                }
            }

            setTimeout(function() {
                $('.main-image-slider').owlCarousel({
                    items: 1,
                    loop: false,
                    nav: true,
                    autoplay: false,
                    autoplaySpeed: 500,
                    dots: true,
                    autoplayTimeout: 3000,
                    autoplayHoverPause: true,
                    responsiveClass: true,
                    navText: ["", ""],
                    navSpeed: 500
                });
            }, 600);

            setTimeout(function() {
                var _h_r = $('.product-lightbox .product-lightbox-inner').height();
                var _h_l = $('.product-lightbox .product-img').height();
                if(_h_l > 0 && _h_r > _h_l) {
                    $('.product-lightbox .product-img').css({'position': 'relative', 'top': (_h_r - _h_l) / 2});
                }
            }, 2600);

            loadingCarousel($);

            loadCountDown($);
        }, 'json');
    }
    
    e.preventDefault();
});

/* Product Gallery Popup */
if($('a.product-lightbox-btn').length > 0 || $('a.product-video-popup').length > 0){
    $('.main-images').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: '<div class="please-wait dark"><span></span><span></span><span></span></div>',
        removalDelay: 300,
        closeOnContentClick: true,
        gallery: {
            enabled: true,
            navigateByImgClick: false,
            preload: [0, 1]
        },
        image: {
            verticalFit: false,
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
        },
        callbacks: {
            beforeOpen: function() {
                var productVideo = $('.product-video-popup').attr('href');

                if(productVideo){
                    // Add product video to gallery popup
                    this.st.mainClass = 'has-product-video';
                    var galeryPopup = $.magnificPopup.instance;
                    galeryPopup.items.push({
                        src: productVideo,
                        type: 'iframe'
                    });

                    galeryPopup.updateItemHTML();
                }
            },
            open: function() {
                
            }
        }
    });

    $('body').on('click', '.product-lightbox-btn', function(e){
        $('.product-images-slider').find('.owl-item.active a').click();
        e.preventDefault();
    });

    /* Product Video Popup */
    $('body').on('click', "a.product-video-popup", function(e){
        $('.product-images-slider').find('.first a').click();
        var galeryPopup = $.magnificPopup.instance;
        galeryPopup.prev();
        e.preventDefault();
    });
};

$("*[id^='attachment'] a, .entry-content a[href$='.jpg'], .entry-content a[href$='.jpeg']").magnificPopup({
    type: 'image',
    tLoading: '<div class="please-wait dark"><span></span><span></span><span></span></div>',
    closeOnContentClick: true,
    mainClass: 'my-mfp-zoom-in',
    image: {
        verticalFit: false
    }
});

$(".gallery a[href$='.jpg'],.gallery a[href$='.jpeg'],.featured-item a[href$='.jpeg'],.featured-item a[href$='.gif'],.featured-item a[href$='.jpg'], .page-featured-item .slider > a, .page-featured-item .page-inner a > img, .gallery a[href$='.png'], .gallery a[href$='.jpeg'], .gallery a[href$='.gif']").parent().magnificPopup({
    delegate: 'a',
    type: 'image',
    tLoading: '<div class="please-wait dark"><span></span><span></span><span></span></div>',
    mainClass: 'my-mfp-zoom-in',
    gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0,1]
    },
    image: {
        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
    }
});

// **********************************************************************// 
// ! Fixed header
// **********************************************************************//
// var _oldTop = 0;
var _menuHeight = $('.mobile-menu').length > 0 ? $('.mobile-menu').height() + 50 : 0;
var headerHeight = $('.header-wrapper').height() + 50;
$(window).scroll(function(){
    var scrollTop = $(this).scrollTop();
    
    if($('input[name="nasa_fixed_single_add_to_cart"]').length && $('input[name="nasa_fixed_single_add_to_cart"]').val() === '1') {
        if($('.nasa-product-details-page .single_add_to_cart_button').length) {
            var addToCart = $('.nasa-product-details-page .product-details') || $('.nasa-product-details-page .single_add_to_cart_button');
            var addToCartOffset = $(addToCart).offset();
            
            if(scrollTop >= addToCartOffset.top) {
                if(!$('body').hasClass('has-nasa-cart-fixed')) {
                    $('body').addClass('has-nasa-cart-fixed');
                }
            } else {
                $('body').removeClass('has-nasa-cart-fixed');
            }
        }
    }
    
    if ($('body').find('.fixNav-enabled').length > 0) {
        var fixedHeader = $('.fixed-header-area');
        if(scrollTop > headerHeight){
            if(!fixedHeader.hasClass('fixed-already')) {
                fixedHeader.stop().addClass('fixed-already');
            }
        } else {
            if(fixedHeader.hasClass('fixed-already')) {
                fixedHeader.stop().removeClass('fixed-already');
            }
        }
    }
    
    if($('.nasa-nav-extra-warp').length > 0) {
        if(scrollTop > headerHeight){
            if(!$('.nasa-nav-extra-warp').hasClass('nasa-show')) {
                $('.nasa-nav-extra-warp').addClass('nasa-show');
            }
        } else {
            if($('.nasa-nav-extra-warp').hasClass('nasa-show')) {
                $('.nasa-nav-extra-warp').removeClass('nasa-show');
            }
        }
    }
    
    if(scrollTop <= headerHeight && $('#mobile-navigation').attr('data-show') === '1' && $(window).width() > 945){
        $('.black-window').click();
    }
    
    /* Back to Top */
    if ($('#nasa-back-to-top').length > 0) {
        var _height_win = $(window).height() / 2;
        if(scrollTop > _height_win){
            var _animate = $('#nasa-back-to-top').attr('data-wow');
            $('#nasa-back-to-top').show().css({'visibility': 'visible', 'animation-name': _animate}).removeClass('animated').addClass('animated');
        } else {
            $('#nasa-back-to-top').hide();
        }
    }
    
    /* Menu mobile */
    if ($('.mobile-menu').length > 0) {
        //if((_menuHeight + 50) < scrollTop && scrollTop < _oldTop) {
        if((_menuHeight + 50) < scrollTop) {
            if(!$('.mobile-menu').hasClass('nasa-mobile-fixed')) {
                var height_adminbar = $('#wpadminbar').length > 0 ? $('#wpadminbar').height() : 0;
                $('.mobile-menu').addClass('nasa-mobile-fixed');
                $('.mobile-menu').css({'top': 0 + height_adminbar});
            }
        }
        else {
            if($('.mobile-menu').hasClass('nasa-mobile-fixed')) {
                $('.mobile-menu').removeClass('nasa-mobile-fixed').removeAttr('style');
            }
        }
        
        //_oldTop = scrollTop;
    }
});

/**
 * Back to Top
 */
$('body').on('click', '#nasa-back-to-top', function() {
    $('html, body').animate({scrollTop: 0}, 800);
});

/**
 * Main menu Reponsive
 */
loadReponsiveMainMenu($);

// **********************************************************************// 
// ! Header slider overlap for Transparent
// **********************************************************************//
$(window).resize(function() {
    var headerWrapper = $('.header-wrapper');
    var bodyWidth = $('body').width();
    if(headerWrapper.hasClass('header-transparent')) {
        var headerHeight = headerWrapper.height();
        var nasa_sc_carousel = $('.nasa-sc-carousel-warper').first();
        if(bodyWidth < 768) {
            headerHeight = 0;
        }
        nasa_sc_carousel.css({
            'marginTop' : - headerHeight
        });
    }

    // Fix Sidebar Mobile, Search Mobile display switch to desktop
    var desk = $('.black-window').hasClass('desk-window');
    if(bodyWidth > 945 && !desk) {
        if($('.col-sidebar').length > 0){
            if($('.nasa-togle-topbar').length > 0) {
                var data_show = $('.nasa-togle-topbar').attr('data-filter');
                if(data_show === '1') {
                    $('.col-sidebar').removeAttr('style');
                } else {
                    $('.col-sidebar').hide();
                }
            } else {
                $('.col-sidebar').removeAttr('style');
            }
        }
        if($('.warpper-mobile-search').length > 0 && !$('.warpper-mobile-search').hasClass('show-in-desk')){
            $('.warpper-mobile-search').hide();
        }
        if($('.black-window').length > 0){
            $('.black-window').hide();
        }
    }
    
    /**
     * Main menu Reponsive
     */
    loadReponsiveMainMenu($);

    /* Fix width menu vertical =============================================== */
    if($('.wide-nav .nasa-vertical-header').length > 0) {
        var _v_width = $('.wide-nav .nasa-vertical-header').width();
        _v_width = _v_width < 280 ? 280: _v_width;
        $('.wide-nav .vertical-menu-container').css({'width': _v_width});
    }
    
    /* Fix tab able nasa-slide-style ========================================= */
    if($('.nasa-slide-style').length > 0) {
        var _width;
        $('.nasa-slide-style').each(function() {
            var _this = $(this);
            _width = 0;
            $(_this).find('.nasa-tabs .nasa-tab').each(function() {
                _width += $(this).outerWidth();
            });
            
            if(_width > $(_this).width()) {
                if(!$(_this).find('.nasa-tabs .nasa-tab').hasClass('nasa-block')) {
                    $(_this).find('.nasa-tabs .nasa-tab').addClass('nasa-block');
                }
            } else {
                $(_this).find('.nasa-tabs .nasa-tab').removeClass('nasa-block');
            }
        });
    }
    
    var _height_adminbar = $('#wpadminbar').length > 0 ? $('#wpadminbar').height() : 0;
    if(_height_adminbar > 0 && $('#mobile-navigation').length === 1) {
        $('#nasa-menu-sidebar-content').css({'top': _height_adminbar});
        
        if($('#mobile-navigation').attr('data-show') === '1' && $(window).width() > 945) {
            var _scrollTop = $(window).scrollTop();
            var _headerHeight = $('.header-wrapper').height() + 50;
            if(_scrollTop <= _headerHeight){
                $('.black-window').click();
            }
        }
    }
    
    if ($('#mobile-navigation').attr('data-show') !== '1' && $('#nasa-menu-sidebar-content').length > 0) {
        var _w_wrap = $('#nasa-menu-sidebar-content').outerWidth().toString();
        $('#nasa-menu-sidebar-content').css({'left': '-' + _w_wrap + 'px'});
    }
    
    /* Fix height for full width to side =================================== */
    loadHeightFullWidthToSide($);
});
/* Fix height for full width to side =================================== */
loadHeightFullWidthToSide($);

/* Fix width menu vertical =============================================== */
if($('.wide-nav .nasa-vertical-header').length > 0){
    var _v_width = $('.wide-nav .nasa-vertical-header').width();
    _v_width = _v_width < 280 ? 280: _v_width;
    $('.wide-nav .vertical-menu-container').css({'width': _v_width});
    if ($('.wide-nav .vertical-menu-container.nasa-allways-show').length) {
        $('.wide-nav .vertical-menu-container.nasa-allways-show').show();
    }
}

/***** Progress Bar *****/
if ($('.progress-bar').length > 0) {
    $('.progress-bar').each(function(){
        var meter = $(this).find('.bar-meter');
        var number = $(this).find('.bar-number');
        var _per = $(meter).attr('data-meter');
        $(this).waypoint(function() {
            $(meter).css({'width': 0, 'max-width': _per + '%'});
            $(meter).delay(50).animate({width : _per + '%'}, 400);
            $(number).delay(400).show();
            setTimeout(function(){
                $(number).css('opacity', 1);
            }, 400);
        },
        {
            offset : _per + '%',
            triggerOnce : true
        });
    });
}

// For demo
if($('.show-theme-options').length > 0){
    $('body').on('click', '.show-theme-options', function(){
        $(this).parent().toggleClass('open');
        $(window).resize();

        return false;
    });
}

if($('.wide-button').length > 0){
    $('body').on('click', '.wide-button', function(){
        $('body').removeClass('boxed');
        $(this).addClass('active');
        $('.config-options').find('.ss-content .boxed-button').removeClass('active');
        $.cookie('layout', null, {path: '/'});
        $(window).resize();
        $('.ss-patterns-content').fadeOut(500);
        $('.nasa-sc-carousel').resize();
    });
}

if($('.boxed-button').length > 0){
    var boxed = $.cookie('layout');
    if(boxed === 'boxed'){
        $('.ss-patterns-content').show();
    }
    
    $('body').on('click', '.boxed-button', function(){
        $('body').addClass('boxed');
        $(this).addClass('active');
        $('.config-options').find('.ss-content .wide-button').removeClass('active');
        $.cookie('layout', 'boxed', {path: '/'});
        $(window).resize();
        $('.ss-patterns-content').fadeIn(500);
        $('.nasa-sc-carousel').resize();
    });
}

if($('.ss-color').length > 0){
    $('body').on('click', '.ss-color', function(){
        var datastyle = $(this).attr('data-style');
        $('head').append('<link rel="stylesheet" href="' + datastyle + '" type="text/css" />');
        if (($.cookie('data-style') != null) && ($.cookie('data-style') != datastyle)){
            $.cookie('data-style', null, { path: '/' });
        }
        $.cookie('data-style', datastyle, {path: '/'});
    });
}

if ($.cookie('data-style') != null){
    $('head').append('<link rel="stylesheet" href="' + $.cookie('data-style') + '" type="text/css" />');
}

if($('.ss-image').length > 0){
    $('body').on('click', '.ss-image', function(){
        var pattern = $(this).attr('data-pattern');
        $('html').css({"background-image":"url('" + pattern + "')", "background-attachment":"fixed"});
        $('body').css("background-color", "transparent");
        if(($.cookie('data-bg') != null) && ($.cookie('data-bg') !== pattern)){
            $.cookie('data-bg', null, { path: '/' });
        }
        $.cookie('data-bg',pattern,{path: '/'});
    });
}

if ($.cookie('data-bg') != null){
    $('html').css({"background-image": "url('" + $.cookie('data-bg') + "')", "background-attachment": "fixed"});
    $('body').css("background-color", "transparent");
}
// End For demo

if($('.collapses .collapses-title a').length > 0){
    $('body').on('click', '.collapses .collapses-title a', function() {
        var g = $(this).parents('.collapses-group');
        var t = $(this).parents('.collapses');
        if(!$(t).hasClass('active')) {
            var c = $(g).find('.collapses.active');
            $(c).removeClass('active').find('.collapses-inner').slideUp(200);
            $(t).addClass('active').find('.collapses-inner').slideDown(200);
        } else {
            $(t).removeClass('active').find('.collapses-inner').slideUp(200);
        }
        return false;
    });
}

if($('.nasa-accordions-content .nasa-accordion-title a').length > 0){
    $('body').on(_event, '.nasa-accordions-content .nasa-accordion-title a', function() {
        var warp = $(this).parents('.nasa-accordions-content');
        var _id = $(this).attr('data-id');
        if(!$(this).hasClass('active')){
            $(warp).find('.nasa-accordion-title a').removeClass('active');
            $(warp).find('.nasa-panel.active').removeClass('active').slideUp(200);
            $('#nasa-secion-' + _id).addClass('active').slideDown(200);
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
            $('#nasa-secion-' + _id).removeClass('active').slideUp(200);
        }
        return false;
    });
}

// Tabable
if($('.nasa-tabs-content ul.nasa-tabs li a').length > 0){
    $('body').on(_event, '.nasa-tabs-content ul.nasa-tabs li a', function(e){
        e.preventDefault();
        var _this = $(this);
        if(!$(_this).parent().hasClass('active')){
            var _root = $(_this).parents('.nasa-tabs-content');
            var currentTab = $(_this).attr('data-id');
            var show = $(_this).parent().attr('data-show');
            $(_root).find('ul li').removeClass('active');
            $(_this).parent().addClass('active');
            $(_root).find('div.nasa-panel').removeClass('active').hide();
            $(currentTab).addClass('active').show();
            
            if($(_root).hasClass('nasa-slide-style')) {
                nasa_tab_slide_style($, _root, 500);
            }
            
            var nasa_slider = $(currentTab).find('.group-slider');
            var nasa_deal = $(currentTab).find('.nasa-row-deal-3');

            if(wow_enable){
                if($(currentTab).find('.product-item').length > 0 || $(currentTab).find('.product_list_widget').length > 0){
                    $(currentTab).css({'opacity': '0.9'}).append('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
                    $(_root).find('.wow').css({
                        'visibility': 'hidden',
                        'animation-name': 'none',
                        'opacity': '0'
                    });

                    if($(nasa_slider).length < 1){
                        $(currentTab).find('.wow').removeClass('animated').css({'animation-name': 'fadeInUp'});
                        $(currentTab).find('.wow').each(function(){
                            var _wow = $(this);
                            var _delay = parseInt($(_wow).attr('data-wow-delay'));

                            setTimeout(function(){
                                $(_wow).css({'visibility': 'visible'});
                                $(_wow).animate({'opacity': 1}, _delay);
                                if($(currentTab).find('.nasa-loader, .please-wait').length > 0){
                                    $(currentTab).css({'opacity': 1});
                                    $(currentTab).find('.nasa-loader, .please-wait').remove();
                                }
                            }, _delay);
                        });
                    } else {
                        $(currentTab).find('.owl-stage').css({'opacity': '0'});
                        setTimeout(function(){
                            $(currentTab).find('.owl-stage').css({'opacity': '1'});
                        }, 500);

                        $(currentTab).find('.wow').each(function(){
                            var _wow = $(this);
                            $(_wow).css({
                                'animation-name': 'fadeInUp',
                                'visibility': 'visible',
                                'opacity': 0
                            });
                            var _delay = parseInt($(_wow).attr('data-wow-delay'));
                            _delay += (show === '0') ? 500 : 0;
                            setTimeout(function(){
                                $(_wow).animate({'opacity': 1}, _delay);
                                if($(currentTab).find('.nasa-loader, .please-wait').length > 0){
                                    $(currentTab).css({'opacity': 1});
                                    $(currentTab).find('.nasa-loader, .please-wait').remove();
                                }
                            }, _delay);
                        });
                    }
                }
            } else {
                if ($(nasa_slider).length > 0) {
                    $(currentTab).css({'opacity': '0.9'}).append('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
                    
                    $(currentTab).find('.owl-stage').css({'opacity': '0'});
                    setTimeout(function(){
                        $(currentTab).find('.owl-stage').css({'opacity': '1'});
                        if($(currentTab).find('.nasa-loader, .please-wait').length > 0){
                            $(currentTab).css({'opacity': 1});
                            $(currentTab).find('.nasa-loader, .please-wait').remove();
                        }
                    }, 300);
                }
            }
            
            if ($(nasa_deal).length > 0) {
                loadHeightDeal($);
            }
        }

        return false;
    });
    
    if ($('.nasa-tabs-content.nasa-slide-style').length > 0) {
        $('.nasa-slide-style').each(function (){
            var _this = $(this);
            nasa_tab_slide_style($, _this, 500);
        });
        
        $(window).resize(function() {
            $('.nasa-slide-style').each(function (){
                var _this = $(this);
                nasa_tab_slide_style($, _this, 50);
            });
        });
    }
}

if(typeof nasa_countdown_l10n !== 'undefined' && (typeof nasa_countdown_init === 'undefined' || nasa_countdown_init === '0')) {
    var nasa_countdown_init = '1';
    // Countdown
    $.countdown.regionalOptions[''] = {
        labels: [
            nasa_countdown_l10n.years,
            nasa_countdown_l10n.months,
            nasa_countdown_l10n.weeks,
            nasa_countdown_l10n.days,
            nasa_countdown_l10n.hours,
            nasa_countdown_l10n.minutes,
            nasa_countdown_l10n.seconds
        ],
        labels1: [
            nasa_countdown_l10n.year,
            nasa_countdown_l10n.month,
            nasa_countdown_l10n.week,
            nasa_countdown_l10n.day,
            nasa_countdown_l10n.hour,
            nasa_countdown_l10n.minute,
            nasa_countdown_l10n.second
        ],
        compactLabels: ['y', 'm', 'w', 'd'],
        whichLabels: null,
        digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
        timeSeparator: ':',
        isRTL: true
    };

    $.countdown.setDefaults($.countdown.regionalOptions['']);
    loadCountDown($);
}

if($('.nasa_banner .center').length > 0){
    $('.nasa_banner .center').vAlign();
    $(window).resize(function() {
        $('.nasa_banner .center').vAlign();
    });
}

if($('.col_hover_focus').length > 0){
    $('body').on('hover', '.col_hover_focus', function(){
        $(this).parent().find('.columns > *').css('opacity', '0.5');
    }, function() {
        $(this).parent().find('.columns > *').css('opacity', '1');
    });
}

if($('.add-to-cart-grid.product_type_simple').length > 0){
    $('body').on('click', '.add-to-cart-grid.product_type_simple', function(){
        $('.mini-cart').addClass('active cart-active');
        $('.mini-cart').hover(function(){
            $('.cart-active').removeClass('cart-active');
        });
        setTimeout(function(){
            $('.cart-active').removeClass('active');
        }, 5000);
    });
}

$('.row ~ br, .columns ~ br, .columns ~ p').remove(); 
$(window).resize();

/* Carousel */
loadingCarousel($);
loadingSCCarosel($);

/* Resize carousel */
setInterval(function(){
    var owldata = $(".owl-carousel").data('owlCarousel');
    if (typeof owldata !== 'undefined' && owldata !== false){
        owldata.updateVars();
    }
}, 1500);

$('.main-images').owlCarousel({
    items: 1,
    nav: false,
    autoplaySpeed: 600,
    dots: false,
    autoHeight:true,
    autoplayTimeout:3000,
    autoplayHoverPause:true,
    responsiveClass:true,
    navText: ["",""],
    navSpeed: 600
});

$('.main-images').on('change.owl.carousel', function(e) {
    var currentItem = e.relatedTarget.relative(e.property.value),
        owlThumbs = $(".product-thumbnails .owl-item");
    $('.active-thumbnail').removeClass('active-thumbnail');
    $(".product-thumbnails").find('.owl-item').eq(currentItem).addClass('active-thumbnail');
    owlThumbs.trigger('to.owl.carousel', [currentItem, 300, true]);
}).data('owl.carousel');

$('body').on('click', '.main-images a', function(e){
    e.preventDefault();
});

$('.product-thumbnails .owl-item').owlCarousel();
$('.product-thumbnails').owlCarousel({
    items: 4,
    nav: true,
    autoplay: false,
    dots: false,
    autoHeight: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: true,
    responsiveClass: true,
    navText: ["", ""],
    navSpeed: 600,
    responsive: {
        "0": {
            items: 2,
            nav: false
        },
        "600": {
            items: 3
        },
        "1000": {
            items: 4
        }
    }
}).on('click', '.owl-item', function () {
    var currentItem = $(this).index();
    $('.main-images').trigger('to.owl.carousel', [currentItem, 300, true]);
});

$('body').on('click', '.product-thumbnails .owl-item a', function(e) {
    e.preventDefault();
});

/*********************************************************************
// ! Promo popup
/ *******************************************************************/
var et_popup_closed = $.cookie('nasatheme_popup_closed');
$('.nasa-popup').magnificPopup({
    items: {
        src: '#nasa-popup',
        type: 'inline'
    },
    removalDelay: 300, //delay removal by X to allow out-animation
    fixedContentPos: false,
    callbacks: {
        beforeOpen: function() {
            this.st.mainClass = 'my-mfp-slide-bottom';
        },
        beforeClose: function() {
            var showagain = $('#showagain:checked').val();
            if(showagain === 'do-not-show'){
                $.cookie('nasatheme_popup_closed', 'do-not-show', {expires: 1, path: '/'});
            }
        }
    }
    // (optionally) other options
});

if(et_popup_closed !== 'do-not-show' && $('.nasa-popup').length > 0 && $('body').hasClass('open-popup')) {
    $('.nasa-popup').magnificPopup('open');
}

$('body').on('click', '#nasa-popup input[type="submit"]', function() {
    $(this).ajaxSuccess(function(event, request, settings) {
        if(typeof request === 'object' && request.responseJSON.status === 'mail_sent') {
            $('body').append('<div id="nasa-newsletter-alert" class="hidden-tag"><div class="wpcf7-response-output wpcf7-mail-sent-ok">' + request.responseJSON.message + '</div></div>');
            
            $.cookie('nasatheme_popup_closed', 'do-not-show', {expires: 1, path: '/'});
            $.magnificPopup.close();
            
            setTimeout(function() {
                $('#nasa-newsletter-alert').fadeIn(300);
                
                setTimeout(function() {
                    $('#nasa-newsletter-alert').fadeOut(500);
                }, 2000);
            }, 300);
        }
    });
});

/*
 * Compare products
 */
$('body').on('click', '.product-interactions .btn-compare', function(){
    var _this = $(this);
    if(!$(_this).hasClass('nasa-compare')) {
        var $button = $(_this).parents('.product-interactions');
        $button.find('.compare-button .compare').trigger('click');
    } else {
        var _id = $(_this).attr('data-prod');
        if(_id) {
            add_compare_product(_id, $);
        }
    }
    
    return false;
});
$('body').on('click', '.nasa-remove-compare', function(){
    var _id = $(this).attr('data-prod');
    if(_id) {
        remove_compare_product(_id, $);
    }
    
    return false;
});
$('body').on('click', '.nasa-compare-clear-all', function(){
    removeAll_compare_product($);
    
    return false;
});
$('body').on('click', '.nasa-close-mini-compare, .transparent-window', function(){
    hideCompare($);
    return false;
});
$('body').on('click', '.nasa-show-compare', function(){
    if(!$(this).hasClass('nasa-showed')) {
        showCompare($);
    } else {
        hideCompare($);
    }
    
    return false;
});

/*
 * Wishlist products
 */
$('body').on('click', '.btn-wishlist', function(){
    var _this = $(this);
    if (!$(_this).hasClass('nasa-disabled')) {
        $('.btn-wishlist').addClass('nasa-disabled');
        if (!$(_this).hasClass('nasa-added')) {
            $(_this).addClass('nasa-added');
            if ($('#tmpl-nasa-global-wishlist').length) {
                var _pid = $(_this).attr('data-prod');
                var _origin_id = $(_this).attr('data-original-product-id');
                var _ptype = $(_this).attr('data-prod_type');
                var _wishlist_tpl = $('#tmpl-nasa-global-wishlist').html();
                if ($('.nasa-global-wishlist').length <= 0) {
                    $('body').append('<div class="nasa-global-wishlist"></div>');
                }
                
                _wishlist_tpl = _wishlist_tpl.replace(/%%product_id%%/g, _pid);
                _wishlist_tpl = _wishlist_tpl.replace(/%%product_type%%/g, _ptype);
                _wishlist_tpl = _wishlist_tpl.replace(/%%original_product_id%%/g, _origin_id);
                
                $('.nasa-global-wishlist').html(_wishlist_tpl);
                $('.nasa-global-wishlist').find('.add_to_wishlist').trigger('click');
            } else {
                var $button = $(_this).parents('.product-interactions');
                $button.find('.add_to_wishlist').trigger('click');
            }
        } else {
            var _pid = $(_this).attr('data-prod');
            if (_pid && $('#yith-wcwl-row-' + _pid).length && $('#yith-wcwl-row-' + _pid).find('.nasa-remove_from_wishlist').length) {
                $(_this).removeClass('nasa-added');
                $(_this).addClass('nasa-unliked');
                $('#yith-wcwl-row-' + _pid).find('.nasa-remove_from_wishlist').trigger('click');
                
                setTimeout(function() {
                    $(_this).removeClass('nasa-unliked');
                }, 1000);
            } else {
                $('.btn-wishlist').removeClass('nasa-disabled');
            }
        }
    }
    
    return false;
});

var _flag_add = false;
var _flag_compare = false;

/* ADD PRODUCT WISHLIST NUMBER */
$('body').on('added_to_wishlist', function() {
    var _data = {};
    _data.action = 'nasa_update_wishlist';
    _data.added = true;
    
    $.ajax({
        url: ajaxurl,
        type: 'post',
        dataType: 'json',
        cache: false,
        data: _data,
        beforeSend: function(){

        },
        success: function(res){
            $('.wishlist_sidebar').replaceWith(res.list);
            var _sl_wishlist = (res.count).toString().replace('+', '');
            var sl_wislist = parseInt(_sl_wishlist);
            $('.wishlist-number .nasa-sl').html(res.count);
            
            if (sl_wislist > 0) {
                $('.wishlist-number').removeClass('nasa-product-empty');
            } else if (sl_wislist === 0 && !$('.wishlist-number').hasClass('nasa-product-empty')){
                $('.wishlist-number').addClass('nasa-product-empty');
            }
            
            if ($('#yith-wcwl-popup-message').length <= 0) {
                $('body').append('<div id="yith-wcwl-popup-message"></div>');
            }
            if ($('#yith-wcwl-popup-message').length && typeof res.mess !== 'undefined' && res.mess !== '') {
                $('#yith-wcwl-popup-message').html(res.mess);

                $('#yith-wcwl-popup-message').fadeIn();
                setTimeout( function() {
                    $('#yith-wcwl-popup-message').fadeOut();
                }, 2000);
            }

            setTimeout(function() {
                initWishlistIcons($, true);
                $('.btn-wishlist').removeClass('nasa-disabled');
            }, 350);
        },
        error: function() {
            $('.btn-wishlist').removeClass('nasa-disabled');
        }
    });
});

/* REMOVE PRODUCT WISHLIST NUMBER */
$('body').on('click', '.nasa-remove_from_wishlist', function(){
    var _pid = $(this).attr('data-prod_id'),
        _wishlist_id = $('.wishlist_table').attr('data-id'),
        _pagination = $('.wishlist_table').attr('data-pagination'),
        _per_page = $('.wishlist_table').attr('data-per-page'),
        _current_page = $('.wishlist_table').attr('data-page');
    $.ajax({
        url: ajaxurl,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'nasa_remove_from_wishlist',
            pid: _pid,
            wishlist_id: _wishlist_id,
            pagination: _pagination,
            per_page: _per_page,
            current_page: _current_page
        },
        beforeSend: function(){
            $.magnificPopup.close();
            // $('.black-window').fadeIn(200).addClass('desk-window');
            // $('#nasa-wishlist-sidebar').show().animate({right: 0}, 500);
            // $('.nasa-wishlist-fog').show().html('<div class="please-wait type2"></div>');
            // $('.nasa-wishlist-fog').show().html('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
        },
        success: function(res){
            if(res.error === '0'){
                $('.nasa-wishlist-fog').hide();
                $('.wishlist_sidebar').replaceWith(res.list);
                var sl_wislist = parseInt(res.count);
                $('.wishlist-number .nasa-sl').html(sl_wislist);
                if (sl_wislist > 0) {
                    $('.wishlist-number').removeClass('nasa-product-empty');
                } else if(sl_wislist === 0 && !$('.wishlist-number').hasClass('nasa-product-empty')){
                    $('.wishlist-number').addClass('nasa-product-empty');
                    $('.wishlist-close a').click();
                }
                
                if ($('.btn-wishlist[data-prod="' + _pid + '"]').length) {
                    $('.btn-wishlist[data-prod="' + _pid + '"]').removeClass('nasa-added');
                    
                    if ($('.btn-wishlist[data-prod="' + _pid + '"]').find('.added').length) {
                        $('.btn-wishlist[data-prod="' + _pid + '"]').find('.added').removeClass('added');
                    }
                }
            }
            
            if ($('#yith-wcwl-popup-message').length <= 0) {
                $('body').append('<div id="yith-wcwl-popup-message"></div>');
            }
            if ($('#yith-wcwl-popup-message').length && typeof res.mess !== 'undefined' && res.mess !== '') {
                $('#yith-wcwl-popup-message').html(res.mess);

                $('#yith-wcwl-popup-message').fadeIn();
                setTimeout( function() {
                    $('#yith-wcwl-popup-message').fadeOut();
                }, 2000);
            }
            
            $('.btn-wishlist').removeClass('nasa-disabled');
        },
        error: function() {
            $('.btn-wishlist').removeClass('nasa-disabled');
        }
    });
    return false;
});

/* ADD OR REMOVE PRODUCT COMPARE NUMBER */
if($('.compare-number').length > 0){
    $('body').on('click', '.btn-compare, .yith-woocompare-widget .compare', function(){
        _flag_add = true;
        $(this).ajaxSuccess(function(event, request, settings) {
            event = settings = null;
            if($('.compare-number .nasa-sl').length > 0 && !_flag_compare){
                _flag_compare = true;
                var strfirst, _list_li;
                var change_sl = false;
                if(typeof request === 'object'){
                    strfirst = request.responseText.substring(0, 1);

                    if(strfirst === '{'){
                        var data = request.responseJSON;
                        if(typeof data.widget_table !== 'undefined'){
                            _list_li = '<ul>' + JSON.parse(request.responseText).widget_table + '</ul>';
                            change_sl = true;
                        }else{
                            change_sl = false;
                        }
                    }else{
                        strfirst = request.responseText.trim().substring(0, 4);
                        if(strfirst === '<li>'){
                            _list_li = (request.responseText === '<li>No products to compare</li>') ? '<ul></ul>' : '<ul>' + request.responseText + '</ul>';
                            change_sl = true;
                        }else{
                            change_sl = false;
                        }
                    }

                    if(change_sl){
                        var sl = $(_list_li).find('li').length;
                            sl = sl ? sl : 0;
                        $('.compare-number .nasa-sl').html(sl);
                        if (sl > 0) {
                            $('.compare-number').removeClass('nasa-product-empty');
                        } else if(sl == 0 && !$('.compare-number').hasClass('nasa-product-empty')){
                            $('.compare-number').addClass('nasa-product-empty');
                        }
                    }
                }
            }
        }).ajaxComplete(function(){
            _flag_compare = _flag_add = false;
        }).ajaxError(function(){
            _flag_compare = _flag_add = false;
        });
    });
}

/* PRODUCT ZOOM */
if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
    $('.yith-wcwl-wishlistexistsbrowse.show').each(function(){
        var tip_message = $(this).find('a').text();
        $(this).find('a').attr('data-tip', tip_message).addClass('tip-top');
    });

    loadTipTop($);
    
    var _zoom = $('body').hasClass('product-zoom') ? true : false;
    if(_zoom) {
        var _easyZoom = $('.product-zoom .easyzoom').easyZoom({loadingNotice: ''});
        var _api_easyZoom = _easyZoom.data('easyZoom');
    }
    if(typeof wc_add_to_cart_variation_params !== 'undefined'){
        $.fn.wc_variations_image_update = function(variation) {
            if (variation && variation.image && variation.image.src && variation.image.src.length > 1) {
                if(_zoom) {
                    _api_easyZoom.swap(variation.image.url, variation.image.url);
                } else {
                    $('.main-images .owl-item:eq(0) img').attr('src', variation.image.url);
                    $('.main-images .owl-item:eq(0) a').attr('href', variation.image.url);
                }
                
                $('.main-images .owl-item:eq(0) img').removeAttr('srcset');
                
                var src_thumb;
                if(variation.image.thumb_src !== 'undefined') {
                    src_thumb = variation.image.thumb_src;
                } else {
                    var thumb_wrap = $('.product-thumbnails .owl-item:eq(0)');
                    if(typeof $(thumb_wrap).attr('data-thumb_org') === 'undefined') {
                        $(thumb_wrap).attr('data-thumb_org', $(thumb_wrap).find('img').attr('src'));
                    }

                    src_thumb = $(thumb_wrap).attr('data-thumb_org');
                }
                if(src_thumb) {
                    $('.product-thumbnails .owl-item:eq(0) img').attr('src', src_thumb).removeAttr('srcset');
                    if($('input[name="nasa-enable-focus-main-image"]').length && $('input[name="nasa-enable-focus-main-image"]').val() === '1') {
                        $('.product-thumbnails .owl-item:eq(0)').click();
                    }
                    
                    if($('.nasa-thumb-clone img').length) {
                        $('.nasa-thumb-clone img').attr('src', src_thumb);
                    }
                }
            } else {
                var image_link = $('.nasa-product-details-page .woocommerce-main-image').attr('data-o_href');
                if(_zoom) {
                    _api_easyZoom.swap(image_link, image_link);
                } else {
                    $('.main-images .owl-item:eq(0) img').attr('src', image_link);
                    $('.main-images .owl-item:eq(0) a').attr('href', image_link);
                }
                
                var thumb_wrap = $('.product-thumbnails .owl-item:eq(0)');
                if(typeof $(thumb_wrap).attr('data-thumb_org') === 'undefined') {
                    $(thumb_wrap).attr('data-thumb_org', $(thumb_wrap).find('img').attr('src'));
                    if($('input[name="nasa-enable-focus-main-image"]').length && $('input[name="nasa-enable-focus-main-image"]').val() === '1') {
                        $('.product-thumbnails .owl-item:eq(0)').click();
                    }
                }
                var src_thumb = $(thumb_wrap).attr('data-thumb_org');
                if(src_thumb) {
                    $('.product-thumbnails .owl-item:eq(0) img').attr('src', src_thumb);
                    if($('input[name="nasa-enable-focus-main-image"]').length && $('input[name="nasa-enable-focus-main-image"]').val() === '1') {
                        $('.product-thumbnails .owl-item:eq(0)').click();
                    }
                    
                    if($('.nasa-thumb-clone img').length) {
                        $('.nasa-thumb-clone img').attr('src', src_thumb);
                    }
                }
            }
            
            /**
             * deal time
             */
            if ($('.nasa-detail-product-deal-countdown').length) {
                if (
                    variation && variation.variation_id &&
                    variation.is_in_stock && variation.is_purchasable
                ) {
                    if(typeof _single_variations[variation.variation_id] === 'undefined') {
                        $.ajax({
                            url: ajaxurl,
                            type: 'post',
                            dataType: 'html',
                            data: {
                                action: 'nasa_get_deal_variation',
                                pid: variation.variation_id
                            },
                            beforeSend: function () {
                                $('.nasa-detail-product-deal-countdown').html('');
                                $('.nasa-detail-product-deal-countdown').removeClass('nasa-show');
                            },
                            success: function (res) {
                                _single_variations[variation.variation_id] = res;
                                $('.nasa-detail-product-deal-countdown').html(_single_variations[variation.variation_id]);
                                if(_single_variations[variation.variation_id] !== '') {
                                    loadCountDown($);
                                    if(!$('.nasa-detail-product-deal-countdown').hasClass('nasa-show')) {
                                        $('.nasa-detail-product-deal-countdown').addClass('nasa-show');
                                    }
                                } else {
                                    $('.nasa-detail-product-deal-countdown').removeClass('nasa-show');
                                }
                            }
                        });
                    } else {
                        $('.nasa-detail-product-deal-countdown').html(_single_variations[variation.variation_id]);
                        if(_single_variations[variation.variation_id] !== '') {
                            loadCountDown($);
                            if(!$('.nasa-detail-product-deal-countdown').hasClass('nasa-show')) {
                                $('.nasa-detail-product-deal-countdown').addClass('nasa-show');
                            }
                        } else {
                            $('.nasa-detail-product-deal-countdown').removeClass('nasa-show');
                        }
                    }
                } else {
                    $('.nasa-detail-product-deal-countdown').html('');
                    $('.nasa-detail-product-deal-countdown').removeClass('nasa-show');
                }
            }
        };
    }
} else {
    if(typeof wc_add_to_cart_variation_params !== 'undefined'){
        $.fn.wc_variations_image_update = function(variation) {
            if (variation && variation.image && variation.image.src && variation.image.src.length > 1) {
                $('.main-images .owl-item:eq(0) img').attr('src', variation.image.url).removeAttr('srcset');
                $('.main-images .owl-item:eq(0) a').attr('href', variation.image.url);
                
                var src_thumb;
                if(variation.image.thumb_src !== 'undefined') {
                    src_thumb = variation.image.thumb_src;
                } else {
                    var thumb_wrap = $('.product-thumbnails .owl-item:eq(0)');
                    if(typeof $(thumb_wrap).attr('data-thumb_org') === 'undefined') {
                        $(thumb_wrap).attr('data-thumb_org', $(thumb_wrap).find('img').attr('src'));
                    }

                    src_thumb = $(thumb_wrap).attr('data-thumb_org');
                }
                if(src_thumb) {
                    $('.product-thumbnails .owl-item:eq(0) img').attr('src', src_thumb).removeAttr('srcset');
                    if($('input[name="nasa-enable-focus-main-image"]').length && $('input[name="nasa-enable-focus-main-image"]').val() === '1') {
                        $('.product-thumbnails .owl-item:eq(0) a').click();
                    }
                    
                    if($('.nasa-thumb-clone img').length) {
                        $('.nasa-thumb-clone img').attr('src', src_thumb);
                    }
                }
            } else {
                var image_link = $('.nasa-product-details-page .woocommerce-main-image').attr('data-o_href');
                $('.main-images .owl-item:eq(0) img').attr('src', image_link).removeAttr('srcset');
                $('.main-images .owl-item:eq(0) a').attr('href', image_link);
                
                var thumb_wrap = $('.product-thumbnails .owl-item:eq(0)');
                if(typeof $(thumb_wrap).attr('data-thumb_org') === 'undefined') {
                    $(thumb_wrap).attr('data-thumb_org', $(thumb_wrap).find('img').attr('src'));
                }
                var src_thumb = $(thumb_wrap).attr('data-thumb_org');
                if(src_thumb) {
                    $('.product-thumbnails .owl-item:eq(0) img').attr('src', src_thumb);
                    if($('input[name="nasa-enable-focus-main-image"]').length && $('input[name="nasa-enable-focus-main-image"]').val() === '1') {
                        $('.product-thumbnails .owl-item:eq(0) a').click();
                    }
                    
                    if($('.nasa-thumb-clone img').length) {
                        $('.nasa-thumb-clone img').attr('src', src_thumb);
                    }
                }
            }
            
            /**
             * deal time
             */
            if ($('.nasa-detail-product-deal-countdown').length) {
                if (
                    variation && variation.variation_id &&
                    variation.is_in_stock && variation.is_purchasable
                ) {
                    if(typeof _single_variations[variation.variation_id] === 'undefined') {
                        $.ajax({
                            url: ajaxurl,
                            type: 'post',
                            dataType: 'html',
                            data: {
                                action: 'nasa_get_deal_variation',
                                pid: variation.variation_id
                            },
                            beforeSend: function () {
                                $('.nasa-detail-product-deal-countdown').html('');
                                $('.nasa-detail-product-deal-countdown').removeClass('nasa-show');
                            },
                            success: function (res) {
                                _single_variations[variation.variation_id] = res;
                                $('.nasa-detail-product-deal-countdown').html(_single_variations[variation.variation_id]);
                                if(_single_variations[variation.variation_id] !== '') {
                                    loadCountDown($);
                                    if(!$('.nasa-detail-product-deal-countdown').hasClass('nasa-show')) {
                                        $('.nasa-detail-product-deal-countdown').addClass('nasa-show');
                                    }
                                } else {
                                    $('.nasa-detail-product-deal-countdown').removeClass('nasa-show');
                                }
                            }
                        });
                    } else {
                        $('.nasa-detail-product-deal-countdown').html(_single_variations[variation.variation_id]);
                        if(_single_variations[variation.variation_id] !== '') {
                            loadCountDown($);
                            if(!$('.nasa-detail-product-deal-countdown').hasClass('nasa-show')) {
                                $('.nasa-detail-product-deal-countdown').addClass('nasa-show');
                            }
                        } else {
                            $('.nasa-detail-product-deal-countdown').removeClass('nasa-show');
                        }
                    }
                } else {
                    $('.nasa-detail-product-deal-countdown').html('');
                    $('.nasa-detail-product-deal-countdown').removeClass('nasa-show');
                }
            }
        };
    }
}

// Target quantity inputs on product pages
$('body').find('input.qty:not(.product-quantity input.qty)').each(function() {
    var min = parseFloat($(this).attr('min'));
    if (min && min > 0 && parseFloat($(this).val()) < min) {
        $(this).val(min);
    }
});

$('body').on('click', '.plus, .minus', function() {
    // Get values
    var $qty = $(this).parents('.quantity').find('.qty'),
        button_add = $(this).parent().parent().find('.single_add_to_cart_button'),
        currentVal = parseFloat($qty.val()),
        max = parseFloat($qty.attr('max')),
        min = parseFloat($qty.attr('min')),
        step = $qty.attr('step');
    // Format values
    currentVal = !currentVal ? 0 : currentVal;
    max = !max ? '' : max;
    min = !min ? 1 : min;
    if (step === 'any' || step === '' || typeof step === 'undefined' || parseFloat(step) === 'NaN') step = 1;
    // Change the value
    if ($(this).is('.plus')) {
        if (max && (max == currentVal || currentVal > max)) {
            $qty.val(max);
            if(button_add.length > 0){
                button_add.attr('data-quantity', max);
            }
        } else {
            $qty.val(currentVal + parseFloat(step));
            if(button_add.length > 0){
                button_add.attr('data-quantity', currentVal + parseFloat(step));
            }
        }
    } else {
        if (min && (min == currentVal || currentVal < min)) {
            $qty.val(min);
            if(button_add.length > 0){
                button_add.attr('data-quantity', min);
            }
        } else if (currentVal > 0) {
            $qty.val(currentVal - parseFloat(step));
            if(button_add.length > 0){
                button_add.attr('data-quantity', currentVal - parseFloat(step));
            }
        }
    }
    // Trigger change event
    $qty.trigger('change');
});

/*
 * Ajax search
 */
if(typeof search_options.enable_live_search !== 'undefined' && search_options.enable_live_search == '1') {
    var empty_mess = $('#nasa-empty-result-search').html();
    
    var searchProducts = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: ajaxurl + '?action=live_search_products',
        limit: search_options.limit_results,
        remote: {
            url: ajaxurl + '?action=live_search_products&s=%QUERY',
            ajax: {
                data:{cat: $('.nasa-cats-search').val()},
                beforeSend: function () {
                    if($('.live-search-input').parent().find('.loader-search').length === 0){
                        $('.live-search-input').parent().append('<div class="please-wait dark"><span></span><span></span><span></span></div>');
                    }
                },
                success: function () {
                    $('.please-wait').remove();
                },
                error: function () {
                    $('.please-wait').remove();
                }
            }
        }
    });

    var initSearch = false;
    $('body').on('focus', 'input.live-search-input', function (){
        if(!initSearch) {
            searchProducts.initialize();
            initSearch = true;
        }
    });

    $('.live-search-input').typeahead({
        minLength: 3,
        hint: true,
        highlight: false,
        backdrop: {
            "opacity": 0.8,
            "filter": "alpha(opacity=80)",
            "background-color": "#eaf3ff"
        },
        backdropOnFocus: false,
        callback: {
            onSubmit: function(node, form, item, event) {
                form.submit();
            }
        }
    },
    {
        name: 'search',
        source: searchProducts.ttAdapter(),
        displayKey: 'title',
        templates: {
            empty : '<p class="empty-message" style="padding:0;margin:0;font-size:100%;">' + empty_mess + '</p>',
            suggestion: Handlebars.compile(search_options.live_search_template)
        }
    });
}

$('body').on('mouseover', '.search-dropdown', function() {
    $(this).addClass('active');
}).on('mouseout', '.search-dropdown', function() {
    $(this).removeClass('active');
});

/*
 * Banner Lax
 */
var windowWidth = $(window).width();
$(window).resize(function() {
    windowWidth = $(window).width();
    if(windowWidth <= 768){
        $('.hover-lax').css('background-position', 'center center');
    }
});

$('body').on('mousemove', '.hover-lax', function(e){
    var lax_bg = $(this);
    var minWidth = $(lax_bg).attr('data-minwidth') ? $(lax_bg).attr('data-minwidth') : 768;

    if(windowWidth > minWidth){
        var amountMovedX = (e.pageX * -1 / 6);
        var amountMovedY = (e.pageY * -1 / 6);
        $(lax_bg).css('background-position', amountMovedX + 'px ' + amountMovedY + 'px');
    }else{
        $(lax_bg).css('background-position', 'center center');
    }
});

$('body').on('click', '.mobile-search', function(){
    $('.black-window').fadeIn(200);
    var height_adminbar = $('#wpadminbar').length > 0 ? $('#wpadminbar').height() : 0;
    $('.warpper-mobile-search').show().animate({top: height_adminbar}, 700);
    $('.warpper-mobile-search').find('input[name="s"]').val('').focus();
});

$('body').on('click', '.desk-search', function(){
    var _search = $(this).parent().find('.nasa-show-search-form'),
        _menu = $('#site-navigation');
    var _w = '500px';
    var search_in_topbar = $('.top-bar-has-search').length > 0 ? true : false;
    if($(_menu).length > 0 && $(_menu).width() && !search_in_topbar){
        _w = $(_menu).width();
    }
    var _this = $(this);
    setTimeout(function(){
        $(_this).toggleClass('open');
    },300);
    if(!search_in_topbar){
        $(_menu).animate({'opacity': 0}, 200);
    }
    $(_search).show().animate({width: _w}, 300).removeClass('nasa-over-hide').after('<div class="nasa-tranparent" />');
    
    var _input = $(_search).find('input[name="s"]');
    if(!$(_input).hasClass('nasa-not-radius')){
        $(_input).addClass('nasa-not-radius');
    }
    $(_input).css({'padding-left': '15px', 'padding-right': '15px'}).val('').focus();
});

$('body').on('click', '.nasa-tranparent', function(){
    var _search = $(this).parent().find('.nasa-show-search-form');
    var _this = $('.desk-search');
    setTimeout(function(){
        $(_this).toggleClass('open');
    },200);
    $(_search).find('input[name="s"]').css({padding: 0});
    $(_search).addClass('nasa-over-hide').animate({width: 0}, 200).hide(200);
    var _menu = $('.header-container').find('.header-nav');
    if($(_menu).length > 0){
        $(_menu).animate({'opacity': 1}, 200);
    }
    $(this).remove();
});

$('body').on('click', '.toggle-sidebar', function(){
    $('.black-window').fadeIn(200);
    if($('.col-sidebar').hasClass('left')){
        $('.col-sidebar').show().animate({left: 0}, 700);
    }else{
        $('.col-sidebar').show().animate({right: 0}, 700);
    }
});

if ($('input[name="nasa_cart_sidebar_show"]').length === 1 && $('input[name="nasa_cart_sidebar_show"]').val() === '1') {
    setTimeout(function() {
        $('.cart-link').click();
    }, 300);
}

$('body').on('click', '.cart-link', function(){
    $('.black-window').fadeIn(200).addClass('desk-window');
    $('#cart-sidebar').show().animate({right: 0}, 700);
    _sidebar_show = true;
});

$('body').on('click', '.wishlist-link', function(){
    $('.black-window').fadeIn(200).addClass('desk-window');
    $('#nasa-wishlist-sidebar').show().animate({right: 0}, 700);
    _sidebar_show = true;
});

$('body').on('click', '#nasa-init-viewed', function(){
    $(this).animate({right: '-100%'}, 200);
    setTimeout(function() {
        $('.black-window').fadeIn(200).addClass('desk-window');
        $('#nasa-viewed-sidebar').show().animate({right: 0}, 700);
    }, 300);
});

$('body').on('click', '.black-window, .white-window, .nasa-sidebar-close a, .nasa-sidebar-return-shop, .login-register-close a', function(){
    _sidebar_show = false;
    if($('.black-window').hasClass('desk-window')){
        $('.black-window').removeClass('desk-window');
    }
    
    if($('#mobile-navigation').length === 1 && $('#mobile-navigation').attr('data-show') === '1') {
        var _w_wrap = $('#nasa-menu-sidebar-content').outerWidth().toString();
        $('#nasa-menu-sidebar-content').animate({left: '-' + _w_wrap + 'px'}, 800);
        $('#mobile-navigation').attr('data-show', '0');
        setTimeout(function() {
            $('.black-window').removeClass('nasa-transparent');
            $('#nasa-menu-sidebar-content').css({'z-index': 399});
        }, 800);
    }
    
    if($('.warpper-mobile-search').length > 0){
        $('.warpper-mobile-search').animate({top: '-100%'}, 700);
        if($('.warpper-mobile-search').hasClass('show-in-desk')){
            setTimeout(function () {
                $('.warpper-mobile-search').removeClass('show-in-desk');
            }, 600);
        }
    }

    var bodyWidth = $('body').width();
    if($('.col-sidebar').length > 0 && bodyWidth <= 945){
        if($('.col-sidebar').hasClass('left')){
            $('.col-sidebar').animate({left: '-100%'}, 1000);
        }else{
            $('.col-sidebar').animate({right: '-100%'}, 1000);
        }
    }

    if($('#cart-sidebar').length > 0){
        $('#cart-sidebar').animate({right: '-100%'}, 1000);
    }

    if($('#nasa-wishlist-sidebar').length > 0){
        $('#nasa-wishlist-sidebar').animate({right: '-100%'}, 1000);
    }
    
    if($('#nasa-viewed-sidebar').length > 0){
        $('#nasa-init-viewed').animate({right: '0'}, 1000);
        $('#nasa-viewed-sidebar').animate({right: '-100%'}, 1000);
    }

    if($('.nasa-login-register-warper').length > 0){
        $('.nasa-login-register-warper').animate({top: '-110%'}, 600);
    }

    $('.black-window, .white-window').fadeOut(800);
});

$(document).on('keyup', function(e){
    if (e.keyCode === 27){
        $('.nasa-tranparent').click();
        $('.black-window, .white-window, .nasa-sidebar-close a, .login-register-close a, .nasa-transparent-topbar').click();
        $.magnificPopup.close();
    }
});

$('body').on('click', '.add_to_cart_button', function() {
    $.magnificPopup.close();
    setTimeout(function () {
        $('.black-window').fadeIn(200).addClass('desk-window');
        $('#nasa-wishlist-sidebar').hide().animate({right: '-100%'}, 700);
        $('#cart-sidebar').show().animate({right: 0}, 700);
        _sidebar_show = true;
    }, 200);
});

if($('.nasa-cart-fog').length > 0 && _sidebar_show){
    $('body').ajaxSend(function(){
        // $('.nasa-cart-fog').show().html('<div class="please-wait type2"></div>');
        $('.nasa-cart-fog').show().html('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
    }).ajaxComplete(function(){
        $('.nasa-cart-fog').hide();
    }).ajaxError(function (){
        $('.nasa-cart-fog').hide();
    });
}

/*
 * Remove items in cart
 */
$('body').on('click', '.remove.item-in-cart', function(){
    var _this = $(this);
    var _key = $(_this).attr('data-key');
    var _id = $(_this).attr('data-id');
    $('.remove.item-in-cart').removeClass('remove');
    if(_key && _id){
        if($('form.nasa-shopping-cart-form .woocommerce-cart-form__contents .product-remove a.remove[data-product_id="' + _id + '"]').length > 0) {
            $('form.nasa-shopping-cart-form .woocommerce-cart-form__contents .product-remove a.remove[data-product_id="' + _id + '"]').click();
        }
        else {
            $.ajax({
                url : ajaxurl,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'nasa_cart_remove_item',
                    item_key: _key
                },
                beforeSend: function(){

                },
                success: function(res){
                    if(res.success){
                        var fragments = res.fragments;
                        if (fragments) {
                            $.each(fragments, function(key, value) {
                                $(key).addClass('updating');
                                $(key).replaceWith(value);
                            });

                            $('.nasa-cart-fog').hide();
                        }

                        if(
                            $('.add_to_cart_button[data-product_id="' + _id + '"]').length > 0 &&
                            $('.add_to_cart_button[data-product_id="' + _id + '"]').hasClass('added')
                        ){
                            $('.add_to_cart_button[data-product_id="' + _id + '"]').removeClass('added');
                        }

                        if(res.showing < 1){
                            var empty = $('.empty.hidden-tag').html();
                            $('.cart_sidebar').html(empty);
                            setTimeout(function () {
                                $('.black-window').removeClass('desk-window');
                                $('#cart-sidebar').animate({right: '-100%'}, 1000);
                                $('.black-window').fadeOut(1000);
                                _sidebar_show = false;
                            }, 200);
                        }
                    }
                },
                error: function(){
                    $('#cart-sidebar .item-in-cart').addClass('remove');
                }
            });
        }
    }
});

/*
 * Single add to cart from wishlist
 */
$('body').on('click', '.nasa_add_to_cart_from_wishlist', function(){
    var _this = $(this),
        _id = $(_this).attr('data-product_id');
    if(_id){
        var _type = $(_this).attr('data-type'),
            _quantity = $(_this).attr('data-quantity'),
            _head_type = $(_this).attr('data-head_type'),
            _data_wislist = {};
        if($('.wishlist_table').length > 0 && $('.wishlist_table').find('tr#yith-wcwl-row-' + _id).length > 0) {
            _data_wislist = {
                from_wishlist: '1',
                wishlist_id: $('.wishlist_table').attr('data-id'),
                pagination: $('.wishlist_table').attr('data-pagination'),
                per_page: $('.wishlist_table').attr('data-per-page'),
                current_page: $('.wishlist_table').attr('data-page')
            };
        }
        
        nasa_single_add_to_cart($, _this, _id, _quantity, _type, null, null, _head_type, _data_wislist);
    }
    return false;
});

/*
 * Add to cart in quick-view Or ditail product
 */
$('body').on('click', 'form.cart .single_add_to_cart_button', function() {
    var _flag_adding = true;
    var _this = $(this);
    var _form = $(_this).parents('form.cart');
    var _enable_ajax = $(_form).find('input[name="nasa-enable-addtocart-ajax"]');
    if($(_enable_ajax).length <= 0 || $(_enable_ajax).val() !== '1') {
        _flag_adding = false;
        return;
    } else {
        var _id = !$(_this).hasClass('disabled') ? $(_form).find('input[name="data-product_id"]').val() : false;
        if(_id) {
            var _type = $(_form).find('input[name="data-type"]').val(),
                _quantity = $(_form).find('.quantity input[name="quantity"]').val(),
                _head_type = $(_form).find('input[name="data-head_type"]').val(),
                _variation_id = $(_form).find('input[name="variation_id"]').length > 0 ? parseInt($(_form).find('input[name="variation_id"]').val()) : 0,
                _variation = {},
                _data_wislist = {},
                _from_wishlist = (
                    $(_form).find('input[name="data-from_wishlist"]').length === 1 &&
                    $(_form).find('input[name="data-from_wishlist"]').val() === '1'
                ) ? '1' : '0';
                
            if(_type === 'variable' && !_variation_id) {
                _flag_adding = false;
                return false;
            } else {
                if(_variation_id > 0 && $(_form).find('.variations').length > 0){
                    $(_form).find('.variations').find('select').each(function(){
                        _variation[$(this).attr('name')] = $(this).val();
                    });
                    
                    if($('.wishlist_table').length > 0 && $('.wishlist_table').find('tr#yith-wcwl-row-' + _id).length > 0) {
                        _data_wislist = {
                            from_wishlist: _from_wishlist,
                            wishlist_id: $('.wishlist_table').attr('data-id'),
                            pagination: $('.wishlist_table').attr('data-pagination'),
                            per_page: $('.wishlist_table').attr('data-per-page'),
                            current_page: $('.wishlist_table').attr('data-page')
                        };
                    }
                }
            }
            
            if(_flag_adding) {
                nasa_single_add_to_cart($, _this, _id, _quantity, _type, _variation_id, _variation, _head_type, _data_wislist);
            }
        }
        
        return false;
    }
});

$('body').on('click', '.nasa_bundle_add_to_cart', function() {
    var _this = $(this),
        _id = $(_this).attr('data-product_id');
    if(_id){
        var _type = $(_this).attr('data-type'),
            _quantity = $(_this).attr('data-quantity'),
            _head_type = $(_this).attr('data-head_type'),
            _variation_id = 0,
            _variation = {},
            _data_wislist = {};
        
        nasa_single_add_to_cart($, _this, _id, _quantity, _type, _variation_id, _variation, _head_type, _data_wislist);
    }
    
    return false;
});

$('body').on('click', '.product_type_variation.add-to-cart-grid', function() {
    var _this = $(this),
        _id = $(_this).attr('data-product_id');
    if(_id){
        var _type = 'variation',
            _quantity = $(_this).attr('data-quantity'),
            _head_type = $(_this).attr('data-head_type'),
            _variation_id = 0,
            _variation = null,
            _data_wislist = {};
            
            if(typeof $(this).attr('data-variation') !== 'undefined') {
                _variation = JSON.parse($(this).attr('data-variation'));
            }
        if(_variation) {
            nasa_single_add_to_cart($, _this, _id, _quantity, _type, _variation_id, _variation, _head_type, _data_wislist);
        }
    }
    
    return false;
});

$('body').on('click', '.product_type_variable', function(){
    if($('input[name="nasa-disable-quickview-ux"]').length <= 0 || $('input[name="nasa-disable-quickview-ux"]').val() === '0') {
        var _this = $(this);

        if($(_this).parents('.compare-list').length > 0) {
            return;
        }

        else {
            if(!$(_this).hasClass('btn-from-wishlist')) {
                var _parent = $(_this).parents('.product-interactions');
                if($(_parent).length < 1){
                    _parent = $(_this).parents('.item-product-widget');
                }
                $(_parent).find('.quick-view').click();
            }
            // From Wishlist
            else {
                var _parent = $(_this).parents('.add-to-cart-wishlist');
                var product_item = $(_this).parents('.product-wishlist-info').find('.wishlist-item');
                $(product_item).css({opacity: 0.3});
                $(product_item).after('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');

                $(_parent).find('.quick-view').click();
            }

            return false;
        }
    } else {
        return;
    }
});

$('body').on('click', '.ajax_add_to_cart_variable', function(){
    $(this).parent().find('.quick-view').click();
    return false;
});

// shortcode post to top
if($('.nasa-post-slider').length > 0){
    var _items = parseInt($('.nasa-post-slider').attr('data-show'));
    $('.nasa-post-slider').owlCarousel({
        items: _items,
        loop: true,
        nav: false,
        autoplay: true,
        dots: false,
        autoHeight: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        responsiveClass: true,
        navText: ["", ""],
        navSpeed: 600,
        responsive:{
            "0": {
                items: 1,
                nav: false
            },
            "600": {
                items: 1,
                nav: false
            },
            "1000": {
                items: _items,
                nav: false
            }
        }
    });
};

if($('.nasa-promotion-close').length > 0){
    var height = $('.nasa-promotion-news').outerHeight();
    if($.cookie('promotion') !== 'hide'){
        $('.nasa-position-relative').animate({'height': height + 'px'}, 500);
        $('.nasa-promotion-news').fadeIn(500);
    }
    
    $('body').on('click', '.nasa-promotion-close', function(){
        $.cookie('promotion','hide', {path: '/'});
        $('.nasa-promotion-show').show();
        $('.nasa-position-relative').animate({'height': '0px'}, 500);
        $('.nasa-promotion-news').fadeOut(500);
    });
    
    $('body').on('click', '.nasa-promotion-show', function(){
        $.cookie('promotion','show', {path: '/'});
        $('.nasa-promotion-show').hide();
        $('.nasa-position-relative').animate({'height': height + 'px'}, 500);
        $('.nasa-promotion-news').fadeIn(500);
    });
    
};

/* ===================== Filter by sidebar =============================== */
var min_price = 0, max_price = 0, hasPrice = '0';
if($('.price_slider_wrapper').length > 0){
    $('.price_slider_wrapper').find('input').attr('readonly', true);
    $('.price_slider_wrapper').find('button').remove();
    min_price = parseFloat($('.price_slider_wrapper').find('input[name="min_price"]').val()),
    max_price = parseFloat($('.price_slider_wrapper').find('input[name="max_price"]').val());
    hasPrice = ($('.nasa_hasPrice').length > 0) ? $('.nasa_hasPrice').val() : '0';

    if(hasPrice === '1'){
        $('.reset_price').show();
    }
}

// Filter by Price
$('body').on("slidestop", ".price_slider", function(){
    var _obj = $(this).parents('form');
    if($('.nasa-has-filter-ajax').length < 1){
        $('input[name="nasa_hasPrice"]').remove();
        $(_obj).submit();
    } else {
        var min = parseFloat($(_obj).find('input[name="min_price"]').val()),
            max = parseFloat($(_obj).find('input[name="max_price"]').val());
        if(min < 0){
            min = 0;
        }
        if(max < min){
            max = min;
        }

        if(min != min_price || max != max_price){
            min_price = min;
            max_price = max;
            hasPrice = '1';
            if($('.nasa_hasPrice').length > 0){
                $('.nasa_hasPrice').val('1');
                $('.reset_price').fadeIn(200);
            }

            // Call filter by price
            var _this = $('.current-cat > .nasa-filter-by-cat'),
                _order = $('select[name="orderby"]').val(),
                _page = false,
                _catid = null,
                _taxonomy = '',
                _url = '';

            if($(_this).length > 0){
                _catid = $(_this).attr('data-id');
                _taxonomy = $(_this).attr('data-taxonomy');
                _url = $(_this).attr('href');
            }

            var _variations = nasa_setVariations($, [], []);

            var _hasSearch = ($('input#nasa_hasSearch').length > 0 && $('input#nasa_hasSearch').val() !== '') ? 1 : 0;
            var _s = (_hasSearch === 1) ? $('input#nasa_hasSearch').val() : '';

            nasa_Ajax_filter($, _url, _page, _catid, _order, _variations, hasPrice, min, max, _hasSearch, _s, _this, _taxonomy, true);
        }

        return false;
    }
});

// Reset filter price
$('body').on('click', '.reset_price', function(){
    if($('.nasa_hasPrice').length > 0 && $('.nasa_hasPrice').val() === '1'){
        var _obj = $(this).parents('form');
        if($('.nasa-has-filter-ajax').length < 1){
            $('#min_price').remove();
            $('#max_price').remove();
            $('input[name="nasa_hasPrice"]').remove();
            $(_obj).append('<input type="hidden" name="reset-price" value="true" />');
            $(_obj).submit();
        } else {
            var _min = $('#min_price').attr('data-min');
            var _max = $('#max_price').attr('data-max');
            $('.price_slider').slider('values', 0, _min);
            $('.price_slider').slider('values', 1, _max);
            $('#min_price').val(_min);
            $('#max_price').val(_max);

            var currency_pos = $('input[name="nasa_currency_pos"]').val(),
                full_price_min = _min,
                full_price_max = _max;
            switch (currency_pos) {
                case 'left':
                    full_price_min = woocommerce_price_slider_params.currency_format_symbol + _min;
                    full_price_max = woocommerce_price_slider_params.currency_format_symbol + _max;
                    break;
                case 'right':
                    full_price_min = _min + woocommerce_price_slider_params.currency_format_symbol;
                    full_price_max = _max + woocommerce_price_slider_params.currency_format_symbol;
                    break;
                case 'left_space' :
                    full_price_min = woocommerce_price_slider_params.currency_format_symbol + ' ' + _min;
                    full_price_max = woocommerce_price_slider_params.currency_format_symbol + ' ' + _max;
                    break;
                case 'right_space' :
                    full_price_min = _min + ' ' + woocommerce_price_slider_params.currency_format_symbol;
                    full_price_max = _max + ' ' + woocommerce_price_slider_params.currency_format_symbol;
                    break;
            }

            $('.price_slider_amount .price_label span.from').html(full_price_min);
            $('.price_slider_amount .price_label span.to').html(full_price_max);

            var min = 0,
                max = 0;

            hasPrice = '0';
            if($('.nasa_hasPrice').length > 0){
                $('.nasa_hasPrice').val('0');
                $('.reset_price').fadeOut(200);
            }

            // Call filter by price
            var _this = $('.current-cat > .nasa-filter-by-cat'),
                _order = $('select[name="orderby"]').val(),
                _page = false,
                _catid = null,
                _taxonomy = '',
                _url = '';

            if($(_this).length > 0){
                _catid = $(_this).attr('data-id');
                _taxonomy = $(_this).attr('data-taxonomy');
                _url = $(_this).attr('href');
            }

            var _variations = nasa_setVariations($, [], []);

            var _hasSearch = ($('input#nasa_hasSearch').length > 0 && $('input#nasa_hasSearch').val() !== '') ? 1 : 0;
            var _s = (_hasSearch === 1) ? $('input#nasa_hasSearch').val() : '';

            nasa_Ajax_filter($, _url, _page, _catid, _order, _variations, hasPrice, min, max, _hasSearch, _s, _this, _taxonomy, true);
        }
    }

    return false;
});

/**
 * Tag clouds
 */
renderTagClouds($);

// Filter by Category
$('body').on('click', '.nasa-filter-by-cat', function(){
    if($('.nasa-has-filter-ajax').length < 1) {
        return;
    } else {
        if(!$(this).hasClass('nasa-disable') && !$(this).hasClass('nasa-active')){
            $('li.cat-item').removeClass('current-cat');
            var _this = $(this),
                _catid = $(_this).attr('data-id'),
                _taxonomy = $(_this).attr('data-taxonomy'),
                _order = $('select[name="orderby"]').val(),
                _url = $(_this).attr('href'),
                _page = false;

            if(_catid){
                var _variations = [];
                $('.nasa-filter-by-variations').each(function(){
                    if($(this).hasClass('nasa-filter-var-chosen')){
                        $(this).parent().removeClass('chosen nasa-chosen');
                        $(this).removeClass('nasa-filter-var-chosen');
                    }
                });

                var min = null,
                    max = null;
                $('input#nasa_hasSearch').val('');
                hasPrice = '0';
                nasa_Ajax_filter($, _url, _page, _catid, _order, _variations, hasPrice, min, max,  0, '', _this, _taxonomy, true);
            }
        }
        return false;
    }
});

if($('.woocommerce-ordering').length > 0 && $('.nasa-has-filter-ajax').length > 0){
    var _parent = $('.woocommerce-ordering').parent(),
        _order = $('.woocommerce-ordering').html();
    $(_parent).html(_order);
}

// Filter by ORDER BY
$('body').on('change', 'select[name="orderby"]', function(){
    if($('.nasa-has-filter-ajax').length <= 0) {
        return;
    }else{
        var _this = $('.current-cat > .nasa-filter-by-cat'),
            _order = $(this).val(),
            _page = false,
            _catid = null,
            _taxonomy = '',
            _url = '';

        if($(_this).length > 0){
            _catid = $(_this).attr('data-id');
            _taxonomy = $(_this).attr('data-taxonomy');
            _url = $(_this).attr('href');
        }

        var _variations = nasa_setVariations($, [], []);

        var min = null,
            max = null;
        if(hasPrice === '1'){
            var _obj = $(".price_slider").parents('form');
            if($(_obj).length > 0){
                min = parseFloat($(_obj).find('input[name="min_price"]').val());
                max = parseFloat($(_obj).find('input[name="max_price"]').val());
                if(min < 0){
                    min = 0;
                }
                if(max < min){
                    max = min;
                }
            }
        }
        
        var _hasSearch = ($('input#nasa_hasSearch').length > 0 && $('input#nasa_hasSearch').val() !== '') ? 1 : 0;
        var _s = (_hasSearch === 1) ? $('input#nasa_hasSearch').val() : '';

        nasa_Ajax_filter($, _url, _page, _catid, _order, _variations, hasPrice, min, max, _hasSearch, _s, _this, _taxonomy, true);
        return false;
    }
});

// Filter by Paging
$('body').on('click', '.nasa-pagination-ajax .page-numbers', function(){
    if($(this).hasClass('nasa-current')){
        return;
    }else{
        var _this = $('.current-cat > .nasa-filter-by-cat'),
            _order = $('select[name="orderby"]').val(),
            _page = $(this).attr('data-page'),
            _catid = null,
            _taxonomy = '',
            _url = '';
        if(_page === '1'){
            _page = false;
        }
        if($(_this).length > 0){
            _catid = $(_this).attr('data-id');
            _taxonomy = $(_this).attr('data-taxonomy');
            _url = $(_this).attr('href');
        }

        var _variations = nasa_setVariations($, [], []);

        var min = null,
            max = null;
        if(hasPrice === '1'){
            var _obj = $(".price_slider").parents('form');
            if($(_obj).length > 0){
                min = parseFloat($(_obj).find('input[name="min_price"]').val());
                max = parseFloat($(_obj).find('input[name="max_price"]').val());
                if(min < 0){
                    min = 0;
                }
                if(max < min){
                    max = min;
                }
            }
        }

        var _hasSearch = ($('input#nasa_hasSearch').length > 0  && $('input#nasa_hasSearch').val() !== '') ? 1 : 0;
        var _s = (_hasSearch === 1) ? $('input#nasa_hasSearch').val() : '';

        nasa_Ajax_filter($, _url, _page, _catid, _order, _variations, hasPrice, min, max, _hasSearch, _s, _this, _taxonomy, true);
        return false;
    }
});

// Filter by variations
$('body').on('click', '.nasa-filter-by-variations', function(){
    if($('.nasa-has-filter-ajax').length < 1){
        return;
    } else {
        var _this = $('.current-cat > .nasa-filter-by-cat'),
            _order = $('select[name="orderby"]').val(),
            _page = false,
            _catid = null,
            _taxonomy = '',
            _url = '';

        if($(_this).length > 0){
            _catid = $(_this).attr('data-id');
            _taxonomy = $(_this).attr('data-taxonomy');
            _url = $(_this).attr('href');
        }

        var _variations = [], 
            _keys = [],
            flag = false;
        if($(this).hasClass('nasa-filter-var-chosen')){
            $(this).parent().removeClass('chosen nasa-chosen').show();
            $(this).removeClass('nasa-filter-var-chosen');
        }else{
            $(this).parent().addClass('chosen nasa-chosen');
            $(this).addClass('nasa-filter-var-chosen');
        }
        flag = true;

        if(flag){
            _variations = nasa_setVariations($, _variations, _keys);
        }

        var min = null,
            max = null;
        var _obj = $(".price_slider").parents('form');
        if($(_obj).length > 0 && hasPrice === '1'){
            min = parseFloat($(_obj).find('input[name="min_price"]').val());
            max = parseFloat($(_obj).find('input[name="max_price"]').val());
            if(min < 0){
                min = 0;
            }
            if(max < min){
                max = min;
            }
        }

        var _hasSearch = ($('input#nasa_hasSearch').length > 0  && $('input#nasa_hasSearch').val() !== '') ? 1 : 0;
        var _s = (_hasSearch === 1) ? $('input#nasa_hasSearch').val() : '';

        nasa_Ajax_filter($, _url, _page, _catid, _order, _variations, hasPrice, min, max, _hasSearch, _s, _this, _taxonomy, true);
        return false;
    }
});

/**
 * nasa-change-layout Change layout
 */
if($('.nasa-change-layout').length > 0) {
    $('body').on('click', '.nasa-change-layout', function(){
        var _this = $(this);
        if($(_this).hasClass('active')){
            return false;
        } else {
            changeLayoutShopPage($, _this);
        }
    });
    
    var _cookie_change_layout = $.cookie('gridcookie');
    if(typeof _cookie_change_layout !== 'undefined') {
        $('.nasa-change-layout.' + _cookie_change_layout).click();
    }
}

// Logout click
$('body').on('click', '.nasa_logout_menu a', function(){
    if($('input[name="nasa_logout_menu"]').length > 0){
        window.location.href = $('input[name="nasa_logout_menu"]').val();
    }
});

// Show more | Show less
$('body').on('click', '.nasa_show_manual > a', function(){
    var _this = $(this),
        _val = $(_this).attr('data-show'),
        _li = $(_this).parent(),
        _delay = $(_li).attr('data-delay') ? parseInt($(_li).attr('data-delay')) : 200,
        _fade = $(_li).attr('data-fadein') === '1' ? true : false;

    if(_val === '1'){
        $(_li).parent().find('.nasa-show-less').each(function(){
            if(!_fade) {
                $(this).slideDown(_delay);
            } else {
                $(this).fadeIn(_delay);
            }
        });
        
        $(_this).fadeOut(350);
        $(_li).find('.nasa-hidden').fadeIn(350);
        
    } else {
        $(_li).parent().find('.nasa-show-less').each(function(){
            if(!$(this).hasClass('nasa-chosen') && !$(this).find('.nasa-active').length > 0){
                if(!_fade) {
                    $(this).slideUp(_delay);
                } else {
                    $(this).fadeOut(_delay);
                }
            }
        });
        
        $(_this).fadeOut(350);
        $(_li).find('.nasa-show').fadeIn(350);
    }
});

// Login Register Form
$('body').on('click', '.nasa-switch-register', function() {
    $('#nasa-login-register-form .nasa-message').html('');
    $('.nasa_register-form, .register-form').animate({'left': '0'}, 350);
    $('.nasa_login-form, .login-form').animate({'left': '-100%'}, 350);
    
    setTimeout(function (){
        $('.nasa_register-form, .register-form').css({'position': 'relative'});
        $('.nasa_login-form, .login-form').css({'position': 'absolute'});
    }, 350);
});

$('body').on('click', '.nasa-switch-login', function() {
    $('#nasa-login-register-form .nasa-message').html('');
    $('.nasa_register-form, .register-form').animate({'left': '100%'}, 350);
    $('.nasa_login-form, .login-form').animate({'left': '0'}, 350);
    
    setTimeout(function (){
        $('.nasa_register-form, .register-form').css({'position': 'absolute'});
        $('.nasa_login-form, .login-form').css({'position': 'relative'});
    }, 350);
});

if($('.nasa-login-register-ajax').length > 0 && $('#nasa-login-register-form').length > 0) {
    $('body').on('click', '.nasa-login-register-ajax', function() {
        if ($(this).attr('data-enable') === '1' && $('#customer_login').length <= 0) {
            var _w_wrap = $('#nasa-menu-sidebar-content').outerWidth().toString();
            $('#nasa-menu-sidebar-content').animate({'left': '-' + _w_wrap + 'px', 'z-index': 399}, 500);
            $('#mobile-navigation').attr('data-show', '0');
            $('.black-window').fadeIn(200).removeClass('nasa-transparent').addClass('desk-window');
            $('.nasa-login-register-warper').show().animate({'top': '15px'}, 500);
            return false;
        }
    });
    
    // Login
    $('body').on('click', '.nasa_login-form input[type="submit"][name="nasa_login"]', function() {
        var _form = $(this).parents('form.login');
        var _data = $(_form).serializeArray();
        $.ajax({
            url: ajaxurl,
            type: 'post',
            dataType: 'json',
            data: {
                'action': 'nasa_process_login',
                'data': _data,
                'login': true
            },
            beforeSend: function(){
                $('#nasa-login-register-form #nasa_customer_login').css({opacity: 0.3});
                $('#nasa-login-register-form #nasa_customer_login').after('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
            },
            success: function(res){
                $('#nasa-login-register-form #nasa_customer_login').css({opacity: 1});
                $('#nasa-login-register-form').find('.nasa-loader, .please-wait').remove();
                var _warning = (res.error === '0') ? 'nasa-success' : 'nasa-error';
                $('#nasa-login-register-form .nasa-message').html('<h4 class="' + _warning + '">' + res.mess + '</h4>');

                if(res.error === '0') {
                    $('#nasa-login-register-form .nasa-form-content').remove();
                    window.location.href = res.redirect;
                } else {
                    if(res._wpnonce === 'error') {
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                }
            }
        });
        
        return false;
    });

    // Register
    $('body').on('click', '.nasa_register-form input[type="submit"][name="nasa_register"]', function() {
        var _form = $(this).parents('form.register');
        var _data = $(_form).serializeArray();
        $.ajax({
            url: ajaxurl,
            type: 'post',
            dataType: 'json',
            data: {
                'action': 'nasa_process_register',
                'data': _data,
                'register': true
            },
            beforeSend: function(){
                $('#nasa-login-register-form #nasa_customer_login').css({opacity: 0.3});
                $('#nasa-login-register-form #nasa_customer_login').after('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
            },
            success: function(res){
                $('#nasa-login-register-form #nasa_customer_login').css({opacity: 1});
                $('#nasa-login-register-form').find('.nasa-loader, .please-wait').remove();
                var _warning = (res.error === '0') ? 'nasa-success' : 'nasa-error';
                $('#nasa-login-register-form .nasa-message').html('<h4 class="' + _warning + '">' + res.mess + '</h4>');
                
                if(res.error === '0') {
                    $('#nasa-login-register-form .nasa-form-content').remove();
                    window.location.href = res.redirect;
                } else {
                    if(res._wpnonce === 'error') {
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                }
            }
        });
        
        return false;
    });
}

$('body').on('click', '.btn-combo-link', function(){
    var _width = $(window).outerWidth();
    var _this = $(this);
    var show_type = $(_this).attr('data-show_type');
    var wrap_item = $(_this).parents('.products.list');
    if (_width < 946 || $(wrap_item).length === 1) {
        show_type = 'popup';
    }
    
    switch (show_type) {
        case 'popup' :
            loadComboPopup($, _this);
            break;
        case 'rowdown' :
        default :
            loadComboRowDown($, _this);
            break;
    }
    
    return false;
});

if($('.nasa-upsell-product-detail').find('.nasa-upsell-slider').length > 0) {
    $('.nasa-upsell-product-detail').find('.nasa-upsell-slider').owlCarousel({
        loop: true,
        nav: true,
        dots: false,
        autoplay: true,
        autoplaySpeed: 600,
        navSpeed: 600,
        autoplayHoverPause: true,
        navText: ["", ""],
        responsive:{
            0:{
                items: 1
            }
        }
    });
}

if ($('.nasa-active').length > 0) {
    $('.nasa-active').each(function() {
        if ($(this).parents('.nasa-show-less').length === 1) {
            $(this).parents('.nasa-show-less').show();
        }
    });
}

/* 
 * custom widget top bar
 * 
 */
if($('.nasa-top-sidebar').length > 0) {
    initNasaTopSidebar($);
}

/* 
 * nasa-icon-cat-topbar
 * 
 */
if($('.nasa-filter-cat-topbar').length > 0) {
    $('body').on(_event, '.nasa-filter-cat-topbar .nasa-icon-cat-topbar a', function() {
        var _this = $(this).parents('.nasa-filter-cat-topbar').find('.nasa-root-cat-topbar-warp');
        
        if(!$(_this).hasClass('nasa-active')) {
            $(_this).parents('.nasa-filter-cat-topbar').find('.nasa-transparent-topbar').show();
            $(_this).addClass('nasa-active').fadeIn(300);
        } else {
            $(_this).parents('.nasa-filter-cat-topbar').find('.nasa-transparent-topbar').hide();
            $(_this).removeClass('nasa-active').fadeOut(300);
        }
    });
    
    $('body').on(_event, '.nasa-transparent-topbar', function() {
        var _this = $('.nasa-root-cat-topbar-warp');
        $('.nasa-transparent-topbar').hide();
        $(_this).removeClass('nasa-active').fadeOut(300);
    });
}

/*
 * Togle show topbar filter
 */
$('body').on('click', '.nasa-togle-topbar', function() {
    var _this = $(this);
    if(!$(_this).hasClass('disable')) {
        $(_this).addClass('disable');
        var data_show = $(_this).attr('data-filter');
        switch(data_show) {
            case '0':
                $(_this).attr('data-filter', '1');
                $('.nasa-hide-filter').show();
                $('.nasa-show-filter').hide();
                $('.nasa-top-sidebar').slideDown(350);
                if($('.nasa-top-sidebar').hasClass('hidden-tag')) {
                    setTimeout(function() {
                        $('.nasa-top-sidebar').removeClass('hidden-tag');
                    }, 350);
                }
                break;
            case '1':
            default:
                $(_this).attr('data-filter', '0');
                $('.nasa-show-filter').show();
                $('.nasa-hide-filter').hide();
                $('.nasa-top-sidebar').slideUp(350);
                break;
        }
        
        $(_this).removeClass('disable');
    }
    
    return false;
});

// Next | Prev slider
if(typeof nasa_next_prev === 'undefined') {
    $('body').on('click', '.nasa-nav-icon-slider', function(){
        var _this = $(this);
        var _wrap = $(_this).parents('.nasa-nav-carousel-wrap');
        var _do = $(_this).attr('data-do');
        var _id = $(_wrap).attr('data-id');
        if ($(_id).length === 1) {
            switch (_do) {
                case 'next':
                    $(_id).find('.owl-nav .owl-next').click();
                    break;
                case 'prev':
                    $(_id).find('.owl-nav .owl-prev').click();
                    break;
                default: break;
            }
        }
    });
}
/*
 * Init nasa git featured
 */
initThemeNasaGiftFeatured($);

/*
 * Event nasa git featured
 */
$('body').on('click', '.nasa-gift-featured-event', function() {
    var _wrap = $(this).parents('.product-item');
    if($(_wrap).find('.nasa-product-grid .btn-combo-link').length === 1) {
        $(_wrap).find('.nasa-product-grid .btn-combo-link').click();
    } else {
        if($(_wrap).find('.nasa-product-list .btn-combo-link').length === 1) {
            $(_wrap).find('.nasa-product-list .btn-combo-link').click();
        }
    }
});

/**
 * Carousel combo gift product single summary
 */
if($('.nasa-content-combo-gift .nasa-combo-slider').length > 0) {
    var _carousel = $('.nasa-content-combo-gift .nasa-combo-slider');
    loadCarouselCombo($, _carousel, 4);
}

/**
 * static-block-wrapper => SUPPORT
 */
if($('.site-header .static-block-wrapper').find('.support-show').length === 1) {
    $('body').on('click', '.site-header .static-block-wrapper .support-show', function() {
        if($('.site-header .static-block-wrapper').find('.nasa-transparent-topbar').length <= 0) {
            $('.site-header .static-block-wrapper').append('<div class="nasa-transparent-topbar"></div>');
        }
        
        if($('.site-header .static-block-wrapper').find('.support-hide').length === 1) {
            if(!$('.site-header .static-block-wrapper .support-hide').hasClass('active')) {
                $('.static-block-wrapper .support-hide').addClass('active').fadeIn(300);
                $('.site-header .static-block-wrapper .nasa-transparent-topbar').show();
            } else {
                $('.static-block-wrapper .support-hide').removeClass('active').fadeOut(300);
                $('.site-header .static-block-wrapper .nasa-transparent-topbar').hide();
            }
        }
    });
    
    $('body').on('click', '.site-header .static-block-wrapper .nasa-transparent-topbar', function() {
        $('.static-block-wrapper .support-hide').removeClass('active').fadeOut(300);
        $('.site-header .static-block-wrapper .nasa-transparent-topbar').hide();
    });
}

/**
 * Change language
 */
if($('select[name="nasa_switch_languages"]').length > 0) {
    $('body').on('change', 'select[name="nasa_switch_languages"]', function() {
        var _act = $(this).attr('data-active');
        if(_act === '1') {
            var _url = $(this).val();
            window.location.href = _url;
        }
    });
}

/**
 * Tab reviewer
 */
$('body').on('click', '.nasa-product-details-page .woocommerce-review-link', function() {
    if ($('.nasa-product-details-page .product-details .nasa-tabs-content .reviews_tab > a').length === 1) {
        var _pos_top = $('.nasa-product-details-page .product-details .nasa-tabs-content .reviews_tab').offset().top;
        $('html, body').animate({scrollTop: (_pos_top - 200)}, 500);
        
        setTimeout(function () {
            $('.nasa-product-details-page .product-details .nasa-tabs-content .reviews_tab > a').click();
        }, 500);
    }
    
    return false;
});

/**
 * Retina logo
 */
if($('.nasa-logo-img').length > 0) {
    var pixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;
    if(pixelRatio > 1) {
        setTimeout(function() {
            var _image_width, _image_height;
            var _src_retina = '';
            $('.nasa-logo-img img').each(function() {
                if(typeof _src_retina === 'undefined' || _src_retina === '') {
                    _src_retina = $(this).attr('data-src-retina');
                }

                if(typeof _src_retina !== 'undefined' && _src_retina !== '') {
                    var _fix_size = $(this).parents('.nasa-no-fix-size-retina').length === 1 ? false : true;

                    _image_width = _fix_size ? $(this).width() : 'auto';
                    _image_height = _fix_size ? $(this).height() : 'auto';

                    $(this).css("width", _image_width);
                    $(this).css("height", _image_height);

                    $(this).attr('src', _src_retina);
                    $(this).removeAttr('srcset');
                }
            });
        }, 1000);
    }
}

/**
 * Show coupon in shopping cart
 */
$('body').on('click', '.nasa-show-coupon', function() {
    if($('.nasa-coupon-wrap').length === 1) {
        $('.nasa-coupon-wrap').toggleClass('nasa-active');
        setTimeout(function() {
            $('.nasa-coupon-wrap.nasa-active input[name="coupon_code"]').focus();
        }, 100);
    }
});

/**
 * Fixed Single form add to cart
 */
if(
    $('input[name="nasa_fixed_single_add_to_cart"]').length &&
    $('input[name="nasa_fixed_single_add_to_cart"]').val() === '1' &
    $('.nasa-product-details-page').length &&
    $('.nasa-add-to-cart-fixed').length < 1
) {
    $('body').append('<div class="nasa-add-to-cart-fixed"><div class="nasa-wrap-content-inner"><div class="nasa-wrap-content"></div></div></div>');
    var _addToCartWrap = $('.nasa-add-to-cart-fixed .nasa-wrap-content');
    
    /**
     * Main Image clone
     */
    $(_addToCartWrap).append('<div class="nasa-fixed-product-info"></div>');
    var _src = '';
    if ($('.nasa-product-details-page .product-thumbnails').length) {
        _src = $('.nasa-product-details-page .product-thumbnails .owl-item:eq(0) > a').attr('data-thumb_org') || $('.nasa-product-details-page .product-thumbnails .owl-item:eq(0) img').attr('src');
    } else {
        _src = $('.nasa-product-details-page .main-images .item-wrap:eq(0) img').attr('src');
    }

    if (_src !== '') {
        $('.nasa-fixed-product-info').append('<div class="nasa-thumb-clone"><img src="' + _src + '" /></div>');
    }
    
    /**
     * Title clone
     */
    if($('.nasa-product-details-page .product-info .entry-title').length) {
        var _title = $('.nasa-product-details-page .product-info .entry-title').html();
        
        $('.nasa-fixed-product-info').append('<div class="nasa-title-clone"><h3>' + _title +'</h3></div>');
    }
    
    /**
     * Price clone
     */
    if($('.nasa-product-details-page .product-info .price').length) {
        var _price = $('.nasa-product-details-page .product-info .price').html();
        if($('.nasa-title-clone').length) {
            $('.nasa-title-clone').append('<span class="price">' + _price + '</span>');
        }
        else {
            $('.nasa-fixed-product-info').append('<div class="nasa-title-clone"><span class="price">' + _price + '</span></div>');
        }
    }
    
    /**
     * Variations clone
     */
    if($('.nasa-product-details-page .variations_form').length) {
        $(_addToCartWrap).append('<div class="nasa-fixed-product-variations-wrap"><div class="nasa-fixed-product-variations"></div></div>');
        
        /**
         * Variations
         * 
         * @type type
         */
        var _k = 1,
            _item = 1;
        $('.nasa-product-details-page .variations_form .variations tr').each(function() {
            var _this = $(this);
            var _classWrap = 'nasa-attr-wrap-' + _k.toString();
            var _type = $(_this).find('select').attr('data-attribute_name') || $(_this).find('select').attr('name');
            
            if($(_this).find('.nasa-attr-ux_wrap').length) {
                $('.nasa-fixed-product-variations').append('<div class="nasa-attr-ux_wrap-clone ' + _classWrap + '"></div>');
                
                $(_this).find('.nasa-attr-ux').each(function () {
                    var _obj = $(this);
                    var _classItem = 'nasa-attr-ux-' + _item.toString();
                    var _classItemClone = 'nasa-attr-ux-clone-' + _item.toString();
                    var _classItemClone_target = _classItemClone;
                    var _style = '';
                    
                    if($(_obj).hasClass('nasa-attr-ux-image')) {
                        _classItemClone += ' nasa-attr-ux-image-clone';
                    }
                    
                    if($(_obj).hasClass('nasa-attr-ux-color')) {
                        _classItemClone += ' nasa-attr-ux-color-clone';
                        _style = ' style="' + $(_obj).attr('style') + '"';
                    }
                    
                    if($(_obj).hasClass('nasa-attr-ux-label')) {
                        _classItemClone += ' nasa-attr-ux-label-clone';
                    }
                    
                    var _selected = $(_obj).hasClass('selected') ? ' selected' : '';
                    var _contentItem = $(_obj).html();

                    $(_obj).addClass(_classItem);
                    $(_obj).attr('data-target', '.' + _classItemClone_target);

                    $('.nasa-attr-ux_wrap-clone.' + _classWrap).append('<a href="javascript:void(0);" class="nasa-attr-ux-clone' + _selected + ' ' + _classItemClone + ' nasa-' + _type + '" data-target=".' + _classItem + '"' + _style + '>' + _contentItem + '</a>');

                    _item++;
                });
            } else {
                $('.nasa-fixed-product-variations').append('<div class="nasa-attr-select_wrap-clone ' + _classWrap + '"></div>');
                
                var _obj = $(_this).find('select');
                
                var _label = $(_this).find('.label').length ? $(_this).find('.label').html() : '';
                
                var _classItem = 'nasa-attr-select-' + _item.toString();
                var _classItemClone = 'nasa-attr-select-clone-' + _item.toString();
                
                var _contentItem = $(_obj).html();
                
                $(_obj).addClass(_classItem).addClass('nasa-attr-select');
                $(_obj).attr('data-target', '.' + _classItemClone);
                
                $('.nasa-attr-select_wrap-clone.' + _classWrap).append(_label + '<select name="' + _type + '" class="nasa-attr-select-clone ' + _classItemClone + ' nasa-' + _type + '" data-target=".' + _classItem + '">' + _contentItem + '</select>');
                
                _item++;
            }
            
            _k++;
        });
    }
    /**
     * Class wrap simple product
     */
    else {
        $(_addToCartWrap).addClass('nasa-fixed-single-simple');
    }
    
    /**
     * Add to cart button
     */
    setTimeout(function() {
        var _button_wrap = nasa_clone_add_to_cart($);
        $(_addToCartWrap).append('<div class="nasa-fixed-product-btn"></div>');
        $('.nasa-fixed-product-btn').html(_button_wrap);
        var _val = $('.nasa-product-details-page form.cart input[name="quantity"]').val();
        $('.nasa-single-btn-clone input[name="quantity"]').val(_val);
    }, 250);
    
    setTimeout(function() {
        if($('.nasa-attr-ux').length) {
            $('.nasa-attr-ux').each(function() {
                var _this = $(this);
                var _targetThis = $(_this).attr('data-target');

                if($(_targetThis).length) {
                    var _disable = $(_this).hasClass('nasa-disable') ? true : false;
                    if(_disable) {
                        if(!$(_targetThis).hasClass('nasa-disable')) {
                            $(_targetThis).addClass('nasa-disable');
                        }
                    } else {
                        $(_targetThis).removeClass('nasa-disable');
                    }
                }
            });
        }
    }, 550);
    
    /**
     * Change Ux
     */
    $('body').on('click', '.nasa-attr-ux', function() {
        var _target = $(this).attr('data-target');
        if($(_target).length) {
            var _wrap = $(_target).parents('.nasa-attr-ux_wrap-clone');
            $(_wrap).find('.nasa-attr-ux-clone').removeClass('selected');
            if($(this).hasClass('selected')) {
                $(_target).addClass('selected');
            }
            
            if($('.nasa-fixed-product-btn').length) {
                setTimeout(function() {
                    var _button_wrap = nasa_clone_add_to_cart($);
                    $('.nasa-fixed-product-btn').html(_button_wrap);
                    var _val = $('.nasa-product-details-page form.cart input[name="quantity"]').val();
                    $('.nasa-single-btn-clone input[name="quantity"]').val(_val);
                }, 250);
            }
            
            setTimeout(function() {
                if($('.nasa-attr-ux').length) {
                    $('.nasa-attr-ux').each(function() {
                        var _this = $(this);
                        var _targetThis = $(_this).attr('data-target');

                        if($(_targetThis).length) {
                            var _disable = $(_this).hasClass('nasa-disable') ? true : false;
                            if(_disable) {
                                if(!$(_targetThis).hasClass('nasa-disable')) {
                                    $(_targetThis).addClass('nasa-disable');
                                }
                            } else {
                                $(_targetThis).removeClass('nasa-disable');
                            }
                        }
                    });
                }
            }, 250);
        }
    });
    
    /**
     * Change Ux clone
     */
    $('body').on('click', '.nasa-attr-ux-clone', function() {
        var _target = $(this).attr('data-target');
        if($(_target).length) {
            $(_target).click();
        }
    });
    
    /**
     * Change select
     */
    $('body').on('change', '.nasa-attr-select', function() {
        var _this = $(this);
        var _target = $(_this).attr('data-target');
        var _value = $(_this).val();
        
        if($(_target).length) {
            setTimeout(function() {
                var _html = $(_this).html();
                $(_target).html(_html);
                $(_target).val(_value);
            }, 100);
            
            setTimeout(function() {
                var _button_wrap = nasa_clone_add_to_cart($);
                $('.nasa-fixed-product-btn').html(_button_wrap);
                var _val = $('.nasa-product-details-page form.cart input[name="quantity"]').val();
                $('.nasa-single-btn-clone input[name="quantity"]').val(_val);
                
                if($('.nasa-attr-ux').length) {
                    $('.nasa-attr-ux').each(function() {
                        var _this = $(this);
                        var _targetThis = $(_this).attr('data-target');

                        if($(_targetThis).length) {
                            var _disable = $(_this).hasClass('nasa-disable') ? true : false;
                            if(_disable) {
                                if(!$(_targetThis).hasClass('nasa-disable')) {
                                    $(_targetThis).addClass('nasa-disable');
                                }
                            } else {
                                $(_targetThis).removeClass('nasa-disable');
                            }
                        }
                    });
                }
            }, 250);
        }
    });
    
    /**
     * Change select clone
     */
    $('body').on('change', '.nasa-attr-select-clone', function() {
        var _target = $(this).attr('data-target');
        var _value = $(this).val();
        if($(_target).length) {
            $(_target).val(_value).change();
        }
    });
    
    /**
     * Reset variations
     */
    $('body').on('click', '.reset_variations', function() {
        $(_addToCartWrap).find('.selected').removeClass('selected');
        
        setTimeout(function() {
            var _button_wrap = nasa_clone_add_to_cart($);
            $('.nasa-fixed-product-btn').html(_button_wrap);
            var _val = $('.nasa-product-details-page form.cart input[name="quantity"]').val();
            $('.nasa-single-btn-clone input[name="quantity"]').val(_val);
            
            if($('.nasa-attr-ux').length) {
                $('.nasa-attr-ux').each(function() {
                    var _this = $(this);
                    var _targetThis = $(_this).attr('data-target');

                    if($(_targetThis).length) {
                        var _disable = $(_this).hasClass('nasa-disable') ? true : false;
                        if(_disable) {
                            if(!$(_targetThis).hasClass('nasa-disable')) {
                                $(_targetThis).addClass('nasa-disable');
                            }
                        } else {
                            $(_targetThis).removeClass('nasa-disable');
                        }
                    }
                });
            }
        }, 250);
    });
    
    /**
     * Plus, Minus button
     */
    $('body').on('click', '.nasa-product-details-page form.cart .quantity .plus, .nasa-product-details-page form.cart .quantity .minus', function() {
        if($('.nasa-single-btn-clone input[name="quantity"]').length) {
            var _val = $('.nasa-product-details-page form.cart input[name="quantity"]').val();
            $('.nasa-single-btn-clone input[name="quantity"]').val(_val);
        }
    });
    
    /**
     * Plus clone button
     */
    $('body').on('click', '.nasa-single-btn-clone .plus', function() {
        if($('.nasa-product-details-page form.cart .quantity .plus').length) {
            $('.nasa-product-details-page form.cart .quantity .plus').click();
        }
    });
    
    /**
     * Minus clone button
     */
    $('body').on('click', '.nasa-single-btn-clone .minus', function() {
        if($('.nasa-product-details-page form.cart .quantity .minus').length) {
            $('.nasa-product-details-page form.cart .quantity .minus').click();
        }
    });
    
    /**
     * Quantily input
     */
    $('body').on('keyup', '.nasa-product-details-page form.cart input[name="quantity"]', function() {
        var _val = $(this).val();
        $('.nasa-single-btn-clone input[name="quantity"]').val(_val);
    });
    
    /**
     * Quantily input clone
     */
    $('body').on('keyup', '.nasa-single-btn-clone input[name="quantity"]', function() {
        var _val = $(this).val();
        $('.nasa-product-details-page form.cart input[name="quantity"]').val(_val);
    });
    
    /**
     * Add to cart click
     */
    $('body').on('click', '.nasa-single-btn-clone .single_add_to_cart_button', function(){
        if($('.nasa-product-details-page form.cart .single_add_to_cart_button').length) {
            $('.nasa-product-details-page form.cart .single_add_to_cart_button').click();
        }
    });
}

initWishlistIcons($);

});