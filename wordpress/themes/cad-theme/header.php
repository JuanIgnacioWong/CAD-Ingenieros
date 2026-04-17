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

<?php $mobile_logo_id = (int) get_theme_mod('custom_logo'); ?>
<div class="cad-site" data-site-shell>
    <div class="cad-mobile-bar">
        <a
            class="cad-mobile-bar__brand<?php echo $mobile_logo_id ? ' cad-mobile-bar__brand--logo' : ''; ?>"
            href="<?php echo esc_url(home_url('/')); ?>"
            aria-label="<?php esc_attr_e('Inicio', 'cad-theme'); ?>"
        >
            <?php if ($mobile_logo_id) : ?>
                <?php
                echo wp_get_attachment_image(
                    $mobile_logo_id,
                    'full',
                    false,
                    array(
                        'class'         => 'cad-mobile-bar__logo',
                        'loading'       => 'eager',
                        'decoding'      => 'async',
                        'fetchpriority' => 'high',
                        'alt'           => '',
                    )
                );
                ?>
            <?php else : ?>
                <span class="cad-mobile-bar__brand-mark">CAD</span>
                <span class="cad-mobile-bar__brand-sub"><?php esc_html_e('Ingenieros', 'cad-theme'); ?></span>
            <?php endif; ?>
        </a>
        <button class="cad-mobile-bar__toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="cad-sidebar">
            <span class="cad-mobile-bar__toggle-copy">
                <span class="cad-mobile-bar__toggle-eyebrow"><?php esc_html_e('Navegacion', 'cad-theme'); ?></span>
                <span class="cad-mobile-bar__toggle-label"><?php esc_html_e('Menu', 'cad-theme'); ?></span>
            </span>
            <span class="cad-mobile-bar__toggle-box" aria-hidden="true">
                <span class="cad-mobile-bar__icon"></span>
            </span>
        </button>
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

    </aside>

    <div class="cad-content">
