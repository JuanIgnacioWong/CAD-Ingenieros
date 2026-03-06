# CAD Theme (Proyecto Independiente)

Este proyecto contiene una instalacion independiente de WordPress para `CAD Theme`.

## Ruta del proyecto

`/Users/ignaciowong/Documents/CAD-theme`

## Estructura

- `.env`
- `docker-compose.yml`
- `database/backups/`
- `wordpress/themes/cad-theme/`
- `wordpress/plugins/`
- `wordpress/mu-plugins/`
- `wordpress/uploads/`

Nota: el tema local esta normalizado como `wordpress/themes/cad-theme`, y Docker lo monta dentro de WordPress como `wp-content/themes/CAD-theme` para mantener compatibilidad.

## Variables de entorno

Archivo: `.env`

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
