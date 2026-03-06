<?php
get_header();

$business_cards = cad_theme_default_business_cards();
$indicator_cards = cad_theme_default_indicators();
$offices = cad_theme_default_offices();
?>

<main id="main-content" class="cad-main">
    <section class="cad-hero">
        <video class="cad-hero__video" autoplay muted loop playsinline data-hero-video>
            <source src="https://ebco.cl/assets/ebco-final-2022-720.mp4" type="video/mp4">
            <source src="https://ebco.cl/assets/ebco-final-2022-720.webm" type="video/webm">
        </video>
        <div class="cad-hero__fallback" style="background-image:url('https://ebco.cl/assets/pages/home/bg-static-video-home.jpg');"></div>
        <div class="cad-hero__overlay"></div>

        <div class="cad-hero__content">
            <h1>
                <span><?php esc_html_e('Creamos espacios para', 'cad-theme'); ?></span>
                <strong><?php esc_html_e('toda una vida', 'cad-theme'); ?></strong>
            </h1>

            <button type="button" class="cad-hero__video-btn" data-video-toggle>
                <?php esc_html_e('Pausar video', 'cad-theme'); ?>
            </button>
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
