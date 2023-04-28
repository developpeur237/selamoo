"use strict";
/* Functions jquery ================================ */
function nasa_check_iOS() {
    var iDevices = [
        'iPad Simulator',
        'iPhone Simulator',
        'iPod Simulator',
        'iPad',
        'iPhone',
        'iPod'
    ];
    while (iDevices.length > 0) {
        if (navigator.platform === iDevices.pop()){
            return true;
        }
    }
    return false;
}

function nasa_load_ajax_funcs($) {
    loadingCarouselNasaCore($);
    loadingSCCaroselNasaCore($);
    loadCountDownNasaCore($);
    reponsiveBanners($);
    loadCorouselHasThumbs($);
    loadingSlickHasExtraVerticalNasaCore($);
    loadingSlickVerticalCategories($);
    nasa_loadTipTop($);
    
    initNasaGiftFeatured($);
    nasaRenderTagClouds($);
    
    nasaLoadHeightFullWidthToSide($);
    loadPinProductsBanner($);
}

function reponsiveBanners($) {
    var _wH = $(window).width();
    if($('.nasa-banner-image').length > 0) {
        var _regex = /(large-\d+)/g;
        var _col, _col_wrap;
        var _zoom, _zoom_wrap, _resize;
        $('.nasa-banner-image').each(function() {
            _col = _col_wrap = 12;
            var _this = $(this);
            var _parent = $(_this).parent();
            var _w_df = $(_parent).closest('.row').closest('.columns');
            
            if($(_w_df).length > 0) {
                var _match_wrap = $(_w_df).attr('class').match(_regex);
                _col_wrap = _match_wrap !== null ? parseInt(_match_wrap[0].replace("large-", "")) : _col_wrap;
            }
            
            var _large = $(_this).parents('.columns');
            if($(_large).length > 0) {
                var _match = $(_large).attr('class').match(_regex);
                _col = _match !== null ? parseInt(_match[0].replace("large-", "")) : _col;
            }
            var _defH = parseInt($(_this).attr('data-height'));
            _zoom = 12/_col;
            _zoom_wrap = 12/_col_wrap;
            if(_wH < 946) {
                _resize = (_wH / 1200) * _zoom_wrap;
                $(_parent).height(_defH * _resize * _zoom);
                $(_parent).find('.nasa-banner-content').css({'font-size': (_zoom * 100 * _resize).toString() + '%'});
            } else if(_wH > 946 && _wH < 1200) {
                _resize = _wH / 1200;
                $(_parent).height(_defH * _resize);
                $(_parent).find('.nasa-banner-content').css({'font-size': (100 * _resize).toString() + '%'});
            } else {
                $(_parent).height(_defH);
                $(_parent).find('.nasa-banner-content').css({'font-size': '100%'});
            }
        });
    }
}

function loadMorePortfolio(jq, cat_id, columns, paged){
    jq.ajax({
        url : ajaxurl_core,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'get_more_portfolio',
            page: paged,
            category: cat_id,
            col: columns
        },
        beforeSend: function(){
            jq('.loadmore-portfolio').before('<div id="ajax-loading"></div>');
            jq('.loadmore-portfolio').hide();
            jq('.portfolio-list').css({'overflow': 'hidden'});
        },
        success: function(res){
            jq('#ajax-loading').remove();
            jq('.loadmore-portfolio').show();
            if(res.success){
                jq('.portfolio-list').isotope('insert', jq(res.result)).isotope({itemSelector:'.portfolio-item'});
                setTimeout(function () {
                    jq('.portfolio-list').isotope({itemSelector:'.portfolio-item'});
                }, 800);
                if(paged >= res.max){
                    jq('.loadmore-portfolio').addClass('end-portfolio').html(res.alert).removeClass('loadmore-portfolio');
                }
            } else {
                jq('.loadmore-portfolio').addClass('end-portfolio').html(res.alert).removeClass('loadmore-portfolio');
            }
            portfolio_load_flag = false;
        }
    });
    
    return false;
};

