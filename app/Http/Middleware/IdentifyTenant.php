<?php

namespace App\Http\Middleware;

use App\Exceptions\TenantNotFoundException;
use App\Exceptions\TenantRequiredException;
use App\Models\Tenant;
use App\Support\Tenancy\SubdomainResolver;
use App\Support\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function __construct(
        protected TenantContext $tenantContext,
        protected SubdomainResolver $subdomainResolver,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $slug = $this->subdomainResolver->fromHost($request->getHost());

        if ($slug === null || $this->isReservedSubdomain($slug)) {
            throw TenantRequiredException::forRoute();
        }

        $tenant = Cache::remember(
            config('forgebase.cache_key_prefix').$slug,
            now()->addMinutes((int) config('forgebase.tenant_cache_ttl', 30)),
            fn () => Tenant::query()->where('slug', $slug)->first(),
        );

        if ($tenant === null) {
            throw TenantNotFoundException::forSlug($slug);
        }

        $this->tenantContext->set($tenant);

        return $next($request);
    }

    protected function isReservedSubdomain(string $slug): bool
    {
        $reserved = array_map(
            static fn (string $value): string => strtolower(trim($value)),
            (array) config('forgebase.reserved_subdomains', []),
        );

        return in_array(strtolower($slug), $reserved, true);
    }
}
