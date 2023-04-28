<?php

// **********************************************************************// 
// ! Fix shortcode content
// **********************************************************************//
if (!function_exists('nasa_fixShortcode')) {

    function nasa_fixShortcode($content) {
        $fix = array(
            '&nbsp;' => '',
            '<p>' => '',
            '</p>' => '',
            '<p></p>' => '',
        );
        $content = strtr($content, $fix);
        $content = wpautop(preg_replace('/<\/?p\>/', "\n", $content) . "\n");

        return do_shortcode(shortcode_unautop($content));
    }

}

add_action('wp_ajax_get_shortcode', 'nasa_get_shortcode');
add_action('wp_ajax_nopriv_get_shortcode', 'nasa_get_shortcode');
function nasa_get_shortcode() {
    die(do_shortcode($_POST["content"]));
}

/* ==========================================================================
  WooCommerce - Function get Query
  ========================================================================== */

function nasa_woocommerce_query($type, $post_per_page = -1, $cat = '', $paged = '', $not = array()) {
    global $woocommerce;
    if (!$woocommerce) {
        return array();
    }
    $wpQueryObj = new WP_Query(nasa_woocommerce_query_args($type, $post_per_page, $cat, $paged, $not));

    remove_filter('posts_clauses', 'nasa_order_by_rating_post_clauses');
    remove_filter('posts_clauses', 'nasa_order_by_recent_review_post_clauses');
    
    return $wpQueryObj;
}

/**
 * Order by rating review
 * @global type $wpdb
 * @param type $args
 * @return array
 */
function nasa_order_by_rating_post_clauses($args) {
    global $wpdb;
    
    $args['fields'] .= ', AVG(' . $wpdb->commentmeta . '.meta_value) as average_rating';
    $args['where']  .= ' AND (' . $wpdb->commentmeta . '.meta_key = "rating" OR ' . $wpdb->commentmeta . '.meta_key IS null) AND ' . $wpdb->comments . '.comment_approved=1 ';
    $args['join']   .= ' LEFT OUTER JOIN ' . $wpdb->comments . ' ON(' . $wpdb->posts . '.ID = ' . $wpdb->comments . '.comment_post_ID) LEFT JOIN ' . $wpdb->commentmeta . ' ON(' . $wpdb->comments . '.comment_ID = ' . $wpdb->commentmeta . '.comment_id) ';
    $args['orderby'] = 'average_rating DESC, ' . $wpdb->posts . '.post_date DESC';
    $args['groupby'] = $wpdb->posts . '.ID';

    return $args;
}

/**
 * Order by recent review
 * @global type $wpdb
 * @param type $args
 * @return array
 */
function nasa_order_by_recent_review_post_clauses($args) {
    global $wpdb;
    
    $args['where']  .= ' AND ' . $wpdb->comments . '.comment_approved=1 ';
    $args['join']   .= ' LEFT JOIN ' . $wpdb->comments . ' ON(' . $wpdb->posts . '.ID = ' . $wpdb->comments . '.comment_post_ID)';
    $args['orderby'] = $wpdb->comments . '.comment_date DESC, ' . $wpdb->comments . '.comment_post_ID DESC';
    $args['groupby'] = $wpdb->posts . '.ID';

    return $args;
}

