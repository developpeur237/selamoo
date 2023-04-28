<?php
/**
 * VC SETUP
 */
add_action('init', 'digi_vc_setup');
if (!function_exists('digi_vc_setup')) :

    function digi_vc_setup() {
        if (!class_exists('WPBakeryVisualComposerAbstract')){
            return;
        }

        // **********************************************************************// 
        // ! Row (add fullwidth, parallax option)
        // **********************************************************************//
        vc_add_param('vc_row', array(
            "type" => 'checkbox',
            "heading" => esc_html__("Fullwidth?", 'digi-theme'),
            "param_name" => "fullwidth",
            "value" => array(
                esc_html__('Yes, please', 'digi-theme') => '1'
            )
        ));

        vc_add_param('vc_row', array(
            "type" => "checkbox",
            "heading" => esc_html__("Parallax", 'digi-theme'),
            "param_name" => "parallax",
            "value" => array(
                esc_html__('Yes, please', 'digi-theme') => '1'
            )
        ));

        vc_add_param('vc_row', array(
            "type" => "textfield",
            "heading" => esc_html__("Parallax speed", 'digi-theme'),
            "param_name" => "parallax_speed",
            "value" => "0.6",
            "dependency" => array(
                "element" => 'parallax',
                "not_empty" => true,
            ),
            "description" => esc_html__('Enter parallax speed ratio (Note: Default value is 0.6, min value is 0)', 'digi-theme'),
        ));
        
        //Add param from tab element
        vc_add_param('vc_tta_tabs', array(
            "type" => "dropdown",
            "heading" => esc_html__("Tabs title display type", 'digi-theme'),
            "param_name" => "tabs_display_type",
            "value" => array(
                esc_html__('Slide', 'digi-theme') => '1',
                esc_html__('Classical', 'digi-theme') => '0'
            ),
            "std" => '1'
        ));

        //Add param from tab element
        vc_add_param('vc_tta_section', array(
            "type" => "dropdown",
            "heading" => esc_html__("Tabs title border", 'digi-theme'),
            "param_name" => "hr",
            "value" => array(
                esc_html__('Yes', 'digi-theme') => true,
                esc_html__('No', 'digi-theme') => false
            ),
            "std" => false,
        ));
        
        /**
         * Custom for columns
         */
        // Add param from columns element
        vc_add_param('vc_column', array(
            "type" => "dropdown",
            "heading" => esc_html__("Effect", 'digi-theme'),
            "param_name" => "nasa_effect",
            'value' => array(
                'none' => 'none',
                'bounce' => 'bounce',
                'flash' => 'flash',
                'pulse' => 'pulse',
                'rubberBand' => 'rubberBand',
                'shake' => 'shake',
                'swing' => 'swing',
                'tada' => 'tada',
                'wobble' => 'wobble',
                'bounceIn' => 'bounceIn',
                'fadeIn' => 'fadeIn',
                'fadeInDown' => 'fadeInDown',
                'fadeInDownBig' => 'fadeInDownBig',
                'fadeInLeft' => 'fadeInLeft',
                'fadeInLeftBig' => 'fadeInLeftBig',
                'fadeInRight' => 'fadeInRight',
                'fadeInRightBig' => 'fadeInRightBig',
                'fadeInUp' => 'fadeInUp',
                'fadeInUpBig' => 'fadeInUpBig',
                'flip' => 'flip',
                'flipInX' => 'flipInX',
                'flipInY' => 'flipInY',
                'lightSpeedIn' => 'lightSpeedIn',
                'rotateInrotateIn' => 'rotateIn',
                'rotateInDownLeft' => 'rotateInDownLeft',
                'rotateInDownRight' => 'rotateInDownRight',
                'rotateInUpLeft' => 'rotateInUpLeft',
                'rotateInUpRight' => 'rotateInUpRight',
                'slideInDown' => 'slideInDown',
                'slideInLeft' => 'slideInLeft',
                'slideInRight' => 'slideInRight',
                'rollIn' => 'rollIn'
            )
        ));
        
        vc_add_param('vc_column', array(
            "type" => "dropdown",
            "heading" => esc_html__("Width full side", 'digi-theme'),
            "param_name" => "width_side",
            'value' => array(
                esc_html__('None', 'digi-theme') => '',
                esc_html__('Full width to left', 'digi-theme') => 'left',
                esc_html__('Full width to right', 'digi-theme') => 'right'
            ),
            'std' => '',
            "description" => esc_html__('Only use for Visual Composer Template.', 'digi-theme'),
        ));

        vc_add_param('vc_column', array(
            "type" => "textfield",
            "heading" => esc_html__("Duration", 'digi-theme'),
            "param_name" => "nasa_duration",
            'value' => '1000'
        ));

        vc_add_param('vc_column', array(
            "type" => "textfield",
            "heading" => esc_html__("Delay", 'digi-theme'),
            "param_name" => "nasa_delay",
            'value' => '100'
        ));
    }

endif;

