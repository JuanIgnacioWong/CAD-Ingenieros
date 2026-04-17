# CAD Theme (Proyecto Independiente)

Este proyecto contiene una instalacion independiente de WordPress para `CAD Theme`.

## Ruta del proyecto

`/Users/ignaciowong/Documents/CAD-theme`

## Estructura

- `.env.example`
- `docker-compose.yml`
- `database/backups/` (local, no versionado)
- `wordpress/themes/cad-theme/`
- `wordpress/plugins/`
- `wordpress/mu-plugins/`
- `wordpress/uploads/` (local, no versionado)

Nota: el tema local esta normalizado como `wordpress/themes/cad-theme`, y Docker lo monta dentro de WordPress como `wp-content/themes/CAD-theme` para mantener compatibilidad.

## Variables de entorno

Archivo base: `.env.example`

Para trabajar localmente:

```bash
cp .env.example .env
```

- `COMPOSE_PROJECT_NAME=cad_theme`
- `WP_PORT=8081`
- `MYSQL_DATABASE=wordpress_cad_theme`
- `MYSQL_USER=wp_cad_theme`
- `MYSQL_PASSWORD=wp_cad_theme_pass`
- `MYSQL_ROOT_PASSWORD=root_cad_theme_pass`

## Levantar entorno

```bash
cd /Users/ignaciowong/Documents/CAD-theme
docker compose up -d
```

Sitio:

- [http://localhost:8081](http://localhost:8081)

## Detener entorno

```bash
cd /Users/ignaciowong/Documents/CAD-theme
docker compose down
```

## Reinicio limpio (borra DB y volumenes)

```bash
cd /Users/ignaciowong/Documents/CAD-theme
docker compose down -v
docker compose up -d
```

## Activar tema

En WordPress:

1. `Apariencia > Temas`
2. Activar `CAD Theme`

## Git Deploy en cPanel

Este repo ya incluye `.cpanel.yml` para que cPanel despliegue solo el tema desde `wordpress/themes/cad-theme/` hacia:

`$HOME/public_html/wp-content/themes/CAD-theme`

Importante:

- El destino usa `CAD-theme` con mayusculas porque el backup actual de WordPress tiene `template` y `stylesheet` configurados asi.
- El deploy no publica `database/`, `uploads/`, `.env` ni archivos de Docker.
- Si el servidor tiene `rsync`, el deploy elimina archivos obsoletos del tema. Si no lo tiene, hace copia simple y los archivos borrados en Git pueden quedar en produccion.

### Flujo recomendado

1. En cPanel abre `Git Version Control`.
2. Crea un repositorio nuevo o clona este repositorio remoto.
3. Verifica que la rama desplegada sea la que usaras en produccion.
4. En modo `Pull deployment`, usa `Update from Remote` y luego `Deploy HEAD Commit`.
5. En modo `Push deployment`, agrega el remoto de cPanel a tu repo local y haz `git push` hacia ese remoto.

### Archivos de deploy

- `.cpanel.yml`: entrypoint que usa la ruta de cPanel y ejecuta el script.
- `scripts/cpanel-deploy-theme.sh`: crea la carpeta destino y copia solo el tema.

### Prueba local del script

```bash
cd /Users/ignaciowong/Documents/CAD-theme
DEPLOYPATH=/tmp/CAD-theme bash scripts/cpanel-deploy-theme.sh
```

### Git ignore base

Se agrego `.gitignore` para ignorar:

- `.env`
- `database/backups/`
- `wordpress/uploads/`

El repo limpio debe versionar `.env.example`, no `.env`, y dejar backups/uploads solo como datos locales.
