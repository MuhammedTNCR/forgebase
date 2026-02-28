<?php

namespace App\Support\Tenancy;

class SubdomainResolver
{
    public function fromHost(?string $host): ?string
    {
        if (! is_string($host) || $host === '') {
            return null;
        }

        $normalizedHost = strtolower(trim($host));
        $hostname = parse_url('http://'.$normalizedHost, PHP_URL_HOST);

        if (! is_string($hostname) || $hostname === '') {
            return null;
        }

        $rootDomain = strtolower((string) config('forgebase.root_domain', 'localhost'));
        $suffix = '.'.$rootDomain;

        if (! str_ends_with($hostname, $suffix)) {
            return null;
        }

        $slug = substr($hostname, 0, -strlen($suffix));

        if ($slug === '' || str_contains($slug, '.')) {
            return null;
        }

        if (! preg_match('/^[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/', $slug)) {
            return null;
        }

        return $slug;
    }
}
