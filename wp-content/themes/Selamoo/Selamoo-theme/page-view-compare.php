<?php
/*
  Template name: Nasa View compare
  This templates View products in compare.
 */
global $yith_woocompare;
if(!$yith_woocompare) :
    wp_redirect(esc_url(home_url('/')));
endif;

get_header();
digi_get_breadcrumb(); ?>

<div class="page-wrapper nasa-view-compare">
    <div class="row">
        <div id="content" class="large-12 columns" role="main">
            <!-- Compare products -->
            <?php
            if((defined('NASA_PLG_CACHE_ACTIVE') && NASA_PLG_CACHE_ACTIVE)) : ?>
                <div id="nasa-view-compare-product">
                    <div class="nasa-loader">
                        <div class="nasa-line"></div>
                        <div class="nasa-line"></div>
                        <div class="nasa-line"></div>
                        <div class="nasa-line"></div>
                    </div>
                </div>
            <?php else:
                echo digi_products_compare_content();
            endif;
            
            while (have_posts()) :
                the_post();
                the_content();
            endwhile; // end of the loop.
            ?>
        </div><!-- end #content large-12 -->
    </div><!-- end row -->
</div><!-- end page-right-sidebar container -->
<?php
get_footer();