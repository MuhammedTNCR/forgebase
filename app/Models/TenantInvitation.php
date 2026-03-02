<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TenantInvitation extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'tenant_id',
        'email',
        'role',
        'token',
        'invited_by_user_id',
        'accepted_by_user_id',
        'accepted_at',
        'expires_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'accepted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('accepted_at')
            ->where(function (Builder $query): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }
}
