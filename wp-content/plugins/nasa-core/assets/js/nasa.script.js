var product_load_flag = false;
var portfolio_load_flag = false;
var portfolio_page = 1;
var nasa_ajax_setup = true;
var nasa_iOS = nasa_check_iOS(),
    _event = (nasa_iOS) ? 'click, mousemove' : 'click';
var nasa_next_prev = true;
var nasa_countdown_init = '0';

/* =========== Document nasa-core ready ==================== */
jQuery(document).ready(function ($) {
    "use strict";
    
    if (nasa_ajax_setup) {
        $.ajaxSetup({
            data: {
                context: 'frontend'
            }
        });
    }

    loadCorouselHasThumbs($);
    loadingSlickHasExtraVerticalNasaCore($);
    loadingSlickVerticalCategories($);
    initVariablesProducts($);
    
    $('body').ajaxComplete(function(){
        setTimeout(function () {
            initVariablesProducts($);
        }, 100);
    });

    /* AJAX PRODUCT */
    $('body').on('click', '.load-more-btn', function () {
        if (product_load_flag) {
            return;
        } else {
            product_load_flag = true;
            var _this = $(this),
                _infinite_id = $(_this).attr('data-infinite'),
                _type = $('.shortcode_' + _infinite_id).attr('data-product-type'),
                _page = parseInt($('.shortcode_' + _infinite_id).attr('data-next-page')),
                _cat = parseInt($('.shortcode_' + _infinite_id).attr('data-cat')),
                _post_per_page = parseInt($('.shortcode_' + _infinite_id).attr('data-post-per-page')),
                _post_per_row = parseInt($('.shortcode_' + _infinite_id).attr('data-post-per-row')),
                _is_deals = $('.shortcode_' + _infinite_id).attr('data-is-deals'),
                _max_pages = parseInt($('.shortcode_' + _infinite_id).attr('data-max-pages'));
            _cat = !_cat ? null : _cat;
            $.ajax({
                url: ajaxurl_core,
                type: 'post',
                data: {
                    action: 'nasa_more_product',
                    page: _page,
                    type: _type,
                    cat: _cat,
                    post_per_page: _post_per_page,
                    columns_number: _post_per_row,
                    is_deals: _is_deals,
                    nasa_load_ajax: '1'
                },
                beforeSend: function () {
                    $(_this).before('<div class="nasa-loader" id="nasa-loader-product-infinite" style="top: 0;"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
                    // $(_this).css('opacity', '0');
                },
                success: function (res) {
                    $(_this).css('opacity', '1');
                    $('.shortcode_' + _infinite_id).append(res).fadeIn(1000);
                    $('.shortcode_' + _infinite_id).attr('data-next-page', _page + 1);
                    $('#nasa-loader-product-infinite').remove();
                    if (_page == _max_pages) {
                        $(_this).addClass('end-product');
                        $(_this).html('<span class="nasa-end-content">' + $(_this).attr('data-nodata') + '</span>').removeClass('load-more-btn');
                    }
                    /* ===========================
                    // loadingCarouselNasaCore($);
                    $('.tip, .tip-bottom').tipr();
                    $('.products-infinite .tip-top').tipr({mode: "top"});
                    =========================== */
                    setTimeout(function(){nasa_load_ajax_funcs($);}, 1000);
                    product_load_flag = false;
                }
            });
            
            return false;
        }
    });

    // **********************************************************************// 
    // ! Portfolio
    // **********************************************************************//
    if ($('.portfolio-list').length > 0 && $('input[name="nasa-enable-portfolio"]').length === 1 && $('input[name="nasa-enable-portfolio"]').val() === '1') {
        var _columns = $('.portfolio-list').attr('data-columns');
        var portfolioGrid = $('.portfolio-list');

        $(portfolioGrid).isotope({
            itemSelector: '.portfolio-item',
            layoutMode: 'masonry',
            filter: '*'
        });

        $(portfolioGrid).parent().find('.portfolio-tabs li a').on('click', function () {
            var selector = $(this).attr('data-filter');
            $(portfolioGrid).parent().find('.portfolio-tabs li').removeClass('active');
            if (!$(this).parents('li').hasClass('active')) {
                $(this).parents('li').addClass('active');
            }
            $(portfolioGrid).isotope({filter: selector});
            return false;
        });

        var _cat_id = $('.loadmore-portfolio').attr('data-category');
        portfolio_load_flag = true;
        loadMorePortfolio($, _cat_id, _columns, portfolio_page, ajaxurl);

        // loadMore Portfolio
        $('body').on('click', '.loadmore-portfolio', function () {
            var button = $(this);
            if (portfolio_load_flag) {
                return;
            } else {
                portfolio_load_flag = true;
                var _cat_id = $(button).attr('data-category');
                portfolio_page++;
                loadMorePortfolio($, _cat_id, _columns, portfolio_page);
                return false;
            }
        });
    }

    $('body').on('click', '.portfolio-image-view', function (e) {
        var _src = $(this).attr('data-src');
        $.magnificPopup.open({
            closeOnContentClick: true,
            items: {
                src: '<div class="portfolio-lightbox"><img src="' + _src + '" /></div>',
                type: 'inline'
            }
        });
        $('.please-wait, .color-overlay').remove();
        e.preventDefault();
    });

    var type_optimazed = $('input[name="nasa-optimized-type"]').length === 1 ? $('input[name="nasa-optimized-type"]').val() : 'sync';
    type_optimazed = type_optimazed !== 'sync' ? 'async' : type_optimazed;
    if (type_optimazed === 'sync') {
        nasa_load_all_shortcodes($);
    } else {
        if ($('.nasa_load_ajax').length > 0) {
            $('.nasa_load_ajax').each(function () {
                var _this = $(this),
                    _parent = $(_this).parent();

                if (!$(_parent).hasClass('nasa-panel') || $(_parent).hasClass('active')) {
                    // Call ajax do_shortcode;
                    nasa_load_shortcodes($, _this);
                }
            });
        }
    }

    // Tabable
    $('body').on(_event, '.nasa-tabs-content ul.nasa-tabs li a', function () {
        var _this = $(this);
        var currentTab = $(_this).attr('data-id');
        var nasa_load_ajax = $(currentTab).find('.nasa_load_ajax');
        if ($(nasa_load_ajax).length > 0) {
            nasa_load_shortcodes($, nasa_load_ajax);
        }
    });

    reponsiveBanners($);
    $(window).resize(function () {
        reponsiveBanners($);
    });
    
    // Next | Prev slider
    if(nasa_next_prev) {
        
        /**
         * Carousel
         */
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
        
        /**
         * Slick
         */
        $('body').on('click', '.nasa-nav-icon-slick', function(){
            var _this = $(this);
            var _wrap = $(_this).parents('.nasa-nav-slick-wrap');
            var _do = $(_this).attr('data-do');
            var _id = $(_wrap).attr('data-id');
            if ($(_id).length === 1) {
                switch (_do) {
                    case 'next':
                        $(_id).find('.slick-arrow.slick-next').click();
                        break;
                    case 'prev':
                        $(_id).find('.slick-arrow.slick-prev').click();
                        break;
                    default: break;
                }
            }
        });
    }
    
    $('body').on('click', '.nasa-slider-deal-vertical-extra-switcher .item-slick', function() {
        var _wrap = $(this).parents('.nasa-slider-deal-vertical-extra-switcher');
        var _speed = parseInt($(_wrap).attr('data-speed'));
        _speed = !_speed ? 600 : _speed;
        $(_wrap).append('<div class="nasa-slick-fog"></div>');
        
        setTimeout(function(){
            $(_wrap).find('.nasa-slick-fog').remove();
        }, _speed);
    });
    
    /*
     * nasa-gift-featured-event
     */
    initNasaGiftFeatured($);

    /* =========== End Document nasa-core ready ==================== */
    
    /**
     * Countdown
     */
    if(typeof nasa_countdown_l10n !== 'undefined' && (typeof nasa_countdown_init === 'undefined' || nasa_countdown_init === '0')) {
        nasa_countdown_init = '1';
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
        loadCountDownNasaCore($);
    }
    
    /**
     * Scroll window
     */
    $(window).scroll(function(){
        nasaLoadHeightMainProducts($);
    });
    
    $('body').on('hover', '.product-item', function() {
        $(this).toggleClass("nasa-hoving");
    });
    
    /**
     * Color | Label variations products
     */
    $.fn.nasa_attr_ux_variation_form = function() {
        return this.each(function() {
            var $form = $(this),
                clicked = null,
                selected = [];

            $form.addClass('nasa-attr-ux-form')
            .on('click', '.nasa-attr-ux', function(e) {
                e.preventDefault();
                var $el = $( this ),
                    $select = $el.closest('.value').find('select'),
                    attribute_name = $select.data('attribute_name') || $select.attr('name'),
                    value = $el.data('value');
                    
                if($el.hasClass('nasa-disable')) {
                    return false;
                }
                
                else {
                    $select.trigger('focusin');

                    // Check if this combination is available
                    if (! $select.find('option[value="' + value + '"]').length) {
                        $el.siblings('.nasa-attr-ux').removeClass('selected');
                        $select.val('').change();
                        $form.trigger('nasa-attr-ux_no_matching_variations', [$el]);
                        return;
                    }

                    clicked = attribute_name;

                    if (selected.indexOf(attribute_name) === -1 ) {
                        selected.push(attribute_name);
                    }

                    if ($el.hasClass('selected')) {
                        $select.val('');
                        $el.removeClass('selected');

                        delete selected[selected.indexOf(attribute_name)];
                    } else {
                        $el.addClass('selected').siblings('.selected').removeClass('selected');
                        $select.val(value);
                    }

                    $select.change();
                    nasa_refresh_attrs($, $form);
                }
            })
            .on('click', '.reset_variations', function() {
                $(this).closest('.variations_form').find('.nasa-attr-ux.selected').removeClass('selected');
                selected = [];
                nasa_refresh_attrs($, $form);
            })
            .on('nasa-attr-ux_no_matching_variations', function() {
                var text_nomatch = wc_add_to_cart_variation_params !== 'undefined' ? wc_add_to_cart_variation_params.i18n_no_matching_variations_text : $('input[name="nasa_no_matching_variations"]').val();
                window.alert(text_nomatch);
                nasa_refresh_attrs($, $form);
            });
            
            setTimeout(function() {
                nasa_refresh_attrs($, $form);
            }, 500);
        });
    };

    $(function () {
        $('.nasa-product-details-page .variations_form').nasa_attr_ux_variation_form();
    });
    
    if($('.nasa-product-content-variable-warp').length) {
        $('.nasa-product-content-variable-warp').each(function() {
            var _this = $(this);
            change_image_content_product_variable($, _this, false);
        });
    }
    
    $('body').on('click', '.nasa-attr-ux-item', function() {
        var _this = $(this),
            _wrap = $(_this).parents('.nasa-product-content-child'),
            _act = $(_this).attr('data-act');
        
        if(!$(_this).hasClass('nasa-disable')) {
            $(_wrap).find('.nasa-attr-ux-item').removeClass('nasa-active').attr('data-act', '0');
            if(_act === '0') {
                $(_this).addClass('nasa-active').attr('data-act', '1');
            }

            var _variations_warp = $(_this).parents('.nasa-product-content-variable-warp');
            change_image_content_product_variable($, _variations_warp, true);
        }
    });
    
    $('body').on('click', '.nasa-toggle-attr-select', function() {
        var _this = $(this);
        
        if($(_this).hasClass('nasa-show')) {
            $(_this).removeClass('nasa-show');
            $(_this).parents('.nasa-product-content-child').find('.nasa-toggle-content-attr-select').slideUp(200);
        } else {
            $(_this).addClass('nasa-show');
            $(_this).parents('.nasa-product-content-child').find('.nasa-toggle-content-attr-select').slideDown(200);
        }
    });
    
    /**
     * Pin init
     */
    loadPinProductsBanner($);
    $('body').on('click', '.easypin-marker .nasa-marker-icon-wrap', function() {
        var _this = $(this);
        var _act = $(_this).parents('.easypin-marker').hasClass('nasa-active');
        var _wrap = $(_this).parents('.nasa-pin-banner-wrap');
        $(_wrap).find('.easypin-marker').removeClass('nasa-active');
        
        if(!_act) {
            $(_this).parents('.easypin-marker').addClass('nasa-active');
        }
    });
    
    /**
     * Check accessories product
     */
    $('body').on('change', '.nasa-check-accessories-product', function () {
        var _this = $(this);

        var _wrap = $(_this).parents('.nasa-accessories-check');

        var _id = $(_this).val();
        var _isChecked = $(_this).is(':checked');

        var _price = $(_wrap).find('.nasa-check-main-product').length ? parseInt($(_wrap).find('.nasa-check-main-product').attr('data-price')) : 0;
        if ($(_wrap).find('.nasa-check-accessories-product').length) {
            $(_wrap).find('.nasa-check-accessories-product').each(function() {
                if ($(this).is(':checked')) {
                    _price += parseInt($(this).attr('data-price'));
                }
            });
        }

        $.ajax({
            url: ajaxurl_core,
            type: 'post',
            dataType: 'json',
            cache: false,
            data: {
                total_price: _price,
                action: 'nasa_refresh_accessories_price',
                nasa_load_ajax: '1'
            },
            beforeSend: function () {
                $(_wrap).append('<div class="nasa-disable-wrap"></div><div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
            },
            success: function (res) {
                if (typeof res.total_price !== 'undefined') {
                    $('.nasa-accessories-total-price .price').html(res.total_price);

                    if (!_isChecked) {
                        $('.nasa-accessories-' + _id).fadeOut(200);
                    } else {
                        $('.nasa-accessories-' + _id).fadeIn(200);
                    }
                }

                $(_wrap).find('.nasa-loader, .nasa-disable-wrap').remove();
            },
            error: function () {

            }
        });
    });
    
    /**
     * Add To cart accessories
     */
    $('body').on('click', '.add_to_cart_accessories', function() {
        var _this = $(this);

        var _wrap = $(_this).parents('#nasa-tab-accessories_content');
        if ($(_wrap).length <= 0) {
            _wrap = $(_this).parents('#nasa-secion-accordion-accessories_content');
        }
        if ($(_wrap).length) {
            var _wrapCheck = $(_wrap).find('.nasa-accessories-check');

            if ($(_wrapCheck).length) {
                var _pid = [];

                // nasa-check-main-product
                if ($(_wrapCheck).find('.nasa-check-main-product').length) {
                    _pid.push($(_wrapCheck).find('.nasa-check-main-product').val());
                }

                // nasa-check-accessories-product
                if ($(_wrapCheck).find('.nasa-check-accessories-product').length) {
                    $(_wrapCheck).find('.nasa-check-accessories-product').each(function() {
                        if ($(this).is(':checked')) {
                            _pid.push($(this).val());
                        }
                    });
                }

                if (_pid.length) {
                    $.ajax({
                        url: ajaxurl_core,
                        type: 'post',
                        dataType: 'json',
                        cache: false,
                        data: {
                            product_ids: _pid,
                            action: 'nasa_add_to_cart_accessories',
                            nasa_load_ajax: '1'
                        },
                        beforeSend: function () {
                            $('.nasa-message-error').hide();
                            $(_wrap).append('<div class="nasa-disable-wrap"></div><div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
                        },
                        success: function (data) {
                            if (data && data.fragments) {
                                $.each(data.fragments, function(key, value) {
                                    $(key).replaceWith(value);
                                });

                                if ($('.cart-link').length) {
                                    $('.cart-link').trigger('click');
                                }
                            } else {
                                if (data && data.error && $('.nasa-message-error').length) {
                                    $('.nasa-message-error').html(data.message);
                                    $('.nasa-message-error').show();
                                }
                            }

                            $(_wrap).find('.nasa-loader, .nasa-disable-wrap').remove();
                        },
                        error: function () {

                        }
                    });
                }
            }
        }

        return false;
    });
});