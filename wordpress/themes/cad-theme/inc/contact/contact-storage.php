<?php

if (!defined('ABSPATH')) {
    exit;
}

function cad_theme_contact_post_type()
{
    return 'cad_contact_message';
}

function cad_theme_contact_message_meta_keys()
{
    return array(
        'full_name'          => '_cad_contact_full_name',
        'email'              => '_cad_contact_email',
        'phone'              => '_cad_contact_phone',
        'subject'            => '_cad_contact_subject',
        'message'            => '_cad_contact_message',
        'status'             => '_cad_contact_status',
        'channel'            => '_cad_contact_channel',
        'origin_url'         => '_cad_contact_origin_url',
        'origin_label'       => '_cad_contact_origin_label',
        'origin_page_id'     => '_cad_contact_origin_page_id',
        'is_replied'         => '_cad_contact_is_replied',
        'replied_at'         => '_cad_contact_replied_at',
        'replied_by'         => '_cad_contact_replied_by',
        'response_history'   => '_cad_contact_response_history',
        'response_count'     => '_cad_contact_response_count',
        'last_response'      => '_cad_contact_last_response',
        'last_response_subj' => '_cad_contact_last_response_subject',
        'admin_mail_sent'    => '_cad_contact_admin_mail_sent',
        'user_mail_sent'     => '_cad_contact_user_mail_sent',
        'received_at'        => '_cad_contact_received_at',
        'ip_hash'            => '_cad_contact_ip_hash',
    );
}

function cad_theme_contact_statuses()
{
    return array(
        'new'      => __('Nuevo', 'cad-theme'),
        'review'   => __('En revision', 'cad-theme'),
        'replied'  => __('Respondido', 'cad-theme'),
        'closed'   => __('Cerrado', 'cad-theme'),
    );
}

function cad_theme_contact_normalize_status($status)
{
    $statuses = cad_theme_contact_statuses();
    $status = sanitize_key((string) $status);

    if (isset($statuses[$status])) {
        return $status;
    }

    return 'new';
}

function cad_theme_contact_get_status_label($status)
{
    $statuses = cad_theme_contact_statuses();
    $status = cad_theme_contact_normalize_status($status);

    return isset($statuses[$status]) ? $statuses[$status] : $statuses['new'];
}

function cad_theme_contact_register_post_type()
{
    $labels = array(
        'name'                  => __('Mensajes de contacto', 'cad-theme'),
        'singular_name'         => __('Mensaje de contacto', 'cad-theme'),
        'menu_name'             => __('Mensajes', 'cad-theme'),
        'all_items'             => __('Todos los mensajes', 'cad-theme'),
        'edit_item'             => __('Gestionar mensaje', 'cad-theme'),
        'view_item'             => __('Ver mensaje', 'cad-theme'),
        'search_items'          => __('Buscar mensajes', 'cad-theme'),
        'not_found'             => __('No hay mensajes', 'cad-theme'),
        'not_found_in_trash'    => __('No hay mensajes en la papelera', 'cad-theme'),
        'filter_items_list'     => __('Filtrar mensajes', 'cad-theme'),
        'items_list_navigation' => __('Navegacion de mensajes', 'cad-theme'),
        'items_list'            => __('Lista de mensajes', 'cad-theme'),
    );

    register_post_type(
        cad_theme_contact_post_type(),
        array(
            'labels'              => $labels,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_admin_bar'   => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'has_archive'         => false,
            'rewrite'             => false,
            'show_in_rest'        => false,
            'supports'            => array(),
            'map_meta_cap'        => true,
            'capability_type'     => 'post',
            'capabilities'        => array(
                'create_posts' => 'do_not_allow',
            ),
        )
    );
}
add_action('init', 'cad_theme_contact_register_post_type');

function cad_theme_contact_get_reference($message_id)
{
    return 'CAD-' . absint($message_id);
}

function cad_theme_contact_build_message_title($data)
{
    $name = isset($data['full_name']) ? (string) $data['full_name'] : '';
    $subject = isset($data['subject']) ? (string) $data['subject'] : '';
    $email = isset($data['email']) ? (string) $data['email'] : '';

    $parts = array_filter(array($name, $subject, $email));

    if (empty($parts)) {
        return __('Mensaje de contacto', 'cad-theme');
    }

    return implode(' | ', $parts);
}

