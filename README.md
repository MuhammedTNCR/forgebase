# Forgebase

Forgebase is a production-ready Laravel 12 multi-tenant starter kit built with a single-database, shared-table architecture.

It provides subdomain-based tenancy, strict tenant isolation, role-based authorization, and a central workspace switching flow — designed for SaaS applications.

---

## ✨ Features

- Subdomain-based multi-tenancy
- Single database, shared tables architecture
- Request-scoped `TenantContext`
- Cached tenant resolver
- Global scope tenant isolation
- Role-based access control (owner, admin, member)
- Central workspace switching
- Tenant-safe CRUD example (Projects module)
- Docker-based local development
- Fully tested core isolation logic

---

## 🏗 Architecture Overview

Forgebase follows a **single database + shared tables** approach.

Core tables:

- `tenants`
- `tenant_user` (pivot with role)
- `projects` (tenant-owned example model)

Isolation is enforced by:

- Subdomain resolver middleware
- `BelongsToTenant` trait (global scope)
- Fail-fast behavior if tenant context is missing
- Policy-based role checks

---

## 🌐 Domain Structure (Local Development)

Forgebase uses `nip.io` for wildcard local domains.

Examples:

- `app.127.0.0.1.nip.io` → Central workspace
- `acme.127.0.0.1.nip.io` → Tenant
- `globex.127.0.0.1.nip.io` → Tenant

---

## 🚀 Quick Start (Docker)

### 1. Clone & Start Containers

```bash
git clone git@github.com:MuhammedTNCR/forgebase.git
cd forgebase

docker compose up -d --build
```

---

### 2. Install Dependencies

```bash
docker compose exec app composer install
docker compose run --rm node npm install
docker compose run --rm node npm run build
```

---

### 3. Environment Setup

Copy `.env.example` to `.env` and ensure:

```env
APP_URL=http://app.127.0.0.1.nip.io

FORGEBASE_ROOT_DOMAIN=127.0.0.1.nip.io
FORGEBASE_CENTRAL_SUBDOMAIN=app

SESSION_DOMAIN=.127.0.0.1.nip.io
SESSION_SECURE_COOKIE=false
```

Then run:

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

---

### 4. Access the Application

Central application:

```
http://app.127.0.0.1.nip.io
```

Tenant applications:

```
http://acme.127.0.0.1.nip.io
http://globex.127.0.0.1.nip.io
```

---

## 🧪 Demo Seed Data

Run:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

This seeds:

- Tenants: `acme`, `globex`
- Users: owner/admin/member
- Memberships in `tenant_user` for both tenants
- 3 projects per tenant

Demo credentials (password is `password` for all):

- `owner@forgebase.test` (Owner)
- `admin@forgebase.test` (Admin)
- `member@forgebase.test` (Member)

Login URL:

```
http://app.127.0.0.1.nip.io/login
```

Projects URLs:

```
http://acme.127.0.0.1.nip.io/projects
http://globex.127.0.0.1.nip.io/projects
```

---

## 👥 Roles

Each tenant membership includes a role:

| Role   | Permissions        |
|--------|-------------------|
| Owner  | Full access        |
| Admin  | Manage projects    |
| Member | Read-only access   |

Authorization is enforced via `ProjectPolicy` and scoped per tenant.

---

## 🔐 Tenant Isolation Guarantees

Forgebase guarantees:

- Users cannot access data from other tenants
- Global scope enforces `tenant_id` automatically
- Creating models auto-assigns the current tenant
- Missing tenant context throws a fail-fast exception
- Unknown tenant subdomain returns 404
- Role restrictions are strictly enforced

All critical behaviors are covered by feature tests.

---

## 🧪 Running Tests

Run the full test suite:

```bash
docker compose exec app php artisan test
```

Core tests include:

- Tenant isolation (no cross-tenant data leaks)
- Automatic tenant_id assignment
- Fail-fast tenant enforcement
- Role-based authorization checks

---

## 📦 Example Module: Projects

Under each tenant:

```
http://{tenant}.127.0.0.1.nip.io/projects
```

Permissions:

- Owner/Admin → Full CRUD
- Member → Read-only

This module demonstrates how to build tenant-safe features.

---

## 📁 Simplified Project Structure

```
app/
 ├── Support/Tenancy/
 ├── Models/Concerns/BelongsToTenant.php
 ├── Policies/
 ├── Http/Middleware/IdentifyTenant.php

routes/
 ├── web.php      (central routes)
 ├── tenant.php   (tenant routes)

config/
 ├── forgebase.php
```

---

## 📌 Version

Current version: **v1.0.0**

Forgebase v1.0.0 includes:

- Stable tenancy core
- Workspace switching
- Tenant-safe CRUD example
- Production-ready isolation guarantees

---

## 📄 License

MIT License
