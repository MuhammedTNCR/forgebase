<?php

namespace App\Support\Tenancy;

use App\Models\Tenant;

class TenancyUrl
{
    public static function tenantUrl(string|Tenant $tenant, string $path = '/'): string
    {
        $tenantSlug = $tenant instanceof Tenant ? $tenant->slug : $tenant;

        return static::toUrl($tenantSlug.'.'.static::rootDomain(), $path);
    }

    public static function centralUrl(string $path = '/'): string
    {
        return static::toUrl(static::centralSubdomain().'.'.static::rootDomain(), $path);
    }

    protected static function toUrl(string $host, string $path): string
    {
        if ($path === '' || $path === '/') {
            return "http://{$host}";
        }

        return "http://{$host}/".ltrim($path, '/');
    }

    protected static function rootDomain(): string
    {
        return (string) config('forgebase.root_domain');
    }

    protected static function centralSubdomain(): string
    {
        return (string) config('forgebase.central_subdomain');
    }
}
