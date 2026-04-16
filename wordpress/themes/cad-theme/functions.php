<?php

if (!defined('ABSPATH')) {
    exit;
}

function cad_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array('height' => 120, 'width' => 260, 'flex-height' => true, 'flex-width' => true));
    add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));

    register_nav_menus(
        array(
            'primary'   => __('Main menu', 'cad-theme'),
            'secondary' => __('Secondary menu', 'cad-theme'),
            'page_nav'  => __('Page section menu', 'cad-theme'),
            'footer'    => __('Footer menu', 'cad-theme'),
        )
    );
}
add_action('after_setup_theme', 'cad_theme_setup');

function cad_theme_register_video_banner_cpt()
{
    $labels = array(
        'name'               => __('Video Banner', 'cad-theme'),
        'singular_name'      => __('Video Banner', 'cad-theme'),
        'add_new'            => __('Agregar', 'cad-theme'),
        'add_new_item'       => __('Agregar banner', 'cad-theme'),
        'edit_item'          => __('Editar banner', 'cad-theme'),
        'new_item'           => __('Nuevo banner', 'cad-theme'),
        'view_item'          => __('Ver banner', 'cad-theme'),
        'search_items'       => __('Buscar banner', 'cad-theme'),
        'not_found'          => __('Sin banners', 'cad-theme'),
        'not_found_in_trash' => __('Sin banners', 'cad-theme'),
        'menu_name'          => __('Video Banner', 'cad-theme'),
    );

    register_post_type(
        'cad_video_banner',
        array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-format-video',
            'supports'           => array('title'),
            'has_archive'        => false,
            'exclude_from_search'=> true,
            'show_in_rest'       => true,
        )
    );
}
add_action('init', 'cad_theme_register_video_banner_cpt');

function cad_theme_register_home_intro_cpt()
{
    $labels = array(
        'name'               => __('Texto Somos', 'cad-theme'),
        'singular_name'      => __('Texto Somos', 'cad-theme'),
        'add_new'            => __('Agregar', 'cad-theme'),
        'add_new_item'       => __('Agregar texto', 'cad-theme'),
        'edit_item'          => __('Editar texto', 'cad-theme'),
        'new_item'           => __('Nuevo texto', 'cad-theme'),
        'view_item'          => __('Ver texto', 'cad-theme'),
        'search_items'       => __('Buscar texto', 'cad-theme'),
        'not_found'          => __('Sin textos', 'cad-theme'),
        'not_found_in_trash' => __('Sin textos', 'cad-theme'),
        'menu_name'          => __('Texto Somos', 'cad-theme'),
    );

    register_post_type(
        'cad_home_intro',
        array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_position'      => 21,
            'menu_icon'          => 'dashicons-edit',
            'supports'           => array('title', 'editor', 'revisions'),
            'has_archive'        => false,
            'exclude_from_search'=> true,
            'publicly_queryable' => false,
            'rewrite'            => false,
            'show_in_rest'       => true,
        )
    );
}
add_action('init', 'cad_theme_register_home_intro_cpt');

function cad_theme_register_business_areas_cpt()
{
    $labels = array(
        'name'               => __('Areas negocio', 'cad-theme'),
        'singular_name'      => __('Area negocio', 'cad-theme'),
        'add_new'            => __('Agregar', 'cad-theme'),
        'add_new_item'       => __('Agregar area', 'cad-theme'),
        'edit_item'          => __('Editar area', 'cad-theme'),
        'new_item'           => __('Nueva area', 'cad-theme'),
        'view_item'          => __('Ver area', 'cad-theme'),
        'search_items'       => __('Buscar areas', 'cad-theme'),
        'not_found'          => __('Sin areas', 'cad-theme'),
        'not_found_in_trash' => __('Sin areas', 'cad-theme'),
        'menu_name'          => __('Areas negocio', 'cad-theme'),
    );

    register_post_type(
        'cad_business_area',
        array(
            'labels'             => $labels,
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_position'      => 22,
            'menu_icon'          => 'dashicons-portfolio',
            'supports'           => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions'),
            'has_archive'        => false,
            'exclude_from_search'=> false,
            'publicly_queryable' => true,
            'rewrite'            => array(
                'slug'       => 'areas-de-negocio',
                'with_front' => false,
            ),
            'show_in_rest'       => true,
        )
    );
}
add_action('init', 'cad_theme_register_business_areas_cpt');

function cad_theme_maybe_flush_rewrite_rules()
{
    if (get_option('cad_rewrite_rules_flushed')) {
        return;
    }

    flush_rewrite_rules(false);
    update_option('cad_rewrite_rules_flushed', 1);
}
add_action('init', 'cad_theme_maybe_flush_rewrite_rules', 99);

function cad_theme_register_business_section_cpt()
{
    $labels = array(
        'name'               => __('Titulo seccion areas', 'cad-theme'),
        'singular_name'      => __('Titulo seccion areas', 'cad-theme'),
        'add_new'            => __('Agregar', 'cad-theme'),
        'add_new_item'       => __('Agregar seccion', 'cad-theme'),
        'edit_item'          => __('Editar seccion', 'cad-theme'),
        'new_item'           => __('Nueva seccion', 'cad-theme'),
        'view_item'          => __('Ver seccion', 'cad-theme'),
        'search_items'       => __('Buscar seccion', 'cad-theme'),
        'not_found'          => __('Sin seccion', 'cad-theme'),
        'not_found_in_trash' => __('Sin seccion', 'cad-theme'),
        'menu_name'          => __('Titulo seccion areas', 'cad-theme'),
    );

    register_post_type(
        'cad_business_section',
        array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => 'edit.php?post_type=cad_business_area',
            'menu_position'      => 23,
            'menu_icon'          => 'dashicons-editor-textcolor',
            'supports'           => array('title', 'editor', 'revisions'),
            'has_archive'        => false,
            'exclude_from_search'=> true,
            'publicly_queryable' => false,
            'rewrite'            => false,
            'show_in_rest'       => true,
        )
    );
}
add_action('init', 'cad_theme_register_business_section_cpt');

function cad_theme_register_indicator_section_cpt()
{
    $labels = array(
        'name'               => __('Titulo seccion indicadores', 'cad-theme'),
        'singular_name'      => __('Titulo seccion indicadores', 'cad-theme'),
        'add_new'            => __('Agregar', 'cad-theme'),
        'add_new_item'       => __('Agregar seccion', 'cad-theme'),
        'edit_item'          => __('Editar seccion', 'cad-theme'),
        'new_item'           => __('Nueva seccion', 'cad-theme'),
        'view_item'          => __('Ver seccion', 'cad-theme'),
        'search_items'       => __('Buscar seccion', 'cad-theme'),
        'not_found'          => __('Sin seccion', 'cad-theme'),
        'not_found_in_trash' => __('Sin seccion', 'cad-theme'),
        'menu_name'          => __('Titulo seccion indicadores', 'cad-theme'),
    );

    register_post_type(
        'cad_indicator_sec',
        array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => 'edit.php?post_type=cad_indicator',
            'menu_position'      => 24,
            'menu_icon'          => 'dashicons-chart-area',
            'supports'           => array('title', 'editor', 'revisions'),
            'has_archive'        => false,
            'exclude_from_search'=> true,
            'publicly_queryable' => false,
            'rewrite'            => false,
            'show_in_rest'       => true,
        )
    );
}
add_action('init', 'cad_theme_register_indicator_section_cpt');

function cad_theme_migrate_indicator_section_post_type()
{
    $old_migrated = get_option('cad_indicator_section_migrated');
    if ($old_migrated && !get_option('cad_indicator_sec_migrated')) {
        update_option('cad_indicator_sec_migrated', 1);
        delete_option('cad_indicator_section_migrated');
    }

    if (get_option('cad_indicator_sec_migrated')) {
        return;
    }

    global $wpdb;

    $old_type = 'cad_indicator_section';
    $new_type = 'cad_indicator_sec';

    if ($old_type === $new_type) {
        update_option('cad_indicator_sec_migrated', 1);
        return;
    }

    $ids = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s",
            $old_type
        )
    );

    if (!empty($ids)) {
        $wpdb->update(
            $wpdb->posts,
            array('post_type' => $new_type),
            array('post_type' => $old_type)
        );

        foreach ($ids as $id) {
            clean_post_cache((int) $id);
        }
    }

    update_option('cad_indicator_sec_migrated', 1);
}
add_action('admin_init', 'cad_theme_migrate_indicator_section_post_type');

function cad_theme_migrate_indicator_section_options()
{
    $old_seeded = get_option('cad_indicator_section_seeded');
    if ($old_seeded && !get_option('cad_indicator_sec_seeded')) {
        update_option('cad_indicator_sec_seeded', 1);
        delete_option('cad_indicator_section_seeded');
    }
}
add_action('admin_init', 'cad_theme_migrate_indicator_section_options');

function cad_theme_register_indicator_cpt()
{
    $labels = array(
        'name'               => __('Indicadores', 'cad-theme'),
        'singular_name'      => __('Indicador', 'cad-theme'),
        'add_new'            => __('Agregar', 'cad-theme'),
        'add_new_item'       => __('Agregar indicador', 'cad-theme'),
        'edit_item'          => __('Editar indicador', 'cad-theme'),
        'new_item'           => __('Nuevo indicador', 'cad-theme'),
        'view_item'          => __('Ver indicador', 'cad-theme'),
        'search_items'       => __('Buscar indicador', 'cad-theme'),
        'not_found'          => __('Sin indicadores', 'cad-theme'),
        'not_found_in_trash' => __('Sin indicadores', 'cad-theme'),
        'menu_name'          => __('Indicadores', 'cad-theme'),
    );

    register_post_type(
        'cad_indicator',
        array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_position'      => 25,
            'menu_icon'          => 'dashicons-chart-bar',
            'supports'           => array('title', 'page-attributes', 'revisions'),
            'has_archive'        => false,
            'exclude_from_search'=> true,
            'publicly_queryable' => false,
            'rewrite'            => false,
            'show_in_rest'       => true,
        )
    );
}
add_action('init', 'cad_theme_register_indicator_cpt');

function cad_theme_register_project_cpt()
{
    $labels = array(
        'name'               => __('Proyectos', 'cad-theme'),
        'singular_name'      => __('Proyecto', 'cad-theme'),
        'add_new'            => __('Agregar', 'cad-theme'),
        'add_new_item'       => __('Agregar proyecto', 'cad-theme'),
        'edit_item'          => __('Editar proyecto', 'cad-theme'),
        'new_item'           => __('Nuevo proyecto', 'cad-theme'),
        'view_item'          => __('Ver proyecto', 'cad-theme'),
        'search_items'       => __('Buscar proyectos', 'cad-theme'),
        'not_found'          => __('Sin proyectos', 'cad-theme'),
        'not_found_in_trash' => __('Sin proyectos', 'cad-theme'),
        'menu_name'          => __('Proyectos', 'cad-theme'),
    );

    register_post_type(
        'cad_project',
        array(
            'labels'             => $labels,
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_position'      => 26,
            'menu_icon'          => 'dashicons-portfolio',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'revisions'),
            'has_archive'        => false,
            'exclude_from_search'=> false,
            'publicly_queryable' => true,
            'rewrite'            => array('slug' => 'proyectos'),
            'show_in_rest'       => true,
        )
    );
}
add_action('init', 'cad_theme_register_project_cpt');

function cad_theme_register_client_cpt()
{
    $labels = array(
        'name'               => __('Clientes', 'cad-theme'),
        'singular_name'      => __('Cliente', 'cad-theme'),
        'add_new'            => __('Agregar', 'cad-theme'),
        'add_new_item'       => __('Agregar cliente', 'cad-theme'),
        'edit_item'          => __('Editar cliente', 'cad-theme'),
        'new_item'           => __('Nuevo cliente', 'cad-theme'),
        'view_item'          => __('Ver cliente', 'cad-theme'),
        'search_items'       => __('Buscar clientes', 'cad-theme'),
        'not_found'          => __('Sin clientes', 'cad-theme'),
        'not_found_in_trash' => __('Sin clientes', 'cad-theme'),
        'menu_name'          => __('Clientes', 'cad-theme'),
    );

    register_post_type(
        'cad_client',
        array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_position'      => 27,
            'menu_icon'          => 'dashicons-groups',
            'supports'           => array('title', 'thumbnail', 'page-attributes', 'revisions'),
            'has_archive'        => false,
            'exclude_from_search'=> true,
            'publicly_queryable' => false,
            'rewrite'            => false,
            'show_in_rest'       => true,
        )
    );
}
add_action('init', 'cad_theme_register_client_cpt');

function cad_theme_register_footer_contact_cpt()
{
    $labels = array(
        'name'               => __('Footer contacto', 'cad-theme'),
        'singular_name'      => __('Footer contacto', 'cad-theme'),
        'add_new'            => __('Agregar', 'cad-theme'),
        'add_new_item'       => __('Agregar footer', 'cad-theme'),
        'edit_item'          => __('Editar footer', 'cad-theme'),
        'new_item'           => __('Nuevo footer', 'cad-theme'),
        'view_item'          => __('Ver footer', 'cad-theme'),
        'search_items'       => __('Buscar footer', 'cad-theme'),
        'not_found'          => __('Sin footer', 'cad-theme'),
        'not_found_in_trash' => __('Sin footer', 'cad-theme'),
        'menu_name'          => __('Footer contacto', 'cad-theme'),
    );

    register_post_type(
        'cad_footer_contact',
        array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_position'      => 28,
            'menu_icon'          => 'dashicons-location-alt',
            'supports'           => array('title', 'revisions'),
            'has_archive'        => false,
            'exclude_from_search'=> true,
            'publicly_queryable' => false,
            'rewrite'            => false,
            'show_in_rest'       => true,
        )
    );
}
add_action('init', 'cad_theme_register_footer_contact_cpt');

function cad_theme_register_project_taxonomy()
{
    $labels = array(
        'name'              => __('Categorias de proyecto', 'cad-theme'),
        'singular_name'     => __('Categoria de proyecto', 'cad-theme'),
        'search_items'      => __('Buscar categorias', 'cad-theme'),
        'all_items'         => __('Todas las categorias', 'cad-theme'),
        'edit_item'         => __('Editar categoria', 'cad-theme'),
        'update_item'       => __('Actualizar categoria', 'cad-theme'),
        'add_new_item'      => __('Agregar categoria', 'cad-theme'),
        'new_item_name'     => __('Nueva categoria', 'cad-theme'),
        'menu_name'         => __('Categorias', 'cad-theme'),
    );

    register_taxonomy(
        'cad_project_category',
        array('cad_project'),
        array(
            'labels'            => $labels,
            'public'            => true,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'hierarchical'      => false,
            'rewrite'           => array('slug' => 'categoria-proyecto'),
        )
    );
}
add_action('init', 'cad_theme_register_project_taxonomy');

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
        'cad-theme-icons',
        'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0',
        array(),
        null
    );

    wp_enqueue_style(
        'cad-theme-main',
        get_template_directory_uri() . '/assets/css/main.css',
        array('cad-theme-fonts', 'cad-theme-icons'),
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

// Login branding is loaded through hooks so it survives WordPress core updates.
function cad_theme_login_company_name()
{
    $company_name = trim((string) wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES));

    if ('' !== $company_name) {
        return $company_name;
    }

    return 'CAD';
}

function cad_theme_get_login_logo_url()
{
    $logo_id = (int) get_theme_mod('custom_logo');

    if (!$logo_id) {
        return '';
    }

    $logo_url = wp_get_attachment_image_url($logo_id, 'full');

    return $logo_url ? esc_url_raw($logo_url) : '';
}

function cad_theme_get_login_background_image_url()
{
    if (!function_exists('cad_theme_get_video_banner')) {
        return '';
    }

    $video_banner = cad_theme_get_video_banner();
    if (!is_array($video_banner)) {
        return '';
    }

    $background_image = isset($video_banner['fallback']) ? esc_url_raw((string) $video_banner['fallback']) : '';
    if ('' === $background_image || empty($video_banner['show_fallback'])) {
        return '';
    }

    $defaults = function_exists('cad_theme_video_banner_defaults') ? cad_theme_video_banner_defaults() : array();
    $default_background = isset($defaults['fallback']) ? esc_url_raw((string) $defaults['fallback']) : '';

    if ('' !== $default_background && $background_image === $default_background) {
        return '';
    }

    return $background_image;
}

function cad_theme_get_login_branding()
{
    return array(
        'company_name'         => cad_theme_login_company_name(),
        'logo_url'             => cad_theme_get_login_logo_url(),
        'background_image_url' => cad_theme_get_login_background_image_url(),
    );
}

function cad_theme_login_body_class($classes)
{
    $branding = cad_theme_get_login_branding();
    $classes[] = 'cad-login';
    $classes[] = $branding['logo_url'] ? 'cad-login--has-custom-logo' : 'cad-login--text-logo';

    if ($branding['background_image_url']) {
        $classes[] = 'cad-login--has-background-image';
    }

    return array_values(array_unique($classes));
}
add_filter('login_body_class', 'cad_theme_login_body_class');

function cad_theme_login_assets()
{
    $version = wp_get_theme()->get('Version');
    $branding = cad_theme_get_login_branding();

    wp_enqueue_style(
        'cad-theme-login-fonts',
        'https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&family=Barlow:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'cad-theme-login',
        get_theme_file_uri('/assets/css/login.css'),
        array('login', 'dashicons', 'cad-theme-login-fonts'),
        $version
    );

    $inline_css = implode(
        '',
        array(
            ':root{',
            '--cad-login-bg:#171b21;',
            '--cad-login-bg-soft:#364150;',
            '--cad-login-panel:#fff3ea;',
            '--cad-login-panel-strong:rgba(255,255,255,0.95);',
            '--cad-login-text:#2f261f;',
            '--cad-login-muted:#7f6a58;',
            '--cad-login-line:rgba(255,255,255,0.14);',
            '--cad-login-line-strong:rgba(47,38,31,0.16);',
            '--cad-login-accent:#ef8432;',
            '--cad-login-accent-hover:#d96f1e;',
            '--cad-login-accent-soft:#f4b483;',
            '}',
        )
    );

    if ($branding['logo_url']) {
        $inline_css .= sprintf(
            '.login h1 a{background-image:url("%s") !important;}',
            esc_url_raw($branding['logo_url'])
        );
    }

    if ($branding['background_image_url']) {
        $inline_css .= sprintf(
            'body.login::before{background-image:url("%s");}',
            esc_url_raw($branding['background_image_url'])
        );
    }

    wp_add_inline_style('cad-theme-login', $inline_css);
}
add_action('login_enqueue_scripts', 'cad_theme_login_assets');

function cad_theme_login_header_url()
{
    return home_url('/');
}
add_filter('login_headerurl', 'cad_theme_login_header_url');

function cad_theme_login_header_text()
{
    return cad_theme_login_company_name();
}
add_filter('login_headertext', 'cad_theme_login_header_text');

function cad_theme_login_message($message)
{
    $intro = sprintf(
        '<p class="cad-login__intro">%s</p>',
        esc_html(
            sprintf(
                __('Acceso privado de %s. Usa tus credenciales corporativas para continuar.', 'cad-theme'),
                cad_theme_login_company_name()
            )
        )
    );

    return $message . $intro;
}
add_filter('login_message', 'cad_theme_login_message');

function cad_theme_login_footer_note()
{
    printf(
        '<p class="cad-login__footer">%s</p>',
        esc_html(
            sprintf(
                __('%1$s | %2$s', 'cad-theme'),
                cad_theme_login_company_name(),
                wp_date('Y')
            )
        )
    );
}
add_action('login_footer', 'cad_theme_login_footer_note');

function cad_theme_video_banner_defaults()
{
    return array(
        'mp4'         => 'https://ebco.cl/assets/ebco-final-2022-720.mp4',
        'webm'        => 'https://ebco.cl/assets/ebco-final-2022-720.webm',
        'fallback'    => 'https://ebco.cl/assets/pages/home/bg-static-video-home.jpg',
        'youtube'     => '',
        'headline_1'  => __('Creamos espacios para', 'cad-theme'),
        'headline_2'  => __('toda una vida', 'cad-theme'),
        'label_play'  => __('Activar video', 'cad-theme'),
        'label_pause' => __('Pausar video', 'cad-theme'),
        'show_video'  => true,
        'show_mp4'    => true,
        'show_webm'   => true,
        'show_fallback' => true,
        'show_headline' => true,
        'show_button' => true,
    );
}

function cad_theme_video_banner_meta_fields()
{
    return array(
        'mp4'        => '_cad_video_mp4',
        'webm'       => '_cad_video_webm',
        'fallback'   => '_cad_video_fallback',
        'youtube'    => '_cad_video_youtube',
        'headline_1' => '_cad_video_headline_1',
        'headline_2' => '_cad_video_headline_2',
        'label_play' => '_cad_video_label_play',
        'label_pause'=> '_cad_video_label_pause',
        'show_video' => '_cad_video_show_video',
        'show_mp4'   => '_cad_video_show_mp4',
        'show_webm'  => '_cad_video_show_webm',
        'show_fallback' => '_cad_video_show_fallback',
        'show_headline' => '_cad_video_show_headline',
        'show_button'=> '_cad_video_show_button',
    );
}

