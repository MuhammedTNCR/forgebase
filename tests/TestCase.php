<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function centralUrl(string $path = '/'): string
    {
        $domain = config('forgebase.central_subdomain').'.'.config('forgebase.root_domain');

        return 'http://'.$domain.'/'.ltrim($path, '/');
    }

    protected function tenantUrl(string $slug, string $path = '/'): string
    {
        $domain = $slug.'.'.config('forgebase.root_domain');

        return 'http://'.$domain.'/'.ltrim($path, '/');
    }
}
