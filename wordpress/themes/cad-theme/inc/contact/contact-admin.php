<?php

if (!defined('ABSPATH')) {
    exit;
}

function cad_theme_contact_set_admin_feedback($feedback)
{
    $GLOBALS['cad_theme_contact_admin_feedback'] = is_array($feedback) ? $feedback : array();
}

function cad_theme_contact_get_admin_feedback()
{
    if (!isset($GLOBALS['cad_theme_contact_admin_feedback']) || !is_array($GLOBALS['cad_theme_contact_admin_feedback'])) {
        return array();
    }

    return $GLOBALS['cad_theme_contact_admin_feedback'];
}

function cad_theme_contact_admin_menu()
{
    add_menu_page(
        __('Contacto', 'cad-theme'),
        __('Contacto', 'cad-theme'),
        'edit_posts',
        'cad-contact-dashboard',
        'cad_theme_contact_render_dashboard_page',
        'dashicons-email-alt2',
        29
    );

    add_submenu_page(
        'cad-contact-dashboard',
        __('Resumen', 'cad-theme'),
        __('Resumen', 'cad-theme'),
        'edit_posts',
        'cad-contact-dashboard',
        'cad_theme_contact_render_dashboard_page'
    );

    add_submenu_page(
        'cad-contact-dashboard',
        __('Mensajes', 'cad-theme'),
        __('Mensajes', 'cad-theme'),
        'edit_posts',
        'edit.php?post_type=' . cad_theme_contact_post_type()
    );

    add_submenu_page(
        'cad-contact-dashboard',
        __('Ajustes', 'cad-theme'),
        __('Ajustes', 'cad-theme'),
        'manage_options',
        'cad-contact-settings',
        'cad_theme_contact_render_settings_page'
    );
}
add_action('admin_menu', 'cad_theme_contact_admin_menu');

function cad_theme_contact_admin_parent_file($parent_file)
{
    $screen = get_current_screen();

    if ($screen && cad_theme_contact_post_type() === $screen->post_type) {
        return 'cad-contact-dashboard';
    }

    return $parent_file;
}
add_filter('parent_file', 'cad_theme_contact_admin_parent_file');

function cad_theme_contact_admin_submenu_file($submenu_file)
{
    $screen = get_current_screen();

    if (!$screen) {
        return $submenu_file;
    }

    if (cad_theme_contact_post_type() === $screen->post_type) {
        return 'edit.php?post_type=' . cad_theme_contact_post_type();
    }

    $page = isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : '';

    if ('cad-contact-settings' === $page) {
        return 'cad-contact-settings';
    }

    if ('cad-contact-dashboard' === $page) {
        return 'cad-contact-dashboard';
    }

    return $submenu_file;
}
add_filter('submenu_file', 'cad_theme_contact_admin_submenu_file');

