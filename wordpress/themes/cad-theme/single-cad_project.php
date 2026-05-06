<?php
get_header();
?>

<main id="main-content" class="cad-main cad-main--generic cad-project-page">
    <?php while (have_posts()) : the_post(); ?>
            <?php
            $documents = get_post_meta(get_the_ID(), '_cad_project_documents', true);
            if (!is_array($documents)) {
                $documents = array();
            }
            $documents_title = get_post_meta(get_the_ID(), '_cad_project_documents_title', true);
            if (!is_string($documents_title)) {
                $documents_title = '';
            }
            $documents_title = trim($documents_title);
            if ('' === $documents_title) {
                $documents_title = __('Documentos destacados', 'cad-theme');
            }

            $category_manual = get_post_meta(get_the_ID(), '_cad_project_category', true);
            $client = get_post_meta(get_the_ID(), '_cad_project_client', true);
            $location = get_post_meta(get_the_ID(), '_cad_project_location', true);
            $surface = get_post_meta(get_the_ID(), '_cad_project_surface', true);

            if (function_exists('get_field')) {
                $acf_category = get_field('categoria');
                $acf_client = get_field('mandante');
                $acf_location = get_field('ubicacion');
                $acf_surface = get_field('superficie');

                if (!empty($acf_category)) {
                    $category_manual = $acf_category;
                }
                if (!empty($acf_client)) {
                    $client = $acf_client;
                }
                if (!empty($acf_location)) {
                    $location = $acf_location;
                }
                if (!empty($acf_surface)) {
                    $surface = $acf_surface;
                }
            }

            $gallery_ids = get_post_meta(get_the_ID(), '_cad_project_gallery', true);
            if (!is_array($gallery_ids)) {
                $gallery_ids = array();
            }
            $gallery_ids = array_values(array_filter(array_map('absint', $gallery_ids)));
            $gallery_title = get_post_meta(get_the_ID(), '_cad_project_gallery_title', true);
            if (!is_string($gallery_title)) {
                $gallery_title = '';
            }
            $gallery_title = trim($gallery_title);
            if ('' === $gallery_title) {
                $gallery_title = __('Imagenes del proyecto', 'cad-theme');
            }

            if (function_exists('get_field')) {
                $acf_gallery = get_field('galeria_proyecto');
                if (!empty($acf_gallery)) {
                    $acf_ids = array();
                    foreach ((array) $acf_gallery as $item) {
                        if (is_numeric($item)) {
                            $acf_ids[] = absint($item);
                            continue;
                        }
                        if (is_array($item) && isset($item['ID'])) {
                            $acf_ids[] = absint($item['ID']);
                        }
                    }
                    $acf_ids = array_values(array_filter($acf_ids));
                    if (!empty($acf_ids)) {
                        $gallery_ids = array_values(array_unique(array_merge($gallery_ids, $acf_ids)));
                    }
                }
            }

            $gallery_items = array();
            foreach ($gallery_ids as $gallery_id) {
                $gallery_items[] = array(
                    'type' => 'attachment',
                    'id'   => absint($gallery_id),
                );
            }

            $gallery_total = count($gallery_items);
            $gallery_per_page = 5;
            $gallery_pages = (int) ceil($gallery_total / $gallery_per_page);

            $videos = get_post_meta(get_the_ID(), '_cad_project_videos', true);
            if (!is_array($videos)) {
                $videos = array();
            }
            $videos_title = get_post_meta(get_the_ID(), '_cad_project_videos_title', true);
            if (!is_string($videos_title)) {
                $videos_title = '';
            }
            $videos_title = trim($videos_title);
            if ('' === $videos_title) {
                $videos_title = __('Videos relacionados', 'cad-theme');
            }

            $terms = get_the_terms(get_the_ID(), 'cad_project_category');
            if (!is_array($terms)) {
                $terms = array();
            }

            $category_items = array();
            $category_seen = array();

            foreach ($terms as $term) {
                $term_name = isset($term->name) ? trim((string) $term->name) : '';
                if ('' === $term_name) {
                    continue;
                }

                $key = strtolower($term_name);
                if (isset($category_seen[$key])) {
                    continue;
                }
                $category_seen[$key] = true;

                $term_link = get_term_link($term);
                if (is_wp_error($term_link)) {
                    $term_link = '';
                }

                $category_items[] = array(
                    'label' => $term_name,
                    'url'   => $term_link ? (string) $term_link : '',
                );
            }

            if (!empty($category_manual)) {
                $manual_parts = preg_split('/[,;\n]+/', (string) $category_manual);
                if (is_array($manual_parts)) {
                    foreach ($manual_parts as $manual_part) {
                        $manual_label = trim((string) $manual_part);
                        if ('' === $manual_label) {
                            continue;
                        }
                        $key = strtolower($manual_label);
                        if (isset($category_seen[$key])) {
                            continue;
                        }
                        $category_seen[$key] = true;
                        $category_items[] = array(
                            'label' => $manual_label,
                            'url'   => '',
                        );
                    }
                }
            }

            $excerpt = get_the_excerpt();
            $description = '';
            if (function_exists('get_field')) {
                $acf_description = get_field('descripcion_proyecto');
                if (!empty($acf_description)) {
                    $description = $acf_description;
                }
            }

            $project_year = get_the_date('Y');
            $eyebrow_label = __('Proyecto CAD Ingenieros', 'cad-theme');
            if (is_string($project_year) && preg_match('/^\d{4}$/', $project_year)) {
                $eyebrow_label = sprintf(__('Proyecto CAD Ingenieros · %s', 'cad-theme'), $project_year);
            }
            $projects_anchor_url = home_url('/#proyectos');
            $contact_anchor_url = home_url('/#contacto');
            $project_cta_text = __('Si quieres desarrollar un proyecto similar, conversemos sobre tu caso y próximos objetivos.', 'cad-theme');
            if (function_exists('cad_theme_get_legacy_business_cta_buttons')) {
                $project_cta_buttons = cad_theme_get_legacy_business_cta_buttons(
                    __('Contactar', 'cad-theme'),
                    $contact_anchor_url,
                    __('Ver proyectos', 'cad-theme'),
                    $projects_anchor_url
                );
            } else {
                $project_cta_buttons = array(
                    array(
                        'label'        => __('Contactar', 'cad-theme'),
                        'url'          => $contact_anchor_url,
                        'target_blank' => '0',
                        'style'        => 'solid',
                        'bg_color'     => '',
                        'text_color'   => '',
                        'border_color' => '',
                        'border_radius' => '10px',
                    ),
                    array(
                        'label'        => __('Ver proyectos', 'cad-theme'),
                        'url'          => $projects_anchor_url,
                        'target_blank' => '0',
                        'style'        => 'outline',
                        'bg_color'     => '',
                        'text_color'   => '',
                        'border_color' => '',
                        'border_radius' => '10px',
                    ),
                );
            }

            $assigned_cta_block = function_exists('cad_theme_get_assigned_cta_block_for_post')
                ? cad_theme_get_assigned_cta_block_for_post(get_the_ID())
                : null;
            $assigned_cta_html = '';
            if (function_exists('cad_theme_render_cta_block') && is_array($assigned_cta_block)) {
                $assigned_cta_html = cad_theme_render_cta_block(
                    $assigned_cta_block,
                    array('actions_extra_class' => 'cad-project__cta-actions')
                );
            }

            $meta_items = array();
            if (!empty($category_items)) {
                $meta_items[] = array(
                    'label' => __('Categoria', 'cad-theme'),
                    'type'  => 'tags',
                    'value' => $category_items,
                );
            }
            if (!empty($client)) {
                $meta_items[] = array(
                    'label' => __('Mandante', 'cad-theme'),
                    'type'  => 'text',
                    'value' => (string) $client,
                );
            }
            if (!empty($location)) {
                $meta_items[] = array(
                    'label' => __('Ubicacion', 'cad-theme'),
                    'type'  => 'text',
                    'value' => (string) $location,
                );
            }
            if (!empty($surface)) {
                $meta_items[] = array(
                    'label' => __('Superficie', 'cad-theme'),
                    'type'  => 'text',
                    'value' => (string) $surface,
                );
            }

            $document_items = array();
            foreach ($documents as $document) {
                $doc_url = isset($document['url']) ? esc_url((string) $document['url']) : '';
                if (!$doc_url) {
                    continue;
                }
                $doc_label = isset($document['label']) ? sanitize_text_field((string) $document['label']) : '';
                if (!$doc_label) {
                    $doc_label = wp_basename($doc_url);
                }
                $document_items[] = array(
                    'url'   => $doc_url,
                    'label' => $doc_label,
                );
            }

            $video_items = array();
            foreach ($videos as $video) {
                $video_url = isset($video['url']) ? esc_url((string) $video['url']) : '';
                if (!$video_url) {
                    continue;
                }
                $video_label = isset($video['label']) ? sanitize_text_field((string) $video['label']) : '';
                $embed = wp_oembed_get($video_url);
                if (!$embed && function_exists('cad_theme_get_external_video_embed_html')) {
                    $embed = cad_theme_get_external_video_embed_html($video_url);
                }
                $video_items[] = array(
                    'url'   => $video_url,
                    'label' => $video_label,
                    'embed' => $embed,
                );
            }
            ?>
            <article <?php post_class('cad-project'); ?>>
                <header class="cad-project__header">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="cad-project__hero">
                            <?php the_post_thumbnail('full', array('loading' => 'lazy', 'class' => 'cad-project__hero-image')); ?>
                        </div>
                    <?php endif; ?>
                    <div class="cad-business-area__inner">
                        <div class="cad-project__header-content">
                            <a class="cad-project__back" href="<?php echo esc_url(home_url('/#proyectos')); ?>">
                                <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
                                <?php esc_html_e('Volver', 'cad-theme'); ?>
                            </a>
                            <div class="cad-project__eyebrow">
                                <span aria-hidden="true"></span>
                                <?php echo esc_html($eyebrow_label); ?>
                            </div>
                            <h1 class="cad-project__title"><?php the_title(); ?></h1>
                            <?php if ($excerpt) : ?>
                                <p class="cad-project__excerpt"><?php echo esc_html($excerpt); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>

                <?php if (!empty($meta_items)) : ?>
                    <section class="cad-business-area__section cad-project-page__section cad-project-page__section--meta">
                        <div class="cad-business-area__inner">
                            <aside class="cad-project__meta" aria-label="<?php esc_attr_e('Datos del proyecto', 'cad-theme'); ?>">
                                <?php foreach ($meta_items as $meta_item) : ?>
                                    <div class="cad-project__meta-item">
                                        <span><?php echo esc_html((string) $meta_item['label']); ?></span>
                                        <?php if ('tags' === $meta_item['type']) : ?>
                                            <div class="cad-project__meta-tags">
                                                <?php foreach ((array) $meta_item['value'] as $category_item) : ?>
                                                    <?php if (!empty($category_item['url'])) : ?>
                                                        <a href="<?php echo esc_url((string) $category_item['url']); ?>"><?php echo esc_html((string) $category_item['label']); ?></a>
                                                    <?php else : ?>
                                                        <strong><?php echo esc_html((string) $category_item['label']); ?></strong>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else : ?>
                                            <strong><?php echo esc_html((string) $meta_item['value']); ?></strong>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </aside>
                        </div>
                    </section>
                <?php endif; ?>

                <section class="cad-business-area__section cad-project-page__section cad-project-page__section--content">
                    <div class="cad-business-area__inner">
                        <div class="cad-project__body">
                        <?php if (!empty($document_items)) : ?>
                            <section class="cad-project-block cad-project-block--documents">
                                <div class="cad-project-block__header">
                                    <span class="cad-project-block__kicker"><?php esc_html_e('Documentos', 'cad-theme'); ?></span>
                                    <h2><?php echo esc_html($documents_title); ?></h2>
                                </div>
                                <ul class="cad-project-block__list">
                                    <?php foreach ($document_items as $document_item) : ?>
                                        <li>
                                            <a href="<?php echo esc_url((string) $document_item['url']); ?>" target="_blank" rel="noopener noreferrer">
                                                <?php echo esc_html((string) $document_item['label']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </section>
                        <?php endif; ?>

                        <?php if (!empty($gallery_items)) : ?>
                            <section class="cad-project-block cad-project-block--gallery" id="project-gallery">
                                <div class="cad-project-block__header cad-project-block__header--gallery">
                                    <span class="cad-project-block__kicker"><?php esc_html_e('Galeria', 'cad-theme'); ?></span>
                                    <h2><?php echo esc_html($gallery_title); ?></h2>
                                    <?php if ($gallery_pages > 1) : ?>
                                        <div class="cad-project-block__gallery-controls">
                                            <button type="button" class="cad-gallery-nav cad-gallery-nav--prev" data-gallery-prev aria-label="<?php esc_attr_e('Ver imagenes anteriores', 'cad-theme'); ?>" disabled>
                                                <span aria-hidden="true">&larr;</span>
                                            </button>
                                            <span class="cad-project-block__gallery-status" data-gallery-status aria-live="polite">1 / <?php echo esc_html((string) $gallery_pages); ?></span>
                                            <button type="button" class="cad-gallery-nav cad-gallery-nav--next" data-gallery-next aria-label="<?php esc_attr_e('Ver imagenes siguientes', 'cad-theme'); ?>">
                                                <span aria-hidden="true">&rarr;</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="cad-project-block__gallery" data-project-gallery-grid data-gallery-per-page="<?php echo esc_attr((string) $gallery_per_page); ?>">
                                    <?php foreach ($gallery_items as $index => $gallery_item) : ?>
                                        <?php
                                        $page = (int) floor($index / $gallery_per_page);
                                        $pattern = $index % $gallery_per_page;
                                        $is_hidden = $page > 0;
                                        ?>
                                        <figure
                                            class="cad-project-block__gallery-item"
                                            data-gallery-item
                                            data-page="<?php echo esc_attr((string) $page); ?>"
                                            data-pattern="<?php echo esc_attr((string) $pattern); ?>"
                                            <?php echo $is_hidden ? 'hidden' : ''; ?>
                                        >
                                            <?php if ('attachment' === $gallery_item['type']) : ?>
                                                <?php echo wp_get_attachment_image((int) $gallery_item['id'], 'large', false, array('loading' => 'lazy')); ?>
                                            <?php else : ?>
                                                <img src="<?php echo esc_url((string) $gallery_item['url']); ?>" alt="<?php echo esc_attr((string) $gallery_item['alt']); ?>" loading="lazy">
                                            <?php endif; ?>
                                        </figure>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php else : ?>
                            <section class="cad-project-block cad-project-block--gallery cad-project-block--gallery-empty" id="project-gallery">
                                <div class="cad-project-block__header">
                                    <span class="cad-project-block__kicker"><?php esc_html_e('Galeria', 'cad-theme'); ?></span>
                                    <h2><?php echo esc_html($gallery_title); ?></h2>
                                </div>
                                <div class="cad-project-block__content">
                                    <p><?php esc_html_e('Aun no hay imagenes disponibles para este proyecto.', 'cad-theme'); ?></p>
                                </div>
                            </section>
                        <?php endif; ?>

                        <section class="cad-project-block cad-project-block--description">
                            <div class="cad-project-block__header">
                                <span class="cad-project-block__kicker"><?php esc_html_e('Descripcion', 'cad-theme'); ?></span>
                                <h2><?php esc_html_e('Sobre el proyecto', 'cad-theme'); ?></h2>
                            </div>
                            <div class="cad-project-block__content cad-project-main__copy">
                                <?php if (!empty($description)) : ?>
                                    <?php echo wp_kses_post($description); ?>
                                <?php else : ?>
                                    <?php the_content(); ?>
                                <?php endif; ?>
                            </div>
                        </section>

                        <?php if (!empty($video_items)) : ?>
                            <footer class="cad-project__footer">
                                <section class="cad-project-block">
                                    <div class="cad-project-block__header">
                                        <span class="cad-project-block__kicker"><?php esc_html_e('Videos', 'cad-theme'); ?></span>
                                        <h2><?php echo esc_html($videos_title); ?></h2>
                                    </div>
                                    <div class="cad-project-block__videos">
                                        <?php foreach ($video_items as $video_item) : ?>
                                            <div class="cad-project-block__video">
                                                <?php if (!empty($video_item['label'])) : ?>
                                                    <h3><?php echo esc_html((string) $video_item['label']); ?></h3>
                                                <?php endif; ?>
                                                <?php if (!empty($video_item['embed'])) : ?>
                                                    <div class="cad-project-block__video-embed">
                                                        <?php if (function_exists('cad_theme_sanitize_video_embed_html')) : ?>
                                                            <?php echo cad_theme_sanitize_video_embed_html((string) $video_item['embed']); ?>
                                                        <?php else : ?>
                                                            <?php echo wp_kses_post((string) $video_item['embed']); ?>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else : ?>
                                                    <a href="<?php echo esc_url((string) $video_item['url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html((string) $video_item['url']); ?></a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </section>
                            </footer>
                        <?php endif; ?>

                        </div>
                    </div>
                </section>
            </article>

            <section class="cad-project__cta-section">
                <div class="cad-business-area__inner">
                    <?php if ('' !== $assigned_cta_html) : ?>
                        <?php echo $assigned_cta_html; ?>
                    <?php else : ?>
                        <?php // TODO: retirar este fallback cuando todos los proyectos tengan CTA global asignado. ?>
                        <section class="cad-cta-block">
                            <div class="cad-cta-block__content">
                                <h2><?php esc_html_e('Siguiente paso', 'cad-theme'); ?></h2>
                                <?php if (!empty($project_cta_text)) : ?>
                                    <div class="cad-cta-block__copy">
                                        <?php echo wpautop(esc_html((string) $project_cta_text)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($project_cta_buttons) && is_array($project_cta_buttons)) : ?>
                                <div class="cad-cta-block__actions cad-project__cta-actions">
                                    <?php foreach ($project_cta_buttons as $cta_button) : ?>
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
                                            class="cad-cta-block__button is-<?php echo esc_attr($button_style); ?>"
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
                        </section>
                    <?php endif; ?>
                </div>
            </section>
    <?php endwhile; ?>
</main>

<?php
get_footer();
