<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'tenant_id',
        'actor_type',
        'actor_id',
        'action',
        'subject_type',
        'subject_id',
        'properties',
        'ip',
        'user_agent',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'properties' => 'array',
    ];

    public function actor(): MorphTo
    {
        return $this->morphTo();
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
