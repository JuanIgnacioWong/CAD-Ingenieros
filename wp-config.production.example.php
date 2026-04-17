<?php
/**
 * Plantilla base para produccion en cPanel.
 *
 * Usar como base para public_html/CAD/wp-config.php y reemplazar todos los
 * valores marcados antes de publicar.
 */

define('DB_NAME', 'CAMBIAR_DB_NAME');
define('DB_USER', 'CAMBIAR_DB_USER');
define('DB_PASSWORD', 'CAMBIAR_DB_PASSWORD');
define('DB_HOST', 'localhost');

define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

define('AUTH_KEY', 'CAMBIAR_AUTH_KEY');
define('SECURE_AUTH_KEY', 'CAMBIAR_SECURE_AUTH_KEY');
define('LOGGED_IN_KEY', 'CAMBIAR_LOGGED_IN_KEY');
define('NONCE_KEY', 'CAMBIAR_NONCE_KEY');
define('AUTH_SALT', 'CAMBIAR_AUTH_SALT');
define('SECURE_AUTH_SALT', 'CAMBIAR_SECURE_AUTH_SALT');
define('LOGGED_IN_SALT', 'CAMBIAR_LOGGED_IN_SALT');
define('NONCE_SALT', 'CAMBIAR_NONCE_SALT');

$table_prefix = 'wp_';

define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
define('DISALLOW_FILE_EDIT', true);

// Descomenta estas lineas si quieres fijar la URL del sitio desde config.
// define('WP_HOME', 'https://tu-dominio.cl/CAD');
// define('WP_SITEURL', 'https://tu-dominio.cl/CAD');

// Soporte basico si el hosting queda detras de proxy o CDN.
if (
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    strpos((string) $_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false
) {
    $_SERVER['HTTPS'] = 'on';
}

if (
    isset($_SERVER['HTTP_X_FORWARDED_SSL']) &&
    'on' === strtolower((string) $_SERVER['HTTP_X_FORWARDED_SSL'])
) {
    $_SERVER['HTTPS'] = 'on';
}

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