function loadingCarouselNasaCore($){
    $('.nasa-slider').each(function(){
        var _this = $(this);
        if(!$(_this).hasClass('owl-loaded')){
            var cols = $(_this).attr('data-columns'),
                cols_small = $(_this).attr('data-columns-small'),
                cols_tablet = $(_this).attr('data-columns-tablet'),

                autoplay_enable = ($(_this).attr('data-autoplay') === 'true') ? true : false,
                loop_enable = ($(_this).attr('data-loop') === 'true') ? true : false,
                dot_enable = ($(_this).attr('data-dot') === 'true') ? true : false,
                nav_disable = ($(_this).attr('data-disable-nav') === 'true') ? false : true,
                height_auto = ($(_this).attr('data-height-auto') === 'true') ? true : false,

                margin_px = parseInt($(_this).attr('data-margin')),
                margin_small = parseInt($(_this).attr('data-margin_small')),
                ap_speed = parseInt($(_this).attr('data-speed')),
                ap_delay = parseInt($(_this).attr('data-delay')),
                disable_drag = ($(_this).attr('data-disable-drag') === 'true') ? false : true,
                padding = $(_this).attr('data-padding') ? $(_this).attr('data-padding') : false;
            
            if (!margin_px && margin_px !== 0) {
                margin_px = 10;
            }
            
            if (!margin_small && margin_small !== 0) {
                margin_small = margin_px;
            }

            if(!ap_speed){
                ap_speed = 600;
            }

            if(!ap_delay){
                ap_delay = 3000;
            }

            var nasa_slider_params = {
                nav: nav_disable,
                autoplay: autoplay_enable,
                autoplaySpeed: ap_speed,
                loop: loop_enable,
                dots: dot_enable,
                autoplayTimeout: ap_delay,
                autoplayHoverPause: true,
                responsiveClass: true,
                navText: ["",""],
                navSpeed: 600,
                lazyLoad : true,
                touchDrag: disable_drag,
                mouseDrag: disable_drag,
                responsive: {
                    0:{
                        items: cols_small,
                        margin: margin_small,
                        nav:false
                    },
                    600:{
                        items:cols_tablet
                    },
                    1000:{
                        items:cols
                    }
                }
            };

            if (margin_px){
                nasa_slider_params.margin = margin_px;
            }
            
            if (height_auto) {
                nasa_slider_params.autoHeight = true;
            }

            $(_this).owlCarousel(nasa_slider_params);
            
            if(padding){
                $(_this).find('> .owl-stage-outer').css({'padding-bottom':padding, 'margin-bottom': '-' + padding, 'height': 'auto'});
            }
            
            // Fix height tabable content slide
            var _height = $(_this).height();
            if(_height > 0 && $(_this).parents('.nasa-panels').length > 0) {
                $(_this).parents('.nasa-panels').css({'min-height': _height});
            }
            
            _this.on('resized.owl.carousel', function() {
                nasaLoadHeightDealBlock($);
            });
        }
    });
}

function loadingSCCaroselNasaCore($){
    if($('.nasa-sc-carousel').length > 0){
        $('.nasa-sc-carousel').each(function(){
            var _sc = $(this);
            if(!$(_sc).hasClass('owl-loaded')){
                var _key = $(_sc).attr('data-contruct');
                var owl = $('#item-slider-' + _key);
                var height = ($(owl).find('.banner').length > 0) ? $(owl).find('.banner').height() : 0;
                if(height){
                    var loading = '<div class="nasa-carousel-loadding" style="height: ' + height + 'px;"><div class="please-wait type2"></div></div>';
                    $(owl).parent().append(loading);
                }

                var _nav = ($(_sc).attr('data-nav') === 'true') ? true : false,
                    _dots = ($(_sc).attr('data-dots') === 'true') ? true : false,
                    _autoplay = ($(_sc).attr('data-autoplay') === 'false') ? false : true,
                    _loop = ($(_sc).attr('data-loop') === 'true') ? true : false,
                    _speed = parseInt($(_sc).attr('data-speed')),
                    _itemSmall = parseInt($(_sc).attr('data-itemSmall')),
                    _itemTablet = parseInt($(_sc).attr('data-itemTablet')),
                    _items = parseInt($(_sc).attr('data-items')),

                _speed = _speed ? _speed : 3000;
                _itemSmall = _itemSmall ? _itemSmall : 1;
                _itemTablet = _itemTablet ? _itemTablet : 1;
                _items = _items ? _items : 1;
                owl.owlCarousel({
                    loop: _loop,
                    nav: _nav,
                    dots: _dots,
                    autoplay: _autoplay,
                    autoplaySpeed: _speed, // Speed when auto play
                    autoplayTimeout: 5000, //Delay for next slide
                    autoplayHoverPause : true,
                    navText: ["", ""],
                    navSpeed: _speed, //Speed when click to navigation arrow
                    dotsSpeed: _speed,
                    responsiveClass: true,
                    callbacks: true,
                    responsive:{
                        0:{
                            items: _itemSmall,
                            nav: false
                        },
                        600:{
                            items: _itemTablet,
                            nav: false
                        },
                        1000:{
                            items: _items,
                            nav: _nav
                        }
                    }
                });

                owl.find('.owl-item').each(function(){
                    var _this = $(this);
                    if($(_this).find('.banner .banner-inner').length > 0){
                        var _banner = $(_this).find('.banner .banner-inner');
                        $(_banner).removeAttr('class').removeAttr('style').addClass('banner-inner');
                        if($(_this).hasClass('active')){
                            var animation = $(_banner).attr('data-animation');
                            setTimeout(function(){
                                $(_banner).show();
                                $(_banner).addClass('animated').addClass(animation).css({'visibility': 'visible', 'animation-duration': '1s', 'animation-delay': '0ms', 'animation-name': animation});
                            }, 200);
                        }
                    }
                });

                owl.on('translated.owl.carousel', function(items) {
                    var warp = items.target;
                    if($(warp).find('.owl-item').length > 0){
                        $(warp).find('.owl-item').each(function(){
                            var _this = $(this);
                            if($(_this).find('.banner .banner-inner').length > 0){
                                var _banner = $(_this).find('.banner .banner-inner');
                                var animation = $(_banner).attr('data-animation');
                                $(_banner).removeClass('animated').removeClass(animation).removeAttr('style');
                                if($(_this).hasClass('active')){
                                    setTimeout(function(){
                                        $(_banner).show();
                                        $(_banner).addClass('animated').addClass(animation).css({'visibility': 'visible', 'animation-duration': '1s', 'animation-delay': '0ms', 'animation-name': animation});;
                                    }, 200);
                                }
                            }
                        });
                    }
                });
                
                $(owl).parent().find('.nasa-carousel-loadding').remove();
            }
        });
    }
}

