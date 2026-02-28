<?php

namespace App\Models\Concerns;

use App\Exceptions\TenantRequiredException;
use App\Support\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder): void {
            $tenantId = app(TenantContext::class)->id();

            if ($tenantId === null) {
                throw TenantRequiredException::forRoute();
            }

            $builder->where($builder->getModel()->qualifyColumn('tenant_id'), $tenantId);
        });

        static::creating(function ($model): void {
            if (! empty($model->tenant_id)) {
                return;
            }

            $tenantId = app(TenantContext::class)->id();

            if ($tenantId === null) {
                throw TenantRequiredException::forRoute();
            }

            $model->tenant_id = $tenantId;
        });
    }
}
