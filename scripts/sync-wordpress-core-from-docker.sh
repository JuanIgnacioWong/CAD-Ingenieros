#!/bin/bash

set -euo pipefail

TARGET_DIR="wordpress-core"
TEMP_DIR="$(mktemp -d)"

cleanup() {
    /bin/rm -rf "$TEMP_DIR"
}

trap cleanup EXIT

docker compose exec -T wordpress sh -lc 'cd /var/www/html && tar cf - --exclude=./wp-config.php --exclude=./wp-config-docker.php --exclude=./.htaccess --exclude=./wp-content/cache --exclude=./wp-content/upgrade --exclude=./wp-content/languages --exclude=./wp-content/uploads --exclude=./wp-content/plugins --exclude=./wp-content/mu-plugins --exclude=./wp-content/themes . ' | tar xf - -C "$TEMP_DIR"

if command -v rsync >/dev/null 2>&1; then
    /bin/mkdir -p "$TARGET_DIR"
    rsync -a --delete "$TEMP_DIR"/ "$TARGET_DIR"/
else
    echo "rsync es requerido para refrescar $TARGET_DIR de forma segura." >&2
    exit 1
fi
