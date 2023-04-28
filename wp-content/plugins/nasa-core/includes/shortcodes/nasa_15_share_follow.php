<?php

add_shortcode('nasa_share', 'nasa_sc_share');
add_shortcode("nasa_follow", "nasa_sc_follow");

function nasa_sc_share($atts, $content = null) {
    extract(shortcode_atts(array(
        'title' => '',
        'size' => '',
        'el_class' => ''
    ), $atts));
    
    global $post, $nasa_opt;
    
    if (isset($post->ID)) {
        $permalink = get_permalink($post->ID);
        $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
        $featured_image_2 = isset($featured_image['0']) ? $featured_image['0'] : (isset($nasa_opt['site_logo']) ? $nasa_opt['site_logo'] : '#');
        $post_title = rawurlencode(get_the_title($post->ID));
    } else {
        global $wp;
        $permalink = home_url($wp->request);
        $featured_image_2 = isset($nasa_opt['site_logo']) ? $nasa_opt['site_logo'] : '#';
        $post_title = get_bloginfo('name', 'display');
    }

    ob_start();
    echo $title ? '<div class="nasa-share-title"><span>' . $title . '</span></div>' : '';
    ?>
    <ul class="social-icons nasa-share<?php echo $size != '' ? ' ' . esc_attr($size) : ''; ?><?php echo $el_class != '' ? ' ' . esc_attr($el_class) : ''; ?>">
        <?php if (isset($nasa_opt['social_icons']['twitter']) && $nasa_opt['social_icons']['twitter']) { ?>
            <li>
                <a href="//twitter.com/share?url=<?php echo esc_url($permalink); ?>" target="_blank" class="icon" title="<?php esc_html_e('Share on Twitter', 'nasa-core'); ?>" title="<?php esc_html_e('Share on Twitter', 'nasa-core'); ?>" rel="nofollow">
                    <i class="fa fa-twitter"></i>
                </a>
            </li>
        <?php } ?>
        
        <?php if (isset($nasa_opt['social_icons']['facebook']) && $nasa_opt['social_icons']['facebook']) { ?>
            <li>
                <a href="//www.facebook.com/sharer.php?u=<?php echo esc_url($permalink); ?>" target="_blank" class="icon" title="<?php esc_html_e('Share on Facebook', 'nasa-core'); ?>" title="<?php esc_html_e('Share on Facebook', 'nasa-core'); ?>" rel="nofollow">
                    <i class="fa fa-facebook"></i>
                </a>
            </li>
        <?php } ?>
        
        <?php if (isset($nasa_opt['social_icons']['email']) && $nasa_opt['social_icons']['email']) { ?>
            <li>
                <a href="mailto:enter-your-mail@domain-here.com?subject=<?php echo esc_attr($post_title); ?>&amp;body=Check%20this%20out:%20<?php echo esc_url($permalink); ?>" target="_blank" class="icon" title="<?php esc_html_e('Email to your friends', 'nasa-core'); ?>" title="<?php esc_html_e('Email to your friends', 'nasa-core'); ?>" rel="nofollow">
                    <i class="fa fa-envelope-o"></i>
                </a>
            </li>
        <?php } ?>
        
        <?php if (isset($nasa_opt['social_icons']['pinterest']) && $nasa_opt['social_icons']['pinterest']) { ?>
            <li>
                <a href="//pinterest.com/pin/create/button/?url=<?php echo esc_url($permalink); ?>&amp;media=<?php echo esc_attr($featured_image_2); ?>&amp;description=<?php echo esc_attr($post_title); ?>" target="_blank" class="icon" title="<?php esc_html_e('Pin on Pinterest', 'nasa-core'); ?>" title="<?php esc_html_e('Pin on Pinterest', 'nasa-core'); ?>" rel="nofollow">
                    <i class="fa fa-pinterest"></i>
                </a>
            </li>
        <?php } ?>
    </ul>

    <?php
    $content = ob_get_clean();
    
    return $content;
}

