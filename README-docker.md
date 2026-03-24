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
podman compose exec tenantcore_app php artisan migrate --force --seed
```

Creates a demo tenant with slug **`acme`** so the README `curl` examples work.

If you already ran migrate without seeding:

```bash
podman compose exec tenantcore_app php artisan db:seed
```

## URLs

- **http://localhost:8084** — web / API (through nginx)