/**
 * Render Time sale Countdown
 */
if(!function_exists('digi_time_sale')) :
    function digi_time_sale($time_sale) {
        return $time_sale ? 
        '<span class="countdown" data-countdown="' . esc_attr(get_date_from_gmt(date('Y-m-d H:i:s', $time_sale), 'M j Y H:i:s O')) . '"></span>' : '';
    }
endif;

// **********************************************************************//
// ! Get logo
// **********************************************************************//
if (!function_exists('digi_logo')) :

    function digi_logo() {
        global $nasa_logo;
        
        if(!isset($nasa_logo) || !$nasa_logo) {
            global $nasa_opt, $wp_query;
            
            $logo_link = get_post_meta($wp_query->get_queried_object_id(), '_nasa_custom_logo', true);
            if ($logo_link == '') {
                $logo_link = isset($nasa_opt['site_logo']) ? $nasa_opt['site_logo'] : '';
            }
            
            $logo_retina = get_post_meta($wp_query->get_queried_object_id(), '_nasa_custom_logo_retina', true);
            if ($logo_retina == '') {
                $logo_retina = isset($nasa_opt['site_logo_retina']) ? $nasa_opt['site_logo_retina'] : '';
            }

            $site_title = esc_attr(get_bloginfo('name', 'display'));

            $content = '<div class="logo nasa-logo-img">';
            $content .= '<a href="' . esc_url(home_url('/')) . '" title="' . $site_title . ' - ' . esc_attr(get_bloginfo('description', 'display')) . '" rel="home">';
            $content .= $logo_link != '' ? '<img src="' . esc_attr($logo_link) . '" class="header_logo" alt="' . $site_title . '" data-src-retina="' . esc_attr($logo_retina) . '" />' : get_bloginfo('name', 'display');
            $content .= '</a>';
            $content .= '</div>';
            
            $GLOBALS['nasa_logo'] = $content;
            
            return $content;
        }
        
        return $nasa_logo;
    }

endif;

// **********************************************************************//
// ! Get header search
// **********************************************************************//
if (!function_exists('digi_search')) :

    function digi_search($search_type = 'icon') {
        global $wp_query, $nasa_opt;
        $style = get_post_meta($wp_query->get_queried_object_id(), '_nasa_search_style', true);
        if ($style == '') {
            $style = isset($nasa_opt['search-style']) ? $nasa_opt['search-style'] : '1';
        }
        
        $class_wrap = ' nasa_search_' . $search_type . ' nasa-search-style-' . ((int) $style);
        echo '<div class="nasa-search-space' . esc_attr($class_wrap) . '">';
            $class = '';
            if ($search_type == 'icon'):
                $class = ' hidden-tag nasa-over-hide';
                echo
                '<a class="search-icon desk-search" href="javascript:void(0);">' .
                    '<span class="circle"></span>' .
                    '<span class="handle"></span>' .
                '</a>';
            endif;

            echo '<div class="nasa-show-search-form' . $class . '">';
                get_search_form();
            echo '</div>';
        echo '</div>';
    }

endif;

// **********************************************************************// 
// ! Get main menu
// **********************************************************************// 
if (!function_exists('digi_get_main_menu')) :

    function digi_get_main_menu($main = true) {
        global $nasa_main_menu;
        
        $mega = class_exists('Nasa_Nav_Menu');
        $walker = $mega ? new Nasa_Nav_Menu() : new Walker_Nav_Menu();
        if(!$nasa_main_menu) {
            if (has_nav_menu('primary')) :
                $GLOBALS['nasa_main_menu'] = wp_nav_menu(array(
                    'echo' => false,
                    'theme_location' => 'primary',
                    'container' => false,
                    'items_wrap' => '%3$s',
                    'depth' => 3,
                    'walker' => $walker
                ));
            else:
                $allowed_html = array(
                    'li' => array(),
                    'b' => array()
                );
                
                $GLOBALS['nasa_main_menu'] = wp_kses(__('<li>Please Define menu in <b>Apperance > Menus</b></li>', 'digi-theme'), $allowed_html);
            endif;
        }
        
        $id_menu = $main ? ' id="site-navigation" ' : ' ';
        $class = $mega ? '' : ' nasa-wp-simple-nav-menu';
        echo '<div class="nav-wrapper inline-block main-menu-warpper">';
        echo '<ul' . $id_menu . 'class="header-nav' . $class . '">';
        echo $nasa_main_menu;
        echo '</ul>';
        echo '</div><!-- nav-wrapper -->';
    }

endif;

if (!function_exists('digi_get_menu')) :

    function digi_get_menu($menu_location, $class = "", $depth = 3) {
        if (has_nav_menu($menu_location)) :
            $mega = class_exists('Nasa_Nav_Menu');
            $walker = $mega ? new Nasa_Nav_Menu() : new Walker_Nav_Menu();
            $class .= $mega ? '' : ' nasa-wp-simple-nav-menu';
            echo '<ul class="' . esc_attr($class) . '">';
            wp_nav_menu(array(
                'theme_location' => $menu_location,
                'container' => false,
                'items_wrap' => '%3$s',
                'depth' => (int) $depth,
                'walker' => $walker
            ));
            echo '</ul>';
        endif;
    }

