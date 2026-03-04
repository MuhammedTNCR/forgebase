<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class FeatureGatingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_free_plan_cannot_access_team_invites(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Globex',
            'slug' => 'globex',
            'plan' => 'free',
        ]);

        $owner = User::factory()->create();
        $owner->tenants()->attach($tenant->id, ['role' => 'owner']);

        $response = $this->actingAs($owner)
            ->get($this->centralUrl("/workspaces/{$tenant->id}/team"));

        $response->assertForbidden();
    }

    public function test_pro_plan_can_access_team_invites(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
            'plan' => 'pro',
        ]);

        $owner = User::factory()->create();
        $owner->tenants()->attach($tenant->id, ['role' => 'owner']);

        $response = $this->actingAs($owner)
            ->get($this->centralUrl("/workspaces/{$tenant->id}/team"));

        $response->assertOk();
    }

    public function test_free_plan_can_access_projects_when_enabled(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Globex',
            'slug' => 'globex',
            'plan' => 'free',
        ]);

        $owner = User::factory()->create();
        $owner->tenants()->attach($tenant->id, ['role' => 'owner']);

        $response = $this->actingAs($owner)
            ->get($this->tenantUrl('globex', '/projects'));

        $response->assertOk();
    }

    public function test_has_feature_uses_config(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
            'plan' => 'pro',
        ]);

        $this->assertTrue($tenant->hasFeature('projects'));
        $this->assertTrue($tenant->hasFeature('team_invites'));
        $this->assertFalse($tenant->hasFeature('export_csv'));
    }
}
