# Changelog
All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog, and this project adheres to Semantic Versioning.

## [1.0.0] - 2026-02-28

### Added
- Laravel 12 foundation
- Docker-based local development environment
- nip.io local subdomain setup for tenant domains
- Subdomain multi-tenancy support
- Cached tenant resolver for improved request performance
- Request-scoped TenantContext for tenant-aware runtime access
- BelongsToTenant global scope to enforce tenant isolation at the model layer
- Central workspace switching (central domain → tenant context)
- Breeze authentication scaffolding
- Tenant-scoped Projects CRUD under tenant domains
- ProjectPolicy with role-based authorization (owner/admin/member)
- RBAC: member role enforced as read-only for Projects

### Security
- Tenant isolation enforced through global scopes and request-scoped context
- Authorization boundaries defined via policies

### Notes
- This is the first stable release of Forgebase, focused on multi-tenant SaaS foundations:
  tenancy, isolation, RBAC, and local dev ergonomics.
