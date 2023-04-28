<?php

if($wp_query->post_count) :
    $_delay = $count = 0;
    $_delay_item = (isset($nasa_opt['delay_overlay']) && (int) $nasa_opt['delay_overlay']) ? (int) $nasa_opt['delay_overlay'] : 100;
    
    if (!isset($_COOKIE['gridcookie']) || (isset($nasa_opt['enable_change_view']) && !$nasa_opt['enable_change_view'])) :
        $products_per_row = (isset($nasa_opt['products_type_view']) && $nasa_opt['products_type_view'] == 'list') ? 4 :
            (isset($nasa_opt['products_per_row']) ? $nasa_opt['products_per_row'] : 5);
    else:
        switch ($_COOKIE['gridcookie']) :
            case 'grid-3' :
                $products_per_row = 3;
                break;
            case 'grid-4' :
            case 'list' :
                $products_per_row = 4;
                break;
            case 'grid-5' :
            default:
                $products_per_row = 5;
                break;
        endswitch;
    endif;
    
    $products_per_row = ($products_per_row == 5 && ($nasa_sidebar == 'left' || $nasa_sidebar == 'right')) ? 4 : $products_per_row;

    $class_item = 'columns';

    // desktop columns
    switch ($products_per_row):
        case '3':
            $class_item .= ' large-4';
            break;
        case '4':
            $class_item .= ' large-3';
            break;
        case '5':
        default:
            $columns_number = 5;
            $class_item .= ' nasa-5-col';
            break;
    endswitch;

    /**
     * Columns for tablet
     */
    $products_per_row_tablet = isset($nasa_opt['products_per_row_tablet']) && (int) $nasa_opt['products_per_row_tablet'] ? (int) $nasa_opt['products_per_row_tablet'] : 2;
    switch ($products_per_row_tablet):
        case '1':
            $class_item .= ' medium-12';
            break;
        case '3':
            $class_item .= ' medium-4';
            break;
        case '2':
        default:
            $class_item .= ' medium-6';
            break;
    endswitch;

    /**
     * Columns for mobile
     */
    $products_per_row_small = isset($nasa_opt['products_per_row_small']) && (int) $nasa_opt['products_per_row_small'] ? (int) $nasa_opt['products_per_row_small'] : 1;
    switch ($products_per_row_small):
        case '2':
            $class_item .= ' small-6';
            break;
        case '1':
        default:
            $class_item .= ' small-12';
            break;
    endswitch;

    $start_row = '<div class="row nasa-product-wrap-all-items" data-columns_small="' . esc_attr($products_per_row_small) . '" data-columns_medium="' . esc_attr($products_per_row_tablet) . '">';
    $end_row = '</div>';
    
    echo $start_row;
    while ($wp_query->have_posts()) :
        $wp_query->the_post();
        // echo ($count && ($count % $products_per_row) == 0) ? $end_row . $start_row : '';
        echo '<div class="product-warp-item ' . esc_attr($class_item) . '">';
        wc_get_template(
            'content-product.php',
            array(
                '_delay' => $_delay,
                'wrapper' => 'div'
            )
        );
        echo '</div>';
        $_delay += $_delay_item;
        $count++;
    endwhile;
    
    echo $end_row;
    
else :
    // No data
    echo '<div class="row"><div class="large-12 columns">';
    wc_get_template('loop/no-products-found.php');
    echo '</div></div>';
endif;