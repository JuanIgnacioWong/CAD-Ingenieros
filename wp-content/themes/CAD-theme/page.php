<?php
get_header();
?>

<main id="main-content" class="ebco-main ebco-main--generic">
    <div class="ebco-shell-narrow">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('ebco-page'); ?>>
                <h1 class="ebco-post__title"><?php the_title(); ?></h1>
                <div class="ebco-post__content"><?php the_content(); ?></div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
