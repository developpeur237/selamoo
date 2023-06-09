<?php
/**
 * The Template for displaying single portfolio project.
 *
 */

if(!NASA_CORE_ACTIVED || !isset($nasa_opt['enable_portfolio']) || !$nasa_opt['enable_portfolio']) :
    require_once DIGI_THEME_PATH . '/404.php';
    exit(); // Exit if nasa-core has not actived OR disable Fortfolios
endif;

get_header();
digi_get_breadcrumb();
?>

<div class="row">
    <div class="content large-12 columns margin-bottom-50">
        <?php if (have_posts()) :
            while (have_posts()) :
                the_post(); ?>
                <div class="portfolio-single-item">
                    <?php the_content(); ?>
                </div>
            <?php 
            endwhile;
        else : ?>
            <h3><?php esc_html_e('Aucune pages trouver!', 'digi-theme') ?></h3>
        <?php endif; ?>
        <div class="clear"></div>
        <?php
        if (!isset($nasa_opt['portfolio_comments']) || $nasa_opt['portfolio_comments']) :
            comments_template('', true);
        endif;
        if (function_exists('nasa_get_recent_portfolio') && (!isset($nasa_opt['recent_projects']) || $nasa_opt['recent_projects'])) :
            echo nasa_get_recent_portfolio(8, esc_html__('Projets recent', 'digi-theme'), $post->ID);
        endif;
        ?>
    </div>
</div>
<?php

get_footer();