function nasa_sc_follow($atts, $content = null) {
    extract(shortcode_atts(array(
        'title' => '',
        'twitter' => '',
        'facebook' => '',
        'pinterest' => '',
        'email' => '',
        'instagram' => '',
        'rss' => '',
        'linkedin' => '',
        'youtube' => '',
        'flickr' => '',
        'telegram' => '',
        'whatsapp' => '',
        'el_class' => ''
    ), $atts));
    
    global $nasa_opt;
    $facebook = $facebook ? $facebook : (isset($nasa_opt['facebook_url_follow']) ? $nasa_opt['facebook_url_follow'] : '');
    $twitter = $twitter ? $twitter : (isset($nasa_opt['twitter_url_follow']) ? $nasa_opt['twitter_url_follow'] : '');
    $email = $email ? $email : (isset($nasa_opt['email_url_follow']) ? $nasa_opt['email_url_follow'] : '');
    $pinterest = $pinterest ? $pinterest : (isset($nasa_opt['pinterest_url_follow']) ? $nasa_opt['pinterest_url_follow'] : '');
    $instagram = $instagram ? $instagram : (isset($nasa_opt['instagram_url_follow']) ? $nasa_opt['instagram_url_follow'] : '');
    $rss = $rss ? $rss : (isset($nasa_opt['rss_url_follow']) ? $nasa_opt['rss_url_follow'] : '');
    $linkedin = $linkedin ? $linkedin : (isset($nasa_opt['linkedin_url_follow']) ? $nasa_opt['linkedin_url_follow'] : '');
    $youtube = $youtube ? $youtube : (isset($nasa_opt['youtube_url_follow']) ? $nasa_opt['youtube_url_follow'] : '');
    $flickr = $flickr ? $flickr : (isset($nasa_opt['flickr_url_follow']) ? $nasa_opt['flickr_url_follow'] : '');
    $telegram = $telegram ? $telegram : (isset($nasa_opt['telegram_url_follow']) ? $nasa_opt['telegram_url_follow'] : '');
    $whatsapp = $whatsapp ? $whatsapp : (isset($nasa_opt['whatsapp_url_follow']) ? $nasa_opt['whatsapp_url_follow'] : '');
    ob_start();
    ?>

    <div class="social-icons nasa-follow<?php echo $el_class ? ' ' . esc_attr($el_class) : ''; ?>">
        <?php if ($title) { ?>
            <div class="nasa-follow-title"><?php echo esc_attr($title); ?></div>
        <?php } ?>
        <div class="follow-icon">
            <?php if ($facebook) { ?>
                <a href="<?php echo esc_url($facebook); ?>" target="_blank" class="icon icon_facebook" title="<?php esc_html_e('Follow us on Facebook', 'nasa-core') ?>" rel="nofollow"><i class="fa fa-facebook"></i></a>
            <?php } ?>
            <?php if ($twitter) { ?>
                <a href="<?php echo esc_url($twitter); ?>" target="_blank" class="icon icon_twitter" title="<?php esc_html_e('Follow us on Twitter', 'nasa-core') ?>" rel="nofollow"><i class="fa fa-twitter"></i></a>
            <?php } ?>
            <?php if ($email) { ?>
                <a href="mailto:<?php echo $email; ?>" target="_blank" class="icon icon_email" title="<?php esc_html_e('Send us an email', 'nasa-core') ?>" rel="nofollow"><i class="fa fa-envelope-o"></i></a>
            <?php } ?>
            <?php if ($pinterest) { ?>
                <a href="<?php echo esc_url($pinterest); ?>" target="_blank" class="icon icon_pintrest" title="<?php esc_html_e('Follow us on Pinterest', 'nasa-core') ?>" rel="nofollow"><i class="fa fa-pinterest"></i></a>
            <?php } ?>
            <?php if ($instagram) { ?>
                <a href="<?php echo esc_url($instagram); ?>" target="_blank" class="icon icon_instagram" title="<?php esc_html_e('Follow us on Instagram', 'nasa-core') ?>" rel="nofollow"><i class="fa fa-instagram"></i></a>
            <?php } ?>
            <?php if ($rss) { ?>
                <a href="<?php echo esc_url($rss); ?>" target="_blank" class="icon icon_rss" title="<?php esc_html_e('Subscribe to RSS', 'nasa-core') ?>" rel="nofollow"><i class="fa fa-rss"></i></a>
            <?php } ?>
            <?php if ($linkedin) { ?>
                <a href="<?php echo esc_url($linkedin); ?>" target="_blank" class="icon icon_linkedin" title="<?php esc_html_e('LinkedIn', 'nasa-core') ?>" rel="nofollow"><i class="fa fa-linkedin-square"></i></a>
            <?php } ?>
            <?php if ($youtube) { ?>
                <a href="<?php echo esc_url($youtube); ?>" target="_blank" class="icon icon_youtube" title="<?php esc_html_e('YouTube', 'nasa-core') ?>" rel="nofollow"><i class="fa fa-youtube-play"></i></a>
            <?php } ?>
            <?php if ($flickr) { ?>
                <a href="<?php echo esc_url($flickr); ?>" target="_blank" class="icon icon_flickr" title="<?php esc_html_e('Flickr', 'nasa-core') ?>" rel="nofollow"><i class="fa fa-flickr"></i></a>
            <?php } ?>
            <?php if ($telegram) { ?>
                <a href="<?php echo esc_url($telegram); ?>" target="_blank" class="icon icon_telegram" title="<?php esc_html_e('Telegram', 'nasa-core') ?>" rel="nofollow"><i class="fa fa-telegram"></i></a>
            <?php } ?>
            <?php if ($whatsapp) { ?>
                <a href="<?php echo esc_url($whatsapp); ?>" target="_blank" class="icon icon_whatsapp" title="<?php esc_html_e('Whatsapp', 'nasa-core') ?>" rel="nofollow"><i class="fa fa-whatsapp"></i></a>
            <?php } ?>
        </div>
    </div>

    <?php
    $content = ob_get_clean();
    
    return $content;
}