function loadCountDownNasaCore($) {
    var countDownEnable = ($('input[name="nasa-count-down-enable"]').length === 1 && $('input[name="nasa-count-down-enable"]').val() === '1') ? true : false;
    if (countDownEnable && $('.countdown').length > 0) {
        $('.countdown').each(function() {
            var count = $(this);
            if (!$(count).hasClass('countdown-loaded')) {
                var austDay = new Date(count.data('countdown'));
                $(count).countdown({
                    until: austDay,
                    padZeroes: true
                });
                
                if($(count).hasClass('pause')) {
                    $(count).countdown('pause');
                }
                
                $(count).addClass('countdown-loaded');
            }
        });
    }
}

function loadCorouselMain(id, $){
    $('.main-images-' + id).owlCarousel({
        items: 1,
        nav: false,
        lazyLoad: true,
        autoplaySpeed: 600,
        dots: false,
        autoHeight: true,
        autoplay: false,
        loop: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        responsiveClass:true,
        navText: ["",""],
        navSpeed: 600
    });

    $('.main-images-' + id).on('change.owl.carousel', function(e) {
        var currentItem = e.relatedTarget.relative(e.property.value);
        var owlThumbs = $(".product-thumbnails-" + id + " .owl-item");
        $('.product-thumbnails-' + id + ' .active-thumbnail').removeClass('active-thumbnail')
        $(".product-thumbnails-" + id).find('.owl-item').eq(currentItem).addClass('active-thumbnail');
        owlThumbs.trigger('to.owl.carousel', [currentItem, 300, true]);
    }).data('owl.carousel');
    
    $('.product-thumbnails-' + id).owlCarousel({
        items: 4,
        loop: false,
        nav: false,
        autoplay: false,
        dots: false,
        autoHeight: false,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        responsiveClass: true,
        navText: ["", ""],
        navSpeed: 600,
        responsive:{
            "0": {
                items: 3,
                nav: false
            },
            "600": {
                items: 4,
                nav: false
            },
            "1000": {
                items: 4,
                nav: false
            }
        }
    }).on('click', '.owl-item', function () {
        var currentItem = $(this).index();
        $('.main-images-' + id).trigger('to.owl.carousel', [currentItem, 300, true]);
    });

    $('body').on('click', '.product-thumbnails-' + id + ' .owl-item a', function(e) {
        e.preventDefault();
    });
}

function loadCorouselHasThumbs($) {
    if($('.nasa-sc-main-product').length > 0) {
        $('.nasa-sc-main-product').each(function() {
            var _this = $(this);
            var id = $(_this).attr('data-id');
            $('.nasa-product-img-slide-' + id).owlCarousel({
                items: 1,
                nav: false,
                lazyLoad: true,
                autoplaySpeed: 600,
                dots: false,
                autoHeight: true,
                autoplay: false,
                loop: false,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                responsiveClass:true,
                navText: ["",""],
                navSpeed: 600
            });

            $('.nasa-product-img-slide-' + id).on('change.owl.carousel', function(e) {
                var currentItem = e.relatedTarget.relative(e.property.value);
                var owlThumbs = $(".product-thumbnails-" + id + " .owl-item");
                $('.product-thumbnails-' + id + ' .active-thumbnail').removeClass('active-thumbnail')
                $(".product-thumbnails-" + id).find('.owl-item').eq(currentItem).addClass('active-thumbnail');
                owlThumbs.trigger('to.owl.carousel', [currentItem, 300, true]);
            }).data('owl.carousel');

            $('.product-thumbnails-' + id).owlCarousel({
                items: 4,
                loop: false,
                nav: true,
                autoplay: false,
                dots: false,
                autoHeight: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                responsiveClass: true,
                navText: ["", ""],
                navSpeed: 600,
                responsive:{
                    "0": {
                        items: 3
                    },
                    "600": {
                        items: 4
                    },
                    "1000": {
                        items: 4
                    }
                }
            }).on('click', '.owl-item', function () {
                var currentItem = $(this).index();
                $('.nasa-product-img-slide-' + id).trigger('to.owl.carousel', [currentItem, 300, true]);
            });
        });
    }
}

function nasa_load_all_shortcodes($) {
    if ($('.nasa_load_ajax').length > 0) {
        var _shortcode = {};
        $('.nasa_load_ajax').each(function() {
            var _this = $(this),
                _parent = $(_this).parent(),
                _idshortcode = $(_this).attr('data-id');
            if (!$(_parent).hasClass('nasa-panel') || $(_parent).hasClass('active')) {
                _shortcode["nasa_sc_" + _idshortcode] = $(_this).find('.nasa-shortcode-content').text();
            }
        });

        // Call ajax do_shortcode;
        if(_shortcode) {
            $.ajax({
                url: ajaxurl_core,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'nasa_load_ajax_all',
                    shortcode: _shortcode,
                    nasa_load_ajax: '1'
                },
                beforeSend: function(){

                },
                success: function(res){
                    if($('#yith-wcwl-popup-message').length < 1) {
                        $('body').prepend('<div id="yith-wcwl-popup-message" style="display: none;"><div id="yith-wcwl-message"></div></div>');
                    }

                    $('.nasa_load_ajax').each(function() {
                        var _this = $(this);
                        if(res['nasa_sc_' + $(_this).attr('data-id')] !== undefined) {
                            var _p = $(_this).parent();
                            
                            if($(_p).find('.nasa-tranparent-absolute').length <= 0) {
                                $(_p).css({'position': 'relative'});
                                $(_p).append('<div class="nasa-tranparent-absolute"></div>');
                            }
                            
                            $(_this).replaceWith(res['nasa_sc_' + $(_this).attr('data-id')]).hide().fadeIn(200);
                        }
                    });

                    nasa_load_ajax_funcs($);
                    setTimeout(function(){
                        $('.nasa-tranparent-absolute').remove();
                    }, 600);
                }
            });
        }
    }
}

