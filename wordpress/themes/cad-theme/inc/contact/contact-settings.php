<?php

if (!defined('ABSPATH')) {
    exit;
}

function cad_theme_contact_option_name()
{
    return 'cad_contact_settings';
}

function cad_theme_contact_normalize_whatsapp_number($value)
{
    $digits = preg_replace('/\D+/', '', (string) $value);

    return is_string($digits) ? $digits : '';
}

function cad_theme_contact_normalize_email($value)
{
    $email = sanitize_email((string) $value);

    return is_email($email) ? $email : '';
}

function cad_theme_contact_get_settings_defaults()
{
    $footer_contact = function_exists('cad_theme_get_footer_contact_data') ? cad_theme_get_footer_contact_data() : array();
    $address_lines = isset($footer_contact['address_lines']) && is_array($footer_contact['address_lines']) ? $footer_contact['address_lines'] : array();
    $phone_label = isset($footer_contact['phone_label']) ? (string) $footer_contact['phone_label'] : '';
    $admin_email = (string) get_option('admin_email');

    return array(
        'receiver_email'         => $admin_email,
        'cc_email'               => '',
        'public_email'           => $admin_email,
        'public_phone'           => $phone_label,
        'public_address'         => implode("\n", $address_lines),
        'business_hours'         => __('Lunes a viernes, 09:00 a 18:00 hrs.', 'cad-theme'),
        'whatsapp_number'        => '',
        'whatsapp_message'       => __('Hola, me gustaria comunicarme con CAD Ingenieros.', 'cad-theme'),
        'whatsapp_button_label'  => __('WhatsApp Business', 'cad-theme'),
        'auto_reply_subject'     => __('Hemos recibido tu mensaje', 'cad-theme'),
        'auto_reply_body'        => "Hola {name},\n\nGracias por escribirnos. Hemos recibido tu mensaje sobre \"{subject}\" y nuestro equipo lo revisara a la brevedad.\n\nReferencia: {message_reference}\n\nEquipo {site_name}",
        'page_eyebrow'           => __('Contacto institucional', 'cad-theme'),
        'page_intro'             => __('Completa el formulario y nuestro equipo revisara tu solicitud para responderte por correo en el menor tiempo posible.', 'cad-theme'),
        'sidebar_title'          => __('Atencion directa', 'cad-theme'),
        'sidebar_text'           => __('Si tu consulta requiere seguimiento tecnico, comercial o coordinacion inicial de proyecto, utiliza este canal para centralizar la solicitud.', 'cad-theme'),
        'legal_text'             => __('Acepto la politica de privacidad y el tratamiento de mis datos para gestionar esta solicitud.', 'cad-theme'),
    );
}

function cad_theme_contact_get_settings()
{
    $settings = get_option(cad_theme_contact_option_name(), array());

    if (!is_array($settings)) {
        $settings = array();
    }

    return wp_parse_args($settings, cad_theme_contact_get_settings_defaults());
}

function cad_theme_contact_get_setting($key, $default = '')
{
    $settings = cad_theme_contact_get_settings();

    if (array_key_exists($key, $settings)) {
        return $settings[$key];
    }

    return $default;
}

function cad_theme_contact_sanitize_settings($input)
{
    if (!is_array($input)) {
        $input = array();
    }

    $output = array();

    $output['receiver_email'] = isset($input['receiver_email']) ? cad_theme_contact_normalize_email(wp_unslash($input['receiver_email'])) : '';
    $output['cc_email'] = isset($input['cc_email']) ? cad_theme_contact_normalize_email(wp_unslash($input['cc_email'])) : '';
    $output['public_email'] = isset($input['public_email']) ? cad_theme_contact_normalize_email(wp_unslash($input['public_email'])) : '';
    $output['public_phone'] = isset($input['public_phone']) ? sanitize_text_field(wp_unslash($input['public_phone'])) : '';
    $output['public_address'] = isset($input['public_address']) ? sanitize_textarea_field(wp_unslash($input['public_address'])) : '';
    $output['business_hours'] = isset($input['business_hours']) ? sanitize_textarea_field(wp_unslash($input['business_hours'])) : '';
    $output['whatsapp_number'] = isset($input['whatsapp_number']) ? cad_theme_contact_normalize_whatsapp_number(wp_unslash($input['whatsapp_number'])) : '';
    $output['whatsapp_message'] = isset($input['whatsapp_message']) ? sanitize_textarea_field(wp_unslash($input['whatsapp_message'])) : '';
    $output['whatsapp_button_label'] = isset($input['whatsapp_button_label']) ? sanitize_text_field(wp_unslash($input['whatsapp_button_label'])) : '';
    $output['auto_reply_subject'] = isset($input['auto_reply_subject']) ? sanitize_text_field(wp_unslash($input['auto_reply_subject'])) : '';
    $output['auto_reply_body'] = isset($input['auto_reply_body']) ? sanitize_textarea_field(wp_unslash($input['auto_reply_body'])) : '';
    $output['page_eyebrow'] = isset($input['page_eyebrow']) ? sanitize_text_field(wp_unslash($input['page_eyebrow'])) : '';
    $output['page_intro'] = isset($input['page_intro']) ? wp_kses_post(wp_unslash($input['page_intro'])) : '';
    $output['sidebar_title'] = isset($input['sidebar_title']) ? sanitize_text_field(wp_unslash($input['sidebar_title'])) : '';
    $output['sidebar_text'] = isset($input['sidebar_text']) ? wp_kses_post(wp_unslash($input['sidebar_text'])) : '';
    $output['legal_text'] = isset($input['legal_text']) ? wp_kses_post(wp_unslash($input['legal_text'])) : '';

    if ('' === $output['receiver_email']) {
        $output['receiver_email'] = cad_theme_contact_get_settings_defaults()['receiver_email'];
    }

    if ('' === $output['public_email']) {
        $output['public_email'] = $output['receiver_email'];
    }

    if ('' === trim($output['whatsapp_button_label'])) {
        $output['whatsapp_button_label'] = cad_theme_contact_get_settings_defaults()['whatsapp_button_label'];
    }

    if ('' === trim($output['auto_reply_subject'])) {
        $output['auto_reply_subject'] = cad_theme_contact_get_settings_defaults()['auto_reply_subject'];
    }

    if ('' === trim($output['auto_reply_body'])) {
        $output['auto_reply_body'] = cad_theme_contact_get_settings_defaults()['auto_reply_body'];
    }

    return $output;
}

