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

            <section class="cad-business-area__section cad-business-area__section--cta">
                <div class="cad-business-area__inner cad-business-area__cta">
                    <div class="cad-business-area__cta-copy">
                        <span class="cad-business-area__cta-kicker"><?php esc_html_e('Siguiente paso', 'cad-theme'); ?></span>
                        <?php if (!empty($business_area_data['final_cta_text'])) : ?>
                            <p><?php echo esc_html((string) $business_area_data['final_cta_text']); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($business_area_data['cta_buttons']) && is_array($business_area_data['cta_buttons'])) : ?>
                        <div class="cad-business-area__cta-actions">
                            <?php foreach ($business_area_data['cta_buttons'] as $cta_button) : ?>
                                <?php
                                $button_style = isset($cta_button['style']) && in_array((string) $cta_button['style'], array('solid', 'outline'), true)
                                    ? (string) $cta_button['style']
                                    : 'solid';
                                $button_target = !empty($cta_button['target_blank']) ? '_blank' : '_self';
                                $button_rel = '_blank' === $button_target ? 'noopener noreferrer' : '';
                                $button_style_attr = sprintf(
                                    '--cta-bg:%1$s;--cta-text:%2$s;--cta-border:%3$s;--cta-radius:%4$s;',
                                    esc_attr(isset($cta_button['bg_color']) ? (string) $cta_button['bg_color'] : ''),
                                    esc_attr(isset($cta_button['text_color']) ? (string) $cta_button['text_color'] : ''),
                                    esc_attr(isset($cta_button['border_color']) ? (string) $cta_button['border_color'] : ''),
                                    esc_attr(isset($cta_button['border_radius']) ? (string) $cta_button['border_radius'] : '10px')
                                );
                                ?>
                                <a
                                    class="cad-business-area__cta-button is-<?php echo esc_attr($button_style); ?>"
                                    href="<?php echo esc_url(isset($cta_button['url']) ? (string) $cta_button['url'] : '#'); ?>"
                                    target="<?php echo esc_attr($button_target); ?>"
                                    <?php if ($button_rel) : ?>rel="<?php echo esc_attr($button_rel); ?>"<?php endif; ?>
                                    style="<?php echo esc_attr($button_style_attr); ?>"
                                >
                                    <?php echo esc_html(isset($cta_button['label']) ? (string) $cta_button['label'] : ''); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();
