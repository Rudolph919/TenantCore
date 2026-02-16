# TenantCore

## API-First Multi-Tenant Backend

### Purpose

This application demonstrates a clean, maintainable API design supporting multiple tenants with strong isolation and predictable contracts.

---

## Problem Statement

Many SaaS platforms require:

- Strict tenant isolation
- Stable API contracts
- Secure token-based authentication
- Long-term maintainability

This application models those concerns without unnecessary complexity.

---

## Core Features

- Multi-tenant architecture
- Tenant-scoped authentication
- Versioned REST API
- Rate limiting
- Tenant-aware CRUD resources

---

## Tech Stack

- Laravel 11/12 (API-only)
- Laravel Sanctum (token-based)
- MySQL / PostgreSQL

---

## Architecture Overview

- Each request is scoped to a tenant via middleware
- Data access is automatically tenant-aware
- APIs are versioned to protect consumers
- Background concerns are kept minimal for clarity

---

## Key Design Decisions

- **Middleware-based tenant resolution**
- **Explicit API versioning**
- **Clear error response formats**
- **Simple resource controllers**

---

## Tradeoffs

- Single-database tenancy model
- REST instead of GraphQL
- Minimal event-driven complexity

---

## What This Demonstrates

- API contract discipline
- Multi-tenant thinking
- Secure backend design
- Maintainable Laravel APIs

---

## Step-by-Step Build Prompts (Commit-Friendly)

1. **API Skeleton** — Create Laravel API-only app with versioned routes.  
   *Commit: `chore: api skeleton with versioned routes`*

2. **Tenant Model** — Add Tenant model and middleware to scope requests.  
   *Commit: `feat: tenant isolation middleware`*

3. **Auth Tokens** — Token-based auth scoped per tenant.  
   *Commit: `feat: tenant-scoped api authentication`*

4. **Sample Resource** — Simple CRUD resource with tenant isolation.  
   *Commit: `feat: tenant-aware sample resource`*

---

## Potential Improvements

- Webhooks
- Async event processing
- Tenant-level analytics

---

## Docker / Podman

See [README-docker.md](README-docker.md) for how to run the stack with Podman or Docker.
