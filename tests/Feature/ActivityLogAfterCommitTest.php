<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Project;
use App\Support\Teams\CreateTenantInvitationAction;
use App\Support\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ActivityLogAfterCommitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_project_log_not_written_on_rollback(): void
    {
        Queue::fake();

        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
            'plan' => 'pro',
        ]);

        $owner = User::factory()->create();
        $owner->tenants()->attach($tenant->id, ['role' => 'owner']);

        app(TenantContext::class)->set($tenant);
        auth()->login($owner);

        try {
            DB::transaction(function (): void {
                Project::query()->create([
                    'name' => 'Rollback Project',
                ]);

                throw new \RuntimeException('rollback');
            });
        } catch (\RuntimeException) {
            // expected
        }

        $this->assertDatabaseMissing('activity_logs', [
            'action' => 'project.created',
        ]);

        Queue::assertNothingPushed();
    }

    public function test_project_log_written_after_commit(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
            'plan' => 'pro',
        ]);

        $owner = User::factory()->create();
        $owner->tenants()->attach($tenant->id, ['role' => 'owner']);

        app(TenantContext::class)->set($tenant);
        auth()->login($owner);

        DB::transaction(function (): void {
            Project::query()->create([
                'name' => 'Committed Project',
            ]);
        });

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'project.created',
            'tenant_id' => $tenant->id,
        ]);
    }

    public function test_invitation_log_not_written_on_rollback(): void
    {
        Queue::fake();
        Mail::fake();

        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
            'plan' => 'pro',
        ]);

        $owner = User::factory()->create();
        $owner->tenants()->attach($tenant->id, ['role' => 'owner']);

        try {
            DB::transaction(function () use ($tenant, $owner): void {
                $action = app(CreateTenantInvitationAction::class);
                $action->execute($tenant, 'invitee@example.com', 'member', $owner);

                throw new \RuntimeException('rollback');
            });
        } catch (\RuntimeException) {
            // expected
        }

        $this->assertDatabaseMissing('activity_logs', [
            'action' => 'team.invited',
        ]);

        Queue::assertNothingPushed();
    }
}
