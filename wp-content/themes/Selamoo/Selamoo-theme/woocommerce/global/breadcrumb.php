<?php

/**
 * Shop breadcrumb
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */
if (!defined('ABSPATH')) :
    exit; // Exit if accessed directly
endif;

global $post, $wp_query, $nasa_opt;

$prepend = '';
$permalinks = get_option('woocommerce_permalinks');
$shop_page_id = wc_get_page_id('shop');
$shop_page = get_post($shop_page_id);

$shop_page_url = get_permalink($shop_page_id);
$shop_page_title = get_the_title($shop_page_id);

$post_type_name = get_post_type();

// If permalinks contain the shop page in the URI prepend the breadcrumb with shop
if ($shop_page_id > 0 && strstr($permalinks['product_base'], '/' . $shop_page->post_name) && get_option('page_on_front') !== $shop_page_id) {
    $prepend = $before . '<a href="' . get_permalink($shop_page) . '" class="nasa-filter-by-cat" data-id="0">' . $shop_page->post_title . '</a>' . $after . $delimiter;
}

$title = '';
$br_content = '';
$page_info = '';
$paged = get_query_var('paged');
if ($paged && $paged > 1) {
    $page_info = ' (' . esc_html__('Page', 'digi-theme') . ' ' . $paged . ')';
}

if (!empty($home)) {
    $home = $before . '<a class="home" href="' . apply_filters('woocommerce_breadcrumb_home_url', esc_url(home_url('/'))) . '">';
    $home .= esc_html__('Home', 'digi-theme');
    $home .= '</a>' . $after;
}

$tax_cat = is_tax('product_cat');
$tax_brand = is_tax('yith_product_brand');

