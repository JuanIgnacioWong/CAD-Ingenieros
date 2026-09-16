#!/bin/bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "${ROOT_DIR}"

required_paths=(
    ".cpanel.yml"
    "scripts/cpanel-deploy-wordpress.sh"
    "wordpress/themes/cad-theme"
    "wordpress/plugins/advanced-custom-fields/acf.php"
)

for path in "${required_paths[@]}"; do
    if [ ! -e "${path}" ]; then
        echo "ERROR: falta ${path}"
        exit 1
    fi
done

if ! grep -q "WP_ROOT_DIR" .cpanel.yml || ! grep -q "scripts/cpanel-deploy-wordpress.sh" .cpanel.yml; then
    echo "ERROR: .cpanel.yml no configura el deploy del tema y ACF"
    exit 1
fi

echo "OK: estructura de deploy encontrada."

if git ls-files --error-unmatch wp-config.production.php >/dev/null 2>&1; then
    echo "ERROR: wp-config.production.php esta versionado (no debe subirse con secretos)."
    exit 1
fi

if [ -f "wp-config.production.php" ]; then
    echo "WARN: existe wp-config.production.php local (revisar secretos antes de push)."
fi

if [ ! -d "wordpress/uploads" ]; then
    echo "WARN: wordpress/uploads no existe; el deploy no incluira media."
fi

echo
echo "Resumen git:"
git status --short --branch

echo
echo "Preflight completado."
