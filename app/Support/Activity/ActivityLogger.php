<?php

declare(strict_types=1);

namespace App\Support\Activity;

use App\Models\ActivityLog;
use App\Support\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogger
{
    public function log(
        string $action,
        ?Model $subject = null,
        array $properties = [],
        ?Model $actor = null,
        ?int $tenantId = null,
    ): ActivityLog {
        $actor ??= auth()->user();
        $tenantId = $tenantId ?? app(TenantContext::class)->id();

        $request = app()->bound('request') ? app(Request::class) : null;

        return ActivityLog::query()->create([
            'tenant_id' => $tenantId,
            'action' => $action,
            'actor_type' => $actor?->getMorphClass(),
            'actor_id' => $actor?->getKey(),
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'properties' => $properties,
            'ip' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