function cad_theme_add_video_banner_metabox()
{
    add_meta_box(
        'cad-video-banner-meta',
        __('Contenido del Video Banner', 'cad-theme'),
        'cad_theme_render_video_banner_metabox',
        'cad_video_banner',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'cad_theme_add_video_banner_metabox');

function cad_theme_render_video_banner_metabox($post)
{
    $defaults = cad_theme_video_banner_defaults();
    $fields = cad_theme_video_banner_meta_fields();
    $mp4 = get_post_meta($post->ID, $fields['mp4'], true);
    $webm = get_post_meta($post->ID, $fields['webm'], true);
    $fallback = get_post_meta($post->ID, $fields['fallback'], true);
    $youtube = get_post_meta($post->ID, $fields['youtube'], true);
    $headline_1 = get_post_meta($post->ID, $fields['headline_1'], true);
    $headline_2 = get_post_meta($post->ID, $fields['headline_2'], true);
    $label_play = get_post_meta($post->ID, $fields['label_play'], true);
    $label_pause = get_post_meta($post->ID, $fields['label_pause'], true);
    $show_video = get_post_meta($post->ID, $fields['show_video'], true);
    $show_mp4 = get_post_meta($post->ID, $fields['show_mp4'], true);
    $show_webm = get_post_meta($post->ID, $fields['show_webm'], true);
    $show_fallback = get_post_meta($post->ID, $fields['show_fallback'], true);
    $show_headline = get_post_meta($post->ID, $fields['show_headline'], true);
    $show_button = get_post_meta($post->ID, $fields['show_button'], true);

    if ('' === $mp4) {
        $mp4 = $defaults['mp4'];
    }
    if ('' === $webm) {
        $webm = $defaults['webm'];
    }
    if ('' === $fallback) {
        $fallback = $defaults['fallback'];
    }
    if ('' === $youtube) {
        $youtube = $defaults['youtube'];
    }
    if ('' === $headline_1) {
        $headline_1 = $defaults['headline_1'];
    }
    if ('' === $headline_2) {
        $headline_2 = $defaults['headline_2'];
    }
    if ('' === $label_play) {
        $label_play = __('Activar video', 'cad-theme');
    }
    if ('' === $label_pause) {
        $label_pause = __('Pausar video', 'cad-theme');
    }
    if ('' === $show_video) {
        $show_video = '1';
    }
    if ('' === $show_mp4) {
        $show_mp4 = '1';
    }
    if ('' === $show_webm) {
        $show_webm = '1';
    }
    if ('' === $show_fallback) {
        $show_fallback = '1';
    }
    if ('' === $show_headline) {
        $show_headline = '1';
    }
    if ('' === $show_button) {
        $show_button = '1';
    }

    wp_nonce_field('cad_video_banner_meta', 'cad_video_banner_meta_nonce');
    ?>
    <div class="cad-video-banner-field">
        <label for="cad-video-mp4"><?php esc_html_e('Video MP4 (URL o biblioteca)', 'cad-theme'); ?></label>
        <div class="cad-video-banner-field__row">
            <input type="text" class="widefat" id="cad-video-mp4" name="cad_video_mp4" value="<?php echo esc_attr($mp4); ?>" placeholder="<?php esc_attr_e('URL del video MP4', 'cad-theme'); ?>">
            <button type="button" class="button cad-media-select" data-media-target="cad-video-mp4" data-media-type="video"><?php esc_html_e('Biblioteca', 'cad-theme'); ?></button>
        </div>
        <label>
            <input type="checkbox" name="cad_video_show_mp4" value="1" <?php checked($show_mp4, '1'); ?>>
            <?php esc_html_e('Mostrar', 'cad-theme'); ?>
        </label>
    </div>

    <div class="cad-video-banner-field">
        <label for="cad-video-webm"><?php esc_html_e('Video WEBM (URL o biblioteca)', 'cad-theme'); ?></label>
        <div class="cad-video-banner-field__row">
            <input type="text" class="widefat" id="cad-video-webm" name="cad_video_webm" value="<?php echo esc_attr($webm); ?>" placeholder="<?php esc_attr_e('URL del video WEBM', 'cad-theme'); ?>">
            <button type="button" class="button cad-media-select" data-media-target="cad-video-webm" data-media-type="video"><?php esc_html_e('Biblioteca', 'cad-theme'); ?></button>
        </div>
        <label>
            <input type="checkbox" name="cad_video_show_webm" value="1" <?php checked($show_webm, '1'); ?>>
            <?php esc_html_e('Mostrar', 'cad-theme'); ?>
        </label>
    </div>

    <div class="cad-video-banner-field">
        <label for="cad-video-fallback"><?php esc_html_e('Imagen de respaldo (URL o biblioteca)', 'cad-theme'); ?></label>
        <div class="cad-video-banner-field__row">
            <input type="text" class="widefat" id="cad-video-fallback" name="cad_video_fallback" value="<?php echo esc_attr($fallback); ?>" placeholder="<?php esc_attr_e('URL de la imagen', 'cad-theme'); ?>">
            <button type="button" class="button cad-media-select" data-media-target="cad-video-fallback" data-media-type="image"><?php esc_html_e('Biblioteca', 'cad-theme'); ?></button>
        </div>
        <label>
            <input type="checkbox" name="cad_video_show_fallback" value="1" <?php checked($show_fallback, '1'); ?>>
            <?php esc_html_e('Mostrar', 'cad-theme'); ?>
        </label>
    </div>

    <div class="cad-video-banner-field">
        <label for="cad-video-youtube"><?php esc_html_e('Video YouTube (URL)', 'cad-theme'); ?></label>
        <input type="text" class="widefat" id="cad-video-youtube" name="cad_video_youtube" value="<?php echo esc_attr($youtube); ?>" placeholder="<?php esc_attr_e('Ej: https://www.youtube.com/watch?v=ID', 'cad-theme'); ?>">
        <p class="description"><?php esc_html_e('Si completas esta URL, se usará YouTube en lugar de MP4/WEBM.', 'cad-theme'); ?></p>
    </div>

    <div class="cad-video-banner-field cad-video-banner-field--inline">
        <label for="cad-video-headline-1"><?php esc_html_e('Titulo linea 1', 'cad-theme'); ?></label>
        <input type="text" class="widefat" id="cad-video-headline-1" name="cad_video_headline_1" value="<?php echo esc_attr($headline_1); ?>">
    </div>

    <div class="cad-video-banner-field cad-video-banner-field--inline">
        <label for="cad-video-headline-2"><?php esc_html_e('Titulo linea 2', 'cad-theme'); ?></label>
        <input type="text" class="widefat" id="cad-video-headline-2" name="cad_video_headline_2" value="<?php echo esc_attr($headline_2); ?>">
    </div>

    <div class="cad-video-banner-field cad-video-banner-field--inline">
        <label for="cad-video-label-play"><?php esc_html_e('Texto botón (Activar video)', 'cad-theme'); ?></label>
        <input type="text" class="widefat" id="cad-video-label-play" name="cad_video_label_play" value="<?php echo esc_attr($label_play); ?>">
    </div>

    <div class="cad-video-banner-field cad-video-banner-field--inline">
        <label for="cad-video-label-pause"><?php esc_html_e('Texto botón (Pausar video)', 'cad-theme'); ?></label>
        <input type="text" class="widefat" id="cad-video-label-pause" name="cad_video_label_pause" value="<?php echo esc_attr($label_pause); ?>">
    </div>

    <div class="cad-video-banner-field cad-video-banner-field--inline">
        <label>
            <input type="checkbox" name="cad_video_show_button" value="1" <?php checked($show_button, '1'); ?>>
            <?php esc_html_e('Mostrar botón de video', 'cad-theme'); ?>
        </label>
    </div>

    <div class="cad-video-banner-field cad-video-banner-field--inline">
        <label>
            <input type="checkbox" name="cad_video_show_video" value="1" <?php checked($show_video, '1'); ?>>
            <?php esc_html_e('Mostrar video', 'cad-theme'); ?>
        </label>
    </div>

    <div class="cad-video-banner-field cad-video-banner-field--inline">
        <label>
            <input type="checkbox" name="cad_video_show_headline" value="1" <?php checked($show_headline, '1'); ?>>
            <?php esc_html_e('Mostrar titulo', 'cad-theme'); ?>
        </label>
    </div>
    <?php
}

function cad_theme_save_video_banner_meta($post_id)
{
    if (!isset($_POST['cad_video_banner_meta_nonce']) || !wp_verify_nonce($_POST['cad_video_banner_meta_nonce'], 'cad_video_banner_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = cad_theme_video_banner_meta_fields();

    update_post_meta($post_id, $fields['mp4'], isset($_POST['cad_video_mp4']) ? esc_url_raw(wp_unslash($_POST['cad_video_mp4'])) : '');
    update_post_meta($post_id, $fields['webm'], isset($_POST['cad_video_webm']) ? esc_url_raw(wp_unslash($_POST['cad_video_webm'])) : '');
    update_post_meta($post_id, $fields['fallback'], isset($_POST['cad_video_fallback']) ? esc_url_raw(wp_unslash($_POST['cad_video_fallback'])) : '');
    update_post_meta($post_id, $fields['youtube'], isset($_POST['cad_video_youtube']) ? esc_url_raw(wp_unslash($_POST['cad_video_youtube'])) : '');
    update_post_meta($post_id, $fields['headline_1'], isset($_POST['cad_video_headline_1']) ? sanitize_text_field(wp_unslash($_POST['cad_video_headline_1'])) : '');
    update_post_meta($post_id, $fields['headline_2'], isset($_POST['cad_video_headline_2']) ? sanitize_text_field(wp_unslash($_POST['cad_video_headline_2'])) : '');
    update_post_meta($post_id, $fields['label_play'], isset($_POST['cad_video_label_play']) ? sanitize_text_field(wp_unslash($_POST['cad_video_label_play'])) : '');
    update_post_meta($post_id, $fields['label_pause'], isset($_POST['cad_video_label_pause']) ? sanitize_text_field(wp_unslash($_POST['cad_video_label_pause'])) : '');
    update_post_meta($post_id, $fields['show_video'], !empty($_POST['cad_video_show_video']) ? '1' : '0');
    update_post_meta($post_id, $fields['show_mp4'], !empty($_POST['cad_video_show_mp4']) ? '1' : '0');
    update_post_meta($post_id, $fields['show_webm'], !empty($_POST['cad_video_show_webm']) ? '1' : '0');
    update_post_meta($post_id, $fields['show_fallback'], !empty($_POST['cad_video_show_fallback']) ? '1' : '0');
    update_post_meta($post_id, $fields['show_headline'], !empty($_POST['cad_video_show_headline']) ? '1' : '0');
    update_post_meta($post_id, $fields['show_button'], !empty($_POST['cad_video_show_button']) ? '1' : '0');
}
add_action('save_post_cad_video_banner', 'cad_theme_save_video_banner_meta');

function cad_theme_admin_video_banner_assets($hook)
{
    if ('post.php' !== $hook && 'post-new.php' !== $hook) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || 'cad_video_banner' !== $screen->post_type) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_style(
        'cad-theme-video-banner-admin',
        get_template_directory_uri() . '/assets/css/admin-video-banner.css',
        array(),
        wp_get_theme()->get('Version')
    );
    wp_enqueue_script(
        'cad-theme-video-banner-admin',
        get_template_directory_uri() . '/assets/js/admin-video-banner.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('admin_enqueue_scripts', 'cad_theme_admin_video_banner_assets');

function cad_theme_admin_project_assets($hook)
{
    if ('post.php' !== $hook && 'post-new.php' !== $hook) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || !in_array($screen->post_type, array('cad_project', 'cad_business_area'), true)) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_style(
        'cad-theme-icons-admin',
        'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0',
        array(),
        null
    );
    wp_enqueue_style(
        'cad-theme-project-admin',
        get_template_directory_uri() . '/assets/css/admin-projects.css',
        array('cad-theme-icons-admin'),
        wp_get_theme()->get('Version')
    );
    wp_enqueue_script(
        'cad-theme-project-admin',
        get_template_directory_uri() . '/assets/js/admin-projects.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('admin_enqueue_scripts', 'cad_theme_admin_project_assets');

function cad_theme_admin_footer_contact_assets($hook)
{
    if ('post.php' !== $hook && 'post-new.php' !== $hook) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || 'cad_footer_contact' !== $screen->post_type) {
        return;
    }

    wp_enqueue_style(
        'cad-theme-footer-contact-admin',
        get_template_directory_uri() . '/assets/css/admin-projects.css',
        array(),
        wp_get_theme()->get('Version')
    );
    wp_enqueue_script(
        'cad-theme-footer-contact-admin',
        get_template_directory_uri() . '/assets/js/admin-projects.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('admin_enqueue_scripts', 'cad_theme_admin_footer_contact_assets');

function cad_theme_client_logo_requirement_message()
{
    return __('Subir únicamente logos en PNG o imágenes con fondo transparente para asegurar una correcta visualización en blanco y hover a color.', 'cad-theme');
}

function cad_theme_client_logo_invalid_notice_message()
{
    return __('Se quitó el logo seleccionado porque no es un PNG válido para la sección Clientes.', 'cad-theme');
}

function cad_theme_client_logo_notice_transient_key()
{
    $user_id = get_current_user_id();
    return 'cad_client_logo_invalid_notice_' . absint($user_id);
}

function cad_theme_is_client_admin_screen()
{
    if (!is_admin() || !function_exists('get_current_screen')) {
        return false;
    }

    $screen = get_current_screen();
    if (!$screen) {
        return false;
    }

    if ('cad_client' === $screen->post_type) {
        return true;
    }

    return false;
}

function cad_theme_client_admin_notices()
{
    if (!cad_theme_is_client_admin_screen()) {
        return;
    }
    ?>
    <div class="notice notice-info">
        <p><strong><?php echo esc_html(cad_theme_client_logo_requirement_message()); ?></strong></p>
    </div>
    <?php

    $notice_key = cad_theme_client_logo_notice_transient_key();
    if (!get_transient($notice_key)) {
        return;
    }

    delete_transient($notice_key);
    ?>
    <div class="notice notice-error">
        <p><?php echo esc_html(cad_theme_client_logo_invalid_notice_message()); ?></p>
    </div>
    <?php
}
add_action('admin_notices', 'cad_theme_client_admin_notices');

function cad_theme_client_featured_image_hint($content, $post_id, $thumbnail_id)
{
    unset($thumbnail_id);
    if ('cad_client' !== get_post_type($post_id)) {
        return $content;
    }

    $hint = '<p style="margin-top:8px;font-weight:600;">' . esc_html(cad_theme_client_logo_requirement_message()) . '</p>';
    return $content . $hint;
}
add_filter('admin_post_thumbnail_html', 'cad_theme_client_featured_image_hint', 10, 3);

function cad_theme_validate_client_logo_upload($file)
{
    if (empty($_REQUEST['post_id'])) {
        return $file;
    }

    $post_id = absint($_REQUEST['post_id']);
    if (!$post_id || 'cad_client' !== get_post_type($post_id)) {
        return $file;
    }

    $type = wp_check_filetype_and_ext($file['tmp_name'], $file['name']);
    $mime = isset($type['type']) ? (string) $type['type'] : '';
    if ('image/png' !== $mime) {
        $file['error'] = esc_html__('Solo se permiten archivos PNG para los logos de clientes.', 'cad-theme');
    }

    return $file;
}
add_filter('wp_handle_upload_prefilter', 'cad_theme_validate_client_logo_upload');

function cad_theme_validate_client_featured_logo_on_save($post_id, $post, $update)
{
    unset($update);
    if (!$post || 'cad_client' !== $post->post_type) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (wp_is_post_revision($post_id)) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $thumbnail_id = (int) get_post_thumbnail_id($post_id);
    if (!$thumbnail_id) {
        return;
    }

    $mime = (string) get_post_mime_type($thumbnail_id);
    if ('image/png' === $mime) {
        return;
    }

    delete_post_thumbnail($post_id);
    set_transient(cad_theme_client_logo_notice_transient_key(), 1, MINUTE_IN_SECONDS);
}
add_action('save_post_cad_client', 'cad_theme_validate_client_featured_logo_on_save', 20, 3);

function cad_theme_get_video_banner()
{
    $defaults = cad_theme_video_banner_defaults();

    $query = new WP_Query(
        array(
            'post_type'      => 'cad_video_banner',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        )
    );

    if (!$query->have_posts()) {
        return $defaults;
    }

    $query->the_post();
    $post_id = get_the_ID();
    wp_reset_postdata();

    $fields = cad_theme_video_banner_meta_fields();
    $mp4 = get_post_meta($post_id, $fields['mp4'], true);
    $webm = get_post_meta($post_id, $fields['webm'], true);
    $fallback = get_post_meta($post_id, $fields['fallback'], true);
    $youtube = get_post_meta($post_id, $fields['youtube'], true);
    $headline_1 = get_post_meta($post_id, $fields['headline_1'], true);
    $headline_2 = get_post_meta($post_id, $fields['headline_2'], true);
    $label_play = get_post_meta($post_id, $fields['label_play'], true);
    $label_pause = get_post_meta($post_id, $fields['label_pause'], true);
    $show_video = get_post_meta($post_id, $fields['show_video'], true);
    $show_mp4 = get_post_meta($post_id, $fields['show_mp4'], true);
    $show_webm = get_post_meta($post_id, $fields['show_webm'], true);
    $show_fallback = get_post_meta($post_id, $fields['show_fallback'], true);
    $show_headline = get_post_meta($post_id, $fields['show_headline'], true);
    $show_button = get_post_meta($post_id, $fields['show_button'], true);

    return array(
        'mp4'         => $mp4 ? $mp4 : $defaults['mp4'],
        'webm'        => $webm ? $webm : $defaults['webm'],
        'fallback'    => $fallback ? $fallback : $defaults['fallback'],
        'youtube'     => $youtube ? $youtube : $defaults['youtube'],
        'headline_1'  => $headline_1 ? $headline_1 : $defaults['headline_1'],
        'headline_2'  => $headline_2 ? $headline_2 : $defaults['headline_2'],
        'label_play'  => $label_play ? $label_play : $defaults['label_play'],
        'label_pause' => $label_pause ? $label_pause : $defaults['label_pause'],
        'show_video'  => '0' !== $show_video,
        'show_mp4'    => '0' !== $show_mp4,
        'show_webm'   => '0' !== $show_webm,
        'show_fallback' => '0' !== $show_fallback,
        'show_headline' => '0' !== $show_headline,
        'show_button' => '0' !== $show_button,
    );
}

function cad_theme_get_youtube_id($url)
{
    if (!$url) {
        return '';
    }

    if (preg_match('~youtu\\.be/([^?&]+)~', $url, $matches)) {
        return $matches[1];
    }

    if (preg_match('~youtube\\.com/(?:watch\\?v=|embed/|shorts/)([^?&/]+)~', $url, $matches)) {
        return $matches[1];
    }

    $parts = wp_parse_url($url);
    if (empty($parts['query'])) {
        return '';
    }

    parse_str($parts['query'], $query_vars);
    return isset($query_vars['v']) ? (string) $query_vars['v'] : '';
}

function cad_theme_get_youtube_embed_url($url)
{
    $video_id = cad_theme_get_youtube_id($url);
    if (!$video_id) {
        return '';
    }

    $params = array(
        'autoplay'       => 1,
        'mute'           => 1,
        'loop'           => 1,
        'controls'       => 0,
        'rel'            => 0,
        'playsinline'    => 1,
        'modestbranding' => 1,
        'playlist'       => $video_id,
    );

    return 'https://www.youtube.com/embed/' . rawurlencode($video_id) . '?' . http_build_query($params, '', '&');
}

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
        array(
            'label'    => __('Areas de negocio', 'cad-theme'),
            'url'      => '#',
            'children' => array(
                array(
                    'label' => __('Construccion', 'cad-theme'),
                    'url'   => home_url('/areas-de-negocio/construccion/'),
                ),
                array(
                    'label' => __('Inmobiliaria', 'cad-theme'),
                    'url'   => home_url('/areas-de-negocio/inmobiliaria/'),
                ),
                array(
                    'label' => __('Servicios', 'cad-theme'),
                    'url'   => home_url('/areas-de-negocio/servicios/'),
                ),
            ),
        ),
    );
}

function cad_theme_default_business_cards()
{
    return array(
        array(
            'title'       => __('Construccion', 'cad-theme'),
            'description' => __('Hemos edificado importantes obras, de gran impacto para el pais, las empresas y sus comunidades.', 'cad-theme'),
            'url'         => home_url('/areas-de-negocio/construccion/'),
            'cta'         => __('Ver proyectos', 'cad-theme'),
            'image'       => 'https://ebco.cl/assets/pages/home/ebco-areas-negocio-construccion-v3.jpg',
            'tone'        => 'is-blue',
        ),
        array(
            'title'       => __('Inmobiliaria', 'cad-theme'),
            'description' => __('Proyectos inmobiliarios destinados a comercializacion de casas y edificacion para viviendas, oficinas comerciales y renta.', 'cad-theme'),
            'url'         => home_url('/areas-de-negocio/inmobiliaria/'),
            'cta'         => __('Ver proyectos', 'cad-theme'),
            'image'       => 'https://ebco.cl/assets/pages/home/ebco-bg-area-negocio-inmobiliaria-v2.jpg',
            'tone'        => 'is-indigo',
        ),
        array(
            'title'       => __('Servicios', 'cad-theme'),
            'description' => __('Orientados a entregar servicios vinculados al sector, buscando maximizar la eficiencia en la gestion de proyectos.', 'cad-theme'),
            'url'         => home_url('/areas-de-negocio/servicios/'),
            'cta'         => __('Ver servicios', 'cad-theme'),
            'image'       => 'https://ebco.cl/assets/pages/home/ebco-areas-negocio-servicios.jpg',
            'tone'        => 'is-slate',
        ),
    );
}

function cad_theme_business_section_defaults()
{
    return array(
        'title'   => __('Areas de Negocio', 'cad-theme'),
        'content' => __('Construccion, Inmobiliaria y Servicios.', 'cad-theme'),
    );
}

function cad_theme_business_area_tones()
{
    return array(
        'is-blue'   => __('Azul', 'cad-theme'),
        'is-indigo' => __('Indigo', 'cad-theme'),
        'is-slate'  => __('Grafito', 'cad-theme'),
    );
}

function cad_theme_business_area_meta_fields()
{
    return array(
        'badge_label'               => '_cad_business_badge_label',
        'badge_context'             => '_cad_business_badge_context',
        'hero_title_suffix'         => '_cad_business_hero_title_suffix',
        'hero_title_accent'         => '_cad_business_hero_title_accent',
        'meta_location'             => '_cad_business_meta_location',
        'meta_experience'           => '_cad_business_meta_experience',
        'meta_projects'             => '_cad_business_meta_projects',
        'description_label'         => '_cad_business_description_label',
        'structure_label'           => '_cad_business_structure_label',
        'structure_title'           => '_cad_business_structure_title',
        'subareas'                  => '_cad_business_subareas',
        'gallery_label'             => '_cad_business_gallery_label',
        'gallery_title'             => '_cad_business_gallery_title',
        'gallery_ids'               => '_cad_business_gallery_ids',
        'projects_label'            => '_cad_business_projects_label',
        'projects_title'            => '_cad_business_projects_title',
        'related_projects'          => '_cad_business_related_projects',
        'final_cta_text'            => '_cad_business_final_cta_text',
        'final_cta_primary_label'   => '_cad_business_final_cta_primary_label',
        'final_cta_primary_url'     => '_cad_business_final_cta_primary_url',
        'final_cta_secondary_label' => '_cad_business_final_cta_secondary_label',
        'final_cta_secondary_url'   => '_cad_business_final_cta_secondary_url',
    );
}

function cad_theme_business_area_icon_resolve($icon)
{
    $options = cad_theme_project_meta_icon_options();
    $icon = sanitize_key((string) $icon);

    if ($icon && isset($options[$icon])) {
        return $icon;
    }

    return 'engineering';
}

function cad_theme_normalize_business_subareas($items)
{
    if (!is_array($items)) {
        return array();
    }

    $normalized = array();

    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }

        $title = isset($item['title']) ? sanitize_text_field((string) $item['title']) : '';
        $description = isset($item['description']) ? sanitize_textarea_field((string) $item['description']) : '';
        $icon = isset($item['icon']) ? cad_theme_business_area_icon_resolve($item['icon']) : 'engineering';

        if ('' === $title && '' === $description) {
            continue;
        }

        $normalized[] = array(
            'icon'        => $icon,
            'title'       => $title,
            'description' => $description,
        );
    }

    return $normalized;
}

function cad_theme_normalize_business_related_projects($items)
{
    if (!is_array($items)) {
        return array();
    }

    $normalized = array();

    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }

        $name = isset($item['name']) ? sanitize_text_field((string) $item['name']) : '';
        $location = isset($item['location']) ? sanitize_text_field((string) $item['location']) : '';
        $year = isset($item['year']) ? sanitize_text_field((string) $item['year']) : '';
        $status = isset($item['status']) ? sanitize_text_field((string) $item['status']) : '';
        $url = isset($item['url']) ? esc_url_raw((string) $item['url']) : '';

        if ('' === $name && '' === $location && '' === $year && '' === $status && '' === $url) {
            continue;
        }

        if ('' === $name) {
            continue;
        }

        $normalized[] = array(
            'name'     => $name,
            'location' => $location,
            'year'     => $year,
            'status'   => $status,
            'url'      => $url,
        );
    }

    return $normalized;
}

