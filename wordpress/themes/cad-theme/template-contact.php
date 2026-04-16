<?php
/**
 * Template Name: Contacto Institucional
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$settings = cad_theme_contact_get_settings();
$state = cad_theme_contact_get_form_state();
$public_contact = cad_theme_contact_get_public_contact_data();
$whatsapp_url = cad_theme_contact_get_whatsapp_url();
$page_intro = cad_theme_contact_format_rich_text(isset($settings['page_intro']) ? $settings['page_intro'] : '');
$sidebar_text = cad_theme_contact_format_rich_text(isset($settings['sidebar_text']) ? $settings['sidebar_text'] : '');
?>

<main id="main-content" class="cad-main cad-contact-page">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $page_content = get_the_content();
        $page_content = trim((string) $page_content);
        ?>
        <section class="cad-section cad-section--intro cad-contact-page__hero">
            <div class="cad-shell-wide">
                <div class="cad-contact-page__hero-inner">
                    <?php if (!empty($settings['page_eyebrow'])) : ?>
                        <span class="cad-business-area__kicker"><?php echo esc_html((string) $settings['page_eyebrow']); ?></span>
                    <?php endif; ?>
                    <h1 class="cad-title"><?php the_title(); ?></h1>
                    <?php if ($page_intro) : ?>
                        <div class="cad-section__lead"><?php echo $page_intro; ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="cad-section cad-contact-page__content">
            <div class="cad-shell-wide">
                <div class="cad-contact-page__layout">
                    <aside class="cad-contact-page__sidebar">
                        <article class="cad-business-area__service-card cad-contact-page__panel">
                            <?php if (!empty($settings['sidebar_title'])) : ?>
                                <h2 class="cad-contact-page__panel-title"><?php echo esc_html((string) $settings['sidebar_title']); ?></h2>
                            <?php endif; ?>

                            <?php if ($sidebar_text) : ?>
                                <div class="cad-contact-page__panel-copy"><?php echo $sidebar_text; ?></div>
                            <?php endif; ?>

                            <?php if ('' !== $page_content) : ?>
                                <div class="cad-contact-page__panel-copy">
                                    <?php echo apply_filters('the_content', $page_content); ?>
                                </div>
                            <?php endif; ?>
                        </article>

                        <div class="cad-contact-page__info-grid">
                            <?php if (!empty($public_contact['email'])) : ?>
                                <article class="cad-business-area__service-card cad-contact-page__info-card">
                                    <span class="material-symbols-outlined cad-contact-page__info-icon" aria-hidden="true">mail</span>
                                    <span class="cad-contact-page__info-label"><?php esc_html_e('Correo', 'cad-theme'); ?></span>
                                    <a href="<?php echo esc_url((string) $public_contact['email_url']); ?>"><?php echo esc_html(antispambot((string) $public_contact['email'])); ?></a>
                                </article>
                            <?php endif; ?>

                            <?php if (!empty($public_contact['phone'])) : ?>
                                <article class="cad-business-area__service-card cad-contact-page__info-card">
                                    <span class="material-symbols-outlined cad-contact-page__info-icon" aria-hidden="true">call</span>
                                    <span class="cad-contact-page__info-label"><?php esc_html_e('Telefono', 'cad-theme'); ?></span>
                                    <?php if (!empty($public_contact['phone_url'])) : ?>
                                        <a href="<?php echo esc_url((string) $public_contact['phone_url']); ?>"><?php echo esc_html((string) $public_contact['phone']); ?></a>
                                    <?php else : ?>
                                        <strong><?php echo esc_html((string) $public_contact['phone']); ?></strong>
                                    <?php endif; ?>
                                </article>
                            <?php endif; ?>

                            <?php if (!empty($public_contact['address_lines'])) : ?>
                                <article class="cad-business-area__service-card cad-contact-page__info-card">
                                    <span class="material-symbols-outlined cad-contact-page__info-icon" aria-hidden="true">location_on</span>
                                    <span class="cad-contact-page__info-label"><?php esc_html_e('Direccion', 'cad-theme'); ?></span>
                                    <div class="cad-contact-page__stack">
                                        <?php foreach ($public_contact['address_lines'] as $line) : ?>
                                            <span><?php echo esc_html((string) $line); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </article>
                            <?php endif; ?>

                            <?php if (!empty($public_contact['hours_lines'])) : ?>
                                <article class="cad-business-area__service-card cad-contact-page__info-card">
                                    <span class="material-symbols-outlined cad-contact-page__info-icon" aria-hidden="true">schedule</span>
                                    <span class="cad-contact-page__info-label"><?php esc_html_e('Horarios', 'cad-theme'); ?></span>
                                    <div class="cad-contact-page__stack">
                                        <?php foreach ($public_contact['hours_lines'] as $line) : ?>
                                            <span><?php echo esc_html((string) $line); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </article>
                            <?php endif; ?>
                        </div>

                        <?php if ($whatsapp_url) : ?>
                            <article class="cad-business-area__service-card cad-contact-page__whatsapp">
                                <span class="cad-contact-page__info-label"><?php esc_html_e('Canal complementario', 'cad-theme'); ?></span>
                                <h2 class="cad-contact-page__panel-title"><?php esc_html_e('WhatsApp Business', 'cad-theme'); ?></h2>
                                <p><?php esc_html_e('Para coordinaciones iniciales o un contacto rapido, abre el canal directo de WhatsApp sin reemplazar el formulario institucional.', 'cad-theme'); ?></p>
                                <a class="cad-business-area__cta-primary cad-contact-page__whatsapp-button" href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php echo esc_html((string) $settings['whatsapp_button_label']); ?>
                                </a>
                            </article>
                        <?php endif; ?>
                    </aside>

                    <section class="cad-business-area__service-card cad-contact-page__form-panel" aria-labelledby="cad-contact-form-title">
                        <div class="cad-contact-page__form-header">
                            <span class="cad-business-area__kicker"><?php esc_html_e('Formulario de contacto', 'cad-theme'); ?></span>
                            <h2 id="cad-contact-form-title"><?php esc_html_e('Escribenos', 'cad-theme'); ?></h2>
                            <p><?php esc_html_e('Todos los campos son obligatorios. El formulario valida en frontend y backend para asegurar un envio consistente.', 'cad-theme'); ?></p>
                        </div>

                        <?php cad_theme_contact_render_form(get_the_ID(), $state); ?>
                    </section>
                </div>
            </div>
        </section>
    <?php endwhile; ?>
</main>

<?php
get_footer();
