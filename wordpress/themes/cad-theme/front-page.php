<?php
get_header();

$business_cards = cad_theme_default_business_cards();
$indicator_cards = cad_theme_default_indicators();
$offices = cad_theme_default_offices();
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

    <div class="cad-page-nav" data-section-nav>
        <a href="#somos" class="is-active"><?php esc_html_e('CAD', 'cad-theme'); ?></a>
        <a href="#business-areas"><?php esc_html_e('Areas de Negocio', 'cad-theme'); ?></a>
        <a href="#indicadores"><?php esc_html_e('Indicadores', 'cad-theme'); ?></a>
        <a href="#presencia"><?php esc_html_e('Presencia', 'cad-theme'); ?></a>
    </div>

    <section id="somos" class="cad-section cad-section--intro">
        <div class="cad-shell-narrow">
            <p><?php esc_html_e('El respeto a las personas, a la sociedad y al medioambiente es el pilar sobre el cual se cimenta la empresa. En base a esto desarrollamos proyectos y construimos relaciones de largo plazo.', 'cad-theme'); ?></p>
        </div>
    </section>

    <section id="business-areas" class="cad-section">
        <div class="cad-shell-wide">
            <h2 class="cad-title"><?php esc_html_e('Areas de Negocio', 'cad-theme'); ?></h2>

            <div class="cad-business-grid">
                <?php foreach ($business_cards as $business_card) : ?>
                    <article class="cad-business-card <?php echo esc_attr((string) $business_card['tone']); ?>">
                        <div class="cad-business-card__media" style="background-image:url('<?php echo esc_url((string) $business_card['image']); ?>');"></div>
                        <div class="cad-business-card__overlay"></div>
                        <div class="cad-business-card__content">
                            <h3><?php echo esc_html((string) $business_card['title']); ?></h3>
                            <p><?php echo esc_html((string) $business_card['description']); ?></p>
                            <a href="<?php echo esc_url((string) $business_card['url']); ?>"><?php echo esc_html((string) $business_card['cta']); ?></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="indicadores" class="cad-section">
        <div class="cad-shell-wide">
            <h2 class="cad-title"><?php esc_html_e('Indicadores', 'cad-theme'); ?></h2>
            <div class="cad-indicators-grid">
                <?php foreach ($indicator_cards as $indicator_card) : ?>
                    <article class="cad-indicator-card">
                        <p class="cad-indicator-card__label"><?php echo esc_html((string) $indicator_card['label']); ?></p>
                        <p class="cad-indicator-card__value"><?php echo esc_html((string) $indicator_card['value']); ?></p>
                        <p class="cad-indicator-card__period"><?php echo esc_html((string) $indicator_card['period']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="presencia" class="cad-section cad-section--presence">
        <div class="cad-shell-wide">
            <h2 class="cad-title"><?php esc_html_e('Presencia', 'cad-theme'); ?></h2>

            <div class="cad-presence-map" aria-hidden="true">
                <div class="cad-presence-map__stats">
                    <p><?php esc_html_e('Nuestra trayectoria', 'cad-theme'); ?></p>
                    <strong>2024</strong>
                    <p><?php esc_html_e('Proyectos: 1.355', 'cad-theme'); ?></p>
                </div>
                <span class="dot is-1"></span>
                <span class="dot is-2"></span>
                <span class="dot is-3"></span>
                <span class="dot is-4"></span>
                <span class="dot is-5"></span>
                <span class="dot is-6"></span>
                <span class="dot is-7"></span>
                <span class="dot is-8"></span>
            </div>

            <div id="oficinas" class="cad-offices">
                <h3><?php esc_html_e('Nuestras oficinas', 'cad-theme'); ?></h3>
                <div class="cad-offices-grid">
                    <?php foreach ($offices as $office) : ?>
                        <article class="cad-office-card">
                            <h4><?php echo esc_html((string) $office['city']); ?></h4>
                            <address>
                                <?php foreach ((array) $office['address'] as $line) : ?>
                                    <span><?php echo esc_html((string) $line); ?></span>
                                <?php endforeach; ?>
                            </address>
                            <?php if (!empty($office['phone'])) : ?>
                                <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', (string) $office['phone'])); ?>"><?php echo esc_html((string) $office['phone']); ?></a>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
