<?php
/*
  Template name: Full Width (100%)
 */
get_header();

/* Display popup window */
if (isset($nasa_opt['promo_popup']) && $nasa_opt['promo_popup'] == 1) : ?>
    <div class="popup_link hide">
        <a class="nasa-popup open-click" href="#nasa-popup">
            <?php esc_html_e('Selamoo Newsletter', 'digi-theme'); ?>
        </a>
    </div>
    <?php do_action('after_page_wrapper'); ?>
<?php endif; ?>

<?php if (has_excerpt()) : ?>
    <div class="page-header">
        <?php the_excerpt(); ?>
    </div>
<?php endif; ?>

<div id="content" role="main">
    <?php while (have_posts()) :
        the_post();
        the_content();
    endwhile; ?>
</div>
<?php
get_footer();