endif;
// **********************************************************************// 
// ! Get Vertical menu
// **********************************************************************// 
if (!function_exists('digi_get_vertical_menu')) :

    function digi_get_vertical_menu() {
        global $nasa_opt, $wp_query;

        $menu = ($menu_overr = get_post_meta($wp_query->get_queried_object_id(), '_nasa_vertical_menu_selected', true)) ? $menu_overr : (isset($nasa_opt['vertical_menu_selected']) ? $nasa_opt['vertical_menu_selected'] : false);

        if (!$menu) {
            $locations = get_theme_mod('nav_menu_locations');
            $menu = isset($locations['vetical-menu']) && $locations['vetical-menu'] ? $locations['vetical-menu'] : null;
        }

        if ($menu && $menu != '-1') {
            $title = ($title_overr = get_post_meta($wp_query->get_queried_object_id(), '_nasa_title_ver_menu', true)) ? $title_overr : (isset($nasa_opt['title_ver_menu']) ? $nasa_opt['title_ver_menu'] : false);

            $vertical_menu_allways_show = get_post_meta($wp_query->get_queried_object_id(), '_nasa_vertical_menu_allways_show', true);
            $nasa_class_menu_vertical = ($vertical_menu_allways_show) ? ' nasa-allways-show' : '';
            $nasa_class_menu_vertical_warp = ($vertical_menu_allways_show) ? ' nasa-allways-show-warp' : '';
            
            $mega = class_exists('Nasa_Nav_Menu');
            $walker = $mega ? new Nasa_Nav_Menu() : new Walker_Nav_Menu();
            $class = $mega ? '' : ' nasa-wp-simple-nav-menu';
            ?>
            <div class="vertical-menu nasa-vertical-header<?php echo esc_attr($nasa_class_menu_vertical_warp); ?>">
                <div class="title-inner">
                    <h5 class="section-title nasa-title-vertical-menu">
                        <span><?php echo $title ? esc_attr($title) : esc_html__('CATEGORIES', 'digi-theme'); ?></span>
                    </h5>
                </div>
                <div class="vertical-menu-container<?php echo esc_attr($nasa_class_menu_vertical); ?>">
                    <ul class="vertical-menu-wrapper<?php echo $class; ?>">
                        <?php
                        wp_nav_menu(array(
                            'menu' => $menu,
                            'container' => false,
                            'items_wrap' => '%3$s',
                            'depth' => 3,
                            'walker' => $walker
                        ));
                        ?>
                    </ul>
                </div>
            </div>
            <?php
        }
    }

endif;

if (!function_exists('digi_tpl2id')) :

    function digi_tpl2id($tpl) {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => $tpl
        ));

        if (empty($pages)) {
            return null;
        }

        foreach ($pages as $page) {
            return $page->ID;
        }
    }

endif;

if (!function_exists('digi_back_to_page')) :

    function digi_back_to_page() {
        echo '<a class="back-history" href="javascript: history.go(-1)">' . esc_html__('Return to Previous Page', 'digi-theme') . '</a>';
    }

endif;

