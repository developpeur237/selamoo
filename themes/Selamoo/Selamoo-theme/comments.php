<?php
/**
 * The template for displaying Comments.
 *
 * @package nasatheme
 */
if (post_password_required()) :
    return;
endif;
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h3 class="comments-title">
            <?php echo esc_html__('Commentaire', 'digi-theme') . ' (' . get_comments_number() . ')'; ?>
        </h3>
        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav id="comment-nav-above" class="navigation-comment" role="navigation">
                <div class="nav-previous">
                    <?php previous_comments_link(esc_html__('&larr; Anciens commentaires', 'digi-theme')); ?>
                </div>
                <div class="nav-next">
                    <?php next_comments_link(esc_html__('Nouveaux commentaires &rarr;', 'digi-theme')); ?>
                </div>
            </nav>
        <?php endif; ?>
        <ol class="comment-list">
            <?php wp_list_comments(array('callback' => 'digi_comment')); ?>
        </ol>
        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav id="comment-nav-below" class="navigation-comment" role="navigation">
                <h1 class="screen-reader-text"><?php esc_html_e('Parcour de commentaire', 'digi-theme'); ?></h1>
                <div class="nav-previous">
                    <?php previous_comments_link(esc_html__('&larr; Anciens commentaires', 'digi-theme')); ?>
                </div>
                <div class="nav-next">
                    <?php next_comments_link(esc_html__('Nouveaux commentaires &rarr;', 'digi-theme')); ?>
                </div>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
    <?php
    if (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'commentaire')) : ?>
        <p class="no-comments"><?php esc_html_e('Les Commentaires sont fermé.', 'digi-theme'); ?></p>
    <?php endif; ?>
    <?php comment_form(); ?>
</div>