<?php

if (!defined('ABSPATH')) {
    exit;
}

function cad_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));

    register_nav_menus(
        array(
            'primary' => __('Main menu', 'cad-theme'),
            'footer'  => __('Footer menu', 'cad-theme'),
        )
    );
}
add_action('after_setup_theme', 'cad_theme_setup');

function cad_theme_assets()
{
    $version = wp_get_theme()->get('Version');

    wp_enqueue_style(
        'cad-theme-fonts',
        'https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&family=Barlow:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'cad-theme-main',
        get_template_directory_uri() . '/assets/css/main.css',
        array('cad-theme-fonts'),
        $version
    );

    wp_enqueue_script(
        'cad-theme-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        $version,
        true
    );
}
add_action('wp_enqueue_scripts', 'cad_theme_assets');

function cad_theme_default_main_menu()
{
    return array(
        array(
            'label' => __('Inicio', 'cad-theme'),
            'url'   => home_url('/'),
        ),
        array(
            'label' => __('Gobierno corporativo', 'cad-theme'),
            'url'   => home_url('/gobierno-corporativo/'),
        ),
    );
}

function cad_theme_default_business_cards()
{
    return array(
        array(
            'title'       => __('Construccion', 'cad-theme'),
            'description' => __('Hemos edificado importantes obras, de gran impacto para el pais, las empresas y sus comunidades.', 'cad-theme'),
            'url'         => home_url('/proyectos/construccion/'),
            'cta'         => __('Ver proyectos', 'cad-theme'),
            'image'       => 'https://cad.cl/assets/pages/home/cad-areas-negocio-construccion-v3.jpg',
            'tone'        => 'is-blue',
        ),
        array(
            'title'       => __('Inmobiliaria', 'cad-theme'),
            'description' => __('Proyectos inmobiliarios destinados a comercializacion de casas y edificacion para viviendas, oficinas comerciales y renta.', 'cad-theme'),
            'url'         => home_url('/proyectos/inmobiliaria/'),
            'cta'         => __('Ver proyectos', 'cad-theme'),
            'image'       => 'https://cad.cl/assets/pages/home/cad-bg-area-negocio-inmobiliaria-v2.jpg',
            'tone'        => 'is-indigo',
        ),
        array(
            'title'       => __('Servicios', 'cad-theme'),
            'description' => __('Orientados a entregar servicios vinculados al sector, buscando maximizar la eficiencia en la gestion de proyectos.', 'cad-theme'),
            'url'         => home_url('/proyectos/servicios/'),
            'cta'         => __('Ver servicios', 'cad-theme'),
            'image'       => 'https://cad.cl/assets/pages/home/cad-areas-negocio-servicios.jpg',
            'tone'        => 'is-slate',
        ),
    );
}

function cad_theme_default_indicators()
{
    return array(
        array(
            'label'  => __('N de proyectos', 'cad-theme'),
            'value'  => '119',
            'period' => __('Abril 2025', 'cad-theme'),
        ),
        array(
            'label'  => __('Trabajadores', 'cad-theme'),
            'value'  => '6.531',
            'period' => __('Mayo 2025', 'cad-theme'),
        ),
    );
}

function cad_theme_default_offices()
{
    return array(
        array(
            'city'    => 'Iquique',
            'address' => array('Santiago Polanco 2075', 'Iquique'),
            'phone'   => '',
        ),
        array(
            'city'    => 'Antofagasta',
            'address' => array('Eduardo Orchard 1438', 'Antofagasta'),
            'phone'   => '224596586',
        ),
        array(
            'city'    => 'Santiago',
            'address' => array('Av. Santa Maria 2450 Providencia'),
            'phone'   => '+56 2 24644700',
        ),
        array(
            'city'    => 'Concepcion',
            'address' => array('Camino a Coronel Km 10 Numero 5580', 'San Pedro de La Paz'),
            'phone'   => '+56 41 2738421',
        ),
        array(
            'city'    => 'Puerto Montt',
            'address' => array('Ruta 5 Sur Km 1025', 'Megacentro 2 Bodega 1'),
            'phone'   => '+56 65 2636868',
        ),
        array(
            'city'    => 'Punta Arenas',
            'address' => array('Calle Mapuche 440'),
            'phone'   => '+56 9 3230 5260',
        ),
    );
}

function cad_theme_default_footer_links()
{
    return array(
        array(
            'label' => __('Contactanos', 'cad-theme'),
            'url'   => home_url('/contacto/'),
        ),
        array(
            'label' => __('Canal de denuncias', 'cad-theme'),
            'url'   => home_url('/canal-de-denuncias/'),
        ),
        array(
            'label' => __('Acceso proveedores', 'cad-theme'),
            'url'   => 'http://proveedores.cad.cl/',
            'new'   => true,
        ),
    );
}

function cad_theme_render_default_primary_menu($args = array())
{
    unset($args);
    $items = cad_theme_default_main_menu();

    echo '<ul class="cad-menu">';
    foreach ($items as $item) {
        $url = isset($item['url']) ? (string) $item['url'] : '#';
        $label = isset($item['label']) ? (string) $item['label'] : '';

        echo '<li class="cad-menu__item">';
        echo '<a class="cad-menu__link" href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
        echo '</li>';
    }
    echo '</ul>';
}

function cad_theme_render_default_footer_menu($args = array())
{
    unset($args);
    $items = cad_theme_default_footer_links();

    echo '<ul class="cad-footer-links">';
    foreach ($items as $item) {
        $url = isset($item['url']) ? (string) $item['url'] : '#';
        $label = isset($item['label']) ? (string) $item['label'] : '';
        $new = !empty($item['new']);
        $target = $new ? ' target="_blank" rel="noopener noreferrer"' : '';

        echo '<li class="cad-footer-links__item">';
        echo '<a class="cad-footer-links__link" href="' . esc_url($url) . '"' . $target . '>' . esc_html($label) . '</a>';
        echo '</li>';
    }
    echo '</ul>';
}
