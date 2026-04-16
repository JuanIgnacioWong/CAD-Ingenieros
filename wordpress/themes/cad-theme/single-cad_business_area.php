<?php
get_header();
?>

<main id="main-content" class="cad-main cad-main--generic cad-business-area-page">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $business_area_data = cad_theme_get_business_area_page_data(get_the_ID());
        $hero_line_two = trim(
            implode(
                ' ',
                array_filter(
                    array(
                        isset($business_area_data['hero_title_suffix']) ? (string) $business_area_data['hero_title_suffix'] : '',
                        isset($business_area_data['hero_title_accent']) ? (string) $business_area_data['hero_title_accent'] : '',
                    )
                )
            )
        );
        ?>
        <article <?php post_class('cad-business-area'); ?>>
            <header class="cad-business-area__hero">
                <div
                    class="cad-business-area__hero-media<?php echo !empty($business_area_data['hero_image']) ? '' : ' is-placeholder'; ?>"
                    <?php if (!empty($business_area_data['hero_image'])) : ?>
                        style="background-image:url('<?php echo esc_url((string) $business_area_data['hero_image']); ?>');"
                    <?php endif; ?>
                ></div>
                <div class="cad-business-area__hero-overlay"></div>

                <div class="cad-business-area__inner cad-business-area__hero-inner">
                    <div class="cad-business-area__badge">
                        <?php if (!empty($business_area_data['badge_label'])) : ?>
                            <span class="cad-business-area__badge-label"><?php echo esc_html((string) $business_area_data['badge_label']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($business_area_data['badge_context'])) : ?>
                            <span class="cad-business-area__badge-context"><?php echo esc_html((string) $business_area_data['badge_context']); ?></span>
                        <?php endif; ?>
                    </div>

                    <h1 class="cad-business-area__title">
                        <span><?php the_title(); ?></span>
                        <span>
                            <?php if (!empty($business_area_data['hero_title_suffix'])) : ?>
                                <span class="cad-business-area__title-base"><?php echo esc_html((string) $business_area_data['hero_title_suffix']); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($business_area_data['hero_title_accent'])) : ?>
                                <em><?php echo esc_html((string) $business_area_data['hero_title_accent']); ?></em>
                            <?php endif; ?>
                            <?php if ('' === $hero_line_two) : ?>
                                <em><?php esc_html_e('Especializada', 'cad-theme'); ?></em>
                            <?php endif; ?>
                        </span>
                    </h1>

                    <ul class="cad-business-area__meta" aria-label="<?php esc_attr_e('Resumen del area', 'cad-theme'); ?>">
                        <?php if (!empty($business_area_data['meta_location'])) : ?>
                            <li><?php echo esc_html((string) $business_area_data['meta_location']); ?></li>
                        <?php endif; ?>
                        <?php if (!empty($business_area_data['meta_experience'])) : ?>
                            <li><?php echo esc_html((string) $business_area_data['meta_experience']); ?></li>
                        <?php endif; ?>
                        <?php if (!empty($business_area_data['meta_projects'])) : ?>
                            <li><?php echo esc_html((string) $business_area_data['meta_projects']); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </header>

            <section class="cad-business-area__section cad-business-area__section--intro">
                <div class="cad-business-area__inner">
                    <div class="cad-business-area__intro">
                        <?php if (!empty($business_area_data['description_label'])) : ?>
                            <span class="cad-business-area__kicker"><?php echo esc_html((string) $business_area_data['description_label']); ?></span>
                        <?php endif; ?>
                        <div class="cad-business-area__intro-copy">
                            <?php echo wp_kses_post((string) $business_area_data['description']); ?>
                        </div>
                    </div>
                </div>
            </section>

            <section class="cad-business-area__section">
                <div class="cad-business-area__inner">
                    <div class="cad-business-area__section-heading">
                        <?php if (!empty($business_area_data['structure_label'])) : ?>
                            <span class="cad-business-area__kicker"><?php echo esc_html((string) $business_area_data['structure_label']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($business_area_data['structure_title'])) : ?>
                            <h2><?php echo esc_html((string) $business_area_data['structure_title']); ?></h2>
                        <?php endif; ?>
                    </div>

                    <div class="cad-business-area__structure-grid">
                        <?php foreach ($business_area_data['subareas'] as $subarea) : ?>
                            <article class="cad-business-area__service-card">
                                <span class="cad-business-area__service-icon material-symbols-outlined" aria-hidden="true"><?php echo esc_html((string) $subarea['icon']); ?></span>
                                <h3><?php echo esc_html((string) $subarea['title']); ?></h3>
                                <?php if (!empty($subarea['description'])) : ?>
                                    <p><?php echo esc_html((string) $subarea['description']); ?></p>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section class="cad-business-area__section cad-business-area__section--gallery">
                <div class="cad-business-area__inner">
                    <div class="cad-business-area__section-heading">
                        <?php if (!empty($business_area_data['gallery_label'])) : ?>
                            <span class="cad-business-area__kicker"><?php echo esc_html((string) $business_area_data['gallery_label']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($business_area_data['gallery_title'])) : ?>
                            <h2><?php echo esc_html((string) $business_area_data['gallery_title']); ?></h2>
                        <?php endif; ?>
                    </div>

                    <div class="cad-business-area__gallery">
                        <?php foreach ($business_area_data['gallery'] as $index => $gallery_item) : ?>
                            <figure class="cad-business-area__gallery-item<?php echo !empty($gallery_item['placeholder']) ? ' is-placeholder' : ''; ?> cad-business-area__gallery-item--<?php echo esc_attr((string) ($index + 1)); ?>">
                                <?php if (empty($gallery_item['placeholder'])) : ?>
                                    <img src="<?php echo esc_url((string) $gallery_item['url']); ?>" alt="<?php echo esc_attr((string) $gallery_item['alt']); ?>" loading="lazy">
                                <?php else : ?>
                                    <div class="cad-business-area__gallery-placeholder" aria-hidden="true">
                                        <span><?php echo esc_html((string) $gallery_item['alt']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </figure>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section class="cad-business-area__section cad-business-area__section--projects">
                <div class="cad-business-area__inner">
                    <div class="cad-business-area__section-heading">
                        <?php if (!empty($business_area_data['projects_label'])) : ?>
                            <span class="cad-business-area__kicker"><?php echo esc_html((string) $business_area_data['projects_label']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($business_area_data['projects_title'])) : ?>
                            <h2><?php echo esc_html((string) $business_area_data['projects_title']); ?></h2>
                        <?php endif; ?>
                    </div>

                    <div class="cad-business-area__project-list">
                        <?php foreach ($business_area_data['related_projects'] as $index => $project_item) : ?>
                            <?php
                            $project_name = isset($project_item['name']) ? (string) $project_item['name'] : '';
                            $project_url = isset($project_item['url']) ? (string) $project_item['url'] : '';
                            $project_location = isset($project_item['location']) ? (string) $project_item['location'] : '';
                            $project_year = isset($project_item['year']) ? (string) $project_item['year'] : '';
                            $project_status = isset($project_item['status']) ? (string) $project_item['status'] : '';
                            $project_tag = $project_url ? 'a' : 'article';
                            ?>
                            <<?php echo $project_tag; ?> class="cad-business-area__project-item<?php echo $project_url ? '' : ' is-static'; ?>"<?php if ($project_url) : ?> href="<?php echo esc_url($project_url); ?>"<?php endif; ?>>
                                <span class="cad-business-area__project-number"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>

                                <div class="cad-business-area__project-body">
                                    <h3><?php echo esc_html($project_name); ?></h3>
                                    <?php if ($project_location) : ?>
                                        <p><?php echo esc_html($project_location); ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="cad-business-area__project-tags">
                                    <?php if ($project_year) : ?>
                                        <span><?php echo esc_html($project_year); ?></span>
                                    <?php endif; ?>
                                    <?php if ($project_status) : ?>
                                        <span><?php echo esc_html($project_status); ?></span>
                                    <?php endif; ?>
                                </div>

                                <span class="cad-business-area__project-arrow material-symbols-outlined" aria-hidden="true">arrow_outward</span>
                            </<?php echo $project_tag; ?>>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section class="cad-business-area__section cad-business-area__section--cta">
                <div class="cad-business-area__inner cad-business-area__cta">
                    <div class="cad-business-area__cta-copy">
                        <span class="cad-business-area__cta-kicker"><?php esc_html_e('Siguiente paso', 'cad-theme'); ?></span>
                        <?php if (!empty($business_area_data['final_cta_text'])) : ?>
                            <p><?php echo esc_html((string) $business_area_data['final_cta_text']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="cad-business-area__cta-actions">
                        <?php if (!empty($business_area_data['final_cta_primary_label']) && !empty($business_area_data['final_cta_primary_url'])) : ?>
                            <a class="cad-business-area__cta-primary" href="<?php echo esc_url((string) $business_area_data['final_cta_primary_url']); ?>">
                                <?php echo esc_html((string) $business_area_data['final_cta_primary_label']); ?>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($business_area_data['final_cta_secondary_label']) && !empty($business_area_data['final_cta_secondary_url'])) : ?>
                            <a class="cad-business-area__cta-secondary" href="<?php echo esc_url((string) $business_area_data['final_cta_secondary_url']); ?>">
                                <?php echo esc_html((string) $business_area_data['final_cta_secondary_label']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();
