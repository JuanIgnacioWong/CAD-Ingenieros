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
            'cta'       => __('Sidebar CTA menu', 'cad-theme'),
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
        'add_new'            => __('Agregar nuevo', 'cad-theme'),
        'add_new_item'       => __('Agregar nuevo Video Banner', 'cad-theme'),
        'edit_item'          => __('Editar Video Banner', 'cad-theme'),
        'new_item'           => __('Nuevo Video Banner', 'cad-theme'),
        'view_item'          => __('Ver Video Banner', 'cad-theme'),
        'search_items'       => __('Buscar Video Banner', 'cad-theme'),
        'not_found'          => __('No se encontraron Video Banner', 'cad-theme'),
        'not_found_in_trash' => __('No hay Video Banner en la papelera', 'cad-theme'),
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
            'url'         => home_url('/proyectos/construccion/'),
            'cta'         => __('Ver proyectos', 'cad-theme'),
            'image'       => 'https://ebco.cl/assets/pages/home/ebco-areas-negocio-construccion-v3.jpg',
            'tone'        => 'is-blue',
        ),
        array(
            'title'       => __('Inmobiliaria', 'cad-theme'),
            'description' => __('Proyectos inmobiliarios destinados a comercializacion de casas y edificacion para viviendas, oficinas comerciales y renta.', 'cad-theme'),
            'url'         => home_url('/proyectos/inmobiliaria/'),
            'cta'         => __('Ver proyectos', 'cad-theme'),
            'image'       => 'https://ebco.cl/assets/pages/home/ebco-bg-area-negocio-inmobiliaria-v2.jpg',
            'tone'        => 'is-indigo',
        ),
        array(
            'title'       => __('Servicios', 'cad-theme'),
            'description' => __('Orientados a entregar servicios vinculados al sector, buscando maximizar la eficiencia en la gestion de proyectos.', 'cad-theme'),
            'url'         => home_url('/proyectos/servicios/'),
            'cta'         => __('Ver servicios', 'cad-theme'),
            'image'       => 'https://ebco.cl/assets/pages/home/ebco-areas-negocio-servicios.jpg',
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

function cad_theme_render_default_secondary_menu($args = array())
{
    unset($args);
    $items = cad_theme_default_footer_links();

    echo '<ul class="cad-sidebar__quick-links-list">';
    foreach ($items as $item) {
        $url = isset($item['url']) ? (string) $item['url'] : '#';
        $label = isset($item['label']) ? (string) $item['label'] : '';
        $new = !empty($item['new']);
        $target = $new ? ' target="_blank" rel="noopener noreferrer"' : '';

        echo '<li class="cad-sidebar__quick-links-item">';
        echo '<a class="cad-sidebar__quick-link" href="' . esc_url($url) . '"' . $target . '>' . esc_html($label) . '</a>';
        echo '</li>';
    }
    echo '</ul>';
}

function cad_theme_render_default_cta_menu($args = array())
{
    unset($args);
    $url = home_url('/sumate-a-cad/');
    $label = __('Sumate a CAD', 'cad-theme');

    echo '<ul class="cad-join-menu">';
    echo '<li class="cad-join-menu__item">';
    echo '<a class="cad-join-btn" href="' . esc_url($url) . '"><span>' . esc_html($label) . '</span></a>';
    echo '</li>';
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

    $cta_id = empty($locations['cta']) ? cad_theme_get_or_create_menu(__('CTA menu (CAD)', 'cad-theme')) : (int) $locations['cta'];
    if (empty($locations['cta']) && $cta_id) {
        $locations['cta'] = $cta_id;
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
        $cta_id,
        array(
            array(
                'title' => __('Sumate a CAD', 'cad-theme'),
                'url'   => home_url('/sumate-a-cad/'),
            ),
        )
    );
}
add_action('after_switch_theme', 'cad_theme_seed_menus');

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
