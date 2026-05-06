#!/bin/bash

set -euo pipefail

CORE_DIR="wordpress-core"
CONTENT_DIR="wordpress"
TARGET_DIR="${DEPLOYPATH:-$HOME/public_html/CAD}"
THEME_SOURCE_DIR="$CONTENT_DIR/themes/cad-theme"
THEME_TARGET_PRIMARY="$TARGET_DIR/wp-content/themes/CAD-theme"
THEME_TARGET_SECONDARY="$TARGET_DIR/wp-content/themes/cad-theme"

if [ ! -d "$CORE_DIR" ]; then
    echo "No existe el directorio del core: $CORE_DIR" >&2
    exit 1
fi

if [ ! -d "$THEME_SOURCE_DIR" ]; then
    echo "No existe el directorio del tema: $THEME_SOURCE_DIR" >&2
    exit 1
fi

/bin/mkdir -p \
    "$TARGET_DIR" \
    "$THEME_TARGET_PRIMARY" \
    "$THEME_TARGET_SECONDARY" \
    "$TARGET_DIR/wp-content/plugins" \
    "$TARGET_DIR/wp-content/mu-plugins" \
    "$TARGET_DIR/wp-content/uploads"

if command -v rsync >/dev/null 2>&1; then
    # Keep server-specific config intact.
    rsync -a --delete \
        --exclude='.git/' \
        --exclude='.gitignore' \
        --exclude='.DS_Store' \
        --exclude='.htaccess' \
        --exclude='wp-config.php' \
        --exclude='wp-config-docker.php' \
        --exclude='wp-content/themes/' \
        --exclude='wp-content/plugins/' \
        --exclude='wp-content/mu-plugins/' \
        --exclude='wp-content/languages/' \
        --exclude='wp-content/cache/' \
        --exclude='wp-content/upgrade/' \
        "$CORE_DIR"/ "$TARGET_DIR"/

    rsync -a --delete \
        --exclude='.git/' \
        --exclude='.gitignore' \
        --exclude='.DS_Store' \
        --exclude='.gitkeep' \
        "$THEME_SOURCE_DIR"/ "$THEME_TARGET_PRIMARY"/

    rsync -a --delete \
        --exclude='.git/' \
        --exclude='.gitignore' \
        --exclude='.DS_Store' \
        --exclude='.gitkeep' \
        "$THEME_SOURCE_DIR"/ "$THEME_TARGET_SECONDARY"/

    rsync -a --delete \
        --exclude='.git/' \
        --exclude='.gitignore' \
        --exclude='.DS_Store' \
        --exclude='.gitkeep' \
        "$CONTENT_DIR/plugins"/ "$TARGET_DIR/wp-content/plugins"/

    rsync -a --delete \
        --exclude='.git/' \
        --exclude='.gitignore' \
        --exclude='.DS_Store' \
        --exclude='.gitkeep' \
        "$CONTENT_DIR/mu-plugins"/ "$TARGET_DIR/wp-content/mu-plugins"/

    rsync -a \
        --exclude='.git/' \
        --exclude='.gitignore' \
        --exclude='.DS_Store' \
        --exclude='.gitkeep' \
        "$CONTENT_DIR/uploads"/ "$TARGET_DIR/wp-content/uploads"/
else
    echo "rsync no esta disponible; se copiara sin eliminar archivos obsoletos." >&2

    /bin/cp -R "$CORE_DIR"/. "$TARGET_DIR"/
    /bin/cp -R "$THEME_SOURCE_DIR"/. "$THEME_TARGET_PRIMARY"/
    /bin/cp -R "$THEME_SOURCE_DIR"/. "$THEME_TARGET_SECONDARY"/
    /bin/cp -R "$CONTENT_DIR/plugins"/. "$TARGET_DIR/wp-content/plugins"/
    /bin/cp -R "$CONTENT_DIR/mu-plugins"/. "$TARGET_DIR/wp-content/mu-plugins"/
    /bin/cp -R "$CONTENT_DIR/uploads"/. "$TARGET_DIR/wp-content/uploads"/
fi