function nasa_woocommerce_query_args($type, $post_per_page = -1, $cat = '', $paged = '', $not = array()) {
    global $woocommerce;
    if (!$woocommerce) {
        return array();
    }
    
    $paged = $paged == '' ? ($paged = get_query_var('paged') ? (int) $paged : 1) : (int) $paged;
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $post_per_page,
        'post_status' => 'publish',
        'paged' => $paged
    );
    
    $args['meta_query'] = array();
    $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
    $args['tax_query'] = array('relation' => 'AND');
    switch ($type) {
        case 'best_selling':
            $args['meta_key']   = 'total_sales';
            $args['order']      = 'DESC';
            $args['orderby']    = 'meta_value_num';
            $args['ignore_sticky_posts'] = 1;
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'featured_product':
            $args['ignore_sticky_posts'] = 1;
            $args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured'
            );
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'top_rate':
            add_filter('posts_clauses', 'nasa_order_by_rating_post_clauses');
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'on_sale':
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['post__in'] = wc_get_product_ids_on_sale();
            break;
        case 'recent_review':
            // nasa_order_by_recent_review_post_clauses
            add_filter('posts_clauses', 'nasa_order_by_recent_review_post_clauses');
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'deals':
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['meta_query'][] = array(
                'key'       => '_sale_price_dates_to',
                'value'     => '0',
                'compare'   => '>',
                'type'      => 'numeric'
            );
            $args['post__in'] = wc_get_product_ids_on_sale();
            $args['post_type'] = array('product', 'product_variation');
            
            break;
        
        case 'recent_product':
        default:
            $args['orderby'] = 'date ID';
            $args['order']   = 'DESC';
            break;
    }
    
    if (!empty($not)) {
        $args['post__not_in'] = $not;
        
        if(!empty($args['post__in'])) {
            $args['post__in'] = array_diff($args['post__in'], $args['post__not_in']);
        }
    }

    if (is_numeric($cat) && $cat) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'terms' => array($cat)
        );
    }
    
    elseif (is_array($cat) && $cat) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'terms' => $cat
        );
    }

    // Find by slug
    elseif ($cat != '') {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $cat
        );
    }
    
    $product_visibility_terms = wc_get_product_visibility_term_ids();
    $arr_not_in = array($product_visibility_terms['exclude-from-catalog']);
    
    // Hide out of stock products.
    if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
        $arr_not_in[] = $product_visibility_terms['outofstock'];
    }

    if (!empty($arr_not_in)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_visibility',
            'field'    => 'term_taxonomy_id',
            'terms'    => $arr_not_in,
            'operator' => 'NOT IN',
        );
    }
    
    if(empty($args['orderby']) || empty($args['order'])) {
        $ordering_args      = WC()->query->get_catalog_ordering_args();
        $args['orderby']    = empty($args['orderby']) ? $ordering_args['orderby'] : $args['orderby'];
        $args['order']      = empty($args['order']) ? $ordering_args['order'] : $args['order'];
    }

    return apply_filters('nasa_woocommerce_query_args', $args);
}

// **********************************************************************// 
// ! Twitter API functions
// **********************************************************************// 
function nasa_capture_tweets($consumer_key, $consumer_secret, $user_token, $user_secret, $user, $count) {
    if (!class_exists('TwitterOAuth')) {
        return;
    }
    
    $connection = new TwitterOAuth($consumer_key, $consumer_secret, $user_token, $user_secret);
    $content = $connection->get("statuses/user_timeline", array(
        'screen_name' => $user,
        'count' => $count
    ));

    return json_encode($content);
}

function nasa_tweet_linkify($tweet) {
    $tweet = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $tweet);
    $tweet = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $tweet);
    $tweet = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $tweet);
    $tweet = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $tweet);
    return $tweet;
}

function nasa_store_tweets($file, $tweets) {
    ob_start(); // turn on the output buffering 
    $fo = fopen($file, 'w'); // opens for writing only or will create if it's not there
    if (!$fo) {
        return nasa_print_tweet_error(error_get_last());
    }
    $fr = fwrite($fo, $tweets); // writes to the file what was grabbed from the previous function
    if (!$fr) {
        return nasa_print_tweet_error(error_get_last());
    }
    fclose($fo); // closes
    ob_end_flush(); // finishes and flushes the output buffer; 
}

function nasa_pick_tweets($file) {
    ob_start(); // turn on the output buffering 
    $fo = fopen($file, 'r'); // opens for reading only 
    if (!$fo) {
        return nasa_print_tweet_error(error_get_last());
    }
    $fr = fread($fo, filesize($file));
    if (!$fr) {
        return nasa_print_tweet_error(error_get_last());
    }
    fclose($fo);
    ob_end_flush();
    return $fr;
}

function nasa_print_tweet_error($errorArray) {
    return '<p class="eth-error">Error: ' . $errorArray['message'] . 'in ' . $errorArray['file'] . 'on line ' . $errorArray['line'] . '</p>';
}

function nasa_twitter_cache_enabled() {
    return true;
}

