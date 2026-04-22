<?php
get_header();

$business_cards = cad_theme_get_business_cards();
$business_count = is_array($business_cards) ? count($business_cards) : 0;
$business_grid_class = 'cad-business-grid';
if ($business_count > 0 && $business_count < 4) {
    $business_grid_class .= ' cad-business-grid--' . $business_count;
}
$indicator_cards = cad_theme_get_indicator_cards();
$indicator_count = is_array($indicator_cards) ? count($indicator_cards) : 0;
$indicator_layout_count = max(1, min(3, $indicator_count));
$indicator_left_class = 'cad-indicators-left cad-indicators-count-' . $indicator_layout_count;
$indicator_section_title = cad_theme_get_indicator_section_title();
$indicator_section_content = cad_theme_get_indicator_section_content();
$indicator_section_content = preg_replace('/(<(?:li|p)[^>]*>\s*)(?:&#x2714;|&#10004;|&#10003;|✔|✓|•)\s*/u', '$1', (string) $indicator_section_content);
$indicator_logo_id = (int) get_theme_mod('custom_logo');
$indicator_logo_html = '';
if ($indicator_logo_id) {
    $indicator_logo_html = wp_get_attachment_image(
        $indicator_logo_id,
        'full',
        false,
        array(
            'class'    => 'cad-indicators-logo',
            'loading'  => 'lazy',
            'decoding' => 'async',
            'alt'      => __('CAD Ingenieros', 'cad-theme'),
        )
    );
}
$clients = cad_theme_get_clients();
$projects_query = new WP_Query(
    array(
        'post_type'      => 'cad_project',
        'posts_per_page' => 12,
        'post_status'    => 'publish',
        'orderby'        => array(
            'menu_order' => 'ASC',
            'date'       => 'DESC',
        ),
        'no_found_rows'  => true,
    )
);
$business_section_content = cad_theme_get_business_section_content();
$home_intro_content = cad_theme_get_home_intro_content();
$video_banner = cad_theme_get_video_banner();
$youtube_embed = $video_banner['show_video'] ? cad_theme_get_youtube_embed_url($video_banner['youtube']) : '';
$mp4_src = $video_banner['show_mp4'] ? $video_banner['mp4'] : '';
$webm_src = $video_banner['show_webm'] ? $video_banner['webm'] : '';
$has_video = $video_banner['show_video'] && (!empty($youtube_embed) || !empty($mp4_src) || !empty($webm_src));
$hero_class = $has_video ? 'cad-hero' : 'cad-hero is-video-paused';
?>

