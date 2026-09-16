# CAD Theme (Proyecto Independiente)

Este proyecto contiene una instalacion independiente de WordPress para `CAD Theme`.

## Ruta del proyecto

`/Users/ignaciowong/Documents/CAD-theme`

## Estructura

- `.env.example`
- `docker-compose.yml`
- `wp-config.production.example.php`
- `wordpress-core/`
- `database/backups/` (local, no versionado)
- `wordpress/themes/cad-theme/`
- `wordpress/plugins/`
- `wordpress/mu-plugins/`
- `wordpress/uploads/`

Nota: el tema local esta normalizado como `wordpress/themes/cad-theme`, y Docker lo monta dentro de WordPress como `wp-content/themes/CAD-theme` para mantener compatibilidad.
El deploy copia el tema a `wp-content/themes/CAD-theme` y `wp-content/themes/cad-theme` para evitar problemas por mayusculas/minusculas del directorio activo en produccion.
El core versionado para despliegue vive en `wordpress-core/` y hoy corresponde a WordPress `6.9.1`.

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

## Git Deploy del tema y ACF en cPanel

El hosting mantiene su propia instalacion de WordPress en `public_html`. Este repositorio despliega exclusivamente:

- el tema hacia `$HOME/public_html/wp-content/themes/CAD-theme`;
- Advanced Custom Fields 6.8.10 hacia `$HOME/public_html/wp-content/plugins/advanced-custom-fields`.

Al terminar, el deploy activa ACF con WP-CLI; si no esta disponible, usa PHP CLI. El deploy falla antes de copiar si no detecta una instalacion completa de WordPress en `public_html`.

No copia ni elimina WordPress, `wp-config.php`, `.htaccess`, base de datos, otros plugins, mu-plugins, idiomas, cache ni uploads. Con `rsync` disponible, solo se eliminan archivos obsoletos dentro de las carpetas del tema y ACF.

### Flujo recomendado

1. En cPanel abre `Git Version Control`.
2. Crea un repositorio nuevo o clona este repositorio remoto.
3. Verifica que la rama desplegada sea la que usaras en produccion.
4. En modo `Pull deployment`, usa `Update from Remote` y luego `Deploy HEAD Commit`.
5. En modo `Push deployment`, agrega el remoto de cPanel a tu repo local y haz `git push` hacia ese remoto.

### Checklist GitHub -> cPanel (tema y ACF)

1. Ejecuta preflight local:

```bash
cd /Users/ignaciowong/Documents/CAD-theme
bash scripts/preflight-github-cpanel.sh
```

2. Haz push de la rama a GitHub:

```bash
git push origin main
```

3. En cPanel (repositorio Git):
   - `Update from Remote`
   - `Deploy HEAD Commit`

4. Verifica en `Plugins` que **Advanced Custom Fields** aparezca activo. Activa **CAD Theme** desde `Apariencia > Temas`.

### Archivos del deploy

- `.cpanel.yml`: fija el destino del tema y ejecuta el script.
- `scripts/cpanel-deploy-wordpress.sh`: sincroniza el tema y ACF, luego activa ACF.

### Prueba local del script

```bash
cd /Users/ignaciowong/Documents/CAD-theme
WP_ROOT_DIR=/ruta/a/wordpress SKIP_ACF_ACTIVATION=1 bash scripts/cpanel-deploy-wordpress.sh
```

### Git ignore base

Se agrego `.gitignore` para ignorar:

- `.env`
- `database/backups/`
- `tmp/cpanel-release-*/`

El repo limpio debe versionar `.env.example`, no `.env`.
