<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_creating_a_project_logs_activity_with_tenant_context(): void
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

        $projectId = DB::table('projects')->where('name', 'Roadmap')->value('id');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'project.created',
            'subject_type' => Project::class,
            'subject_id' => $projectId,
            'actor_type' => User::class,
            'actor_id' => $owner->id,
            'tenant_id' => $tenant->id,
        ]);
    }

    public function test_updating_a_project_logs_activity_with_diff(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $owner = User::factory()->create();
        $owner->tenants()->attach($tenant->id, ['role' => 'owner']);

        $createResponse = $this->actingAs($owner)
            ->post($this->tenantUrl('acme', '/projects'), [
                'name' => 'Initial Name',
            ]);

        $createResponse->assertRedirect($this->tenantUrl('acme', '/projects'));

        $projectId = DB::table('projects')->where('name', 'Initial Name')->value('id');

        $response = $this->actingAs($owner)
            ->patch($this->tenantUrl('acme', "/projects/{$projectId}"), [
                'name' => 'Updated Name',
            ]);

        $response->assertRedirect($this->tenantUrl('acme', '/projects'));

        $log = ActivityLog::query()
            ->where('action', 'project.updated')
            ->where('subject_id', $projectId)
            ->latest('id')
            ->firstOrFail();

        $this->assertSame($tenant->id, $log->tenant_id);
        $this->assertSame(Project::class, $log->subject_type);
        $this->assertSame($projectId, $log->subject_id);
        $this->assertSame(User::class, $log->actor_type);
        $this->assertSame($owner->id, $log->actor_id);
        $this->assertSame(['name' => 'Updated Name'], $log->properties['changes']);
        $this->assertSame(['name' => 'Initial Name'], $log->properties['before']);
    }

    public function test_deleting_a_project_logs_activity(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $owner = User::factory()->create();
        $owner->tenants()->attach($tenant->id, ['role' => 'owner']);

        $createResponse = $this->actingAs($owner)
            ->post($this->tenantUrl('acme', '/projects'), [
                'name' => 'Legacy Project',
            ]);

        $createResponse->assertRedirect($this->tenantUrl('acme', '/projects'));

        $projectId = DB::table('projects')->where('name', 'Legacy Project')->value('id');

        $response = $this->actingAs($owner)
            ->delete($this->tenantUrl('acme', "/projects/{$projectId}"));

        $response->assertRedirect($this->tenantUrl('acme', '/projects'));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'project.deleted',
            'subject_type' => Project::class,
            'subject_id' => $projectId,
            'actor_type' => User::class,
            'actor_id' => $owner->id,
            'tenant_id' => $tenant->id,
        ]);
    }
}