<main id="main-content" class="cad-main">
    <section class="<?php echo esc_attr($hero_class); ?>">
        <?php if ($has_video && !empty($youtube_embed)) : ?>
            <iframe
                class="cad-hero__video cad-hero__video--embed"
                src="<?php echo esc_url($youtube_embed); ?>"
                data-hero-video
                data-video-src="<?php echo esc_url($youtube_embed); ?>"
                title="<?php esc_attr_e('Video principal', 'cad-theme'); ?>"
                frameborder="0"
                allow="autoplay; encrypted-media; picture-in-picture"
                allowfullscreen
            ></iframe>
        <?php elseif ($has_video) : ?>
            <video class="cad-hero__video" autoplay muted loop playsinline data-hero-video>
                <?php if (!empty($mp4_src)) : ?>
                    <source src="<?php echo esc_url($mp4_src); ?>" type="video/mp4">
                <?php endif; ?>
                <?php if (!empty($webm_src)) : ?>
                    <source src="<?php echo esc_url($webm_src); ?>" type="video/webm">
                <?php endif; ?>
            </video>
        <?php endif; ?>
        <?php if (!empty($video_banner['show_fallback'])) : ?>
            <div class="cad-hero__fallback" style="background-image:url('<?php echo esc_url($video_banner['fallback']); ?>');"></div>
        <?php endif; ?>
        <div class="cad-hero__overlay"></div>

        <div class="cad-hero__content">
            <?php if (!empty($video_banner['show_headline'])) : ?>
                <h1>
                    <span><?php echo esc_html($video_banner['headline_1']); ?></span>
                    <strong><?php echo esc_html($video_banner['headline_2']); ?></strong>
                </h1>
            <?php endif; ?>

            <?php if (!empty($video_banner['show_button']) && $has_video) : ?>
                <button type="button" class="cad-hero__video-btn" data-video-toggle data-label-play="<?php echo esc_attr($video_banner['label_play']); ?>" data-label-pause="<?php echo esc_attr($video_banner['label_pause']); ?>">
                    <?php echo esc_html($video_banner['label_pause']); ?>
                </button>
            <?php endif; ?>
        </div>
    </section>

    <nav class="cad-page-nav" data-section-nav aria-label="<?php esc_attr_e('Secciones de la pagina', 'cad-theme'); ?>">
        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'page_nav',
                'container'      => false,
                'menu_class'     => 'cad-page-nav__list',
                'depth'          => 1,
                'fallback_cb'    => 'cad_theme_render_default_page_nav',
            )
        );
        ?>
    </nav>

    <section id="somos" class="cad-section cad-section--intro">
        <div class="cad-shell-narrow">
            <?php echo $home_intro_content; ?>
        </div>
    </section>

    <section id="business-areas" class="cad-section">
        <div class="cad-shell-wide">
            <h2 class="cad-title"><?php echo esc_html(cad_theme_get_business_section_title()); ?></h2>
            <?php if (!empty($business_section_content)) : ?>
                <div class="cad-section__lead"><?php echo $business_section_content; ?></div>
            <?php endif; ?>

            <div class="cad-business-carousel" data-business-carousel>
                <div class="cad-business-carousel__viewport">
                    <div class="<?php echo esc_attr($business_grid_class); ?>" data-business-track>
                        <?php foreach ($business_cards as $business_card) : ?>
                            <article class="cad-business-card <?php echo esc_attr((string) $business_card['tone']); ?>">
                                <div class="cad-business-card__media" style="background-image:url('<?php echo esc_url((string) $business_card['image']); ?>');"></div>
                                <div class="cad-business-card__overlay"></div>
                                <div class="cad-business-card__content">
                                    <h3><?php echo esc_html((string) $business_card['title']); ?></h3>
                                    <?php if (!empty($business_card['description'])) : ?>
                                        <?php echo $business_card['description']; ?>
                                    <?php endif; ?>
                                    <?php if (!empty($business_card['cta'])) : ?>
                                        <a class="cad-business-card__cta" href="<?php echo esc_url((string) $business_card['url']); ?>"><?php echo esc_html((string) $business_card['cta']); ?></a>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php if ($business_count > 1) : ?>
                    <div class="cad-business-carousel__pagination" data-business-pagination></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="indicadores" class="cad-section cad-indicators-split">
        <div class="cad-shell-wide">
            <div class="cad-indicators-layout">
                <div class="<?php echo esc_attr($indicator_left_class); ?>">
                    <?php foreach ($indicator_cards as $indicator_card) : ?>
                        <?php
                        $indicator_period = isset($indicator_card['period']) ? trim((string) $indicator_card['period']) : '';
                        $indicator_card_class = 'cad-indicator-card';
                        $indicator_value = isset($indicator_card['value']) ? (string) $indicator_card['value'] : '';
                        $indicator_count_value = '';
                        $indicator_count_prefix = '';
                        $indicator_count_suffix = '';
                        $indicator_count_separator = '';
                        if ('' === $indicator_period) {
                            $indicator_card_class .= ' is-no-period';
                        }

                        if (preg_match('/^(.*?)(\d[\d\.,]*)(.*)$/u', $indicator_value, $indicator_matches)) {
                            $indicator_count_prefix = trim((string) $indicator_matches[1]);
                            $indicator_count_suffix = trim((string) $indicator_matches[3]);
                            $indicator_numeric_token = (string) $indicator_matches[2];
                            $indicator_digits_only = preg_replace('/\D+/', '', $indicator_numeric_token);

                            if (is_string($indicator_digits_only) && '' !== $indicator_digits_only) {
                                $indicator_count_value = $indicator_digits_only;

                                if (false !== strpos($indicator_numeric_token, '.')) {
                                    $indicator_count_separator = '.';
                                } elseif (false !== strpos($indicator_numeric_token, ',')) {
                                    $indicator_count_separator = ',';
                                }
                            }
                        }
                        ?>
                        <article class="<?php echo esc_attr($indicator_card_class); ?>">
                            <p class="cad-indicator-card__label"><?php echo esc_html((string) $indicator_card['label']); ?></p>
                            <p
                                class="cad-indicator-card__value"
                                <?php if ('' !== $indicator_count_value) : ?>
                                    data-count="<?php echo esc_attr($indicator_count_value); ?>"
                                    <?php if ('' !== $indicator_count_prefix) : ?>
                                        data-prefix="<?php echo esc_attr($indicator_count_prefix); ?>"
                                    <?php endif; ?>
                                    <?php if ('' !== $indicator_count_suffix) : ?>
                                        data-suffix="<?php echo esc_attr($indicator_count_suffix); ?>"
                                    <?php endif; ?>
                                    <?php if ('' !== $indicator_count_separator) : ?>
                                        data-separator="<?php echo esc_attr($indicator_count_separator); ?>"
                                    <?php endif; ?>
                                <?php endif; ?>
                            ><?php echo esc_html($indicator_value); ?></p>
                            <?php if ('' !== $indicator_period) : ?>
                                <p class="cad-indicator-card__period"><?php echo esc_html($indicator_period); ?></p>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="cad-indicators-right">
                    <div class="cad-indicators-hero">
                        <div>
                            <div class="cad-indicators-eyebrow"><?php esc_html_e('CAD Ingenieros', 'cad-theme'); ?></div>
                            <h2 class="cad-title"><?php echo esc_html($indicator_section_title); ?></h2>
                            <?php if ('' !== trim(wp_strip_all_tags($indicator_section_content))) : ?>
                                <div class="cad-indicators-copy">
                                    <?php echo wp_kses_post(wpautop($indicator_section_content)); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($indicator_logo_html) : ?>
                            <div class="cad-indicators-footer">
                                <?php echo $indicator_logo_html; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="proyectos" class="cad-section cad-section--projects">
        <div class="cad-shell-wide">
            <span id="presencia" class="cad-anchor-legacy" aria-hidden="true"></span>
            <h2 class="cad-title"><?php esc_html_e('Proyectos', 'cad-theme'); ?></h2>

            <?php if ($projects_query->have_posts()) : ?>
                <div class="cad-projects-carousel" data-projects-carousel>
                    <button type="button" class="cad-projects-carousel__nav" data-projects-prev aria-label="<?php esc_attr_e('Ver proyectos anteriores', 'cad-theme'); ?>">
                        <span class="material-symbols-outlined" aria-hidden="true">chevron_left</span>
                    </button>
                    <div class="cad-projects-carousel__viewport">
                        <div class="cad-projects-carousel__track" data-projects-track>
                            <?php while ($projects_query->have_posts()) : $projects_query->the_post(); ?>
                                <?php
                                $excerpt = get_the_excerpt();
                                $excerpt = $excerpt ? wp_trim_words($excerpt, 18) : '';
                                $thumb_html = get_the_post_thumbnail(get_the_ID(), 'large', array(
                                    'class'   => 'cad-project-card__image',
                                    'loading' => 'lazy',
                                ));
                                ?>
                                <a class="cad-project-card" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                                    <div class="cad-project-card__media">
                                        <?php if ($thumb_html) : ?>
                                            <?php echo $thumb_html; ?>
                                        <?php else : ?>
                                            <div class="cad-project-card__placeholder"></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="cad-project-card__overlay"></div>
                                    <div class="cad-project-card__content">
                                        <h3 class="cad-project-card__title"><?php the_title(); ?></h3>
                                        <?php if ($excerpt) : ?>
                                            <p class="cad-project-card__excerpt"><?php echo esc_html($excerpt); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <button type="button" class="cad-projects-carousel__nav" data-projects-next aria-label="<?php esc_attr_e('Ver proyectos siguientes', 'cad-theme'); ?>">
                        <span class="material-symbols-outlined" aria-hidden="true">chevron_right</span>
                    </button>
                </div>
            <?php else : ?>
                <p class="cad-projects-empty"><?php esc_html_e('Pronto compartiremos nuevos proyectos destacados.', 'cad-theme'); ?></p>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>

            <div id="clientes" class="cad-clients">
                <h3><?php esc_html_e('Clientes', 'cad-theme'); ?></h3>
                <?php if (!empty($clients)) : ?>
                    <div class="cad-clients-carousel" data-clients-carousel>
                        <button type="button" class="cad-clients-carousel__nav" data-clients-prev aria-label="<?php esc_attr_e('Ver clientes anteriores', 'cad-theme'); ?>">
                            <span class="material-symbols-outlined" aria-hidden="true">chevron_left</span>
                        </button>
                        <div class="cad-clients-carousel__viewport">
                            <div class="cad-clients-carousel__track" data-clients-track>
                                <?php foreach ($clients as $client) : ?>
                                    <?php
                                    $logo_html = wp_get_attachment_image(
                                        (int) $client['logo_id'],
                                        'medium',
                                        false,
                                        array(
                                            'class'    => 'cad-client-card__logo',
                                            'loading'  => 'lazy',
                                            'decoding' => 'async',
                                            'alt'      => (string) $client['alt'],
                                        )
                                    );
                                    ?>
                                    <article class="cad-client-card" aria-label="<?php echo esc_attr((string) $client['name']); ?>">
                                        <?php if ($logo_html) : ?>
                                            <?php echo $logo_html; ?>
                                        <?php endif; ?>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button type="button" class="cad-clients-carousel__nav" data-clients-next aria-label="<?php esc_attr_e('Ver clientes siguientes', 'cad-theme'); ?>">
                            <span class="material-symbols-outlined" aria-hidden="true">chevron_right</span>
                        </button>
                    </div>
                <?php else : ?>
                    <p class="cad-clients-empty"><?php esc_html_e('Agrega clientes desde el panel para mostrar sus logos aqui.', 'cad-theme'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