function nasa_load_shortcodes($, _this) {
    var _shortcode_name = $(_this).attr('data-shortcode'),
        _shortcode = $(_this).find('.nasa-shortcode-content').text();
    if(_shortcode && _shortcode_name) {
        $.ajax({
            url: ajaxurl_core,
            type: 'post',
            data: {
                action: 'nasa_load_ajax_item',
                shortcode: _shortcode,
                shortcode_name: _shortcode_name,
                nasa_load_ajax: '1'
            },
            beforeSend: function(){
                
            },
            success: function(res){
                if($('#yith-wcwl-popup-message').length < 1) {
                    $('body').prepend('<div id="yith-wcwl-popup-message" style="display: none;"><div id="yith-wcwl-message"></div></div>');
                }
                
                var _p = $(_this).parent();
                
                if($(_p).find('.nasa-tranparent-absolute').length <= 0) {
                    $(_p).css({'position': 'relative'});
                    $(_p).append('<div class="nasa-tranparent-absolute"></div>');
                }
                
                $(_this).replaceWith(res);
                
                nasa_load_ajax_funcs($);
                setTimeout(function(){
                    $('.nasa-tranparent-absolute').remove();
                }, 600);
            }
        });
    }
}

function nasa_loadTipTop($) {
    if ($('.tip-top').length > 0) {
        var tip, option;
        $('.tip-top').each(function() {
            option = {mode:"top"};
            tip = $(this);
            if($(tip).parents('.nasa-group-btn-in-list') <= 0) {
                if (!$(tip).hasClass('nasa-tiped')) {
                    $(tip).addClass('nasa-tiped');
                    if ($(tip).attr('data-pos') === 'bot') {
                        option = {mode:"bottom"};
                    }

                    $(tip).tipr(option);
                }
            }
        });
    }
}

/*
 * Nasa gift featured
 */
function initNasaGiftFeatured($) {
    var _enable = ($('input[name="nasa-enable-gift-effect"]').length === 1 && $('input[name="nasa-enable-gift-effect"]').val() === '1') ? true : false;
    
    if(_enable && $('.nasa-gift-featured-event').length > 0) {
        var _delay = 0;
        $('.nasa-gift-featured-event').each(function(){
            var _this = $(this);
            if(!$(_this).hasClass('nasa-inited')) {
                $(_this).addClass('nasa-inited');
                var _wrap = $(_this).parents('.nasa-gift-featured-wrap');
                setTimeout(function() {
                    setInterval(function() {
                        $(_wrap).animate({'font-size': '250%'}, 300);
                        setTimeout(function() {
                            $(_wrap).animate({'font-size': '180%'}, 300);
                        }, 300);
                        setTimeout(function() {
                            $(_wrap).animate({'font-size': '250%'}, 300);
                        }, 600);
                        setTimeout(function() {
                            $(_wrap).animate({'font-size': '100%'}, 300);
                        }, 900);
                    }, 4000);
                }, _delay);
                
                _delay += 900;
            }
        });
    }
}

function nasaRenderTagClouds($) {
    if($('.nasa-tag-cloud').length > 0) {
        var _cat_act = parseInt($('.nasa-has-filter-ajax').find('.current-cat a').attr('data-id'));
        var re = /(tag-link-\d+)/g;
        $('.nasa-tag-cloud').each(function (){
            var _this = $(this),
                _taxonomy = $(_this).attr('data-taxonomy');
            
            if(!$(_this).hasClass('nasa-taged')) {
                var _term_id;
                $(_this).find('a').each(function(){
                    var _class = $(this).attr('class');
                    var m = _class.match(re);
                    _term_id = m !== null ? parseInt(m[0].replace("tag-link-", "")) : false;
                    if(_term_id){
                        $(this).addClass('nasa-filter-by-cat').attr('data-id', _term_id).attr('data-taxonomy', _taxonomy).removeAttr('style');
                        if(_term_id === _cat_act){
                            $(this).addClass('nasa-active');
                        }
                    }
                });
                
                $(_this).addClass('nasa-taged');
            }
        });
    }
}

/*
 * nasaLoadHeightMainProducts($)
 */
function nasaLoadHeightMainProducts($) {
    if($('.nasa-main-content-warp').length > 0) {
        var bodyWidth = $('body').width();
        if(bodyWidth > 945) {
            $('.nasa-main-content-warp').each(function() {
                var _this = $(this);
                var _sc = $(_this).parents('.nasa-sc-main-extra-product');
                var _side = $(_sc).find('.nasa-product-main-aside.first');
                if($(_side).length === 1 && $(_side).find('.product-item').length === 2) {
                    var _height = $(_side).height();
                    $(_this).css({'min-height': _height});
                }
            });
        } else {
            $('.nasa-main-content-warp').css({'min-height': 'auto'});
        }
    }
}

