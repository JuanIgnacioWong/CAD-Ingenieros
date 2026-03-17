<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('cad-body'); ?>>
<?php wp_body_open(); ?>
<a class="cad-skip-link" href="#main-content"><?php esc_html_e('Saltar al contenido', 'cad-theme'); ?></a>

<div class="cad-site" data-site-shell>
    <div class="cad-mobile-bar">
        <button class="cad-mobile-bar__toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="cad-sidebar">
            <span class="cad-mobile-bar__icon" aria-hidden="true"></span>
            <span><?php esc_html_e('Menu', 'cad-theme'); ?></span>
        </button>
        <a class="cad-mobile-bar__brand" href="<?php echo esc_url(home_url('/')); ?>">CAD</a>
    </div>

    <div class="cad-overlay" data-menu-overlay></div>

    <aside id="cad-sidebar" class="cad-sidebar" data-menu-panel>
        <div class="cad-sidebar__brand-wrap">
            <?php if (has_custom_logo()) : ?>
                <div class="cad-sidebar__brand cad-sidebar__brand--logo">
                    <?php the_custom_logo(); ?>
                </div>
            <?php else : ?>
                <a class="cad-sidebar__brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Inicio', 'cad-theme'); ?>">
                    <span class="cad-sidebar__brand-mark">CAD</span>
                    <span class="cad-sidebar__brand-sub"><?php esc_html_e('Ingenieros', 'cad-theme'); ?></span>
                </a>
            <?php endif; ?>
        </div>

        <nav class="cad-sidebar__nav" aria-label="<?php esc_attr_e('Menu principal', 'cad-theme'); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'cad-menu',
                    'submenu_class'  => 'cad-submenu',
                    'depth'          => 2,
                    'fallback_cb'    => 'cad_theme_render_default_primary_menu',
                )
            );
            ?>
        </nav>

        <nav class="cad-sidebar__quick-links" aria-label="<?php esc_attr_e('Menu secundario', 'cad-theme'); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'secondary',
                    'container'      => false,
                    'menu_class'     => 'cad-sidebar__quick-links-list',
                    'depth'          => 1,
                    'fallback_cb'    => 'cad_theme_render_default_secondary_menu',
                )
            );
            ?>
        </nav>

        <div class="cad-sidebar__bottom">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'cta',
                    'container'      => false,
                    'menu_class'     => 'cad-join-menu',
                    'depth'          => 1,
                    'fallback_cb'    => 'cad_theme_render_default_cta_menu',
                )
            );
            ?>
        </div>
    </aside>

    <div class="cad-content">
