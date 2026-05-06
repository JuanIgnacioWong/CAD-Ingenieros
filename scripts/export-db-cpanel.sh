#!/bin/bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "${ROOT_DIR}"

if [ ! -f ".env" ]; then
    echo "ERROR: no existe .env"
    exit 1
fi

set -a
# shellcheck disable=SC1091
source .env
set +a

if [ -z "${MYSQL_DATABASE:-}" ] || [ -z "${MYSQL_USER:-}" ] || [ -z "${MYSQL_PASSWORD:-}" ]; then
    echo "ERROR: faltan MYSQL_DATABASE, MYSQL_USER o MYSQL_PASSWORD en .env"
    exit 1
fi

mkdir -p database/backups

timestamp="$(date +%Y%m%d_%H%M%S)"
output="database/backups/db_backup_${MYSQL_DATABASE}_${timestamp}_cpanel_ready.sql"

docker compose exec -T db mysqldump \
    --no-tablespaces \
    --single-transaction \
    -u"${MYSQL_USER}" \
    -p"${MYSQL_PASSWORD}" \
    "${MYSQL_DATABASE}" > "${output}"

echo "Backup generado: ${output}"
