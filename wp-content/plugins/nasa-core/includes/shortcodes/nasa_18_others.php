<?php

add_shortcode("nasa_service_box", "nasa_sc_service_box");
add_shortcode('nasa_client', 'nasa_sc_client');
add_shortcode("nasa_contact_us", "nasa_sc_contact_us");
add_shortcode('nasa_title', 'nasa_title');
add_shortcode('nasa_opening_time', 'nasa_opening_time');

/* SERVICE BOX */
function nasa_sc_service_box($atts, $content = null) {
    extract(shortcode_atts(array(
        'service_icon' => '',
        'service_title' => '',
        'service_desc' => '',
        'service_link' => '',
        'service_style' => 'style-1',
        'service_hover' => '',
        'el_class' => ''
    ), $atts));
    ob_start();
    echo (isset($service_link) && trim($service_link) != '') ? '<a href="' . $service_link . '">' : '';
    ?>
    <div class="service-block <?php echo esc_attr($service_style); ?> <?php echo esc_attr($el_class); ?>">
        <div class="box">
            <div class="service-icon <?php echo esc_attr($service_hover); ?> <?php echo esc_attr($service_icon) ?>"></div>
            <div class="service-text">
                <?php if (isset($service_title) && $service_title != '') { ?>
                    <div class="service-title"><?php echo esc_attr($service_title); ?></div>
                <?php } ?>
                <?php if (isset($service_desc) && $service_desc != '') { ?>
                    <div class="service-desc"><?php echo esc_attr($service_desc); ?></div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
    echo (isset($service_link) && trim($service_link) != '') ? '</a>' : '';
    $content = ob_get_clean();
    
    return $content;
}

function nasa_sc_client($atts, $content) {
    extract(shortcode_atts(array(
        "img_src" => '',
        "name" => '',
        "company" => '',
        "text_color" => '#fff',
        "content" => $content,
        'text_align' => 'center',
        'el_class' => ''
    ), $atts));

    $content = (trim($content) != '') ? nasa_fixShortcode($content) : '';
    $el_class = (trim($el_class) != '') ? ' ' . asc_attr($el_class) : '';

    switch ($text_align) {
        case 'right':
        case 'left':
        case 'justify':
            $el_class .= ' text-' . $text_align;
            break;
        case 'center':
        default:
            $el_class .= ' text-center';
            break;
    }

    $image = '';
    if ($img_src != '') {
        $image = wp_get_attachment_image_src($img_src, 'full');
        $image = '<img class="wow fadeInUp" data-wow-delay="100ms" data-wow-duration="1s" src="' . esc_url($image[0]) . '" alt="" />';
    }

    $text_color = esc_attr($text_color);
    $client = 
        '<div class="client large-12' . $el_class . '">' .
            '<div class="client-inner" style="color:' . $text_color . '">' .
                '<div class="client-info wow fadeInUp" data-wow-delay="100ms" data-wow-duration="1s">' .
                    '<div class="client-content" style="color:' . $text_color . '">' . $content . '</div>' .
                    '<div class="client-img-info">' .
                        '<div class="client-img">' . $image . '</div>' .
                        '<div class="client-name-post">' .
                            '<h4 class="client-name">' . $name . '</h4>' .
                            '<span class="client-pos" style="color:' . $text_color . '">' . $company . '</span>' .
                        '</div>' .
                    '</div>' .
                '</div>' .
            '</div>' .
        '</div>';

    return $client;
}

/* CONTACT US ELEMENT */
function nasa_sc_contact_us($atts, $content = null) {
    extract(shortcode_atts(array(
        'contact_logo' => '',
        'contact_address' => '',
        'contact_phone' => '',
        'service_desc' => '',
        'contact_email' => '',
        'contact_website' => '',
        'el_class' => ''
    ), $atts));
    ob_start();
    ?>
    <ul class="contact-information <?php echo esc_attr($el_class); ?>">
    <?php if (isset($contact_logo) && $contact_logo) { ?>
        <li class="contact-logo">
            <img src="<?php echo esc_attr($contact_logo); ?>" alt="Logo" />
        </li>
    <?php } ?>

    <?php if (isset($contact_address) && $contact_address) { ?>
        <li class="media">
            <div class="contact-text"><span><?php echo esc_attr($contact_address); ?></span></div>
        </li>
    <?php } ?>

    <?php if (isset($contact_phone) && $contact_phone) { ?>
        <li class="media">
            <div class="contact-text"><span><?php echo esc_attr($contact_phone); ?></span></div>
        </li>
    <?php } ?>
        
    <?php if (isset($contact_email) && $contact_email) { ?>
        <li class="media">
            <div class="contact-text"><span><?php echo esc_attr($contact_email); ?></span></div>
        </li>
    <?php } ?>
    <?php if (isset($contact_website) && $contact_website) { ?>
        <li class="media">
            <div class="contact-text"><span><?php echo esc_attr($contact_website); ?></span></div>
        </li>
    <?php } ?>
    </ul>

    <?php
    $content = ob_get_clean();
    
    return $content;
}

/* TITLE */
function nasa_title($atts, $content = null) {
    extract(shortcode_atts(array(
        'title_text' => '',
        'title_style' => '',
        /* 'title_align' => '', */
        'first_special' => '0',
        'el_class' => '',
        'text' => '',
        'style' => '',
        'align' => '',
        'title_type' => 'type_2'
    ), $atts));
    
    $style_output = $title_style != '' ? ' title-' . $title_style : '';
    $align_output = ''; /* ($title_align != '') ? ' ' . $title_align : ''; */
    $title = $title_text ? '<span>' . $title_text . '</span>' : '';
    if($first_special) {
        $texts = $title_text ? explode(' ', $title_text) : array('');
        $first = $texts[0];
        unset($texts[0]);
        $title = $first != '' ? '<span class="nasa-first-word">' . $first . '</span>' : '';
        $title .= count($texts) ? '<span> ' . implode(' ', $texts) . '</span>' : '';
    }
    $el_class = $el_class != '' ? ' ' . $el_class : '';
    if ($title_type != 'type_2') {
        $title .= '<span class="title-border-separator"></span>';
        return '<h5 class="nasa-title clearfix' . $style_output . $align_output . $el_class . '">' . $title . '</h5>';
    } else {
        return '<div class="nasa-title nasa_' . $title_type . $style_output . $align_output . $el_class . '">
                <h3 class="nasa-title-heading">' . $title . '</h3>
                <hr class="nasa-separator" /></div>';
    }
    
}

/* Opening Time */
function nasa_opening_time($atts, $content = null) {
    extract(shortcode_atts(array(
        'weekdays_start' => '08:00',
        'weekdays_end' => '20:00',
        'sat_start' => '09:00',
        'sat_end' => '21:00',
        'sun_start' => '13:00',
        'sun_end' => '22:00'
    ), $atts));

    $content = '<ul class="nasa-opening-time">';
        $content .= '<li><span class="nasa-day-open">' . esc_html__('Monday - Friday', 'nasa-core') . '</span><span class="nasa-time-open">' . $weekdays_start . ' - ' . $weekdays_end . '</span></li>';
        $content .= '<li><span class="nasa-day-open">' . esc_html__('Saturday', 'nasa-core') . '</span><span class="nasa-time-open">' . $sat_start . ' - ' . $sat_end . '</span></li>';
        $content .= '<li><span class="nasa-day-open">' . esc_html__('Sunday', 'nasa-core') . '</span><span class="nasa-time-open">' . $sun_start . ' - ' . $sun_end . '</span></li>';
    $content .= '</ul>';

    return $content;
}

add_action('init', 'nasa_register_others');
function nasa_register_others(){
    // **********************************************************************// 
    // ! Register New Element: Service Box
    // **********************************************************************//
    $service_box_params = array(
        "name" => esc_html__("Service box", 'nasa-core'),
        "base" => "nasa_service_box",
        'icon' => 'icon-wpb-nasatheme',
        'description' => esc_html__("Create sevice box.", 'nasa-core'),
        "content_element" => true,
        "category" => 'Nasa Core',
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__("Service title", 'nasa-core'),
                "param_name" => "service_title",
                "admin_label" => true,
                "description" => esc_html__("Enter service title.", 'nasa-core'),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Service Description", 'nasa-core'),
                "param_name" => "service_desc",
                "description" => esc_html__("Enter service Description.", 'nasa-core'),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Icon", 'nasa-core'),
                "param_name" => "service_icon",
                "description" => esc_html__("Enter icon class name. Support  Font Awesome, Font Pe 7 Stroke (http://themes-pixeden.com/font-demos/7-stroke/), ", 'nasa-core')
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Service link", 'nasa-core'),
                "param_name" => "service_link",
                "admin_label" => true,
                "description" => esc_html__("Enter service title.", 'nasa-core'),
            ),
            array(
                "type" => "dropdown",
                "heading" => "Service style type",
                "param_name" => "service_style",
                "description" => esc_html__("Select style type", 'nasa-core'),
                "value" => array(
                    esc_html__('Style 1', 'nasa-core') => 'style-1',
                    esc_html__('Style 2', 'nasa-core') => 'style-2',
                    esc_html__('Style 3', 'nasa-core') => 'style-3',
                )
            ),
            array(
                "type" => "dropdown",
                "heading" => "Service Hover Effect",
                "param_name" => "service_hover",
                "description" => esc_html__("Select effect when hover service icon", 'nasa-core'),
                "value" => array(
                    esc_html__('None', 'nasa-core') => '',
                    esc_html__('Fly', 'nasa-core') => 'fly_effect',
                    esc_html__('Buzz', 'nasa-core') => 'buzz_effect',
                    esc_html__('Rotate', 'nasa-core') => 'rotate_effect',
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
    vc_map($service_box_params);
    
    // **********************************************************************// 
    // ! Register New Element: Testimonials
    // **********************************************************************//
    $client_params = array(
        "name" => esc_html__("Testimonials", 'nasa-core'),
        "base" => "nasa_client",
        'icon' => 'icon-wpb-nasatheme',
        'description' => esc_html__("Ex: Customers say about us.", 'nasa-core'),
        "content_element" => true,
        "category" => 'Nasa Core',
        "params" => array(
            array(
                "type" => "attach_image",
                "heading" => esc_html__("Testimonials avatar image", 'nasa-core'),
                "param_name" => "img_src",
                "description" => esc_html__("Choose Avatar image.", 'nasa-core')
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Testimonials name", 'nasa-core'),
                "param_name" => "name",
                "description" => esc_html__("Enter name.", 'nasa-core')
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Testimonials job", 'nasa-core'),
                "param_name" => "company",
                "description" => esc_html__("Enter job.", 'nasa-core')
            ),
            array(
                "type" => "colorpicker",
                "heading" => esc_html__("Testimonials text color", 'nasa-core'),
                "param_name" => "text_color",
                "value" => "#fff",
                "description" => esc_html__("Choose text color.", 'nasa-core')
            ),
            array(
                "type" => "textarea_html",
                "holder" => "div",
                "heading" => "Testimonials content say",
                "param_name" => "content",
                "value" => "Some promo text",
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Align', 'nasa-core'),
                "param_name" => 'text_align',
                "value" => array(
                    "center" => 'center',
                    "left" => 'left',
                    "right" => 'right',
                    "justify" => 'justify'
                ),
                'std' => 'center'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Extra class name", 'nasa-core'),
                "param_name" => "el_class",
                "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'nasa-core')
            )
        )
    );
    vc_map($client_params);
    
    // **********************************************************************// 
    // ! Register New Element: Contact Footer
    // **********************************************************************//
    $contact_us_params = array(
        "name" => esc_html__("Contact info", 'nasa-core'),
        'base' => 'nasa_contact_us',
        'icon' => 'icon-wpb-nasatheme',
        'description' => esc_html__("Create info contact, introduce.", 'nasa-core'),
        'category' => 'Nasa Core',
        'params' => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__("Contact Logo (Link of image logo)", 'nasa-core'),
                "param_name" => "title",
                "value" => ""
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Address", 'nasa-core'),
                "param_name" => "contact_address"
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Phone", 'nasa-core'),
                "param_name" => "contact_phone"
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Email", 'nasa-core'),
                "param_name" => "contact_email"
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Website", 'nasa-core'),
                "param_name" => "contact_website"
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Extra Class", 'nasa-core'),
                "param_name" => "class"
            )
        )
    );
    vc_map($contact_us_params);
    
    // **********************************************************************// 
    // ! Register New Element: nasa Title
    // **********************************************************************//
    // first_special
    $nasa_title_params = array(
        "name" => esc_html__("Title", 'nasa-core'),
        "base" => "nasa_title",
        'icon' => 'icon-wpb-nasatheme',
        'description' => esc_html__("Create title of session.", 'nasa-core'),
        "content_element" => true,
        "category" => 'Nasa Core',
        "params" => array(
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Title text', 'nasa-core'),
                'param_name' => 'title_text',
                'admin_label' => true,
                'value' => '',
                'description' => ''
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Title type', 'nasa-core'),
                "param_name" => 'title_type',
                "value" => array(
                    esc_html__('Full HR', 'nasa-core') => 'type_2',
                    esc_html__('Simple HR', 'nasa-core') => 'type_1'
                ),
                'std' => 'type_2'
            ),
            
            /** Igrone
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Title Alignment', 'nasa-core'),
                "param_name" => 'title_align',
                "value" => array(
                    esc_html__('Left', 'nasa-core') => '',
                    esc_html__('Center', 'nasa-core') => 'text-center',
                    esc_html__('Right', 'nasa-core') => 'text-right'
                )
            ), */
            
            array(
                "type" => "dropdown",
                "heading" => esc_html__('Title Style', 'nasa-core'),
                "param_name" => 'first_special',
                "value" => array(
                    esc_html__('Special First word', 'nasa-core') => '1',
                    esc_html__('None Special First word', 'nasa-core') => '0'
                ),
                "std" => '0'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Extra class name", 'nasa-core'),
                "param_name" => "el_class",
                "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'nasa-core')
            )
        )
    );
    vc_map($nasa_title_params);
    
    // **********************************************************************// 
    // ! Register New Element: Opening Time
    // **********************************************************************//
    $opening = array(
        "name" => esc_html__("Opening time", 'nasa-core'),
        "base" => "nasa_opening_time",
        'icon' => 'icon-wpb-nasatheme',
        'description' => esc_html__("Create info opening time of shop.", 'nasa-core'),
        "content_element" => true,
        "category" => 'Nasa Core',
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__('Weekdays Start Time', 'nasa-core'),
                "param_name" => 'weekdays_start',
                "std" => '08:00'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Weekdays End Time', 'nasa-core'),
                "param_name" => 'weekdays_end',
                "std" => '20:00'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Saturday Start Time', 'nasa-core'),
                "param_name" => 'sat_start',
                "std" => '09:00'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Saturday End Time', 'nasa-core'),
                "param_name" => 'sat_end',
                "std" => '21:00'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Sunday Start Time', 'nasa-core'),
                "param_name" => 'sun_start',
                "std" => '13:00'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__('Sunday End Time', 'nasa-core'),
                "param_name" => 'sun_end',
                "std" => '22:00'
            )
        )
    );
    vc_map($opening);
}