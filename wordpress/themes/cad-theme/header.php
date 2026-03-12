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
            <a class="cad-sidebar__brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Inicio', 'cad-theme'); ?>">
                <span class="cad-sidebar__brand-mark">CAD</span>
                <span class="cad-sidebar__brand-sub"><?php esc_html_e('Ingenieros', 'cad-theme'); ?></span>
            </a>
        </div>

        <nav class="cad-sidebar__nav" aria-label="<?php esc_attr_e('Menu principal', 'cad-theme'); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'cad-menu',
                    'fallback_cb'    => 'cad_theme_render_default_primary_menu',
                )
            );
            ?>
        </nav>

        <section class="cad-sidebar__business" aria-label="<?php esc_attr_e('Areas de negocio', 'cad-theme'); ?>">
            <h2><?php esc_html_e('Areas de Negocio', 'cad-theme'); ?></h2>
            <ul class="cad-submenu">
                <?php foreach (cad_theme_default_business_cards() as $business_item) : ?>
                    <li class="cad-submenu__item">
                        <a class="cad-submenu__link" href="<?php echo esc_url((string) $business_item['url']); ?>">
                            <?php echo esc_html((string) $business_item['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <div class="cad-sidebar__quick-links">
            <?php foreach (cad_theme_default_footer_links() as $footer_link) : ?>
                <?php
                $new_tab = !empty($footer_link['new']);
                $new_tab_attr = $new_tab ? ' target="_blank" rel="noopener noreferrer"' : '';
                ?>
                <a class="cad-sidebar__quick-link" href="<?php echo esc_url((string) $footer_link['url']); ?>"<?php echo $new_tab_attr; ?>>
                    <?php echo esc_html((string) $footer_link['label']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="cad-sidebar__bottom">
            <a class="cad-join-btn" href="<?php echo esc_url(home_url('/sumate-a-cad/')); ?>">
                <span><?php esc_html_e('Sumate a CAD', 'cad-theme'); ?></span>
            </a>
        </div>
    </aside>

    <div class="cad-content">
