"use strict";
/* =========== Functions base ==================== */
function afterLoadAjaxList($) {
    loadingCarousel($);
    loadingSCCarosel($);
    loadTipTop($);
    initThemeNasaGiftFeatured($);
    
    /*
     * Parallax Breadcrumb
     */
    $(window).stellar('refresh');
    /**
     * Load countdonw
     */
    loadCountDown($);
    
    $('body').trigger('nasa_after_load_ajax');
}

function check_iOS() {
    var iDevices = [
        'iPad Simulator',
        'iPhone Simulator',
        'iPod Simulator',
        'iPad',
        'iPhone',
        'iPod'
    ];
    while (iDevices.length > 0) {
        if (navigator.platform === iDevices.pop()) {
            return true;
        }
    }
    return false;
}

function nasa_Ajax_filter($, _url, _page, _catid, _order, _variations, _hasPrice, _min, _max, _hasSearch, _s, _this, _taxonomy, _toTop) {
    /**
     * Built URL
     */
    $('#nasa-hidden-current-cat').attr({
        'href': _url,
        'data-id': _catid,
        'data-taxonomy': _taxonomy
    });

    if (_url === '') {
        if (_hasSearch === 0) {
            _url = $('.static-position input[name="nasa-shop-page-url"]').val();
        } else if (_hasSearch === 1) {
            _url = $('.static-position input[name="nasa-base-url"]').val();
        }
    }

    var _h = false;
    if (_hasSearch != 1) {
        var patt = /\?/g;
        _h = patt.test(_url);
    }
    var pagestring = '';
    var _friendly = $('.static-position input[name="nasa-friendly-url"]').length === 1 && $('.static-position input[name="nasa-friendly-url"]').val() === '1' ? true : false;
    if (_page) {
        if (_h || _hasSearch == 1 || !_friendly) {
            pagestring = '&paged=' + _page;
        } else {
            // Paging change (friendly Url)
            var lenUrl = _url.length;
            _url += (_url.length > 0 && _url.substring(lenUrl - 1, lenUrl) !== '/') ? '/' : '';
            _url += 'page/' + _page + '/';
        }
    }

    // Search change
    if (_hasSearch == 1) {
        _url += _h ? '&' : '?';
        _url += 's=' + encodeURI(_s) + '&page=search&post_type=product';
        _h = true;
    } else {
        if ($('.nasa-results-blog-search').length > 0) {
            $('.nasa-results-blog-search').remove();
        }
        if ($('input[name="hasSearch"]').length > 0) {
            $('input[name="hasSearch"]').remove();
        }
    }
    _url += pagestring;

    // Variations change
    if (_variations.length > 0) {
        var l = _variations.length;
        for (var i = 0; i < l; i++) {
            var _qtype = (_variations[i].type === 'or') ? '&query_type_' + _variations[i].taxonomy + '=' + _variations[i].type : '';
            _url += _h ? '&' : '?';
            _url += 'filter_' + _variations[i].taxonomy + '=' + (_variations[i].slug).toString() + _qtype;
            _h = true;
        }
    }

    // Price change
    if (_hasPrice == 1 && _min && _max) {
        _url += _h ? '&' : '?';
        _url += 'min_price=' + _min + '&max_price=' + _max;
        _h = true;
    }

    // Order change
    if (_order && _order !== 'menu_order') {
        _url += _h ? '&' : '?';
        _url += 'orderby=' + _order;
        _h = true;
    }

    // Get Sidebar
    if($('input[name="nasa_getSidebar"]').length === 1) {
        var _sidebar = $('input[name="nasa_getSidebar"]').val();
        _url += _h ? '&' : '?';
        _url += 'sidebar=' + _sidebar;
        _h = true;
    }
    
    $.ajax({
        url: _url,
        type: 'post',
        dataType: 'html',
        data: {},
        beforeSend: function () {
            $('.nasa-content-page-products').append('<div class="opacity-3" />');

            if($('.nasa-progress-bar-load-shop').length === 1) {
                $('.nasa-progress-bar-load-shop .nasa-progress-per').removeClass('nasa-loaded');
                $('.nasa-progress-bar-load-shop').addClass('nasa-loading');
            }

            $('.col-sidebar').append('<div class="opacity-2"></div>');

            $('.nasa-filter-by-cat').addClass('nasa-disable').removeClass('nasa-active');

            if ($(_this).parents('ul.children').length > 0) {
                $(_this).parents('ul.children').show();
            }
        },
        success: function (res) {
            var _act_widget = $('a.nasa-togle-topbar');
            var $html = $.parseHTML(res);
            var $mainContent = $('#main-content', $html);
            
            $('#main-content').replaceWith($mainContent);
            
            $('.black-window, .white-window, .transparent-window').hide();

            $('.nasa-filter-by-cat').removeClass('nasa-disable');

            if (_toTop && $('.category-page').length) {
                var _pos_top = $('.category-page').offset().top;
                $('html, body').animate({scrollTop: (_pos_top - 125)}, 700);
            }

            if($('.nasa-top-sidebar').length > 0) {
                initNasaTopSidebar($);
                if($(_act_widget).length === 1) {
                    var _filter = $(_act_widget).attr('data-filter');
                    if($('a.nasa-togle-topbar').length === 1 && $('a.nasa-togle-topbar').attr('data-filter') != _filter) {
                        $('a.nasa-togle-topbar').click();
                    }
                }
            }

            if($('.price_slider').length === 1) {
                var min_price = $('.price_slider_amount #min_price').data('min'),
                    max_price = $('.price_slider_amount #max_price').data('max'),
                    current_min_price = parseInt(min_price, 10),
                    current_max_price = parseInt(max_price, 10);
                    if (_hasPrice == 1 && _min && _max) {
                        current_min_price = _min;
                        current_max_price = _max;
                    }
                $('.price_slider').slider({
                    range: true,
                    animate: true,
                    min: min_price,
                    max: max_price,
                    values: [current_min_price, current_max_price],
                    create: function() {
                        $('.price_slider_amount #min_price').val(current_min_price);
                        $('.price_slider_amount #max_price').val(current_max_price);
                        $(document.body).trigger('price_slider_create', [current_min_price, current_max_price]);
                    },
                    slide: function(event, ui) {
                        $('input#min_price').val(ui.values[0]);
                        $('input#max_price').val(ui.values[1]);

                        $(document.body).trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
                    },
                    change: function(event, ui) {
                        $(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
                    }
                });

                if (_hasPrice == 1 && _min && _max) {
                    $('.reset_price').attr('data-has_price', "1").show();
                }
                $('.price_slider_amount input, .price_slider_amount button').hide();
            }

            afterLoadAjaxList($);
        },
        error: function () {
            $('.opacity-2').remove();
            $('.opacity-3').remove();
            $('.nasa-filter-by-cat').removeClass('nasa-disable');
        }
    });
    
    window.history.pushState(null, '', _url);
}

function nasa_setVariations($, variations, keys) {
    $('.nasa-filter-var-chosen').each(function () {
        var _attr = $(this).attr('data-attr'),
                _attrVal = $(this).attr('data-term_id'),
                _attrSlug = $(this).attr('data-term_slug'),
                _attrType = $(this).attr('data-type');
        var l = variations.length;
        if (keys.indexOf(_attr) === -1) {
            variations.push({
                taxonomy: _attr,
                values: [_attrVal],
                slug: [_attrSlug],
                type: _attrType
            });
            keys.push(_attr);
        } else {
            for (var i = 0; i < l; i++) {
                if (variations[i].taxonomy.length > 0 && variations[i].taxonomy === _attr) {
                    variations[i].values.push(_attrVal);
                    variations[i].slug.push(_attrSlug);
                    break;
                }
            }
        }
    });

    return variations;
}

function loadingCarousel($, heightAuto, minHeight) {
    heightAuto = heightAuto === undefined ? false : heightAuto;
    minHeight = minHeight === undefined ? true : minHeight;
    $('.nasa-slider').each(function () {
        var _this = $(this);
        if (!$(_this).hasClass('owl-loaded')) {
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

            if (!ap_speed) {
                ap_speed = 600;
            }

            if (!ap_delay) {
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
                navText: ["", ""],
                navSpeed: 600,
                lazyLoad: true,
                touchDrag: disable_drag,
                mouseDrag: disable_drag,
                responsive: {
                    0: {
                        items: cols_small,
                        margin: margin_small,
                        nav: false
                    },
                    600: {
                        items: cols_tablet
                    },
                    1000: {
                        items: cols
                    }
                }
            };

            if (margin_px) {
                nasa_slider_params.margin = margin_px;
            }

            if (height_auto) {
                nasa_slider_params.autoHeight = true;
            }

            $(_this).owlCarousel(nasa_slider_params);

            if (padding) {
                $(_this).find('> .owl-stage-outer').css({'padding-bottom': padding, 'margin-bottom': '-' + padding});
            }

            if (heightAuto === true) {
                $(_this).find('> .owl-stage-outer').css({'height': 'auto'});
            }

            // Fix height tabable content slide
            if (minHeight === true) {
                var _height = $(_this).height();
                if (_height > 0 && $(_this).parents('.nasa-panels').length > 0) {
                    $(_this).parents('.nasa-panels').css({'min-height': _height});
                    setTimeout(function() {
                        $(_this).parents('.nasa-panels').css({'min-height': 'auto'});
                    }, 500);
                }
            }
        }
    });
}

function loadingSCCarosel($) {
    if ($('.nasa-sc-carousel').length > 0) {
        $('.nasa-sc-carousel').each(function () {
            var _sc = $(this);
            if (!$(_sc).hasClass('owl-loaded')) {
                var _key = $(_sc).attr('data-contruct');
                var owl = $('#item-slider-' + _key);
                var height = ($(owl).find('.banner').length > 0) ? $(owl).find('.banner').height() : 0;
                if (height) {
                    var loading = '<div class="nasa-carousel-loadding" style="height: ' + height + 'px"><div class="please-wait type2"></div></div>';
                    $(owl).parent().append(loading);
                }

                var _nav = ($(_sc).attr('data-nav') === 'true') ? true : false,
                    _dots = ($(_sc).attr('data-dots') === 'true') ? true : false,
                    _autoplay = ($(_sc).attr('data-autoplay') === 'false') ? false : true,
                    _speed = parseInt($(_sc).attr('data-speed')),
                    _itemSmall = parseInt($(_sc).attr('data-itemSmall')),
                    _itemTablet = parseInt($(_sc).attr('data-itemTablet')),
                    _items = parseInt($(_sc).attr('data-items')),
                    _speed = _speed ? _speed : 3000;
                _itemSmall = _itemSmall ? _itemSmall : 1;
                _itemTablet = _itemTablet ? _itemTablet : 1;
                _items = _items ? _items : 1;
                owl.owlCarousel({
                    loop: true,
                    nav: _nav,
                    dots: _dots,
                    autoplay: _autoplay,
                    autoplaySpeed: _speed, // Speed when auto play
                    autoplayTimeout: 5000, //Delay for next slide
                    autoplayHoverPause: true,
                    navText: ["", ""],
                    navSpeed: _speed, //Speed when click to navigation arrow
                    dotsSpeed: _speed,
                    responsiveClass: true,
                    callbacks: true,
                    responsive: {
                        0: {
                            items: _itemSmall,
                            nav: false
                        },
                        600: {
                            items: _itemTablet,
                            nav: false
                        },
                        1000: {
                            items: _items
                        }
                    }
                });

                owl.find('.owl-item').each(function () {
                    var _this = $(this);
                    if ($(_this).find('.banner .banner-inner').length) {
                        var _banner = $(_this).find('.banner .banner-inner');
                        $(_banner).removeAttr('class').removeAttr('style').addClass('banner-inner');
                        if ($(_this).hasClass('active')) {
                            var animation = $(_banner).attr('data-animation');
                            setTimeout(function () {
                                $(_banner).show();
                                $(_banner).addClass('animated').addClass(animation).css({'visibility': 'visible', 'animation-duration': '1s', 'animation-delay': '0ms', 'animation-name': animation});
                            }, 200);
                        }
                    }
                });

                owl.on('translated.owl.carousel', function (items) {
                    var warp = items.target;
                    if ($(warp).find('.owl-item').length) {
                        $(warp).find('.owl-item').each(function () {
                            var _this = $(this);
                            if ($(_this).find('.banner .banner-inner').length) {
                                var _banner = $(_this).find('.banner .banner-inner');
                                var animation = $(_banner).attr('data-animation');
                                $(_banner).removeClass('animated').removeClass(animation).removeAttr('style');
                                if ($(_this).hasClass('active')) {
                                    setTimeout(function () {
                                        $(_banner).show();
                                        $(_banner).addClass('animated').addClass(animation).css({'visibility': 'visible', 'animation-duration': '1s', 'animation-delay': '0ms', 'animation-name': animation});
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

// Tabs slide
function nasa_tab_slide_style($, _this, exttime) {
    exttime = !exttime ? 500 : exttime;
    var _tab = $(_this).find('.nasa-slide-tab');
    var _act = $(_this).find('.nasa-tab.active');
    if ($(_this).find('.nasa-tab-icon').length > 0) {
        $(_this).find('.nasa-tab > a').css({'padding': '15px 30px'});
    }
    
    var _width_border = parseInt($(_this).find('.nasa-tabs').css("border-top-width"));
    _width_border = !_width_border ? 0 : _width_border;
    
    var _pos = $(_act).position();
    $(_tab).show().animate({'height': $(_act).height() + (2*_width_border), 'width': $(_act).width() + (2*_width_border), 'top': _pos.top - _width_border, 'left': _pos.left - _width_border}, exttime);
}

function loadCountDown($) {
    var countDownEnable = ($('input[name="nasa-count-down-enable"]').length === 1 && $('input[name="nasa-count-down-enable"]').val() === '1') ? true : false;
    if (countDownEnable && $('.countdown').length > 0) {
        $('.countdown').each(function () {
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

/* Nasa compare =========================== */
function add_compare_product(_id, $) {
    var _compare_table = $('.nasa-wrap-table-compare').length > 0 ? true : false;
    
    $.ajax({
        url: ajaxurl,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'nasa_add_compare_product',
            pid: _id,
            compare_table: _compare_table
        },
        beforeSend: function () {
            showCompare($);
            // $('.nasa-compare-list-bottom').append('<div class="please-wait type2" style="top:40%"></div>');
            $('.nasa-compare-list-bottom').append('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
        },
        success: function (res) {
            if (res.result_compare === 'success') {
                if (res.mini_compare !== 'no-change' && $('.nasa-compare-list').length > 0) {
                    $('.nasa-compare-list').replaceWith(res.mini_compare);
                    if ($('.compare-number .nasa-sl').length > 0) {
                        $('.compare-number .nasa-sl').html(res.count_compare);
                        if (res.count_compare === 0) {
                            if (!$('.compare-number').hasClass('nasa-product-empty')) {
                                $('.compare-number').addClass('nasa-product-empty');
                            }
                        } else {
                            if ($('.compare-number').hasClass('nasa-product-empty')) {
                                $('.compare-number').removeClass('nasa-product-empty');
                            }
                        }
                    }

                    $('.nasa-compare-success').html(res.mess_compare);
                    $('.nasa-compare-success').fadeIn(200);
                    
                    if(_compare_table) {
                        $('.nasa-wrap-table-compare').replaceWith(res.result_table);
                    }
                } else {
                    $('.nasa-compare-exists').html(res.mess_compare);
                    $('.nasa-compare-exists').fadeIn(200);
                }
                
                if(!$('.nasa-compare[data-prod="' + _id + '"]').hasClass('added')) {
                    $('.nasa-compare[data-prod="' + _id + '"]').addClass('added');
                }

                setTimeout(function () {
                    $('.nasa-compare-success').fadeOut(200);
                    $('.nasa-compare-exists').fadeOut(200);
                }, 2000);
            }

            $('.nasa-compare-list-bottom').find('.nasa-loader, .please-wait').remove();
        },
        error: function () {

        }
    });
}

function remove_compare_product(_id, $) {
    var _compare_table = $('.nasa-wrap-table-compare').length > 0 ? true : false;
    
    $.ajax({
        url: ajaxurl,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'nasa_remove_compare_product',
            pid: _id,
            compare_table: _compare_table
        },
        beforeSend: function () {
            // $('.nasa-compare-list-bottom').append('<div class="please-wait type2" style="top:40%"></div>');
            $('.nasa-compare-list-bottom').append('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
            if($('table.nasa-table-compare tr.remove-item td.nasa-compare-view-product_' + _id).length >0) {
                // $('table.nasa-table-compare').css('opacity', '0.3').prepend('<div class="please-wait type2" style="top: 40%"></div>');
                $('table.nasa-table-compare').css('opacity', '0.3').prepend('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
            }
        },
        success: function (res) {
            if (res.result_compare === 'success') {
                if (res.mini_compare !== 'no-change' && $('.nasa-compare-list').length > 0) {
                    $('.nasa-compare-list').replaceWith(res.mini_compare);
                    $('.nasa-compare[data-prod="' + _id + '"]').removeClass('added');
                    if ($('.compare-number .nasa-sl').length > 0) {
                        $('.compare-number .nasa-sl').html(res.count_compare);
                        if (res.count_compare === 0) {
                            if (!$('.compare-number').hasClass('nasa-product-empty')) {
                                $('.compare-number').addClass('nasa-product-empty');
                            }
                        } else {
                            if ($('.compare-number').hasClass('nasa-product-empty')) {
                                $('.compare-number').removeClass('nasa-product-empty');
                            }
                        }
                    }

                    $('.nasa-compare-success').html(res.mess_compare);
                    $('.nasa-compare-success').fadeIn(200);
                    
                    if(_compare_table) {
                        $('.nasa-wrap-table-compare').replaceWith(res.result_table);
                    }
                } else {
                    $('.nasa-compare-exists').html(res.mess_compare);
                    $('.nasa-compare-exists').fadeIn(200);
                }

                setTimeout(function () {
                    $('.nasa-compare-success').fadeOut(200);
                    $('.nasa-compare-exists').fadeOut(200);
                    if (res.count_compare === 0) {
                        $('.nasa-close-mini-compare').click();
                    }
                }, 2000);
            }

            $('table.nasa-table-compare').find('.nasa-loader, .please-wait').remove();
            $('.nasa-compare-list-bottom').find('.nasa-loader, .please-wait').remove();
        },
        error: function () {

        }
    });
}

function removeAll_compare_product($) {
    var _compare_table = $('.nasa-wrap-table-compare').length > 0 ? true : false;
    $.ajax({
        url: ajaxurl,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'nasa_removeAll_compare_product',
            compare_table: _compare_table
        },
        beforeSend: function () {
            $('.nasa-compare-list-bottom').append('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
        },
        success: function (res) {
            if (res.result_compare === 'success') {
                if (res.mini_compare !== 'no-change' && $('.nasa-compare-list').length > 0) {
                    $('.nasa-compare-list').replaceWith(res.mini_compare);

                    if ($('.compare-number .nasa-sl').length > 0) {
                        $('.compare-number .nasa-sl').html('0');
                        if (!$('.compare-number').hasClass('nasa-product-empty')) {
                            $('.compare-number').addClass('nasa-product-empty');
                        }
                    }

                    $('.nasa-compare-success').html(res.mess_compare);
                    $('.nasa-compare-success').fadeIn(200);
                    
                    if(_compare_table) {
                        $('.nasa-wrap-table-compare').replaceWith(res.result_table);
                    }
                } else {
                    $('.nasa-compare-exists').html(res.mess_compare);
                    $('.nasa-compare-exists').fadeIn(200);
                }

                setTimeout(function () {
                    $('.nasa-compare-success').fadeOut(200);
                    $('.nasa-compare-exists').fadeOut(200);
                    $('.nasa-close-mini-compare').click();
                }, 2000);
            }

            $('.nasa-compare-list-bottom').find('.nasa-loader, .please-wait').remove();
        },
        error: function () {

        }
    });
}

function showCompare($) {
    if ($('.nasa-compare-list-bottom').length > 0) {
        $('.transparent-window').show();
        if ($('.nasa-show-compare').length > 0 && !$('.nasa-show-compare').hasClass('nasa-showed')) {
            $('.nasa-show-compare').addClass('nasa-showed');
        }
        $('.nasa-compare-list-bottom').animate({'bottom': 0}, 800);
    }
}
function hideCompare($) {
    if ($('.nasa-compare-list-bottom').length > 0) {
        $('.transparent-window').hide(800);
        if ($('.nasa-show-compare').length > 0 && $('.nasa-show-compare').hasClass('nasa-showed')) {
            $('.nasa-show-compare').removeClass('nasa-showed');
        }
        $('.nasa-compare-list-bottom').animate({'bottom': '-150%'}, 800);
    }
}

function loadTipTop($) {
    if ($('.tip-top').length > 0) {
        var tip, option;
        $('.tip-top').each(function () {
            option = {mode: "top"};
            tip = $(this);
            if($(tip).parents('.nasa-group-btn-in-list') <= 0) {
                if (!$(tip).hasClass('nasa-tiped')) {
                    $(tip).addClass('nasa-tiped');
                    if ($(tip).attr('data-pos') === 'bot') {
                        option = {mode: "bottom"};
                    }

                    $(tip).tipr(option);
                }
            }
        });
    }
}

function changeLayoutShopPage($, _this) {
    var value_cookie, class_item;
    // var item_row, item_html;

    if ($(_this).hasClass('productList')) {
        value_cookie = 'list';
        $('.nasa-content-page-products .products').removeClass('grid').addClass('list');
    } else {
        var columns = $(_this).attr('data-columns');
        
        if($('input[name="nasa-data-sidebar"]').length === 1) {
            columns = columns === '5' ? '4' : columns;
        }
        
        class_item = 'product-warp-item columns';

        switch (columns) {
            case '3' :
                // item_row = 3;
                value_cookie = 'grid-3';
                class_item += ' large-4';
                break;
            case '4' :
                // item_row = 4;
                value_cookie = 'grid-4';
                class_item += ' large-3';
                break;
            case '5' :
            default :
                // item_row = 5;
                value_cookie = 'grid-5';
                class_item += ' nasa-5-col';
                break;
        }

        var count = $('.nasa-content-page-products .products').find('.product-warp-item').length;
        if (count > 0) {
            var _wrap_all = $('.nasa-content-page-products .products .nasa-product-wrap-all-items');
            if($(_wrap_all).length) {
                var _col_medium = $(_wrap_all).attr('data-columns_medium');
                switch (_col_medium) {
                    case '3' :
                        class_item += ' medium-4';
                        break;
                    case '4' :
                        class_item += ' medium-3';
                        break;
                    case '2' :
                    default :
                        class_item += ' medium-6';
                        break;
                }
                
                var _col_small = $(_wrap_all).attr('data-columns_small');
                switch (_col_small) {
                    case '2' :
                        class_item += ' small-6';
                        break;
                    case '1' :
                    default :
                        class_item += ' small-12';
                        break;
                }
            }

            // var new_content = '<div class="row">';
            // var i = 0;
            $('.nasa-content-page-products .products').find('.product-warp-item').each(function () {
                var item = $(this);
                $(item).attr('class', class_item);
                $(item).find('.nasa-gift-featured-event').removeClass('nasa-inited');
                // $(item).find('.product-item').removeClass('animated').removeAttr('style');
                $(item).find('.tip-top').removeClass('nasa-tiped');
                // item_html = $(item).html();
                // new_content += (i && (i % item_row) == 0) ? '</div><div class="row">' : '';
                // new_content += '<div class="product-warp-item ' + class_item + '">' + item_html + '</div>';
                //i++;
            });
            // new_content += '</div>';
        }

        $('.nasa-content-page-products .products').removeClass('list').addClass('grid');
        // $('.nasa-content-page-products .products').html(new_content);
    }

    $(".nasa-change-layout").removeClass("active");
    $(_this).addClass("active");
    $.cookie('gridcookie', value_cookie, {path: '/'});
    loadTipTop($);
    initThemeNasaGiftFeatured($);
}

function nasa_single_add_to_cart($, _this, _id, _quantity, _type, _variation_id, _variation, _head_type, _data_wislist) {
    if(_type === 'grouped') {
        $('form.cart').find('.nasa-custom-fields').append('<input type="hidden" name="nasa_cart_sidebar" value="1" />');
        $('form.cart').submit();
        return;
    }
    // Ajax add to cart
    else {
        $.ajax({
            url: ajaxurl,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'nasa_single_add_to_cart',
                product_id: _id,
                quantity: _quantity,
                product_type: _type,
                variation_id: _variation_id,
                variation: _variation,
                head_type: _head_type,
                data_wislist: _data_wislist
            },
            beforeSend: function () {
                $.magnificPopup.close();
                $('.black-window').fadeIn(200).addClass('desk-window');
                $('#nasa-wishlist-sidebar').show().animate({right: '-100%'}, 500).hide(500);
                $('#cart-sidebar').show().animate({right: 0}, 500);
                // $('.nasa-cart-fog').show().html('<div class="please-wait type2"></div>');
                $('.nasa-cart-fog').show().html('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
            },
            success: function (res) {
                if (res.error) {
                    window.location.reload();
                } else {
                    var fragments = res.fragments;
                    if (fragments) {
                        $.each(fragments, function (key, value) {
                            $(key).addClass('updating');
                            $(key).replaceWith(value);
                        });
                        $('.nasa-cart-fog').hide();
                        
                        if(!$(_this).hasClass('added')) {
                            $(_this).addClass('added');
                        }
                    }

                    if ($('.wishlist_sidebar').length > 0) {
                        if (typeof res.wishlist !== 'undefined') {
                            $('.wishlist_sidebar').replaceWith(res.wishlist);
                            if ($('.wishlist-number .nasa-sl').length > 0) {
                                var sl_wislist = parseInt(res.wishlistcount);
                                $('.wishlist-number .nasa-sl').html(sl_wislist);
                                if (sl_wislist > 0) {
                                    $('.wishlist-number').removeClass('nasa-product-empty');
                                }
                                else if (sl_wislist === 0 && !$('.wishlist-number').hasClass('nasa-product-empty')) {
                                    $('.wishlist-number').addClass('nasa-product-empty');
                                }
                            }

                            if ($('.add-to-wishlist-' + _id).length > 0) {
                                $('.add-to-wishlist-' + _id).find('.yith-wcwl-add-button').show();
                                $('.add-to-wishlist-' + _id).find('.yith-wcwl-wishlistaddedbrowse').hide();
                                $('.add-to-wishlist-' + _id).find('.yith-wcwl-wishlistexistsbrowse').hide();
                            }
                        }
                    }
                    
                    if($('.page-shopping-cart').length === 1) {
                        $.ajax({
                            url: window.location.href,
                            type: 'get',
                            dataType: 'html',
                            data: {},
                            success: function (res) {
                                var $html = $.parseHTML(res);
                                
                                if($('.nasa-shopping-cart-form').length === 1) {
                                    var $new_form   = $('.nasa-shopping-cart-form', $html);
                                    var $new_totals = $('.cart_totals', $html);
                                    var $notices    = $('.woocommerce-error, .woocommerce-message, .woocommerce-info', $html);
                                    $('.nasa-shopping-cart-form').replaceWith($new_form);
                                    
                                    if ($notices.length > 0) {
                                        $('.nasa-shopping-cart-form').before($notices);
                                    }
                                    $('.cart_totals').replaceWith($new_totals);
                                    
                                } else {
                                    var $neu_content = $('.page-shopping-cart', $html);
                                    $('.page-shopping-cart').replaceWith($neu_content);
                                }
                                
                                $(document.body).trigger('updated_cart_totals');
                                $(document.body).trigger('updated_wc_div');
                                $('.nasa-shopping-cart-form').find('input[name="update_cart"]').prop('disabled', true);
                            }
                        });
                    }
                }
            }
        });
    }
    
    return false;
}

function loadComboRowDown($, _this) {
    if (!$(_this).hasClass('nasaing')) {
        $('.btn-combo-link').addClass('nasaing');
        var row = $(_this).parents('.product-warp-item').parent();
        var item = $(_this).parents('.product-item');
        var row_after = $(row).next();
        var combo_id = 0;
        $(row).find('.product-item').removeClass('nasa-active');
        
        if ($(row_after).hasClass('nasa-combo-row')) {
            combo_id = $(row_after).attr('data-prod');
            $(row_after).slideUp(500);
        }

        var pid = $(_this).attr('data-prod');
        if (pid) {
            if (combo_id === pid) {
                if (!$(row_after).hasClass('comboed-row')) {
                    $(row_after).slideDown(500);
                    $(item).addClass('nasa-active');
                    setTimeout(function () {
                        $(row_after).addClass('comboed-row');
                        $('.btn-combo-link').removeClass('nasaing');
                    }, 500);
                } else {
                    setTimeout(function () {
                        $(row_after).removeClass('comboed-row');
                        $('.btn-combo-link').removeClass('nasaing');
                    }, 500);
                }
            } else {
                $.ajax({
                    url: ajaxurl,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'nasa_combo_products',
                        id: pid
                    },
                    beforeSend: function() {
                        $(item).append('<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
                        $(item).find('.inner-wrap').css('opacity', '0.3');
                    },
                    success: function (res) {
                        if (!$(row_after).hasClass('nasa-combo-row')) {
                            $(row).after('<div class="row nasa-combo-row comboed-row" data-prod="' + pid + '">' + res.content + '</div>');
                            row_after = $(row).next();
                            setTimeout(function () {
                                $('.btn-combo-link').removeClass('nasaing');
                                $(item).find('.please-wait, .nasa-loader').remove();
                                $(item).find('.inner-wrap').css('opacity', '1');
                            }, 500);
                        } else {
                            if (!$(row_after).hasClass('comboed-row')) {
                                $(row_after).attr('data-prod', pid).html(res.content).slideDown(500);
                                $(item).addClass('nasa-active');
                                setTimeout(function () {
                                    $(row_after).addClass('comboed-row');
                                    $('.btn-combo-link').removeClass('nasaing');
                                    $(item).find('.please-wait, .nasa-loader').remove();
                                    $(item).find('.inner-wrap').css('opacity', '1');
                                }, 500);
                            } else {
                                $(row_after).attr('data-prod', pid).html(res.content).slideDown(500);
                                $(item).addClass('nasa-active');
                                $('.btn-combo-link').removeClass('nasaing');
                                $(item).find('.please-wait, .nasa-loader').remove();
                                $(item).find('.inner-wrap').css('opacity', '1');
                            }
                        }
                        
                        var _carousel = $(row_after).find('.nasa-combo-slider');
                        loadCarouselCombo($, _carousel, 4);

                        $(row_after).hide();
                        $(row_after).slideDown(500);
                        setTimeout(function () {
                            $(item).addClass('nasa-active');
                            if(!wow_enable) {
                                $(_carousel).find('.product-item').css({'visibility': 'visible'});
                            } else {
                                var _data_animate, _delay;
                                $(_carousel).find('.product-item').each(function() {
                                    var _item = $(this);
                                    if(!$(_item).hasClass('animated')) {
                                        _data_animate = $(_item).attr('data-wow');
                                        _delay = parseInt($(_item).attr('data-wow-delay'));
                                        $(_item).css({
                                            'visibility': 'visible',
                                            'animation-delay': _delay + 'ms',
                                            'animation-name': _data_animate
                                        });
                                    }
                                });
                            }
                            
                            var _height = $(_carousel).find('.owl-height').height();
                            var _real_height = $(_carousel).find('.owl-stage').height();
                            if(_height < _real_height) {
                                $(_carousel).find('.owl-height').removeAttr('style').css({'min-height': _real_height});
                            }
                        }, 500);
                    },
                    error: function () {
                        $('.btn-combo-link').removeClass('nasaing');
                    }
                });
            }
        }
    }
}

function loadComboPopup($, _this) {
    var item = $(_this).parents('.product-item');
    if (!$(_this).hasClass('nasaing')) {
        $('.btn-combo-link').addClass('nasaing');
        var pid = $(_this).attr('data-prod');
        if (pid) {
            $.ajax({
                url: ajaxurl,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'nasa_combo_products',
                    id: pid,
                    'title_columns': 2
                },
                beforeSend: function () {
                    // $(_this).after('<div class="please-wait type-combo type2" style="top:50%"></div>');
                    $(item).append('<div class="nasa-loader" style="top:50%"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>');
                    $(item).find('.inner-wrap').css('opacity', '0.3');
                },
                success: function (res) {
                    $.magnificPopup.open({
                        mainClass: 'my-mfp-slide-bottom nasa-combo-popup-wrap',
                        items: {
                            src: '<div class="row nasa-combo-popup nasa-combo-row comboed-row zoom-anim-dialog" data-prod="' + pid + '">' + res.content + '</div>',
                            type: 'inline'
                        },
                        removalDelay: 300,
                        callbacks: {
                            afterClose: function() {
                                
                            }
                        }
                    });
                    
                    var _carousel = $('.nasa-combo-popup').find('.nasa-combo-slider');
                    loadCarouselCombo($, _carousel, 4);
                    
                    setTimeout(function () {
                        $('.btn-combo-link').removeClass('nasaing');
                        $(item).find('.please-wait, .nasa-loader').remove();
                        $(item).find('.inner-wrap').css('opacity', '1');
                        if(!wow_enable) {
                            $('.nasa-combo-popup').find('.product-item').css({'visibility': 'visible'});
                        } else {
                            var _data_animate, _delay;
                            $('.nasa-combo-popup').find('.product-item').each(function() {
                                var _this = $(this);
                                _data_animate = $(_this).attr('data-wow');
                                _delay = parseInt($(_this).attr('data-wow-delay'));
                                $(_this).css({
                                    'visibility': 'visible',
                                    'animation-delay': _delay + 'ms',
                                    'animation-name': _data_animate
                                }).addClass('animated');
                            });
                        }
                        var _height = $('.nasa-combo-popup').find('.owl-height').height();
                        var _real_height = $('.nasa-combo-popup').find('.owl-stage').height();
                        if(_height < _real_height) {
                            $('.nasa-combo-popup').find('.owl-height').removeAttr('style').css({'min-height': _real_height});
                        }
                    }, 500);
                },
                error: function () {
                    $('.btn-combo-link').removeClass('nasaing');
                }
            });
        }
    }
}

function loadCarouselCombo($, _carousel, max_columns) {
    $(_carousel).owlCarousel({
        nav: false,
        loop: false,
        autoHeight: true,
        dots: false,
        autoplay: false,
        autoplaySpeed: 600,
        navSpeed: 600,
        autoplayHoverPause: true,
        navText: ["", ""],
        responsive: {
            "0": {
                items: 1,
                nav: false
            },
            "600": {
                items: 3,
                nav: false
            },
            "1000": {
                items: max_columns,
                nav: false
            }
        }
    });
    
    _carousel.find('.owl-item.active:first').addClass('first');
    _carousel.on('translated.owl.carousel', function(items) {
        var warp = items.target;
        if($(warp).find('.owl-item').length > 0){
            $(warp).find('.owl-item').removeClass('first');
            $(warp).find('.owl-item.active:first').addClass('first');
        }
    });
}

/*
 * Nasa gift featured
 */
function initThemeNasaGiftFeatured($) {
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

function renderTagClouds($) {
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

// Reload Height deal
function loadHeightDeal($) {
    if($('.nasa-row-deal-3').length > 0) {
        var bodyWidth = $('body').width();
        if(bodyWidth > 945) {
            $('.nasa-row-deal-3').each(function() {
                var _this = $(this);
                var _sc = $(_this).find('.main-deal-block .nasa-sc-pdeal-block');
                var _side = $(_this).find('.nasa-sc-product-deals-grid');
                if($(_side).length === 1) {
                    var _height = $(_side).height();
                    $(_sc).css({'min-height': _height - 30});
                }
            });
        } else {
            $('.nasa-row-deal-3 .main-deal-block .nasa-sc-pdeal-block').css({'height': 'auto'});
        }
    }
}

/**
 * Load height full to side
 */
function loadHeightFullWidthToSide($) {
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
 * Main menu Reponsive
 */
function loadReponsiveMainMenu($) {
    if($('.nasa-menus-wrapper-reponsive').length === 1) {
        var _wrap = $('.nasa-menus-wrapper-reponsive').parents('.wide-nav');
        var _wwin = $(window).width();
        var _tl = _wwin/1200;
        if(_tl < 1) {
            var _x = $('.nasa-menus-wrapper-reponsive').attr('data-padding_x');
            var _y = $('.nasa-menus-wrapper-reponsive').attr('data-padding_y');
            var _params = {'font-size': (100*_tl).toString() + '%'};
            _params.padding = ($(_wrap).hasClass('nasa-nav-style-1')) ? (_tl*_y).toString() + 'px ' + (_tl*_x*2).toString() + 'px ' + (_tl*_y).toString() + 'px 0' : (_tl*_y).toString() + 'px ' + (_tl*_x).toString() + 'px';
            
            $('.nasa-menus-wrapper-reponsive').find('#site-navigation > li > a').css(_params);

            $('.nasa-menus-wrapper-reponsive').find('.nasa-title-vertical-menu').css({
                'padding': (_tl*_y - 1).toString() + 'px ' + (_tl*_x + 10).toString() + 'px ' + (_tl*_y - 1).toString() + 'px 0'
            });

            $('.nasa-menus-wrapper-reponsive').find('.title-inner').css({
                'font-size': (100*_tl).toString() + '%'
            });
        } else {
            $('.nasa-menus-wrapper-reponsive').find('#site-navigation > li > a').removeAttr('style');
            $('.nasa-menus-wrapper-reponsive').find('.nasa-title-vertical-menu').removeAttr('style');
            $('.nasa-menus-wrapper-reponsive').find('.title-inner').removeAttr('style');
        }

        if(_wwin < 1200) {
            var _w_wrap = $('.nasa-menus-wrapper-reponsive').outerWidth();
            $('#site-navigation .nasa-megamenu > .nav-dropdown').css({'max-width': _w_wrap});
        } else {
            $('#site-navigation .nasa-megamenu > .nav-dropdown').removeAttr('style');
        }
    }
}

/**
 * 
 * @type initMainMenuVertical.mini_acc|initMainMenuVertical.head_menu|StringMain menu
 */
function initMainMenuVertical($) {
    var  _mobile_menu = '';
    if($('#site-navigation').length === 1) {
        
        _mobile_menu += $('#site-navigation').html();
        
        /**
         * Vertical menu
         */
        if($('#nasa-menu-vertical-header .vertical-menu-wrapper').length > 0){
            var ver_menu = $('#nasa-menu-vertical-header .vertical-menu-wrapper').html();
            var ver_menu_title = $('#nasa-menu-vertical-header .nasa-title-vertical-menu').html();
            var ver_menu_warp = '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-parent-item default-menu root-item li_accordion"><a href="javascript:void(0);">' + ver_menu_title + '</a><div class="nav-dropdown-mobile"><div class="div-sub"><ul class="sub-menu">' + ver_menu + '</ul></div></div></li>';
            _mobile_menu += ver_menu_warp;
        }
        
        /**
         * Topbar menu
         */
        if ($('.nasa-topbar-menu').length) {
            _mobile_menu += $('.nasa-topbar-menu').html();
        }
        
        /**
         * Mobile account
         */
        if($('#heading-menu-mobile').length === 1 && $('#mobile-account').length === 1) {
            var head_menu = '<li class="menu-item root-item menu-item-heading">' + $('#heading-menu-mobile').html() + '</li>';
            var mini_acc = '<li class="menu-item root-item menu-item-account">' + $('#mobile-account').html() + '</li>';
            _mobile_menu = head_menu + _mobile_menu + mini_acc;
        }
        
        /**
         * Switch language
         */
        var switch_lang = '';
        if($('.topbar-menu-container .header-switch-languages').length === 1) {
            switch_lang = '<li class="margin-left-10 margin-right-5">' + $('.topbar-menu-container .header-switch-languages').find('li').html() + '<li>';
        }
        
        _mobile_menu = '<ul id="mobile-navigation" class="header-nav nasa-menu-accordion">' + switch_lang + _mobile_menu + '</ul>';
        $('#nasa-menu-sidebar-content #mobile-navigation').replaceWith(_mobile_menu);
        var _nav =  $('#nasa-menu-sidebar-content').find('#mobile-navigation');
        $(_nav).find('.nav-dropdown').attr('class', 'nav-dropdown-mobile').removeAttr('style').find('>.div-sub').removeAttr('style');
        $(_nav).find('.nav-dropdown-mobile').find('.sub-menu').removeAttr('style');
        $(_nav).find('.nav-column-links').addClass('nav-dropdown-mobile');
        $(_nav).find('hr.hr-nasa-megamenu').remove();
        $(_nav).find('li').each(function(){
            if($(this).hasClass('menu-item-has-children')){
                $(this).addClass('li_accordion');
                if($(this).hasClass('current-menu-ancestor') || $(this).hasClass('current-menu-parent')){
                    $(this).addClass('active');
                    $(this).prepend('<a href="javascript:void(0);" class="accordion"><span class="icon fa fa-minus-square-o"></span></a>');
                }else{
                    $(this).prepend('<a href="javascript:void(0);" class="accordion"><span class="icon fa fa-plus-square-o"></span></a>').find('>.nav-dropdown-mobile').hide();
                }
            }
        });
        
        var _w_wrap = $('#nasa-menu-sidebar-content').outerWidth().toString();
        $('#nasa-menu-sidebar-content').css({'left': '-' + _w_wrap + 'px'});
        
        var _h_adminbar = $('#wpadminbar').length > 0 ? $('#wpadminbar').height() : 0;
        if(_h_adminbar > 0) {
            $('#nasa-menu-sidebar-content').css({'top': _h_adminbar});
        }
    }
}

/**
 * Render top sidebar
 */
function initNasaTopSidebar($) {
    var wk = 0;
    var first = ' nasa-first';
    var last = '';

    var rows = '<div class="row nasa-show">';
    $('.nasa-top-sidebar').find('>.row>.widget').each(function() {
        var _this = $(this);
        
        if (wk !== 0 && wk % 4 === 0) {
            rows += '</div><div class="row nasa-hidden">';
            first = ' nasa-first';
        }
        
        last = (wk !== 0 && wk % 4 === 3) ? ' nasa-last' : '';
        rows += '<div class="large-3 columns' + first + last + '">';
        rows += $(_this).wrap('<div>').parent().html();
        rows += '</div>';
        first = '';
        wk++;
    });

    rows += '</div>';
    $('.nasa-top-sidebar').html(rows);
    if($('.nasa-togle-topbar').attr('data-filter') === '1') {
        $('.nasa-top-sidebar').removeClass('hidden-tag');
    }
}

/**
 * clone add to cart button fixed
 * 
 * @param {type} $
 * @returns {String}
 */
function nasa_clone_add_to_cart($) {
    var _ressult = '';
    
    if($('.nasa-product-details-page').length) {
        var _wrap = $('.nasa-product-details-page');
        
        /**
         * Variations
         */
        if($(_wrap).find('.single_variation_wrap').length) {
            var _price = $(_wrap).find('.single_variation_wrap .woocommerce-variation .woocommerce-variation-price').length && $(_wrap).find('.single_variation_wrap .woocommerce-variation').css('display') !== 'none' ?
                $(_wrap).find('.single_variation_wrap').find('.woocommerce-variation-price').html() : '';
            var _addToCart = $(_wrap).find('.single_variation_wrap').find('.woocommerce-variation-add-to-cart').clone();
            $(_addToCart).find('*').removeAttr('id');
            var _btn = $(_addToCart).html();
            
            var _disable = $(_wrap).find('.single_variation_wrap').find('.woocommerce-variation-add-to-cart-disabled').length ? ' nasa-clone-disable' : '';

            _ressult = '<div class="nasa-single-btn-clone single_variation_wrap-clone' + _disable + '">' + _price + '<div class="woocommerce-variation-add-to-cart-clone">' + _btn + '</div></div>';
        }

        /**
         * Simple
         */
        else if($(_wrap).find('.cart').length){
            var _addToCart = $(_wrap).find('.cart').clone();
            $(_addToCart).find('*').removeAttr('id');
            var _btn = $(_addToCart).html();
            
            _ressult = '<div class="nasa-single-btn-clone">' + _btn + '</div>';
        }
    }
    
    return _ressult;
}

/**
 * init Wishlist icons
 */
function initWishlistIcons($, init) {
    if (
        $('.wishlist_sidebar .wishlist_table').length ||
        $('.wishlist_sidebar .nasa_yith_wishlist_premium-wrap .wishlist_table').length
    ) {
        var _init = typeof init === 'undefined' ? false : init;
        
        var _wishlistArr = [];
        if ($('.wishlist_sidebar .wishlist_table [data-row-id]').length) {
            $('.wishlist_sidebar .wishlist_table [data-row-id]').each(function() {
                _wishlistArr.push($(this).attr('data-row-id'));
            });
        }
        
        if ($('.wishlist_sidebar .nasa_yith_wishlist_premium-wrap .wishlist_table tbody tr').length) {
            $('.wishlist_sidebar .nasa_yith_wishlist_premium-wrap .wishlist_table tbody tr').each(function() {
                _wishlistArr.push($(this).attr('data-row-id'));
            });
        }
        
        $('.btn-wishlist').each(function() {
            var _this = $(this);
            var _prod = $(_this).attr('data-prod');
            
            if (_wishlistArr.indexOf(_prod) !== -1) {
                if (!$(_this).hasClass('nasa-added')) {
                    $(_this).addClass('nasa-added');
                }
                
                if (!$(_this).find('.wishlist-icon').hasClass('added')) {
                    $(_this).find('.wishlist-icon').addClass('added');
                }
            }
            
            else if (_init) {
                if ($(_this).hasClass('nasa-added')) {
                    $(_this).removeClass('nasa-added');
                }
                
                if ($(_this).find('.wishlist-icon').hasClass('added')) {
                    $(_this).find('.wishlist-icon').removeClass('added');
                }
            }
        });
    }
}