function nasa_print_tweets($consumer_key, $consumer_secret, $user_token, $user_secret, $user, $count, $cachetime = 50) {
    if (nasa_twitter_cache_enabled()) {
        //setting the location to cache file
        $cachefile = get_template_directory() . '/includes/cache/twitterCache.json';

        // the file exitsts but is outdated, update the cache file
        if (file_exists($cachefile) && ( current_time('timestamp') - $cachetime > filemtime($cachefile)) && filesize($cachefile) > 0) {
            //capturing fresh tweets
            $tweets = nasa_capture_tweets($consumer_key, $consumer_secret, $user_token, $user_secret, $user, $count);
            $tweets_decoded = json_decode($tweets, true);
            //if get error while loading fresh tweets - load outdated file
            if (isset($tweets_decoded['error'])) {
                $tweets = nasa_pick_tweets($cachefile);
            }
            //else store fresh tweets to cache
            else {
                nasa_store_tweets($cachefile, $tweets);
            }
        }
        //file doesn't exist or is empty, create new cache file
        elseif (!file_exists($cachefile) || filesize($cachefile) == 0) {
            $tweets = nasa_capture_tweets($consumer_key, $consumer_secret, $user_token, $user_secret, $user, $count);
            $tweets_decoded = json_decode($tweets, true);
            //if request fails, and there is no old cache file - print error
            if (isset($tweets_decoded['error'])) {
                return 'Error: ' . $tweets_decoded['error'];
            }
            //make new cache file with request results
            else {
                nasa_store_tweets($cachefile, $tweets);
            }
        }
        //file exists and is fresh
        //load the cache file
        else {
            $tweets = nasa_pick_tweets($cachefile);
        }
    } else {
        $tweets = nasa_capture_tweets($consumer_key, $consumer_secret, $user_token, $user_secret, $user, $count);
    }

    $tweets = json_decode($tweets, true);
    $html = '<ul class="twitter-list">';

    foreach ($tweets as $tweet) {
        $html .= '<li class="lastItem firstItem"><div class="media"><i class="pull-left fa fa-twitter"></i><div class="media-body">' . $tweet['text'] . '</div></div></li>';
    }
    $html .= '</ul>';
    
    return nasa_tweet_linkify($html);
}

//convert dates to readable format  
if (!function_exists('nasa_relative_time')) {

    function nasa_relative_time($a) {
        //get current timestampt
        $b = strtotime('now');
        //get timestamp when tweet created
        $c = strtotime($a);
        //get difference
        $d = $b - $c;
        //calculate different time values
        $minute = 60;
        $hour = $minute * 60;
        $day = $hour * 24;
        $week = $day * 7;

        if (is_numeric($d) && $d > 0) {
            //if less then 3 seconds
            if ($d < 3) {
                return esc_html__('right now', 'nasa-core');
            }
            //if less then minute
            if ($d < $minute) {
                return floor($d) . esc_html__(' seconds ago', 'nasa-core');
            }
            //if less then 2 minutes
            if ($d < $minute * 2) {
                return esc_html__('about 1 minute ago', 'nasa-core');
            }
            //if less then hour
            if ($d < $hour) {
                return floor($d / $minute) . esc_html__(' minutes ago', 'nasa-core');
            }
            //if less then 2 hours
            if ($d < $hour * 2) {
                return esc_html__('about 1 hour ago', 'nasa-core');
            }
            //if less then day
            if ($d < $day) {
                return floor($d / $hour) . esc_html__(' hours ago', 'nasa-core');
            }
            //if more then day, but less then 2 days
            if ($d > $day && $d < $day * 2) {
                return esc_html__('yesterday', 'nasa-core');
            }
            //if less then year
            if ($d < $day * 365) {
                return floor($d / $day) . esc_html__(' days ago', 'nasa-core');
            }
            //else return more than a year
            return esc_html__('over a year ago', 'nasa-core');
        }
    }

}

// Do shortcode anything more ...
add_action('init', 'nasa_custom_do_sc');
function nasa_custom_do_sc() {
    add_filter('widget_text', 'do_shortcode');
    add_filter('the_excerpt', 'do_shortcode');
}

