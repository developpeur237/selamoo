<?php
/**
 * @package nasatheme
 */
get_header(); ?>
<style>
    img.left {
        width: 50%;
        position: relative;
        left: -6%;
    }

</style>
<div  class="container-wrap">
    <div class="row">
        <div id="content" class="large-12 left columns" role="main">
            <article id="post-0" class="post error404 not-found text-center">
                <header class="entry-header">
                    <img class="left" src="<?php echo DIGI_THEME_URI.'/assets/images/404.png'; ?>" />
                    <h1 class="entry-title"><?php esc_html_e( 'Oops! Page introuvable', 'digi-theme' ); ?></h1>
                </header><!-- .entry-header -->
                <div class="entry-content">
                    <p><?php esc_html_e( 'Nous sommes desoler, la page que vous cherchez n&rsquo;existe pas chez Selamoo. Veillez vous rassurer d&rsquo;avoir entree les bonne URL.', 'digi-theme' ); ?></p>
                    <?php get_search_form(); 
                    
                    ?>
                    <a class="button medium" href="<?php echo esc_url(home_url('/'));?>"><?php esc_html_e('Retour a l&rsquo;Accueil','digi-theme');?></a>
                </div>
            </article>
        </div>
    </div>
</div>

<?php
get_footer();