/**
 * NasaLoadheightDealBlock
 */
function nasaLoadHeightDealBlock($) {
    if($('.nasa-row-deal-3').length > 0) {
        var bodyWidth = $('body').width();
        
        if(bodyWidth > 945) {
            $('.nasa-row-deal-3').each(function() {
                var _this = $(this);
                var _sc = $(_this).find('.main-deal-block .nasa-sc-pdeal-block');
                var _side = $(_this).find('.nasa-sc-product-deals-grid.nasa-deal-right');
                if($(_side).length === 1) {
                    var _height = $(_side).height();
                    $(_sc).css({'min-height': _height - 30});
                }
            });
        } else {
            $('.nasa-row-deal-3 .main-deal-block .nasa-sc-pdeal-block').css({'min-height': 'auto'});
        }
    }
}

/**
 * Load height full to side
 */
function nasaLoadHeightFullWidthToSide($) {
    if($('#main-content #content > .section-element > .row > .columns.nasa-full-to-left, #main-content #content > .section-element > .row > .columns.nasa-full-to-right').length > 0) {
        var _wwin = $(window).width();
        $('#main-content #content > .section-element > .row > .columns.nasa-full-to-left, #main-content #content > .section-element > .row > .columns.nasa-full-to-right').each(function() {
            var _this = $(this);
            if(_wwin > 1200) {
                var _hElement = $(_this).outerHeight();
                var _hWrap = $(_this).parent().height();
                if(_hWrap <= _hElement) {
                    $(_this).parent().css({'min-height': _hElement});
                } else {
                    $(_this).parent().css({'min-height': 'auto'});
                }
            } else {
                $(_this).parent().css({'min-height': 'auto'});
            }
        });
    }
}

/**
 * slick slide has extra vertical
 */
function loadingSlickHasExtraVerticalNasaCore($){
    $('.nasa-slider-deal-has-vertical').each(function(){
        var _this = $(this);
        if(!$(_this).hasClass('slick-initialized')) {
            var id = $(_this).attr('data-id'),
                _autoplay = $(_this).attr('data-autoplay') === 'true' ? true : false,
                _loop = $(_this).attr('data-loop') === 'false' ? false : true,
                _speed = parseInt($(_this).attr('data-speed')),
                _delay = parseInt($(_this).attr('data-delay'));

            _speed = !_speed ? 600 : _speed;
            _delay = !_delay ? 3000 : _delay;
            
            var _setting = {
                vertical: true,
                verticalSwiping: true,
                slidesToShow: 4,
                dots: false,
                arrows: false,
                infinite: _loop
            };

            _setting.asNavFor = '#nasa-slider-slick-' + id;
            _setting.slidesToScroll = 1;
            _setting.centerMode = false;
            _setting.centerPadding = '0px';
            _setting.focusOnSelect = true;
            _setting.responsive = [{
                breakpoint: 500,
                settings: {
                    slidesToShow: 1
                }
            }];
        
            $('.nasa-slider-deal-vertical-extra-' + id).attr('data-speed', _speed);

            $(_this).slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: _autoplay,
                autoplaySpeed: _delay,
                speed: _speed,
                arrows: true,
                infinite: _loop,
                asNavFor: '.nasa-slider-deal-vertical-extra-' + id
            });
            
            $('.nasa-slider-deal-vertical-extra-' + id).slick(_setting);
            $(_this).addClass('slick-initialized');
        }
    });
}

/**
 * Load categories vertical slide
 * Slick slider - Vertical slider
 */
function loadingSlickVerticalCategories($) {
    if($('.nasa-vertical-slider').length > 0){
        $('.nasa-vertical-slider').each(function(){
            var _this = $(this);
            if(!$(_this).hasClass('slick-initialized')) {
                var _change = parseInt($(_this).attr('data-change'));
                var _speed = parseInt($(_this).attr('data-speed'));
                var _delay = parseInt($(_this).attr('data-delay'));

                var _show = $(_this).attr('data-show') ? $(_this).attr('data-show') : '1',
                    _autoplay = $(_this).attr('data-autoplay') === 'false' ? false : true,
                    _delay = _delay ? _delay : 3000,
                    _speed = _speed ? _speed : 1000,
                    _change = _change ? _change : false,
                    _dot = $(_this).attr('data-dot') === 'true' ? true : false,
                    _arrows = $(_this).attr('data-arrows') === 'true' ? true : false;

                var _setting = {
                    vertical: true,
                    verticalSwiping: true,
                    slidesToShow: _show,
                    autoplay: _autoplay,
                    autoplaySpeed: _delay,
                    speed: _speed,
                    dots: _dot,
                    arrows: _arrows
                };

                if(_change){
                    _setting.slidesToScroll = _change;
                }

                $(_this).slick(_setting);
                
                $(_this).addClass('slick-initialized');
            }
        });
    }
}

/**
 * Change variation
 * 
 * @param {type} $
 * @param {type} _variations_warp
 * @param {type} _alert
 * @returns {undefined}
 */