// Recommend product
add_action('nasa_recommend_product', 'nasa_get_recommend_product', 10, 1);
function nasa_get_recommend_product($catId = null) {
    return '';
    
    global $nasa_opt, $woocommerce;
    
    if (!$woocommerce || !isset($nasa_opt['category_sidebar']) || $nasa_opt['category_sidebar'] == 'top' || (isset($nasa_opt['enable_recommend_product']) && $nasa_opt['enable_recommend_product'] != '1')) {
        return '';
    }

    $columns_number = isset($nasa_opt['products_per_row']) ? (int) $nasa_opt['products_per_row'] : 3;

    $columns_number_small = 1;
    $columns_number_tablet = $columns_number < 3 ? $columns_number : 3;

    $number = (isset($nasa_opt['recommend_product_limit']) && ((int) $nasa_opt['recommend_product_limit'] >= $columns_number)) ? (int) $nasa_opt['recommend_product_limit'] : 9;

    $loop = nasa_woocommerce_query('featured_product', $number, (int) $catId ? (int) $catId : null);
    if ($loop->found_posts) {
        ?>
        <div class="row margin-bottom-50 nasa-recommend-product">
            <div class="large-12 columns">
                <h5 class="nasa-title clearfix title-style-2"><?php echo esc_html__('Recommend Products', 'nasa-core'); ?></h5>
                <div class="woocommerce">
                    <div class="inner-content">
                        <?php
                        $type = null;
                        $data_margin = 0;
                        $height_auto = 'false';
                        include NASA_CORE_PRODUCT_LAYOUTS . 'nasa_products/carousel.php';
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

/* ============================================= */
function nasa_getProductDeals($id = null) {
    if (!(int) $id || !function_exists('wc_get_product')) {
        return null;
    }
    
    $timeNow = time();
    if($product = wc_get_product((int) $id)) {
        $time_sale = false;
        
        if($product->get_type() == 'variable') {
            $args = array(
                'fields' => 'ids',
                'post_type' => 'product_variation',
                'post_parent' => (int) $id,
                'posts_per_page' => 100,
                'post_status' => 'publish',
                'orderby' => 'ID',
                'order' => 'ASC',
                'paged' => 1
            );

            $children = new WP_Query($args);
            if(!empty($children->posts)) {
                foreach ($children->posts as $variable) {
                    $time_sale = get_post_meta($variable, '_sale_price_dates_to', true);
                    if ($time_sale && $time_sale > $timeNow) {
                        break;
                    }
                }
            }
        }
        
        $time_sale = !$time_sale ? get_post_meta((int) $id, '_sale_price_dates_to', true) : $time_sale;
        if($time_sale > $timeNow){
            $product->time_sale = $time_sale;
            return $product;
        }
    }

    return null;
}

function nasa_getProductGrid($notid = null, $catIds = null, $type = 'best_selling', $limit = 6) {
    $notIn = $notid ? array($notid) : array();
    return nasa_woocommerce_query($type, $limit, $catIds, 1, $notIn);
}

function nasa_add_to_cart_button_sc($product, $quickview = true, $echo = true) {
    global $post, $nasa_opt, $wp_query;
    $head_type = $nasa_opt['header-type'];
    if (isset($post->ID)) {
        $custom_header = get_post_meta($wp_query->get_queried_object_id(), '_nasa_custom_header', true);
        if (!empty($custom_header)) {
            $head_type = $custom_header;
        }
    }

    if ($quickview) {
        echo '<div class="add-to-cart-btn"><a href="javascript:void(0);" class="button quick-view primary-color" data-prod="' . esc_attr($product->get_id()) . '" data-head_type="' . esc_attr($head_type) . '">' . esc_html__('Buy now', 'nasa-core') . '</a></div>';
        return;
    }

    $result = apply_filters('woocommerce_loop_add_to_cart_link', sprintf(
        '<div class="add-to-cart-btn">' .
            '<a href="%s" rel="nofollow" data-product_id="%s" class="%s button small product_type_%s add-to-cart-grid" data-head_type="%s">' .
                '<span class="add_to_cart_text">%s</span>' .
                '<span class="cart-icon-handle"></span>' .
            '</a>%s' .
        '</div>',
        esc_url($product->add_to_cart_url()),
        esc_attr($product->get_id()),
        ($product->is_purchasable() && $product->is_in_stock() && $product->product_type == 'simple') ?
            'ajax_add_to_cart add_to_cart_button' : (($product->product_type == 'variable') ? 'ajax_add_to_cart_variable' : ''),
        esc_attr($product->product_type),
        esc_attr($head_type),
        esc_html($product->add_to_cart_text()),
        ($product->product_type == 'variable') ? '<a class="hidden-tag quick-view" data-prod="' . esc_attr($product->get_id()) . '" data-head_type="' . esc_attr($head_type) . '"></a>' : ''
    ), $product);

    if (!$echo) {
        return $result;
    }

    echo $result;
}

function nasa_getThumbs($_id, $image_pri, $count_imgs, $img_thumbs) {
    $thumbs = '<div class="nasa-sc-p-thumbs">';
    $thumbs .= '<div class="product-thumbnails-' . $_id . ' owl-carousel">';

    if ($image_pri) {
        $thumbs .= '<a href="javascript:void(0);" class="active-thumbnail nasa-thumb-a">';
        $thumbs .= '<img class="nasa-thumb-img" src="' . esc_attr($image_pri['thumb'][0]) . '" />';
        $thumbs .= '</a>';
    }

    if ($count_imgs) {
        foreach ($img_thumbs as $key => $thumb) {
            $thumbs .= '<a href="javascript:void(0);" class="nasa-thumb-a">';
            $thumbs .= '<img class="nasa-thumb-img" src="' . esc_attr($thumb['src'][0]) . '" />';
            $thumbs .= '</a>';
        }
    } else {
        $thumbs .= sprintf('<a href="%s" class="active-thumbnail"><img src="%s" /></a>', wc_placeholder_img_src(), wc_placeholder_img_src());
    }

    $thumbs .= '</div>';
    $thumbs .= '</div>';
    return $thumbs;
}

function nasa_getThumbsVertical($_id, $image_pri, $count_imgs, $img_thumbs) {
    $thumbs = '';
    $show = 3;
    $k = 0;
    if ($image_pri) {
        $thumbs .= '<a href="javascript:void(0);" class="nasa-thumb-a"><div class="row nasa-pos-relative">';
        $thumbs .= '<div class="large-4 medium-4 small-2 columns nasa-icon-current"><i class="pe-7s-angle-left"></i></div>';
        $thumbs .= '<div class="large-8 medium-8 small-10 columns"><img class="nasa-thumb-img" src="' . esc_attr($image_pri['thumb'][0]) . '" /></div>';
        $thumbs .= '</div></a>';
        $k++;
    }

    if ($count_imgs) {
        foreach ($img_thumbs as $key => $thumb) {
            $k++;
            $thumbs .= '<a href="javascript:void(0);" class="nasa-thumb-a"><div class="row nasa-pos-relative">';
            $thumbs .= '<div class="large-4 medium-4 small-2 columns nasa-icon-current"><i class="pe-7s-angle-left"></i></div>';
            $thumbs .= '<div class="large-8 medium-8 small-10 columns"><img class="nasa-thumb-img" src="' . esc_attr($thumb['src'][0]) . '" /></div>';
            $thumbs .= '</div></a>';
        }
    } else {
        $k++;
        $imgSrc = wc_placeholder_img_src();
        $thumbs .=
            '<a href="' . $imgSrc . '" class="nasa-thumb-a">' .
                '<div class="nasa-pos-relative">' .
                    '<div class="large-4 medium-4 small-2 columns nasa-icon-current">' .
                        '<i class="pe-7s-angle-left"></i>' .
                    '</div>' .
                    '<div class="large-8 medium-8 small-10 columns">' .
                        '<img src="' . $imgSrc . '" />' .
                    '</div>' .
                '</div>' .
            '</a>';
    }

    $thumbs_begin = '<div class="nasa-sc-p-thumbs">';
    $attr_top = ($k <= $show) ? ' data-top="1"' : '';

    $thumbs_begin .= '<div class="y-thumb-images-' . $_id . ' images-popups-gallery" data-show="' . $show . '" data-autoplay="1"' . $attr_top . '>';

    $thumbs .= '</div>';
    $thumbs .= '</div>';

    return $thumbs_begin . $thumbs;
}

// Product group button
function nasa_sc_product_group_button($product = null, $toltip = false) {
    if (!$product) {
        return;
    }

    global $nasa_opt, $wp_query;
    $nasa_compare = (!isset($nasa_opt['nasa-product-compare']) || $nasa_opt['nasa-product-compare']) ? true : false;
    $_cart_btn = ''; //nasa_add_to_cart_button_sc($product, false, $toltip);
    $head_type = isset($nasa_opt['header-type']) ? $nasa_opt['header-type'] : 1;
    if ($product->get_id()) {
        $custom_header = get_post_meta($wp_query->get_queried_object_id(), '_nasa_custom_header', true);
        if (!empty($custom_header)) {
            $head_type = $custom_header;
        }
    }
    $GLOBALS['product'] = $product;
    
    $file = NASA_THEME_CHILD_PATH . '/includes/nasa-product-buttons.php';
    $file = is_file($file) ? $file : NASA_THEME_PATH . '/includes/nasa-product-buttons.php';
    
    include is_file($file) ? $file : NASA_CORE_PRODUCT_LAYOUTS . 'globals/product-buttons.php';
}

function nasa_category_thumbnail($category, $type = 'nasa-category-vertical') {
    $small_thumbnail_size = apply_filters('subcategory_archive_thumbnail_size', $type);
    $thumbnail_id = function_exists('get_term_meta') ? get_term_meta($category->term_id, 'thumbnail_id', true) : get_woocommerce_term_meta($category->term_id, 'thumbnail_id', true);

    if ($thumbnail_id) {
        $image = wp_get_attachment_image_src($thumbnail_id, $small_thumbnail_size);
        $image = $image[0];
    } else {
        $image = wc_placeholder_img_src();
    }

    if ($image) {
        // Prevent esc_url from breaking spaces in urls for image embeds
        // Ref: https://core.trac.wordpress.org/ticket/23605
        $image = str_replace(' ', '%20', $image);

        echo '<img src="' . esc_url($image) . '" alt="' . esc_attr($category->name) . '" />';
    }
}

function nasa_shortcode_vars($atts) {
    $variables = array();
    if (!empty($atts)) {
        $old_key = '';
        foreach ($atts as $value) {
            $value = explode('=', $value);
            $count = count($value);
            if ($count == 2) {
                $old_key = $value[0];
                $variables[$old_key] = str_replace('"', '', $value[1]);
            } elseif ($count == 1) {
                $variables[$old_key] .= ' ' . str_replace('"', '', $value[0]);
            }
        }
    }
    
    return $variables;
}

function nasa_shortcode_text($name = '', $atts = array(), $content = '') {
    global $id_shortcode;
    $GLOBALS['id_shortcode'] = (!isset($id_shortcode) || !$id_shortcode) ? 1 : $id_shortcode + 1;
    $height = (isset($atts['min_height']) && (int)$atts['min_height']) ? (int)$atts['min_height'] . 'px;' : '200px;';
    $height .= (isset($atts['height']) && (int)$atts['height']) ? 'height:' . (int)$atts['height'] . 'px;' : '';
    $attsSC = array();
    if (!empty($atts)) {
        foreach ($atts as $key => $value) {
            $attsSC[] = $key . '="' . $value . '"';
        }
    }
    
    $result = '<div class="nasa_load_ajax" data-id="' . $id_shortcode . '" id="nasa_sc_' . $id_shortcode . '" data-shortcode="' . $name . '" style="min-height: ' . $height . '">';
    
    $result .= '<div class="nasa-loader"><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div><div class="nasa-line"></div></div>';
    $result .= '<div class="nasa-shortcode-content hidden-tag">[' . $name;
    $result .= !empty($attsSC) ? ' ' . implode(' ', $attsSC) : '';
    $result .= trim($content) != '' ? ']' . esc_html($content) . '[/' . $name : '';
    $result .= ']</div></div>';

    return $result;
}
/* ============================================= */

/**
 * Set cookie products viewed
 */
remove_action('template_redirect', 'wc_track_product_view', 25);
add_action('template_redirect', 'nasa_set_products_viewed', 20);
function nasa_set_products_viewed() {
    global $nasa_opt;
    
    if (!class_exists('WooCommerce') || !is_singular('product') || (isset($nasa_opt['disable-viewed']) && $nasa_opt['disable-viewed'])) {
        return;
    }
    
    global $post;
    
    $product_id = isset($post->ID) ? (int) $post->ID : 0;
    
    if($product_id) {
        
        $limit = !isset($nasa_opt['limit_product_viewed']) || !(int) $nasa_opt['limit_product_viewed'] ?
            12 : (int) $nasa_opt['limit_product_viewed'];

        $list_viewed = !empty($_COOKIE[NASA_COOKIE_VIEWED]) ? explode('|', $_COOKIE[NASA_COOKIE_VIEWED]) : array();
        if(!in_array((int) $product_id, $list_viewed)) {
            if(count($list_viewed) > $limit) {
                array_shift($list_viewed);
            }
            $list_viewed[] = $product_id;

            setcookie(NASA_COOKIE_VIEWED, implode('|', $list_viewed), 0, COOKIEPATH, COOKIE_DOMAIN, false, false);
        }
    }
}

/**
 * Get cookie products viewed
 */
function nasa_get_products_viewed() {
    global $nasa_opt;
    $query = null;
    
    if (!class_exists('WooCommerce') || (isset($nasa_opt['disable-viewed']) && $nasa_opt['disable-viewed'])) {
        return $query;
    }
    
    $viewed_products = !empty($_COOKIE[NASA_COOKIE_VIEWED]) ? explode('|', $_COOKIE[NASA_COOKIE_VIEWED]) : array();
    if(!empty($viewed_products)) {
        
        $limit = !isset($nasa_opt['limit_product_viewed']) || !(int) $nasa_opt['limit_product_viewed'] ? 12 : (int) $nasa_opt['limit_product_viewed'];
        
        $query_args = array(
            'posts_per_page' => $limit,
            'no_found_rows'  => 1,
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'post__in'       => $viewed_products,
            'orderby'        => 'post__in',
        );

        if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'outofstock',
                    'operator' => 'NOT IN',
                ),
            );
        }

        $query = new WP_Query($query_args);
    }
    
    return $query;
}