function cad_theme_contact_admin_assets()
{
    $screen = get_current_screen();
    $page = isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : '';

    if (!$screen) {
        return;
    }

    if (cad_theme_contact_post_type() !== $screen->post_type && !in_array($page, array('cad-contact-dashboard', 'cad-contact-settings'), true)) {
        return;
    }

    wp_enqueue_style(
        'cad-theme-contact-admin',
        get_template_directory_uri() . '/assets/css/admin-contact.css',
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('admin_enqueue_scripts', 'cad_theme_contact_admin_assets');

function cad_theme_contact_add_meta_boxes()
{
    add_meta_box(
        'cad-contact-message-detail',
        __('Detalle del mensaje', 'cad-theme'),
        'cad_theme_contact_render_message_detail_box',
        cad_theme_contact_post_type(),
        'normal',
        'high'
    );

    add_meta_box(
        'cad-contact-message-reply',
        __('Responder', 'cad-theme'),
        'cad_theme_contact_render_message_reply_box',
        cad_theme_contact_post_type(),
        'normal',
        'default'
    );

    add_meta_box(
        'cad-contact-message-status',
        __('Gestion', 'cad-theme'),
        'cad_theme_contact_render_message_status_box',
        cad_theme_contact_post_type(),
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'cad_theme_contact_add_meta_boxes');

function cad_theme_contact_render_message_detail_box($post)
{
    $message = cad_theme_contact_get_message($post->ID);
    $received_label = $message['received_at'] ? cad_theme_contact_format_datetime($message['received_at']) : get_the_date('', $post);
    wp_nonce_field('cad_contact_message_admin', 'cad_contact_message_admin_nonce');
    ?>
    <div class="cad-contact-admin__detail-grid">
        <div class="cad-contact-admin__detail-item">
            <span class="cad-contact-admin__detail-label"><?php esc_html_e('Referencia', 'cad-theme'); ?></span>
            <strong><?php echo esc_html($message['reference']); ?></strong>
        </div>
        <div class="cad-contact-admin__detail-item">
            <span class="cad-contact-admin__detail-label"><?php esc_html_e('Fecha de recepcion', 'cad-theme'); ?></span>
            <strong><?php echo esc_html($received_label); ?></strong>
        </div>
        <div class="cad-contact-admin__detail-item">
            <span class="cad-contact-admin__detail-label"><?php esc_html_e('Nombre', 'cad-theme'); ?></span>
            <strong><?php echo esc_html($message['full_name']); ?></strong>
        </div>
        <div class="cad-contact-admin__detail-item">
            <span class="cad-contact-admin__detail-label"><?php esc_html_e('Correo', 'cad-theme'); ?></span>
            <strong><a href="mailto:<?php echo esc_attr(antispambot($message['email'])); ?>"><?php echo esc_html(antispambot($message['email'])); ?></a></strong>
        </div>
        <div class="cad-contact-admin__detail-item">
            <span class="cad-contact-admin__detail-label"><?php esc_html_e('Telefono', 'cad-theme'); ?></span>
            <strong><?php echo esc_html($message['phone']); ?></strong>
        </div>
        <div class="cad-contact-admin__detail-item">
            <span class="cad-contact-admin__detail-label"><?php esc_html_e('Asunto', 'cad-theme'); ?></span>
            <strong><?php echo esc_html($message['subject']); ?></strong>
        </div>
        <div class="cad-contact-admin__detail-item">
            <span class="cad-contact-admin__detail-label"><?php esc_html_e('Canal', 'cad-theme'); ?></span>
            <strong><?php echo esc_html('web_form' === $message['channel'] ? __('Formulario web', 'cad-theme') : $message['channel']); ?></strong>
        </div>
        <div class="cad-contact-admin__detail-item">
            <span class="cad-contact-admin__detail-label"><?php esc_html_e('Origen', 'cad-theme'); ?></span>
            <strong>
                <?php if (!empty($message['origin_url'])) : ?>
                    <a href="<?php echo esc_url($message['origin_url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($message['origin_label'] ? $message['origin_label'] : $message['origin_url']); ?></a>
                <?php else : ?>
                    <?php echo esc_html__('Sin registro', 'cad-theme'); ?>
                <?php endif; ?>
            </strong>
        </div>
        <div class="cad-contact-admin__detail-item">
            <span class="cad-contact-admin__detail-label"><?php esc_html_e('Mail admin', 'cad-theme'); ?></span>
            <strong><?php echo esc_html($message['admin_mail_sent'] ? __('Enviado', 'cad-theme') : __('Pendiente / fallo', 'cad-theme')); ?></strong>
        </div>
        <div class="cad-contact-admin__detail-item">
            <span class="cad-contact-admin__detail-label"><?php esc_html_e('Mail usuario', 'cad-theme'); ?></span>
            <strong><?php echo esc_html($message['user_mail_sent'] ? __('Enviado', 'cad-theme') : __('Pendiente / fallo', 'cad-theme')); ?></strong>
        </div>
    </div>

    <div class="cad-contact-admin__message">
        <h3><?php esc_html_e('Mensaje recibido', 'cad-theme'); ?></h3>
        <div class="cad-contact-admin__message-body">
            <?php echo wpautop(esc_html($message['message'])); ?>
        </div>
    </div>
    <?php
}

function cad_theme_contact_render_message_status_box($post)
{
    $message = cad_theme_contact_get_message($post->ID);
    ?>
    <p>
        <label for="cad-contact-status"><strong><?php esc_html_e('Estado', 'cad-theme'); ?></strong></label><br>
        <select id="cad-contact-status" name="cad_contact_status" class="widefat">
            <?php foreach (cad_theme_contact_statuses() as $status => $label) : ?>
                <option value="<?php echo esc_attr($status); ?>" <?php selected($message['status'], $status); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p class="description"><?php esc_html_e('Puedes cambiar el estado manualmente y usar el boton Actualizar para guardar.', 'cad-theme'); ?></p>

    <hr>

    <p><strong><?php esc_html_e('Respondido', 'cad-theme'); ?>:</strong> <?php echo esc_html($message['is_replied'] ? __('Si', 'cad-theme') : __('No', 'cad-theme')); ?></p>
    <p><strong><?php esc_html_e('Fecha de respuesta', 'cad-theme'); ?>:</strong> <?php echo esc_html(cad_theme_contact_format_datetime($message['replied_at'])); ?></p>
    <p><strong><?php esc_html_e('Administrador', 'cad-theme'); ?>:</strong>
        <?php
        if ($message['replied_by']) {
            $user = get_userdata($message['replied_by']);
            echo esc_html($user ? $user->display_name : __('Usuario eliminado', 'cad-theme'));
        } else {
            esc_html_e('Sin registro', 'cad-theme');
        }
        ?>
    </p>
    <?php
}

function cad_theme_contact_render_message_reply_box($post)
{
    $message = cad_theme_contact_get_message($post->ID);
    $history = $message['response_history'];
    $default_subject = 'Re: ' . $message['subject'];
    ?>
    <p>
        <label for="cad-contact-reply-subject"><strong><?php esc_html_e('Asunto de la respuesta', 'cad-theme'); ?></strong></label><br>
        <input id="cad-contact-reply-subject" type="text" name="cad_contact_reply_subject" class="widefat" value="<?php echo esc_attr($default_subject); ?>">
    </p>
    <p>
        <label for="cad-contact-reply-body"><strong><?php esc_html_e('Respuesta', 'cad-theme'); ?></strong></label><br>
        <textarea id="cad-contact-reply-body" name="cad_contact_reply_body" class="widefat" rows="8" placeholder="<?php esc_attr_e('Escribe aqui la respuesta que se enviara por correo al remitente.', 'cad-theme'); ?>"></textarea>
    </p>
    <p class="description"><?php esc_html_e('Al enviar la respuesta se registran la fecha, el usuario administrador y el historial basico del intercambio.', 'cad-theme'); ?></p>
    <p>
        <button type="submit" name="cad_contact_send_reply" value="1" class="button button-primary"><?php esc_html_e('Enviar respuesta', 'cad-theme'); ?></button>
    </p>

    <?php if (!empty($history)) : ?>
        <div class="cad-contact-admin__history">
            <h3><?php esc_html_e('Historial de respuestas', 'cad-theme'); ?></h3>
            <?php foreach ($history as $entry) : ?>
                <?php $user = !empty($entry['user_id']) ? get_userdata((int) $entry['user_id']) : false; ?>
                <article class="cad-contact-admin__history-item">
                    <div class="cad-contact-admin__history-meta">
                        <strong><?php echo esc_html(isset($entry['subject']) ? (string) $entry['subject'] : ''); ?></strong>
                        <span><?php echo esc_html(cad_theme_contact_format_datetime(isset($entry['sent_at']) ? (int) $entry['sent_at'] : 0)); ?></span>
                        <span><?php echo esc_html($user ? $user->display_name : __('Sistema', 'cad-theme')); ?></span>
                    </div>
                    <div class="cad-contact-admin__history-body"><?php echo wpautop(esc_html(isset($entry['message']) ? (string) $entry['message'] : '')); ?></div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php
}

function cad_theme_contact_save_message($post_id)
{
    if (!isset($_POST['cad_contact_message_admin_nonce']) || !wp_verify_nonce(wp_unslash($_POST['cad_contact_message_admin_nonce']), 'cad_contact_message_admin')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $feedback = array();

    if (isset($_POST['cad_contact_status'])) {
        cad_theme_contact_update_status($post_id, wp_unslash($_POST['cad_contact_status']));
        $feedback['status'] = 'updated';
    }

    if (isset($_POST['cad_contact_send_reply'])) {
        $reply_subject = isset($_POST['cad_contact_reply_subject']) ? sanitize_text_field(wp_unslash($_POST['cad_contact_reply_subject'])) : '';
        $reply_body = isset($_POST['cad_contact_reply_body']) ? sanitize_textarea_field(wp_unslash($_POST['cad_contact_reply_body'])) : '';

        if ('' === trim($reply_body)) {
            $feedback['reply'] = 'empty';
        } else {
            if ('' === $reply_subject) {
                $message = cad_theme_contact_get_message($post_id);
                $reply_subject = 'Re: ' . (isset($message['subject']) ? (string) $message['subject'] : '');
            }

            $result = cad_theme_contact_send_reply_email($post_id, $reply_subject, $reply_body);

            if (is_wp_error($result)) {
                $feedback['reply'] = 'failed';
            } else {
                cad_theme_contact_record_reply($post_id, get_current_user_id(), $reply_subject, $reply_body);
                cad_theme_contact_update_status($post_id, 'replied');
                $feedback['reply'] = 'sent';
            }
        }
    }

    cad_theme_contact_set_admin_feedback($feedback);
}
add_action('save_post_cad_contact_message', 'cad_theme_contact_save_message');

function cad_theme_contact_redirect_post_location($location, $post_id)
{
    if (cad_theme_contact_post_type() !== get_post_type($post_id)) {
        return $location;
    }

    foreach (cad_theme_contact_get_admin_feedback() as $key => $value) {
        $location = add_query_arg('cad_contact_' . $key, $value, $location);
    }

    return $location;
}
add_filter('redirect_post_location', 'cad_theme_contact_redirect_post_location', 10, 2);

function cad_theme_contact_admin_notices()
{
    $screen = get_current_screen();

    if (!$screen) {
        return;
    }

    if (cad_theme_contact_post_type() === $screen->post_type) {
        $reply_state = isset($_GET['cad_contact_reply']) ? sanitize_key(wp_unslash($_GET['cad_contact_reply'])) : '';

        if ('sent' === $reply_state) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('La respuesta se envio correctamente y el mensaje quedo marcado como respondido.', 'cad-theme') . '</p></div>';
        } elseif ('empty' === $reply_state) {
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__('Debes escribir una respuesta antes de enviarla.', 'cad-theme') . '</p></div>';
        } elseif ('failed' === $reply_state) {
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__('No fue posible enviar la respuesta por correo.', 'cad-theme') . '</p></div>';
        }

        $bulk_status = isset($_GET['cad_contact_bulk_status']) ? sanitize_key(wp_unslash($_GET['cad_contact_bulk_status'])) : '';
        $bulk_count = isset($_GET['cad_contact_bulk_count']) ? absint(wp_unslash($_GET['cad_contact_bulk_count'])) : 0;

        if ($bulk_status && $bulk_count > 0) {
            printf(
                '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
                esc_html(
                    sprintf(
                        _n('%d mensaje actualizado a %s.', '%d mensajes actualizados a %s.', $bulk_count, 'cad-theme'),
                        $bulk_count,
                        cad_theme_contact_get_status_label($bulk_status)
                    )
                )
            );
        }
    }
}
add_action('admin_notices', 'cad_theme_contact_admin_notices');

function cad_theme_contact_manage_columns($columns)
{
    return array(
        'cb'       => isset($columns['cb']) ? $columns['cb'] : '',
        'title'    => __('Mensaje', 'cad-theme'),
        'status'   => __('Estado', 'cad-theme'),
        'contact'  => __('Contacto', 'cad-theme'),
        'replied'  => __('Respondido', 'cad-theme'),
        'channel'  => __('Canal', 'cad-theme'),
        'date'     => __('Fecha', 'cad-theme'),
    );
}
add_filter('manage_edit-cad_contact_message_columns', 'cad_theme_contact_manage_columns');

function cad_theme_contact_render_column($column, $post_id)
{
    $message = cad_theme_contact_get_message($post_id);

    switch ($column) {
        case 'title':
            echo '<strong><a href="' . esc_url(get_edit_post_link($post_id, '')) . '">' . esc_html($message['full_name']) . '</a></strong>';
            echo '<p>' . esc_html($message['subject']) . '</p>';
            break;

        case 'status':
            echo '<span class="cad-contact-admin__badge is-' . esc_attr($message['status']) . '">' . esc_html($message['status_label']) . '</span>';
            break;

        case 'contact':
            echo '<a href="mailto:' . esc_attr(antispambot($message['email'])) . '">' . esc_html(antispambot($message['email'])) . '</a>';
            if (!empty($message['phone'])) {
                echo '<br><span>' . esc_html($message['phone']) . '</span>';
            }
            break;

        case 'replied':
            echo esc_html($message['is_replied'] ? __('Si', 'cad-theme') : __('No', 'cad-theme'));
            if ($message['replied_at']) {
                echo '<br><span>' . esc_html(cad_theme_contact_format_datetime($message['replied_at'])) . '</span>';
            }
            break;

        case 'channel':
            echo esc_html('web_form' === $message['channel'] ? __('Formulario web', 'cad-theme') : $message['channel']);
            break;
    }
}
add_action('manage_cad_contact_message_posts_custom_column', 'cad_theme_contact_render_column', 10, 2);

function cad_theme_contact_restrict_manage_posts($post_type)
{
    if (cad_theme_contact_post_type() !== $post_type) {
        return;
    }

    $selected_status = isset($_GET['cad_contact_status']) ? sanitize_key(wp_unslash($_GET['cad_contact_status'])) : '';
    $selected_replied = isset($_GET['cad_contact_replied']) ? sanitize_key(wp_unslash($_GET['cad_contact_replied'])) : '';
    ?>
    <select name="cad_contact_status">
        <option value=""><?php esc_html_e('Todos los estados', 'cad-theme'); ?></option>
        <?php foreach (cad_theme_contact_statuses() as $status => $label) : ?>
            <option value="<?php echo esc_attr($status); ?>" <?php selected($selected_status, $status); ?>><?php echo esc_html($label); ?></option>
        <?php endforeach; ?>
    </select>

    <select name="cad_contact_replied">
        <option value=""><?php esc_html_e('Todas las respuestas', 'cad-theme'); ?></option>
        <option value="yes" <?php selected($selected_replied, 'yes'); ?>><?php esc_html_e('Respondidos', 'cad-theme'); ?></option>
        <option value="no" <?php selected($selected_replied, 'no'); ?>><?php esc_html_e('Sin responder', 'cad-theme'); ?></option>
    </select>
    <?php
}
add_action('restrict_manage_posts', 'cad_theme_contact_restrict_manage_posts');

function cad_theme_contact_filter_admin_query($query)
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    global $pagenow;

    if ('edit.php' !== $pagenow || cad_theme_contact_post_type() !== $query->get('post_type')) {
        return;
    }

    $fields = cad_theme_contact_message_meta_keys();
    $meta_query = (array) $query->get('meta_query');
    $status = isset($_GET['cad_contact_status']) ? cad_theme_contact_normalize_status(wp_unslash($_GET['cad_contact_status'])) : '';
    $replied = isset($_GET['cad_contact_replied']) ? sanitize_key(wp_unslash($_GET['cad_contact_replied'])) : '';

    if (!empty($_GET['cad_contact_status'])) {
        $meta_query[] = array(
            'key'   => $fields['status'],
            'value' => $status,
        );
    }

    if ('yes' === $replied) {
        $meta_query[] = array(
            'key'   => $fields['is_replied'],
            'value' => '1',
        );
    } elseif ('no' === $replied) {
        $meta_query[] = array(
            'relation' => 'OR',
            array(
                'key'     => $fields['is_replied'],
                'compare' => 'NOT EXISTS',
            ),
            array(
                'key'   => $fields['is_replied'],
                'value' => '0',
            ),
        );
    }

    if (!empty($meta_query)) {
        $query->set('meta_query', $meta_query);
    }
}
add_action('pre_get_posts', 'cad_theme_contact_filter_admin_query');

function cad_theme_contact_bulk_actions($actions)
{
    $actions['cad_mark_new'] = __('Marcar como Nuevo', 'cad-theme');
    $actions['cad_mark_review'] = __('Marcar En revision', 'cad-theme');
    $actions['cad_mark_replied'] = __('Marcar Respondido', 'cad-theme');
    $actions['cad_mark_closed'] = __('Marcar Cerrado', 'cad-theme');

    return $actions;
}
add_filter('bulk_actions-edit-cad_contact_message', 'cad_theme_contact_bulk_actions');

function cad_theme_contact_handle_bulk_actions($redirect_to, $doaction, $post_ids)
{
    $map = array(
        'cad_mark_new'      => 'new',
        'cad_mark_review'   => 'review',
        'cad_mark_replied'  => 'replied',
        'cad_mark_closed'   => 'closed',
    );

    if (!isset($map[$doaction])) {
        return $redirect_to;
    }

    $status = $map[$doaction];
    $count = 0;

    foreach ((array) $post_ids as $post_id) {
        cad_theme_contact_update_status($post_id, $status);
        $count++;
    }

    return add_query_arg(
        array(
            'cad_contact_bulk_status' => $status,
            'cad_contact_bulk_count'  => $count,
        ),
        $redirect_to
    );
}
add_filter('handle_bulk_actions-edit-cad_contact_message', 'cad_theme_contact_handle_bulk_actions', 10, 3);

function cad_theme_contact_get_metrics_where_clause($alias = 'p', $from = '', $to = '')
{
    $where = array(
        "{$alias}.post_type = %s",
        "{$alias}.post_status = %s",
    );
    $params = array(
        cad_theme_contact_post_type(),
        'private',
    );

    if ('' !== $from) {
        $where[] = "{$alias}.post_date >= %s";
        $params[] = $from . ' 00:00:00';
    }

    if ('' !== $to) {
        $where[] = "{$alias}.post_date <= %s";
        $params[] = $to . ' 23:59:59';
    }

    return array(
        'sql'    => 'WHERE ' . implode(' AND ', $where),
        'params' => $params,
    );
}

function cad_theme_contact_get_metrics($from = '', $to = '')
{
    global $wpdb;

    $fields = cad_theme_contact_message_meta_keys();
    $where = cad_theme_contact_get_metrics_where_clause('p', $from, $to);

    $status_query = $wpdb->prepare(
        "SELECT COALESCE(NULLIF(pm.meta_value, ''), %s) AS status, COUNT(DISTINCT p.ID) AS total
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm
            ON p.ID = pm.post_id
            AND pm.meta_key = %s
        {$where['sql']}
        GROUP BY status",
        array_merge(array('new', $fields['status']), $where['params'])
    );

    $status_rows = $wpdb->get_results($status_query, ARRAY_A);
    $status_counts = array_fill_keys(array_keys(cad_theme_contact_statuses()), 0);

    if (is_array($status_rows)) {
        foreach ($status_rows as $row) {
            $status = isset($row['status']) ? cad_theme_contact_normalize_status($row['status']) : 'new';
            $status_counts[$status] = isset($row['total']) ? (int) $row['total'] : 0;
        }
    }

    $total = array_sum($status_counts);
    $replied_total_query = $wpdb->prepare(
        "SELECT COUNT(DISTINCT p.ID)
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm
            ON p.ID = pm.post_id
            AND pm.meta_key = %s
        {$where['sql']}
        AND pm.meta_value = '1'",
        array_merge(array($fields['is_replied']), $where['params'])
    );

    $replied_total = (int) $wpdb->get_var($replied_total_query);

    $reply_query = $wpdb->prepare(
        "SELECT AVG(
            CAST(pm.meta_value AS UNSIGNED) - UNIX_TIMESTAMP(
                CASE
                    WHEN p.post_date_gmt = '0000-00-00 00:00:00' THEN p.post_date
                    ELSE p.post_date_gmt
                END
            )
        ) AS avg_seconds
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm
            ON p.ID = pm.post_id
            AND pm.meta_key = %s
        {$where['sql']}
        AND pm.meta_value <> ''
        AND pm.meta_value <> '0'",
        array_merge(array($fields['replied_at']), $where['params'])
    );

    $avg_seconds = (int) $wpdb->get_var($reply_query);

    $daily_limit = ('' !== $from || '' !== $to) ? 366 : 14;
    $daily_query = $wpdb->prepare(
        "SELECT DATE(p.post_date) AS day, COUNT(DISTINCT p.ID) AS total
        FROM {$wpdb->posts} p
        {$where['sql']}
        GROUP BY DATE(p.post_date)
        ORDER BY day DESC
        LIMIT %d",
        array_merge($where['params'], array($daily_limit))
    );

    $daily_rows = $wpdb->get_results($daily_query, ARRAY_A);
    $daily_rows = is_array($daily_rows) ? array_reverse($daily_rows) : array();

    return array(
        'total'                 => $total,
        'new'                   => $status_counts['new'],
        'review'                => $status_counts['review'],
        'replied'               => $status_counts['replied'],
        'closed'                => $status_counts['closed'],
        'pending'               => $status_counts['new'] + $status_counts['review'],
        'response_rate'         => $total > 0 ? round(($replied_total / $total) * 100, 1) : 0,
        'average_response_time' => $avg_seconds,
        'daily'                 => $daily_rows,
    );
}

function cad_theme_contact_get_date_filter()
{
    $from = isset($_GET['from']) ? sanitize_text_field(wp_unslash($_GET['from'])) : '';
    $to = isset($_GET['to']) ? sanitize_text_field(wp_unslash($_GET['to'])) : '';

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) {
        $from = '';
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
        $to = '';
    }

    return array(
        'from' => $from,
        'to'   => $to,
    );
}