function cad_theme_contact_register_settings()
{
    register_setting(
        'cad_contact_settings_group',
        cad_theme_contact_option_name(),
        array(
            'type'              => 'array',
            'sanitize_callback' => 'cad_theme_contact_sanitize_settings',
            'default'           => cad_theme_contact_get_settings_defaults(),
        )
    );

    add_settings_section(
        'cad_contact_settings_section_mail',
        __('Correos y recepcion', 'cad-theme'),
        '__return_false',
        'cad-contact-settings'
    );

    add_settings_field(
        'cad_contact_receiver_email',
        __('Correo receptor principal', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_mail',
        array(
            'key'         => 'receiver_email',
            'type'        => 'email',
            'placeholder' => 'contacto@dominio.cl',
            'description' => __('Destino principal de los mensajes enviados desde el formulario.', 'cad-theme'),
        )
    );

    add_settings_field(
        'cad_contact_cc_email',
        __('Correo secundario / copia', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_mail',
        array(
            'key'         => 'cc_email',
            'type'        => 'email',
            'placeholder' => 'equipo@dominio.cl',
            'description' => __('Opcional. Se agrega como copia en la notificacion al equipo interno.', 'cad-theme'),
        )
    );

    add_settings_field(
        'cad_contact_public_email',
        __('Correo visible en frontend', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_mail',
        array(
            'key'         => 'public_email',
            'type'        => 'email',
            'placeholder' => 'contacto@dominio.cl',
            'description' => __('Se muestra en la columna informativa de la pagina de contacto.', 'cad-theme'),
        )
    );

    add_settings_section(
        'cad_contact_settings_section_whatsapp',
        __('WhatsApp Business', 'cad-theme'),
        '__return_false',
        'cad-contact-settings'
    );

    add_settings_field(
        'cad_contact_whatsapp_number',
        __('Numero de WhatsApp', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_whatsapp',
        array(
            'key'         => 'whatsapp_number',
            'type'        => 'text',
            'placeholder' => '56912345678',
            'description' => __('Ingresa solo numeros con codigo de pais para generar el enlace `wa.me`.', 'cad-theme'),
        )
    );

    add_settings_field(
        'cad_contact_whatsapp_message',
        __('Mensaje predefinido', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_whatsapp',
        array(
            'key'         => 'whatsapp_message',
            'type'        => 'textarea',
            'rows'        => 4,
            'placeholder' => __('Hola, me gustaria comunicarme con CAD Ingenieros.', 'cad-theme'),
            'description' => __('Texto inicial del chat que se completa automaticamente al abrir WhatsApp.', 'cad-theme'),
        )
    );

    add_settings_field(
        'cad_contact_whatsapp_button_label',
        __('Texto del boton de WhatsApp', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_whatsapp',
        array(
            'key'         => 'whatsapp_button_label',
            'type'        => 'text',
            'placeholder' => __('WhatsApp Business', 'cad-theme'),
            'description' => __('Etiqueta visible del CTA complementario.', 'cad-theme'),
        )
    );

    add_settings_section(
        'cad_contact_settings_section_copy',
        __('Textos de la pagina', 'cad-theme'),
        '__return_false',
        'cad-contact-settings'
    );

    add_settings_field(
        'cad_contact_page_eyebrow',
        __('Eyebrow / kicker superior', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_copy',
        array(
            'key'         => 'page_eyebrow',
            'type'        => 'text',
            'placeholder' => __('Contacto institucional', 'cad-theme'),
        )
    );

    add_settings_field(
        'cad_contact_page_intro',
        __('Bajada introductoria', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_copy',
        array(
            'key'         => 'page_intro',
            'type'        => 'textarea',
            'rows'        => 5,
            'description' => __('Texto breve bajo el titulo principal. Admite HTML basico.', 'cad-theme'),
        )
    );

    add_settings_field(
        'cad_contact_sidebar_title',
        __('Titulo del bloque informativo', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_copy',
        array(
            'key'         => 'sidebar_title',
            'type'        => 'text',
            'placeholder' => __('Atencion directa', 'cad-theme'),
        )
    );

    add_settings_field(
        'cad_contact_sidebar_text',
        __('Texto del bloque informativo', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_copy',
        array(
            'key'         => 'sidebar_text',
            'type'        => 'textarea',
            'rows'        => 6,
            'description' => __('Contenido introductorio junto a los datos de contacto. Admite HTML basico.', 'cad-theme'),
        )
    );

    add_settings_field(
        'cad_contact_legal_text',
        __('Texto legal / privacidad', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_copy',
        array(
            'key'         => 'legal_text',
            'type'        => 'textarea',
            'rows'        => 4,
            'description' => __('Texto usado en el checkbox obligatorio del formulario. Admite HTML basico.', 'cad-theme'),
        )
    );

    add_settings_section(
        'cad_contact_settings_section_public',
        __('Datos publicos de contacto', 'cad-theme'),
        '__return_false',
        'cad-contact-settings'
    );

    add_settings_field(
        'cad_contact_public_phone',
        __('Telefono visible', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_public',
        array(
            'key'         => 'public_phone',
            'type'        => 'text',
            'placeholder' => '+56 2 2464 4700',
        )
    );

    add_settings_field(
        'cad_contact_public_address',
        __('Direccion visible', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_public',
        array(
            'key'         => 'public_address',
            'type'        => 'textarea',
            'rows'        => 4,
            'description' => __('Una linea por fila para renderizar la direccion institucional.', 'cad-theme'),
        )
    );

    add_settings_field(
        'cad_contact_business_hours',
        __('Horarios / disponibilidad', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_public',
        array(
            'key'         => 'business_hours',
            'type'        => 'textarea',
            'rows'        => 3,
            'description' => __('Texto libre para horarios de atencion, ventanas de respuesta o disponibilidad.', 'cad-theme'),
        )
    );

    add_settings_section(
        'cad_contact_settings_section_auto_reply',
        __('Correo automatico al usuario', 'cad-theme'),
        '__return_false',
        'cad-contact-settings'
    );

    add_settings_field(
        'cad_contact_auto_reply_subject',
        __('Asunto del correo automatico', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_auto_reply',
        array(
            'key'         => 'auto_reply_subject',
            'type'        => 'text',
            'placeholder' => __('Hemos recibido tu mensaje', 'cad-theme'),
            'description' => __('Puedes usar placeholders: {name}, {subject}, {site_name}, {message_reference}.', 'cad-theme'),
        )
    );

    add_settings_field(
        'cad_contact_auto_reply_body',
        __('Cuerpo del correo automatico', 'cad-theme'),
        'cad_theme_contact_render_settings_field',
        'cad-contact-settings',
        'cad_contact_settings_section_auto_reply',
        array(
            'key'         => 'auto_reply_body',
            'type'        => 'textarea',
            'rows'        => 8,
            'description' => __('Texto plano con placeholders: {name}, {subject}, {email}, {phone}, {message_reference}, {site_name}.', 'cad-theme'),
        )
    );
}
add_action('admin_init', 'cad_theme_contact_register_settings');

function cad_theme_contact_render_settings_field($args)
{
    $settings = cad_theme_contact_get_settings();
    $key = isset($args['key']) ? (string) $args['key'] : '';
    $type = isset($args['type']) ? (string) $args['type'] : 'text';
    $rows = isset($args['rows']) ? max(2, absint($args['rows'])) : 4;
    $placeholder = isset($args['placeholder']) ? (string) $args['placeholder'] : '';
    $description = isset($args['description']) ? (string) $args['description'] : '';
    $value = isset($settings[$key]) ? $settings[$key] : '';
    $name = cad_theme_contact_option_name() . '[' . $key . ']';
    $field_id = 'cad-contact-setting-' . $key;

    if ('textarea' === $type) {
        printf(
            '<textarea id="%1$s" name="%2$s" class="large-text" rows="%3$d" placeholder="%4$s">%5$s</textarea>',
            esc_attr($field_id),
            esc_attr($name),
            esc_attr($rows),
            esc_attr($placeholder),
            esc_textarea((string) $value)
        );
    } else {
        printf(
            '<input id="%1$s" name="%2$s" type="%3$s" class="regular-text" value="%4$s" placeholder="%5$s">',
            esc_attr($field_id),
            esc_attr($name),
            esc_attr($type),
            esc_attr((string) $value),
            esc_attr($placeholder)
        );
    }

    if ('' !== $description) {
        printf('<p class="description">%s</p>', wp_kses_post($description));
    }
}
