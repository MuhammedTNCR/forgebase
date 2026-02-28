<?php

namespace Tests\Feature;

use App\Exceptions\TenantRequiredException;
use App\Http\Middleware\IdentifyTenant;
use App\Models\Project;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class BelongsToTenantTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $acmeTenant;

    protected Tenant $globexTenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->acmeTenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $this->globexTenant = Tenant::query()->create([
            'name' => 'Globex',
            'slug' => 'globex',
        ]);

        DB::table('projects')->insert([
            [
                'tenant_id' => $this->acmeTenant->id,
                'name' => 'Acme Project',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $this->globexTenant->id,
                'name' => 'Globex Project',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Route::domain('{tenant}.'.config('forgebase.root_domain'))
            ->middleware(IdentifyTenant::class)
            ->group(function (): void {
                Route::get('/_tenant-test/projects', function () {
                    return response()->json([
                        'projects' => Project::query()->pluck('name')->all(),
                    ]);
                });

                Route::post('/_tenant-test/projects', function () {
                    $project = Project::query()->create([
                        'name' => 'Created in Tenant',
                    ]);

                    return response()->json([
                        'id' => $project->id,
                        'tenant_id' => $project->tenant_id,
                    ]);
                });
            });

        Route::domain('{tenant}.'.config('forgebase.root_domain'))->get('/_tenant-test/unprotected-project-count', function () {
            return response()->json([
                'count' => Project::query()->count(),
            ]);
        });
    }

    public function test_acme_tenant_cannot_see_globex_projects(): void
    {
        $response = $this->get($this->tenantUrl('acme', '/_tenant-test/projects'));

        $response
            ->assertOk()
            ->assertJson([
                'projects' => ['Acme Project'],
            ]);
    }

    public function test_project_created_under_acme_host_gets_tenant_id_automatically(): void
    {
        $response = $this->post($this->tenantUrl('acme', '/_tenant-test/projects'));

        $response
            ->assertOk()
            ->assertJson([
                'tenant_id' => $this->acmeTenant->id,
            ]);
    }

    public function test_accessing_tenant_owned_model_on_unprotected_tenant_route_fails_fast(): void
    {
        $response = $this->get($this->tenantUrl('acme', '/_tenant-test/unprotected-project-count'));

        $response->assertNotFound();
    }

    public function test_tenant_owned_model_without_context_throws_tenant_required_exception(): void
    {
        $this->expectException(TenantRequiredException::class);

        Project::query()->count();
    }
}
