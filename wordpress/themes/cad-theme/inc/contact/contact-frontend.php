<?php

if (!defined('ABSPATH')) {
    exit;
}

function cad_theme_contact_enqueue_assets()
{
    if (!is_page_template('template-contact.php')) {
        return;
    }

    wp_enqueue_style(
        'cad-theme-contact',
        get_template_directory_uri() . '/assets/css/contact.css',
        array('cad-theme-main'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'cad_theme_contact_enqueue_assets');

function cad_theme_contact_form_fields()
{
    $fields = array(
        'full_name' => array(
            'label'        => __('Nombre completo', 'cad-theme'),
            'type'         => 'text',
            'required'     => true,
            'maxlength'    => 120,
            'autocomplete' => 'name',
        ),
        'email' => array(
            'label'        => __('Correo electronico', 'cad-theme'),
            'type'         => 'email',
            'required'     => true,
            'maxlength'    => 140,
            'autocomplete' => 'email',
        ),
        'phone' => array(
            'label'        => __('Telefono', 'cad-theme'),
            'type'         => 'text',
            'required'     => true,
            'maxlength'    => 40,
            'autocomplete' => 'tel',
        ),
        'subject' => array(
            'label'        => __('Asunto', 'cad-theme'),
            'type'         => 'text',
            'required'     => true,
            'maxlength'    => 160,
            'autocomplete' => 'off',
        ),
        'message' => array(
            'label'        => __('Mensaje', 'cad-theme'),
            'type'         => 'textarea',
            'required'     => true,
            'maxlength'    => 2000,
            'autocomplete' => 'off',
            'rows'         => 7,
        ),
    );

    return apply_filters('cad_theme_contact_form_fields', $fields);
}

function cad_theme_contact_get_default_form_values()
{
    $values = array(
        'privacy' => '',
    );

    foreach (cad_theme_contact_form_fields() as $key => $field) {
        unset($field);
        $values[$key] = '';
    }

    return $values;
}

function cad_theme_contact_get_form_state()
{
    $state = array(
        'status'  => '',
        'message' => '',
        'errors'  => array(),
        'values'  => cad_theme_contact_get_default_form_values(),
    );

    $token = isset($_GET['cad_contact_notice']) ? sanitize_key(wp_unslash($_GET['cad_contact_notice'])) : '';

    if ('' === $token) {
        return $state;
    }

    $stored = get_transient('cad_contact_notice_' . $token);

    if (!is_array($stored)) {
        return $state;
    }

    delete_transient('cad_contact_notice_' . $token);

    return wp_parse_args($stored, $state);
}

function cad_theme_contact_store_form_state($state)
{
    $token = wp_generate_password(12, false, false);
    set_transient('cad_contact_notice_' . $token, $state, 10 * MINUTE_IN_SECONDS);

    return $token;
}

function cad_theme_contact_get_request_ip()
{
    $value = isset($_SERVER['REMOTE_ADDR']) ? (string) wp_unslash($_SERVER['REMOTE_ADDR']) : '';

    return trim($value);
}

function cad_theme_contact_get_rate_limit_key($email)
{
    return 'cad_contact_rate_' . md5(cad_theme_contact_get_request_ip() . '|' . strtolower((string) $email));
}

function cad_theme_contact_is_rate_limited($email)
{
    return (bool) get_transient(cad_theme_contact_get_rate_limit_key($email));
}

function cad_theme_contact_set_rate_limit($email)
{
    set_transient(cad_theme_contact_get_rate_limit_key($email), 1, MINUTE_IN_SECONDS);
}

function cad_theme_contact_get_return_url_from_request($page_id = 0)
{
    $return_url = isset($_POST['cad_contact_return_url']) ? esc_url_raw(wp_unslash($_POST['cad_contact_return_url'])) : '';
    $fallback = $page_id ? get_permalink($page_id) : home_url('/');

    if (!$fallback) {
        $fallback = home_url('/');
    }

    if ('' === $return_url) {
        return $fallback;
    }

    return wp_validate_redirect($return_url, $fallback);
}

function cad_theme_contact_redirect_with_state($return_url, $state)
{
    $token = cad_theme_contact_store_form_state($state);
    $url = add_query_arg('cad_contact_notice', $token, $return_url);
    wp_safe_redirect($url . '#cad-contact-form');
    exit;
}

function cad_theme_contact_get_string_length($value)
{
    return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
}

function cad_theme_contact_validate_submission($request)
{
    $values = cad_theme_contact_get_default_form_values();
    $errors = array();

    foreach (cad_theme_contact_form_fields() as $key => $field) {
        $raw_value = isset($request[$key]) ? wp_unslash($request[$key]) : '';
        $type = isset($field['type']) ? $field['type'] : 'text';

        if ('textarea' === $type) {
            $value = sanitize_textarea_field($raw_value);
        } elseif ('email' === $type) {
            $value = sanitize_email($raw_value);
        } else {
            $value = sanitize_text_field($raw_value);
        }

        $values[$key] = $value;
        $required = !empty($field['required']);
        $maxlength = isset($field['maxlength']) ? absint($field['maxlength']) : 0;

        if ($required && '' === trim($value)) {
            $errors[$key] = __('Este campo es obligatorio.', 'cad-theme');
            continue;
        }

        if ('email' === $type && '' !== $value && !is_email($value)) {
            $errors[$key] = __('Ingresa un correo valido.', 'cad-theme');
        }

        if ($maxlength > 0 && cad_theme_contact_get_string_length($value) > $maxlength) {
            $errors[$key] = sprintf(__('No puede superar %d caracteres.', 'cad-theme'), $maxlength);
        }
    }

    $values['privacy'] = isset($request['cad_contact_privacy']) ? '1' : '';

    if ('1' !== $values['privacy']) {
        $errors['privacy'] = __('Debes aceptar la politica de privacidad.', 'cad-theme');
    }

    return array(
        'values' => $values,
        'errors' => $errors,
    );
}

function cad_theme_contact_handle_form_submission()
{
    $page_id = isset($_POST['cad_contact_page_id']) ? absint(wp_unslash($_POST['cad_contact_page_id'])) : 0;
    $return_url = cad_theme_contact_get_return_url_from_request($page_id);
    $default_error = __('No fue posible procesar tu solicitud. Intenta nuevamente.', 'cad-theme');

    if (!isset($_POST['cad_contact_nonce']) || !wp_verify_nonce(wp_unslash($_POST['cad_contact_nonce']), 'cad_contact_form_submit')) {
        cad_theme_contact_redirect_with_state(
            $return_url,
            array(
                'status'  => 'error',
                'message' => $default_error,
                'errors'  => array(),
                'values'  => cad_theme_contact_get_default_form_values(),
            )
        );
    }

    $honeypot = isset($_POST['cad_contact_company']) ? sanitize_text_field(wp_unslash($_POST['cad_contact_company'])) : '';
    $issued_at = isset($_POST['cad_contact_issued_at']) ? absint(wp_unslash($_POST['cad_contact_issued_at'])) : 0;

    if ('' !== $honeypot || !$issued_at || (time() - $issued_at) < 3) {
        cad_theme_contact_redirect_with_state(
            $return_url,
            array(
                'status'  => 'error',
                'message' => $default_error,
                'errors'  => array(),
                'values'  => cad_theme_contact_get_default_form_values(),
            )
        );
    }

    $validated = cad_theme_contact_validate_submission($_POST);

    if (!empty($validated['errors'])) {
        cad_theme_contact_redirect_with_state(
            $return_url,
            array(
                'status'  => 'error',
                'message' => __('Revisa los campos marcados y vuelve a intentarlo.', 'cad-theme'),
                'errors'  => $validated['errors'],
                'values'  => $validated['values'],
            )
        );
    }

    if (cad_theme_contact_is_rate_limited($validated['values']['email'])) {
        cad_theme_contact_redirect_with_state(
            $return_url,
            array(
                'status'  => 'error',
                'message' => __('Ya recibimos una solicitud reciente desde este contacto. Espera un minuto antes de reenviar.', 'cad-theme'),
                'errors'  => array(),
                'values'  => $validated['values'],
            )
        );
    }

    $origin_url = $page_id ? get_permalink($page_id) : $return_url;
    $origin_label = $page_id ? get_the_title($page_id) : __('Pagina de contacto', 'cad-theme');
    $ip_hash = wp_hash(cad_theme_contact_get_request_ip());

    $payload = array(
        'full_name'      => $validated['values']['full_name'],
        'email'          => $validated['values']['email'],
        'phone'          => $validated['values']['phone'],
        'subject'        => $validated['values']['subject'],
        'message'        => $validated['values']['message'],
        'channel'        => 'web_form',
        'origin_url'     => (string) $origin_url,
        'origin_label'   => (string) $origin_label,
        'origin_page_id' => $page_id,
        'ip_hash'        => $ip_hash,
    );

    $message_id = cad_theme_contact_insert_message($payload);

    if (is_wp_error($message_id) || !$message_id) {
        cad_theme_contact_redirect_with_state(
            $return_url,
            array(
                'status'  => 'error',
                'message' => __('No pudimos registrar tu mensaje en este momento. Intenta nuevamente.', 'cad-theme'),
                'errors'  => array(),
                'values'  => $validated['values'],
            )
        );
    }

    $admin_mail_sent = cad_theme_contact_send_admin_notification($message_id, $payload);
    $user_mail_sent = cad_theme_contact_send_user_confirmation($message_id, $payload);

    cad_theme_contact_update_delivery_status($message_id, $admin_mail_sent, $user_mail_sent);
    cad_theme_contact_set_rate_limit($validated['values']['email']);

    cad_theme_contact_redirect_with_state(
        $return_url,
        array(
            'status'  => 'success',
            'message' => __('Tu mensaje fue enviado correctamente. Nuestro equipo lo revisara a la brevedad.', 'cad-theme'),
            'errors'  => array(),
            'values'  => cad_theme_contact_get_default_form_values(),
        )
    );
}
add_action('admin_post_nopriv_cad_submit_contact_form', 'cad_theme_contact_handle_form_submission');
add_action('admin_post_cad_submit_contact_form', 'cad_theme_contact_handle_form_submission');

function cad_theme_contact_split_lines($value)
{
    $lines = preg_split('/\r\n|\r|\n/', (string) $value);

    if (!is_array($lines)) {
        return array();
    }

    return array_values(
        array_filter(
            array_map(
                'trim',
                $lines
            )
        )
    );
}

function cad_theme_contact_get_public_contact_data()
{
    $settings = cad_theme_contact_get_settings();
    $phone = isset($settings['public_phone']) ? (string) $settings['public_phone'] : '';
    $email = isset($settings['public_email']) ? (string) $settings['public_email'] : '';
    $address = isset($settings['public_address']) ? (string) $settings['public_address'] : '';
    $hours = isset($settings['business_hours']) ? (string) $settings['business_hours'] : '';
    $phone_url = function_exists('cad_theme_footer_phone_url') ? cad_theme_footer_phone_url($phone) : '';

    if ('' === $phone_url && '' !== $phone) {
        $digits = preg_replace('/\D+/', '', $phone);
        $prefix = 0 === strpos($phone, '+') ? '+' : '';
        if (is_string($digits) && '' !== $digits) {
            $phone_url = 'tel:' . $prefix . $digits;
        }
    }

    return array(
        'phone'         => $phone,
        'phone_url'     => $phone_url,
        'email'         => $email,
        'email_url'     => '' !== $email ? 'mailto:' . antispambot($email) : '',
        'address_lines' => cad_theme_contact_split_lines($address),
        'hours_lines'   => cad_theme_contact_split_lines($hours),
    );
}

function cad_theme_contact_get_whatsapp_url()
{
    $settings = cad_theme_contact_get_settings();
    $number = isset($settings['whatsapp_number']) ? cad_theme_contact_normalize_whatsapp_number($settings['whatsapp_number']) : '';
    $message = isset($settings['whatsapp_message']) ? (string) $settings['whatsapp_message'] : '';

    if ('' === $number) {
        return '';
    }

    return 'https://wa.me/' . rawurlencode($number) . '?text=' . rawurlencode($message);
}

function cad_theme_contact_format_rich_text($text)
{
    $text = trim((string) $text);

    if ('' === $text) {
        return '';
    }

    return wpautop(wp_kses_post($text));
}

function cad_theme_contact_get_privacy_label_html()
{
    $settings = cad_theme_contact_get_settings();
    $label = isset($settings['legal_text']) ? trim((string) $settings['legal_text']) : '';

    if ('' === $label) {
        $label = cad_theme_contact_get_settings_defaults()['legal_text'];
    }

    $policy_url = get_privacy_policy_url();

    if ($policy_url && false === stripos($label, '<a ')) {
        $label .= ' <a href="' . esc_url($policy_url) . '" target="_blank" rel="noopener noreferrer">' . esc_html__('Ver politica', 'cad-theme') . '</a>';
    }

    return wp_kses_post($label);
}

function cad_theme_contact_render_form($page_id, $state = array())
{
    $state = wp_parse_args(
        $state,
        array(
            'status'  => '',
            'message' => '',
            'errors'  => array(),
            'values'  => cad_theme_contact_get_default_form_values(),
        )
    );
    $values = wp_parse_args($state['values'], cad_theme_contact_get_default_form_values());
    $errors = is_array($state['errors']) ? $state['errors'] : array();
    $return_url = get_permalink($page_id);
    ?>
    <div class="cad-contact-form">
        <?php if (!empty($state['message'])) : ?>
            <div class="cad-contact-form__notice is-<?php echo esc_attr($state['status'] ? $state['status'] : 'info'); ?>" aria-live="polite">
                <?php echo esc_html((string) $state['message']); ?>
            </div>
        <?php endif; ?>

        <form id="cad-contact-form" class="cad-contact-form__form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" novalidate>
            <input type="hidden" name="action" value="cad_submit_contact_form">
            <input type="hidden" name="cad_contact_page_id" value="<?php echo esc_attr((string) absint($page_id)); ?>">
            <input type="hidden" name="cad_contact_return_url" value="<?php echo esc_url($return_url); ?>">
            <input type="hidden" name="cad_contact_issued_at" value="<?php echo esc_attr((string) time()); ?>">
            <?php wp_nonce_field('cad_contact_form_submit', 'cad_contact_nonce'); ?>

            <div class="cad-contact-form__honeypot" aria-hidden="true">
                <label for="cad-contact-company"><?php esc_html_e('Empresa', 'cad-theme'); ?></label>
                <input id="cad-contact-company" type="text" name="cad_contact_company" tabindex="-1" autocomplete="off">
            </div>

            <div class="cad-contact-form__grid">
                <?php foreach (cad_theme_contact_form_fields() as $key => $field) : ?>
                    <?php
                    $field_id = 'cad-contact-' . $key;
                    $field_type = isset($field['type']) ? (string) $field['type'] : 'text';
                    $field_value = isset($values[$key]) ? (string) $values[$key] : '';
                    $field_error = isset($errors[$key]) ? (string) $errors[$key] : '';
                    $field_rows = isset($field['rows']) ? absint($field['rows']) : 4;
                    $field_class = 'cad-contact-form__field';
                    if ('message' === $key) {
                        $field_class .= ' is-full';
                    }
                    ?>
                    <div class="<?php echo esc_attr($field_class); ?>">
                        <label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html((string) $field['label']); ?></label>
                        <?php if ('textarea' === $field_type) : ?>
                            <textarea
                                id="<?php echo esc_attr($field_id); ?>"
                                name="<?php echo esc_attr($key); ?>"
                                rows="<?php echo esc_attr((string) $field_rows); ?>"
                                maxlength="<?php echo esc_attr((string) absint($field['maxlength'])); ?>"
                                <?php echo !empty($field['required']) ? 'required' : ''; ?>
                                aria-invalid="<?php echo $field_error ? 'true' : 'false'; ?>"
                                aria-describedby="<?php echo $field_error ? esc_attr($field_id . '-error') : ''; ?>"
                            ><?php echo esc_textarea($field_value); ?></textarea>
                        <?php else : ?>
                            <input
                                id="<?php echo esc_attr($field_id); ?>"
                                type="<?php echo esc_attr($field_type); ?>"
                                name="<?php echo esc_attr($key); ?>"
                                value="<?php echo esc_attr($field_value); ?>"
                                maxlength="<?php echo esc_attr((string) absint($field['maxlength'])); ?>"
                                autocomplete="<?php echo esc_attr(isset($field['autocomplete']) ? (string) $field['autocomplete'] : 'off'); ?>"
                                <?php echo !empty($field['required']) ? 'required' : ''; ?>
                                aria-invalid="<?php echo $field_error ? 'true' : 'false'; ?>"
                                aria-describedby="<?php echo $field_error ? esc_attr($field_id . '-error') : ''; ?>"
                            >
                        <?php endif; ?>

                        <?php if ($field_error) : ?>
                            <p id="<?php echo esc_attr($field_id . '-error'); ?>" class="cad-contact-form__error"><?php echo esc_html($field_error); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cad-contact-form__field cad-contact-form__field--checkbox">
                <label class="cad-contact-form__checkbox">
                    <input type="checkbox" name="cad_contact_privacy" value="1" <?php checked('1', isset($values['privacy']) ? $values['privacy'] : ''); ?>>
                    <span><?php echo cad_theme_contact_get_privacy_label_html(); ?></span>
                </label>
                <?php if (!empty($errors['privacy'])) : ?>
                    <p class="cad-contact-form__error"><?php echo esc_html((string) $errors['privacy']); ?></p>
                <?php endif; ?>
            </div>

            <div class="cad-contact-form__actions">
                <button type="submit" class="cad-business-area__cta-primary cad-contact-form__submit">
                    <?php esc_html_e('Enviar mensaje', 'cad-theme'); ?>
                </button>
            </div>
        </form>
    </div>
    <?php
}