// **********************************************************************// 
// ! Get breadcrumb
// **********************************************************************// 
add_action('nasa_get_breadcrumb', 'digi_get_breadcrumb');
if (!function_exists('digi_get_breadcrumb')) :

    function digi_get_breadcrumb($ajax = false) {
        if (!NASA_WOO_ACTIVED) {
            return;
        }

        global $post, $nasa_opt, $wp_query;
        $enable = (isset($nasa_opt['breadcrumb_show']) && !$nasa_opt['breadcrumb_show']) ? false : true;
        $override = false;
        
        // Theme option
        $has_bg = (isset($nasa_opt['breadcrumb_type']) && $nasa_opt['breadcrumb_type'] == 'has-background') ? true : false;

        $bg = (isset($nasa_opt['breadcrumb_bg']) && trim($nasa_opt['breadcrumb_bg']) != '') ?
            $nasa_opt['breadcrumb_bg'] : false;

        $bg_cl = (isset($nasa_opt['breadcrumb_bg_color']) && $nasa_opt['breadcrumb_bg_color']) ?
            $nasa_opt['breadcrumb_bg_color'] : false;
        
        $bg_lax = (isset($nasa_opt['breadcrumb_bg_lax']) && $nasa_opt['breadcrumb_bg_lax'] == 1) ? true : false;

        $h_bg = (isset($nasa_opt['breadcrumb_height']) && (int) $nasa_opt['breadcrumb_height']) ?
            (int) $nasa_opt['breadcrumb_height'] : false;

        $txt_color = (isset($nasa_opt['breadcrumb_color']) && $nasa_opt['breadcrumb_color']) ?
            $nasa_opt['breadcrumb_color'] : false;

        /*
         * Category breadcrumb BG
         */
        if($enable && $tax_cat = is_tax('product_cat')) {
            $query_obj = get_queried_object();
            $term_id = isset($query_obj->term_id) ? $query_obj->term_id : false;
            if($term_id) {
                $bgImgId = get_term_meta($term_id, 'cat_breadcrumb_bg', true);
                if ($bgImgId) {
                    $bg = wp_get_attachment_image_url($bgImgId, 'full');
                    $has_bg = true;
                }
                
                $text_color_cat = get_term_meta($term_id, 'cat_breadcrumb_text_color', true);
                $txt_color = $text_color_cat != '' ? $text_color_cat : $txt_color;
            }
        }
        
        else {
            if (isset($post->ID) && $post->post_type == 'page') {
                $queryObj = $wp_query->get_queried_object_id();
                $show_breadcrumb = get_post_meta($queryObj, '_nasa_show_breadcrumb', true);
                $enable = ($show_breadcrumb != 'on') ? false : true;
                $override = true;
            }

            if ($enable === false) {
                return;
            }

            // Override
            if ($override) {
                $type_bg = get_post_meta($queryObj, '_nasa_type_breadcrumb', true);
                $bg_override = get_post_meta($queryObj, '_nasa_bg_breadcrumb', true);
                $bg_cl_override = get_post_meta($queryObj, '_nasa_bg_color_breadcrumb', true);
                $h_override = get_post_meta($queryObj, '_nasa_height_breadcrumb', true);
                $color_override = get_post_meta($queryObj, '_nasa_color_breadcrumb', true);

                if ($type_bg == '1') {
                    $bg = $bg_override ? $bg_override : $bg;
                    $bg_cl = $bg_cl_override ? $bg_cl_override : $bg_cl;
                    $h_bg = (int) $h_override ? (int) $h_override : $h_bg;
                    $txt_color = $color_override ? $color_override : $txt_color;
                }
            }
        }

        // set style by option breadcrumb
        $style_custom = '';
        if ($has_bg) {
            $style_custom .= $bg ? 'background:url(\'' . esc_url($bg) . '\') center center repeat-y;' : '';
            $style_custom .= $bg_cl ? 'background-color:' . $bg_cl . ';' : '';
            $style_custom .= $h_bg ? 'height:' . $h_bg . 'px' : 'height:auto';
            $style_custom .= $txt_color ? ';color:' . $txt_color : '';
        }
        
        $defaults = array(
            'delimiter' => '<span class="fa fa-angle-right"></span>',
            'wrap_before' => '<h3 class="breadcrumb">',
            'wrap_after' => '</h3>',
            'before' => '',
            'after' => '',
            'home' => esc_html__('Home', 'digi-theme'),
            'ajax' => $ajax
        );
        
        $parallax = $has_bg && $bg && $bg_lax ? true : false;

        $args = wp_parse_args($defaults);
        ?>
        <div id="nasa-breadcrumb-site" class="bread nasa-breadcrumb<?php echo $has_bg ? ' nasa-breadcrumb-has-bg' : ''; echo $parallax ? ' nasa-parallax': ''; ?>"<?php echo ($style_custom) ? ' style="' . esc_attr($style_custom) . '"' : ''; ?><?php echo $parallax ? ' data-stellar-background-ratio="0.6"' : ''; ?>>
            <div class="row">
                <div class="large-12 columns">
                    <div class="breadcrumb-row">
                        <?php wc_get_template('global/breadcrumb.php', $args); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

endif;

// **********************************************************************// 
// ! Add body class
// **********************************************************************//
add_filter('body_class', 'digi_body_classes');
if (!function_exists('digi_body_classes')) :

    function digi_body_classes($classes) {
        global $nasa_opt;

        $classes[] = 'antialiased';
        if (is_multi_author()) {
            $classes[] = 'group-blog';
        }

        if (isset($nasa_opt['site_layout']) && $nasa_opt['site_layout'] == 'boxed') {
            $classes[] = 'boxed';
        }

        if (isset($nasa_opt['promo_popup']) && $nasa_opt['promo_popup'] == 1) {
            $classes[] = 'open-popup';
        }

        if (NASA_WOO_ACTIVED && function_exists('is_product')) {
            if (is_product() && isset($nasa_opt['product-zoom']) && $nasa_opt['product-zoom']) {
                $classes[] = 'product-zoom';
            }
        }

        return $classes;
    }

endif;

// **********************************************************************// 
// ! Add hr to the widget title
// **********************************************************************//
// add_filter('widget_title', 'digi_widget_title', 10, 3);
if (!function_exists('digi_widget_title')) :

    function digi_widget_title($title) {
        return !empty($title) ? $title . '<span class="nasa-hr small primary-color"></span>' : '';
    }

endif;

// **********************************************************************// 
// ! Comments
// **********************************************************************//  
if (!function_exists('digi_comment')) :

    function digi_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' : ?>
                <li class="post pingback">
                    <p><?php esc_html_e('Pingback:', 'digi-theme'); ?> <?php comment_author_link(); ?><?php edit_comment_link(esc_html__('Edit', 'digi-theme'), '<span class="edit-link">', '<span>'); ?></p>
                <?php
                break;
            default : ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="comment-inner">
                        <div class="row collapse">
                            <div class="large-2 columns">
                                <div class="comment-author">
                                    <?php echo get_avatar($comment, 80); ?>
                                </div>
                            </div>
                            <div class="large-10 columns">
                                <?php printf('<cite class="fn">%s</cite>', get_comment_author_link()); ?>
                                <div class="comment-meta commentmetadata right">
                                    <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                                        <time datetime="<?php comment_time('c'); ?>">
                                            <?php printf(_x('%1$s at %2$s', '1: date, 2: time', 'digi-theme'), get_comment_date(), get_comment_time()); ?>
                                        </time>
                                    </a>
                                    <?php edit_comment_link(esc_html__('Edit', 'digi-theme'), '<span class="edit-link">', '<span>'); ?>
                                </div>
                                <div class="reply">
                                    <?php
                                    comment_reply_link(array_merge($args, array(
                                        'depth' => $depth,
                                        'max_depth' => $args['max_depth'],
                                    )));
                                    ?>
                                </div>
                                <?php if ($comment->comment_approved == '0') : ?>
                                    <em><?php esc_html_e('Your comment is awaiting moderation.', 'digi-theme'); ?></em>
                                    <br />
                                <?php endif; ?>

                                <div class="comment-content"><?php comment_text(); ?></div>
                            </div>
                        </div>
                    </article>
                <?php
                break;
        endswitch;
    }

