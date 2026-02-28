<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TenantHostEnforcementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();

        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $this->user = User::factory()->create();
        $this->user->tenants()->attach($tenant->id, ['role' => 'member']);
    }

    public function test_root_on_app_subdomain_returns_ok(): void
    {
        $response = $this->get($this->centralUrl('/'));

        $response->assertOk();
    }

    public function test_root_on_existing_tenant_subdomain_returns_ok(): void
    {
        $response = $this->actingAs($this->user)->get($this->tenantUrl('acme', '/projects'));

        $response->assertOk();
    }

    public function test_root_on_unknown_tenant_subdomain_returns_not_found(): void
    {
        $response = $this->actingAs($this->user)->get($this->tenantUrl('acm', '/projects'));

        $response->assertNotFound();
    }
}
