<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProjectAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_member_cannot_delete_project(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $member = User::factory()->create();
        $member->tenants()->attach($tenant->id, ['role' => 'member']);

        $projectId = DB::table('projects')->insertGetId([
            'tenant_id' => $tenant->id,
            'name' => 'Protected Project',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($member)
            ->delete($this->tenantUrl('acme', "/projects/{$projectId}"));

        $response->assertForbidden();
        $this->assertDatabaseHas('projects', ['id' => $projectId]);
    }

    public function test_owner_can_create_project_with_automatic_tenant_assignment(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $owner = User::factory()->create();
        $owner->tenants()->attach($tenant->id, ['role' => 'owner']);

        $response = $this->actingAs($owner)
            ->post($this->tenantUrl('acme', '/projects'), [
                'name' => 'Roadmap',
            ]);

        $response->assertRedirect($this->tenantUrl('acme', '/projects'));

        $this->assertDatabaseHas('projects', [
            'name' => 'Roadmap',
            'tenant_id' => $tenant->id,
        ]);
    }
}
