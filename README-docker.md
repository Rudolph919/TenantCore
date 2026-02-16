# TenantCore – Docker / Podman

Run the full stack with **Podman** and **podman-compose** (or Docker Compose). API-only app; no Node/Vite service needed.

## Stack

- **tenantcore_db**: MySQL 8.0, database `tenantcore`, port **3311** (host)
- **tenantcore_app**: PHP 8.3-FPM (Laravel)
- **tenantcore_web**: nginx → **http://localhost:8084**

## One-time setup

```bash
cp .env.example .env
# Edit .env: APP_URL=http://localhost:8084

podman compose up -d --build
podman compose exec tenantcore_app php artisan key:generate
podman compose exec tenantcore_app php artisan migrate --force
```

## URLs

- **Without proxy**: http://localhost:8084 (API)
- **With proxy**: https://tenantcore.docker