function cad_theme_contact_insert_message($data)
{
    $fields = cad_theme_contact_message_meta_keys();
    $received_at = current_time('timestamp', true);

    $post_id = wp_insert_post(
        wp_slash(
            array(
                'post_type'    => cad_theme_contact_post_type(),
                'post_status'  => 'private',
                'post_title'   => cad_theme_contact_build_message_title($data),
                'post_content' => isset($data['message']) ? (string) $data['message'] : '',
                'post_excerpt' => isset($data['phone']) ? (string) $data['phone'] : '',
            )
        ),
        true
    );

    if (is_wp_error($post_id)) {
        return $post_id;
    }

    $defaults = array(
        'full_name'      => '',
        'email'          => '',
        'phone'          => '',
        'subject'        => '',
        'message'        => '',
        'channel'        => 'web_form',
        'origin_url'     => '',
        'origin_label'   => '',
        'origin_page_id' => 0,
        'ip_hash'        => '',
    );
    $data = wp_parse_args($data, $defaults);

    update_post_meta($post_id, $fields['full_name'], (string) $data['full_name']);
    update_post_meta($post_id, $fields['email'], (string) $data['email']);
    update_post_meta($post_id, $fields['phone'], (string) $data['phone']);
    update_post_meta($post_id, $fields['subject'], (string) $data['subject']);
    update_post_meta($post_id, $fields['message'], (string) $data['message']);
    update_post_meta($post_id, $fields['status'], 'new');
    update_post_meta($post_id, $fields['channel'], (string) $data['channel']);
    update_post_meta($post_id, $fields['origin_url'], (string) $data['origin_url']);
    update_post_meta($post_id, $fields['origin_label'], (string) $data['origin_label']);
    update_post_meta($post_id, $fields['origin_page_id'], absint($data['origin_page_id']));
    update_post_meta($post_id, $fields['is_replied'], '0');
    update_post_meta($post_id, $fields['replied_at'], 0);
    update_post_meta($post_id, $fields['replied_by'], 0);
    update_post_meta($post_id, $fields['response_history'], array());
    update_post_meta($post_id, $fields['response_count'], 0);
    update_post_meta($post_id, $fields['last_response'], '');
    update_post_meta($post_id, $fields['last_response_subj'], '');
    update_post_meta($post_id, $fields['admin_mail_sent'], '0');
    update_post_meta($post_id, $fields['user_mail_sent'], '0');
    update_post_meta($post_id, $fields['received_at'], $received_at);
    update_post_meta($post_id, $fields['ip_hash'], (string) $data['ip_hash']);

    return $post_id;
}

function cad_theme_contact_update_delivery_status($post_id, $admin_mail_sent, $user_mail_sent)
{
    $fields = cad_theme_contact_message_meta_keys();

    update_post_meta($post_id, $fields['admin_mail_sent'], $admin_mail_sent ? '1' : '0');
    update_post_meta($post_id, $fields['user_mail_sent'], $user_mail_sent ? '1' : '0');
}

function cad_theme_contact_update_status($post_id, $status)
{
    $fields = cad_theme_contact_message_meta_keys();
    update_post_meta($post_id, $fields['status'], cad_theme_contact_normalize_status($status));
}

function cad_theme_contact_get_response_history($post_id)
{
    $fields = cad_theme_contact_message_meta_keys();
    $history = get_post_meta($post_id, $fields['response_history'], true);

    if (!is_array($history)) {
        return array();
    }

    $normalized = array();

    foreach ($history as $entry) {
        if (!is_array($entry)) {
            continue;
        }

        $normalized[] = array(
            'sent_at' => isset($entry['sent_at']) ? absint($entry['sent_at']) : 0,
            'user_id' => isset($entry['user_id']) ? absint($entry['user_id']) : 0,
            'subject' => isset($entry['subject']) ? sanitize_text_field((string) $entry['subject']) : '',
            'message' => isset($entry['message']) ? sanitize_textarea_field((string) $entry['message']) : '',
        );
    }

    return $normalized;
}

