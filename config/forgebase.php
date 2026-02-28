<?php

return [
    'root_domain' => env('FORGEBASE_ROOT_DOMAIN', '127.0.0.1.nip.io'),

    'central_subdomain' => env('FORGEBASE_CENTRAL_SUBDOMAIN', 'app'),

    'reserved_subdomains' => explode(',', env('FORGEBASE_RESERVED_SUBDOMAINS', 'app,www,admin,api')),

    'tenant_cache_ttl' => (int) env('FORGEBASE_TENANT_CACHE_TTL', 30),

    'cache_key_prefix' => env('FORGEBASE_TENANT_CACHE_KEY_PREFIX', 'forgebase:tenant:slug:'),
];