function change_image_content_product_variable($, _variations_warp, _alert) {
    _alert = (typeof _alert === 'undefined') ? false : _alert;
    
    var _count_attr = $(_variations_warp).find('.nasa-product-content-child').length,
        _selected_count = $(_variations_warp).find('.nasa-product-content-child .nasa-active').length,
        _product_item = $(_variations_warp).parents('.product-item'),
        _main_img = $(_product_item).find('.main-img img'),
        _main_src = $(_main_img).attr('data-org_img'),
        _back_img = $(_product_item).find('.back-img img'),
        _back_src = $(_back_img).attr('data-org_img'),
        _add_wrap = $(_product_item).find('.add-to-cart-btn a.add-to-cart-grid');

    var _main_srcset = $(_main_img).attr('srcset'),
        _back_srcset = $(_back_img).attr('srcset');

    var _main_data_srcset = $(_main_img).attr('data-srcset'),
        _back_data_srcset = $(_back_img).attr('data-srcset');
    
    var _variations = JSON.parse($(_variations_warp).attr('data-product_variations'));
    
    /**
     * Refress attribute
     */
    var _choseAttrs = nasa_chosen_attrs($, _variations_warp),
        currentAttributes = _choseAttrs.data;
    $(_variations_warp).find('.nasa-product-content-child').each( function() {
        var current_attr_name = 'attribute_pa_' + $(this).find('.nasa-attr-ux-item:eq(0)').attr('data-pa');
        var checkAttributes = $.extend(true, {}, currentAttributes);
        checkAttributes[current_attr_name] = '';
        var _new_variations = nasa_matching_variations(_variations, checkAttributes);
        var _enable = [];

        /**
         * Init array attributes name
         */
        if(typeof _new_variations !== 'undefined') {
            for (var k1 in _new_variations) {
                var _attrs1 = _new_variations[k1].attributes;
                for (var name1 in _attrs1) {
                    if(name1 === current_attr_name && _attrs1[name1] !== '') {
                        _enable.push(_attrs1[name1]);
                    }
                }
            }
        }

        /**
         * Disable variations out of stock
         */
        if(_enable.length) {
            var _pa_name = current_attr_name.replace('attribute_pa_', '');
            if($(_variations_warp).find('.nasa-product-content-' + _pa_name + '-wrap-child .nasa-attr-ux-item').length) {
                $(_variations_warp).find('.nasa-product-content-' + _pa_name + '-wrap-child .nasa-attr-ux-item').each(function() {
                    var _nasa_item = $(this);
                    var _value_item = $(_nasa_item).attr('data-value');
                    if(_enable.indexOf(_value_item) === -1) {
                        if(!$(_nasa_item).hasClass('nasa-disable')) {
                            $(_nasa_item).addClass('nasa-disable');
                        }
                    } else {
                        $(_nasa_item).removeClass('nasa-disable');
                    }
                });
            }
        }
    });
    
    /**
     * Old Price
     */
    if($(_product_item).find('.nasa-org-price.hidden-tag').length <= 0) {
        $(_product_item).find('.price').after('<div class="nasa-org-price hidden-tag">' + $(_product_item).find('.price').html() + '</div>');
    }

    /**
     * Old Add to cart text
     */
    if(typeof $(_variations_warp).attr('data-select_text') === 'undefined') {
        $(_variations_warp).attr('data-select_text', $(_add_wrap).find('.add_to_cart_text').html());
    }

    var _select_text = $(_variations_warp).attr('data-select_text');

    /**
     * Not select full attributes
     */
    if(_count_attr !== _selected_count) {
        if(typeof _main_src !== 'undefined') {
            $(_main_img).attr('src', _main_src);
            
            if(_main_data_srcset) {
                $(_main_img).attr('srcset', _main_data_srcset);
            }
        }

        if(typeof _back_src !== 'undefined') {
            $(_back_img).attr('src', _back_src);
            
            if(_back_data_srcset) {
                $(_back_img).attr('srcset', _back_data_srcset);
            }
        }

        $(_add_wrap).find('.add_to_cart_text').html(_select_text);
        $(_add_wrap).attr('title', _select_text);
        $(_add_wrap).attr('data-product_id', $(_variations_warp).attr('data-product_id')).addClass('product_type_variable').removeClass('product_type_variation').removeAttr('data-variation');
        $(_product_item).find('.price').html($(_product_item).find('.nasa-org-price').html());
        $(_product_item).find('.add-to-cart-btn').removeClass('nasa-active');

        return;
    }
    
    /**
     * Select full Attributes
     */
    else {
        var _selected_attr = [];
        var _variation = {};
        $(_variations_warp).find('.nasa-product-content-child .nasa-active').each(function(){
            var _attr = $(this),
                _attr_name = 'attribute_pa_' + $(_attr).attr('data-pa'),
                _attr_val = $(_attr).attr('data-value'),
                _attr_selected = {
                    'key': _attr_name,
                    'value': _attr_val
                };

            _variation[_attr_name] = _attr_val;
            _selected_attr.push(_attr_selected);
        });
        
        var _finded = false;
        var _variation_finded = null;
        for (var k in _variations) {
            var _attrs = _variations[k].attributes,
                _total_attr = 0;
            for (var k_attr in _attrs) {
                _total_attr++;
            }

            if(_count_attr !== _total_attr) {
                break;
            }

            for (var k_select in _selected_attr) {
                if(_attrs[_selected_attr[k_select].key] === '' || _attrs[_selected_attr[k_select].key] === _selected_attr[k_select].value) {
                    _finded = true;
                } else {
                    _finded = false;
                    break;
                }
            }

            if(_finded) {
                _variation_finded = _variations[k];
                break;
            }
        }

        /**
         * Matching variation
         */
        if(_variation_finded) {
            /**
             * Change image show
             */
            var _org_img = _main_src ? _main_src : $(_main_img).attr('src');
            _org_img = _org_img.replace('https:', '');
            _org_img = _org_img.replace('http:', '');
            var _image_catalog = '';
            if(_variation_finded.image_catalog !== 'undefined') {
                _image_catalog = _variation_finded.image_catalog.replace('https:', '');
                _image_catalog = _image_catalog.replace('http:', '');
            }

            if(
                typeof _variation_finded.image_catalog !== 'undefined' &&
                _variation_finded.image_catalog !== '' &&
                _image_catalog !== _org_img
            ) {
                if(typeof _main_src === 'undefined') {
                    $(_main_img).attr('data-org_img', $(_main_img).attr('src'));
                }

                if(typeof _back_src === 'undefined') {
                    $(_back_img).attr('data-org_img', $(_back_img).attr('src'));
                }

                $(_main_img).attr('src', _variation_finded.image_catalog);
                $(_back_img).attr('src', _variation_finded.image_catalog);
                
                if(_main_srcset) {
                    $(_main_img).removeAttr('srcset');
                    $(_main_img).attr('data-srcset', _main_srcset);
                }
                
                if(_back_srcset) {
                    $(_back_img).removeAttr('srcset');
                    $(_back_img).attr('data-srcset', _back_srcset);
                }
            }

            else {
                if(typeof _main_src !== 'undefined') {
                    $(_main_img).attr('src', _main_src);
                    
                    if(_main_data_srcset) {
                        $(_main_img).attr('srcset', _main_data_srcset);
                    }
                }

                if(typeof _back_src !== 'undefined') {
                    $(_back_img).attr('src', _back_src);
                    
                    if(_back_data_srcset) {
                        $(_back_img).attr('srcset', _back_data_srcset);
                    }
                }
            }

            /**
             * Change price and add to cart button
             */
            if(_variation_finded.variation_id && _variation_finded.is_in_stock && _variation_finded.variation_is_visible && _variation_finded.is_purchasable) {
                var _add_text = $('input[name="add_to_cart_text"]').val();
                $(_add_wrap).find('.add_to_cart_text').html(_add_text);
                $(_add_wrap).attr('title', _add_text);
                if(!$(_product_item).find('.add-to-cart-btn').hasClass('nasa-active')) {
                    $(_product_item).find('.add-to-cart-btn').addClass('nasa-active');
                }
                
                var _variObj = {};
                for(var attr_pa in _variation_finded.attributes) {
                    _variObj[attr_pa] = _variation[attr_pa];
                }

                if(_variation_finded.price_html) {
                    $(_product_item).find('.price').replaceWith(_variation_finded.price_html);
                }

                $(_add_wrap)
                    .attr('data-product_id', _variation_finded.variation_id)
                    .removeClass('product_type_variable')
                    .addClass('product_type_variation')
                    .attr('data-variation', JSON.stringify(_variObj));
            }

            else {
                $(_add_wrap).find('.add_to_cart_text').html(_select_text);
                $(_add_wrap).attr('title', _select_text);
                $(_add_wrap)
                    .attr('data-product_id', $(_variations_warp).attr('data-product_id'))
                    .addClass('product_type_variable')
                    .removeClass('product_type_variation')
                    .removeAttr('data-variation');
                $(_product_item).find('.price').html($(_product_item).find('.nasa-org-price').html());
                $(_product_item).find('.add-to-cart-btn').removeClass('nasa-active');
            }
        }
        
        /**
         * No match
         */
        else {
            if(typeof _main_src !== 'undefined') {
                $(_main_img).attr('src', _main_src);
                
                if(_main_data_srcset) {
                    $(_main_img).attr('srcset', _main_data_srcset);
                }
            }

            if(typeof _back_src !== 'undefined') {
                $(_back_img).attr('src', _back_src);
                
                if(_back_data_srcset) {
                    $(_back_img).attr('srcset', _back_data_srcset);
                }
            }
            
            $(_add_wrap).find('.add_to_cart_text').html(_select_text);
            $(_add_wrap).attr('title', _select_text);
            $(_add_wrap).attr('data-product_id', $(_variations_warp).attr('data-product_id')).addClass('product_type_variable').removeClass('product_type_variation').removeAttr('data-variation');
            $(_product_item).find('.price').replaceWith($(_product_item).find('.nasa-org-price').html());
            $(_product_item).find('.add-to-cart-btn').removeClass('nasa-active');
            
            if(_alert) {
                var text_nomatch = (typeof wc_add_to_cart_variation_params !== 'undefined') ?
                    wc_add_to_cart_variation_params.i18n_no_matching_variations_text :
                    $('input[name="nasa_no_matching_variations"]').val();

                window.alert(text_nomatch);
            }
        }
    }
}

