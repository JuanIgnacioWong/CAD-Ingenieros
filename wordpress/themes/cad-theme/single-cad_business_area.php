<?php
get_header();
?>

<main id="main-content" class="cad-main cad-main--generic cad-business-area-page">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $business_area_data = cad_theme_get_business_area_page_data(get_the_ID());
        $desc_ambitos_title = trim((string) $business_area_data['desc_ambitos_title']);
        $desc_ambitos_description = isset($business_area_data['desc_ambitos_description']) ? (string) $business_area_data['desc_ambitos_description'] : '';
        $desc_ambitos_items = isset($business_area_data['desc_ambitos_items']) && is_array($business_area_data['desc_ambitos_items'])
            ? $business_area_data['desc_ambitos_items']
            : array();
        $has_desc_ambitos_description = '' !== trim((string) wp_strip_all_tags($desc_ambitos_description));
        $has_desc_ambitos_items = !empty($desc_ambitos_items);
        $has_desc_ambitos_section = $has_desc_ambitos_description || $has_desc_ambitos_items;
        $assigned_cta_block = function_exists('cad_theme_get_assigned_cta_block_for_post')
            ? cad_theme_get_assigned_cta_block_for_post(get_the_ID())
            : null;
        $assigned_cta_html = '';
        if (function_exists('cad_theme_render_cta_block') && is_array($assigned_cta_block)) {
            $assigned_cta_html = cad_theme_render_cta_block(
                $assigned_cta_block,
                array('actions_extra_class' => 'cad-business-area__cta-actions')
            );
        }

        $legacy_cta_html = '';
        if (function_exists('cad_theme_render_cta_block') && function_exists('cad_theme_normalize_business_cta_buttons')) {
            $legacy_cta_text = '';
            $legacy_cta_buttons = array();
            $has_legacy_cta_data = false;

            if (metadata_exists('post', get_the_ID(), '_cad_business_final_cta_text')) {
                $legacy_cta_text = sanitize_text_field((string) get_post_meta(get_the_ID(), '_cad_business_final_cta_text', true));
                if ('' !== trim($legacy_cta_text)) {
                    $has_legacy_cta_data = true;
                }
            }

            if (metadata_exists('post', get_the_ID(), '_cad_business_cta_buttons')) {
                $legacy_cta_buttons = cad_theme_normalize_business_cta_buttons(get_post_meta(get_the_ID(), '_cad_business_cta_buttons', true));
                if (!empty($legacy_cta_buttons)) {
                    $has_legacy_cta_data = true;
                }
            } else {
                $primary_label = metadata_exists('post', get_the_ID(), '_cad_business_final_cta_primary_label')
                    ? (string) get_post_meta(get_the_ID(), '_cad_business_final_cta_primary_label', true)
                    : '';
                $primary_url = metadata_exists('post', get_the_ID(), '_cad_business_final_cta_primary_url')
                    ? (string) get_post_meta(get_the_ID(), '_cad_business_final_cta_primary_url', true)
                    : '';
                $secondary_label = metadata_exists('post', get_the_ID(), '_cad_business_final_cta_secondary_label')
                    ? (string) get_post_meta(get_the_ID(), '_cad_business_final_cta_secondary_label', true)
                    : '';
                $secondary_url = metadata_exists('post', get_the_ID(), '_cad_business_final_cta_secondary_url')
                    ? (string) get_post_meta(get_the_ID(), '_cad_business_final_cta_secondary_url', true)
                    : '';

                $legacy_cta_buttons = cad_theme_get_legacy_business_cta_buttons($primary_label, $primary_url, $secondary_label, $secondary_url);
                if (!empty($legacy_cta_buttons)) {
                    $has_legacy_cta_data = true;
                }
            }

            if ($has_legacy_cta_data) {
                // TODO: retirar este fallback cuando todos los CTAs legacy se migren a cad_cta_block.
                $legacy_cta_html = cad_theme_render_cta_block(
                    array(
                        'visible_title' => __('Siguiente paso', 'cad-theme'),
                        'content'       => $legacy_cta_text,
                        'buttons'       => $legacy_cta_buttons,
                    ),
                    array('actions_extra_class' => 'cad-business-area__cta-actions')
                );
            }
        }
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

            <?php if ($has_desc_ambitos_section) : ?>
                <section class="cad-business-area__section cad-business-area-dev">
                    <div class="cad-business-area__inner">
                        <?php if ('' !== $desc_ambitos_title) : ?>
                            <header class="cad-business-area-dev__header">
                                <h2 class="cad-business-area-dev__title"><?php echo esc_html($desc_ambitos_title); ?></h2>
                            </header>
                        <?php endif; ?>

                        <div class="cad-business-area-dev__grid<?php echo (!$has_desc_ambitos_description || !$has_desc_ambitos_items) ? ' is-single-column' : ''; ?>">
                            <?php if ($has_desc_ambitos_description) : ?>
                                <article class="cad-business-area-dev__card">
                                    <div class="cad-business-area-dev__card-line" aria-hidden="true"></div>
                                    <div class="cad-business-area-dev__icon" aria-hidden="true">
                                        <span class="material-symbols-outlined">description</span>
                                    </div>
                                    <div class="cad-business-area-dev__description">
                                        <?php echo wp_kses_post($desc_ambitos_description); ?>
                                    </div>
                                </article>
                            <?php endif; ?>

                            <?php if ($has_desc_ambitos_items) : ?>
                                <div class="cad-business-area-dev__list">
                                    <?php foreach ($desc_ambitos_items as $index => $item) : ?>
                                        <?php
                                        $item_title = isset($item['title']) ? (string) $item['title'] : '';
                                        $item_description = isset($item['description']) ? (string) $item['description'] : '';
                                        ?>
                                        <article class="cad-business-area-dev__item<?php echo 0 === $index ? ' is-featured' : ''; ?>">
                                            <div class="cad-business-area-dev__item-top">
                                                <span class="cad-business-area-dev__dot" aria-hidden="true"></span>
                                                <h3 class="cad-business-area-dev__item-title"><?php echo esc_html($item_title); ?></h3>
                                            </div>

                                            <?php if ('' !== $item_description) : ?>
                                                <div class="cad-business-area-dev__item-body">
                                                    <?php echo wp_kses_post(wpautop(esc_html($item_description))); ?>
                                                </div>
                                            <?php endif; ?>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

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
                <?php if ('1' === (string) $business_area_data['show_related_projects'] && !empty($business_area_data['related_projects'])) : ?>
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
                <?php endif; ?>
            </section>

            <?php if ('' !== $assigned_cta_html || '' !== $legacy_cta_html) : ?>
                <section class="cad-business-area__section cad-business-area__section--cta">
                    <div class="cad-business-area__inner">
                        <?php if ('' !== $assigned_cta_html) : ?>
                            <?php echo $assigned_cta_html; ?>
                        <?php else : ?>
                            <?php echo $legacy_cta_html; ?>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();