function cad_theme_business_area_presets()
{
    $contact_url = home_url('/contacto/');
    $projects_url = home_url('/#proyectos');

    return array(
        'default' => array(
            'badge_label'       => __('Area de Negocio', 'cad-theme'),
            'badge_context'     => __('CAD Ingenieros', 'cad-theme'),
            'hero_title_suffix' => __('Industrial', 'cad-theme'),
            'hero_title_accent' => __('Especializada', 'cad-theme'),
            'meta_location'     => __('Cobertura nacional', 'cad-theme'),
            'meta_experience'   => __('18 anos de experiencia', 'cad-theme'),
            'meta_projects'     => __('126 proyectos desarrollados', 'cad-theme'),
            'description_label' => __('Capacidad tecnica', 'cad-theme'),
            'content'           => __('Desarrollamos soluciones de ingenieria con foco en precision operativa, coordinacion multidisciplinaria y control de ejecucion en terreno. Cada encargo se aborda con criterios tecnicos, trazabilidad y una metodologia orientada al cumplimiento.', 'cad-theme') . "\n\n" . __('La estructura del area permite responder a distintos niveles de complejidad, integrando analisis, planificacion y soporte de obra bajo un mismo estandar corporativo. El resultado es una oferta robusta, confiable y facil de escalar.', 'cad-theme'),
            'structure_label'   => __('Estructura del area', 'cad-theme'),
            'structure_title'   => __('Un sistema operativo tecnico para proyectos de alta exigencia', 'cad-theme'),
            'subareas'          => array(
                array(
                    'icon'        => 'architecture',
                    'title'       => __('Ingenieria base', 'cad-theme'),
                    'description' => __('Definicion de criterios, alcances, especialidades y lineamientos tecnicos para una toma de decisiones precisa.', 'cad-theme'),
                ),
                array(
                    'icon'        => 'precision_manufacturing',
                    'title'       => __('Coordinacion de obra', 'cad-theme'),
                    'description' => __('Articulacion entre disciplinas, proveedores y equipos de terreno para controlar plazos, interferencias y calidad.', 'cad-theme'),
                ),
                array(
                    'icon'        => 'task_alt',
                    'title'       => __('Aseguramiento tecnico', 'cad-theme'),
                    'description' => __('Protocolos, control documental y seguimiento de hitos criticos para sostener continuidad operacional.', 'cad-theme'),
                ),
                array(
                    'icon'        => 'monitoring',
                    'title'       => __('Optimizacion continua', 'cad-theme'),
                    'description' => __('Revision de desempeno, productividad y riesgos para mejorar resultados y consolidar estandares.', 'cad-theme'),
                ),
            ),
            'gallery_label'     => __('Galeria tecnica', 'cad-theme'),
            'gallery_title'     => __('Entornos, procesos y ejecucion en escala real', 'cad-theme'),
            'projects_label'    => __('Proyectos relacionados', 'cad-theme'),
            'projects_title'    => __('Experiencia reciente vinculada a esta capacidad', 'cad-theme'),
            'related_projects'  => array(
                array(
                    'name'     => __('Centro logistica integral', 'cad-theme'),
                    'location' => __('Santiago, Chile', 'cad-theme'),
                    'year'     => '2025',
                    'status'   => __('Ejecutado', 'cad-theme'),
                    'url'      => '',
                ),
                array(
                    'name'     => __('Planta de soporte tecnico', 'cad-theme'),
                    'location' => __('Antofagasta, Chile', 'cad-theme'),
                    'year'     => '2024',
                    'status'   => __('En desarrollo', 'cad-theme'),
                    'url'      => '',
                ),
                array(
                    'name'     => __('Infraestructura operacional', 'cad-theme'),
                    'location' => __('Concepcion, Chile', 'cad-theme'),
                    'year'     => '2023',
                    'status'   => __('Entregado', 'cad-theme'),
                    'url'      => '',
                ),
            ),
            'final_cta_text'            => __('Conversemos sobre una solucion tecnica alineada a tus exigencias operacionales.', 'cad-theme'),
            'final_cta_primary_label'   => __('Solicitar reunion', 'cad-theme'),
            'final_cta_primary_url'     => $contact_url,
            'final_cta_secondary_label' => __('Ver proyectos CAD', 'cad-theme'),
            'final_cta_secondary_url'   => $projects_url,
        ),
        'construccion' => array(
            'hero_title_suffix' => __('Industrial', 'cad-theme'),
            'hero_title_accent' => __('de precision', 'cad-theme'),
            'meta_location'     => __('Santiago y regiones', 'cad-theme'),
            'meta_experience'   => __('22 anos de experiencia', 'cad-theme'),
            'meta_projects'     => __('148 proyectos ejecutados', 'cad-theme'),
            'content'           => __('Ejecutamos obras con una mirada integral sobre planificacion, produccion, seguridad y control de calidad. La operacion se estructura para responder con precision a programas exigentes, entornos tecnicos complejos y multiples frentes de trabajo.', 'cad-theme') . "\n\n" . __('El area combina capacidad de coordinacion, soporte tecnico y una lectura profunda de las restricciones del proyecto para asegurar continuidad, robustez constructiva y cumplimiento operacional.', 'cad-theme'),
            'structure_title'   => __('Unidades especializadas para obras de alta demanda tecnica', 'cad-theme'),
            'subareas'          => array(
                array(
                    'icon'        => 'construction',
                    'title'       => __('Edificacion industrial', 'cad-theme'),
                    'description' => __('Construccion de recintos, infraestructura productiva y espacios de soporte bajo altos estandares de coordinacion.', 'cad-theme'),
                ),
                array(
                    'icon'        => 'domain',
                    'title'       => __('Obras corporativas', 'cad-theme'),
                    'description' => __('Soluciones para edificios institucionales, oficinas y programas que requieren una ejecucion sobria y precisa.', 'cad-theme'),
                ),
                array(
                    'icon'        => 'factory',
                    'title'       => __('Montaje y habilitacion', 'cad-theme'),
                    'description' => __('Integracion de sistemas, terminaciones tecnicas y partidas criticas para asegurar puesta en marcha ordenada.', 'cad-theme'),
                ),
                array(
                    'icon'        => 'verified',
                    'title'       => __('Control de entrega', 'cad-theme'),
                    'description' => __('Seguimiento de hitos, protocolos y cierre tecnico con foco en calidad y trazabilidad documental.', 'cad-theme'),
                ),
            ),
            'related_projects' => array(
                array(
                    'name'     => __('Parque industrial Santa Marta', 'cad-theme'),
                    'location' => __('Santiago, Chile', 'cad-theme'),
                    'year'     => '2025',
                    'status'   => __('En desarrollo', 'cad-theme'),
                    'url'      => '',
                ),
                array(
                    'name'     => __('Centro de operaciones norte', 'cad-theme'),
                    'location' => __('Antofagasta, Chile', 'cad-theme'),
                    'year'     => '2024',
                    'status'   => __('Ejecutado', 'cad-theme'),
                    'url'      => '',
                ),
                array(
                    'name'     => __('Complejo logistico sur', 'cad-theme'),
                    'location' => __('Puerto Montt, Chile', 'cad-theme'),
                    'year'     => '2023',
                    'status'   => __('Entregado', 'cad-theme'),
                    'url'      => '',
                ),
            ),
        ),
        'inmobiliaria' => array(
            'hero_title_suffix' => __('Desarrollo', 'cad-theme'),
            'hero_title_accent' => __('estrategico', 'cad-theme'),
            'meta_location'     => __('Mercado metropolitano', 'cad-theme'),
            'meta_experience'   => __('16 anos de experiencia', 'cad-theme'),
            'meta_projects'     => __('94 activos desarrollados', 'cad-theme'),
            'content'           => __('Impulsamos desarrollos con una lectura fina del mercado, la factibilidad tecnica y la consistencia del producto final. La toma de decisiones se apoya en analisis urbano, eficiencia del programa y viabilidad economica.', 'cad-theme') . "\n\n" . __('La propuesta combina gestion, coordinacion y una vision de largo plazo para consolidar proyectos habitacionales y corporativos con posicionamiento, calidad y control.', 'cad-theme'),
            'structure_title'   => __('Capacidades integradas para estructurar valor inmobiliario', 'cad-theme'),
            'subareas'          => array(
                array(
                    'icon'        => 'apartment',
                    'title'       => __('Desarrollo residencial', 'cad-theme'),
                    'description' => __('Configuracion de productos habitacionales con foco en eficiencia, habitabilidad y lectura de mercado.', 'cad-theme'),
                ),
                array(
                    'icon'        => 'real_estate_agent',
                    'title'       => __('Gestion de activos', 'cad-theme'),
                    'description' => __('Estrategias para consolidar valor, controlar riesgos y sostener desempeno comercial del desarrollo.', 'cad-theme'),
                ),
                array(
                    'icon'        => 'architecture',
                    'title'       => __('Planificacion de producto', 'cad-theme'),
                    'description' => __('Definicion programatica y tecnica para alinear factibilidad, experiencia de usuario y retorno.', 'cad-theme'),
                ),
                array(
                    'icon'        => 'monitoring',
                    'title'       => __('Seguimiento comercial', 'cad-theme'),
                    'description' => __('Monitoreo de hitos, absorcion y posicionamiento para optimizar la evolucion del proyecto.', 'cad-theme'),
                ),
            ),
        ),
        'servicios' => array(
            'hero_title_suffix' => __('Operacion', 'cad-theme'),
            'hero_title_accent' => __('integral', 'cad-theme'),
            'meta_location'     => __('Cobertura multisede', 'cad-theme'),
            'meta_experience'   => __('19 anos de experiencia', 'cad-theme'),
            'meta_projects'     => __('210 contratos de servicio', 'cad-theme'),
            'content'           => __('Articulamos servicios especializados para mantener continuidad operacional, eficiencia y control en activos complejos. El foco esta puesto en procesos claros, respuesta oportuna y soporte tecnico permanente.', 'cad-theme') . "\n\n" . __('La estructura del area permite intervenir en distintas escalas, integrando mantenimiento, supervison y mejora continua bajo una misma logica corporativa.', 'cad-theme'),
            'structure_title'   => __('Servicios tecnicos para continuidad y mejora operacional', 'cad-theme'),
            'subareas'          => array(
                array(
                    'icon'        => 'build',
                    'title'       => __('Mantenimiento tecnico', 'cad-theme'),
                    'description' => __('Programas preventivos y correctivos con trazabilidad, prioridad operativa y respaldo documental.', 'cad-theme'),
                ),
                array(
                    'icon'        => 'settings_suggest',
                    'title'       => __('Optimizacion de sistemas', 'cad-theme'),
                    'description' => __('Ajustes, calibracion y mejoras sobre infraestructura critica para elevar confiabilidad y rendimiento.', 'cad-theme'),
                ),
                array(
                    'icon'        => 'groups',
                    'title'       => __('Soporte especializado', 'cad-theme'),
                    'description' => __('Equipos tecnicos preparados para responder en terreno y coordinar decisiones con rapidez.', 'cad-theme'),
                ),
                array(
                    'icon'        => 'fact_check',
                    'title'       => __('Control y reporting', 'cad-theme'),
                    'description' => __('Seguimiento de indicadores, protocolos y avances para una gestion visible y medible.', 'cad-theme'),
                ),
            ),
        ),
    );
}

function cad_theme_get_business_area_preset($post)
{
    $presets = cad_theme_business_area_presets();
    $default = isset($presets['default']) ? $presets['default'] : array();

    if (!$post instanceof WP_Post) {
        return $default;
    }

    $key = sanitize_title($post->post_name ? $post->post_name : $post->post_title);
    if ($key && isset($presets[$key])) {
        return array_merge($default, $presets[$key]);
    }

    return $default;
}

function cad_theme_get_business_area_related_project_fallbacks($post_id, $preset)
{
    $post = get_post($post_id);
    $slug = $post ? sanitize_title($post->post_name ? $post->post_name : $post->post_title) : '';
    $query_args = array(
        'post_type'      => 'cad_project',
        'posts_per_page' => 4,
        'post_status'    => 'publish',
        'post__not_in'   => array((int) $post_id),
        'orderby'        => array(
            'menu_order' => 'ASC',
            'date'       => 'DESC',
        ),
    );

    if ($slug) {
        $term = get_term_by('slug', $slug, 'cad_project_category');
        if ($term && !is_wp_error($term)) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'cad_project_category',
                    'field'    => 'term_id',
                    'terms'    => array((int) $term->term_id),
                ),
            );
        }
    }

    $projects = get_posts($query_args);
    if (empty($projects) && !empty($query_args['tax_query'])) {
        unset($query_args['tax_query']);
        $projects = get_posts($query_args);
    }

    if (empty($projects)) {
        return isset($preset['related_projects']) && is_array($preset['related_projects']) ? $preset['related_projects'] : array();
    }

    $items = array();
    foreach ($projects as $project) {
        $location = get_post_meta($project->ID, '_cad_project_location', true);
        if (!$location && function_exists('get_field')) {
            $location = get_field('ubicacion', $project->ID);
        }

        $items[] = array(
            'name'     => get_the_title($project),
            'location' => $location ? (string) $location : __('Chile', 'cad-theme'),
            'year'     => get_the_date('Y', $project),
            'status'   => __('Ejecutado', 'cad-theme'),
            'url'      => get_permalink($project),
        );
    }

    return $items;
}

function cad_theme_get_business_area_page_data($post_id)
{
    $post = get_post($post_id);
    if (!$post instanceof WP_Post) {
        return array();
    }

    $fields = cad_theme_business_area_meta_fields();
    $preset = cad_theme_get_business_area_preset($post);
    $data = array();

    foreach ($fields as $key => $meta_key) {
        $value = get_post_meta($post_id, $meta_key, true);
        if ('' === $value || null === $value) {
            $value = isset($preset[$key]) ? $preset[$key] : '';
        }
        $data[$key] = $value;
    }

    $content = trim((string) $post->post_content);
    if ('' !== $content) {
        $data['description'] = apply_filters('the_content', $content);
    } else {
        $fallback_content = isset($preset['content']) ? (string) $preset['content'] : '';
        $data['description'] = $fallback_content ? wpautop(esc_html($fallback_content)) : '';
    }

    $subareas = cad_theme_normalize_business_subareas($data['subareas']);
    if (empty($subareas) && !empty($preset['subareas'])) {
        $subareas = cad_theme_normalize_business_subareas($preset['subareas']);
    }
    $data['subareas'] = $subareas;

    $gallery_ids = get_post_meta($post_id, $fields['gallery_ids'], true);
    if (!is_array($gallery_ids)) {
        $gallery_ids = array();
    }
    $gallery_ids = array_values(array_filter(array_map('absint', $gallery_ids)));

    $gallery = array();
    foreach (array_slice($gallery_ids, 0, 3) as $gallery_id) {
        $image_url = wp_get_attachment_image_url($gallery_id, 'full');
        if (!$image_url) {
            continue;
        }

        $alt = get_post_meta($gallery_id, '_wp_attachment_image_alt', true);
        if (!$alt) {
            $alt = get_the_title($gallery_id);
        }

        $gallery[] = array(
            'id'          => $gallery_id,
            'url'         => $image_url,
            'alt'         => (string) $alt,
            'placeholder' => false,
        );
    }

    while (count($gallery) < 3) {
        $slot = count($gallery) + 1;
        $gallery[] = array(
            'id'          => 0,
            'url'         => '',
            'alt'         => sprintf(__('Muestra %02d', 'cad-theme'), $slot),
            'placeholder' => true,
        );
    }
    $data['gallery'] = $gallery;

    $related_projects = cad_theme_normalize_business_related_projects($data['related_projects']);
    if (empty($related_projects)) {
        $related_projects = cad_theme_get_business_area_related_project_fallbacks($post_id, $preset);
        $related_projects = cad_theme_normalize_business_related_projects($related_projects);
    }
    $data['related_projects'] = $related_projects;

    $data['hero_image'] = get_the_post_thumbnail_url($post_id, 'full');
    $data['title'] = get_the_title($post);

    return $data;
}

function cad_theme_get_business_cards()
{
    $posts = get_posts(
        array(
            'post_type'      => 'cad_business_area',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        )
    );

    if (empty($posts)) {
        $defaults = cad_theme_default_business_cards();
        $cards = array();

        foreach ($defaults as $default) {
            $description = isset($default['description']) ? (string) $default['description'] : '';
            $cards[] = array(
                'title'       => isset($default['title']) ? (string) $default['title'] : '',
                'description' => $description ? wpautop(esc_html($description)) : '',
                'url'         => isset($default['url']) ? (string) $default['url'] : '#',
                'cta'         => isset($default['cta']) ? (string) $default['cta'] : __('Ver proyectos', 'cad-theme'),
                'image'       => isset($default['image']) ? (string) $default['image'] : '',
                'tone'        => isset($default['tone']) ? (string) $default['tone'] : '',
            );
        }

        return $cards;
    }

    $tones = cad_theme_business_area_tones();
    $defaults = cad_theme_default_business_cards();
    $cards = array();

    foreach ($posts as $index => $post) {
        setup_postdata($post);
        $cta_label = get_post_meta($post->ID, '_cad_business_cta_label', true);
        if (!$cta_label) {
            $cta_label = __('Ver proyectos', 'cad-theme');
        }

        $cta_url = get_post_meta($post->ID, '_cad_business_cta_url', true);
        $permalink = get_permalink($post);
        if (
            !$cta_url
            || false !== strpos((string) $cta_url, '/proyectos/')
        ) {
            $cta_url = $permalink ? $permalink : '#';
        }

        $tone = get_post_meta($post->ID, '_cad_business_tone', true);
        if (empty($tone) || !isset($tones[$tone])) {
            $tone = 'is-blue';
        }

        $image = get_the_post_thumbnail_url($post->ID, 'full');
        if (!$image && isset($defaults[$index]['image'])) {
            $image = (string) $defaults[$index]['image'];
        }

        $excerpt = has_excerpt($post->ID) ? get_the_excerpt($post) : '';
        if ('' === trim($excerpt) && !empty($post->post_content)) {
            $excerpt = wp_trim_words(wp_strip_all_tags((string) $post->post_content), 20);
        }
        if ('' === trim($excerpt)) {
            $preset = cad_theme_get_business_area_preset($post);
            $excerpt = isset($preset['content']) ? wp_trim_words((string) $preset['content'], 20) : '';
        }

        $cards[] = array(
            'title'       => get_the_title($post),
            'description' => $excerpt ? wpautop(esc_html($excerpt)) : '',
            'url'         => (string) $cta_url,
            'cta'         => (string) $cta_label,
            'image'       => (string) $image,
            'tone'        => (string) $tone,
        );
    }

    wp_reset_postdata();

    return $cards;
}

function cad_theme_get_business_section_title()
{
    $posts = get_posts(
        array(
            'post_type'      => 'cad_business_section',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        )
    );

    if (!empty($posts)) {
        return get_the_title($posts[0]);
    }

    $defaults = cad_theme_business_section_defaults();
    return isset($defaults['title']) ? (string) $defaults['title'] : '';
}