/**
 * Attributes selected
 * 
 * @param {type} $
 * @param {type} _variations_warp
 * @returns {}
 */
function nasa_chosen_attrs($, _variations_warp) {
    var data = {};
    var count = 0;
    var chosen = 0;

    $(_variations_warp).find('.nasa-product-content-child').each( function() {
        var name = 'attribute_pa_';
        var value = '';
        
        var k = 0;
        $(this).find('.nasa-attr-ux-item').each(function() {
            if(k === 0) {
                name += $(this).attr('data-pa');
            }
            
            if($(this).hasClass('nasa-active')) {
                value = $(this).attr('data-value');
            }
            
            k++;
        });

        if (value.length > 0) {
            chosen ++;
        }

        count ++;
        data[name] = value;
    });

    return {
        'count': count,
        'chosenCount': chosen,
        'data': data
    };
}

/**
 * Is match variation
 * 
 * @param {type} variation_attributes
 * @param {type} attributes
 * @returns {Boolean}
 */
function nasa_isMatch_variation(variation_attributes, attributes) {
    var match = true;
    for (var attr_name in variation_attributes) {
        if (typeof variation_attributes[attr_name] !== 'undefined') {
            var val1 = variation_attributes[attr_name];
            var val2 = attributes[attr_name];
            if (
                val1 !== undefined &&
                val2 !== undefined &&
                val1.length !== 0 &&
                val2.length !== 0 &&
                val1 !== val2
            ) {
                match = false;
            }
        }
    }
    
    return match;
}