/**
 * Render Time sale countdown
 * 
 * @param type $time_sale
 * @return type
 */
function nasa_time_sale($time_sale = false, $gmt = true) {
    if($time_sale) {
        return $gmt ?
            '<span class="countdown" data-countdown="' . esc_attr(get_date_from_gmt(date('Y-m-d H:i:s', $time_sale), 'M j Y H:i:s O')) . '"></span>' : 
            '<span class="countdown" data-countdown="' . esc_attr(date('M j Y H:i:s O', $time_sale)) . '"></span>';
    }
    
    return '';
}

/**
 * New Featured
 *
 * Add tab Bought Together
 */
add_filter('woocommerce_product_tabs', 'nasa_accessories_product_tab');
function nasa_accessories_product_tab($tabs) {
    global $product;
    
    if ($product && 'simple' === $product->get_type()) {
        $productIds = get_post_meta($product->get_id(), '_accessories_ids', true);
        if (!empty($productIds)) {
            $GLOBALS['accessories_ids'] = $productIds;
            $tabs['accessories_content'] = array(
                'title'     => esc_html__('Bought Together', 'nasa-core'),
                'priority'  => 5,
                'callback'  => 'nasa_accessories_product_tab_content'
            );
        }
    }

    return $tabs;
}

/**
 * Content accessories of the current Product
 */
function nasa_accessories_product_tab_content() {
    global $product, $accessories_ids, $nasa_opt;
    if (!$product || !$accessories_ids) {
        return;
    }

    $accessories = array();
    foreach ($accessories_ids as $accessories_id) {
        $product_acc = wc_get_product($accessories_id);
        if (
            is_object($product_acc) &&
            $product_acc->get_status() === 'publish' &&
            in_array($product_acc->get_type(), array('simple', 'variation'))
        ) {
            $accessories[] = $product_acc;
        }
    }

    if (empty($accessories)) {
        return;
    }

    include NASA_CORE_PRODUCT_LAYOUTS . 'nasa_single_product/nasa-single-product-accessories-tab-content.php';
}

