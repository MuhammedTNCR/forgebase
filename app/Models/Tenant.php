<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    /** @use HasFactory<\Database\Factories\TenantFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'plan',
    ];

    public function plan(): string
    {
        $plan = $this->plan ?? config('features.default_plan', 'free');

        return is_string($plan) && $plan !== '' ? strtolower($plan) : 'free';
    }

    /**
     * @return list<string>
     */
    public function features(): array
    {
        $plans = (array) config('features.plans', []);
        $features = $plans[$this->plan()] ?? $plans[config('features.default_plan', 'free')] ?? [];

        return array_values(array_unique(array_map('strval', $features)));
    }

    public function hasFeature(string $feature): bool
    {
        $feature = strtolower(trim($feature));

        if ($feature === '') {
            return false;
        }

        return in_array($feature, $this->features(), true);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_user')
            ->withPivot('role');
    }
}