// **********************************************************************// 
// ! Register New Element: Share
// **********************************************************************//
add_action('init', 'nasa_register_share_follow');
function nasa_register_share_follow(){
    $share_params = array(
        "name" => esc_html__("Share", 'nasa-core'),
        "base" => "nasa_share",
        'icon' => 'icon-wpb-nasatheme',
        'description' => esc_html__("Display share icon social.", 'nasa-core'),
        "content_element" => true,
        "category" => 'Nasa Core',
        "show_settings_on_create" => false,
        "params" => array(
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Size', 'nasa-core'),
                "param_name" => 'size',
                "value" => array(
                    esc_html__('Normal', 'nasa-core') => '',
                    esc_html__('Large', 'nasa-core') => 'large'
                )
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Extra class name", 'nasa-core'),
                "param_name" => "el_class",
                "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'nasa-core')
            )
        )
    );
    vc_map($share_params);

    // **********************************************************************// 
    // ! Register New Element: Follow
    // **********************************************************************//
    $follow = array(
        "name" => esc_html__("Follow", 'nasa-core'),
        "base" => "nasa_follow",
        'icon' => 'icon-wpb-nasatheme',
        'description' => esc_html__("Display Follow icon social.", 'nasa-core'),
        "content_element" => true,
        "category" => 'Nasa Core',
        "show_settings_on_create" => false,
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__('Title', 'nasa-core'),
                "param_name" => 'title'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Twitter', 'nasa-core'),
                "param_name" => 'twitter'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Facebook', 'nasa-core'),
                "param_name" => 'facebook'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Pinterest', 'nasa-core'),
                "param_name" => 'pinterest'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Email', 'nasa-core'),
                "param_name" => 'email'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Instagram', 'nasa-core'),
                "param_name" => 'instagram'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('RSS', 'nasa-core'),
                "param_name" => 'rss'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Linkedin', 'nasa-core'),
                "param_name" => 'linkedin'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Youtube', 'nasa-core'),
                "param_name" => 'youtube'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Flickr', 'nasa-core'),
                "param_name" => 'flickr'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Telegram', 'nasa-core'),
                "param_name" => 'telegram'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Whatsapp', 'nasa-core'),
                "param_name" => 'whatsapp'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Extra class name", 'nasa-core'),
                "param_name" => "el_class",
                "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'nasa-core')
            )
        )
    );
    
    vc_map($follow);
}