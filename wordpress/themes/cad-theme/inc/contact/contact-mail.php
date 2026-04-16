<?php

if (!defined('ABSPATH')) {
    exit;
}

function cad_theme_contact_mail_placeholders($message_id, $data = array())
{
    $message_id = absint($message_id);
    $reference = cad_theme_contact_get_reference($message_id);
    $site_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
    $data = wp_parse_args(
        $data,
        array(
            'full_name' => '',
            'email'     => '',
            'phone'     => '',
            'subject'   => '',
            'message'   => '',
        )
    );

    return array(
        '{name}'              => (string) $data['full_name'],
        '{email}'             => (string) $data['email'],
        '{phone}'             => (string) $data['phone'],
        '{subject}'           => (string) $data['subject'],
        '{message}'           => (string) $data['message'],
        '{site_name}'         => $site_name,
        '{message_reference}' => $reference,
    );
}

function cad_theme_contact_replace_placeholders($template, $placeholders)
{
    return strtr((string) $template, $placeholders);
}

function cad_theme_contact_plain_text_to_html($text)
{
    $text = trim((string) $text);

    if ('' === $text) {
        return '';
    }

    return nl2br(esc_html($text));
}

function cad_theme_contact_mail_wrapper($title, $body_html)
{
    $site_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);

    return '
        <div style="margin:0;padding:24px;background:#f4f0eb;font-family:Arial,Helvetica,sans-serif;color:#2f261f;">
            <div style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #eadccf;border-radius:16px;overflow:hidden;">
                <div style="padding:20px 24px;background:#171b21;color:#f8f3ee;">
                    <div style="font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#f4b483;">' . esc_html($site_name) . '</div>
                    <h1 style="margin:10px 0 0;font-size:26px;line-height:1.1;color:#f8f3ee;">' . esc_html($title) . '</h1>
                </div>
                <div style="padding:24px;font-size:15px;line-height:1.7;color:#2f261f;">
                    ' . $body_html . '
                </div>
            </div>
        </div>';
}

function cad_theme_contact_build_admin_notification_body($message_id, $data)
{
    $edit_link = get_edit_post_link($message_id, '');
    $message_html = cad_theme_contact_plain_text_to_html(isset($data['message']) ? $data['message'] : '');
    $origin_label = isset($data['origin_label']) ? (string) $data['origin_label'] : '';
    $origin_url = isset($data['origin_url']) ? (string) $data['origin_url'] : '';

    $rows = array(
        __('Referencia', 'cad-theme') => cad_theme_contact_get_reference($message_id),
        __('Nombre', 'cad-theme')     => isset($data['full_name']) ? (string) $data['full_name'] : '',
        __('Correo', 'cad-theme')     => isset($data['email']) ? (string) $data['email'] : '',
        __('Telefono', 'cad-theme')   => isset($data['phone']) ? (string) $data['phone'] : '',
        __('Asunto', 'cad-theme')     => isset($data['subject']) ? (string) $data['subject'] : '',
        __('Canal', 'cad-theme')      => __('Formulario web', 'cad-theme'),
    );

    if ('' !== $origin_label || '' !== $origin_url) {
        $rows[__('Origen', 'cad-theme')] = '' !== $origin_url ? '<a href="' . esc_url($origin_url) . '">' . esc_html($origin_label ? $origin_label : $origin_url) . '</a>' : esc_html($origin_label);
    }

    $table_rows = '';

    foreach ($rows as $label => $value) {
        $table_rows .= '<tr>';
        $table_rows .= '<th style="padding:8px 12px;border-bottom:1px solid #eadccf;text-align:left;background:#fff8f2;width:160px;">' . esc_html($label) . '</th>';
        $table_rows .= '<td style="padding:8px 12px;border-bottom:1px solid #eadccf;">' . wp_kses_post($value) . '</td>';
        $table_rows .= '</tr>';
    }

    $actions = '';

    if ($edit_link) {
        $actions = '<p style="margin-top:18px;"><a href="' . esc_url($edit_link) . '" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#171b21;color:#ffffff;text-decoration:none;font-weight:700;">' . esc_html__('Abrir en WordPress', 'cad-theme') . '</a></p>';
    }

    return '
        <p style="margin-top:0;">' . esc_html__('Se recibio un nuevo mensaje desde la pagina de contacto.', 'cad-theme') . '</p>
        <table style="width:100%;border-collapse:collapse;margin:18px 0 0;">' . $table_rows . '</table>
        <div style="margin-top:18px;padding:16px;border-radius:12px;background:#fff8f2;border:1px solid #eadccf;">
            <strong style="display:block;margin-bottom:8px;">' . esc_html__('Mensaje', 'cad-theme') . '</strong>
            <div>' . $message_html . '</div>
        </div>
        ' . $actions;
}