function cad_theme_contact_render_dashboard_page()
{
    if (!current_user_can('edit_posts')) {
        wp_die(esc_html__('No tienes permisos para ver esta seccion.', 'cad-theme'));
    }

    $filter = cad_theme_contact_get_date_filter();
    $metrics = cad_theme_contact_get_metrics($filter['from'], $filter['to']);
    $recent_messages = get_posts(
        array(
            'post_type'      => cad_theme_contact_post_type(),
            'post_status'    => 'private',
            'posts_per_page' => 6,
            'orderby'        => 'date',
            'order'          => 'DESC',
        )
    );
    $messages_url = admin_url('edit.php?post_type=' . cad_theme_contact_post_type());
    $settings_url = admin_url('admin.php?page=cad-contact-settings');
    $max_daily = 0;

    foreach ($metrics['daily'] as $row) {
        $row_total = isset($row['total']) ? (int) $row['total'] : 0;
        if ($row_total > $max_daily) {
            $max_daily = $row_total;
        }
    }
    ?>
    <div class="wrap cad-contact-admin">
        <h1><?php esc_html_e('Contacto', 'cad-theme'); ?></h1>
        <p><?php esc_html_e('Panel operativo de la bandeja de contacto: metricas, mensajes recientes y accesos directos a la gestion completa.', 'cad-theme'); ?></p>

        <div class="cad-contact-admin__actions">
            <a class="button button-primary" href="<?php echo esc_url($messages_url); ?>"><?php esc_html_e('Ver mensajes', 'cad-theme'); ?></a>
            <a class="button" href="<?php echo esc_url($settings_url); ?>"><?php esc_html_e('Ajustes', 'cad-theme'); ?></a>
        </div>

        <form class="cad-contact-admin__filters" method="get">
            <input type="hidden" name="page" value="cad-contact-dashboard">
            <label>
                <span><?php esc_html_e('Desde', 'cad-theme'); ?></span>
                <input type="date" name="from" value="<?php echo esc_attr($filter['from']); ?>">
            </label>
            <label>
                <span><?php esc_html_e('Hasta', 'cad-theme'); ?></span>
                <input type="date" name="to" value="<?php echo esc_attr($filter['to']); ?>">
            </label>
            <button type="submit" class="button"><?php esc_html_e('Filtrar', 'cad-theme'); ?></button>
            <a href="<?php echo esc_url(admin_url('admin.php?page=cad-contact-dashboard')); ?>" class="button button-link"><?php esc_html_e('Limpiar', 'cad-theme'); ?></a>
        </form>

        <div class="cad-contact-admin__cards">
            <div class="cad-contact-admin__card">
                <span><?php esc_html_e('Total recibidos', 'cad-theme'); ?></span>
                <strong><?php echo esc_html((string) $metrics['total']); ?></strong>
            </div>
            <div class="cad-contact-admin__card">
                <span><?php esc_html_e('Nuevos', 'cad-theme'); ?></span>
                <strong><?php echo esc_html((string) $metrics['new']); ?></strong>
            </div>
            <div class="cad-contact-admin__card">
                <span><?php esc_html_e('En revision', 'cad-theme'); ?></span>
                <strong><?php echo esc_html((string) $metrics['review']); ?></strong>
            </div>
            <div class="cad-contact-admin__card">
                <span><?php esc_html_e('Respondidos', 'cad-theme'); ?></span>
                <strong><?php echo esc_html((string) $metrics['replied']); ?></strong>
            </div>
            <div class="cad-contact-admin__card">
                <span><?php esc_html_e('Cerrados', 'cad-theme'); ?></span>
                <strong><?php echo esc_html((string) $metrics['closed']); ?></strong>
            </div>
            <div class="cad-contact-admin__card">
                <span><?php esc_html_e('Pendientes', 'cad-theme'); ?></span>
                <strong><?php echo esc_html((string) $metrics['pending']); ?></strong>
            </div>
            <div class="cad-contact-admin__card">
                <span><?php esc_html_e('Tasa de respuesta', 'cad-theme'); ?></span>
                <strong><?php echo esc_html((string) $metrics['response_rate']); ?>%</strong>
            </div>
            <div class="cad-contact-admin__card">
                <span><?php esc_html_e('Promedio de respuesta', 'cad-theme'); ?></span>
                <strong><?php echo esc_html(cad_theme_contact_format_duration($metrics['average_response_time'])); ?></strong>
            </div>
        </div>

        <div class="cad-contact-admin__panels">
            <section class="cad-contact-admin__panel">
                <h2><?php esc_html_e('Mensajes por fecha', 'cad-theme'); ?></h2>
                <?php if (!empty($metrics['daily'])) : ?>
                    <div class="cad-contact-admin__daily-list">
                        <?php foreach ($metrics['daily'] as $row) : ?>
                            <?php
                            $day_total = isset($row['total']) ? (int) $row['total'] : 0;
                            $width = $max_daily > 0 ? ($day_total / $max_daily) * 100 : 0;
                            ?>
                            <div class="cad-contact-admin__daily-item">
                                <div class="cad-contact-admin__daily-meta">
                                    <strong><?php echo esc_html(wp_date(get_option('date_format'), strtotime((string) $row['day']), wp_timezone())); ?></strong>
                                    <span><?php echo esc_html((string) $day_total); ?></span>
                                </div>
                                <div class="cad-contact-admin__daily-bar">
                                    <span style="width:<?php echo esc_attr((string) $width); ?>%"></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p><?php esc_html_e('Aun no hay datos para el periodo seleccionado.', 'cad-theme'); ?></p>
                <?php endif; ?>
            </section>

            <section class="cad-contact-admin__panel">
                <h2><?php esc_html_e('Mensajes recientes', 'cad-theme'); ?></h2>
                <?php if (!empty($recent_messages)) : ?>
                    <div class="cad-contact-admin__recent-list">
                        <?php foreach ($recent_messages as $recent_post) : ?>
                            <?php $message = cad_theme_contact_get_message($recent_post->ID); ?>
                            <article class="cad-contact-admin__recent-item">
                                <div>
                                    <a href="<?php echo esc_url(get_edit_post_link($recent_post->ID, '')); ?>"><strong><?php echo esc_html($message['full_name']); ?></strong></a>
                                    <p><?php echo esc_html($message['subject']); ?></p>
                                    <span><?php echo esc_html(get_the_date('', $recent_post)); ?></span>
                                </div>
                                <span class="cad-contact-admin__badge is-<?php echo esc_attr($message['status']); ?>"><?php echo esc_html($message['status_label']); ?></span>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p><?php esc_html_e('Todavia no se reciben mensajes desde el formulario.', 'cad-theme'); ?></p>
                <?php endif; ?>
            </section>
        </div>
    </div>
    <?php
}

function cad_theme_contact_render_settings_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('No tienes permisos para ver esta seccion.', 'cad-theme'));
    }
    ?>
    <div class="wrap cad-contact-admin">
        <h1><?php esc_html_e('Ajustes de contacto', 'cad-theme'); ?></h1>
        <p><?php esc_html_e('Configuracion centralizada para correos, WhatsApp, textos del frontend y correo automatico al usuario.', 'cad-theme'); ?></p>

        <form method="post" action="options.php" class="cad-contact-admin__settings-form">
            <?php
            settings_fields('cad_contact_settings_group');
            do_settings_sections('cad-contact-settings');
            submit_button(__('Guardar ajustes', 'cad-theme'));
            ?>
        </form>
    </div>
    <?php
}