endif;

// **********************************************************************// 
// ! Post meta top
// **********************************************************************//  
if (!function_exists('digi_posted_on')) :

    function digi_posted_on() {
        $allowed_html = array(
            'span' => array('class' => array()),
            'strong' => array(),
            'a' => array('class' => array(), 'href' => array(), 'title' => array(), 'rel' => array()),
            'time' => array('class' => array(), 'datetime' => array())
        );
        $day = get_the_date('d');
        $month = get_the_date('m');
        $year = get_the_date('Y');
        printf(wp_kses(__('<span class="meta-author">By <strong><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></strong>.</span> Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>', 'digi-theme'), $allowed_html), esc_url(get_day_link($year, $month, $day)), esc_attr(get_the_time()), esc_attr(get_the_date('c')), esc_html(get_the_date()), esc_url(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(esc_html__('View all posts by %s', 'digi-theme'), get_the_author())), get_the_author()
        );
    }

endif;


// **********************************************************************// 
// ! Promo Popup
// **********************************************************************// 
add_action('after_page_wrapper', 'digi_promo_popup');
if (!function_exists('digi_promo_popup')) :

    function digi_promo_popup() {
        global $nasa_opt;
        ?>
        <style type="text/css">
            #nasa-popup{
                width: <?php echo isset($nasa_opt['pp_width']) ? (int) $nasa_opt['pp_width'] : 412; ?>px;
                background-color: <?php echo isset($nasa_opt['pp_background_color']) ? $nasa_opt['pp_background_color'] : '' ?>;
                <?php if(isset($nasa_opt['pp_background_image']) && $nasa_opt['pp_background_image'] != '') : ?>
                    background-image: url('<?php echo $nasa_opt['pp_background_image']; ?>');
                <?php endif; ?>
            }
            #nasa-popup, #nasa-popup .nasa-popup-wrap {
                height: <?php echo isset($nasa_opt['pp_height']) ? (int) $nasa_opt['pp_height'] : 412; ?>px;
            }
        </style>
        <div id="nasa-popup" class="white-popup-block mfp-hide mfp-with-anim zoom-anim-dialog">
            <div class="nasa-popup-wrap nasa-relative">
                <div class="nasa-no-fix-size-retina">
                    <?php echo digi_logo(); ?>
                </div>

                <?php echo isset($nasa_opt['pp_content']) ? do_shortcode($nasa_opt['pp_content']) : ''; ?>

                <hr class="nasa-popup-hr" />

                <p class="checkbox-label align-center">
                    <input type="checkbox" value="do-not-show" name="showagain" id="showagain" class="showagain" />
                    <label for="showagain"><?php esc_html_e("Don't show this popup again", 'digi-theme'); ?></label>
                </p>

                <?php echo (isset($nasa_opt['pp_contact_form']) && (int) $nasa_opt['pp_contact_form'] && shortcode_exists('contact-form-7')) ? do_shortcode('[contact-form-7 id="' . ((int) $nasa_opt['pp_contact_form']) . '"]') : ''; ?>
            </div>
        </div>
        <?php
    }

endif;

