<?php
get_header();
?>

<main id="main-content" class="ebco-main ebco-main--generic">
    <div class="ebco-shell-narrow">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('ebco-post'); ?>>
                    <h1 class="ebco-post__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                    <div class="ebco-post__meta"><?php echo esc_html(get_the_date()); ?></div>
                    <div class="ebco-post__content"><?php the_excerpt(); ?></div>
                </article>
            <?php endwhile; ?>

            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e('No hay contenido disponible.', 'cad-theme'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