function cad_theme_contact_record_reply($post_id, $user_id, $subject, $message)
{
    $fields = cad_theme_contact_message_meta_keys();
    $history = cad_theme_contact_get_response_history($post_id);
    $subject = sanitize_text_field((string) $subject);
    $message = sanitize_textarea_field((string) $message);
    $sent_at = current_time('timestamp', true);

    array_unshift(
        $history,
        array(
            'sent_at' => $sent_at,
            'user_id' => absint($user_id),
            'subject' => $subject,
            'message' => $message,
        )
    );

    update_post_meta($post_id, $fields['response_history'], $history);
    update_post_meta($post_id, $fields['response_count'], count($history));
    update_post_meta($post_id, $fields['last_response'], $message);
    update_post_meta($post_id, $fields['last_response_subj'], $subject);
    update_post_meta($post_id, $fields['is_replied'], '1');
    update_post_meta($post_id, $fields['replied_at'], $sent_at);
    update_post_meta($post_id, $fields['replied_by'], absint($user_id));
}

function cad_theme_contact_get_message($post_id)
{
    $post = get_post($post_id);

    if (!$post || cad_theme_contact_post_type() !== $post->post_type) {
        return array();
    }

    $fields = cad_theme_contact_message_meta_keys();
    $status = get_post_meta($post_id, $fields['status'], true);

    return array(
        'ID'                 => $post_id,
        'full_name'          => (string) get_post_meta($post_id, $fields['full_name'], true),
        'email'              => (string) get_post_meta($post_id, $fields['email'], true),
        'phone'              => (string) get_post_meta($post_id, $fields['phone'], true),
        'subject'            => (string) get_post_meta($post_id, $fields['subject'], true),
        'message'            => (string) get_post_meta($post_id, $fields['message'], true),
        'status'             => cad_theme_contact_normalize_status($status),
        'status_label'       => cad_theme_contact_get_status_label($status),
        'channel'            => (string) get_post_meta($post_id, $fields['channel'], true),
        'origin_url'         => (string) get_post_meta($post_id, $fields['origin_url'], true),
        'origin_label'       => (string) get_post_meta($post_id, $fields['origin_label'], true),
        'origin_page_id'     => absint(get_post_meta($post_id, $fields['origin_page_id'], true)),
        'is_replied'         => '1' === (string) get_post_meta($post_id, $fields['is_replied'], true),
        'replied_at'         => absint(get_post_meta($post_id, $fields['replied_at'], true)),
        'replied_by'         => absint(get_post_meta($post_id, $fields['replied_by'], true)),
        'response_count'     => absint(get_post_meta($post_id, $fields['response_count'], true)),
        'last_response'      => (string) get_post_meta($post_id, $fields['last_response'], true),
        'last_response_subj' => (string) get_post_meta($post_id, $fields['last_response_subj'], true),
        'admin_mail_sent'    => '1' === (string) get_post_meta($post_id, $fields['admin_mail_sent'], true),
        'user_mail_sent'     => '1' === (string) get_post_meta($post_id, $fields['user_mail_sent'], true),
        'received_at'        => absint(get_post_meta($post_id, $fields['received_at'], true)),
        'response_history'   => cad_theme_contact_get_response_history($post_id),
        'reference'          => cad_theme_contact_get_reference($post_id),
        'received_post_date' => get_post_datetime($post, 'date'),
    );
}

function cad_theme_contact_format_datetime($timestamp)
{
    $timestamp = absint($timestamp);

    if (!$timestamp) {
        return __('Sin registro', 'cad-theme');
    }

    return wp_date(
        get_option('date_format') . ' ' . get_option('time_format'),
        $timestamp,
        wp_timezone()
    );
}

function cad_theme_contact_format_duration($seconds)
{
    $seconds = absint($seconds);

    if (!$seconds) {
        return __('Sin datos', 'cad-theme');
    }

    $days = (int) floor($seconds / DAY_IN_SECONDS);
    $seconds -= $days * DAY_IN_SECONDS;
    $hours = (int) floor($seconds / HOUR_IN_SECONDS);
    $seconds -= $hours * HOUR_IN_SECONDS;
    $minutes = (int) floor($seconds / MINUTE_IN_SECONDS);

    $parts = array();

    if ($days > 0) {
        $parts[] = sprintf(_n('%d dia', '%d dias', $days, 'cad-theme'), $days);
    }

    if ($hours > 0) {
        $parts[] = sprintf(_n('%d hora', '%d horas', $hours, 'cad-theme'), $hours);
    }

    if ($minutes > 0 && count($parts) < 2) {
        $parts[] = sprintf(_n('%d minuto', '%d minutos', $minutes, 'cad-theme'), $minutes);
    }

    if (empty($parts)) {
        $parts[] = __('Menos de 1 minuto', 'cad-theme');
    }

    return implode(' ', array_slice($parts, 0, 2));
}
