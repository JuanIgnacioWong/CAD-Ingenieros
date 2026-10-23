<?php

if (!defined('ABSPATH')) {
    exit;
}

function cad_theme_get_masonry_gallery_item($attachment_id, $preview_size = 'large')
{
    $attachment_id = absint($attachment_id);
    $mime = (string) get_post_mime_type($attachment_id);
    if (!$attachment_id || 0 !== strpos($mime, 'image/')) {
        return array();
    }

    $metadata = wp_get_attachment_metadata($attachment_id);
    $full = wp_get_attachment_image_src($attachment_id, 'full');
    $preview = wp_get_attachment_image_src($attachment_id, $preview_size);
    if (!$full || !$preview) {
        return array();
    }

    $width = !empty($metadata['width']) ? absint($metadata['width']) : absint($full[1]);
    $height = !empty($metadata['height']) ? absint($metadata['height']) : absint($full[2]);
    if (!$width || !$height) {
        return array();
    }

    $alt = trim((string) get_post_meta($attachment_id, '_wp_attachment_image_alt', true));
    if ('' === $alt) {
        $alt = trim((string) get_the_title($attachment_id));
    }

    return array(
        'id'      => $attachment_id,
        'src'     => (string) $preview[0],
        'srcset'  => (string) wp_get_attachment_image_srcset($attachment_id, $preview_size),
        'sizes'   => (string) wp_get_attachment_image_sizes($attachment_id, $preview_size),
        'full'    => (string) $full[0],
        'width'   => $width,
        'height'  => $height,
        'alt'     => $alt,
        'caption' => trim((string) wp_get_attachment_caption($attachment_id)),
    );
}

function cad_theme_get_masonry_gallery_items($attachment_ids, $preview_size = 'large')
{
    $items = array();
    $seen = array();

    foreach ((array) $attachment_ids as $attachment_id) {
        $attachment_id = absint($attachment_id);
        if (!$attachment_id || isset($seen[$attachment_id])) {
            continue;
        }

        $item = cad_theme_get_masonry_gallery_item($attachment_id, $preview_size);
        if (empty($item)) {
            continue;
        }

        $seen[$attachment_id] = true;
        $items[] = $item;
    }

    return $items;
}

function cad_theme_masonry_gallery_ratio($item)
{
    $width = isset($item['width']) ? (float) $item['width'] : 1;
    $height = isset($item['height']) ? (float) $item['height'] : 1;

    return $height > 0 ? max(0.05, $width / $height) : 1;
}

function cad_theme_render_masonry_gallery_image($item, $loading = 'lazy', $static = false)
{
    $src = isset($item['src']) ? (string) $item['src'] : '';
    $full = isset($item['full']) ? (string) $item['full'] : $src;
    $alt = isset($item['alt']) ? (string) $item['alt'] : '';
    $width = isset($item['width']) ? absint($item['width']) : 0;
    $height = isset($item['height']) ? absint($item['height']) : 0;
    $srcset = isset($item['srcset']) ? (string) $item['srcset'] : '';
    $sizes = isset($item['sizes']) ? (string) $item['sizes'] : '';

    if ($static) {
        echo '<a class="cad-masonry-gallery__static-link" href="' . esc_url($full) . '">';
    }
    ?>
    <img
        src="<?php echo esc_url($src); ?>"
        <?php if ($srcset) : ?>srcset="<?php echo esc_attr($srcset); ?>"<?php endif; ?>
        <?php if ($sizes) : ?>sizes="<?php echo esc_attr($sizes); ?>"<?php endif; ?>
        <?php if ($width) : ?>width="<?php echo esc_attr((string) $width); ?>"<?php endif; ?>
        <?php if ($height) : ?>height="<?php echo esc_attr((string) $height); ?>"<?php endif; ?>
        alt="<?php echo esc_attr($alt); ?>"
        loading="<?php echo esc_attr($loading); ?>"
        decoding="async"
    />
    <?php
    if ($static) {
        echo '</a>';
    }
}