function cad_theme_get_business_section_content()
{
    $posts = get_posts(
        array(
            'post_type'      => 'cad_business_section',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        )
    );

    if (!empty($posts)) {
        $content = (string) $posts[0]->post_content;
        if ('' !== trim($content)) {
            return apply_filters('the_content', $content);
        }

        return '';
    }

    $defaults = cad_theme_business_section_defaults();
    $fallback = isset($defaults['content']) ? (string) $defaults['content'] : '';
    if (!$fallback) {
        return '';
    }

    return wpautop(esc_html($fallback));
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

function cad_theme_indicator_section_defaults()
{
    return array(
        'title' => __('Indicadores', 'cad-theme'),
    );
}

function cad_theme_default_clients()
{
    return array(
        array(
            'name'  => 'Google',
            'logo'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Google_2015_logo.svg/640px-Google_2015_logo.svg.png',
            'color' => '#4285F4',
        ),
        array(
            'name'  => 'Amazon',
            'logo'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Amazon_logo.svg/640px-Amazon_logo.svg.png',
            'color' => '#111111',
        ),
        array(
            'name'  => 'Microsoft',
            'logo'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Microsoft_logo.svg/640px-Microsoft_logo.svg.png',
            'color' => '#00A4EF',
        ),
        array(
            'name'  => 'Samsung',
            'logo'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/Samsung_Logo.svg/640px-Samsung_Logo.svg.png',
            'color' => '#1428A0',
        ),
        array(
            'name'  => 'Adidas',
            'logo'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/20/Adidas_Logo.svg/640px-Adidas_Logo.svg.png',
            'color' => '#000000',
        ),
        array(
            'name'  => 'Coca-Cola',
            'logo'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/ce/Coca-Cola_logo.svg/640px-Coca-Cola_logo.svg.png',
            'color' => '#F40009',
        ),
    );
}

function cad_theme_home_intro_defaults()
{
    return array(
        'title'   => __('Somos', 'cad-theme'),
        'content' => __('El respeto a las personas, a la sociedad y al medioambiente es el pilar sobre el cual se cimenta la empresa. En base a esto desarrollamos proyectos y construimos relaciones de largo plazo.', 'cad-theme'),
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

function cad_theme_footer_contact_meta_fields()
{
    return array(
        'address'      => '_cad_footer_address',
        'phone_label'  => '_cad_footer_phone_label',
        'phone_url'    => '_cad_footer_phone_url',
        'social_links' => '_cad_footer_social_links',
    );
}

function cad_theme_footer_contact_defaults()
{
    return array(
        'title'        => __('Footer principal', 'cad-theme'),
        'address'      => "AV. SANTA MARIA 2450 PROVIDENCIA\nSANTIAGO - CHILE",
        'phone_label'  => '+56 2 2464 4700',
        'phone_url'    => 'tel:+56224644700',
        'social_links' => array(
            array(
                'label' => 'Instagram',
                'url'   => 'https://www.instagram.com/somoscad?igshid=jwy8h5uamg3f',
            ),
            array(
                'label' => 'LinkedIn',
                'url'   => 'https://www.linkedin.com/company/cadsa/',
            ),
            array(
                'label' => 'YouTube',
                'url'   => 'https://www.youtube.com/channel/UC_G_L0F8-RDHMJYtKyM0uAw',
            ),
        ),
    );
}

function cad_theme_footer_contact_address_lines($address)
{
    if (!is_string($address) || '' === trim($address)) {
        return array();
    }

    $lines = preg_split('/\r\n|\r|\n/', $address);
    if (!is_array($lines)) {
        return array();
    }

    $normalized = array();

    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ('' !== $line) {
            $normalized[] = $line;
        }
    }

    return $normalized;
}

function cad_theme_normalize_footer_social_links($links)
{
    if (!is_array($links)) {
        return array();
    }

    $normalized = array();

    foreach ($links as $link) {
        if (!is_array($link)) {
            continue;
        }

        $label = isset($link['label']) ? sanitize_text_field((string) $link['label']) : '';
        $url = isset($link['url']) ? esc_url_raw((string) $link['url']) : '';

        if ('' === $label || '' === $url) {
            continue;
        }

        $normalized[] = array(
            'label' => $label,
            'url'   => $url,
        );
    }

    return $normalized;
}

function cad_theme_footer_phone_url($phone_label, $phone_url = '')
{
    $phone_url = trim((string) $phone_url);
    if ('' !== $phone_url) {
        return $phone_url;
    }

    $phone_label = trim((string) $phone_label);
    if ('' === $phone_label) {
        return '';
    }

    $prefix = 0 === strpos($phone_label, '+') ? '+' : '';
    $digits = preg_replace('/\D+/', '', $phone_label);

    if (!is_string($digits) || '' === $digits) {
        return '';
    }

    return 'tel:' . $prefix . $digits;
}

function cad_theme_default_page_nav()
{
    return array(
        array(
            'label' => __('CAD', 'cad-theme'),
            'url'   => '#somos',
        ),
        array(
            'label' => __('Areas de Negocio', 'cad-theme'),
            'url'   => '#business-areas',
        ),
        array(
            'label' => __('Indicadores', 'cad-theme'),
            'url'   => '#indicadores',
        ),
        array(
            'label' => __('Proyectos', 'cad-theme'),
            'url'   => '#proyectos',
        ),
    );
}

function cad_theme_get_home_intro_post()
{
    $posts = get_posts(
        array(
            'post_type'      => 'cad_home_intro',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        )
    );

    return !empty($posts) ? $posts[0] : null;
}

function cad_theme_get_footer_contact_post()
{
    $posts = get_posts(
        array(
            'post_type'      => 'cad_footer_contact',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        )
    );

    return !empty($posts) ? $posts[0] : null;
}

function cad_theme_get_home_intro_content()
{
    $post = cad_theme_get_home_intro_post();
    if ($post && !empty($post->post_content)) {
        return apply_filters('the_content', $post->post_content);
    }

    $defaults = cad_theme_home_intro_defaults();
    $fallback = isset($defaults['content']) ? (string) $defaults['content'] : '';
    if (!$fallback) {
        return '';
    }

    return wpautop(esc_html($fallback));
}

function cad_theme_get_footer_contact_data()
{
    $defaults = cad_theme_footer_contact_defaults();
    $fields = cad_theme_footer_contact_meta_fields();
    $post = cad_theme_get_footer_contact_post();

    $address = isset($defaults['address']) ? (string) $defaults['address'] : '';
    $phone_label = isset($defaults['phone_label']) ? (string) $defaults['phone_label'] : '';
    $phone_url = isset($defaults['phone_url']) ? (string) $defaults['phone_url'] : '';
    $social_links = isset($defaults['social_links']) ? cad_theme_normalize_footer_social_links($defaults['social_links']) : array();

    if ($post) {
        if (metadata_exists('post', $post->ID, $fields['address'])) {
            $address = (string) get_post_meta($post->ID, $fields['address'], true);
        }

        if (metadata_exists('post', $post->ID, $fields['phone_label'])) {
            $phone_label = (string) get_post_meta($post->ID, $fields['phone_label'], true);
        }

        if (metadata_exists('post', $post->ID, $fields['phone_url'])) {
            $phone_url = (string) get_post_meta($post->ID, $fields['phone_url'], true);
        }

        if (metadata_exists('post', $post->ID, $fields['social_links'])) {
            $social_links = cad_theme_normalize_footer_social_links(get_post_meta($post->ID, $fields['social_links'], true));
        }
    }

    return array(
        'address'       => $address,
        'address_lines' => cad_theme_footer_contact_address_lines($address),
        'phone_label'   => $phone_label,
        'phone_url'     => cad_theme_footer_phone_url($phone_label, $phone_url),
        'social_links'  => $social_links,
    );
}

function cad_theme_get_indicator_section_title()
{
    $posts = get_posts(
        array(
            'post_type'      => 'cad_indicator_sec',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        )
    );

    if (!empty($posts)) {
        return get_the_title($posts[0]);
    }

    $defaults = cad_theme_indicator_section_defaults();
    return isset($defaults['title']) ? (string) $defaults['title'] : '';
}

function cad_theme_get_indicator_cards()
{
    $posts = get_posts(
        array(
            'post_type'      => 'cad_indicator',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        )
    );

    if (empty($posts)) {
        $defaults = cad_theme_default_indicators();
        $cards = array();

        foreach ($defaults as $default) {
            $cards[] = array(
                'label'  => isset($default['label']) ? (string) $default['label'] : '',
                'value'  => isset($default['value']) ? (string) $default['value'] : '',
                'period' => isset($default['period']) ? (string) $default['period'] : '',
            );
        }

        return $cards;
    }

    $defaults = cad_theme_default_indicators();
    $cards = array();

    foreach ($posts as $index => $post) {
        setup_postdata($post);

        $label = get_the_title($post);
        $value = get_post_meta($post->ID, '_cad_indicator_value', true);
        $period = get_post_meta($post->ID, '_cad_indicator_period', true);

        if (!$label && isset($defaults[$index]['label'])) {
            $label = (string) $defaults[$index]['label'];
        }

        if (!$value && isset($defaults[$index]['value'])) {
            $value = (string) $defaults[$index]['value'];
        }

        $cards[] = array(
            'label'  => (string) $label,
            'value'  => (string) $value,
            'period' => (string) $period,
        );
    }

    wp_reset_postdata();

    return $cards;
}

function cad_theme_get_clients()
{
    $posts = get_posts(
        array(
            'post_type'      => 'cad_client',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => array(
                'menu_order' => 'ASC',
                'date'       => 'DESC',
            ),
        )
    );

    if (empty($posts)) {
        return array();
    }

    $clients = array();
    foreach ($posts as $post) {
        $post_id = (int) $post->ID;
        $logo_id = (int) get_post_thumbnail_id($post_id);
        if (!$logo_id) {
            continue;
        }

        $name = get_the_title($post_id);
        $alt = get_post_meta($logo_id, '_wp_attachment_image_alt', true);
        if (!$alt) {
            $alt = $name;
        }

        $clients[] = array(
            'id'      => $post_id,
            'name'    => (string) $name,
            'logo_id' => $logo_id,
            'alt'     => (string) $alt,
        );
    }

    return $clients;
}

function cad_theme_business_area_meta_boxes()
{
    add_meta_box(
        'cad-business-area-content',
        __('Contenido del area', 'cad-theme'),
        'cad_theme_business_area_content_box',
        'cad_business_area',
        'normal',
        'high'
    );

    add_meta_box(
        'cad-business-area-settings',
        __('Tarjeta del home', 'cad-theme'),
        'cad_theme_business_area_settings_box',
        'cad_business_area',
        'normal',
        'default'
    );

    add_meta_box(
        'cad-business-area-style',
        __('Estilo', 'cad-theme'),
        'cad_theme_business_area_style_box',
        'cad_business_area',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'cad_theme_business_area_meta_boxes');

function cad_theme_indicator_meta_boxes()
{
    add_meta_box(
        'cad-indicator-settings',
        __('Datos del indicador', 'cad-theme'),
        'cad_theme_indicator_settings_box',
        'cad_indicator',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'cad_theme_indicator_meta_boxes');

function cad_theme_footer_contact_meta_boxes()
{
    add_meta_box(
        'cad-footer-contact-settings',
        __('Direccion y redes', 'cad-theme'),
        'cad_theme_footer_contact_settings_box',
        'cad_footer_contact',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'cad_theme_footer_contact_meta_boxes');

function cad_theme_project_meta_boxes()
{
    add_meta_box(
        'cad-project-assets',
        __('Contenido del proyecto', 'cad-theme'),
        'cad_theme_project_assets_box',
        'cad_project',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'cad_theme_project_meta_boxes');

function cad_theme_project_meta_icon_options()
{
    return array(
        'category'      => __('Categoria', 'cad-theme'),
        'sell'          => __('Etiqueta', 'cad-theme'),
        'apartment'     => __('Edificio', 'cad-theme'),
        'business'      => __('Empresa', 'cad-theme'),
        'engineering'   => __('Ingenieria', 'cad-theme'),
        'construction'  => __('Construccion', 'cad-theme'),
        'location_on'   => __('Ubicacion', 'cad-theme'),
        'pin_drop'      => __('Pin', 'cad-theme'),
        'public'        => __('Mundo', 'cad-theme'),
        'square_foot'   => __('Superficie', 'cad-theme'),
        'straighten'    => __('Regla', 'cad-theme'),
        'open_in_full'  => __('Dimension', 'cad-theme'),
        'architecture'  => __('Arquitectura', 'cad-theme'),
        'domain'        => __('Dominio', 'cad-theme'),
        'corporate_fare'=> __('Corporativo', 'cad-theme'),
        'account_balance' => __('Institucion', 'cad-theme'),
        'home_work'     => __('Complejo', 'cad-theme'),
        'condo'         => __('Condominio', 'cad-theme'),
        'factory'       => __('Fabrica', 'cad-theme'),
        'warehouse'     => __('Bodega', 'cad-theme'),
        'store'         => __('Tienda', 'cad-theme'),
        'business_center' => __('Centro negocio', 'cad-theme'),
        'precision_manufacturing' => __('Manufactura', 'cad-theme'),
        'handyman'      => __('Mantenimiento', 'cad-theme'),
        'build'         => __('Herramientas', 'cad-theme'),
        'bolt'          => __('Energia', 'cad-theme'),
        'electric_bolt' => __('Electrico', 'cad-theme'),
        'solar_power'   => __('Solar', 'cad-theme'),
        'hvac'          => __('Climatizacion', 'cad-theme'),
        'plumbing'      => __('Sanitario', 'cad-theme'),
        'settings'      => __('Ajustes', 'cad-theme'),
        'tune'          => __('Calibracion', 'cad-theme'),
        'settings_suggest' => __('Optimizacion', 'cad-theme'),
        'rule'          => __('Normativa', 'cad-theme'),
        'map'           => __('Mapa', 'cad-theme'),
        'place'         => __('Lugar', 'cad-theme'),
        'near_me'       => __('Cercania', 'cad-theme'),
        'route'         => __('Ruta', 'cad-theme'),
        'alt_route'     => __('Ruta alterna', 'cad-theme'),
        'my_location'   => __('Posicion', 'cad-theme'),
        'terrain'       => __('Terreno', 'cad-theme'),
        'landscape'     => __('Paisaje', 'cad-theme'),
        'forest'        => __('Bosque', 'cad-theme'),
        'park'          => __('Parque', 'cad-theme'),
        'water'         => __('Agua', 'cad-theme'),
        'local_shipping' => __('Logistica', 'cad-theme'),
        'directions_car' => __('Vehiculo', 'cad-theme'),
        'train'         => __('Tren', 'cad-theme'),
        'flight'        => __('Aereo', 'cad-theme'),
        'hub'           => __('Red', 'cad-theme'),
        'lan'           => __('LAN', 'cad-theme'),
        'router'        => __('Router', 'cad-theme'),
        'memory'        => __('Memoria', 'cad-theme'),
        'dns'           => __('Servidor', 'cad-theme'),
        'cloud'         => __('Nube', 'cad-theme'),
        'cloud_upload'  => __('Subir nube', 'cad-theme'),
        'cloud_download' => __('Bajar nube', 'cad-theme'),
        'calendar_month' => __('Calendario', 'cad-theme'),
        'schedule'      => __('Horario', 'cad-theme'),
        'event_available' => __('Disponible', 'cad-theme'),
        'pending_actions' => __('Pendientes', 'cad-theme'),
        'checklist'     => __('Checklist', 'cad-theme'),
        'task_alt'      => __('Tarea', 'cad-theme'),
        'verified'      => __('Verificado', 'cad-theme'),
        'fact_check'    => __('Revision', 'cad-theme'),
        'assignment'    => __('Asignacion', 'cad-theme'),
        'description'   => __('Descripcion', 'cad-theme'),
        'article'       => __('Articulo', 'cad-theme'),
        'insert_drive_file' => __('Archivo', 'cad-theme'),
        'folder'        => __('Carpeta', 'cad-theme'),
        'folder_open'   => __('Carpeta abierta', 'cad-theme'),
        'attach_file'   => __('Adjunto', 'cad-theme'),
        'image'         => __('Imagen', 'cad-theme'),
        'photo_library' => __('Galeria foto', 'cad-theme'),
        'video_library' => __('Galeria video', 'cad-theme'),
        'play_circle'   => __('Reproducir', 'cad-theme'),
        'smart_display' => __('Pantalla', 'cad-theme'),
        'link'          => __('Enlace', 'cad-theme'),
        'share'         => __('Compartir', 'cad-theme'),
        'groups'        => __('Equipo', 'cad-theme'),
    );
}

function cad_theme_project_meta_icon_defaults()
{
    return array(
        'category' => 'category',
        'client'   => 'business',
        'location' => 'location_on',
        'surface'  => 'square_foot',
    );
}

function cad_theme_project_meta_icon_resolve($icon, $slot)
{
    $options = cad_theme_project_meta_icon_options();
    $defaults = cad_theme_project_meta_icon_defaults();
    $fallback = isset($defaults[$slot]) ? (string) $defaults[$slot] : 'category';

    $icon = sanitize_key((string) $icon);
    if ($icon && isset($options[$icon])) {
        return $icon;
    }

    return $fallback;
}

function cad_theme_get_external_video_embed_url($url)
{
    $url = trim((string) $url);
    if ('' === $url) {
        return '';
    }

    $parts = wp_parse_url($url);
    if (!is_array($parts) || empty($parts['host'])) {
        return '';
    }

    $host = strtolower((string) $parts['host']);
    $path = isset($parts['path']) ? trim((string) $parts['path'], '/') : '';

    if (false !== strpos($host, 'youtu.be')) {
        $segments = array_values(array_filter(explode('/', $path)));
        $video_id = isset($segments[0]) ? (string) $segments[0] : '';
        if ($video_id && preg_match('/^[A-Za-z0-9_-]{6,64}$/', $video_id)) {
            return 'https://www.youtube.com/embed/' . rawurlencode($video_id) . '?rel=0&modestbranding=1';
        }
    }

    if (false !== strpos($host, 'youtube.com') || false !== strpos($host, 'youtube-nocookie.com')) {
        $video_id = '';
        if (!empty($parts['query'])) {
            parse_str((string) $parts['query'], $query_args);
            if (!empty($query_args['v'])) {
                $video_id = (string) $query_args['v'];
            }
        }

        if (!$video_id) {
            $segments = array_values(array_filter(explode('/', $path)));
            if (isset($segments[0], $segments[1]) && in_array($segments[0], array('embed', 'shorts', 'live'), true)) {
                $video_id = (string) $segments[1];
            }
        }

        if ($video_id && preg_match('/^[A-Za-z0-9_-]{6,64}$/', $video_id)) {
            return 'https://www.youtube.com/embed/' . rawurlencode($video_id) . '?rel=0&modestbranding=1';
        }
    }

    if (false !== strpos($host, 'vimeo.com')) {
        $segments = array_values(array_filter(explode('/', $path)));
        $video_id = '';

        if (isset($segments[0]) && 'video' === $segments[0] && isset($segments[1])) {
            $video_id = (string) $segments[1];
        } elseif (!empty($segments)) {
            $video_id = (string) end($segments);
        }

        if ($video_id && preg_match('/^\d+$/', $video_id)) {
            return 'https://player.vimeo.com/video/' . rawurlencode($video_id);
        }
    }

    return '';
}

function cad_theme_get_external_video_embed_html($url)
{
    $embed_url = cad_theme_get_external_video_embed_url($url);
    if (!$embed_url) {
        return '';
    }

    return '<iframe src="' . esc_url($embed_url) . '" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen title="' . esc_attr__('Video del proyecto', 'cad-theme') . '"></iframe>';
}

function cad_theme_allowed_video_embed_html()
{
    $allowed = wp_kses_allowed_html('post');
    $allowed['iframe'] = array(
        'src'             => true,
        'width'           => true,
        'height'          => true,
        'frameborder'     => true,
        'allow'           => true,
        'allowfullscreen' => true,
        'title'           => true,
        'loading'         => true,
        'referrerpolicy'  => true,
    );

    return $allowed;
}

function cad_theme_sanitize_video_embed_html($html)
{
    return wp_kses((string) $html, cad_theme_allowed_video_embed_html());
}

function cad_theme_render_project_icon_picker($field_id, $field_name, $selected_icon, $label, $options = array())
{
    if (empty($options) || !is_array($options)) {
        return;
    }

    $selected_icon = sanitize_key((string) $selected_icon);
    if (!isset($options[$selected_icon])) {
        reset($options);
        $selected_icon = (string) key($options);
    }
    $selected_label = isset($options[$selected_icon]) ? (string) $options[$selected_icon] : '';
    ?>
    <div class="cad-project-icon-picker-field" data-icon-picker-field>
        <label for="<?php echo esc_attr($field_id); ?>"><strong><?php echo esc_html($label); ?></strong></label><br>
        <input type="hidden" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>" value="<?php echo esc_attr($selected_icon); ?>" data-icon-picker-input>
        <button type="button" class="button cad-project-icon-picker__trigger" data-icon-picker-open aria-haspopup="dialog" aria-controls="cad-project-icon-modal" aria-expanded="false">
            <span class="material-symbols-outlined cad-project-icon-picker__preview-icon" aria-hidden="true" data-icon-picker-preview-icon><?php echo esc_html($selected_icon); ?></span>
            <span class="cad-project-icon-picker__preview-text" data-icon-picker-preview-text><?php echo esc_html($selected_label); ?></span>
            <span class="cad-project-icon-picker__hint"><?php esc_html_e('Cambiar', 'cad-theme'); ?></span>
        </button>
    </div>
    <?php
}

function cad_theme_render_project_icon_modal($options = array())
{
    if (empty($options) || !is_array($options)) {
        return;
    }
    ?>
    <div class="cad-project-icon-modal" id="cad-project-icon-modal" hidden>
        <div class="cad-project-icon-modal__backdrop" data-icon-picker-close></div>
        <div class="cad-project-icon-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="cad-project-icon-modal-title">
            <div class="cad-project-icon-modal__header">
                <h3 id="cad-project-icon-modal-title"><?php esc_html_e('Selecciona un icono', 'cad-theme'); ?></h3>
                <button type="button" class="button-link cad-project-icon-modal__close" data-icon-picker-close aria-label="<?php esc_attr_e('Cerrar', 'cad-theme'); ?>">&times;</button>
            </div>
            <div class="cad-project-icon-modal__body">
                <div class="cad-project-icon-modal__grid" role="listbox" aria-label="<?php esc_attr_e('Iconos disponibles', 'cad-theme'); ?>">
                    <?php foreach ($options as $icon_key => $icon_label) : ?>
                        <button
                            type="button"
                            class="cad-project-icon-modal__option"
                            data-icon-option
                            data-icon-value="<?php echo esc_attr((string) $icon_key); ?>"
                            data-icon-label="<?php echo esc_attr((string) $icon_label); ?>"
                        >
                            <span class="material-symbols-outlined cad-project-icon-modal__icon" aria-hidden="true"><?php echo esc_html((string) $icon_key); ?></span>
                            <span class="cad-project-icon-modal__label"><?php echo esc_html((string) $icon_label); ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function cad_theme_project_assets_box($post)
{
    wp_nonce_field('cad_project_meta', 'cad_project_meta_nonce');

    $category = get_post_meta($post->ID, '_cad_project_category', true);
    $category_icon = get_post_meta($post->ID, '_cad_project_category_icon', true);
    $client = get_post_meta($post->ID, '_cad_project_client', true);
    $client_icon = get_post_meta($post->ID, '_cad_project_client_icon', true);
    $location = get_post_meta($post->ID, '_cad_project_location', true);
    $location_icon = get_post_meta($post->ID, '_cad_project_location_icon', true);
    $surface = get_post_meta($post->ID, '_cad_project_surface', true);
    $surface_icon = get_post_meta($post->ID, '_cad_project_surface_icon', true);

    $icon_options = cad_theme_project_meta_icon_options();
    $category_icon = cad_theme_project_meta_icon_resolve($category_icon, 'category');
    $client_icon = cad_theme_project_meta_icon_resolve($client_icon, 'client');
    $location_icon = cad_theme_project_meta_icon_resolve($location_icon, 'location');
    $surface_icon = cad_theme_project_meta_icon_resolve($surface_icon, 'surface');

    $documents = get_post_meta($post->ID, '_cad_project_documents', true);
    if (!is_array($documents)) {
        $documents = array();
    }
    $documents_title = get_post_meta($post->ID, '_cad_project_documents_title', true);
    if (!is_string($documents_title)) {
        $documents_title = '';
    }

    $videos = get_post_meta($post->ID, '_cad_project_videos', true);
    if (!is_array($videos)) {
        $videos = array();
    }
    $videos_title = get_post_meta($post->ID, '_cad_project_videos_title', true);
    if (!is_string($videos_title)) {
        $videos_title = '';
    }

    $gallery_ids = get_post_meta($post->ID, '_cad_project_gallery', true);
    if (!is_array($gallery_ids)) {
        $gallery_ids = array();
    }
    $gallery_ids = array_values(array_filter(array_map('absint', $gallery_ids)));
    $gallery_value = implode(',', $gallery_ids);
    $gallery_title = get_post_meta($post->ID, '_cad_project_gallery_title', true);
    if (!is_string($gallery_title)) {
        $gallery_title = '';
    }
    ?>
    <div class="cad-project-meta">
        <div class="cad-project-meta__section">
            <h4><?php esc_html_e('Ficha del proyecto', 'cad-theme'); ?></h4>
            <p class="description"><?php esc_html_e('Datos principales que se muestran en el detalle del proyecto. Puedes usar categorias del panel (taxonomia) y/o escribir una categoria manual aqui.', 'cad-theme'); ?></p>
            <p>
                <label for="cad-project-category"><strong><?php esc_html_e('Categoria', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-project-category" name="cad_project_category" class="widefat" value="<?php echo esc_attr($category); ?>" placeholder="<?php esc_attr_e('Ej: Construccion industrial', 'cad-theme'); ?>">
            </p>
            <?php
            cad_theme_render_project_icon_picker(
                'cad-project-category-icon',
                'cad_project_category_icon',
                $category_icon,
                __('Icono categoria', 'cad-theme'),
                $icon_options
            );
            ?>
            <p>
                <label for="cad-project-client"><strong><?php esc_html_e('Mandante', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-project-client" name="cad_project_client" class="widefat" value="<?php echo esc_attr($client); ?>" placeholder="<?php esc_attr_e('Ej: Consorcio mecanico', 'cad-theme'); ?>">
            </p>
            <?php
            cad_theme_render_project_icon_picker(
                'cad-project-client-icon',
                'cad_project_client_icon',
                $client_icon,
                __('Icono mandante', 'cad-theme'),
                $icon_options
            );
            ?>
            <p>
                <label for="cad-project-location"><strong><?php esc_html_e('Ubicacion', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-project-location" name="cad_project_location" class="widefat" value="<?php echo esc_attr($location); ?>" placeholder="<?php esc_attr_e('Ej: Santiago, Chile', 'cad-theme'); ?>">
            </p>
            <?php
            cad_theme_render_project_icon_picker(
                'cad-project-location-icon',
                'cad_project_location_icon',
                $location_icon,
                __('Icono ubicacion', 'cad-theme'),
                $icon_options
            );
            ?>
            <p>
                <label for="cad-project-surface"><strong><?php esc_html_e('Superficie', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-project-surface" name="cad_project_surface" class="widefat" value="<?php echo esc_attr($surface); ?>" placeholder="<?php esc_attr_e('Ej: 18.500 m2', 'cad-theme'); ?>">
            </p>
            <?php
            cad_theme_render_project_icon_picker(
                'cad-project-surface-icon',
                'cad_project_surface_icon',
                $surface_icon,
                __('Icono superficie', 'cad-theme'),
                $icon_options
            );
            ?>
        </div>

        <div class="cad-project-meta__section">
            <h4><?php esc_html_e('Documentos relacionados', 'cad-theme'); ?></h4>
            <p class="description"><?php esc_html_e('Agrega documentos descargables asociados al proyecto.', 'cad-theme'); ?></p>
            <p>
                <label for="cad-project-documents-title"><strong><?php esc_html_e('Titulo bloque de documentos', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-project-documents-title" name="cad_project_documents_title" class="widefat" value="<?php echo esc_attr($documents_title); ?>" placeholder="<?php esc_attr_e('Documentos destacados', 'cad-theme'); ?>">
            </p>
            <div class="cad-repeatable" data-repeatable="documents">
                <div class="cad-repeatable__list">
                    <?php foreach ($documents as $index => $document) : ?>
                        <?php
                        $label = isset($document['label']) ? (string) $document['label'] : '';
                        $url = isset($document['url']) ? (string) $document['url'] : '';
                        ?>
                        <div class="cad-repeatable__item" data-index="<?php echo esc_attr($index); ?>">
                            <div class="cad-repeatable__fields">
                                <label for="cad-project-doc-label-<?php echo esc_attr($index); ?>"><strong><?php esc_html_e('Etiqueta', 'cad-theme'); ?></strong></label>
                                <input type="text" id="cad-project-doc-label-<?php echo esc_attr($index); ?>" name="cad_project_documents[<?php echo esc_attr($index); ?>][label]" class="widefat" value="<?php echo esc_attr($label); ?>">
                            </div>
                            <div class="cad-repeatable__fields">
                                <label for="cad-project-doc-url-<?php echo esc_attr($index); ?>"><strong><?php esc_html_e('URL del documento', 'cad-theme'); ?></strong></label>
                                <div class="cad-repeatable__row">
                                    <input type="url" id="cad-project-doc-url-<?php echo esc_attr($index); ?>" name="cad_project_documents[<?php echo esc_attr($index); ?>][url]" class="widefat" value="<?php echo esc_attr($url); ?>" placeholder="https://">
                                    <button type="button" class="button cad-media-select" data-media-target="cad-project-doc-url-<?php echo esc_attr($index); ?>" data-media-type="application"><?php esc_html_e('Biblioteca', 'cad-theme'); ?></button>
                                </div>
                            </div>
                            <button type="button" class="button-link cad-repeatable__remove"><?php esc_html_e('Quitar', 'cad-theme'); ?></button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button cad-repeatable__add" data-repeatable-add="documents"><?php esc_html_e('Agregar documento', 'cad-theme'); ?></button>
                <template class="cad-repeatable__template" data-repeatable-template="documents">
                    <div class="cad-repeatable__item" data-index="__INDEX__">
                        <div class="cad-repeatable__fields">
                            <label for="cad-project-doc-label-__INDEX__"><strong><?php esc_html_e('Etiqueta', 'cad-theme'); ?></strong></label>
                            <input type="text" id="cad-project-doc-label-__INDEX__" name="cad_project_documents[__INDEX__][label]" class="widefat" value="">
                        </div>
                        <div class="cad-repeatable__fields">
                            <label for="cad-project-doc-url-__INDEX__"><strong><?php esc_html_e('URL del documento', 'cad-theme'); ?></strong></label>
                            <div class="cad-repeatable__row">
                                <input type="url" id="cad-project-doc-url-__INDEX__" name="cad_project_documents[__INDEX__][url]" class="widefat" value="" placeholder="https://">
                                <button type="button" class="button cad-media-select" data-media-target="cad-project-doc-url-__INDEX__" data-media-type="application"><?php esc_html_e('Biblioteca', 'cad-theme'); ?></button>
                            </div>
                        </div>
                        <button type="button" class="button-link cad-repeatable__remove"><?php esc_html_e('Quitar', 'cad-theme'); ?></button>
                    </div>
                </template>
            </div>
        </div>

        <div class="cad-project-meta__section">
            <h4><?php esc_html_e('Galeria de imagenes', 'cad-theme'); ?></h4>
            <p class="description"><?php esc_html_e('Selecciona multiples imagenes para la galeria del proyecto.', 'cad-theme'); ?></p>
            <p>
                <label for="cad-project-gallery-title"><strong><?php esc_html_e('Titulo bloque de galeria', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-project-gallery-title" name="cad_project_gallery_title" class="widefat" value="<?php echo esc_attr($gallery_title); ?>" placeholder="<?php esc_attr_e('Imagenes del proyecto', 'cad-theme'); ?>">
            </p>
            <div class="cad-project-gallery" data-project-gallery>
                <input type="hidden" id="cad-project-gallery-ids" name="cad_project_gallery_ids" value="<?php echo esc_attr($gallery_value); ?>" data-gallery-ids-input>
                <div class="cad-project-gallery__preview" data-project-gallery-preview>
                    <?php foreach ($gallery_ids as $image_id) : ?>
                        <div class="cad-project-gallery__item">
                            <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="cad-project-gallery__actions">
                    <button type="button" class="button cad-project-gallery__select"><?php esc_html_e('Seleccionar imagenes', 'cad-theme'); ?></button>
                    <button type="button" class="button cad-project-gallery__clear"><?php esc_html_e('Limpiar galeria', 'cad-theme'); ?></button>
                </div>
            </div>
        </div>

        <div class="cad-project-meta__section">
            <h4><?php esc_html_e('Videos relacionados', 'cad-theme'); ?></h4>
            <p class="description"><?php esc_html_e('Agrega enlaces a videos (YouTube, Vimeo, etc.).', 'cad-theme'); ?></p>
            <p>
                <label for="cad-project-videos-title"><strong><?php esc_html_e('Titulo bloque de videos', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-project-videos-title" name="cad_project_videos_title" class="widefat" value="<?php echo esc_attr($videos_title); ?>" placeholder="<?php esc_attr_e('Videos relacionados', 'cad-theme'); ?>">
            </p>
            <div class="cad-repeatable" data-repeatable="videos">
                <div class="cad-repeatable__list">
                    <?php foreach ($videos as $index => $video) : ?>
                        <?php
                        $label = isset($video['label']) ? (string) $video['label'] : '';
                        $url = isset($video['url']) ? (string) $video['url'] : '';
                        ?>
                        <div class="cad-repeatable__item" data-index="<?php echo esc_attr($index); ?>">
                            <div class="cad-repeatable__fields">
                                <label for="cad-project-video-label-<?php echo esc_attr($index); ?>"><strong><?php esc_html_e('Etiqueta', 'cad-theme'); ?></strong></label>
                                <input type="text" id="cad-project-video-label-<?php echo esc_attr($index); ?>" name="cad_project_videos[<?php echo esc_attr($index); ?>][label]" class="widefat" value="<?php echo esc_attr($label); ?>">
                            </div>
                            <div class="cad-repeatable__fields">
                                <label for="cad-project-video-url-<?php echo esc_attr($index); ?>"><strong><?php esc_html_e('URL del video', 'cad-theme'); ?></strong></label>
                                <input type="url" id="cad-project-video-url-<?php echo esc_attr($index); ?>" name="cad_project_videos[<?php echo esc_attr($index); ?>][url]" class="widefat" value="<?php echo esc_attr($url); ?>" placeholder="https://">
                            </div>
                            <button type="button" class="button-link cad-repeatable__remove"><?php esc_html_e('Quitar', 'cad-theme'); ?></button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button cad-repeatable__add" data-repeatable-add="videos"><?php esc_html_e('Agregar video', 'cad-theme'); ?></button>
                <template class="cad-repeatable__template" data-repeatable-template="videos">
                    <div class="cad-repeatable__item" data-index="__INDEX__">
                        <div class="cad-repeatable__fields">
                            <label for="cad-project-video-label-__INDEX__"><strong><?php esc_html_e('Etiqueta', 'cad-theme'); ?></strong></label>
                            <input type="text" id="cad-project-video-label-__INDEX__" name="cad_project_videos[__INDEX__][label]" class="widefat" value="">
                        </div>
                        <div class="cad-repeatable__fields">
                            <label for="cad-project-video-url-__INDEX__"><strong><?php esc_html_e('URL del video', 'cad-theme'); ?></strong></label>
                            <input type="url" id="cad-project-video-url-__INDEX__" name="cad_project_videos[__INDEX__][url]" class="widefat" value="" placeholder="https://">
                        </div>
                        <button type="button" class="button-link cad-repeatable__remove"><?php esc_html_e('Quitar', 'cad-theme'); ?></button>
                    </div>
                </template>
            </div>
        </div>

        <?php cad_theme_render_project_icon_modal($icon_options); ?>
    </div>
    <?php
}

function cad_theme_indicator_settings_box($post)
{
    wp_nonce_field('cad_indicator_meta', 'cad_indicator_meta_nonce');

    $value = get_post_meta($post->ID, '_cad_indicator_value', true);
    $period = get_post_meta($post->ID, '_cad_indicator_period', true);
    ?>
    <p class="description"><?php esc_html_e('El titulo del indicador se edita en el titulo de la entrada.', 'cad-theme'); ?></p>
    <p>
        <label for="cad-indicator-value"><strong><?php esc_html_e('Valor', 'cad-theme'); ?></strong></label><br>
        <input type="text" id="cad-indicator-value" name="cad_indicator_value" class="widefat" value="<?php echo esc_attr($value); ?>" placeholder="<?php esc_attr_e('119', 'cad-theme'); ?>">
    </p>
    <p>
        <label for="cad-indicator-period"><strong><?php esc_html_e('Periodo', 'cad-theme'); ?></strong></label><br>
        <input type="text" id="cad-indicator-period" name="cad_indicator_period" class="widefat" value="<?php echo esc_attr($period); ?>" placeholder="<?php esc_attr_e('Abril 2025', 'cad-theme'); ?>">
    </p>
    <?php
}

function cad_theme_footer_contact_settings_box($post)
{
    wp_nonce_field('cad_footer_contact_meta', 'cad_footer_contact_meta_nonce');

    $fields = cad_theme_footer_contact_meta_fields();
    $defaults = cad_theme_footer_contact_defaults();

    $address = metadata_exists('post', $post->ID, $fields['address']) ? (string) get_post_meta($post->ID, $fields['address'], true) : (string) $defaults['address'];
    $phone_label = metadata_exists('post', $post->ID, $fields['phone_label']) ? (string) get_post_meta($post->ID, $fields['phone_label'], true) : (string) $defaults['phone_label'];
    $phone_url = metadata_exists('post', $post->ID, $fields['phone_url']) ? (string) get_post_meta($post->ID, $fields['phone_url'], true) : (string) $defaults['phone_url'];
    $social_links = metadata_exists('post', $post->ID, $fields['social_links']) ? cad_theme_normalize_footer_social_links(get_post_meta($post->ID, $fields['social_links'], true)) : cad_theme_normalize_footer_social_links($defaults['social_links']);
    ?>
    <p class="description"><?php esc_html_e('Edita la direccion del footer usando una linea por fila y administra aqui los enlaces sociales.', 'cad-theme'); ?></p>
    <p>
        <label for="cad-footer-address"><strong><?php esc_html_e('Direccion', 'cad-theme'); ?></strong></label><br>
        <textarea id="cad-footer-address" name="cad_footer_address" class="widefat" rows="4" placeholder="<?php esc_attr_e('AV. SANTA MARIA 2450 PROVIDENCIA', 'cad-theme'); ?>"><?php echo esc_textarea($address); ?></textarea>
    </p>
    <p>
        <label for="cad-footer-phone-label"><strong><?php esc_html_e('Telefono visible', 'cad-theme'); ?></strong></label><br>
        <input type="text" id="cad-footer-phone-label" name="cad_footer_phone_label" class="widefat" value="<?php echo esc_attr($phone_label); ?>" placeholder="<?php esc_attr_e('+56 2 2464 4700', 'cad-theme'); ?>">
    </p>
    <p>
        <label for="cad-footer-phone-url"><strong><?php esc_html_e('Enlace telefono', 'cad-theme'); ?></strong></label><br>
        <input type="text" id="cad-footer-phone-url" name="cad_footer_phone_url" class="widefat" value="<?php echo esc_attr($phone_url); ?>" placeholder="tel:+56224644700">
    </p>
    <div class="cad-repeatable" data-repeatable="footer-social">
        <div class="cad-repeatable__list">
            <?php foreach ($social_links as $index => $social_link) : ?>
                <?php
                $label = isset($social_link['label']) ? (string) $social_link['label'] : '';
                $url = isset($social_link['url']) ? (string) $social_link['url'] : '';
                ?>
                <div class="cad-repeatable__item" data-index="<?php echo esc_attr($index); ?>">
                    <div class="cad-repeatable__fields">
                        <label for="cad-footer-social-label-<?php echo esc_attr($index); ?>"><strong><?php esc_html_e('Nombre red social', 'cad-theme'); ?></strong></label>
                        <input type="text" id="cad-footer-social-label-<?php echo esc_attr($index); ?>" name="cad_footer_social[<?php echo esc_attr($index); ?>][label]" class="widefat" value="<?php echo esc_attr($label); ?>">
                    </div>
                    <div class="cad-repeatable__fields">
                        <label for="cad-footer-social-url-<?php echo esc_attr($index); ?>"><strong><?php esc_html_e('URL', 'cad-theme'); ?></strong></label>
                        <input type="url" id="cad-footer-social-url-<?php echo esc_attr($index); ?>" name="cad_footer_social[<?php echo esc_attr($index); ?>][url]" class="widefat" value="<?php echo esc_attr($url); ?>" placeholder="https://">
                    </div>
                    <button type="button" class="button-link cad-repeatable__remove"><?php esc_html_e('Quitar', 'cad-theme'); ?></button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button cad-repeatable__add" data-repeatable-add="footer-social"><?php esc_html_e('Agregar red social', 'cad-theme'); ?></button>
        <template class="cad-repeatable__template" data-repeatable-template="footer-social">
            <div class="cad-repeatable__item" data-index="__INDEX__">
                <div class="cad-repeatable__fields">
                    <label for="cad-footer-social-label-__INDEX__"><strong><?php esc_html_e('Nombre red social', 'cad-theme'); ?></strong></label>
                    <input type="text" id="cad-footer-social-label-__INDEX__" name="cad_footer_social[__INDEX__][label]" class="widefat" value="">
                </div>
                <div class="cad-repeatable__fields">
                    <label for="cad-footer-social-url-__INDEX__"><strong><?php esc_html_e('URL', 'cad-theme'); ?></strong></label>
                    <input type="url" id="cad-footer-social-url-__INDEX__" name="cad_footer_social[__INDEX__][url]" class="widefat" value="" placeholder="https://">
                </div>
                <button type="button" class="button-link cad-repeatable__remove"><?php esc_html_e('Quitar', 'cad-theme'); ?></button>
            </div>
        </template>
    </div>
    <p class="description"><?php esc_html_e('Los enlaces sociales se muestran como texto y se abren en una nueva pestana.', 'cad-theme'); ?></p>
    <?php
}

function cad_theme_render_business_subarea_item($index, $item, $icon_options)
{
    $icon = isset($item['icon']) ? cad_theme_business_area_icon_resolve($item['icon']) : 'engineering';
    $title = isset($item['title']) ? (string) $item['title'] : '';
    $description = isset($item['description']) ? (string) $item['description'] : '';
    ?>
    <div class="cad-repeatable__item" data-index="<?php echo esc_attr((string) $index); ?>">
        <div class="cad-repeatable__fields">
            <?php
            cad_theme_render_project_icon_picker(
                'cad-business-subarea-icon-' . $index,
                'cad_business_subareas[' . $index . '][icon]',
                $icon,
                __('Icono', 'cad-theme'),
                $icon_options
            );
            ?>
        </div>
        <div class="cad-repeatable__fields">
            <label for="cad-business-subarea-title-<?php echo esc_attr((string) $index); ?>"><strong><?php esc_html_e('Nombre subarea', 'cad-theme'); ?></strong></label>
            <input type="text" id="cad-business-subarea-title-<?php echo esc_attr((string) $index); ?>" name="cad_business_subareas[<?php echo esc_attr((string) $index); ?>][title]" class="widefat" value="<?php echo esc_attr($title); ?>">
        </div>
        <div class="cad-repeatable__fields">
            <label for="cad-business-subarea-description-<?php echo esc_attr((string) $index); ?>"><strong><?php esc_html_e('Descripcion breve', 'cad-theme'); ?></strong></label>
            <textarea id="cad-business-subarea-description-<?php echo esc_attr((string) $index); ?>" name="cad_business_subareas[<?php echo esc_attr((string) $index); ?>][description]" class="widefat" rows="3"><?php echo esc_textarea($description); ?></textarea>
        </div>
        <button type="button" class="button-link cad-repeatable__remove"><?php esc_html_e('Quitar', 'cad-theme'); ?></button>
    </div>
    <?php
}

function cad_theme_render_business_related_project_item($index, $item)
{
    $name = isset($item['name']) ? (string) $item['name'] : '';
    $location = isset($item['location']) ? (string) $item['location'] : '';
    $year = isset($item['year']) ? (string) $item['year'] : '';
    $status = isset($item['status']) ? (string) $item['status'] : '';
    $url = isset($item['url']) ? (string) $item['url'] : '';
    ?>
    <div class="cad-repeatable__item" data-index="<?php echo esc_attr((string) $index); ?>">
        <div class="cad-repeatable__fields">
            <label for="cad-business-project-name-<?php echo esc_attr((string) $index); ?>"><strong><?php esc_html_e('Nombre proyecto', 'cad-theme'); ?></strong></label>
            <input type="text" id="cad-business-project-name-<?php echo esc_attr((string) $index); ?>" name="cad_business_related_projects[<?php echo esc_attr((string) $index); ?>][name]" class="widefat" value="<?php echo esc_attr($name); ?>">
        </div>
        <div class="cad-repeatable__fields">
            <label for="cad-business-project-location-<?php echo esc_attr((string) $index); ?>"><strong><?php esc_html_e('Ubicacion', 'cad-theme'); ?></strong></label>
            <input type="text" id="cad-business-project-location-<?php echo esc_attr((string) $index); ?>" name="cad_business_related_projects[<?php echo esc_attr((string) $index); ?>][location]" class="widefat" value="<?php echo esc_attr($location); ?>">
        </div>
        <div class="cad-repeatable__row">
            <div class="cad-repeatable__fields" style="flex:1;">
                <label for="cad-business-project-year-<?php echo esc_attr((string) $index); ?>"><strong><?php esc_html_e('Ano', 'cad-theme'); ?></strong></label>
                <input type="text" id="cad-business-project-year-<?php echo esc_attr((string) $index); ?>" name="cad_business_related_projects[<?php echo esc_attr((string) $index); ?>][year]" class="widefat" value="<?php echo esc_attr($year); ?>">
            </div>
            <div class="cad-repeatable__fields" style="flex:1;">
                <label for="cad-business-project-status-<?php echo esc_attr((string) $index); ?>"><strong><?php esc_html_e('Estado', 'cad-theme'); ?></strong></label>
                <input type="text" id="cad-business-project-status-<?php echo esc_attr((string) $index); ?>" name="cad_business_related_projects[<?php echo esc_attr((string) $index); ?>][status]" class="widefat" value="<?php echo esc_attr($status); ?>">
            </div>
        </div>
        <div class="cad-repeatable__fields">
            <label for="cad-business-project-url-<?php echo esc_attr((string) $index); ?>"><strong><?php esc_html_e('URL destino', 'cad-theme'); ?></strong></label>
            <input type="url" id="cad-business-project-url-<?php echo esc_attr((string) $index); ?>" name="cad_business_related_projects[<?php echo esc_attr((string) $index); ?>][url]" class="widefat" value="<?php echo esc_attr($url); ?>" placeholder="https://">
        </div>
        <button type="button" class="button-link cad-repeatable__remove"><?php esc_html_e('Quitar', 'cad-theme'); ?></button>
    </div>
    <?php
}

function cad_theme_business_area_content_box($post)
{
    $fields = cad_theme_business_area_meta_fields();
    $preset = cad_theme_get_business_area_preset($post);
    $icon_options = cad_theme_project_meta_icon_options();

    $get_field_value = static function ($meta_key, $fallback = '') use ($post) {
        if (metadata_exists('post', $post->ID, $meta_key)) {
            return get_post_meta($post->ID, $meta_key, true);
        }
        return $fallback;
    };

    $badge_label = (string) $get_field_value($fields['badge_label'], isset($preset['badge_label']) ? $preset['badge_label'] : '');
    $badge_context = (string) $get_field_value($fields['badge_context'], isset($preset['badge_context']) ? $preset['badge_context'] : '');
    $hero_title_suffix = (string) $get_field_value($fields['hero_title_suffix'], isset($preset['hero_title_suffix']) ? $preset['hero_title_suffix'] : '');
    $hero_title_accent = (string) $get_field_value($fields['hero_title_accent'], isset($preset['hero_title_accent']) ? $preset['hero_title_accent'] : '');
    $meta_location = (string) $get_field_value($fields['meta_location'], isset($preset['meta_location']) ? $preset['meta_location'] : '');
    $meta_experience = (string) $get_field_value($fields['meta_experience'], isset($preset['meta_experience']) ? $preset['meta_experience'] : '');
    $meta_projects = (string) $get_field_value($fields['meta_projects'], isset($preset['meta_projects']) ? $preset['meta_projects'] : '');
    $description_label = (string) $get_field_value($fields['description_label'], isset($preset['description_label']) ? $preset['description_label'] : '');
    $structure_label = (string) $get_field_value($fields['structure_label'], isset($preset['structure_label']) ? $preset['structure_label'] : '');
    $structure_title = (string) $get_field_value($fields['structure_title'], isset($preset['structure_title']) ? $preset['structure_title'] : '');
    $gallery_label = (string) $get_field_value($fields['gallery_label'], isset($preset['gallery_label']) ? $preset['gallery_label'] : '');
    $gallery_title = (string) $get_field_value($fields['gallery_title'], isset($preset['gallery_title']) ? $preset['gallery_title'] : '');
    $projects_label = (string) $get_field_value($fields['projects_label'], isset($preset['projects_label']) ? $preset['projects_label'] : '');
    $projects_title = (string) $get_field_value($fields['projects_title'], isset($preset['projects_title']) ? $preset['projects_title'] : '');
    $final_cta_text = (string) $get_field_value($fields['final_cta_text'], isset($preset['final_cta_text']) ? $preset['final_cta_text'] : '');
    $final_cta_primary_label = (string) $get_field_value($fields['final_cta_primary_label'], isset($preset['final_cta_primary_label']) ? $preset['final_cta_primary_label'] : '');
    $final_cta_primary_url = (string) $get_field_value($fields['final_cta_primary_url'], isset($preset['final_cta_primary_url']) ? $preset['final_cta_primary_url'] : '');
    $final_cta_secondary_label = (string) $get_field_value($fields['final_cta_secondary_label'], isset($preset['final_cta_secondary_label']) ? $preset['final_cta_secondary_label'] : '');
    $final_cta_secondary_url = (string) $get_field_value($fields['final_cta_secondary_url'], isset($preset['final_cta_secondary_url']) ? $preset['final_cta_secondary_url'] : '');

    $subareas = $get_field_value($fields['subareas'], isset($preset['subareas']) ? $preset['subareas'] : array());
    $subareas = cad_theme_normalize_business_subareas($subareas);
    if (empty($subareas) && !empty($preset['subareas'])) {
        $subareas = cad_theme_normalize_business_subareas($preset['subareas']);
    }

    $related_projects = $get_field_value($fields['related_projects'], isset($preset['related_projects']) ? $preset['related_projects'] : array());
    $related_projects = cad_theme_normalize_business_related_projects($related_projects);
    if (empty($related_projects) && !empty($preset['related_projects'])) {
        $related_projects = cad_theme_normalize_business_related_projects($preset['related_projects']);
    }

    $gallery_ids = $get_field_value($fields['gallery_ids'], array());
    if (!is_array($gallery_ids)) {
        $gallery_ids = array();
    }
    $gallery_ids = array_values(array_filter(array_map('absint', $gallery_ids)));
    $gallery_value = implode(',', $gallery_ids);
    ?>
    <div class="cad-project-meta">
        <div class="cad-project-meta__section">
            <h4><?php esc_html_e('Base del contenido', 'cad-theme'); ?></h4>
            <p class="description"><?php esc_html_e('El titulo del post define la primera linea del hero. El editor principal alimenta el bloque de descripcion. El extracto se usa como resumen de la tarjeta del home y la imagen destacada como fondo del hero.', 'cad-theme'); ?></p>
        </div>

        <div class="cad-project-meta__section">
            <h4><?php esc_html_e('Hero principal', 'cad-theme'); ?></h4>
            <p>
                <label for="cad-business-badge-label"><strong><?php esc_html_e('Etiqueta superior', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-business-badge-label" name="cad_business_badge_label" class="widefat" value="<?php echo esc_attr($badge_label); ?>" placeholder="<?php esc_attr_e('Area de Negocio', 'cad-theme'); ?>">
            </p>
            <p>
                <label for="cad-business-badge-context"><strong><?php esc_html_e('Contexto / empresa', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-business-badge-context" name="cad_business_badge_context" class="widefat" value="<?php echo esc_attr($badge_context); ?>" placeholder="<?php esc_attr_e('CAD Ingenieros', 'cad-theme'); ?>">
            </p>
            <p>
                <label for="cad-business-hero-title-suffix"><strong><?php esc_html_e('Segunda linea base', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-business-hero-title-suffix" name="cad_business_hero_title_suffix" class="widefat" value="<?php echo esc_attr($hero_title_suffix); ?>" placeholder="<?php esc_attr_e('Industrial', 'cad-theme'); ?>">
            </p>
            <p>
                <label for="cad-business-hero-title-accent"><strong><?php esc_html_e('Palabra destacada', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-business-hero-title-accent" name="cad_business_hero_title_accent" class="widefat" value="<?php echo esc_attr($hero_title_accent); ?>" placeholder="<?php esc_attr_e('Especializada', 'cad-theme'); ?>">
            </p>
            <div class="cad-repeatable__row">
                <div class="cad-repeatable__fields" style="flex:1;">
                    <label for="cad-business-meta-location"><strong><?php esc_html_e('Metadata: ubicacion', 'cad-theme'); ?></strong></label>
                    <input type="text" id="cad-business-meta-location" name="cad_business_meta_location" class="widefat" value="<?php echo esc_attr($meta_location); ?>">
                </div>
                <div class="cad-repeatable__fields" style="flex:1;">
                    <label for="cad-business-meta-experience"><strong><?php esc_html_e('Metadata: experiencia', 'cad-theme'); ?></strong></label>
                    <input type="text" id="cad-business-meta-experience" name="cad_business_meta_experience" class="widefat" value="<?php echo esc_attr($meta_experience); ?>">
                </div>
            </div>
            <p>
                <label for="cad-business-meta-projects"><strong><?php esc_html_e('Metadata: proyectos', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-business-meta-projects" name="cad_business_meta_projects" class="widefat" value="<?php echo esc_attr($meta_projects); ?>">
            </p>
        </div>

        <div class="cad-project-meta__section">
            <h4><?php esc_html_e('Descripcion', 'cad-theme'); ?></h4>
            <p class="description"><?php esc_html_e('Este label antecede el bloque de texto principal. El texto mismo se edita en el editor del post.', 'cad-theme'); ?></p>
            <p>
                <label for="cad-business-description-label"><strong><?php esc_html_e('Label superior', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-business-description-label" name="cad_business_description_label" class="widefat" value="<?php echo esc_attr($description_label); ?>" placeholder="<?php esc_attr_e('Capacidad tecnica', 'cad-theme'); ?>">
            </p>
        </div>

        <div class="cad-project-meta__section">
            <h4><?php esc_html_e('Estructura del area', 'cad-theme'); ?></h4>
            <p>
                <label for="cad-business-structure-label"><strong><?php esc_html_e('Label de seccion', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-business-structure-label" name="cad_business_structure_label" class="widefat" value="<?php echo esc_attr($structure_label); ?>">
            </p>
            <p>
                <label for="cad-business-structure-title"><strong><?php esc_html_e('Titulo de seccion', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-business-structure-title" name="cad_business_structure_title" class="widefat" value="<?php echo esc_attr($structure_title); ?>">
            </p>
            <div class="cad-repeatable" data-repeatable="business-subareas">
                <div class="cad-repeatable__list">
                    <?php foreach ($subareas as $index => $subarea) : ?>
                        <?php cad_theme_render_business_subarea_item($index, $subarea, $icon_options); ?>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button cad-repeatable__add" data-repeatable-add="business-subareas"><?php esc_html_e('Agregar subarea', 'cad-theme'); ?></button>
                <template class="cad-repeatable__template" data-repeatable-template="business-subareas">
                    <?php cad_theme_render_business_subarea_item('__INDEX__', array(), $icon_options); ?>
                </template>
            </div>
        </div>

        <div class="cad-project-meta__section">
            <h4><?php esc_html_e('Galeria editorial', 'cad-theme'); ?></h4>
            <p>
                <label for="cad-business-gallery-label"><strong><?php esc_html_e('Label de seccion', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-business-gallery-label" name="cad_business_gallery_label" class="widefat" value="<?php echo esc_attr($gallery_label); ?>">
            </p>
            <p>
                <label for="cad-business-gallery-title"><strong><?php esc_html_e('Titulo de seccion', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-business-gallery-title" name="cad_business_gallery_title" class="widefat" value="<?php echo esc_attr($gallery_title); ?>">
            </p>
            <div class="cad-project-gallery" data-project-gallery>
                <input type="hidden" id="cad-business-gallery-ids" name="cad_business_gallery_ids" value="<?php echo esc_attr($gallery_value); ?>" data-gallery-ids-input>
                <div class="cad-project-gallery__preview" data-project-gallery-preview>
                    <?php foreach ($gallery_ids as $image_id) : ?>
                        <div class="cad-project-gallery__item">
                            <?php echo wp_kses_post(wp_get_attachment_image($image_id, 'thumbnail')); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="cad-project-gallery__actions">
                    <button type="button" class="button cad-project-gallery__select"><?php esc_html_e('Seleccionar imagenes', 'cad-theme'); ?></button>
                    <button type="button" class="button cad-project-gallery__clear"><?php esc_html_e('Limpiar galeria', 'cad-theme'); ?></button>
                </div>
            </div>
        </div>

        <div class="cad-project-meta__section">
            <h4><?php esc_html_e('Proyectos relacionados', 'cad-theme'); ?></h4>
            <p>
                <label for="cad-business-projects-label"><strong><?php esc_html_e('Label de seccion', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-business-projects-label" name="cad_business_projects_label" class="widefat" value="<?php echo esc_attr($projects_label); ?>">
            </p>
            <p>
                <label for="cad-business-projects-title"><strong><?php esc_html_e('Titulo de seccion', 'cad-theme'); ?></strong></label><br>
                <input type="text" id="cad-business-projects-title" name="cad_business_projects_title" class="widefat" value="<?php echo esc_attr($projects_title); ?>">
            </p>
            <p class="description"><?php esc_html_e('Si no agregas items manuales, la pagina usara proyectos del sitio como respaldo.', 'cad-theme'); ?></p>
            <div class="cad-repeatable" data-repeatable="business-projects">
                <div class="cad-repeatable__list">
                    <?php foreach ($related_projects as $index => $related_project) : ?>
                        <?php cad_theme_render_business_related_project_item($index, $related_project); ?>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button cad-repeatable__add" data-repeatable-add="business-projects"><?php esc_html_e('Agregar proyecto', 'cad-theme'); ?></button>
                <template class="cad-repeatable__template" data-repeatable-template="business-projects">
                    <?php cad_theme_render_business_related_project_item('__INDEX__', array()); ?>
                </template>
            </div>
        </div>

        <div class="cad-project-meta__section">
            <h4><?php esc_html_e('Franja CTA final', 'cad-theme'); ?></h4>
            <p>
                <label for="cad-business-final-cta-text"><strong><?php esc_html_e('Mensaje principal', 'cad-theme'); ?></strong></label><br>
                <textarea id="cad-business-final-cta-text" name="cad_business_final_cta_text" class="widefat" rows="3"><?php echo esc_textarea($final_cta_text); ?></textarea>
            </p>
            <div class="cad-repeatable__row">
                <div class="cad-repeatable__fields" style="flex:1;">
                    <label for="cad-business-final-cta-primary-label"><strong><?php esc_html_e('Boton principal', 'cad-theme'); ?></strong></label>
                    <input type="text" id="cad-business-final-cta-primary-label" name="cad_business_final_cta_primary_label" class="widefat" value="<?php echo esc_attr($final_cta_primary_label); ?>">
                </div>
                <div class="cad-repeatable__fields" style="flex:1;">
                    <label for="cad-business-final-cta-primary-url"><strong><?php esc_html_e('URL principal', 'cad-theme'); ?></strong></label>
                    <input type="url" id="cad-business-final-cta-primary-url" name="cad_business_final_cta_primary_url" class="widefat" value="<?php echo esc_attr($final_cta_primary_url); ?>" placeholder="https://">
                </div>
            </div>
            <div class="cad-repeatable__row">
                <div class="cad-repeatable__fields" style="flex:1;">
                    <label for="cad-business-final-cta-secondary-label"><strong><?php esc_html_e('CTA complementario', 'cad-theme'); ?></strong></label>
                    <input type="text" id="cad-business-final-cta-secondary-label" name="cad_business_final_cta_secondary_label" class="widefat" value="<?php echo esc_attr($final_cta_secondary_label); ?>">
                </div>
                <div class="cad-repeatable__fields" style="flex:1;">
                    <label for="cad-business-final-cta-secondary-url"><strong><?php esc_html_e('URL complementario', 'cad-theme'); ?></strong></label>
                    <input type="url" id="cad-business-final-cta-secondary-url" name="cad_business_final_cta_secondary_url" class="widefat" value="<?php echo esc_attr($final_cta_secondary_url); ?>" placeholder="https://">
                </div>
            </div>
        </div>

        <?php cad_theme_render_project_icon_modal($icon_options); ?>
    </div>
    <?php
}

function cad_theme_business_area_settings_box($post)
{
    wp_nonce_field('cad_business_area_meta', 'cad_business_area_meta_nonce');

    $cta_label = get_post_meta($post->ID, '_cad_business_cta_label', true);
    $cta_url = get_post_meta($post->ID, '_cad_business_cta_url', true);
    ?>
    <p class="description"><?php esc_html_e('Define el CTA de la tarjeta en el home. Si la URL queda vacia, la tarjeta usara la pagina publica del area.', 'cad-theme'); ?></p>
    <p>
        <label for="cad-business-cta-label"><strong><?php esc_html_e('Texto del boton', 'cad-theme'); ?></strong></label><br>
        <input type="text" id="cad-business-cta-label" name="cad_business_cta_label" class="widefat" value="<?php echo esc_attr($cta_label); ?>" placeholder="<?php esc_attr_e('Ver proyectos', 'cad-theme'); ?>">
    </p>
    <p>
        <label for="cad-business-cta-url"><strong><?php esc_html_e('URL del boton', 'cad-theme'); ?></strong></label><br>
        <input type="url" id="cad-business-cta-url" name="cad_business_cta_url" class="widefat" value="<?php echo esc_url($cta_url); ?>" placeholder="https://">
    </p>
    <?php
}

function cad_theme_business_area_style_box($post)
{
    $tones = cad_theme_business_area_tones();
    $tone = get_post_meta($post->ID, '_cad_business_tone', true);
    if (empty($tone) || !isset($tones[$tone])) {
        $tone = 'is-blue';
    }
    ?>
    <p>
        <label for="cad-business-tone"><strong><?php esc_html_e('Tono del overlay', 'cad-theme'); ?></strong></label><br>
        <select id="cad-business-tone" name="cad_business_tone" class="widefat">
            <?php foreach ($tones as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($tone, $value); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p class="description"><?php esc_html_e('Usa la imagen destacada para el hero y la tarjeta del home.', 'cad-theme'); ?></p>
    <?php
}

function cad_theme_save_business_area_meta($post_id)
{
    if (!isset($_POST['cad_business_area_meta_nonce']) || !wp_verify_nonce($_POST['cad_business_area_meta_nonce'], 'cad_business_area_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = cad_theme_business_area_meta_fields();

    $cta_label = isset($_POST['cad_business_cta_label']) ? sanitize_text_field(wp_unslash($_POST['cad_business_cta_label'])) : '';
    if ($cta_label) {
        update_post_meta($post_id, '_cad_business_cta_label', $cta_label);
    } else {
        delete_post_meta($post_id, '_cad_business_cta_label');
    }

    $cta_url = isset($_POST['cad_business_cta_url']) ? esc_url_raw(wp_unslash($_POST['cad_business_cta_url'])) : '';
    if ($cta_url) {
        update_post_meta($post_id, '_cad_business_cta_url', $cta_url);
    } else {
        delete_post_meta($post_id, '_cad_business_cta_url');
    }

    $tone = isset($_POST['cad_business_tone']) ? sanitize_key(wp_unslash($_POST['cad_business_tone'])) : '';
    $tones = cad_theme_business_area_tones();
    if ($tone && isset($tones[$tone])) {
        update_post_meta($post_id, '_cad_business_tone', $tone);
    } else {
        delete_post_meta($post_id, '_cad_business_tone');
    }

    $text_fields = array(
        'badge_label'               => 'cad_business_badge_label',
        'badge_context'             => 'cad_business_badge_context',
        'hero_title_suffix'         => 'cad_business_hero_title_suffix',
        'hero_title_accent'         => 'cad_business_hero_title_accent',
        'meta_location'             => 'cad_business_meta_location',
        'meta_experience'           => 'cad_business_meta_experience',
        'meta_projects'             => 'cad_business_meta_projects',
        'description_label'         => 'cad_business_description_label',
        'structure_label'           => 'cad_business_structure_label',
        'structure_title'           => 'cad_business_structure_title',
        'gallery_label'             => 'cad_business_gallery_label',
        'gallery_title'             => 'cad_business_gallery_title',
        'projects_label'            => 'cad_business_projects_label',
        'projects_title'            => 'cad_business_projects_title',
        'final_cta_primary_label'   => 'cad_business_final_cta_primary_label',
        'final_cta_secondary_label' => 'cad_business_final_cta_secondary_label',
    );

    foreach ($text_fields as $field_key => $request_key) {
        $value = isset($_POST[$request_key]) ? sanitize_text_field(wp_unslash($_POST[$request_key])) : '';
        if ($value) {
            update_post_meta($post_id, $fields[$field_key], $value);
        } else {
            delete_post_meta($post_id, $fields[$field_key]);
        }
    }

    $textarea_fields = array(
        'final_cta_text' => 'cad_business_final_cta_text',
    );

    foreach ($textarea_fields as $field_key => $request_key) {
        $value = isset($_POST[$request_key]) ? sanitize_textarea_field(wp_unslash($_POST[$request_key])) : '';
        if ($value) {
            update_post_meta($post_id, $fields[$field_key], $value);
        } else {
            delete_post_meta($post_id, $fields[$field_key]);
        }
    }

    $url_fields = array(
        'final_cta_primary_url'   => 'cad_business_final_cta_primary_url',
        'final_cta_secondary_url' => 'cad_business_final_cta_secondary_url',
    );

    foreach ($url_fields as $field_key => $request_key) {
        $value = isset($_POST[$request_key]) ? esc_url_raw(wp_unslash($_POST[$request_key])) : '';
        if ($value) {
            update_post_meta($post_id, $fields[$field_key], $value);
        } else {
            delete_post_meta($post_id, $fields[$field_key]);
        }
    }

    $subareas = array();
    if (isset($_POST['cad_business_subareas']) && is_array($_POST['cad_business_subareas'])) {
        $subareas = cad_theme_normalize_business_subareas(wp_unslash($_POST['cad_business_subareas']));
    }
    if (!empty($subareas)) {
        update_post_meta($post_id, $fields['subareas'], $subareas);
    } else {
        delete_post_meta($post_id, $fields['subareas']);
    }

    $related_projects = array();
    if (isset($_POST['cad_business_related_projects']) && is_array($_POST['cad_business_related_projects'])) {
        $related_projects = cad_theme_normalize_business_related_projects(wp_unslash($_POST['cad_business_related_projects']));
    }
    if (!empty($related_projects)) {
        update_post_meta($post_id, $fields['related_projects'], $related_projects);
    } else {
        delete_post_meta($post_id, $fields['related_projects']);
    }

    $gallery_ids = array();
    if (isset($_POST['cad_business_gallery_ids'])) {
        $raw_ids = sanitize_text_field(wp_unslash($_POST['cad_business_gallery_ids']));
        $raw_ids = array_filter(array_map('trim', explode(',', $raw_ids)));
        foreach ($raw_ids as $raw_id) {
            $id = absint($raw_id);
            if ($id) {
                $gallery_ids[] = $id;
            }
        }
    }

    if (!empty($gallery_ids)) {
        update_post_meta($post_id, $fields['gallery_ids'], $gallery_ids);
    } else {
        delete_post_meta($post_id, $fields['gallery_ids']);
    }
}
add_action('save_post_cad_business_area', 'cad_theme_save_business_area_meta');

function cad_theme_save_indicator_meta($post_id)
{
    if (!isset($_POST['cad_indicator_meta_nonce']) || !wp_verify_nonce($_POST['cad_indicator_meta_nonce'], 'cad_indicator_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $value = '';
    if (isset($_POST['cad_indicator_value'])) {
        $value = sanitize_text_field(wp_unslash($_POST['cad_indicator_value']));
    }

    if ($value) {
        update_post_meta($post_id, '_cad_indicator_value', $value);
    } else {
        delete_post_meta($post_id, '_cad_indicator_value');
    }

    $period = '';
    if (isset($_POST['cad_indicator_period'])) {
        $period = sanitize_text_field(wp_unslash($_POST['cad_indicator_period']));
    }

    if ($period) {
        update_post_meta($post_id, '_cad_indicator_period', $period);
    } else {
        delete_post_meta($post_id, '_cad_indicator_period');
    }
}
add_action('save_post_cad_indicator', 'cad_theme_save_indicator_meta');

function cad_theme_save_footer_contact_meta($post_id)
{
    if (!isset($_POST['cad_footer_contact_meta_nonce']) || !wp_verify_nonce($_POST['cad_footer_contact_meta_nonce'], 'cad_footer_contact_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = cad_theme_footer_contact_meta_fields();

    $address = isset($_POST['cad_footer_address']) ? sanitize_textarea_field(wp_unslash($_POST['cad_footer_address'])) : '';
    $phone_label = isset($_POST['cad_footer_phone_label']) ? sanitize_text_field(wp_unslash($_POST['cad_footer_phone_label'])) : '';
    $phone_url = isset($_POST['cad_footer_phone_url']) ? esc_url_raw(wp_unslash($_POST['cad_footer_phone_url'])) : '';
    $social_links = array();

    if (isset($_POST['cad_footer_social']) && is_array($_POST['cad_footer_social'])) {
        $social_links = cad_theme_normalize_footer_social_links(wp_unslash($_POST['cad_footer_social']));
    }

    update_post_meta($post_id, $fields['address'], $address);
    update_post_meta($post_id, $fields['phone_label'], $phone_label);
    update_post_meta($post_id, $fields['phone_url'], $phone_url);
    update_post_meta($post_id, $fields['social_links'], $social_links);
}
add_action('save_post_cad_footer_contact', 'cad_theme_save_footer_contact_meta');

function cad_theme_save_project_meta($post_id)
{
    if (!isset($_POST['cad_project_meta_nonce']) || !wp_verify_nonce($_POST['cad_project_meta_nonce'], 'cad_project_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $category_icon = '';
    if (isset($_POST['cad_project_category_icon'])) {
        $category_icon = cad_theme_project_meta_icon_resolve(wp_unslash($_POST['cad_project_category_icon']), 'category');
    }
    if ($category_icon) {
        update_post_meta($post_id, '_cad_project_category_icon', $category_icon);
    } else {
        delete_post_meta($post_id, '_cad_project_category_icon');
    }

    $client_icon = '';
    if (isset($_POST['cad_project_client_icon'])) {
        $client_icon = cad_theme_project_meta_icon_resolve(wp_unslash($_POST['cad_project_client_icon']), 'client');
    }
    if ($client_icon) {
        update_post_meta($post_id, '_cad_project_client_icon', $client_icon);
    } else {
        delete_post_meta($post_id, '_cad_project_client_icon');
    }

    $location_icon = '';
    if (isset($_POST['cad_project_location_icon'])) {
        $location_icon = cad_theme_project_meta_icon_resolve(wp_unslash($_POST['cad_project_location_icon']), 'location');
    }
    if ($location_icon) {
        update_post_meta($post_id, '_cad_project_location_icon', $location_icon);
    } else {
        delete_post_meta($post_id, '_cad_project_location_icon');
    }

    $surface_icon = '';
    if (isset($_POST['cad_project_surface_icon'])) {
        $surface_icon = cad_theme_project_meta_icon_resolve(wp_unslash($_POST['cad_project_surface_icon']), 'surface');
    }
    if ($surface_icon) {
        update_post_meta($post_id, '_cad_project_surface_icon', $surface_icon);
    } else {
        delete_post_meta($post_id, '_cad_project_surface_icon');
    }

    $category = '';
    if (isset($_POST['cad_project_category'])) {
        $category = sanitize_text_field(wp_unslash($_POST['cad_project_category']));
    }
    if ($category) {
        update_post_meta($post_id, '_cad_project_category', $category);
    } else {
        delete_post_meta($post_id, '_cad_project_category');
    }

    $client = '';
    if (isset($_POST['cad_project_client'])) {
        $client = sanitize_text_field(wp_unslash($_POST['cad_project_client']));
    }
    if ($client) {
        update_post_meta($post_id, '_cad_project_client', $client);
    } else {
        delete_post_meta($post_id, '_cad_project_client');
    }

    $location = '';
    if (isset($_POST['cad_project_location'])) {
        $location = sanitize_text_field(wp_unslash($_POST['cad_project_location']));
    }
    if ($location) {
        update_post_meta($post_id, '_cad_project_location', $location);
    } else {
        delete_post_meta($post_id, '_cad_project_location');
    }

    $surface = '';
    if (isset($_POST['cad_project_surface'])) {
        $surface = sanitize_text_field(wp_unslash($_POST['cad_project_surface']));
    }
    if ($surface) {
        update_post_meta($post_id, '_cad_project_surface', $surface);
    } else {
        delete_post_meta($post_id, '_cad_project_surface');
    }

    $documents_title = '';
    if (isset($_POST['cad_project_documents_title'])) {
        $documents_title = sanitize_text_field(wp_unslash($_POST['cad_project_documents_title']));
    }
    if ($documents_title) {
        update_post_meta($post_id, '_cad_project_documents_title', $documents_title);
    } else {
        delete_post_meta($post_id, '_cad_project_documents_title');
    }

    $documents = array();
    if (isset($_POST['cad_project_documents']) && is_array($_POST['cad_project_documents'])) {
        foreach ($_POST['cad_project_documents'] as $document) {
            $label = isset($document['label']) ? sanitize_text_field(wp_unslash($document['label'])) : '';
            $url = isset($document['url']) ? esc_url_raw(wp_unslash($document['url'])) : '';

            if (!$label && !$url) {
                continue;
            }

            if ($url) {
                $documents[] = array(
                    'label' => $label,
                    'url'   => $url,
                );
            }
        }
    }

    if (!empty($documents)) {
        update_post_meta($post_id, '_cad_project_documents', $documents);
    } else {
        delete_post_meta($post_id, '_cad_project_documents');
    }

    $videos_title = '';
    if (isset($_POST['cad_project_videos_title'])) {
        $videos_title = sanitize_text_field(wp_unslash($_POST['cad_project_videos_title']));
    }
    if ($videos_title) {
        update_post_meta($post_id, '_cad_project_videos_title', $videos_title);
    } else {
        delete_post_meta($post_id, '_cad_project_videos_title');
    }

    $videos = array();
    if (isset($_POST['cad_project_videos']) && is_array($_POST['cad_project_videos'])) {
        foreach ($_POST['cad_project_videos'] as $video) {
            $label = isset($video['label']) ? sanitize_text_field(wp_unslash($video['label'])) : '';
            $url = isset($video['url']) ? esc_url_raw(wp_unslash($video['url'])) : '';

            if (!$label && !$url) {
                continue;
            }

            if ($url) {
                $videos[] = array(
                    'label' => $label,
                    'url'   => $url,
                );
            }
        }
    }

    if (!empty($videos)) {
        update_post_meta($post_id, '_cad_project_videos', $videos);
    } else {
        delete_post_meta($post_id, '_cad_project_videos');
    }

    $gallery_title = '';
    if (isset($_POST['cad_project_gallery_title'])) {
        $gallery_title = sanitize_text_field(wp_unslash($_POST['cad_project_gallery_title']));
    }
    if ($gallery_title) {
        update_post_meta($post_id, '_cad_project_gallery_title', $gallery_title);
    } else {
        delete_post_meta($post_id, '_cad_project_gallery_title');
    }

    $gallery_ids = array();
    if (isset($_POST['cad_project_gallery_ids'])) {
        $raw_ids = sanitize_text_field(wp_unslash($_POST['cad_project_gallery_ids']));
        $raw_ids = array_filter(array_map('trim', explode(',', $raw_ids)));
        foreach ($raw_ids as $raw_id) {
            $id = absint($raw_id);
            if ($id) {
                $gallery_ids[] = $id;
            }
        }
    }

    if (!empty($gallery_ids)) {
        update_post_meta($post_id, '_cad_project_gallery', $gallery_ids);
    } else {
        delete_post_meta($post_id, '_cad_project_gallery');
    }
}
add_action('save_post_cad_project', 'cad_theme_save_project_meta');

function cad_theme_page_nav_seed_items()
{
    $items = cad_theme_default_page_nav();
    $seed_items = array();

    foreach ($items as $item) {
        $seed_items[] = array(
            'title' => isset($item['label']) ? $item['label'] : '',
            'url'   => isset($item['url']) ? $item['url'] : '#',
        );
    }

    return $seed_items;
}

function cad_theme_render_default_primary_menu($args = array())
{
    unset($args);
    $items = cad_theme_default_main_menu();

    echo '<ul class="cad-menu">';
    foreach ($items as $item) {
        $url = isset($item['url']) ? (string) $item['url'] : '#';
        $label = isset($item['label']) ? (string) $item['label'] : '';
        $children = isset($item['children']) && is_array($item['children']) ? $item['children'] : array();
        $has_children = !empty($children);
        $item_class = $has_children ? ' class="cad-menu__item menu-item-has-children"' : ' class="cad-menu__item"';

        echo '<li' . $item_class . '>';
        echo '<a class="cad-menu__link" href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
        if ($has_children) {
            echo '<ul class="cad-submenu">';
            foreach ($children as $child) {
                $child_url = isset($child['url']) ? (string) $child['url'] : '#';
                $child_label = isset($child['label']) ? (string) $child['label'] : '';
                echo '<li class="cad-submenu__item">';
                echo '<a class="cad-submenu__link" href="' . esc_url($child_url) . '">' . esc_html($child_label) . '</a>';
                echo '</li>';
            }
            echo '</ul>';
        }
        echo '</li>';
    }
    echo '</ul>';
}

function cad_theme_render_default_page_nav($args = array())
{
    unset($args);
    $items = cad_theme_default_page_nav();

    echo '<ul class="cad-page-nav__list">';
    foreach ($items as $index => $item) {
        $url = isset($item['url']) ? (string) $item['url'] : '#';
        $label = isset($item['label']) ? (string) $item['label'] : '';
        $active = 0 === $index ? ' class="is-active"' : '';

        echo '<li>';
        echo '<a href="' . esc_url($url) . '"' . $active . '>' . esc_html($label) . '</a>';
        echo '</li>';
    }
    echo '</ul>';
}

function cad_theme_render_default_secondary_menu($args = array())
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

function cad_theme_disable_parent_menu_links($atts, $item, $args)
{
    if (empty($args->theme_location) || 'primary' !== $args->theme_location) {
        return $atts;
    }

    if (!empty($item->classes) && in_array('menu-item-has-children', $item->classes, true)) {
        $atts['href'] = '#';
        $atts['role'] = 'button';
        $atts['aria-haspopup'] = 'true';
        $atts['aria-expanded'] = 'false';
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'cad_theme_disable_parent_menu_links', 10, 3);

function cad_theme_menu_item_title($title, $item, $args, $depth)
{
    if (empty($args->theme_location) || 'primary' !== $args->theme_location) {
        return $title;
    }

    return '<span class="cad-menu__label">' . $title . '</span>';
}
add_filter('nav_menu_item_title', 'cad_theme_menu_item_title', 10, 4);

function cad_theme_append_menu_icon($item_output, $item, $depth, $args)
{
    if (empty($args->theme_location) || 'primary' !== $args->theme_location) {
        return $item_output;
    }

    $icon = get_post_meta($item->ID, '_cad_menu_icon', true);
    if (!$icon) {
        return $item_output;
    }

    $icon_html = '<span class="cad-menu__icon material-symbols-outlined" aria-hidden="true">' . esc_html($icon) . '</span>';

    return str_replace('</a>', $icon_html . '</a>', $item_output);
}
add_filter('walker_nav_menu_start_el', 'cad_theme_append_menu_icon', 10, 4);

function cad_theme_menu_icon_field($item_id, $item, $depth, $args)
{
    $icon = get_post_meta($item_id, '_cad_menu_icon', true);
    ?>
    <p class="description description-wide cad-menu-icon-field">
        <label for="cad-menu-icon-<?php echo esc_attr($item_id); ?>">
            <?php esc_html_e('Google icon (Material Symbols)', 'cad-theme'); ?><br>
            <input type="text" id="cad-menu-icon-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-custom" name="cad_menu_icon[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($icon); ?>" placeholder="<?php esc_attr_e('Ej: apartment, domain, business', 'cad-theme'); ?>" list="cad-menu-icons">
        </label>
        <button type="button" class="button cad-menu-icon-picker" data-target="cad-menu-icon-<?php echo esc_attr($item_id); ?>"><?php esc_html_e('Elegir icono', 'cad-theme'); ?></button>
    </p>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'cad_theme_menu_icon_field', 10, 4);

function cad_theme_menu_icon_datalist()
{
    $screen = get_current_screen();
    if (!$screen || 'nav-menus' !== $screen->id) {
        return;
    }
    ?>
    <datalist id="cad-menu-icons">
        <option value="apartment"></option>
        <option value="domain"></option>
        <option value="business"></option>
        <option value="engineering"></option>
        <option value="location_city"></option>
        <option value="construction"></option>
        <option value="home_work"></option>
        <option value="warehouse"></option>
        <option value="build"></option>
        <option value="insights"></option>
    </datalist>
    <?php
}
add_action('admin_footer', 'cad_theme_menu_icon_datalist');

function cad_theme_admin_menu_icon_assets($hook)
{
    if ('nav-menus.php' !== $hook) {
        return;
    }

    wp_enqueue_style(
        'cad-theme-icons',
        'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0',
        array(),
        null
    );

    wp_enqueue_style(
        'cad-theme-menu-icons',
        get_template_directory_uri() . '/assets/css/admin-menu-icons.css',
        array('cad-theme-icons'),
        wp_get_theme()->get('Version')
    );

    wp_enqueue_script(
        'cad-theme-menu-icons',
        get_template_directory_uri() . '/assets/js/admin-menu-icons.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    $icons = array(
        'apartment',
        'architecture',
        'article',
        'assessment',
        'assignment',
        'badge',
        'business',
        'build',
        'construction',
        'design_services',
        'domain',
        'engineering',
        'factory',
        'foundation',
        'gavel',
        'grid_view',
        'home_work',
        'insights',
        'inventory_2',
        'lab_research',
        'location_city',
        'maps_home_work',
        'modeling',
        'monitoring',
        'paid',
        'precision_manufacturing',
        'real_estate_agent',
        'roofing',
        'rule',
        'shield',
        'safety_check',
        'support_agent',
        'sync_alt',
        'task',
        'timeline',
        'tune',
        'warehouse',
        'water_drop',
        'work',
        'work_outline',
        'workspace_premium',
    );

    wp_localize_script('cad-theme-menu-icons', 'cadMenuIcons', $icons);
}
add_action('admin_enqueue_scripts', 'cad_theme_admin_menu_icon_assets');

function cad_theme_save_menu_icon($menu_id, $menu_item_db_id, $args)
{
    $icon = '';
    if (isset($_POST['cad_menu_icon'][$menu_item_db_id])) {
        $icon = sanitize_text_field(wp_unslash($_POST['cad_menu_icon'][$menu_item_db_id]));
    }

    if ($icon) {
        update_post_meta($menu_item_db_id, '_cad_menu_icon', $icon);
        return;
    }

    delete_post_meta($menu_item_db_id, '_cad_menu_icon');
}
add_action('wp_update_nav_menu_item', 'cad_theme_save_menu_icon', 10, 3);

function cad_theme_get_or_create_menu($name)
{
    $menu = wp_get_nav_menu_object($name);
    if ($menu) {
        return (int) $menu->term_id;
    }

    $menu_id = wp_create_nav_menu($name);
    if (is_wp_error($menu_id)) {
        return 0;
    }

    return (int) $menu_id;
}

function cad_theme_seed_menu_items($menu_id, $items)
{
    if (!$menu_id) {
        return;
    }

    $existing_items = wp_get_nav_menu_items($menu_id);
    if (!empty($existing_items)) {
        return;
    }

    $created_ids = array();
    foreach ($items as $item) {
        $args = array(
            'menu-item-title'  => $item['title'],
            'menu-item-url'    => $item['url'],
            'menu-item-status' => 'publish',
        );

        if (!empty($item['parent_key']) && isset($created_ids[$item['parent_key']])) {
            $args['menu-item-parent-id'] = $created_ids[$item['parent_key']];
        }

        if (!empty($item['target'])) {
            $args['menu-item-target'] = $item['target'];
        }

        $created_id = wp_update_nav_menu_item($menu_id, 0, $args);
        if (!is_wp_error($created_id) && !empty($item['key'])) {
            $created_ids[$item['key']] = (int) $created_id;
        }

        if (!is_wp_error($created_id) && !empty($item['icon'])) {
            update_post_meta($created_id, '_cad_menu_icon', sanitize_text_field($item['icon']));
        }
    }
}

function cad_theme_seed_menus()
{
    $locations = get_nav_menu_locations();
    $updated = false;

    $primary_id = empty($locations['primary']) ? cad_theme_get_or_create_menu(__('Main menu (CAD)', 'cad-theme')) : (int) $locations['primary'];
    if (empty($locations['primary']) && $primary_id) {
        $locations['primary'] = $primary_id;
        $updated = true;
    }

    $secondary_id = empty($locations['secondary']) ? cad_theme_get_or_create_menu(__('Secondary menu (CAD)', 'cad-theme')) : (int) $locations['secondary'];
    if (empty($locations['secondary']) && $secondary_id) {
        $locations['secondary'] = $secondary_id;
        $updated = true;
    }

    $page_nav_id = empty($locations['page_nav']) ? cad_theme_get_or_create_menu(__('Page section menu (CAD)', 'cad-theme')) : (int) $locations['page_nav'];
    if (empty($locations['page_nav']) && $page_nav_id) {
        $locations['page_nav'] = $page_nav_id;
        $updated = true;
    }

    if ($updated) {
        set_theme_mod('nav_menu_locations', $locations);
    }

    cad_theme_seed_menu_items(
        $primary_id,
        array(
            array(
                'key'   => 'inicio',
                'title' => __('Inicio', 'cad-theme'),
                'url'   => home_url('/'),
            ),
            array(
                'key'   => 'gobierno',
                'title' => __('Gobierno corporativo', 'cad-theme'),
                'url'   => home_url('/gobierno-corporativo/'),
            ),
            array(
                'key'   => 'areas',
                'title' => __('Areas de negocio', 'cad-theme'),
                'url'   => '#',
            ),
            array(
                'title'      => __('Construccion', 'cad-theme'),
                'url'        => home_url('/areas-de-negocio/construccion/'),
                'parent_key' => 'areas',
                'icon'       => 'construction',
            ),
            array(
                'title'      => __('Inmobiliaria', 'cad-theme'),
                'url'        => home_url('/areas-de-negocio/inmobiliaria/'),
                'parent_key' => 'areas',
                'icon'       => 'apartment',
            ),
            array(
                'title'      => __('Servicios', 'cad-theme'),
                'url'        => home_url('/areas-de-negocio/servicios/'),
                'parent_key' => 'areas',
                'icon'       => 'engineering',
            ),
        )
    );

    cad_theme_seed_menu_items(
        $secondary_id,
        array(
            array(
                'title' => __('Contactanos', 'cad-theme'),
                'url'   => home_url('/contacto/'),
            ),
            array(
                'title' => __('Canal de denuncias', 'cad-theme'),
                'url'   => home_url('/canal-de-denuncias/'),
            ),
            array(
                'title'  => __('Acceso proveedores', 'cad-theme'),
                'url'    => 'http://proveedores.cad.cl/',
                'target' => '_blank',
            ),
        )
    );

    cad_theme_seed_menu_items(
        $page_nav_id,
        cad_theme_page_nav_seed_items()
    );
}
add_action('after_switch_theme', 'cad_theme_seed_menus');

function cad_theme_cleanup_legacy_cta_menu()
{
    if (get_option('cad_legacy_cta_menu_removed')) {
        return;
    }

    $locations = get_theme_mod('nav_menu_locations');
    if (is_array($locations) && isset($locations['cta'])) {
        unset($locations['cta']);
        set_theme_mod('nav_menu_locations', $locations);
    }

    $candidates = array(
        'CTA menu (CAD)',
        'menu-cta-menu-cad',
        'cta-menu-cad',
    );

    foreach ($candidates as $candidate) {
        $menu = wp_get_nav_menu_object($candidate);
        if ($menu && !is_wp_error($menu)) {
            wp_delete_nav_menu((int) $menu->term_id);
        }
    }

    $menu_by_slug = get_term_by('slug', 'menu-cta-menu-cad', 'nav_menu');
    if ($menu_by_slug && !is_wp_error($menu_by_slug)) {
        wp_delete_nav_menu((int) $menu_by_slug->term_id);
    }

    update_option('cad_legacy_cta_menu_removed', 1);
}
add_action('admin_init', 'cad_theme_cleanup_legacy_cta_menu');

function cad_theme_maybe_seed_page_nav_menu()
{
    $locations = get_nav_menu_locations();
    $page_nav_id = !empty($locations['page_nav']) ? (int) $locations['page_nav'] : 0;

    if (!$page_nav_id) {
        $page_nav_id = cad_theme_get_or_create_menu(__('Page section menu (CAD)', 'cad-theme'));
        if ($page_nav_id) {
            $locations['page_nav'] = $page_nav_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }

    if (!$page_nav_id) {
        return;
    }

    cad_theme_seed_menu_items($page_nav_id, cad_theme_page_nav_seed_items());
}
add_action('admin_init', 'cad_theme_maybe_seed_page_nav_menu');

function cad_theme_seed_business_section_if_missing()
{
    $existing = get_posts(
        array(
            'post_type'      => 'cad_business_section',
            'posts_per_page' => 1,
            'post_status'    => array('publish', 'draft', 'pending', 'private'),
            'fields'         => 'ids',
        )
    );

    if (!empty($existing)) {
        $defaults = cad_theme_business_section_defaults();
        $post_id = (int) $existing[0];
        $post = get_post($post_id);

        if ($post && '' === trim((string) $post->post_content)) {
            $default_title = isset($defaults['title']) ? (string) $defaults['title'] : '';
            $default_content = isset($defaults['content']) ? (string) $defaults['content'] : '';

            if ($default_content && $post->post_title === $default_title) {
                wp_update_post(
                    array(
                        'ID'           => $post_id,
                        'post_content' => $default_content,
                    )
                );
            }
        }

        update_option('cad_business_section_seeded', 1);
        return;
    }

    $defaults = cad_theme_business_section_defaults();
    $post_id = wp_insert_post(
        array(
            'post_type'    => 'cad_business_section',
            'post_status'  => 'publish',
            'post_title'   => $defaults['title'],
            'post_content' => $defaults['content'],
        )
    );

    if (is_wp_error($post_id) || !$post_id) {
        return;
    }

    update_option('cad_business_section_seeded', 1);
}

function cad_theme_seed_business_section()
{
    cad_theme_seed_business_section_if_missing();
}
add_action('after_switch_theme', 'cad_theme_seed_business_section');

function cad_theme_seed_indicator_section_if_missing()
{
    $existing = get_posts(
        array(
            'post_type'      => 'cad_indicator_sec',
            'posts_per_page' => 1,
            'post_status'    => array('publish', 'draft', 'pending', 'private'),
            'fields'         => 'ids',
        )
    );

    if (!empty($existing)) {
        update_option('cad_indicator_sec_seeded', 1);
        return;
    }

    $defaults = cad_theme_indicator_section_defaults();
    $post_id = wp_insert_post(
        array(
            'post_type'   => 'cad_indicator_sec',
            'post_status' => 'publish',
            'post_title'  => $defaults['title'],
        )
    );

    if (is_wp_error($post_id) || !$post_id) {
        return;
    }

    update_option('cad_indicator_sec_seeded', 1);
}

function cad_theme_seed_indicator_section()
{
    cad_theme_seed_indicator_section_if_missing();
}
add_action('after_switch_theme', 'cad_theme_seed_indicator_section');

function cad_theme_maybe_seed_business_section()
{
    if (get_option('cad_business_section_seeded')) {
        return;
    }

    cad_theme_seed_business_section_if_missing();
}
add_action('admin_init', 'cad_theme_maybe_seed_business_section');

function cad_theme_maybe_seed_indicator_section()
{
    if (get_option('cad_indicator_sec_seeded')) {
        return;
    }

    cad_theme_seed_indicator_section_if_missing();
}
add_action('admin_init', 'cad_theme_maybe_seed_indicator_section');

function cad_theme_maybe_sideload_image($url, $post_id)
{
    if (!$url || !$post_id || has_post_thumbnail($post_id)) {
        return 0;
    }

    if (!function_exists('media_handle_sideload')) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $tmp = download_url($url);
    if (is_wp_error($tmp)) {
        return 0;
    }

    $path = wp_parse_url($url, PHP_URL_PATH);
    $name = $path ? basename($path) : 'cad-business-area.jpg';
    $file_array = array(
        'name'     => $name,
        'tmp_name' => $tmp,
    );

    $attachment_id = media_handle_sideload($file_array, $post_id);
    if (is_wp_error($attachment_id)) {
        @unlink($tmp);
        return 0;
    }

    set_post_thumbnail($post_id, $attachment_id);

    return (int) $attachment_id;
}

function cad_theme_generate_client_seed_logo_binary($label, $color = '#ffffff')
{
    if (!function_exists('imagecreatetruecolor')) {
        return '';
    }

    $label = trim((string) $label);
    if ('' === $label) {
        $label = 'CLIENTE';
    }

    $safe_label = strtoupper(preg_replace('/[^A-Za-z0-9&\- ]/', '', $label));
    if ('' === trim($safe_label)) {
        $safe_label = 'CLIENTE';
    }

    $width = 900;
    $height = 320;
    $image = imagecreatetruecolor($width, $height);
    if (!$image) {
        return '';
    }

    imagealphablending($image, false);
    imagesavealpha($image, true);
    $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
    imagefilledrectangle($image, 0, 0, $width, $height, $transparent);
    imagealphablending($image, true);

    $rgb = cad_theme_hex_to_rgb($color);
    $text_color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
    $line_color = imagecolorallocatealpha($image, $rgb[0], $rgb[1], $rgb[2], 80);

    imagefilledrectangle($image, 0, $height - 20, $width, $height - 12, $line_color);

    $font = 5;
    $max_width = $width - 80;
    $char_width = imagefontwidth($font);
    $max_chars = (int) floor($max_width / $char_width);
    if ($max_chars > 0 && strlen($safe_label) > $max_chars) {
        $safe_label = substr($safe_label, 0, max(1, $max_chars - 3)) . '...';
    }

    $text_width = imagefontwidth($font) * strlen($safe_label);
    $text_height = imagefontheight($font);
    $x = (int) max(20, ($width - $text_width) / 2);
    $y = (int) (($height - $text_height) / 2) - 4;
    imagestring($image, $font, $x, $y, $safe_label, $text_color);

    ob_start();
    imagepng($image);
    $binary = (string) ob_get_clean();
    imagedestroy($image);

    return $binary;
}

function cad_theme_create_client_seed_logo_attachment($post_id, $label, $color = '#ffffff')
{
    $post_id = (int) $post_id;
    if ($post_id < 1) {
        return 0;
    }

    if (has_post_thumbnail($post_id)) {
        return (int) get_post_thumbnail_id($post_id);
    }

    $binary = cad_theme_generate_client_seed_logo_binary($label, $color);
    if ('' === $binary) {
        return 0;
    }

    if (!function_exists('wp_upload_bits')) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }
    if (!function_exists('wp_generate_attachment_metadata')) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $filename_base = sanitize_title((string) $label);
    if ('' === $filename_base) {
        $filename_base = 'cliente';
    }
    $filename = sprintf('cad-client-%s.png', $filename_base);
    $upload = wp_upload_bits($filename, null, $binary);
    if (!empty($upload['error'])) {
        return 0;
    }

    $attachment_id = wp_insert_attachment(
        array(
            'post_mime_type' => 'image/png',
            'post_title'     => sanitize_text_field((string) $label),
            'post_status'    => 'inherit',
        ),
        $upload['file'],
        $post_id
    );

    if (is_wp_error($attachment_id) || !$attachment_id) {
        return 0;
    }

    $metadata = wp_generate_attachment_metadata($attachment_id, $upload['file']);
    if (!empty($metadata)) {
        wp_update_attachment_metadata($attachment_id, $metadata);
    }

    set_post_thumbnail($post_id, $attachment_id);

    return (int) $attachment_id;
}

function cad_theme_seed_clients_if_missing()
{
    $existing = get_posts(
        array(
            'post_type'      => 'cad_client',
            'posts_per_page' => 1,
            'post_status'    => array('publish', 'draft', 'pending', 'private'),
            'fields'         => 'ids',
        )
    );

    if (!empty($existing)) {
        update_option('cad_clients_seeded', 1);
        return;
    }

    $defaults = cad_theme_default_clients();
    foreach ($defaults as $index => $item) {
        $name = isset($item['name']) ? sanitize_text_field((string) $item['name']) : '';
        if ('' === $name) {
            continue;
        }

        $post_id = wp_insert_post(
            array(
                'post_type'   => 'cad_client',
                'post_status' => 'publish',
                'post_title'  => $name,
                'menu_order'  => (int) $index,
            )
        );

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        $logo_url = isset($item['logo']) ? esc_url_raw((string) $item['logo']) : '';
        $logo_color = isset($item['color']) ? (string) $item['color'] : '#ffffff';

        $attachment_id = 0;
        if ('' !== $logo_url) {
            $attachment_id = cad_theme_maybe_sideload_image($logo_url, $post_id);
        }
        if (!$attachment_id) {
            $attachment_id = cad_theme_create_client_seed_logo_attachment($post_id, $name, $logo_color);
        }

        if ($attachment_id) {
            $alt_text = sprintf(__('Logo de %s', 'cad-theme'), $name);
            update_post_meta($attachment_id, '_wp_attachment_image_alt', sanitize_text_field($alt_text));
        }
    }

    update_option('cad_clients_seeded', 1);
}

function cad_theme_seed_clients()
{
    cad_theme_seed_clients_if_missing();
}
add_action('after_switch_theme', 'cad_theme_seed_clients');

function cad_theme_maybe_seed_clients()
{
    if (get_option('cad_clients_seeded')) {
        return;
    }

    cad_theme_seed_clients_if_missing();
}
add_action('admin_init', 'cad_theme_maybe_seed_clients');

function cad_theme_client_admin_columns($columns)
{
    $result = array();
    foreach ($columns as $key => $label) {
        $result[$key] = $label;
        if ('title' === $key) {
            $result['cad_client_logo'] = __('Logo', 'cad-theme');
            $result['menu_order'] = __('Orden', 'cad-theme');
        }
    }

    return $result;
}
add_filter('manage_cad_client_posts_columns', 'cad_theme_client_admin_columns');

function cad_theme_client_admin_column_content($column, $post_id)
{
    if ('cad_client_logo' === $column) {
        $thumbnail_id = (int) get_post_thumbnail_id($post_id);
        if (!$thumbnail_id) {
            echo '&mdash;';
            return;
        }

        echo wp_kses_post(
            wp_get_attachment_image(
                $thumbnail_id,
                array(72, 40),
                false,
                array(
                    'style' => 'width:72px;height:40px;object-fit:contain;',
                    'alt'   => '',
                )
            )
        );
        return;
    }

    if ('menu_order' === $column) {
        echo esc_html((string) ((int) get_post_field('menu_order', $post_id)));
    }
}
add_action('manage_cad_client_posts_custom_column', 'cad_theme_client_admin_column_content', 10, 2);

function cad_theme_client_admin_sortable_columns($columns)
{
    $columns['menu_order'] = 'menu_order';
    return $columns;
}
add_filter('manage_edit-cad_client_sortable_columns', 'cad_theme_client_admin_sortable_columns');

function cad_theme_client_admin_default_order($query)
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ('cad_client' !== $query->get('post_type')) {
        return;
    }

    if ($query->get('orderby')) {
        return;
    }

    $query->set(
        'orderby',
        array(
            'menu_order' => 'ASC',
            'title'      => 'ASC',
        )
    );
    $query->set('order', 'ASC');
}
add_action('pre_get_posts', 'cad_theme_client_admin_default_order');

function cad_theme_backfill_business_area_images()
{
    if (get_option('cad_business_areas_image_backfill_done')) {
        return;
    }

    $defaults = cad_theme_default_business_cards();
    $images_by_title = array();

    foreach ($defaults as $item) {
        if (empty($item['title']) || empty($item['image'])) {
            continue;
        }

        $images_by_title[sanitize_title($item['title'])] = (string) $item['image'];
    }

    if (empty($images_by_title)) {
        update_option('cad_business_areas_image_backfill_done', 1);
        return;
    }

    $posts = get_posts(
        array(
            'post_type'      => 'cad_business_area',
            'posts_per_page' => -1,
            'post_status'    => array('publish', 'draft', 'pending', 'private'),
        )
    );

    foreach ($posts as $post) {
        if (has_post_thumbnail($post->ID)) {
            continue;
        }

        $key = sanitize_title($post->post_title);
        if (empty($images_by_title[$key])) {
            continue;
        }

        cad_theme_maybe_sideload_image($images_by_title[$key], $post->ID);
    }

    update_option('cad_business_areas_image_backfill_done', 1);
}
add_action('admin_init', 'cad_theme_backfill_business_area_images');

function cad_theme_project_gallery_seed_palette()
{
    return array(
        array('#dbeafe', '#93c5fd'),
        array('#ffe4e6', '#fda4af'),
        array('#dcfce7', '#86efac'),
        array('#fef3c7', '#fcd34d'),
        array('#ede9fe', '#c4b5fd'),
        array('#cffafe', '#67e8f9'),
        array('#ffe8cc', '#fdba74'),
        array('#fef9c3', '#fde047'),
        array('#d1fae5', '#6ee7b7'),
        array('#e0f2fe', '#7dd3fc'),
        array('#e2e8f0', '#94a3b8'),
        array('#fde68a', '#f59e0b'),
        array('#fecdd3', '#fb7185'),
        array('#ddd6fe', '#a78bfa'),
        array('#bae6fd', '#38bdf8'),
    );
}

function cad_theme_hex_to_rgb($hex)
{
    $hex = ltrim((string) $hex, '#');
    if (3 === strlen($hex)) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    if (6 !== strlen($hex)) {
        return array(148, 163, 184);
    }

    return array(
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2)),
    );
}

function cad_theme_generate_project_gallery_seed_binary($index)
{
    if (!function_exists('imagecreatetruecolor')) {
        return '';
    }

    $palette = cad_theme_project_gallery_seed_palette();
    if (empty($palette)) {
        return '';
    }

    $slot = max(1, (int) $index) - 1;
    $colors = $palette[$slot % count($palette)];
    $start_rgb = cad_theme_hex_to_rgb(isset($colors[0]) ? $colors[0] : '#dbeafe');
    $end_rgb = cad_theme_hex_to_rgb(isset($colors[1]) ? $colors[1] : '#93c5fd');

    $width = 1600;
    $height = 1000;
    $image = imagecreatetruecolor($width, $height);
    if (!$image) {
        return '';
    }

    for ($y = 0; $y < $height; $y++) {
        $ratio = $height > 1 ? ($y / ($height - 1)) : 0;
        $red = (int) round($start_rgb[0] + (($end_rgb[0] - $start_rgb[0]) * $ratio));
        $green = (int) round($start_rgb[1] + (($end_rgb[1] - $start_rgb[1]) * $ratio));
        $blue = (int) round($start_rgb[2] + (($end_rgb[2] - $start_rgb[2]) * $ratio));
        $line_color = imagecolorallocate($image, $red, $green, $blue);
        imageline($image, 0, $y, $width, $y, $line_color);
    }

    $glow_a = imagecolorallocatealpha($image, 255, 255, 255, 95);
    imagefilledellipse($image, (int) ($width * 0.2), (int) ($height * 0.22), (int) ($width * 0.36), (int) ($width * 0.36), $glow_a);
    $glow_b = imagecolorallocatealpha($image, 15, 23, 42, 112);
    imagefilledellipse($image, (int) ($width * 0.8), (int) ($height * 0.82), (int) ($width * 0.42), (int) ($width * 0.42), $glow_b);

    $text_color = imagecolorallocate($image, 15, 23, 42);
    $label = sprintf('Proyecto %02d', max(1, (int) $index));
    imagestring($image, 5, 26, 26, $label, $text_color);

    ob_start();
    imagepng($image);
    $binary = (string) ob_get_clean();
    imagedestroy($image);

    return $binary;
}

function cad_theme_create_project_gallery_seed_attachment($index)
{
    $index = max(1, (int) $index);
    $binary = cad_theme_generate_project_gallery_seed_binary($index);
    if ('' === $binary) {
        return 0;
    }

    if (!function_exists('wp_upload_bits')) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }
    if (!function_exists('wp_generate_attachment_metadata')) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $filename = sprintf('cad-project-gallery-%02d.png', $index);
    $upload = wp_upload_bits($filename, null, $binary);
    if (!empty($upload['error'])) {
        return 0;
    }

    $filetype = wp_check_filetype($upload['file'], null);
    $attachment_id = wp_insert_attachment(
        array(
            'post_mime_type' => !empty($filetype['type']) ? $filetype['type'] : 'image/png',
            'post_title'     => sprintf(__('Galeria proyecto %02d', 'cad-theme'), $index),
            'post_status'    => 'inherit',
        ),
        $upload['file']
    );

    if (is_wp_error($attachment_id) || !$attachment_id) {
        return 0;
    }

    $metadata = wp_generate_attachment_metadata($attachment_id, $upload['file']);
    if (!empty($metadata)) {
        wp_update_attachment_metadata($attachment_id, $metadata);
    }

    update_post_meta($attachment_id, '_cad_project_seed_gallery', '1');
    update_post_meta($attachment_id, '_cad_project_seed_gallery_index', $index);

    return (int) $attachment_id;
}

function cad_theme_get_project_gallery_seed_attachment_ids($count = 15)
{
    $count = max(1, (int) $count);
    $ids = array();

    for ($index = 1; $index <= $count; $index++) {
        $existing = get_posts(
            array(
                'post_type'      => 'attachment',
                'post_status'    => 'inherit',
                'posts_per_page' => 1,
                'fields'         => 'ids',
                'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                        'key'   => '_cad_project_seed_gallery',
                        'value' => '1',
                    ),
                    array(
                        'key'   => '_cad_project_seed_gallery_index',
                        'value' => (string) $index,
                    ),
                ),
            )
        );

        if (!empty($existing)) {
            $ids[] = (int) $existing[0];
            continue;
        }

        $created_id = cad_theme_create_project_gallery_seed_attachment($index);
        if ($created_id) {
            $ids[] = $created_id;
        }
    }

    return array_values(array_filter(array_map('absint', $ids)));
}

function cad_theme_backfill_project_gallery_media()
{
    if (get_option('cad_projects_gallery_media_backfilled')) {
        return;
    }

    $project_ids = get_posts(
        array(
            'post_type'      => 'cad_project',
            'posts_per_page' => -1,
            'post_status'    => array('publish', 'draft', 'pending', 'private'),
            'fields'         => 'ids',
        )
    );

    if (empty($project_ids)) {
        update_option('cad_projects_gallery_media_backfilled', 1);
        return;
    }

    $seed_ids = cad_theme_get_project_gallery_seed_attachment_ids(15);
    if (empty($seed_ids)) {
        return;
    }

    foreach ($project_ids as $project_id) {
        $gallery_ids = get_post_meta($project_id, '_cad_project_gallery', true);
        if (!is_array($gallery_ids)) {
            $gallery_ids = array();
        }
        $gallery_ids = array_values(array_filter(array_map('absint', $gallery_ids)));

        if (count($gallery_ids) >= 15) {
            continue;
        }

        $updated_gallery = $gallery_ids;
        foreach ($seed_ids as $seed_id) {
            if (in_array($seed_id, $updated_gallery, true)) {
                continue;
            }

            $updated_gallery[] = $seed_id;
            if (count($updated_gallery) >= 15) {
                break;
            }
        }

        if ($updated_gallery !== $gallery_ids) {
            update_post_meta($project_id, '_cad_project_gallery', $updated_gallery);
        }
    }

    update_option('cad_projects_gallery_media_backfilled', 1);
}
add_action('admin_init', 'cad_theme_backfill_project_gallery_media');

function cad_theme_autofill_project_gallery_media_on_save($post_id, $post, $update)
{
    if (!$post || 'cad_project' !== $post->post_type) {
        return;
    }
    if ($update && wp_is_post_revision($post_id)) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $gallery_ids = get_post_meta($post_id, '_cad_project_gallery', true);
    if (!is_array($gallery_ids)) {
        $gallery_ids = array();
    }
    $gallery_ids = array_values(array_filter(array_map('absint', $gallery_ids)));

    if (count($gallery_ids) >= 15) {
        return;
    }

    $seed_ids = cad_theme_get_project_gallery_seed_attachment_ids(15);
    if (empty($seed_ids)) {
        return;
    }

    $updated_gallery = $gallery_ids;
    foreach ($seed_ids as $seed_id) {
        if (in_array($seed_id, $updated_gallery, true)) {
            continue;
        }

        $updated_gallery[] = $seed_id;
        if (count($updated_gallery) >= 15) {
            break;
        }
    }

    if ($updated_gallery !== $gallery_ids) {
        update_post_meta($post_id, '_cad_project_gallery', $updated_gallery);
    }
}
add_action('save_post_cad_project', 'cad_theme_autofill_project_gallery_media_on_save', 20, 3);

function cad_theme_seed_business_areas_if_missing()
{
    $existing = get_posts(
        array(
            'post_type'      => 'cad_business_area',
            'posts_per_page' => 1,
            'post_status'    => array('publish', 'draft', 'pending', 'private'),
            'fields'         => 'ids',
        )
    );

    if (!empty($existing)) {
        update_option('cad_business_areas_seeded', 1);
        return;
    }

    $defaults = cad_theme_default_business_cards();
    foreach ($defaults as $index => $item) {
        $post_id = wp_insert_post(
            array(
                'post_type'    => 'cad_business_area',
                'post_status'  => 'publish',
                'post_title'   => isset($item['title']) ? (string) $item['title'] : '',
                'post_content' => isset($item['description']) ? (string) $item['description'] : '',
                'menu_order'   => (int) $index,
            )
        );

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        if (!empty($item['cta'])) {
            update_post_meta($post_id, '_cad_business_cta_label', sanitize_text_field($item['cta']));
        }

        if (!empty($item['url'])) {
            update_post_meta($post_id, '_cad_business_cta_url', esc_url_raw($item['url']));
        }

        if (!empty($item['tone'])) {
            update_post_meta($post_id, '_cad_business_tone', sanitize_key($item['tone']));
        }

        if (!empty($item['image'])) {
            cad_theme_maybe_sideload_image($item['image'], $post_id);
        }
    }

    update_option('cad_business_areas_seeded', 1);
}

function cad_theme_seed_business_areas()
{
    cad_theme_seed_business_areas_if_missing();
}
add_action('after_switch_theme', 'cad_theme_seed_business_areas');

function cad_theme_seed_indicators_if_missing()
{
    $existing = get_posts(
        array(
            'post_type'      => 'cad_indicator',
            'posts_per_page' => 1,
            'post_status'    => array('publish', 'draft', 'pending', 'private'),
            'fields'         => 'ids',
        )
    );

    if (!empty($existing)) {
        update_option('cad_indicators_seeded', 1);
        return;
    }

    $defaults = cad_theme_default_indicators();
    foreach ($defaults as $index => $item) {
        $post_id = wp_insert_post(
            array(
                'post_type'   => 'cad_indicator',
                'post_status' => 'publish',
                'post_title'  => isset($item['label']) ? (string) $item['label'] : '',
                'menu_order'  => (int) $index,
            )
        );

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        if (!empty($item['value'])) {
            update_post_meta($post_id, '_cad_indicator_value', sanitize_text_field($item['value']));
        }

        if (!empty($item['period'])) {
            update_post_meta($post_id, '_cad_indicator_period', sanitize_text_field($item['period']));
        }
    }

    update_option('cad_indicators_seeded', 1);
}

function cad_theme_seed_indicators()
{
    cad_theme_seed_indicators_if_missing();
}
add_action('after_switch_theme', 'cad_theme_seed_indicators');

function cad_theme_maybe_seed_business_areas()
{
    if (get_option('cad_business_areas_seeded')) {
        return;
    }

    cad_theme_seed_business_areas_if_missing();
}
add_action('admin_init', 'cad_theme_maybe_seed_business_areas');

function cad_theme_maybe_seed_indicators()
{
    if (get_option('cad_indicators_seeded')) {
        return;
    }

    cad_theme_seed_indicators_if_missing();
}
add_action('admin_init', 'cad_theme_maybe_seed_indicators');

function cad_theme_seed_home_intro_if_missing()
{
    $existing = get_posts(
        array(
            'post_type'      => 'cad_home_intro',
            'posts_per_page' => 1,
            'post_status'    => array('publish', 'draft', 'pending', 'private'),
            'fields'         => 'ids',
        )
    );

    if (!empty($existing)) {
        update_option('cad_home_intro_seeded', 1);
        return;
    }

    $defaults = cad_theme_home_intro_defaults();
    $post_id = wp_insert_post(
        array(
            'post_type'    => 'cad_home_intro',
            'post_status'  => 'publish',
            'post_title'   => $defaults['title'],
            'post_content' => $defaults['content'],
            'post_name'    => 'somos',
        )
    );

    if (is_wp_error($post_id) || !$post_id) {
        return;
    }

    update_option('cad_home_intro_seeded', 1);
}

function cad_theme_seed_home_intro()
{
    cad_theme_seed_home_intro_if_missing();
}
add_action('after_switch_theme', 'cad_theme_seed_home_intro');

function cad_theme_maybe_seed_home_intro()
{
    if (get_option('cad_home_intro_seeded')) {
        return;
    }

    cad_theme_seed_home_intro_if_missing();
}
add_action('admin_init', 'cad_theme_maybe_seed_home_intro');

function cad_theme_seed_footer_contact_if_missing()
{
    $existing = get_posts(
        array(
            'post_type'      => 'cad_footer_contact',
            'posts_per_page' => 1,
            'post_status'    => array('publish', 'draft', 'pending', 'private'),
            'fields'         => 'ids',
        )
    );

    $defaults = cad_theme_footer_contact_defaults();
    $fields = cad_theme_footer_contact_meta_fields();
    $default_social_links = cad_theme_normalize_footer_social_links($defaults['social_links']);

    if (!empty($existing)) {
        $post_id = (int) $existing[0];

        if (!metadata_exists('post', $post_id, $fields['address'])) {
            update_post_meta($post_id, $fields['address'], $defaults['address']);
        }

        if (!metadata_exists('post', $post_id, $fields['phone_label'])) {
            update_post_meta($post_id, $fields['phone_label'], $defaults['phone_label']);
        }

        if (!metadata_exists('post', $post_id, $fields['phone_url'])) {
            update_post_meta($post_id, $fields['phone_url'], $defaults['phone_url']);
        }

        if (!metadata_exists('post', $post_id, $fields['social_links'])) {
            update_post_meta($post_id, $fields['social_links'], $default_social_links);
        }

        update_option('cad_footer_contact_seeded', 1);
        return;
    }

    $post_id = wp_insert_post(
        array(
            'post_type'   => 'cad_footer_contact',
            'post_status' => 'publish',
            'post_title'  => $defaults['title'],
        )
    );

    if (is_wp_error($post_id) || !$post_id) {
        return;
    }

    update_post_meta($post_id, $fields['address'], $defaults['address']);
    update_post_meta($post_id, $fields['phone_label'], $defaults['phone_label']);
    update_post_meta($post_id, $fields['phone_url'], $defaults['phone_url']);
    update_post_meta($post_id, $fields['social_links'], $default_social_links);
    update_option('cad_footer_contact_seeded', 1);
}

function cad_theme_seed_footer_contact()
{
    cad_theme_seed_footer_contact_if_missing();
}
add_action('after_switch_theme', 'cad_theme_seed_footer_contact');

function cad_theme_maybe_seed_footer_contact()
{
    if (get_option('cad_footer_contact_seeded')) {
        return;
    }

    cad_theme_seed_footer_contact_if_missing();
}
add_action('admin_init', 'cad_theme_maybe_seed_footer_contact');

function cad_theme_seed_video_banner()
{
    $existing = get_posts(
        array(
            'post_type'      => 'cad_video_banner',
            'posts_per_page' => 1,
            'post_status'    => array('publish', 'draft', 'pending', 'private'),
            'fields'         => 'ids',
        )
    );

    if (!empty($existing)) {
        return;
    }

    $post_id = wp_insert_post(
        array(
            'post_type'   => 'cad_video_banner',
            'post_status' => 'publish',
            'post_title'  => __('Video Banner principal', 'cad-theme'),
        )
    );

    if (is_wp_error($post_id) || !$post_id) {
        return;
    }

    $defaults = cad_theme_video_banner_defaults();
    $fields = cad_theme_video_banner_meta_fields();

    update_post_meta($post_id, $fields['mp4'], $defaults['mp4']);
    update_post_meta($post_id, $fields['webm'], $defaults['webm']);
    update_post_meta($post_id, $fields['fallback'], $defaults['fallback']);
    update_post_meta($post_id, $fields['youtube'], $defaults['youtube']);
    update_post_meta($post_id, $fields['headline_1'], $defaults['headline_1']);
    update_post_meta($post_id, $fields['headline_2'], $defaults['headline_2']);
    update_post_meta($post_id, $fields['label_play'], $defaults['label_play']);
    update_post_meta($post_id, $fields['label_pause'], $defaults['label_pause']);
    update_post_meta($post_id, $fields['show_video'], $defaults['show_video'] ? '1' : '0');
    update_post_meta($post_id, $fields['show_mp4'], $defaults['show_mp4'] ? '1' : '0');
    update_post_meta($post_id, $fields['show_webm'], $defaults['show_webm'] ? '1' : '0');
    update_post_meta($post_id, $fields['show_fallback'], $defaults['show_fallback'] ? '1' : '0');
    update_post_meta($post_id, $fields['show_headline'], $defaults['show_headline'] ? '1' : '0');
    update_post_meta($post_id, $fields['show_button'], $defaults['show_button'] ? '1' : '0');
}
add_action('after_switch_theme', 'cad_theme_seed_video_banner');

function cad_theme_limit_upload_size($size)
{
    $limit = 4 * 1024 * 1024;
    return min($size, $limit);
}
add_filter('upload_size_limit', 'cad_theme_limit_upload_size');

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

require_once get_template_directory() . '/inc/contact/bootstrap.php';