if ((!is_home() && !is_front_page() && !(is_post_type_archive() && get_option('page_on_front') == $shop_page_id)) || is_paged()) {
    
    if (is_category()) {
        $title = '<h2>' . $before . single_cat_title('', false) . $after . $page_info . '</h2>';
        $cat_obj = $wp_query->get_queried_object();
        $this_category = get_category($cat_obj->term_id);
        $br_content .= $home . $delimiter;
        if ($this_category->parent != 0) {
            $parent_category = get_category($this_category->parent);
            $br_content .= get_category_parents($parent_category, true, $delimiter);
        }
    }
    
    elseif ($tax_cat || $tax_brand) {
        $query_obj = get_queried_object();
        $taxonomy = $query_obj->taxonomy;
        $slug = $query_obj->slug;
        $current_term = get_term_by('slug', $slug, $taxonomy);
        
        $ancestors = array_reverse(get_ancestors($current_term->term_id, $taxonomy));
        $count = count($ancestors);
        $tree_content = $tax_brand ? $before . esc_html__('Brand', 'digi-theme') . $after : '';
        if ($count) {
            foreach ($ancestors as $k => $ancestor) {
                $ancestor = get_term($ancestor, $taxonomy);
                $tree_content .= $before . '<a href="' . get_term_link($ancestor->slug, $taxonomy) . '" class="nasa-filter-by-cat" data-id="' . esc_attr($ancestor->term_id) . '">' . esc_html($ancestor->name) . '</a>' . $after;
                $tree_content .= ($k == $count - 1) ? '' : $delimiter;
            }
        }
        
        if (is_search() && !$ajax) {
            $cat = '<a href="' . get_term_link($current_term->slug, get_query_var('taxonomy')) . '" class="nasa-filter-by-cat" data-id="' . esc_attr($current_term->term_id) . '">' . esc_html($current_term->name) . '</a>' . $after;
            $cat = $tree_content != '' ? $tree_content . $delimiter . $cat : $cat;
            $br_content .= $home . $delimiter . $prepend . $before . $cat . $delimiter;
            $title = '<h2>' . esc_html__('Search results for &ldquo;', 'digi-theme') . get_search_query() . '&rdquo;' . $after . $page_info . '</h2>';
        } else {
            $br_content .= $home . $delimiter . $prepend . $tree_content;
            $title = '<h2>' . $before . esc_html($current_term->name) . $after . $page_info . '</h2>';
        }
        
    }
    
    elseif (is_tax('product_tag')) {
        $queried_object = $wp_query->get_queried_object();
        $title = '<h2>' . $before . esc_html__('Products tagged &ldquo;', 'digi-theme') . $queried_object->name . '&rdquo;' . $after . $page_info . '</h2>';
        $br_content .= $home . $delimiter . $prepend;
    }
    
    elseif (is_day()) {
        $title = '<h2>' . $before . get_the_time('d') . $after . $page_info . '</h2>';
        $br_content .= $home . $delimiter;
        $br_content .= $before . '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $after . $delimiter;
        $br_content .= $before . '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $after;
    }
    
    elseif (is_month()) {
        $title = '<h2>' . $before . get_the_time('F') . $after . $page_info . '</h2>';
        $br_content .= $home . $delimiter;
        $br_content .= $before . '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $after;
    }
    
    elseif (is_year()) {
        $title = '<h2>' . $before . get_the_time('Y') . $after . $page_info . '</h2>';
        $br_content .= $home . $delimiter;
    }
    
    elseif (is_post_type_archive('product') && get_option('page_on_front') !== $shop_page_id) {
        $_name = $shop_page_id ? $shop_page_title : '';
        
        if (!$_name) {
            $product_post_type = get_post_type_object('product');
            $_name = $product_post_type->labels->singular_name;
        }
        
        if (is_search()) {
            $title = '<h2>' . esc_html__('Search results for &ldquo;', 'digi-theme') . get_search_query() . '&rdquo;' . $after . $page_info . '</h2>';
            $br_content .= $home . $delimiter . $before . '<a href="' . get_post_type_archive_link('product') . '" class="nasa-filter-by-cat" data-id="0">' . $_name . '</a>';
        }
        
        elseif (is_paged()) {
            $title = '<h2>' . $before . '<a href="' . get_post_type_archive_link('product') . '" class="nasa-filter-by-cat" data-id="0">' . $_name . '</a>' . $after . $page_info . '</h2>';
            $br_content .= $home;
        }
        
        else {
            $title = '<h2>' . $before . $_name . $after . $page_info . '</h2>';
            $br_content .= $home;
        }
    }
    
    elseif (is_single() && !is_attachment()) {
        if ($post_type_name == 'product') {
            $title = '<h2>' . $before . get_the_title() . $after . '</h2>';

            $br_content .= $home . $delimiter . $prepend;
            if ($terms = wp_get_post_terms($post->ID, 'product_cat', array('orderby' => 'parent', 'order' => 'DESC'))) {
                $main_term = $terms[0];
                $ancestors = get_ancestors($main_term->term_id, 'product_cat');
                $ancestors = array_reverse($ancestors);
                if (count($ancestors)) {
                    foreach ($ancestors as $ancestor) {
                        $ancestor = get_term($ancestor, 'product_cat');
                        $br_content .= $before . '<a href="' . get_term_link($ancestor->slug, 'product_cat') . '">' . $ancestor->name . '</a>' . $after . $delimiter;
                    }
                }
                $br_content .= $before . '<a href="' . get_term_link($main_term->slug, 'product_cat') . '">' . $main_term->name . '</a>' . $after;
            }
        } elseif ($post_type_name != 'post') {
            $post_type = get_post_type_object($post_type_name);
            $slug = $post_type->rewrite;
            $title = '<h2>' . $before . get_the_title() . $after . $page_info . '</h2>';
            $br_content .= $home . $delimiter;
            $br_content .= $before . '<a href="' . esc_url(home_url('/') . $slug['slug']) . '">' . $post_type->labels->singular_name . '</a>' . $after;
        } else {
            $cat = current(get_the_category());
            $title = '<h2>' . $before . get_the_title() . $after . $page_info . '</h2>';
            $br_content .= $home . $delimiter;
            $br_content .= get_category_parents($cat, true, $delimiter);
        }
    } elseif (is_404()) {
        $br_content .= $home . $delimiter;
        $br_content .= $before . esc_html__('Error 404', 'digi-theme') . $after;
    } elseif (!is_single() && !is_page() && $post_type_name != 'post') {
        $title = '<br /><br /><br />';
        $br_content .= $home . $delimiter;
        $queried_object = $wp_query->get_queried_object();
        if(isset($queried_object->slug) && isset($queried_object->taxonomy) && isset($queried_object->name)) {
            $label = get_taxonomy($queried_object->taxonomy)->labels->singular_name;
            $br_content .= $before . $label . $delimiter . $queried_object->name . $after;
        }
        
        elseif ($post_type = get_post_type_object($post_type_name)) {
            $br_content .= $before . $post_type->labels->singular_name . $after;
        }
    } elseif (is_attachment()) {
        $title = '<h2>' . $before . get_the_title() . $after . $page_info . '</h2>';
        $br_content .= $home . $delimiter;
        $parent = get_post($post->post_parent);
        $cat = get_the_category($parent->ID);
        $cat = $cat[0];
        $br_content .= get_category_parents($cat, true, '' . $delimiter);
        $br_content .= $before . '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>' . $after;
    } elseif (is_page() && !$post->post_parent) {
        $title = '<h2>' . $before . get_the_title() . $after . $page_info . '</h2>';
        $br_content .= $home;
    } elseif (is_page() && $post->post_parent) {
        $title = '<h2>' . $before . get_the_title() . $after . '</h2>';
        $br_content .= $home . $delimiter;
        $parent_id = $post->post_parent;
        $breadcrumbs = array();

        while ($parent_id) {
            $page = get_page($parent_id);
            $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
            $parent_id = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);

        $count = count($breadcrumbs);
        foreach ($breadcrumbs as $k => $crumb) {
            $br_content .= $crumb;
            if ($k != $count - 1) {
                $br_content .= $delimiter;
            }
        }
    } elseif (is_search()) {
        $title = '<h2>' . $before . esc_html__('Search results for &ldquo;', 'digi-theme') . get_search_query() . '&rdquo;' . $after . '</h2>';
        $br_content .= $home;
    } elseif (is_tag()) {
        $title = '<h2>' . $before . esc_html__('Posts tagged &ldquo;', 'digi-theme') . single_tag_title('', false) . '&rdquo;' . $after . '</h2>';
        $br_content .= $home;
    } elseif (is_author()) {
        $author_id = get_the_author_meta('ID');
        $userdata = get_userdata($author_id);
        $title = '<h2>' . $before . esc_html__('Author:', 'digi-theme') . ' ' . $userdata->display_name . $after . '</h2>';
        $br_content .= $home;
    }
    
    /**
     * Blogs page > 2
     */
    elseif($paged > 1) {
        $title = '<h2>' . $before . esc_html__('Blog', 'digi-theme') . $after . $page_info . '</h2>';
        $br_content .= $home;
    }
}

/**
 * Blogs page > 2
 */
else {
    $title = '<h2>' . $before . esc_html__('Blog', 'digi-theme') . $after . $page_info . '</h2>';
    $br_content .= $home;
}
$lenth = strlen($br_content);
$lent_sep = strlen($delimiter);
$sub = substr($br_content, $lenth - $lent_sep, $lenth);
if ($sub == $delimiter) {
    $br_content = substr($br_content, 0, $lenth - $lent_sep);
}
$br_content .= $delimiter;
echo $title . $wrap_before . $br_content . $wrap_after;
