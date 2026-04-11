<?php
get_header();
?>

<main id="main-content" class="cad-main cad-main--generic cad-project-page">
    <div class="cad-shell-wide">
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
            $category_icon = get_post_meta(get_the_ID(), '_cad_project_category_icon', true);
            $client = get_post_meta(get_the_ID(), '_cad_project_client', true);
            $client_icon = get_post_meta(get_the_ID(), '_cad_project_client_icon', true);
            $location = get_post_meta(get_the_ID(), '_cad_project_location', true);
            $location_icon = get_post_meta(get_the_ID(), '_cad_project_location_icon', true);
            $surface = get_post_meta(get_the_ID(), '_cad_project_surface', true);
            $surface_icon = get_post_meta(get_the_ID(), '_cad_project_surface_icon', true);

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

            $min_gallery_items = 15;
            $gallery_count = count($gallery_items);
            if ($gallery_count < $min_gallery_items) {
                for ($index = $gallery_count; $index < $min_gallery_items; $index++) {
                    $item_number = $index + 1;
                    $label = sprintf(__('Muestra %02d', 'cad-theme'), $item_number);
                    $hue_a = ($item_number * 23) % 360;
                    $hue_b = ($hue_a + 42) % 360;
                    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 960"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="hsl(' . $hue_a . ',70%,84%)"/><stop offset="100%" stop-color="hsl(' . $hue_b . ',72%,68%)"/></linearGradient></defs><rect width="1280" height="960" fill="url(#g)"/><circle cx="240" cy="160" r="180" fill="rgba(255,255,255,0.32)"/><circle cx="1080" cy="860" r="220" fill="rgba(17,24,39,0.08)"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#0f172a" font-family="Arial, sans-serif" font-size="52" font-weight="700">' . esc_html($label) . '</text></svg>';
                    $gallery_items[] = array(
                        'type' => 'placeholder',
                        'url'  => 'data:image/svg+xml;utf8,' . rawurlencode($svg),
                        'alt'  => $label,
                    );
                }
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

            if (function_exists('cad_theme_project_meta_icon_resolve')) {
                $category_icon = cad_theme_project_meta_icon_resolve($category_icon, 'category');
                $client_icon = cad_theme_project_meta_icon_resolve($client_icon, 'client');
                $location_icon = cad_theme_project_meta_icon_resolve($location_icon, 'location');
                $surface_icon = cad_theme_project_meta_icon_resolve($surface_icon, 'surface');
            } else {
                $category_icon = 'category';
                $client_icon = 'business';
                $location_icon = 'location_on';
                $surface_icon = 'square_foot';
            }

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
            ?>
            <article <?php post_class('cad-project'); ?>>
                <header class="cad-project__header">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="cad-project__hero">
                            <?php the_post_thumbnail('full', array('loading' => 'lazy', 'class' => 'cad-project__hero-image')); ?>
                        </div>
                    <?php endif; ?>
                    <div class="cad-project__header-content">
                        <a class="cad-project__back" href="<?php echo esc_url(home_url('/#proyectos')); ?>">
                            <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
                            <?php esc_html_e('Volver', 'cad-theme'); ?>
                        </a>
                        <h1 class="cad-project__title"><?php the_title(); ?></h1>
                        <?php if ($excerpt) : ?>
                            <p class="cad-project__excerpt"><?php echo esc_html($excerpt); ?></p>
                        <?php endif; ?>
                    </div>
                </header>

                <div class="cad-project__layout">
                    <aside class="cad-project__meta">
                        <?php if (!empty($category_items)) : ?>
                            <div class="cad-project-meta-card cad-project-meta-card--full">
                                <span class="cad-project-meta-card__icon material-symbols-outlined" aria-hidden="true"><?php echo esc_html($category_icon); ?></span>
                                <div class="cad-project-meta-card__content">
                                    <span class="cad-project-meta-card__label"><?php esc_html_e('Categoria', 'cad-theme'); ?></span>
                                    <div class="cad-project-meta-card__value cad-project-meta-card__tags">
                                        <?php foreach ($category_items as $category_item) : ?>
                                            <?php if (!empty($category_item['url'])) : ?>
                                                <a href="<?php echo esc_url((string) $category_item['url']); ?>"><?php echo esc_html((string) $category_item['label']); ?></a>
                                            <?php else : ?>
                                                <span><?php echo esc_html((string) $category_item['label']); ?></span>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($client) : ?>
                            <div class="cad-project-meta-card">
                                <span class="cad-project-meta-card__icon material-symbols-outlined" aria-hidden="true"><?php echo esc_html($client_icon); ?></span>
                                <div class="cad-project-meta-card__content">
                                    <span class="cad-project-meta-card__label"><?php esc_html_e('Mandante', 'cad-theme'); ?></span>
                                    <span class="cad-project-meta-card__value"><?php echo esc_html($client); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($location) : ?>
                            <div class="cad-project-meta-card">
                                <span class="cad-project-meta-card__icon material-symbols-outlined" aria-hidden="true"><?php echo esc_html($location_icon); ?></span>
                                <div class="cad-project-meta-card__content">
                                    <span class="cad-project-meta-card__label"><?php esc_html_e('Ubicacion', 'cad-theme'); ?></span>
                                    <span class="cad-project-meta-card__value"><?php echo esc_html($location); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($surface) : ?>
                            <div class="cad-project-meta-card">
                                <span class="cad-project-meta-card__icon material-symbols-outlined" aria-hidden="true"><?php echo esc_html($surface_icon); ?></span>
                                <div class="cad-project-meta-card__content">
                                    <span class="cad-project-meta-card__label"><?php esc_html_e('Superficie', 'cad-theme'); ?></span>
                                    <span class="cad-project-meta-card__value"><?php echo esc_html($surface); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                    </aside>

                    <div class="cad-project__body">
                        <?php if (!empty($documents)) : ?>
                            <section class="cad-project-block cad-project-block--documents">
                                <div class="cad-project-block__header">
                                    <span class="cad-project-block__kicker"><?php esc_html_e('Documentos', 'cad-theme'); ?></span>
                                    <h2><?php echo esc_html($documents_title); ?></h2>
                                </div>
                                <ul class="cad-project-block__list">
                                    <?php foreach ($documents as $document) : ?>
                                        <?php
                                        $doc_url = isset($document['url']) ? esc_url($document['url']) : '';
                                        if (!$doc_url) {
                                            continue;
                                        }
                                        $doc_label = isset($document['label']) ? sanitize_text_field($document['label']) : '';
                                        if (!$doc_label) {
                                            $doc_label = wp_basename($doc_url);
                                        }
                                        ?>
                                        <li>
                                            <a href="<?php echo esc_url($doc_url); ?>" target="_blank" rel="noopener noreferrer">
                                                <?php echo esc_html($doc_label); ?>
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
                                            <span class="cad-project-block__gallery-status" data-gallery-status>1 / <?php echo esc_html((string) $gallery_pages); ?></span>
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
                        <?php endif; ?>

                        <section class="cad-project-main">
                            <span class="cad-project-main__kicker"><?php esc_html_e('Descripcion del proyecto', 'cad-theme'); ?></span>
                            <div class="cad-project-main__copy">
                                <?php if (!empty($description)) : ?>
                                    <?php echo wp_kses_post($description); ?>
                                <?php else : ?>
                                    <?php the_content(); ?>
                                <?php endif; ?>
                            </div>
                        </section>

                        <?php if (!empty($videos)) : ?>
                            <footer class="cad-project__footer">
                                <section class="cad-project-block">
                                    <div class="cad-project-block__header">
                                        <span class="cad-project-block__kicker"><?php esc_html_e('Videos', 'cad-theme'); ?></span>
                                        <h2><?php echo esc_html($videos_title); ?></h2>
                                    </div>
                                    <div class="cad-project-block__videos">
                                        <?php foreach ($videos as $video) : ?>
                                            <?php
                                            $video_url = isset($video['url']) ? esc_url((string) $video['url']) : '';
                                            if (!$video_url) {
                                                continue;
                                            }
                                            $video_label = isset($video['label']) ? sanitize_text_field($video['label']) : '';
                                            $embed = wp_oembed_get($video_url);
                                            if (!$embed && function_exists('cad_theme_get_external_video_embed_html')) {
                                                $embed = cad_theme_get_external_video_embed_html($video_url);
                                            }
                                            ?>
                                            <div class="cad-project-block__video">
                                                <?php if ($video_label) : ?>
                                                    <h3><?php echo esc_html($video_label); ?></h3>
                                                <?php endif; ?>
                                                <?php if ($embed) : ?>
                                                    <div class="cad-project-block__video-embed">
                                                        <?php if (function_exists('cad_theme_sanitize_video_embed_html')) : ?>
                                                            <?php echo cad_theme_sanitize_video_embed_html($embed); ?>
                                                        <?php else : ?>
                                                            <?php echo wp_kses_post($embed); ?>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else : ?>
                                                    <a href="<?php echo esc_url($video_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($video_url); ?></a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </section>
                            </footer>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