add_filter('wp_nav_menu_objects', 'digi_add_menu_parent_class');
if (!function_exists('digi_add_menu_parent_class')) :
    function digi_add_menu_parent_class($items) {
        $parents = array();
        foreach ($items as $item) {
            if ($item->menu_item_parent && $item->menu_item_parent > 0) {
                $parents[] = $item->menu_item_parent;
            }
        }

        foreach ($items as $item) {
            if (in_array($item->ID, $parents)) {
                $item->classes[] = 'menu-parent-item';
            }
        }

        return $items;
    }
endif;

// add_action('woocommerce_single_product_summary', 'digi_ProductShowReviews', 15);
// add_action('woocommerce_single_review', 'digi_ProductShowReviews', 10);
if (!function_exists('digi_ProductShowReviews')) :
    function digi_ProductShowReviews() {
        if (comments_open()) {
            global $wpdb, $post;

            $count = $wpdb->get_var($wpdb->prepare("
                SELECT COUNT(meta_value) FROM $wpdb->commentmeta
                LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
                WHERE meta_key = %s
                AND comment_post_ID = %s
                AND comment_approved = %s
                AND meta_value > %s", 'rating', $post->ID, '1', '0'
            ));

            $rating = $wpdb->get_var($wpdb->prepare("
                SELECT SUM(meta_value) FROM $wpdb->commentmeta
                LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
                WHERE meta_key = %s
                AND comment_post_ID = %s
                AND comment_approved = %s", 'rating', $post->ID, '1'
            ));

            if ($count > 0) {
                $average = number_format($rating / $count, 2);

                echo '<a href="#tab-reviews" class="scroll-to-reviews"><div class="star-rating tip-top" data-tip="' . $count . ' review(s)"><span style="width:' . ($average * 16) . 'px"><span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="rating"><span itemprop="ratingValue">' . $average . '</span><span itemprop="reviewCount" class="hidden">' . $count . '</span></span> ' . esc_html__('out of 5', 'digi-theme') . '</span></div></a>';
            }
        }
    }
endif;

if (!function_exists('digi_get_adjacent_post_product')) :
    function digi_get_adjacent_post_product($in_same_cat = false, $excluded_categories = '', $previous = true) {
        global $wpdb;

        if (!$post = get_post()) {
            return null;
        }

        $current_post_date = $post->post_date;
        $join = '';
        $posts_in_ex_cats_sql = '';
        if ($in_same_cat || !empty($excluded_categories)) {
            $join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";

            if ($in_same_cat) {
                if (!is_object_in_taxonomy($post->post_type, 'product_cat')) {
                    return '';
                }
                $cat_array = wp_get_object_terms($post->ID, 'product_cat', array('fields' => 'ids'));
                if (!$cat_array || is_wp_error($cat_array)) {
                    return '';
                }
                $join .= " AND tt.taxonomy='product_cat' AND tt.term_id IN (" . implode(',', $cat_array) . ")";
            }

            $posts_in_ex_cats_sql = "AND tt.taxonomy = 'product_cat'";
            if (!empty($excluded_categories)) {
                if (!is_array($excluded_categories)) {
                    if (strpos($excluded_categories, ' and ') !== false) {
                        _deprecated_argument(__FUNCTION__, '3.3', esc_html_e('Use commas instead of and to separate excluded categories.', 'digi-theme'));
                        $excluded_categories = explode(' and ', $excluded_categories);
                    } else {
                        $excluded_categories = explode(',', $excluded_categories);
                    }
                }

                $excluded_categories = array_map('intval', $excluded_categories);

                if (!empty($cat_array)) {
                    $excluded_categories = array_diff($excluded_categories, $cat_array);
                    $posts_in_ex_cats_sql = '';
                }

                if (!empty($excluded_categories)) {
                    $posts_in_ex_cats_sql = " AND tt.taxonomy = 'product_cat' AND tt.term_id NOT IN (" . implode($excluded_categories, ',') . ')';
                }
            }
        }

        $adjacent = $previous ? 'previous' : 'next';
        $op = $previous ? '<' : '>';
        $order = $previous ? 'DESC' : 'ASC';

        $join = apply_filters("get_{$adjacent}_post_join", $join, $in_same_cat, $excluded_categories);
        $where = apply_filters("get_{$adjacent}_post_where", $wpdb->prepare("WHERE p.post_date $op %s AND p.post_type = %s AND p.post_status = 'publish' $posts_in_ex_cats_sql", $current_post_date, $post->post_type), $in_same_cat, $excluded_categories);
        $sort = apply_filters("get_{$adjacent}_post_sort", "ORDER BY p.post_date $order LIMIT 1");

        $query = "SELECT p.id FROM $wpdb->posts AS p $join $where $sort";
        $query_key = 'adjacent_post_' . md5($query);
        $result = wp_cache_get($query_key, 'counts');
        if (false !== $result) {
            if ($result) {
                $result = get_post($result);
            }
            return $result;
        }

        $result = $wpdb->get_var($wpdb->prepare($query));
        if (null === $result) {
            $result = '';
        }

        wp_cache_set($query_key, $result, 'counts');

        if ($result) {
            $result = get_post($result);
        }

        return $result;
    }