function cad_theme_render_masonry_gallery_item($item, $index, $static = false)
{
    $index = absint($index);
    $ratio = cad_theme_masonry_gallery_ratio($item);
    $caption = isset($item['caption']) ? trim((string) $item['caption']) : '';
    $alt = isset($item['alt']) ? (string) $item['alt'] : '';
    $full = isset($item['full']) ? (string) $item['full'] : '';
    $loading = $index < 4 ? 'eager' : 'lazy';
    ?>
    <figure
        class="cad-masonry-gallery__item"
        data-cad-gallery-item
        data-gallery-index="<?php echo esc_attr((string) $index); ?>"
        style="--cad-gallery-ratio: <?php echo esc_attr((string) $ratio); ?>;"
    >
        <?php if ($static) : ?>
            <?php cad_theme_render_masonry_gallery_image($item, $loading, true); ?>
        <?php else : ?>
            <button
                type="button"
                class="cad-masonry-gallery__trigger"
                data-gallery-open
                data-gallery-index="<?php echo esc_attr((string) $index); ?>"
                aria-haspopup="dialog"
                aria-label="<?php echo esc_attr(sprintf(__('Ampliar imagen: %s', 'cad-theme'), $alt ? $alt : __('sin titulo', 'cad-theme'))); ?>"
                data-full-src="<?php echo esc_url($full); ?>"
            >
                <?php cad_theme_render_masonry_gallery_image($item, $loading); ?>
            </button>
        <?php endif; ?>
        <?php if ($caption) : ?>
            <figcaption class="cad-masonry-gallery__caption"><?php echo esc_html($caption); ?></figcaption>
        <?php endif; ?>
    </figure>
    <?php
}

