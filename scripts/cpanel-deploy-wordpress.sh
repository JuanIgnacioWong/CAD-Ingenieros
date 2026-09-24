#!/bin/bash

set -euo pipefail

WP_ROOT_DIR="${WP_ROOT_DIR:-$HOME/public_html}"
THEME_SOURCE_DIR="wordpress/themes/cad-theme"
THEME_TARGET_DIR="$WP_ROOT_DIR/wp-content/themes/CAD-theme"
ACF_SOURCE_DIR="wordpress/plugins/advanced-custom-fields"
ACF_TARGET_DIR="$WP_ROOT_DIR/wp-content/plugins/advanced-custom-fields"

if [ ! -d "$THEME_SOURCE_DIR" ]; then
    echo "No existe el directorio del tema: $THEME_SOURCE_DIR" >&2
    exit 1
fi

if [ ! -d "$ACF_SOURCE_DIR" ]; then
    echo "No existe el directorio de ACF: $ACF_SOURCE_DIR" >&2
    exit 1
fi

if [ ! -f "$WP_ROOT_DIR/wp-load.php" ]; then
    echo "No existe una instalacion de WordPress en: $WP_ROOT_DIR" >&2
    exit 1
fi

/bin/mkdir -p "$THEME_TARGET_DIR" "$ACF_TARGET_DIR"

if command -v rsync >/dev/null 2>&1; then
    # Deploy only the versioned theme and ACF. WordPress and site data stay server-managed.
    rsync -a --delete \
        --exclude='.git/' \
        --exclude='.gitignore' \
        --exclude='.DS_Store' \
        --exclude='.gitkeep' \
        "$THEME_SOURCE_DIR"/ "$THEME_TARGET_DIR"/

    rsync -a --delete \
        --exclude='.git/' \
        --exclude='.gitignore' \
        --exclude='.DS_Store' \
        --exclude='.gitkeep' \
        "$ACF_SOURCE_DIR"/ "$ACF_TARGET_DIR"/
else
    echo "rsync no esta disponible; se copiaran el tema y ACF sin eliminar archivos obsoletos." >&2
    /bin/cp -R "$THEME_SOURCE_DIR"/. "$THEME_TARGET_DIR"/
    /bin/cp -R "$ACF_SOURCE_DIR"/. "$ACF_TARGET_DIR"/
fi

if [ "${SKIP_ACF_ACTIVATION:-0}" = "1" ]; then
    echo "ACF copiado; activacion omitida por SKIP_ACF_ACTIVATION=1."
    exit 0
fi

# A vanilla WordPress archive from wordpress.org has wp-load.php but no
# wp-config.php yet. Copy the theme and ACF now, and defer activation until
# the administrator finishes the WordPress installation and database setup.
if [ ! -f "$WP_ROOT_DIR/wp-config.php" ]; then
    echo "Theme y ACF copiados; wp-config.php no existe, activacion de ACF pendiente."
    exit 0
fi

if command -v wp >/dev/null 2>&1; then
    wp --path="$WP_ROOT_DIR" plugin activate advanced-custom-fields
    exit 0
fi

if command -v php >/dev/null 2>&1; then
    php -r '
        require $argv[1] . "/wp-load.php";
        require_once ABSPATH . "wp-admin/includes/plugin.php";
        $result = activate_plugin("advanced-custom-fields/acf.php");
        if (is_wp_error($result)) {
            fwrite(STDERR, $result->get_error_message() . PHP_EOL);
            exit(1);
        }
        echo "ACF activado.\n";
    ' "$WP_ROOT_DIR"
    exit 0
fi

echo "No se encontro WP-CLI ni PHP CLI para activar ACF." >&2
exit 1
