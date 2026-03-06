<?php
get_header();
?>

<main id="main-content" class="cad-main cad-main--generic">
    <div class="cad-shell-narrow">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('cad-post'); ?>>
                <h1 class="cad-post__title"><?php the_title(); ?></h1>
                <div class="cad-post__meta"><?php echo esc_html(get_the_date()); ?></div>
                <div class="cad-post__content"><?php the_content(); ?></div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