/**
 * Matching variation
 * 
 * @param {type} variations
 * @param {type} attributes
 * @returns {Array|nasa_matching_variations.matching}
 */
function nasa_matching_variations(variations, attributes) {
    var matching = [];
    for (var i = 0; i < variations.length; i++) {
        var variation = variations[i];

        if (nasa_isMatch_variation(variation.attributes, attributes)) {
            matching.push(variation);
        }
    }
    
    return matching;
}

/**
 * Init show pin
 */
function loadPinProductsBanner($) {
    if($('.nasa-pin-banner-wrap').length > 0) {
        $('.nasa-pin-banner-wrap').each(function() {
            var _this = $(this);
            if(!$(_this).hasClass('nasa-inited')) {
                $(_this).addClass('nasa-inited');
                var _init = $(_this).attr('data-pin');
                var _img = $(_this).find('img.nasa_pin_pb_image');
                var _reponsive = $(_img).parents('columns').length === 1 ? true : false;
                
                if(_init && $(_img).length >0) {
                    $(_img).easypinShow({
                        data: _init,
                        responsive: _reponsive,
                        popover: {
                            show: false,
                            animate: false
                        },
                        each: function(index, data) {
                            return data;
                        },
                        error: function() {
                            
                        },
                        success: function() {
                            if($(_this).find('.nasa-product-pin .price.nasa-price-pin').length > 0){
                                $(_this).find('.nasa-product-pin .price.nasa-price-pin').each(function() {
                                    var _pid = $(this).attr('data-product_id');
                                    if($(_this).find('.nasa-price-pin-' + _pid).length > 0) {
                                        $(this).html($(_this).find('.nasa-price-pin-' + _pid).html());
                                    }
                                });
                            }
                            
                            if($(_this).hasClass('nasa-has-effect')) {
                                setInterval(function() {
                                    $(_this).find('.nasa-marker-icon-wrap').toggleClass('nasa-effect');
                                }, 2400);
                            }
                        }
                    });
                }
                
                $(_img).click(function() {
                    $(_this).find('.easypin-popover').hide();
                });
                
                $(document).on('keyup', function(e){
                    if (e.keyCode === 27){
                        $(_img).click();
                    }
                });
            }
        });
    }
}

/**
 * Init variable ux for product variable
 */
function initVariablesProducts($) {
    if($('.nasa-product-variable-call-ajax').length > 0) {
        
        var _pids = [];
        $('.nasa-product-variable-call-ajax').each(function() {
            if(!$(this).hasClass('nasa-process')) {
                $(this).addClass('nasa-process');
                if(_pids.indexOf($(this).attr('data-product_id')) === -1) {
                    _pids.push($(this).attr('data-product_id'));
                }
            }
        });
        
        if(_pids.length > 0) {
            $.ajax({
                url : ajaxurl_core,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'nasa_render_variables',
                    'pids': _pids
                },
                beforeSend: function(){
                    
                },
                success: function(res){
                    if(typeof res.empty !== 'undefined' && res.empty === '0') {
                        for (var pid in res.products) {
                            $('.nasa-product-variable-call-ajax.nasa-product-variable-' + pid).replaceWith(res.products[pid]);
                        }
                    }
                    
                    if($('.nasa-product-content-variable-warp').length) {
                        $('.nasa-product-content-variable-warp').each(function() {
                            var _this = $(this);
                            change_image_content_product_variable($, _this, false);
                        });
                    }
                },
                error: function() {
                    $('.nasa-product-variable-call-ajax').remove();
                }
            });
        }
    }
}

function nasa_refresh_attrs($, $form) {
    $form.find('.nasa-attr-ux_wrap').each(function() {
        var _this = $(this);
        var _attr_name = $(_this).attr('data-attribute_name');
        if($('select[name="' + _attr_name + '"]').length) {
            $(_this).find('.nasa-attr-ux').each(function() {
                var _item = $(this);
                var _value = $(_item).attr('data-value');
                if($('select[name="' + _attr_name + '"]').find('option[value="' + _value + '"]').length <= 0) {
                    if(!$(_item).hasClass('nasa-disable')) {
                        $(_item).addClass('nasa-disable');
                    }
                } else {
                    $(_item).removeClass('nasa-disable');
                }
            });
        }
    });
}