function cad_theme_contact_send_admin_notification($message_id, $data)
{
    $settings = cad_theme_contact_get_settings();
    $to = isset($settings['receiver_email']) ? cad_theme_contact_normalize_email($settings['receiver_email']) : '';

    if ('' === $to) {
        return false;
    }

    $site_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
    $subject = sprintf(
        '[%s] %s: %s',
        $site_name,
        __('Nuevo mensaje de contacto', 'cad-theme'),
        isset($data['subject']) ? (string) $data['subject'] : ''
    );

    $headers = array('Content-Type: text/html; charset=UTF-8');

    if (!empty($data['full_name']) && !empty($data['email']) && is_email($data['email'])) {
        $headers[] = 'Reply-To: ' . sanitize_text_field((string) $data['full_name']) . ' <' . sanitize_email((string) $data['email']) . '>';
    }

    if (!empty($settings['cc_email']) && is_email($settings['cc_email'])) {
        $headers[] = 'Cc: ' . sanitize_email($settings['cc_email']);
    }

    $body = cad_theme_contact_build_admin_notification_body($message_id, $data);

    return (bool) wp_mail(
        $to,
        $subject,
        cad_theme_contact_mail_wrapper(__('Nuevo mensaje de contacto', 'cad-theme'), $body),
        $headers
    );
}

function cad_theme_contact_send_user_confirmation($message_id, $data)
{
    $email = isset($data['email']) ? cad_theme_contact_normalize_email($data['email']) : '';

    if ('' === $email) {
        return false;
    }

    $settings = cad_theme_contact_get_settings();
    $placeholders = cad_theme_contact_mail_placeholders($message_id, $data);
    $subject_template = isset($settings['auto_reply_subject']) ? (string) $settings['auto_reply_subject'] : '';
    $body_template = isset($settings['auto_reply_body']) ? (string) $settings['auto_reply_body'] : '';
    $reply_to = isset($settings['receiver_email']) ? cad_theme_contact_normalize_email($settings['receiver_email']) : '';
    $subject = cad_theme_contact_replace_placeholders($subject_template, $placeholders);
    $body_text = cad_theme_contact_replace_placeholders($body_template, $placeholders);
    $headers = array('Content-Type: text/html; charset=UTF-8');

    if ('' !== $reply_to) {
        $headers[] = 'Reply-To: ' . $reply_to;
    }

    return (bool) wp_mail(
        $email,
        $subject,
        cad_theme_contact_mail_wrapper($subject, '<div>' . cad_theme_contact_plain_text_to_html($body_text) . '</div>'),
        $headers
    );
}

function cad_theme_contact_send_reply_email($message_id, $reply_subject, $reply_body)
{
    $message = cad_theme_contact_get_message($message_id);

    if (empty($message)) {
        return new WP_Error('message_not_found', __('No fue posible cargar el mensaje.', 'cad-theme'));
    }

    if (empty($message['email']) || !is_email($message['email'])) {
        return new WP_Error('invalid_recipient', __('El remitente no tiene un correo valido.', 'cad-theme'));
    }

    $reply_subject = sanitize_text_field((string) $reply_subject);
    $reply_body = sanitize_textarea_field((string) $reply_body);

    if ('' === $reply_subject) {
        $reply_subject = 'Re: ' . $message['subject'];
    }

    if ('' === trim($reply_body)) {
        return new WP_Error('empty_reply', __('Escribe un mensaje antes de enviar la respuesta.', 'cad-theme'));
    }

    $settings = cad_theme_contact_get_settings();
    $reply_to = isset($settings['receiver_email']) ? cad_theme_contact_normalize_email($settings['receiver_email']) : '';
    $headers = array('Content-Type: text/html; charset=UTF-8');

    if ('' !== $reply_to) {
        $headers[] = 'Reply-To: ' . $reply_to;
    }

    $body_html = '
        <p style="margin-top:0;">' . esc_html__('Esta es una respuesta a tu solicitud de contacto.', 'cad-theme') . '</p>
        <div style="padding:16px;border:1px solid #eadccf;border-radius:12px;background:#fff8f2;">' . cad_theme_contact_plain_text_to_html($reply_body) . '</div>
        <p style="margin-bottom:0;margin-top:18px;color:#7f6a58;">' . esc_html__('Referencia', 'cad-theme') . ': ' . esc_html($message['reference']) . '</p>';

    $sent = wp_mail(
        $message['email'],
        $reply_subject,
        cad_theme_contact_mail_wrapper($reply_subject, $body_html),
        $headers
    );

    if (!$sent) {
        return new WP_Error('mail_failed', __('WordPress no pudo enviar el correo de respuesta.', 'cad-theme'));
    }

    return true;
}