function cad_theme_render_masonry_gallery($items, $args = array())
{
    $items = array_values(array_filter((array) $items));
    if (empty($items)) {
        return '';
    }

    $defaults = array(
        'id'     => wp_unique_id('cad-masonry-gallery-'),
        'kicker' => __('Galeria', 'cad-theme'),
        'title'  => __('Imagenes', 'cad-theme'),
    );
    $args = wp_parse_args($args, $defaults);
    if ('' === trim((string) $args['kicker'])) {
        $args['kicker'] = $defaults['kicker'];
    }
    if ('' === trim((string) $args['title'])) {
        $args['title'] = $defaults['title'];
    }
    $total = count($items);
    $initial_count = min(8, $total);
    $label_id = sanitize_html_class((string) $args['id']) . '-title';
    $initial_end = max(1, $initial_count);
    $progress = min(100, ($initial_end / $total) * 100);

    ob_start();
    ?>
    <section
        id="<?php echo esc_attr((string) $args['id']); ?>"
        class="cad-masonry-gallery"
        data-cad-masonry-gallery
        data-gallery-total="<?php echo esc_attr((string) $total); ?>"
        aria-labelledby="<?php echo esc_attr($label_id); ?>"
    >
        <header class="cad-masonry-gallery__header">
            <div class="cad-masonry-gallery__heading">
                <span class="cad-masonry-gallery__kicker"><?php echo esc_html((string) $args['kicker']); ?></span>
                <h2 id="<?php echo esc_attr($label_id); ?>"><?php echo esc_html((string) $args['title']); ?></h2>
            </div>
            <?php if ($total > 1) : ?>
                <div class="cad-masonry-gallery__summary">
                    <span class="cad-masonry-gallery__status" data-gallery-status aria-live="polite">
                        <?php echo esc_html(sprintf('%02d–%02d / %d', 1, $initial_end, $total)); ?>
                    </span>
                    <div
                        class="cad-masonry-gallery__progress"
                        role="progressbar"
                        aria-label="<?php esc_attr_e('Progreso de la galeria', 'cad-theme'); ?>"
                        aria-valuemin="1"
                        aria-valuemax="<?php echo esc_attr((string) $total); ?>"
                        aria-valuenow="<?php echo esc_attr((string) $initial_end); ?>"
                    >
                        <span data-gallery-progress style="width: <?php echo esc_attr((string) $progress); ?>%;"></span>
                    </div>
                </div>
            <?php endif; ?>
        </header>

        <div class="cad-masonry-gallery__viewport" data-gallery-viewport tabindex="0">
            <div class="cad-masonry-gallery__track" data-gallery-track>
                <?php for ($index = 0; $index < $initial_count; $index++) : ?>
                    <?php cad_theme_render_masonry_gallery_item($items[$index], $index); ?>
                <?php endfor; ?>
                <span class="cad-masonry-gallery__sentinel" data-gallery-sentinel aria-hidden="true"></span>
            </div>
            <p class="cad-masonry-gallery__loading" data-gallery-loading hidden><?php esc_html_e('Cargando imagenes...', 'cad-theme'); ?></p>
        </div>

        <?php if ($total > 1) : ?>
            <div class="cad-masonry-gallery__controls" data-gallery-controls>
                <button type="button" class="cad-masonry-gallery__nav" data-gallery-prev aria-label="<?php esc_attr_e('Ver imagenes anteriores', 'cad-theme'); ?>" disabled>
                    <span aria-hidden="true">&larr;</span>
                </button>
                <button type="button" class="cad-masonry-gallery__nav" data-gallery-next aria-label="<?php esc_attr_e('Ver imagenes siguientes', 'cad-theme'); ?>"<?php echo $total > $initial_count ? '' : ' disabled'; ?>>
                    <span aria-hidden="true">&rarr;</span>
                </button>
            </div>
        <?php endif; ?>

        <script type="application/json" data-cad-gallery-manifest><?php echo wp_json_encode($items, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>

        <noscript>
            <style>#<?php echo esc_attr((string) $args['id']); ?> [data-gallery-track] { display: none; }</style>
            <div class="cad-masonry-gallery__noscript" aria-label="<?php esc_attr_e('Galeria completa', 'cad-theme'); ?>">
                <?php foreach ($items as $index => $item) : ?>
                    <?php cad_theme_render_masonry_gallery_item($item, $index, true); ?>
                <?php endforeach; ?>
            </div>
        </noscript>
    </section>
    <?php

    return (string) ob_get_clean();
}

function cad_theme_masonry_gallery_project_ids($post_id)
{
    $gallery_ids = get_post_meta($post_id, '_cad_project_gallery', true);
    if (!is_array($gallery_ids)) {
        $gallery_ids = array();
    }

    if (function_exists('get_field')) {
        $acf_gallery = get_field('galeria_proyecto', $post_id);
        if (!empty($acf_gallery)) {
            foreach ((array) $acf_gallery as $item) {
                if (is_numeric($item)) {
                    $gallery_ids[] = absint($item);
                } elseif (is_array($item) && isset($item['ID'])) {
                    $gallery_ids[] = absint($item['ID']);
                }
            }
        }
    }

    if (function_exists('cad_theme_filter_project_gallery_attachment_ids')) {
        $gallery_ids = cad_theme_filter_project_gallery_attachment_ids($gallery_ids);
    }

    return array_values(array_unique(array_filter(array_map('absint', $gallery_ids))));
}

function cad_theme_masonry_gallery_has_items()
{
    if (!is_singular(array('cad_project', 'cad_business_area'))) {
        return false;
    }

    $post_id = get_queried_object_id();
    if (!$post_id) {
        return false;
    }

    if ('cad_project' === get_post_type($post_id)) {
        return !empty(cad_theme_get_masonry_gallery_items(cad_theme_masonry_gallery_project_ids($post_id), 'large'));
    }

    $gallery_ids = get_post_meta($post_id, '_cad_business_gallery_ids', true);
    return !empty(cad_theme_get_masonry_gallery_items(is_array($gallery_ids) ? $gallery_ids : array(), 'large'));
}

function cad_theme_masonry_gallery_assets()
{
    if (!cad_theme_masonry_gallery_has_items()) {
        return;
    }

    $css_path = get_theme_file_path('/assets/css/cad-masonry-gallery.css');
    $js_path = get_theme_file_path('/assets/js/cad-masonry-gallery.js');
    $version = wp_get_theme()->get('Version');
    $css_version = file_exists($css_path) ? (string) filemtime($css_path) : $version;
    $js_version = file_exists($js_path) ? (string) filemtime($js_path) : $version;

    wp_enqueue_style(
        'cad-theme-masonry-gallery',
        get_template_directory_uri() . '/assets/css/cad-masonry-gallery.css',
        array(),
        $css_version
    );
    wp_enqueue_script(
        'cad-theme-masonry-gallery',
        get_template_directory_uri() . '/assets/js/cad-masonry-gallery.js',
        array(),
        $js_version,
        true
    );
}
add_action('wp_enqueue_scripts', 'cad_theme_masonry_gallery_assets', 20);
