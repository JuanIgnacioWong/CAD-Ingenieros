<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('ebco-body'); ?>>
<?php wp_body_open(); ?>
<a class="ebco-skip-link" href="#main-content"><?php esc_html_e('Saltar al contenido', 'cad-theme'); ?></a>

<div class="ebco-site" data-site-shell>
    <div class="ebco-mobile-bar">
        <button class="ebco-mobile-bar__toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="ebco-sidebar">
            <span class="ebco-mobile-bar__icon" aria-hidden="true"></span>
            <span><?php esc_html_e('Menu', 'cad-theme'); ?></span>
        </button>
        <a class="ebco-mobile-bar__brand" href="<?php echo esc_url(home_url('/')); ?>">EBCO</a>
    </div>

    <div class="ebco-overlay" data-menu-overlay></div>

    <aside id="ebco-sidebar" class="ebco-sidebar" data-menu-panel>
        <div class="ebco-sidebar__brand-wrap">
            <a class="ebco-sidebar__brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Inicio', 'cad-theme'); ?>">
                <span class="ebco-sidebar__brand-mark">EBCO</span>
                <span class="ebco-sidebar__brand-sub"><?php esc_html_e('Grupo', 'cad-theme'); ?></span>
            </a>
        </div>

        <nav class="ebco-sidebar__nav" aria-label="<?php esc_attr_e('Menu principal', 'cad-theme'); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'ebco-menu',
                    'fallback_cb'    => 'cad_theme_render_default_primary_menu',
                )
            );
            ?>
        </nav>

        <section class="ebco-sidebar__business" aria-label="<?php esc_attr_e('Areas de negocio', 'cad-theme'); ?>">
            <h2><?php esc_html_e('Areas de Negocio', 'cad-theme'); ?></h2>
            <ul class="ebco-submenu">
                <?php foreach (cad_theme_default_business_cards() as $business_item) : ?>
                    <li class="ebco-submenu__item">
                        <a class="ebco-submenu__link" href="<?php echo esc_url((string) $business_item['url']); ?>">
                            <?php echo esc_html((string) $business_item['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <div class="ebco-sidebar__quick-links">
            <?php foreach (cad_theme_default_footer_links() as $footer_link) : ?>
                <?php
                $new_tab = !empty($footer_link['new']);
                $new_tab_attr = $new_tab ? ' target="_blank" rel="noopener noreferrer"' : '';
                ?>
                <a class="ebco-sidebar__quick-link" href="<?php echo esc_url((string) $footer_link['url']); ?>"<?php echo $new_tab_attr; ?>>
                    <?php echo esc_html((string) $footer_link['label']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="ebco-sidebar__bottom">
            <a class="ebco-join-btn" href="<?php echo esc_url(home_url('/sumate-a-ebco/')); ?>">
                <span><?php esc_html_e('Sumate a EBCO', 'cad-theme'); ?></span>
            </a>
        </div>
    </aside>

    <div class="ebco-content">
