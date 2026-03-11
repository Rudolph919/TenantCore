# TenantCore

**API-First Multi-Tenant Backend** — A clean, maintainable Laravel API demonstrating tenant isolation, stable contracts, and SaaS architecture fundamentals.

---

## Purpose

This application models the core concerns of multi-tenant SaaS backends:

- **Strict tenant isolation** — Data and auth scoped per tenant
- **Stable API contracts** — Versioned routes, consistent JSON responses
- **Secure token-based authentication** — Laravel Sanctum, tenant-scoped
- **Long-term maintainability** — Clear structure, test coverage, minimal complexity

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| Framework | Laravel 12 (API-only) |
| Auth | Laravel Sanctum (Bearer tokens) |
| Database | MySQL / PostgreSQL (SQLite for tests) |
| Testing | Pest, PHPUnit 12 |

---

## Core Features

- **Multi-tenant architecture** — Tenant model, `X-Tenant-ID` header resolution
- **Tenant-scoped authentication** — Login/register/logout per tenant
- **Versioned REST API** — `/api/v1` prefix
- **Tenant-aware CRUD** — Sample Item resource with isolation
- **Rate limiting** — Laravel default API throttle
- **Test coverage** — Pest-based unit and feature tests

---

## Architecture

```
Request → ResolveTenant (X-Tenant-ID) → auth:sanctum (protected routes) → Controller
                ↓
         Tenant bound to request / container
                ↓
         All queries scoped to tenant
```

- **Middleware-based tenant resolution** — Tenant resolved from header before controllers
- **Single-database tenancy** — Shared DB, `tenant_id` on all tenant-scoped tables
- **Explicit API versioning** — `/api/v1` for future compatibility

---

## API Endpoints

All tenant-scoped routes require the `X-Tenant-ID` header (tenant slug or id).

### Public

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1` | API version info |
| GET | `/up` | Health check |

### Tenant Context (no auth)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/tenant` | Current tenant info |

### Auth

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/login` | Login (email, password) → token |
| POST | `/api/v1/register` | Register user → token |
| POST | `/api/v1/logout` | Revoke token (Bearer required) |
| GET | `/api/v1/me` | Current user (Bearer required) |

### Items (Bearer + tenant required)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/items` | List items |
| POST | `/api/v1/items` | Create item |
| GET | `/api/v1/items/{id}` | Show item |
| PUT/PATCH | `/api/v1/items/{id}` | Update item |
| DELETE | `/api/v1/items/{id}` | Delete item |

---

## Quick Start

### Local (PHP + SQLite)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

### Docker / Podman

See [README-docker.md](README-docker.md).

### Example Request

```bash
# Create tenant (via seeder or DB)
# Register user
curl -X POST http://localhost:8000/api/v1/register \
  -H "X-Tenant-ID: acme" \
  -H "Content-Type: application/json" \
  -d '{"name":"Jane","email":"jane@acme.com","password":"password","password_confirmation":"password"}'

# Create item (use token from response)
curl -X POST http://localhost:8000/api/v1/items \
  -H "X-Tenant-ID: acme" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"My Item","description":"Optional description"}'
```

---

## Testing

```bash
php artisan test
```

Tests use SQLite in-memory (no Docker required). Coverage includes:

- API skeleton and health
- Tenant resolution (missing header, invalid tenant, by id/slug)
- Auth (login, register, logout, tenant scoping)
- Item CRUD and tenant isolation (cross-tenant returns 404)

---

## Key Design Decisions

| Decision | Rationale |
|----------|-----------|
| **X-Tenant-ID header** | Stateless, works with tokens; no session/subdomain coupling |
| **Middleware before auth** | Tenant must be known before user lookup (email can repeat across tenants) |
| **Explicit tenant check in controllers** | Show/show/update/destroy verify `tenant_id` to prevent cross-tenant access |
| **Versioned routes** | `/api/v1` allows future breaking changes without breaking clients |

---

## Tradeoffs

- **Single-database tenancy** — Simpler ops; for very large scale, consider schema-per-tenant or DB-per-tenant
- **REST over GraphQL** — Simpler contracts; GraphQL adds flexibility at the cost of complexity
- **Minimal event-driven logic** — Keeps the example focused; add events/jobs as needed

---

## Potential Improvements

- Webhooks for tenant events
- Async job processing
- Tenant-level analytics
- OpenAPI/Swagger documentation
- Form Request classes for validation
- Policies for fine-grained authorization

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/V1/ItemController.php
│   │   └── Auth/{Login,Register}Controller.php
│   └── Middleware/ResolveTenant.php
├── Models/
│   ├── Item.php
│   ├── Tenant.php
│   └── User.php
routes/
└── api.php          # Versioned, tenant-scoped routes
tests/
├── Feature/
│   ├── Api/V1/      # API skeleton, Item CRUD
│   ├── Auth/        # Login, register, token scoping
│   └── Tenant/      # Tenant resolution
└── Unit/Models/    # Tenant model
```

---

## License

MIT
