#!/bin/bash

set -euo pipefail

SOURCE_DIR="wordpress/themes/cad-theme"
TARGET_DIR="${DEPLOYPATH:-$HOME/public_html/wp-content/themes/CAD-theme}"

if [ ! -d "$SOURCE_DIR" ]; then
    echo "No existe el directorio de origen: $SOURCE_DIR" >&2
    exit 1
fi

/bin/mkdir -p "$TARGET_DIR"

if command -v rsync >/dev/null 2>&1; then
    rsync -a --delete \
        --exclude='.git/' \
        --exclude='.gitignore' \
        --exclude='.DS_Store' \
        "$SOURCE_DIR"/ "$TARGET_DIR"/
else
    echo "rsync no esta disponible; se copiara sin eliminar archivos obsoletos." >&2
    /bin/cp -R "$SOURCE_DIR"/. "$TARGET_DIR"/
fi
