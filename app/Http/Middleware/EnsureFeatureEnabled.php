<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $tenant = app(TenantContext::class)->get();

        if (! $tenant) {
            $routeTenant = $request->route('tenant');

            if ($routeTenant instanceof Tenant) {
                $tenant = $routeTenant;
            } elseif (is_numeric($routeTenant)) {
                $tenant = Tenant::query()->find($routeTenant);
            }
        }

        if (! $tenant || ! method_exists($tenant, 'hasFeature')) {
            abort(404, 'Tenant context is required for this route.');
        }

        if (! $tenant->hasFeature($feature)) {
            abort(403, 'This feature is not enabled for your plan.');
        }

        return $next($request);
    }
}