endif;

// **********************************************************************// 
// ! Blog - Add "Read more" links
// **********************************************************************//
add_action('the_content_more_link', 'digi_add_morelink_class', 10, 2);
if (!function_exists('digi_add_morelink_class')) :
    function digi_add_morelink_class($link, $text) {
        return str_replace('more-link', 'more-link button small', $link);
    }
endif;

// **********************************************************************// 
// ! Language Flags
// **********************************************************************//
// add_action('digi_language_switcher', 'digi_language_flages', 1);
if (!function_exists('digi_language_flages')) :

    function digi_language_flages() {
        global $nasa_opt;
        
        if(!isset($nasa_opt['switch_lang']) || $nasa_opt['switch_lang'] != 1) {
            return '';
        }
        
        $language_output = '<select name="nasa_switch_languages" class="nasa-select-language" data-active="0">';
        $options = '';
        if (function_exists('icl_get_languages')) {
            $current = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en';
            
            $language_output = '<select name="nasa_switch_languages" class="nasa-select-language" data-active="1">';
            $languages = icl_get_languages('skip_missing=0&orderby=code');
            if (!empty($languages)) {
                foreach ($languages as $l) {
                    $selected = $current == $l['language_code'] ? ' selected' : '';
                    $options .= '<option data-code="' . $l['language_code'] . '" value="' . $l['url'] . '"' . $selected . '>' . $l['native_name'] . '</option>';
                }
            }
        } else {
            $options .=
                '<option value="en">' . esc_html__('English', 'digi-theme') . '</option>' .
                '<option value="gr">' . esc_html__('German', 'digi-theme') . '</option>' .
                '<option value="fr">' . esc_html__('French', 'digi-theme') . '</option>';
        }
        
        $language_output .= $options;
        $language_output .= '</select>';

        echo '<ul class="header-switch-languages"><li>' . $language_output . '</li></ul>';
    }

endif;

/**
 * ignore post type Product
 */
if (!NASA_CORE_IN_ADMIN) {
    add_action('pre_get_posts', 'digi_pre_get_posts_action');
    if (!function_exists('digi_pre_get_posts_action')) :
        function digi_pre_get_posts_action($query) {
            $action = isset($_GET['action']) ? $_GET['action'] : '';
            if ($action == 'woocommerce_json_search_products') {
                return;
            }
            if (defined('DOING_AJAX') && DOING_AJAX && !empty($query->query_vars['s'])) {
                if (isset($query->query_vars['post_type'])) {
                    $query->query_vars['post_type'] = array($query->query_vars['post_type'], 'post', 'page');
                }
                if (isset($query->query_vars['meta_query'])) {
                    $query->query_vars['meta_query'] = new WP_Meta_Query(array('relation' => 'OR', $query->query_vars['meta_query']));
                }
            }
        }
    endif;
}

/* Mobile header */
if (!function_exists('digi_mobile_header')) :

    function digi_mobile_header() {
        ?>
        <div class="row">
            <div class="large-12 columns">
                <table>
                    <tr>
                        <td class="nasa-td-25">
                            <div class="mini-icon-mobile">
                                <a href="javascript:void(0);" class="nasa-mobile-menu_toggle mobile_toggle"><span class="icon-menu"></span></a>
                                <a class="icon pe-7s-search mobile-search" href="javascript:void(0);"></a>
                            </div>
                        </td>

                        <td>
                            <div class="logo-wrapper">
                                <?php echo digi_logo(); ?>
                            </div>
                        </td>

                        <td class="nasa-td-mobile-icons">
                            <?php echo digi_header_icons(); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    }
endif;

/**
 * Group header icons
 */
if (!function_exists('digi_header_icons')) :
    function digi_header_icons($compare = true, $wishlist = true, $cart = true) {
        $icons = '';
        
        if($compare) {
            $nasa_icon_compare = digi_icon_compare();
            $icons .= $nasa_icon_compare != '' ? '<li class="first nasa-icon-compare">' . $nasa_icon_compare . '</li>' : '';
        }
        
        if($wishlist) {
            $nasa_icon_wishlist = digi_icon_wishlist();
            $icons .= $nasa_icon_wishlist != '' ? '<li class="nasa-icon-wishlist">' . $nasa_icon_wishlist . '</li>' : '';
        }
        
        if($cart) {
            $show = defined('NASA_PLG_CACHE_ACTIVE') && NASA_PLG_CACHE_ACTIVE ? false : true;
            $nasa_mini_cart = digi_mini_cart($show);
            $icons .= $nasa_mini_cart != '' ? '<li class="nasa-icon-mini-cart">' . $nasa_mini_cart . '</li>' : '';
        }
        
        return ($icons != '') ? '<ul class="header-icons">' . $icons . '</ul>' : '';
    }
endif;

