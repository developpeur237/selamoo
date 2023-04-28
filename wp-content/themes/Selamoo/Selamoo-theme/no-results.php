<?php
/**
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package nasatheme
 */
?>

<article id="post-0" class="post no-results not-found">
    <header class="entry-header">
        <h1 class="entry-title"><?php esc_html_e('Nothing Found', 'digi-theme'); ?></h1>
    </header>

    <div class="entry-content">
        <?php if (is_home() && current_user_can('publish_posts')) : ?>
            <p>
                <?php
                printf(wp_kses(__('Pret a poster ta premiere publication? <a href="%1$s">Commencez ici</a>.', 'digi-theme'), array('a' => array('href' => array()))), esc_url(admin_url('post-new.php')));
                ?>
            </p>
        <?php elseif (is_search()) : ?>
            <p><?php esc_html_e('Desolé, rien ne correspond a votres term de recherche. Veillez rechercher evec des mot-clé different.', 'digi-theme'); ?></p>
            <?php get_search_form(); ?>
        <?php else : ?>
            <p><?php esc_html_e('Il semblerait que nous ne trouvons pas ce que vous voulez. Peu-t-etre des recherch peuvent vous aider.', 'digi-theme'); ?></p>
            <?php get_search_form(); ?>
        <?php endif; ?>
    </div>
</article>