/* cut string limit */
if (!function_exists('digi_limit_words')) :

    function digi_limit_words($string, $word_limit) {
        $words = explode(' ', $string, ($word_limit + 1));
        if (count($words) <= $word_limit) {
            return $string;
        }
        array_pop($words);
        return implode(' ', $words) . ' ...';
    }

endif;

// **********************************************************************// 
// ! Blog post navigation
// **********************************************************************//  
if (!function_exists('digi_content_nav')) :

    function digi_content_nav($nav_id) {
        global $wp_query, $post;
        $allowed_html = array(
            'span' => array('class' => array())
        );
        
        $is_single = is_single();

        if ($is_single) {
            $previous = (is_attachment()) ? get_post($post->post_parent) : get_adjacent_post(false, '', true);
            $next = get_adjacent_post(false, '', false);

            if (!$next && !$previous) {
                return;
            }
        }

        if ($wp_query->max_num_pages < 2 && (is_home() || is_archive() || is_search())) {
            return;
        }

        $nav_class = $is_single ? 'navigation-post' : 'navigation-paging';
        ?>
        <nav role="navigation" id="<?php echo esc_attr($nav_id); ?>" class="<?php echo esc_attr($nav_class); ?>">
            <?php
            if ($is_single) {
                previous_post_link('<div class="nav-previous left">%link</div>', '<span class="fa fa-caret-left">' . _x('', 'Previous post link', 'digi-theme') . '</span> %title');
                next_post_link('<div class="nav-next right">%link</div>', '%title <span class="fa fa-caret-right">' . _x('', 'Next post link', 'digi-theme') . '</span>');
            } elseif ($wp_query->max_num_pages > 1 && (is_home() || is_archive() || is_search())) {
                // navigation links for home, archive, and search pages
                if (get_next_posts_link()) {
                    ?>
                    <div class="nav-previous"><?php next_posts_link(wp_kses(__('Next <span class="fa fa-caret-right"></span>', 'digi-theme'), $allowed_html)); ?></div>
                    <?php
                }
                if (get_previous_posts_link()) {
                    ?>
                    <div class="nav-next"><?php previous_posts_link(wp_kses(__('<span class="fa fa-caret-left"></span> Previous', 'digi-theme'), $allowed_html)); ?></div>
                    <?php
                }
            }
            ?>
        </nav>
        <?php
    }

endif;

//Add shortcode Top bar Promotion news
add_action('nasa_promotion_recent_post', 'digi_promotion_recent_post');
if (!function_exists('digi_promotion_recent_post')):
    function digi_promotion_recent_post() {
        global $nasa_opt;

        if (isset($nasa_opt['enable_post_top']) && !$nasa_opt['enable_post_top']) {
            return '';
        }

        $content = '';
        $posts = null;

        if (!isset($nasa_opt['type_display']) || $nasa_opt['type_display'] == 'custom') {
            $content = isset($nasa_opt['content_custom']) ? $nasa_opt['content_custom'] : '';
        } elseif (isset($nasa_opt['type_display']) && $nasa_opt['type_display'] == 'list-posts') {
            if (!isset($nasa_opt['category_post']) || !$nasa_opt['category_post']) {
                $nasa_opt['category_post'] = null;
            }

            if (!isset($nasa_opt['number_post']) || !$nasa_opt['number_post']) {
                $nasa_opt['number_post'] = 4;
            }

            $args = array(
                'post_status' => 'publish',
                'post_type' => 'post',
                'orderby' => 'date',
                'order' => 'DESC',
                'category' => ((int) $nasa_opt['category_post'] != 0) ? (int) $nasa_opt['category_post'] : null,
                'posts_per_page' => $nasa_opt['number_post']
            );

            $posts = get_posts($args);
        }

        $file = DIGI_CHILD_PATH . '/includes/nasa-blogs-carousel.php';
        include is_file($file) ? $file : DIGI_THEME_PATH . '/includes/nasa-blogs-carousel.php';
    }
endif;

if (!function_exists('digi_get_block')):

    function digi_get_block() {
        global $nasa_opt;

        return (isset($nasa_opt['header-block']) && (int) $nasa_opt['header-block'] && ($block = get_post_field('post_content', (int) $nasa_opt['header-block']))) ? do_shortcode($block) : '';
    }

endif;

/**
 * Before load effect site
 */
add_action('nasa_theme_before_load', 'digi_theme_before_load');
if (!function_exists('digi_theme_before_load')):

    function digi_theme_before_load() {
        global $nasa_opt;

        if (!isset($nasa_opt['effect_before_load']) || $nasa_opt['effect_before_load'] == 1) {
            echo 
            '<div id="nasa-before-load">' .
                '<div class="nasa-relative nasa-center">' .
                    '<div class="nasa-loader">' .
                        '<div class="nasa-line"></div>' .
                        '<div class="nasa-line"></div>' .
                        '<div class="nasa-line"></div>' .
                        '<div class="nasa-line"></div>' .
                    '</div>' .
                '</div>' .
            '</div>';
        }
    }

endif;
